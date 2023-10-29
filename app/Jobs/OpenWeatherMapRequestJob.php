<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Weather;
use App\Utils\OpenWeatherMapConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OpenWeatherMapRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public City $city)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $openmapService = new OpenWeatherMapConnectionService($this->city->lat , $this->city->lng);

        try {
            $result = $openmapService->send();
            if (
                isset($result->current->feels_like) &&
                isset($result->current->uvi) &&
                isset($result->minutely[0]->precipitation)) {

                Weather::updateOrCreate([
                    'service_id' => 1,
                    'city_id' => $this->city->id,
                ], [
                    'uv' => $result->current->uvi,
                    'temperature' => $result->current->feels_like,
                    'precipitation' => $result->minutely[0]->precipitation
                ]);
            }

        } catch (RequestException $e) {
            Log::error('Openmap Service error' . $e);
        }
    }
}
