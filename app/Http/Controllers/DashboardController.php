<?php

namespace App\Http\Controllers;

use App\Jobs\GetLatestWeatherJob;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
      GetLatestWeatherJob::dispatch();
        return inertia('Dashboard');
    }
}
