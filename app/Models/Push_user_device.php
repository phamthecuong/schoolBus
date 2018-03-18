<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;

class Push_user_device extends Model
{
    use Uuid32ModelTrait;

    public function devices()
    {
    	return $this->hasOne('App\Models\Push_device', 'id', 'device_id');
    }
}
