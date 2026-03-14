<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PoeModelController extends Controller
{
    public function index()
    {
        $models = Cache::remember('poe_models', now()->addHours(12), function () {
            try {
                $response = Http::withToken(config('services.poe.key'))
                    ->acceptJson()
                    ->get(config('services.poe.base_url') . '/v1/models');

                if ($response->successful()) {
                    return $response->json('data', []);
                }
            } catch (\Exception $e) {
                // Log error but return empty array so page still renders
                Log::error('Failed to fetch models: ' . $e->getMessage());
            }

            return [];
        });

        // Sort models if needed (your view expects $sortedModels)
        $sortedModels = collect($models)->sortBy('id')->values()->all();

        return view('poe-models', [
            'sortedModels' => $sortedModels,
            'error' => empty($models) ? 'Unable to load models' : null
        ]);
    }
}
