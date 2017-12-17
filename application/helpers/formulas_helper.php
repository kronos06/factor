<?php
function MargenDeUtilidad($pv, $pc)
{
	return number_format(($pv - $pc) / $pv * 100 , 2);
}
function MargenDeGanancia($pv, $pc)
{
	return number_format(($pv - $pc) / $pc * 100 , 2);
}