<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class GoogleMapsHelper
{
    public static function calculateDistance($origin, $destination)
    {
        $apiKey = config('services.google_maps.api_key');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json";
        $response = Http::get($url, [
            'origins' => $origin,
            'destinations' => $destination,
            'key' => $apiKey,
        ]);
        $data = $response->json();

        if ($response->successful() && isset($data['rows'][0]['elements'][0])) {
            $element = $data['rows'][0]['elements'][0];

            if ($element['status'] === 'OK') {
                return [
                    'distance' => floatval($element['distance']['text']) ?? 'N/A',
                    'duration' => $element['duration']['text'] ?? 'N/A',
                ];
            }

            // Si el estado no es "OK"
            return ['distance' => 'N/A', 'duration' => 'N/A', 'error' => $element['status']];
        }

        // Si la respuesta no es exitosa
        return [
            'distance' => 'N/A',
            'duration' => 'N/A',
            'error' => $data['status'] ?? 'UNKNOWN_ERROR',
        ];
    }
}
