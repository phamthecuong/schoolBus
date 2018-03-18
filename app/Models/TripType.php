<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripType extends Model
{
    protected $table = 'trip_types';

    static function allToOption($has_all = FALSE)
    {
        $data = TripType::get();
        $dataset = array();
        if ($has_all !== FALSE)
        {
            $dataset+= array(
                -1 => $has_all,
            );
        }
        foreach ($data as $r)
        {
            $dataset[$r->id] = array(
                'name' => $r->name,
                'value' => $r->id,
                'selected' => FALSE,
            );
        }
        return $dataset;
    }
}
