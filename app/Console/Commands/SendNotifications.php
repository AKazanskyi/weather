<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\HighPrecipitationNotification;
use App\Notifications\HighUvNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultUvLimit = config('services.default_weather_limit.uv');
        $defaultPrLimit = config('services.default_weather_limit.precipitation');

        //get users with empty settings
        $usersWithNoSettings = User::has('cities.weather')->doesntHave('settings')->with('cities.weather')->get();

        //get users with enabled notifications
        $usersWithEnabledNotifications = User::has('cities.weather')->with('cities.weather', 'settings')->whereHas('settings', function ($query) {
            $query->whereNull('pause_until')->orWhere('pause_until', '<', Carbon::now());
        })->get();

        foreach ($usersWithNoSettings as $user){
            $cities = $user->cities;
            $uvLimit = $defaultUvLimit;
            $prLimit = $defaultPrLimit;

            $this->sendNotificationByCity($cities, $uvLimit, $prLimit, $user);
        }


        foreach ($usersWithEnabledNotifications as $user){
            $cities = $user->cities;
            $uvLimit = empty($user->settings->max_uv) ? $defaultUvLimit : $user->settings->max_uv;
            $prLimit = empty($user->settings->max_pr) ? $defaultPrLimit : $user->settings->max_pr;

            $this->sendNotificationByCity($cities, $uvLimit, $prLimit, $user);
        }
    }

    private function sendNotificationByCity(Collection $cities, float $uvLimit, float $prLimit, User $user){
        foreach ($cities as $city){

            //send UV notification
            if($city->weather && $city->weather->uv >= $uvLimit){
                $user->notify(new HighUvNotification('uv', $city->weather->uv, $city->name));
                $this->info('Sending uv notification for user '.$user->name.' and city '.$city->name);
            }

            //send Precipitation notification
            if($city->weather && $city->weather->precipitation >= $prLimit){
                $user->notify(new HighPrecipitationNotification('precipitation', $city->weather->precipitation, $city->name));
                $this->info('Sending precipitation notification for user '.$user->name.' and city '.$city->name);
            }
        }
    }

}
