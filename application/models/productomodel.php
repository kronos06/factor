<?php
class ProductoModel extends CI_Model
{
	public function HaSidoAsignada($id)
	{
		$sql = "
			SELECT COUNT(*) Total FROM comprobantedetalle WHERE producto_id = $id AND Tipo = 1
			UNION 
			SELECT COUNT(*) Total FROM almacen WHERE producto_id = $id			
		";

		return ($this->db->query($sql)->row()->Total > 0) ? true : false;
	}
	public function Actualizar($data)
	{
		$id = $data['id'];

		if($this->HaSidoAsignada($id) && isset($data['Marca']))
		{
			$this->db->where('id', $data['id']);
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$p = $this->db->get('producto')->row();

			if($data['Marca'] != $p->Marca)
			{
				$this->responsemodel->SetResponse(false, 'No se puede cambiar la <b>Marca</b> a este producto porque ya ha sido asignada a otro registro.');
				return $this->responsemodel;
			}
			if($data['UnidadMedida_id'] != $p->UnidadMedida_id)
			{
				$this->responsemodel->SetResponse(false, 'No se puede cambiar la <b>Unidad de Medida</b> a este producto porque ya ha sido asignada a otro registro.');
				return $this->responsemodel;
			}
		}
		
		$this->db->where('id', $data['id']);
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->update('producto', $data);
		
		$this->responsemodel->SetResponse(true);
 		return $this->responsemodel;
	}
	public function Registrar($data)
	{
		if(empty($data['Marca'])) $data['Marca'] = 'S/M';
		if(HasModule('stock'))
		{
			if(empty($data['Stock'])) $data['Stock'] = '0.00';
		}
		
		$data['Empresa_id'] = $this->user->Empresa_id;
		$this->db->insert('producto', $data);
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'mantenimiento/producto/' . $this->db->insert_id();
		
		return $this->responsemodel;
	}
	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$this->db->select("*, CONCAT('[', Marca, '] - ',Nombre) NombreCompleto", false);
		return $this->db->get('producto')->row();
	}
	public function Eliminar($id)
	{
		if($this->HaSidoAsignada($id))
		{
			$this->responsemodel->SetResponse(false, 'Este <b>registro</b> no puede ser eliminado.');
		}
		else
		{
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->delete('producto');
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'mantenimiento/productos/';
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
				if($f->field == 'Nombre') $where .= "AND Nombre LIKE '%" . $f->data . "%' ";
				if($f->field == 'Marca')  $where .= "AND Marca LIKE '" . $f->data . "%' ";
				if($f->field == 'UnidadMedida_id' && $f->data != 't')  $where .= "AND UnidadMedida_id = '" . $f->data . "' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM producto')->get()->row()->Total);
		
		$this->db->order_by($this->jqgridmodel->sord);
		$this->db->where($where);
		
		$productos = $this->db->get(
				'producto', 
				$this->jqgridmodel->limit, 
				$this->jqgridmodel->start)->result();
				
		foreach($productos as $p)
		{
			$p->{'MargenGanancia'} = MargenDeGanancia($p->Precio, $p->PrecioCompra);
		}
		
		$this->jqgridmodel->DataSource($productos);
			
		return $this->jqgridmodel;
	}
	public function Buscar($criterio, $servicios = false)
	{
		if(!$servicios)
		{
			$sql = "
				SELECT 
					*, CONCAT('[', Marca, '] - ',Nombre) Nombre,
					Nombre NombreSimple
				FROM producto
				WHERE Nombre LIKE '%$criterio%'
				AND Empresa_id = " . $this->user->Empresa_id . "
				ORDER BY Nombre
				LIMIT 10
			";			
		}else
		{
			$sql = "
				SELECT * FROM (
						SELECT 
							id,UnidadMedida_id,
							PrecioCompra,Precio,Marca,
							CONCAT('[', Marca, '] - ',Nombre) Nombre,
							Stock, 1 Tipo
						FROM producto
						WHERE Empresa_id = " . $this->user->Empresa_id . "
						UNION
						SELECT 
							id,UnidadMedida_id,
							PrecioCompra,Precio,'' Marca,
							CONCAT('[Servicio] - ', Nombre) Nombre,
							0 Stock, 2 Tipo
						FROM servicio
						WHERE Empresa_id = " . $this->user->Empresa_id . "
				) alias
				WHERE Nombre LIKE '%$criterio%'
				ORDER BY Nombre
				LIMIT 10
			";
		}
		return $this->db->query($sql)->result();
	}
	public function Marcas($criterio)
	{
		$sql = "
			SELECT Distinct Marca 
			FROM producto
			WHERE Marca LIKE '%$criterio%'
			AND Empresa_id = " . $this->user->Empresa_id . "
			ORDER BY Marca
			LIMIT 10
		";
		return $this->db->query($sql)->result();
	}
	public function Medidas($criterio)
	{
		$sql = "
			SELECT Distinct UnidadMedida_id 
			FROM producto
			WHERE UnidadMedida_id LIKE '%$criterio%'
			AND Empresa_id = " . $this->user->Empresa_id . "
			ORDER BY UnidadMedida_id
			LIMIT 10
		";
		return $this->db->query($sql)->result();
	}
}