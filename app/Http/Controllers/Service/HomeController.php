<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Banner;
use App\Models\TemplateChat;
use App\Models\ContactMessage;
use Auth;
use URL;

class HomeController extends ApiController
{
    public function getNews()
    {
        if (Auth::user()->isParent())
        {
            $students = Auth::user()->profile->students;
            $school_ids = [];
            foreach ($students as $student) {
                $school_id = $student->classes[0]->school_id;
                $school_ids[] = $school_id;
            }
            $news =  News::with('creator.profile', 'schools')
                ->whereIn('school_id', $school_ids)
                ->orWhere(function ($query) {
                    $query->whereNull('school_id');
                })->orderBy('created_at', 'desc')->get()->toArray();
            return $this->transform($news);
        }
        elseif (Auth::user()->isDriver())
        {
            $school_ids = Auth::user()->profile->school_id;
            $news =  News::with('creator.profile', 'schools')
                ->where('school_id', $school_ids)
                ->orWhere(function ($query) {
                    $query->whereNull('school_id');
                })->orderBy('created_at', 'desc')->get()->toArray();
            return $this->transform($news);
        }
    }

    public function getBanner()
    {
    	return Banner::first();
    }

    public function getTemplateChat()
    {
    	$user = Auth::user();
    	if ($user->isParent())
    	{
    		$template_chat = TemplateChat::where('type', 1)->get();
    		return $template_chat;
    	}
    	elseif ($user->isDriver())
    	{
    		$template_chat = TemplateChat::where('type', 2)->get();
    		return $template_chat;
    	}
    }

    function transform($data)
    {
        if (empty($data))
        {
            return $data;
        }
        else
        {
            $result = [];
            foreach ($data as $d) 
            {
                $res = [];
                foreach ($d as $key => $value) 
                {
                    if ($key == 'description')
                    {
                        $res[$key] = $this->formatDescription($value);
                    }
                    else
                    {
                        $res[$key] = $value;
                    }
                }
                $result[] = $res;
            }
            return $result;
        }
    }

    function formatDescription($value)
    {
        $str = str_replace( 'src="/uploads', 'src="'.URL::to('/').'/uploads', $value );
        return $str;
    }
}
