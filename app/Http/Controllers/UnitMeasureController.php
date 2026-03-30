<?php

namespace App\Http\Controllers;

use App\Models\Unit_measure;
use Illuminate\Http\Request;

class UnitMeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $measurements = Unit_measure::all();

        return view('unitMeasure.index',compact('measurements'));
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
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $name= ucwords($request['name']);
        $description = ucwords($request['description']);

        $measure = new Unit_measure();

        $measure->name = $name;
        $measure->description = $description;
        $measure->save();
        return redirect()->route('measurement.index')->with('success','Measurement added successfully');
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
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $name= ucwords($request['name']);
        $description = ucwords($request['description']);

        $measure = Unit_measure::findOrFail($id);

        $measure->name = $name;
        $measure->description = $description;
        $measure->save();
        return redirect()->route('measurement.index')->with('success','Measurement added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $measure = Unit_measure::findOrFail($id);
        $measure->delete();

        return redirect()->route('measurement.index')->with('success','measurement delete');
    }
}
