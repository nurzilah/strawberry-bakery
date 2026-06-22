<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Contracts\View\View;

class RecommendationController extends Controller
{
    public function __construct(private readonly RecommendationService $recommendationService)
    {
    }

    public function index(): View
    {
        $recommendations = $this->recommendationService->forUser(auth()->id(), 6);

        return view('recommendations.index', [
            'recommendations' => $recommendations,
        ]);
    }
}
