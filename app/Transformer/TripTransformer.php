<?php
namespace App\Transformer;

class TripTransformer 
{
	protected $result = [];

	public function result()
    {
        return $this->result;
    }

	function __construct($data)
	{
		$this->result = $this->transform($data);
	}

	function transform($data) 
	{
		// $result = [];
	
		foreach($data as $key => $value)
		{
			$students = $value->students;
			$childrens = [];
			foreach ($students as $s) 
			{
				if (count($s->parents) > 0)
				{
					$childrens[] = $s;
				}
			}
			$data[$key]->childrens = $childrens;
		}
		return $data;
	}
}