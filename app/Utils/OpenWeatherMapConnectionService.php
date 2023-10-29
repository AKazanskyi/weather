<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

class OpenWeatherMapConnectionService
{
    private string $baseUrl = 'https://api.openweathermap.org/data/3.0/onecall';

    /**
     * Constructor
     */
    public function __construct(public float $lat, public float $lng)
    {}

    /**
     * Send Request To Openmap weather
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function send(): object
    {
        $apiKey = config('services.openweathermap.key');

        $lat = round($this->lat, 2);
        $lon = round($this->lng, 2);

        $url  = $this->baseUrl."?lat=$lat&lon=$lon&exclude=hourly,daily&appid=$apiKey";
        $response = Http::get($url)->throw();

        Log::debug($response);

        return json_decode($response->body());
    }

}
