<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCityRequest;
use App\Jobs\OpenWeatherMapRequestJob;
use App\Jobs\TomorrowIoRequestJob;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCityRequest $request)
    {
        $user = Auth::user();
        $requestData = $request->validated();

        // Do not create the same city
        $city  = City::updateOrCreate([
            'name' => $requestData['name'],
        ], [
            'lat' =>  $requestData['lat'],
            'lng' =>  $requestData['lng'],
        ]);

        //Update user city relation if not exist
        if ($user && $city && !$city->users->contains($user)) {
            $city->users()->save($user);
        }

        //Update weather immediately
        OpenWeatherMapRequestJob::dispatch($city);
        TomorrowIoRequestJob::dispatch($city);

        return response()->json(['status' => 'SUCCESS', 'data' => $city, 'msg' => 'City has been created. Updating weather info... Please wait']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = Auth::user();
        $city = City::find($id);

        if(!$city){
            return response()->json(['status' => 'ERROR', 'msg' => 'City not found']);
        }

        $city->users()->detach($user->id);

        //delete city if it is not attached to any user
        if($city->users->count() == 0){
            $city->delete();
        }

        return response()->json(['status' => 'ERROR', 'msg' => 'City has been deleted']);
    }
}
