<?php

namespace App\Http\Controllers;

use App\Jobs\GetLatestWeatherJob;
use App\Models\City;
use App\Models\Weather;
use App\Utils\GeocodeApiConnectionService;
use App\Utils\OpenWeatherMapConnectionService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Dashboard page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Dashboard');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getGeocodeList(Request $request): string {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->noContent();
        }

        $googleService = new GeocodeApiConnectionService($request->ip() , $query);
        $result = "";

        try {
            $result = $googleService->send();
        } catch (RequestException $e) {
            Log::error('Google Service error' . $e);
        }

        return $result;
    }
}
