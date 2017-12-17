<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ventas extends CI_Controller 
{
	public function __CONSTRUCT()
	{
		parent::__construct();
		$this->load->model('clientemodel', 'clm');
		$this->load->model('monedamodel', 'mm');
		$this->load->model('productomodel', 'pm');
		$this->load->model('usuariomodel', 'um');
		$this->load->model('comprobantemodel', 'cpm');
		$this->load->model('reportemodel', 'rm');
	}
	public function comprobantes()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
				
		$this->load->view('header');
		$this->load->view('ventas/comprobantes', array(
			'tipos'        => $this->cpm->Tipos(),
			'estados'      => $this->cpm->Estados(),
			'pendiente'    => $this->cpm->ImpresionPendiente()    
		));
		$this->load->view('footer');
	}
	public function comprobante($id = 0)
	{
		$c = $id != '' ? $this->cpm->Obtener($id) : null;
		
		$this->load->view('header');
		$this->load->view('ventas/comprobante', array(
			'comprobante'  => $c,
			'tipos'        => $this->cpm->Tipos(),
			'estados'      => $this->cpm->Estados(),
		));
		$this->load->view('footer');
	}
	public function comprobantecrud()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		print_r(json_encode(isset($_POST['id']) ? $this->cpm->Actualizar(SafeRequestParameters($_POST)) : $this->cpm->Registrar(SafeRequestParameters($_POST))));
	}
	public function proforma($id)
	{
		require_once 'application/libraries/dompdf/dompdf_config.inc.php';
		
		$html = $this->load->view('ventas/_proforma', array('comprobante' => $this->cpm->Obtener($id)), true);
		
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		
		$dompdf->stream(date('ymdhis'),array('Attachment'=>0));
	}
	public function impresion($id)
	{
		$this->load->library('EnLetras', 'el');		
		$this->load->view('ventas/impresion', array(
			'comprobante' => $this->cpm->Obtener($id),
			'EnLetras'    => new EnLetras()
		));
	}
	public function reportes()
	{
		// Verificamos si tiene permiso
		if(!$this->menumodel->VerificarAcceso()) redirect('inicio');
		
		$this->load->view('header');
		$this->load->view('ventas/reportes');
		$this->load->view('footer');
	}
	public function ajax($action)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// Productos
		switch($action)
		{
			case 'CargarComprobantes':
				print_r(json_encode($this->cpm->Listar()));
				break;
			case 'DisponibleParaImprimir':
				print_r(json_encode($this->cpm->DisponibleParaImpresion($this->input->post('id'))));
				break;
			case 'Imprimir':
				print_r(json_encode($this->cpm->Imprimir($this->input->post('id'), $this->input->post('f'))));
				break;
			case 'CancelarImpresion':
				print_r(json_encode($this->cpm->CancelarImpresion($this->input->post('id'))));
				break;
			case 'CorregirCorrelativo':
				print_r(json_encode($this->cpm->CorregirCorrelativo($_POST)));
				break;
			case 'Devolver':
				print_r(json_encode($this->cpm->Devolver($_POST)));
				break;
			case 'CargarDetalleParaDevolver':
				$comprobante_id = $this->input->post('comprobante_id');
				echo $this->load->view('ventas/_devolucion', 
					array(
						'comprobante_id' => $comprobante_id,
						'detalle'        => $this->cpm->ObtenerProductosParaDevolucion($comprobante_id)
					), true);
				break;
				break;
			case 'CorrelativoIncorrecto':
				echo $this->load->view('ventas/_CorrelativoIncorrecto', 
					array(
						'correlativo' => $this->input->post('correlativo'),
						'id'          => $this->input->post('id'),
						'tipo'        => $this->input->post('tipo'),
					), true);
				break;
			case 'SubReporte':
				/* SubReporte para el Reporte de Venta Diario */
				if($this->input->post('tipo') == 'reportediariodetalle')
				{
					$reporte = $this->rm->ReporteDiarioDetalle($this->input->post('fecha'));
					
					echo $this->load->view('ventas/subreportes/reportediariodetalle', array(
						'reporte' => $reporte
					), true);
				}
				break;
			case 'Reporte':
				$reporte = null;
				$titulo  = '';
				
				/* Reporte de Venta Diario */
				if($this->input->post('tipo') == '1')
				{
					$reporte = $this->rm->ReporteDiario($this->input->post('m'), $this->input->post('y'));
					$titulo = 'Reporte Diario';
				}
				/* Reporte de Venta Mensual */
				if($this->input->post('tipo') == '2')
				{
					$reporte = $this->rm->ReporteMensual($this->input->post('y'));
					$titulo = 'Reporte Mensual';
				}
				
				/* Reporte de Venta Anual */
				if($this->input->post('tipo') == '3')
				{
 					$reporte = $this->rm->ReporteAnual();
 					$titulo = 'Reporte Anual';
				}
				
				/* Productos mas vendidos */
				if($this->input->post('tipo') == '4')
				{
 					$reporte = $this->rm->ProductosMasVendidos($this->input->post('m'), $this->input->post('y'));
 					$titulo = 'Top de Productos';
				}
				
				/* Mejores Clientes */
				if($this->input->post('tipo') == '5')
				{
 					$reporte = $this->rm->MejoresClientes($this->input->post('m'), $this->input->post('y'));
 					$titulo = 'Top de Clientes';
				}
				
				/* Analisis de Venta por Estacion */
				if($this->input->post('tipo') == '6')
				{
 					$reporte = $this->rm->ProductosRentablesPorTrimestre($this->input->post('y'));
 					$titulo = 'Rentabilidad de Producto Trimestral';
				}
				
				/* Mejores Empleados */
				if($this->input->post('tipo') == '7')
				{
 					$reporte = $this->rm->MejoresEmpleados($this->input->post('m'), $this->input->post('y'));
 					$titulo = 'Top de Empleados';
				}
				
				echo $this->load->view('ventas/_reporte', array(
					'reporte' => $reporte,
					'tipo'    => $this->input->post('tipo'),
					'm'       => $this->input->post('m'),
					'y'       => $this->input->post('y'),
					'titulo'  => $titulo
				), true);
				break;
		}
	}
}