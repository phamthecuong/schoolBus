<?php

namespace App\Http\Controllers\Backend\School;

use App\Http\Requests\BackEnd\BusRequest;
use App\Models\Bus;
use App\Models\SchoolAdmin;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.bus.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.bus.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BusRequest $request)
    {   
        try
        {
            $school = SchoolAdmin::select('school_id')
                ->where('id', Auth::user()->profile_id)->get();
            foreach ($school as $s)
            {
                $school_id = $s->school_id;
            }
            $bus = new Bus();
            $bus->name = $request->name;
            $bus->school_id = $school_id;
            $bus->save();
            return redirect(url('/school/bus'))->with('warning', trans('bus.addNew_success'));
        }
        catch(\Exception $e)
		{ 
            dd($e);
        }
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
        $buss = Bus::find($id);
        return view('backend.bus.add',['bus' => $buss]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BusRequest $request, $id)
    {
        try
        {
        $bus = Bus::find($id);
        $bus->name = $request->name;
        $bus->save();
        return redirect(url('/school/bus'))->with( 'update', trans('bus.update_success'));
        }
        catch(\Exception $e)
        {
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $trip = Trip::where('bus_id', $id)->get();
            foreach ($trip as $t)
            {
            }
            if (isset($t))
            {
                return redirect(url('/school/bus'))->with('delete_fail', trans('validation.bus.exist'));
            }
            else
            {
                Bus::find($id)->delete();
                DB::commit();
                return redirect(url('/school/bus'));
            }
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
