<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Http\Resources\Property as PropertyResource;
use App\Http\Resources\PropertiesCollection;

class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = new PropertiesCollection(Property::paginate(10));
        return response()->json($properties, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate required inputs
        $this->validate(
            $request,
            [
                'streetAddress' => 'required',
                'city'  => 'required',
                'postCode' => 'required',
                'country' => 'required',
                'longitude' => 'nullable|numeric',
                'latitude' => 'nullable|numeric'
            ],
            [
                'latitude.numeric' => 'The latitude must be a valid map coordinate.',
                'longitude.numeric' => 'The longitude must be a valid map coordinate.'
            ]
        );

        // If all inputs are valid, save new record
        $property = new Property();
        $property->street_address = $request->streetAddress;
        $property->city = $request->city;
        $property->post_code = $request->postCode;
        $property->country = $request->country;
        $property->longitude = $request->longitude;
        $property->latitude = $request->latitude;
        $property->save();
        return response()->json([], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        // If id is null, not a number, or a number less than 1, return 422 status
        if (!$id || !is_numeric($id) || $id <= 0) {
            return response()->json([], 422);
        }

        $property = Property::find($id);

        // If no record found, return 404 status
        if (!$property) {
            return response()->json([], 404);
        }

        return response()->json(new PropertyResource($property), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        // If id is null, not a number, or a number less than 1, return 422 status
        if (!$id || !is_numeric($id) || $id <= 0) {
            return response()->json([], 422);
        }

        // If all id given is valid, find in database
        $property = Property::find($id);

        // If no record found, return 404 status
        if (!$property) {
            return response()->json([], 404);
        }

        // Validate required inputs
        $this->validate(
            $request,
            [
                'streetAddress' => 'required',
                'city'  => 'required',
                'postCode' => 'required',
                'country' => 'required',
                'longitude' => 'nullable|numeric',
                'latitude' => 'nullable|numeric'
            ],
            [
                'latitude.numeric' => 'The latitude must be a valid map coordinate.',
                'longitude.numeric' => 'The longitude must be a valid map coordinate.'
            ]
        );

        $property->street_address = $request->streetAddress;
        $property->city = $request->city;
        $property->post_code = $request->postCode;
        $property->country = $request->country;
        $property->longitude = $request->longitude;
        $property->latitude = $request->latitude;
        $property->save();
        return response()->json([], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
