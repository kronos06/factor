<?php
function jqGrid_searchoptions($data, $value, $display)
{
	$format = 't:Todos;';
	foreach($data as $k => $d)
	{
		$format .= $d->$value . ':' . $d->$display . ($k < count($data) - 1 ? ';' : ''); 
	}
	return $format;
}