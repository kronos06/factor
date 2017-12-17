<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Almacen extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('almacenmodel', 'am');
		$this->load->model('productomodel', 'pm');
	}
	
	public function Index()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('almacen/index', 
			array(
				'tipos' => $this->am->Tipos()
			)
		);
		$this->load->view('footer');		
	}

	public function Kardex()
	{
		$this->load->view('header');
		$this->load->view('almacen/kardex');
		$this->load->view('footer');		
	}

	public function Entrada($id = 0)
	{
		$datos = array();

		if($id !=0)
			$datos['producto'] = $this->pm->Obtener($id);

		$this->load->view('header');
		$this->load->view('almacen/entrada', $datos);
		$this->load->view('footer');		
	}

	public function EntradaCrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
		print_r(json_encode($this->am->Entrada($_POST)));
	}

	public function Ajustar()
	{
		$this->load->view('header');
		$this->load->view('almacen/entrada');
		$this->load->view('footer');		
	}

	public function AjustarCrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
		print_r(json_encode($this->am->Ajustar($_POST)));
	}

	public function Ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Productos
		switch($action)
		{
			case 'CargarAlmacen':
				print_r(json_encode($this->am->Listar()));
				break;
			case 'CargarKardex':
				echo $this->load->view('almacen/_kardex', 
						array(
							'kardex' => $this->am->Kardex(
													$this->input->post('f1'),
													$this->input->post('f2'))),
							 true);
				break;
		}
	}
}