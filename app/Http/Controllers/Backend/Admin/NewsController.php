<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\SchoolAdmin;
use Auth;
use App\Http\Requests\BackEnd\NewsRequest;
use DB;
use App\Models\Media;
use App\Models\School;
use App\Models\Classes;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        if ($user->isSchoolAdmin() && isset($user->profile_id))
        {    
            $user_id = Auth::user()->profile_id;
            $school_admin = SchoolAdmin::findOrFail($user_id);
            $id = $school_admin->school_id;
            $news = News::where('school_id',$id)->get();
            return view('backend.news.index',['news'=>$news, 'school_admin' => $school_admin]);
            
        } 
        else if ($user->profile_id == NULL)
        {
            $news = News::where('type',1)->get();
            return view('backend.news.index',['news'=>$news]);
        }
        else
        {
            return view('backend.news.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('backend.news.add');
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        if($user->isSchoolAdmin())
        {
            return view('backend.news.add', ['user' => $user]);    
        }
        else if ($user->isAdmin() || $user->isSuperAdmin())
        {
            return view('backend.news.add');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $news = new News();
            $news->title = $request->title;
            $news->short_description = $request->short_description;
            $news->description = $request->description;
            if ($request->hasFile("image"))              
            {               
                $media = Media::saveFile($request->file("image"));            
                $news->image_id = $media->id;
            } 
            $news->type = 1;
            $id = Auth::user()->id;
            $user = User::findOrFail($id);
            if($user->isSchoolAdmin() && isset($user->profile_id))
            {
                $user_id = Auth::user()->profile_id;
                $school_admin = SchoolAdmin::findOrFail($user_id);
                $news->school_id = $school_admin->school_id;
                $news->type = 0;
            }
            $news->created_by = $id;
            //$news->updated_by = $id;
            
            $news->save();
            DB::commit();
            return redirect(url('admin/news'))->with(['flash_level'=>'success','flash_message'=>trans('general.success_add')]);        
        }
       catch (\Exception $e)
       {
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
        $news = News::findOrFail($id);
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        if($user->isSchoolAdmin())
        {
            return view('backend.news.add', ['user' => $user , 'news'=> $news]);    
        }
        else if ($user->isAdmin() || $user->isSuperAdmin())
        {
            return view('backend.news.add',['news'=>$news]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewsRequest $request, $id)
    {
        DB::beginTransaction();
        try
        {
            $news = News::findOrFail($id);
            $news->title = $request->title;
            $news->short_description = $request->short_description;
            $news->description = $request->description;
            if ($request->hasFile("image"))              
            {               
                $media = Media::saveFile($request->file("image"));            
                $news->image_id = $media->id;
            } 
            $news->updated_by = Auth::user()->id;
            $news->save();

            DB::commit();
            return redirect(url('admin/news'))->with(['flash_level'=>'success','flash_message'=>trans('general.success_edit')]);        
        }
       catch (\Exception $e)
       {
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
        DB::beginTransaction();
        try
        {   
            $news = News::findOrFail($id);
            $media = Media::find($news->image_id);
            $media->delete();
            $news->delete();
            DB::commit();
            return redirect(url('admin/news'))->with(['flash_level'=>'success','flash_message'=>trans('general.success_delete')]);        
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }   
    }
}
