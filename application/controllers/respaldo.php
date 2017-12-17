<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Respaldo extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
        $this->load->model('backupmodel', 'bm');
	}

	public function Index()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('respaldo/index', array(
			'copias' => $this->bm->Listar()
		));
		$this->load->view('footer');		
	}

	public function Respaldar()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		if(IS_DEMO == 1)
		{
			exit(json_encode(array('r' => false, 'message' => 'La versión de prueba no permite realizar copias de seguridad.')));
		}
		
		ini_set('max_execution_time', 600); // 10 Minutos

		echo json_encode($this->bm->Respaldar());
	}
}