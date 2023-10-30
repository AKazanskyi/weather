<?php

namespace App\Console\Commands;

use App\Models\AverageWeather;
use App\Models\Weather;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AverageWeatherCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:average-weather-calculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate average weather from different services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $avgweathers = DB::table('weather')
            ->select(DB::raw('city_id, avg(temperature) as avgt, avg(precipitation) as avgp, avg(uv) as avguv'))
            ->groupBy('city_id')->get();

        foreach ($avgweathers as $item){
            AverageWeather::updateOrCreate([
                'city_id' => $item->city_id,
            ], [
                'temperature' => $item->avgt,
                'uv' =>  $item->avguv,
                'precipitation' =>  $item->avgp,
            ]);
        }

    }
}
