<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PoeModelController extends Controller
{
    public function index()
    {
        $response = Http::withToken(config('services.poe.key'))
            ->acceptJson()
            ->get(config('services.poe.url'));

        // Initialize with empty collection by default
        $sortedModels = collect([]);

        if ($response->failed()) {
            return view('poe-models', [
                'error' => 'Failed to fetch models from Poe API. Check your API key.',
                'sortedModels' => $sortedModels // Pass the empty variable to prevent crash
            ]);
        }

        $models = $response->json()['data'] ?? [];

        $sortedModels = collect($models)->sortByDesc('created')->values();

        return view('poe-models', compact('sortedModels'));
    }
}
