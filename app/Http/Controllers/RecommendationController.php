<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Tampilkan halaman rekomendasi untuk customer yang login.
     */
    public function index()
    {
        $user = auth()->user();
        $recommendedProducts = $this->getRecommendedProducts($user->id);

        // Contoh mengambil metrik evaluasi untuk ditampilkan (opsional)
        $precision = $this->recommendationService->calculatePrecision();
        $recall = $this->recommendationService->calculateRecall($user->id);
        $mae = $this->recommendationService->calculateMAE($user->id);
        $rmse = $this->recommendationService->calculateRMSE($user->id);

        return view('recommendations.index', compact('recommendedProducts', 'precision', 'recall', 'mae', 'rmse'));
    }

    /**
     * Panggil service untuk mendapatkan koleksi produk.
     */
    public function getRecommendedProducts(int $userId)
    {
        $products = $this->recommendationService->getRecommendations($userId, 5, 4); // K=5, Limit=4

        // Hapus log rekomendasi lama yang belum diklik untuk mencegah duplikasi
        DB::table('recommendation_logs')
            ->where('user_id', $userId)
            ->where('clicked', false)
            ->delete();
        
        // Simpan log rekomendasi baru
        foreach ($products as $product) {
            DB::table('recommendation_logs')->insert([
                'user_id' => $userId,
                'product_id' => $product->id,
                'recommended_at' => now(),
                'clicked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $products;
    }

    /**
     * Catat interaksi saat customer klik produk rekomendasi.
     */
    public function logClick(Request $request, $productId)
    {
        $user = auth()->user();

        DB::table('recommendation_logs')
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('clicked', false) // Hanya catat yang belum diklik
            ->update([
                'clicked' => true,
                'updated_at' => now(),
            ]);

        // Opsional: Bisa langsung redirect ke produk
        // return redirect()->route('products.show', $productId);
        
        return response()->json(['status' => 'success', 'message' => 'Klik dicatat.']);
    }
}
