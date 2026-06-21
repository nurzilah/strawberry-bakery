<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Support\Facades\Schema;

class RecommendationService
{
    public function forUser(int $userId, int $k = 5, int $limit = 6)
    {
        // rating user aktif
        $userRatings = Rating::where('user_id', $userId)->pluck('rating', 'product_id');

        // kalau rating masih sedikit, fallback ke produk populer
        if ($userRatings->count() < 2) {
            $fallbackQuery = Product::with('category');
            if (Schema::hasColumn('products', 'purchase_count')) {
                $fallbackQuery->orderByDesc('purchase_count');
            } else {
                $fallbackQuery->latest();
            }

            return $fallbackQuery->take($limit)->get()->map(function ($product) {
                return ['product' => $product, 'score' => null];
            })->all();
        }

        // cari user lain yang pernah merating produk yang sama
        $otherUserIds = Rating::whereIn('product_id', $userRatings->keys())
            ->where('user_id', '!=', $userId)
            ->pluck('user_id')
            ->unique();

        $similarities = [];

        foreach ($otherUserIds as $otherId) {
            $otherRatings = Rating::where('user_id', $otherId)->pluck('rating', 'product_id');
            $sim = $this->cosineSimilarity($userRatings, $otherRatings);
            if ($sim > 0) {
                $similarities[$otherId] = $sim;
            }
        }

        arsort($similarities);
        $neighbors = array_slice($similarities, 0, $k, true); // K terdekat

        $candidates = Product::with('category')->whereNotIn('id', $userRatings->keys())->get();
        $scored = [];

        foreach ($candidates as $product) {
            $num = 0;
            $den = 0;

            foreach ($neighbors as $otherId => $sim) {
                $r = Rating::where('user_id', $otherId)
                    ->where('product_id', $product->id)
                    ->value('rating');

                if ($r) {
                    $num += $sim * $r;
                    $den += $sim;
                }
            }

            if ($den > 0) {
                $pred = $num / $den;
                $scored[] = ['product' => $product, 'score' => $pred];
            }
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($scored, 0, $limit);
    }

    private function cosineSimilarity($a, $b): float
    {
        $common = $a->keys()->intersect($b->keys());
        if ($common->isEmpty()) {
            return 0;
        }

        $dot = 0;
        $na = 0;
        $nb = 0;

        foreach ($common as $pid) {
            $x = $a[$pid];
            $y = $b[$pid];
            $dot += $x * $y;
            $na  += $x * $x;
            $nb  += $y * $y;
        }

        if ($na == 0 || $nb == 0) {
            return 0;
        }

        return $dot / (sqrt($na) * sqrt($nb));
    }
}
