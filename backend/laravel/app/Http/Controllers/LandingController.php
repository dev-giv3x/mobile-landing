<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use App\Support\LandingTemplate;
use Illuminate\Http\JsonResponse;

class LandingController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $landing = Landing::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(LandingTemplate::normalize($landing));
    }
}
