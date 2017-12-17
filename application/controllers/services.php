<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'clm');
		$this->load->model('productomodel', 'pm');
		$this->load->model('serviciomodel', 'sm');
	}
	public function clientes()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->clm->Buscar($this->input->post('criterio'), $this->input->post('tipo'))));		
	}
	public function productosyservicios()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Buscar($this->input->post('criterio'), true)));		
	}
	public function productos()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Buscar($this->input->post('criterio'))));		
	}
	public function marcas()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Marcas($this->input->post('criterio'))));		
	}
	public function medidas()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->pm->Medidas($this->input->post('criterio'))));		
	}
	public function servicios()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode($this->sm->Marcas($this->input->post('criterio'))));		
	}
}