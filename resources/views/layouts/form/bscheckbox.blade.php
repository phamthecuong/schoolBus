<div class="checkbox">
	<label>
		<?php
	    	$attrs = [];
			if (isset($attribute))
			{
				$attrs = array_merge($attrs, $attribute);
			}
	    ?>
		<input type='hidden' value='0' name='{{ $name }}'>
		{{ Form::checkbox($name, 1, ($value == 1 ? true : false), $attrs) }}
		{{ $title }}
	</label>
</div>
