<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Recommendation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Phpml\Math\Distance\Euclidean;

class RecommendationService
{
    private const K = 5;
    private const ALGORITHM = 'user_based_cf_knn_k5';
    private const COLD_START_ALGORITHM = 'popularity_cold_start';
    private const SPARSE_FILL_ALGORITHM = 'popularity_sparse_fill';

    public function forUser(int $userId, int $limit = 6): array
    {
        $limit = max(1, $limit);

        $userRatings = Rating::query()
            ->where('user_id', $userId)
            ->pluck('rating', 'product_id');

        $hasTransactions = Order::query()
            ->where('user_id', $userId)
            ->exists();

        if (! $hasTransactions && $userRatings->isEmpty()) {
            return $this->popularProducts($userId, $limit, self::COLD_START_ALGORITHM);
        }

        $products = Product::query()
            ->with('category')
            ->where('is_available', true)
            ->orderBy('id')
            ->get();

        if ($products->isEmpty()) {
            return [];
        }

        $productIds = $products->pluck('id')->values();
        $ratingMatrix = $this->ratingMatrix();
        $activeVector = $this->ratingVector($userRatings, $productIds);
        $neighbors = $this->nearestNeighbors($userId, $activeVector, $productIds, $ratingMatrix);
        $predictions = $this->predictScores($products, $userRatings->keys(), $neighbors, $ratingMatrix);

        if (count($predictions) < $limit) {
            $predictedProductIds = collect($predictions)
                ->pluck('product.id')
                ->merge($userRatings->keys())
                ->unique()
                ->values();

            $fillers = $this->popularProductRows(
                $limit - count($predictions),
                $predictedProductIds,
                self::SPARSE_FILL_ALGORITHM
            );

            $predictions = array_merge($predictions, $fillers);
        }

        $recommendations = array_slice($predictions, 0, $limit);
        $this->persistRecommendations($userId, $recommendations);

        return $recommendations;
    }

    private function ratingMatrix(): Collection
    {
        return Rating::query()
            ->select(['user_id', 'product_id', 'rating'])
            ->get()
            ->groupBy('user_id')
            ->map(function (EloquentCollection $ratings) {
                return $ratings->pluck('rating', 'product_id');
            });
    }

    private function nearestNeighbors(int $userId, array $activeVector, Collection $productIds, Collection $ratingMatrix): array
    {
        $distance = new Euclidean();
        $neighbors = [];

        foreach ($ratingMatrix as $otherUserId => $ratings) {
            if ((int) $otherUserId === $userId) {
                continue;
            }

            $otherVector = $this->ratingVector($ratings, $productIds);
            $euclideanDistance = $distance->distance($activeVector, $otherVector);
            $similarity = 1 / (1 + $euclideanDistance);

            if ($similarity > 0) {
                $neighbors[] = [
                    'user_id' => (int) $otherUserId,
                    'similarity' => (float) $similarity,
                ];
            }
        }

        usort($neighbors, fn (array $a, array $b) => $b['similarity'] <=> $a['similarity']);

        return array_slice($neighbors, 0, self::K);
    }

    private function predictScores(EloquentCollection $products, Collection $ratedProductIds, array $neighbors, Collection $ratingMatrix): array
    {
        if (empty($neighbors)) {
            return [];
        }

        $ratedLookup = $ratedProductIds
            ->map(fn ($id) => (int) $id)
            ->flip();

        $predictions = [];

        foreach ($products as $product) {
            if ($ratedLookup->has((int) $product->id)) {
                continue;
            }

            $weightedRatingTotal = 0.0;
            $similarityTotal = 0.0;

            foreach ($neighbors as $neighbor) {
                $neighborRatings = $ratingMatrix->get($neighbor['user_id']);
                $rating = $neighborRatings ? $neighborRatings->get($product->id) : null;

                if ($rating === null) {
                    continue;
                }

                $weightedRatingTotal += (float) $neighbor['similarity'] * (float) $rating;
                $similarityTotal += (float) $neighbor['similarity'];
            }

            if ($similarityTotal <= 0) {
                continue;
            }

            $predictions[] = [
                'product' => $product,
                'score' => round($weightedRatingTotal / $similarityTotal, 4),
                'algorithm' => self::ALGORITHM,
            ];
        }

        usort($predictions, fn (array $a, array $b) => $b['score'] <=> $a['score']);

        return $predictions;
    }

    private function ratingVector(Collection $ratings, Collection $productIds): array
    {
        return $productIds
            ->map(fn ($productId) => (float) ($ratings->get($productId) ?? 0))
            ->all();
    }

    private function popularProducts(int $userId, int $limit, string $algorithm): array
    {
        $recommendations = $this->popularProductRows($limit, collect(), $algorithm);
        $this->persistRecommendations($userId, $recommendations);

        return $recommendations;
    }

    private function popularProductRows(int $limit, Collection $excludeProductIds, string $algorithm): array
    {
        if ($limit <= 0) {
            return [];
        }

        return Product::query()
            ->with('category')
            ->where('is_available', true)
            ->when($excludeProductIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $excludeProductIds))
            ->orderByDesc('purchase_count')
            ->orderByDesc('rating')
            ->orderByDesc('reviews_count')
            ->take($limit)
            ->get()
            ->map(fn (Product $product) => [
                'product' => $product,
                'score' => null,
                'algorithm' => $algorithm,
            ])
            ->all();
    }

    private function persistRecommendations(int $userId, array $recommendations): void
    {
        if (empty($recommendations)) {
            return;
        }

        $timestamp = now();

        DB::transaction(function () use ($userId, $recommendations, $timestamp) {
            foreach ($recommendations as $recommendation) {
                Recommendation::query()->create([
                    'user_id' => $userId,
                    'product_id' => $recommendation['product']->id,
                    'score' => $recommendation['score'],
                    'algorithm' => $recommendation['algorithm'] ?? self::ALGORITHM,
                    'generated_at' => $timestamp,
                ]);
            }
        });
    }
}
