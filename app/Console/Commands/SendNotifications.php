<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        //get all user who will to receive notifications
        $this->getNotifiableUsersIds();
    }

    private function getNotifiableUsersIds()
    {
        //get users with empty settings
        $usersWithNoSettings = User::has('cities.weathers')->doesntHave('settings')->with('cities.weathers')->get();

        //get users with enabled notifications
        $usersWithEnabledNotifications = User::has('cities.weathers')->with('cities.weathers', 'settings')->whereHas('settings', function ($query) {
            $query->whereNull('pause_until')->orWhere('pause_until', '<', Carbon::now());
        })->get();

        $allUsersWhoWillGetNotified = $usersWithNoSettings->merge($usersWithEnabledNotifications);



        return $allUsersWhoWillGetNotified;
    }

}
