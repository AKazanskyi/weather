<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeatherSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $settings = $user->settings;

        return inertia('WeatherSettings/Show', compact('user', 'settings'));
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
    public function store(Request $request)
    {
        $user = Auth::user();
        Setting::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'pause_until' => $request->pause_until,
            'max_uv' => $request->max_uv,
            'max_pr' => $request->max_pr,
        ]);

        return redirect()->route('dashboard')->with('success', 'data has been updated');
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
        $user = Auth::user();
        Setting::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'pause_until' => $request->pause_until,
            'max_uv' => $request->max_uv,
            'max_pr' => $request->max_pr,
        ]);

        return redirect()->back()->with('success', 'data has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
