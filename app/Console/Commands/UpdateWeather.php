<?php

namespace App\Console\Commands;

use App\Jobs\OpenWeatherMapRequestJob;
use App\Jobs\TomorrowIoRequestJob;
use App\Models\City;
use Illuminate\Console\Command;

class UpdateWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current weather for all cities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cities = City::all();

        foreach ($cities as $city){

            $this->info('Updating '.$city->name);

            OpenWeatherMapRequestJob::dispatch($city);

            TomorrowIoRequestJob::dispatch($city);

        }
    }
}
