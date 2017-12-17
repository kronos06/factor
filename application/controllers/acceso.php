<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acceso extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('usuariomodel', 'um');
	}
	public function index()
	{
		$this->load->view('acceso/index', array(
			'empresas' => $this->um->Empresas()
		));
	}
	public function logout()
	{
		$this->session->unset_userdata('usuario');
		redirect('');
	}
	public function ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		switch($action)
		{
			case 'Acceder':
				print_r(
					json_encode(
						$this->um->Acceder(
							$this->input->post('Empresa_id'),
							$this->input->post('Usuario'),
							$this->input->post('Contrasena')
						)
					));
				break;
		}
	}
	
	
	
	
	

}