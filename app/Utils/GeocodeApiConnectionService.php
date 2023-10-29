<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class GeocodeApiConnectionService
{
    /**
     * @var PendingRequest
     */
    private PendingRequest $client;
    private string $baseUrl = 'https://maps.googleapis.com/maps/api/geocode/json';

    /**
     * Constructor
     */
    public function __construct(public string $ip, public string $query)
    {}

    /**
     * Send Request To Google
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function send(): string
    {
        $googleKey = config('services.google.key');
        $countryCode = $this->getCountry();

        $countryCode = empty($countryCode->country_code2) ? "" : $countryCode->country_code2;
        $address = urlencode($this->query) . ' '.$countryCode;

        $url  = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$googleKey";
        $response = Http::get($url)->throw();

        return $response->body();
    }

    /**
     * Get country code by current IP address to narrow google search results
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    private function getCountry():object{
        $response = Http::get('https://api.iplocation.net/?ip='.$this->ip);
        return json_decode($response->body());
    }
}
