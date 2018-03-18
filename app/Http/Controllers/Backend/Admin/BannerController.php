<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Requests\BackEnd\BannerRequest;
use App\Models\Banner;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banner = Banner::all();
        return view('backend.banner.index', ['banner' => $banner]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banner.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $banner = new Banner();

            if ($request->hasFile("banner"))
                {
                    $media = Media::saveFile($request->file('banner'));
                    $banner->image_id = $media->id;
                }
            $banner->created_by = Auth::user()->id;
            $banner->url = $request->url;
            $banner->save();
            DB::commit();
            return redirect(url('/admin/banner'))->with('warning', trans('bus.addNew_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banner = Banner::find($id);
        return view('backend.banner.add', ['banner' => $banner]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {
            $banner = Banner::find($id);
            if ($request->hasFile("banner"))
            {
                $media = Media::saveFile($request->file('banner'));
                $banner->image_id = $media->id;
            }
            $banner->updated_by = Auth::user()->id;
            $banner->url = $request->url;
            $banner->save();
            DB::commit();
            return redirect(url('/admin/banner'))->with( 'update', trans('bus.update_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
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
        //
    }
}
