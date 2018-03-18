<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    public function schools()
    {
    	return $this->belongsTo('App\Models\School', 'school_id');
    }

    public function creator()
    {
    	return $this->belongsTo('App\Models\User', 'created_by');
    }
}
