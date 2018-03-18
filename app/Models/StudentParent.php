<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentParent extends Model
{
    protected $table = 'student_parents';

}
