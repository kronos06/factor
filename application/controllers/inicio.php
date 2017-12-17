<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reportemodel', 'rm');
		$this->load->model('almacenmodel', 'am');
	}
	public function index()
	{
		$this->load->view('header');
		$this->load->view('inicio/index', array(
			'resumen'           => $this->rm->ReporteResumenBasico(),
			'ProductosSinStock' => $this->am->ProductosPorAgotarse()
		));
		$this->load->view('footer');
	}
	public function contacto()
	{
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$message = "";
		$message .= "Nombre: " . $_POST['Nombre'] . '<br />';
		$message .= "Email: " . $_POST['Correo'] . '<br />';
		$message .= "Teléfono: " . $_POST['Telefono'] . '<br />';
		$message .= "Comentario: " . $_POST['Comentario'] . '<br />';
		$message .= "Como: " . $_POST['Como'] . '<br />';
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		@mail("hitogoroshi@outlook.com", "Posible cliente de Ventor", $message, $headers);
		
		echo json_encode(array('r' => true, 'function' => 'FinContacto();'));
	}
	}
}