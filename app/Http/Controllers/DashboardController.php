<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Utils\GeocodeApiConnectionService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Dashboard page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $user = Auth::user();
        $cities = $user->cities->pluck('id')->toArray();
        $cities = City::whereIn('id', $cities)->has('weather')->with('weather')->get();

        return inertia('Dashboard', compact('cities'));
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
