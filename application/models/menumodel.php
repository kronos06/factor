<?php
class MenuModel extends CI_Model
{
	public function Listar()
	{
		$sql = "
			SELECT m.* FROM menu m
			INNER JOIN menusuario mu
			ON mu.Menu_id = m.id
			WHERE padre = 0
			AND mu.UsuarioTipo_id = " . $this->user->Tipo . "
			ORDER BY m.orden
		";
		
		$m1 = $this->db->query($sql)->result();

		// Limpiamos los menus que no queremos
		foreach($m1 as $k => $m)
		{
			if(!HasModule('stock') && $m->id == 12)
			{
				unset($m1[$k]);
			}
		}
		
		foreach($m1 as $m)
		{
			$sql = "
				SELECT m.* FROM menu m
				INNER JOIN menusuario mu
				ON mu.Menu_id = m.id
				WHERE padre = " . $m->id . "
				AND mu.UsuarioTipo_id = " . $this->user->Tipo . "
				ORDER BY m.orden
			";
			
			$m->{'Hijos'} = $this->db->query($sql)->result();
		}
		
		return $m1;
	}
	public function VerificarAcceso()
	{
		$sql = "
			SELECT m.* FROM menu m
			INNER JOIN menusuario mu
			ON mu.Menu_id = m.id
			WHERE Url = '" . $this->router->class . "/" . $this->router->method . "'
			AND mu.UsuarioTipo_id = " . $this->user->Tipo . "
		";
		
		return is_object($this->db->query($sql)->row()) 
				? true : false;
	}
}