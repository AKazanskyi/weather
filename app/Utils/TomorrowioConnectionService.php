<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;

class TomorrowioConnectionService
{
    private string $baseUrl = 'https://api.tomorrow.io/v4/weather/realtime';


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
        $apiKey = config('services.tomorrowio.key');

        $url  = $this->baseUrl."?location=$this->lat,$this->lng&apikey=$apiKey";
        $response = Http::get($url)->throw();

        return json_decode($response->body());
    }

}
