<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use App\Models\Parents;
use App\Models\Distance;
use Auth;

class ParentDistanceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $parent_distances = $user->profile->distances;
        $distances = Distance::all();
        return response([
            'data' => $parent_distances,
            'distances' => $distances
        ]);
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
        $this->validate( $request,[
            'distance_id'=>'required|json'
        ]);
        $distance_id = json_decode($request->distance_id);
        $parent_id = Auth::user()->profile_id;
        $parent = Parents::findOrFail($parent_id);
        $parent->distances()->sync($distance_id);
        foreach ($distance_id as $id) 
        {
            $parent->distances()->updateExistingPivot($id, ['created_by' => Auth::user()->id, 'created_at' => date('Y-m-d H:i:s')]);
        }
        return response([
            'description' => 'success'
        ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $distance = Distance::find($id);
        // if ($distance) 
        // {
        //     $parent_id = Auth::user()->profile_id;
        //     $parent = Parents::findOrFail($parent_id);
        //     $parent->distances()->detach($id);
        //     return response([
        //         'description' => 'success'
        //     ]);
        // }
        // else
        // {
        //     $errors = new MessageBag(['distance_id' => ['distance_id is invalid']]);
        //     return $this->errorInvalid($errors);
        // }
    }
}
