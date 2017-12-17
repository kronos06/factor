<?php
class ConfiguracionModel extends CI_Model
{
	public function Obtener()
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$c = $this->db->get('configuracion')->row();
		
		$this->db->where('Value', $c->Moneda_id);
		$this->db->where('Relacion', 'moneda');
		$c->{'moneda'} = $this->db->get('tabladato')->row();
		
		return $c;
	}
	public function Actualizar($data)
	{
		
		$this->db->trans_start();
		
		unset($data['Factura']);
		unset($data['Boleta']);
		
		$FConfig = 	array(
			'upload_path'   => './uploads',
			'allowed_types' => 'gif|jpg|png',
			'max_size'      => '2000',
			'max_width'     => '2000',
			'max_height'    => '2000',
			'overwrite'     => true
		);
		
		if(isset($data['Estilo'])) $data['Estilo'] = strtolower($data['Estilo']);
		
		$foto1 = true;
		if(isset($_FILES['Boleta']))
		{
			$FConfig['file_name'] = $this->conf->Empresa_id . '_boleta';
			$this->upload->initialize($FConfig);
			if ( ! $this->upload->do_upload('Boleta') )
			{
				$this->responsemodel->message = $this->upload->display_errors();
				$foto1 = false;
			}
			else
			{
				$upload = $this->upload->data();
				$data['BoletaFoto'] = $upload['file_name'];	
			}
		}
		
		$foto2 = true;
		if(isset($_FILES['Factura']))
		{
			$FConfig['file_name'] = $this->conf->Empresa_id . '_factura';
			$this->upload->initialize($FConfig);
			if ( ! $this->upload->do_upload('Factura') )
			{
				$this->responsemodel->message = $this->upload->display_errors();
				$foto2 = false;
			}
			else
			{
				$upload = $this->upload->data();
				$data['FacturaFoto'] = $upload['file_name'];	
			}
		}
		
		if($foto1 && $foto2)
		{
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->update('configuracion', $data);
			
			$this->responsemodel->SetResponse(true);
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
		}
		
		return $this->responsemodel;
	}
	public function GuardarConfiguracionImpresora($formato, $tipo)
	{
		// Actualizamos el formato de impresion
		$conf = array();
		
		if($tipo == 2) $conf['BoletaFormato']  = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);
		if($tipo == 3) $conf['FacturaFormato'] = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);
			
		// Actualizamos la configuracion
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->update('configuracion', $conf);
		
		$this->responsemodel->SetResponse(true);
		return $this->responsemodel;
	}
}