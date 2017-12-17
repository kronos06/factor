<?php
class MonedaModel extends CI_Model
{
	public function Listar()
	{
		$this->db->order_by('nombre');
		$this->db->where("Relacion", 'moneda');
		return $this->db->get('tabladato')->result();
	}
}