<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolAdmin extends Model
{
    protected $table = 'school_admins';

    function users()
    {
    	return $this->morphMany('App\Models\User', 'profile');
    }

    public function avatar()
    {
        return $this->belongsTo('App\Models\Media','avatar_id');
    }

    public function school()
    {
        return $this->belongsTo('App\Models\School','school_id');
    }
}
