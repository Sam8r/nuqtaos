<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{
    public function getRate(string $base, string $target): ?float
    {
        $response = Http::get("https://hexarate.paikama.co/api/rates/{$base}/{$target}/latest");

        if ($response->ok()) {
            return $response->json()['data']['mid'] ?? null;
        }

        return null;
    }
}
