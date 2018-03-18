<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;

class ParentStatus extends Model
{
	use Uuid32ModelTrait;
    public $timestamps = false;
}
