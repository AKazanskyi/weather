<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\Weather;
use App\Utils\OpenWeatherMapConnectionService;
use App\Utils\TomorrowioConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TomorrowIoRequestJob implements ShouldQueue
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
        $tomorrowioService = new TomorrowioConnectionService($this->city->lat , $this->city->lng);

        try {
            $result = $tomorrowioService->send();
            if (
                isset($result->data->values->uvIndex) &&
                isset($result->data->values->temperature) &&
                isset($result->data->values->precipitationProbability)) {

                Weather::updateOrCreate([
                    'service_id' => 2,
                    'city_id' => $this->city->id,
                ], [
                    'uv' => $result->data->values->uvIndex,
                    'temperature' => $result->data->values->temperature,
                    'precipitation' => $result->data->values->precipitationProbability
                ]);
            }

        } catch (RequestException $e) {
            Log::error('Openmap Service error' . $e);
        }
    }
}
