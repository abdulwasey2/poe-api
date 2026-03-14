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
            $response = Http::withToken(config('services.poe.key'))
                ->acceptJson()
                ->get(config('services.poe.base_url') . '/v1/models');

            if ($response->successful()) {
                return $response->json('data', []);
            }

            return [];
        });

        return view('poe-models', ['models' => $models]);
    }
}
