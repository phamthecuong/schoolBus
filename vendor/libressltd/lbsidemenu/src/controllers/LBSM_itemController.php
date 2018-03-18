<?php

namespace LIBRESSLtd\LBSideMenu\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Models\LBSM_item;

class LBSM_itemController extends Controller
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
        $items = LBSM_item::all_ordered();
        return view("libressltd.lbsidemenu.item.index", ["items" => $items]);
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
    public function store(Request $request)
    {
        $item = new LBSM_item;
        $item->fill($request->all());

        if ($request->parent_id != -1)
        {
            $item->parent_id = $request->parent_id;
        }
        else
        {
            $item->parent_id = null;
        }

        $item->save();

        if ($request->roles)
        {
            $item->roles()->sync($request->roles);
        }

        if ($request->permissions)
        {
            $item->permissions()->sync($request->permissions);
        }
		
		return redirect()->back();
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
    	$item = LBSM_item::findOrFail($id);
        return view("libressltd.lbsidemenu.item.add", array("item" => $item));
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
        $item = LBSM_item::findOrFail($id);
        $item->fill($request->all());

        if ($request->parent_id != -1)
        {
            $item->parent_id = $request->parent_id;
        }
        else
        {
            $item->parent_id = null;
        }

        if ($request->roles)
        {
            $item->roles()->sync($request->roles);
        }

        if ($request->permissions)
        {
            $item->permissions()->sync($request->permissions);
        }

        $item->save();
        
        return redirect(url("/lbsm/item"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = LBSM_item::findOrFail($id);
        $item->delete();
        return redirect()->back();
    }

    public function init()
    {
        if (LBSM_item::item_exist("sidemenu_lbsm_item"))
        {
            $item = new LBSM_item;
            $item->name = "lbsm.sidemenu.item.title";
            $item->translated = 1;
        }
    }
}
