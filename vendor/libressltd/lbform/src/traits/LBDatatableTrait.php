<?php

namespace LIBRESSLtd\LBForm\Traits;

use Auth;

trait LBDatatableTrait {

    public function scopeToOption($query, $name = "name", $value = "id", $additional_array = array())
    {
        $items = $query->get();
        $array = $additional_array;
        foreach ($items as $item)
        {
            $comps = explode(".", $name);
            $object = $item;
            foreach ($comps as $c)
            {
                $object = $object->$c;
            }
            $array[] = [
                "name" => $object,
                "value" => $item->$value
            ];
        }
        return $array;
    }

    public function scopeDatatable($query, $request)
    {
        $class = get_class($this);
        $total = $class::count();
        $totalFiltered = $query->count();

        $columns = [];
        foreach ($request->columns as $column)
        {
            $data = $column["data"];
            $columns[] = $data;
            if (strlen($column["search"]["value"]) > 0)
            {
                if (strpos($data, ".") === false)
                {
                    $query = $query->where($column["data"], "like", "%".$column["search"]["value"]."%");
                }
                else
                {
                    $comps = explode(".", $data);
                    $last = $comps[count($comps) - 1];
                    $data = substr($data, 0, strlen($data) - strlen($last) - 1);
                    $value = $column["search"]["value"];
                    $query = $query->whereHas($data, function($query) use ($last, $value) {
                        $query->where($last, "like", "%$value%");
                    });
                }
            }
        }
        foreach ($request->order as $order)
        {
            $column_index = $order["column"];
            $column_direction = $order["dir"];
            $data = $request->columns[$column_index]["data"];
            if (strpos($data, ".") === false)
            {
                $query->orderBy($data, $column_direction);
            }
        }
        if ($request->length > 0)
        {
            $data = $query->offset($request->start)->limit($request->length)->get();
        }
        else
        {
            $data = $query->get();
        }

        return ["draw" => $request->draw, "recordsTotal" => $total, "recordsFiltered" => $totalFiltered, "data" => $data];
    }
}