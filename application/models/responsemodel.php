<?php
class ResponseModel extends CI_Model
{
	public $result     = null;
	public $response   = false;
	public $message    = 'Ocurrio un error inesperado.';
	public $href       = null;
	
	public $filter     = null;
	
	public function SetResponse($response, $m = '')
	{
		$this->response = $response;
		$this->message = $m;

		if(!$response && $m = '') $this->response = 'Ocurrio un error inesperado';
	}
}