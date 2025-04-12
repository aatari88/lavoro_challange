<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SunatTipoCambioService
{
    protected string $baseUrl = 'https://api.apis.net.pe/v1/tipo-cambio-sunat';

    public function getByDate($date)
    {
        $response = Http::get("{$this->baseUrl}?fecha={$date}");

        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }
}