<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use LIBRESSLtd\DeepPermission\Traits\DPUserModelTrait;
use App\Notifications\MyResetPassword;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use DPUserModelTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_id', 'profile_type', 'facebook_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function devices()
    {
        return $this->belongsToMany('App\Models\Push_device', 'push_user_devices', 'user_id', 'device_id');
    }

    public function conversations()
    {
        return $this->belongsToMany('App\Models\LBM_conversation','LBM_conversation_users', "user_id", "conversation_id");
    }

    public function profile()
    {
        return $this->morphTo();
    }

    public function isSuperAdmin()
    {
        if ($this->profile_type == NULL)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function isAdmin()
    {
        if ($this->profile_type == 'admin')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function isSchoolAdmin()
    {
        if ($this->profile_type == 'school')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isParent()
    {
        if ($this->profile_type == 'parent')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isDriver()
    {
        if ($this->profile_type == 'driver')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isStudent()
    {
        if ($this->profile_type == 'student')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isTeacher()
    {
        if ($this->profile_type == 'teacher')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //scope
    public function scopeLogs($query)
    {
        return $query->with('tripLogs.information', 'tripLogs.information.type', 'tripLogs.information.bus', 'tripLogs.information.drivers');
    }
    
    //get list gender
    static public function allToOption()
    {
        $array = [
            ['name' => trans('gender.male'),'value' => 1],
            ['name' => trans('gender.female') , 'value' => 2]
        ];
        return $array;
    }
    //get list parent
    // static public function listParent()
    // {
    //     $parent  = User::where('profile_type', 3)->get();
    //     foreach ($parent as $row)
    //     {
    //         $parent[] = ['name'=> $row->name, 'value'=> $row->profile_id];
    //     }
    //     return $parent;
    // }

    public function notification($title, $description, $type, $conversation_id)
    {
        foreach ($this->devices as $device)
        {
            $device->send($title, $description, $type, $conversation_id);
        }
    }

    function syncRoleByCodeName($codename)
    {
        $role = \App\Models\Role::where('code', $codename)->first();
        $this->roles()->sync([$role->id]);
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }
}
