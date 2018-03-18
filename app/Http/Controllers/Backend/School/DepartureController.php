<?php

namespace App\Http\Controllers\Backend\School;

use App\Models\SchoolAdmin;
use Illuminate\Http\Request;
use App\Models\Departure;
use App\Http\Requests\BackEnd\DepartureRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
class DepartureController extends Controller
{
    public function getList()
	{	
        $school = SchoolAdmin::select('school_id')
            ->where('id', Auth::user()->profile_id)->get();
        foreach ($school as $s)
        {
            $school_id = $s->school_id;
        }
		$data = Departure::select('id','name','lat','long')
            ->where('school_id', $school_id)
            ->orderBy('id','DESC')->get()->toArray();
		return view('backend.departure.list',compact('data'));
	}

	public function getAdd()
	{
		return view('backend.departure.add');
	}

	public function postAdd(DepartureRequest $departure_request)
	{
		DB::beginTransaction();
		try
		{
            $school = SchoolAdmin::select('school_id')
                ->where('id', Auth::user()->profile_id)->get();
            foreach ($school as $s)
            {
                $school_id = $s->school_id;
            }
			$departure = new Departure();
			$departure->name = $departure_request->txtName;
			$departure->long = $departure_request->lng;
			$departure->lat = $departure_request->lat;
			$departure->school_id = $school_id;
			//$departure_check = Departure::where('school_id',$school_id)->where('long',$departure->long)->where('lat', $departure->lat)->get();
			$departure->created_by = Auth::user()->id;
			//$departure->updated_by = Auth::user()->profile_id;
			$departure->save();
			DB::commit();
			return redirect()->route('backend.departure.list')->with(['flash_level'=>'success','flash_message'=>trans('general.success_add')]);
		}
		catch(\Exception $e)
		{
			DB::rollBack();
			dd($e->getMessage());
		}
	}

	public function getDelete($id)
	{
		$school_id = Auth::user()->profile->school_id;
		$departure = Departure::whereDoesntHave('trips')->where('school_id',$school_id)->find($id);
		if (!isset($departure))
		{	
			return redirect()->route('backend.departure.list')->with(['flash_level'=>'danger','flash_message'=>trans('general.fail_delete')]);
		}
		else 
		{
			$departure->delete($id);
			return redirect()->route('backend.departure.list')->with(['flash_level'=>'success','flash_message'=>trans('general.success_delete')]);
		}

		// $departure = Departure::find($id);
		// $departure->delete($id);
		// return redirect()->route('backend.departure.list')->with(['flash_level'=>'success','flash_message'=>trans('general.success_delete')]);
	}
	

	public function getEdit($id)
	{
		$departure = Departure::find($id);
		return view('backend.departure.edit',compact('departure'));
	}


	public function postEdit($id, DepartureRequest $departure_request)
	{
		try
		{
			$departure = Departure::find($id);
			$departure->name = $departure_request->txtName;
			$departure->lat = $departure_request->lat;
			$departure->long = $departure_request->lng;
			$departure->updated_by = Auth::user()->id;
			$departure->save();
			return redirect()->route('backend.departure.list')->with(['flash_level'=>'success','flash_message'=>trans('general.success_edit')]);
		}
		catch(\Exception $e)
		{
			dd($e);
		}
	}

}
