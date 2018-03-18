<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distance extends Model
{
    static public function boot()
    {
        Distance::saving(function ($distance) {
        	if (Auth::user())
        	{
	            if ($distance->id)
	            {
	            	$distance->updated_by = Auth::user()->id;
	            }
	            else
	            {
					$distance->created_by = Auth::user()->id;
	            }
	        }
        });
    }
}
