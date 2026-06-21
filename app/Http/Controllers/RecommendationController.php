<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;

class RecommendationController extends Controller
{
    public function index(RecommendationService $service)
    {
        $userId = auth()->id();
        $recommendations = $service->forUser($userId);

        return view('profile.recommendations', [
            'recommendations' => $recommendations,
        ]);
    }
}
