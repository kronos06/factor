<?php
class AlmacenModel extends CI_Model
{
	public function Listar()
	{
		$where = 'a.Empresa_id = ' . $this->user->Empresa_id;
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Tipo' && $f->data != 't') $where .= " AND a.Tipo = " . $f->data . " ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM almacen a')->get()->row()->Total);
		
		$sql = "
			SELECT 
				a.*, 
				u.Nombre UsuarioNombre
			FROM almacen a
			INNER JOIN usuario u
			ON a.Usuario_id = u.id
			WHERE $where 
			ORDER BY " . $this->jqgridmodel->sord . "
			LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->db->where($where);
		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
			
		return $this->jqgridmodel;
	}
	public function Entrada($data)
	{
		$this->db->trans_start();

		$data['Tipo']       = 1;
		$data['Fecha']      = date('Y/m/d');
		$data['Empresa_id'] = $this->user->Empresa_id;
		$data['Usuario_id'] = $this->user->id;

		$last_id = $this->db->insert_id();

		$this->db->where("id", $data['Producto_id']);
		$this->db->set('stock', 'stock+' . $data['Cantidad'], false);
		$this->db->set('PrecioCompra', $data['Precio']);
		$this->db->update('producto');

		$this->db->insert("almacen", $data);

		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'almacen/index';

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
		}
		
		return $this->responsemodel;
	}

	public function Ajustar($data)
	{
		$this->db->where('id', $data['Producto_id']);
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->update('producto', array( 'Stock' => $data['Cantidad']));

		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'almacen/index';
		
		return $this->responsemodel;
	}

	public function Kardex($f1, $f2)
	{
		$where = 'a.Empresa_id = ' . $this->user->Empresa_id;

		if($f2 == '')
		{
			$where .= " AND Fecha = '" . ToDate($f1) . "' ";
		}
		else
		{
			$where .= " AND CAST(Fecha as DATE) BETWEEN CAST('" . ToDate($f1) . "' AS DATE) AND CAST('" . ToDate($f2) . "' AS DATE) ";
		}

		return $this->db->query("
			SELECT 
				a.id, a.Tipo, a.Producto_id, a.ProductoNombre, a.UnidadMedida_id, a.Comprobante_id,
				SUM(a.Cantidad) Cantidad, 
				IF(Tipo = 2, 
					SUM(a.Precio), 
					(SELECT Precio FROM almacen WHERE Producto_id = a.Producto_id ORDER BY id DESC LIMIT 1)) Precio,
				p.Stock
			FROM almacen a
			LEFT JOIN producto p
			ON a.Producto_id = p.id
			LEFT JOIN comprobante c
			ON a.Comprobante_id = c.id
			WHERE Tipo IN (1,2)
			AND $where
			AND (CASE WHEN Tipo = 2 THEN c.Estado = 2 AND c.Correlativo IS NOT NULL ELSE TRUE END)
			GROUP BY Tipo, Producto_id, UnidadMedida_id
			ORDER BY Nombre DESC
		")->result();		
	}

	public function ProductosPorAgotarse()
	{
		$this->db->where('StockMinimo >= Stock');
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->order_by("RAND(), Stock");

		return $this->db->get('producto', 15)->result();
	}

	public function Tipos()
	{
		$this->db->where('relacion', 'almacentipo');
		return $this->db->get('tabladato')->result();
	}
}