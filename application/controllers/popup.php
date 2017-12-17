<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Popup extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'clm');
		$this->load->model('productomodel', 'pm');
		$this->load->model('serviciomodel', 'sm');
	}
	public function productoservicio()
	{
		echo $this->load->view('popup/producto', array(
			'tipo'     => $this->input->post('tipo'),
			'producto' => ($this->input->post('tipo') == 1 ? $this->pm->Obtener($this->input->post('id')) : $this->sm->Obtener($this->input->post('id')))
		), true);
	}
}