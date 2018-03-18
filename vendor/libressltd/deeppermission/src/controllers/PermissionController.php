<?php

namespace LIBRESSLtd\DeepPermission\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Models\Permission;
use App\Http\Requests\DeepPermission\CreatePermission;

class PermissionController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("libressltd.deeppermission.permission.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("libressltd.deeppermission.permission.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePermission $request)
    {
        $permission = new Permission;
		$permission->name = $request->name;
		$permission->code = $request->code;
		$permission->permission_group_id = $request->permission_group_id;
		$permission->save();
		
		return redirect(url("permission"))->with('dp_announce', trans('deeppermission.alert.permission.created'));
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
    	$permission = Permission::findOrFail($id);
        return view("libressltd.deeppermission.permission.add", array("permission" => $permission));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePermission $request, $id)
    {
        $permission = Permission::findOrFail($id);
		$permission->name = $request->name;
		$permission->code = $request->code;
		$permission->permission_group_id = $request->permission_group_id;
		$permission->save();
		
		return redirect(url("permission"))->with('dp_announce', trans('deeppermission.alert.permission.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$permission = Permission::findOrFail($id);
		$permission->delete();
		
		return redirect(url("permission"))->with('dp_announce', trans('deeppermission.alert.permission.deleted'));
    }
}
