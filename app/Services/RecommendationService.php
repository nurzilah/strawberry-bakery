<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Cek apakah user memiliki riwayat pembelian.
     */
    public function hasPurchaseHistory(int $userId): bool
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', $userId)
            ->exists();
    }

    /**
     * Bangun matriks user-produk dari riwayat pembelian (order_items).
     * Mengembalikan array 2D: [user_id => [product_id => total_quantity]]
     */
    public function buildUserItemMatrix(): array
    {
        $orderItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('product_reviews', function ($join) {
                $join->on('orders.user_id', '=', 'product_reviews.user_id')
                     ->on('order_items.product_id', '=', 'product_reviews.product_id')
                     ->where('product_reviews.is_approved', true);
            })
            ->select(
                'orders.user_id', 
                'order_items.product_id', 
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('MAX(product_reviews.rating) as user_rating')
            )
            ->whereNotNull('orders.user_id')
            ->whereNotNull('order_items.product_id')
            ->groupBy('orders.user_id', 'order_items.product_id')
            ->get();

        $matrix = [];
        foreach ($orderItems as $item) {
            $weight = (float) $item->total_quantity;
            if ($item->user_rating) {
                // Bobot tambahan: rating 1-5 dikonversi jadi multiplier 1.2 hingga 2.0
                // Rating 5 -> +100% weight, rating 1 -> +20% weight
                $weight = $weight * (1 + ($item->user_rating / 5));
            }
            $matrix[$item->user_id][$item->product_id] = $weight;
        }

        return $matrix;
    }

    /**
     * Algoritma utama: mendapatkan rekomendasi produk untuk target user.
     * Menggunakan User-Based Collaborative Filtering dengan K-Nearest Neighbors (KNN) 
     * dan metrik jarak Cosine Similarity.
     */
    public function getRecommendations(int $targetUserId, int $k = 5, int $limit = 5)
    {
        $matrix = $this->buildUserItemMatrix();

        // Handle Edge Case: Cold Start (User baru belum ada transaksi)
        if (!isset($matrix[$targetUserId]) || empty($matrix[$targetUserId])) {
            return $this->getFallbackRecommendations($limit);
        }

        $targetUserItems = $matrix[$targetUserId];
        $similarities = [];

        // 1. Hitung Cosine Similarity antara Target User dengan semua User lainnya
        foreach ($matrix as $userId => $items) {
            if ($userId === $targetUserId) {
                continue;
            }

            $sim = $this->calculateCosineSimilarity($targetUserItems, $items);
            if ($sim > 0) {
                $similarities[$userId] = $sim;
            }
        }

        // Jika tidak ada user yang mirip (semua similarity 0)
        if (empty($similarities)) {
            return $this->getFallbackRecommendations($limit);
        }

        // 2. Ambil K user paling mirip (KNN) dengan mengurutkan nilai similarity descending
        arsort($similarities);
        $topKUsers = array_slice($similarities, 0, $k, true);

        // 3. Prediksi "Rating" (Quantity) untuk item yang belum dibeli user target
        $predictedRatings = [];
        $similaritySums = [];

        foreach ($topKUsers as $similarUserId => $similarity) {
            foreach ($matrix[$similarUserId] as $productId => $quantity) {
                // Hanya memproses produk yang belum pernah dibeli target user
                if (!isset($targetUserItems[$productId])) {
                    if (!isset($predictedRatings[$productId])) {
                        $predictedRatings[$productId] = 0;
                        $similaritySums[$productId] = 0;
                    }
                    // Weighted sum: similarity * actual rating
                    $predictedRatings[$productId] += $similarity * $quantity;
                    $similaritySums[$productId] += $similarity;
                }
            }
        }

        // 4. Hitung skor akhir dengan normalisasi (membagi total weight dengan total similarity)
        $finalScores = [];
        foreach ($predictedRatings as $productId => $weightedSum) {
            if ($similaritySums[$productId] > 0) {
                $finalScores[$productId] = $weightedSum / $similaritySums[$productId];
            }
        }

        // Urutkan skor tertinggi ke terendah
        arsort($finalScores);

        // Fallback jika tidak ada kandidat produk baru yang didapatkan dari neighbor
        if (empty($finalScores)) {
            return $this->getFallbackRecommendations($limit);
        }

        // Ambil ID produk teratas
        $recommendedProductIds = array_slice(array_keys($finalScores), 0, $limit);
        
        // Ambil objek Product dari DB, pertahankan urutan KNN
        $placeholders = implode(',', array_fill(0, count($recommendedProductIds), '?'));
        return Product::whereIn('id', $recommendedProductIds)
            // ->where('is_active', true) // Jika produk memiliki flag is_active
            ->orderByRaw("FIELD(id, $placeholders)", $recommendedProductIds)
            ->get();
    }

    /**
     * Hitung Cosine Similarity manual antara dua vector user.
     * Rumus: (A dot B) / (||A|| * ||B||)
     */
    private function calculateCosineSimilarity(array $userA, array $userB): float
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        $allProducts = array_unique(array_merge(array_keys($userA), array_keys($userB)));

        foreach ($allProducts as $productId) {
            $valA = $userA[$productId] ?? 0;
            $valB = $userB[$productId] ?? 0;

            $dotProduct += ($valA * $valB);
            $normA += ($valA * $valA);
            $normB += ($valB * $valB);
        }

        if ($normA == 0 || $normB == 0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Cold start fallback: Ambil produk terlaris.
     */
    private function getFallbackRecommendations(int $limit = 5)
    {
        return Product::orderBy('purchase_count', 'desc')
            ->take($limit)
            ->get();
    }

    // =========================================================================
    // METRIK EVALUASI
    // =========================================================================

    /**
     * Precision: Proporsi produk yang direkomendasikan dan benar-benar diklik/dibeli.
     * Rumus: Total Rekomendasi Terklik / Total Semua Rekomendasi
     */
    public function calculatePrecision(): float
    {
        $totalRecommended = DB::table('recommendation_logs')->count();
        if ($totalRecommended === 0) return 0.0;

        $totalClicked = DB::table('recommendation_logs')->where('clicked', true)->count();
        return ($totalClicked / $totalRecommended) * 100;
    }

    /**
     * Recall: Proporsi produk relevan (dibeli) yang berhasil direkomendasikan.
     * Rumus: Total Dibeli yang Direkomendasikan / Total Semua Dibeli
     */
    public function calculateRecall(int $userId): float
    {
        $purchasedProductIds = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', $userId)
            ->pluck('order_items.product_id')
            ->toArray();

        if (empty($purchasedProductIds)) return 0.0;

        $recommendedAndBought = DB::table('recommendation_logs')
            ->where('user_id', $userId)
            ->whereIn('product_id', $purchasedProductIds)
            ->where('clicked', true)
            ->distinct('product_id')
            ->count('product_id');

        $uniquePurchases = count(array_unique($purchasedProductIds));
        return ($recommendedAndBought / $uniquePurchases) * 100;
    }

    /**
     * Mean Absolute Error (MAE): Rata-rata dari nilai absolut error.
     * MAE = sum(|Prediksi - Aktual|) / n
     */
    public function calculateMAE(int $userId): float
    {
        $matrix = $this->buildUserItemMatrix();
        if (!isset($matrix[$userId])) return 0.0;

        // Untuk mengevaluasi error, kita memprediksi skor item yang sebenarnya SUDAH dibeli user
        // Lalu membandingkan skor prediksi dengan actual quantity
        $actuals = $matrix[$userId];
        $totalError = 0;
        $count = 0;

        // Mendapatkan skor prediksi untuk item yg sudah ada (simulasi hide-one)
        foreach ($actuals as $productId => $actualQty) {
            // Prediksi nilai seolah-olah item ini belum dibeli
            $predictedQty = $this->predictSingleItem($userId, $productId, $matrix);
            
            $totalError += abs($predictedQty - $actualQty);
            $count++;
        }

        return $count > 0 ? ($totalError / $count) : 0.0;
    }

    /**
     * Root Mean Square Error (RMSE): Akar rata-rata kuadrat error.
     * RMSE = sqrt(sum((Prediksi - Aktual)^2) / n)
     */
    public function calculateRMSE(int $userId): float
    {
        $matrix = $this->buildUserItemMatrix();
        if (!isset($matrix[$userId])) return 0.0;

        $actuals = $matrix[$userId];
        $totalSquaredError = 0;
        $count = 0;

        foreach ($actuals as $productId => $actualQty) {
            $predictedQty = $this->predictSingleItem($userId, $productId, $matrix);
            
            $error = $predictedQty - $actualQty;
            $totalSquaredError += ($error * $error);
            $count++;
        }

        return $count > 0 ? sqrt($totalSquaredError / $count) : 0.0;
    }

    /**
     * Helper untuk evaluasi: Prediksi nilai 1 item untuk user tertentu 
     * (mengabaikan item itu sendiri saat menghitung prediksi)
     */
    private function predictSingleItem(int $targetUserId, int $targetProductId, array $matrix, int $k = 5): float
    {
        $targetUserItems = $matrix[$targetUserId];
        unset($targetUserItems[$targetProductId]); // Sembunyikan item ini untuk cross-validation

        $similarities = [];
        foreach ($matrix as $userId => $items) {
            if ($userId === $targetUserId) continue;
            
            $sim = $this->calculateCosineSimilarity($targetUserItems, $items);
            if ($sim > 0) {
                $similarities[$userId] = $sim;
            }
        }

        if (empty($similarities)) return 0.0;

        arsort($similarities);
        $topKUsers = array_slice($similarities, 0, $k, true);

        $predictedRating = 0;
        $similaritySum = 0;

        foreach ($topKUsers as $similarUserId => $similarity) {
            if (isset($matrix[$similarUserId][$targetProductId])) {
                $quantity = $matrix[$similarUserId][$targetProductId];
                $predictedRating += $similarity * $quantity;
                $similaritySum += $similarity;
            }
        }

        return $similaritySum > 0 ? ($predictedRating / $similaritySum) : 0.0;
    }
}
