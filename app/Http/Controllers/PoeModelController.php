<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PoeModelController extends Controller
{
    public function index()
    {
        // 1. Fetch data from the API
        $response = Http::withToken(config('services.poe.key'))
            ->acceptJson()
            ->get(config('services.poe.url'));

        if ($response->failed()) {
            return view('poe-models', ['error' => 'Failed to fetch models from Poe API.']);
        }

        $models = $response->json()['data'] ?? [];

        // 2. Process and Sort by 'created' (Newest first)
        $sortedModels = collect($models)->sortByDesc('created')->values();

        return view('poe-models', compact('sortedModels'));
    }
}
