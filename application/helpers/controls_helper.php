<?php
function Select($name, $data, $display, $value, $select = null, $empty = false, $extraClass = '', $attr = array())
{
	if(count($data) == 0) return;

	$_attr = '';
	foreach($attr as $k => $a) $_attr .= $k . '="' . $a . '"';

	$html = "<select $_attr name=\"$name\" class=\"form-control required $extraClass\">";
	if($empty) $html .= '<option value="0" '. (!is_null($select) ? "selected='selected'" : "") .'>Sin Selección</option>';
	
	foreach($data as $d)
	{
		if($select != null)
		{
			if($select == $d->$value)
			{
				$html .= '<option selected="selected" value="' . $d->$value . '">' . $d->$display . '</option>';	
			}else
			{
				$html .= '<option value="' . $d->$value . '">' . $d->$display . '</option>';	
			}
		}else
		{
			$html .= '<option value="' . $d->$value . '">' . $d->$display . '</option>';			
		}
	}
	$html .= "</select>";
	return $html;
}

function DateFormat($date, $t)
{
	$_date = explode('/', $date);

	if(count($_date) > 1) $d = new DateTime($_date[2] . '/' . $_date[1] . '/' . $_date[0]);
	else $d = new DateTime($date);
	
	$dia = DayToSpanish($d->Format('w'), true);
	$mes = MonthToSpanish($d->Format('m'), true);
	
	if($t == 1) return $dia . ' ' . $d->format(" d ");
	if($t == 2) return $mes;
	if($t == 3) return $d->format("Y");
	if($t == 4) return $d->format(" d ") . ' de ' . $mes . ' del ' . $d->format('y');
	if($t == 5) return $d->format(" d ") . ' de ' . $mes . ' del ' . $d->format('y') . ', ' . $d->format('h:i:sa');
}
function Months()
{
	return (object)array(
		(object)array(
			'mes'   => 'Enero',
			'valor' => 1
		),
		(object)array(
			'mes'   => 'Febrero',
			'valor' => 2
		),
		(object)array(
			'mes'   => 'Marzo',
			'valor' => 3
		),
		(object)array(
			'mes'   => 'Abril',
			'valor' => 4
		),
		(object)array(
			'mes'   => 'Mayo',
			'valor' => 5
		),
		(object)array(
			'mes'   => 'Junio',
			'valor' => 6
		),
		(object)array(
			'mes'   => 'Julio',
			'valor' => 7
		),
		(object)array(
			'mes'   => 'Agosto',
			'valor' => 8
		),
		(object)array(
			'mes'   => 'Setiembre',
			'valor' => 9
		),
		(object)array(
			'mes'   => 'Octubre',
			'valor' => 10
		),
		(object)array(
			'mes'   => 'Noviembre',
			'valor' => 11
		),
		(object)array(
			'mes'   => 'Diciembre',
			'valor' => 12
		),		
	);
}
function Years($y)
{
	$years = array();
	
	for($i = $y; $i <= date('Y'); $i++)
	{
		$years[] = (object)array(
			'anio' => $i
		);
	}
	
	return (object)$years;
}
function DayToSpanish($x, $short = false )
{
	$dias = array("Domingo","Lunes", "Mártes", "Miercoles", "Jueves", "Viernes", "Sábado");
	
	if(!$short) return $dias[$x];
	else return substr(QuitarTildes($dias[$x]), 0, 3);
}
function MonthToSpanish($x, $short = false ) 
{
	$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$x = (int)$x;
	
	if(!$short) return $meses[$x];
	else return substr($meses[$x], 0, 3);
}
function ToDate($date)
{
	$d = explode('/', $date);
	return $d[2] . '/' . $d[1] . '/' . $d[0];
}
function isRuc($ruc)
{
	if(!is_numeric($ruc)) return false;
	if(strlen($ruc) != '11') return false;
	
	return true;
}
function isDni($ruc)
{
	if(!is_numeric($ruc)) return false;
	if(strlen($ruc) != '8') return false;
	
	return true;
}
function SafeRequestParameters($data, $html = true, $uw = true)
{
	foreach($data as $k => $d)
	{
		if(!is_array($d))
		{
			if($html) $data[$k] = strip_tags($d);
			$data[$k] = trim($d);
			if($uw) $data[$k] = ucwords($d);
		}else
		{
			SafeRequestParameters($d);
		}
	}
	
	return $data;
}
function HasModule($module)
{
	foreach(explode('|', MODULES) as $m)
    {
        if($module == $m) return true;
    }
    
    return false;
}

function QuitarTildes($cadena) {
	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	return $texto;
}