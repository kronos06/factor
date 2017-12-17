<?php
class ServicioModel extends CI_Model
{
	public function Actualizar($data)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $data['id']);
		$this->db->update('servicio', $data);
		
		$this->responsemodel->SetResponse(true);
 		return $this->responsemodel;
	}
	public function Registrar($data)
	{
		$data['Empresa_id'] = $this->user->Empresa_id;
		$this->db->insert('servicio', $data);
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'mantenimiento/servicio/' . $this->db->insert_id();
		
		return $this->responsemodel;
	}
	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		return $this->db->get('servicio')->row();
	}
	public function Eliminar($id)
	{
		$sql = "
			SELECT COUNT(*) Total FROM comprobantedetalle WHERE producto_id = $id AND Tipo = 2
		";

		if($this->db->query($sql)->row()->Total > 0)
		{
			$this->responsemodel->SetResponse(false, 'Este <b>registro</b> no puede ser eliminado.');
		}
		else
		{
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->delete('servicio');
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'mantenimiento/servicios/';			
		}
	
		return $this->responsemodel;

	}
	public function Listar()
	{
		$where = 'Empresa_id = ' . $this->user->Empresa_id . ' ';;
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'id') $where .= "AND id = '" . $f->data . "' ";
				if($f->field == 'Nombre') $where .= "AND Nombre LIKE '" . $f->data . "%' ";
				if($f->field == 'Marca')  $where .= "AND Marca LIKE '" . $f->data . "%' ";
				if($f->field == 'UnidadMedida_id' && $f->data != 't')  $where .= "AND UnidadMedida_id = '" . $f->data . "' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM servicio')->get()->row()->Total);
		
		$this->db->order_by($this->jqgridmodel->sord);
		$this->db->where($where);
		$this->jqgridmodel->DataSource(
			$this->db->get(
				'servicio', 
				$this->jqgridmodel->limit, 
				$this->jqgridmodel->start)->result());
			
		return $this->jqgridmodel;
	}
	public function Buscar($criterio)
	{
		$sql = "
			SELECT * FROM servicio
			WHERE Nombre LIKE '%$criterio%'
			ORDER BY Nombre
			AND Empresa_id = " . $this->user->Empresa_id . "
			LIMIT 0,10
		";
		return $this->db->query($sql)->result();
	}
}