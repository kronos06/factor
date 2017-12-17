<?php
class ComprobanteModel extends CI_Model
{
	public function ImpresionPendiente()
	{
		$sql = "
			SELECT COUNT(*) Total FROM comprobante
			WHERE Impresion = 1
			AND Empresa_id = " . $this->user->Empresa_id . "
		";
		
		// Cuando cada usuario maneja su propia impresora
		if($this->conf->Impresion == 2)
		{
			$sql .= " AND UsuarioImprimiendo_id  = " . $this->user->id;
		}
		
		return $this->db->query($sql)->row()->Total == 0 ? true : false;
	}
	public function DisponibleParaImpresion($id)
	{
		$this->db->trans_start();
		
		// Verificamos que no se pueda imprimir la impresion de otro
		$sql = "SELECT 
					UsuarioImprimiendo_id,
					(SELECT Nombre FROM usuario WHERE id = UsuarioImprimiendo_id) Usuario
				FROM comprobante 
				WHERE
				id = $id
				AND Impresion = 1 
				AND UsuarioImprimiendo_id != " . $this->user->id;
		
		$row = $this->db->query($sql)->row();
		
		if(is_object($row))
		{
			$this->responsemodel->message = 'Este comprobante tiene una orden de impresión enviada por ' . $row->Usuario . ', para evitar errores usted no podra realizar esta acción.';
		}
		else
		{
			$sql = "
				SELECT 
					UsuarioImprimiendo_id,
					(SELECT Nombre FROM usuario WHERE id = UsuarioImprimiendo_id) Usuario FROM comprobante
				WHERE Impresion = 1
				AND id != $id
				AND Empresa_id = " . $this->user->Empresa_id . "
			";
			
			$row = $this->db->query($sql)->row();
									
			if(is_object($row))
			{
				if($row->UsuarioImprimiendo_id == $this->user->id)
				{
					$this->responsemodel->message = 'Actualmente usted tiene una impresión pendiente de otro comprobante.';
				}
				else
				{
					$this->responsemodel->message = 'Actualmente la Impresora esta siendo usada por ' . $row->Usuario . '.';					
				}
			}
			else
			{
				// Marcamos como preparando impresion
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$this->db->where('id', $id);
				$this->db->update('comprobante', array(
					'impresion'           => 1,
					'UsuarioImprimiendo_id' => $this->user->id
				));
				
				// Obtenemos la configuracion actual
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$conf = $this->db->get('configuracion')->row_array();
				
				// Obtenemos el correlativo actual
				$this->db->where('id', $id);
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$c = $this->db->get('comprobante')->row_array();
			
				if($c['ComprobanteTipo_id'] == 2)
				{
					$c['Serie']       = $conf['SBoleta'];
					$c['Correlativo'] = str_pad($conf['NBoleta'], $this->conf->Zeros, '0', STR_PAD_LEFT);
				}
				if($c['ComprobanteTipo_id'] == 3)
				{
					$c['Serie']       = $conf['SFactura'];
					$c['Correlativo'] = str_pad($conf['NFactura'], $this->conf->Zeros, '0', STR_PAD_LEFT);
				}
				
				$correlativo = $c['Serie'] . '-' . $c['Correlativo'];
							
				$this->responsemodel->response = true;
				$this->responsemodel->result   = $correlativo;
				$this->responsemodel->message  = "¿El correlativo '$correlativo' es igual al del talonario?";
			}			
		}
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
		return $this->responsemodel;
	}
	public function CorregirCorrelativo($data)
	{
		$this->db->trans_start();
		
		if($data['Razon']=='1')
		{
			if($data['CorrelativoNuevo'][0] != $data['CorrelativoNuevo'][1])
			{
				$this->responsemodel->SetResponse(false);
				$this->responsemodel->message = "Los correlativos ingresados no coinciden. <b>Verifique la confirmación</b>.";
			}
			else
			{
				$correlativo_actual = explode('-', $data['CorrelativoActual']);
				$correlativo_nuevo  = (int)$data['CorrelativoNuevo'][0];
												
				if((int)$correlativo_actual[1] >= (int)$correlativo_nuevo)
				{
					$this->responsemodel->SetResponse(false);
					$this->responsemodel->message = "El <b>correlativo que intenta ingresar</b> debe ser <b>mayor al actual</b>.";					
				}
				else
				{
					// Obtenemos el comprobante actual
					$this->db->where('Empresa_id', $this->user->Empresa_id);
					$this->db->where('id', $data['id']);
					$c = $this->db->get('comprobante')->row_array();
					
					// Actualizamos la configuracion
					$this->db->where('Empresa_id', $this->user->Empresa_id);
					$this->db->update('configuracion', array(
						$c['ComprobanteTipo_id'] == 2 
							? 'NBoleta' : 'NFactura' => $correlativo_nuevo
					));
					
					// Creamos comprobantes para revisar
					$i_comienza = (int)$correlativo_actual[1];
					for($i=$i_comienza; $i < $correlativo_nuevo; $i++)
					{
						$this->db->insert('comprobante', array(
							'Serie'        		 => $correlativo_actual[0],
							'Correlativo'        => str_pad($i, $this->conf->Zeros, '0', STR_PAD_LEFT),
							'ComprobanteTipo_id' => $data['Tipo'],
							'Estado'             => 4,
						    'Glosa'              => $data['Glosa'],
							'FechaEmitido'       => date('d/m/Y'),
							'Empresa_id'         => $this->user->Empresa_id,
							'Usuario_id'         => $this->user->id
						));
					}
					
					$this->responsemodel->SetResponse(true);
					$this->responsemodel->href = 'ventas/impresion/' . $data['id'];
				}
			}
		}else
		{
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
	public function CancelarImpresion($id)
	{
		$this->db->trans_start();
		
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$c = $this->db->get('comprobante')->row();
		
		// Actualizamos el comprobante
		$this->db->where('id', $id);
		$this->db->update('comprobante', array(
											'Impresion' => $c->Correlativo != '' ? 2 : 0,
											'UsuarioImprimiendo_id' => NULL
										));
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->function = 'ImprimirDocumento();';
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
 		return $this->responsemodel;
	}
	public function Imprimir($id, $formato)
	{
		$this->db->trans_start();

		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$c = $this->db->get('comprobante')->row_array();
		
		// Obtenes la configuracion actual
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$conf = $this->db->get('configuracion')->row_array();
		
		// Marcamos como Impreso
		if($c['Impresion'] == 1)
		{
			$c['Impresion'] = 2;
			$c['UsuarioImprimiendo_id'] = NULL;
			$c['Estado'] = $c['Estado'] == 3 ? 3 : 2;

			// Estos NO tienen correlativo
			if($c['Correlativo'] == '')
			{
				// Correlativo
				if($c['ComprobanteTipo_id'] == 2)
				{
					$c['Serie']       = $conf['SBoleta'];
					$c['Correlativo'] = str_pad($conf['NBoleta'], $this->conf->Zeros, '0', STR_PAD_LEFT);
					$conf['NBoleta']++;
				}
				if($c['ComprobanteTipo_id'] == 3)
				{
					$c['Serie']       = $conf['SFactura'];
					$c['Correlativo'] = str_pad($conf['NFactura'], $this->conf->Zeros, '0', STR_PAD_LEFT);
					$conf['NFactura']++;
				}
			}
			
			// Actualizamos el comprobante
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $id);
			$this->db->update('comprobante', $c);
		}

		// Actualizamos el formato de impresion
		if($c['ComprobanteTipo_id'] == 2) $conf['BoletaFormato']  = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);;
		if($c['ComprobanteTipo_id'] == 3) $conf['FacturaFormato'] = str_replace(' background: none repeat scroll 0% 0% transparent;', '', $formato);;
			
		// Actualizamos la configuracion
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->update('configuracion', $conf);
		
		$this->responsemodel->SetResponse(true);
		
		$this->db->trans_complete();
		 
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);	
		}
		
 		return $this->responsemodel;
	}
	public function Actualizar($data)
	{
		$this->db->trans_start();
		
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $data['id']);
		$c = $this->db->get('comprobante')->row();
		
		if($data['Estado'] == 3) // Si queremos anular
		{
			$devolucion = 0;
			// Marcamos si tiene pendiente de anulación
			if(HasModule('stock') && ($c->ComprobanteTipo_id == 2 || $c->ComprobanteTipo_id == 3 || $c->ComprobanteTipo_id == 4))
			{
				if($this->db->query("SELECT COUNT(*) Total FROM comprobantedetalle WHERE Tipo = 1 AND comprobante_id = " . $data['id'])->row()->Total == 0) // No hay productos para devolver
				{
					$devolucion = 1;
				}
			}

			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->where('id', $data['id']);
			$this->db->update('comprobante', array('Estado' => 3, 'Devolucion' => $devolucion));
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href = 'self';
		}
		else if($c->Estado == 4) // Modo Revision
		{
			// Detalle
			$items = 0;
			
			foreach($data['Producto_id'] as $id)
			{
				if($id!='') $items++;
			}
			
			if($items == 0)
			{
				$this->responsemodel->message = 'El comprobante debe tener un item por lo menos.';
				$this->responsemodel->function = 'ComboEstadoDefault();';
			}
			else
			{
				$total  = 0;
				$totalC = 0;
				
				// Detalle
				$detalle = array();
				for($i = 0; $i < count($data['Producto_id']); $i++)
				{
					if($data['Producto_id'][$i] != '')
					{
						$detalle[] = array(
							'tipo'                  => $data['Tipo'][$i], // 1 Producto 2 Servicio
							'Producto_id'           => $data['Producto_id'][$i],
							'ProductoNombre'        => $data['ProductoNombre'][$i],
							'UnidadMedida_id' 	    => $data['UnidadMedida_id'][$i],
							'Cantidad'              => $data['Cantidad'][$i],
							'PrecioUnitarioCompra'  => $data['PrecioUnitarioCompra'][$i],
							'PrecioTotalCompra'     => $data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i],
							'PrecioUnitario'        => $data['PrecioUnitario'][$i],
							'PrecioTotal'           => $data['PrecioUnitario'][$i] * $data['Cantidad'][$i],
							'Ganancia'              => ($data['PrecioUnitario'][$i] * $data['Cantidad'][$i]) - ($data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i]),
							'Comprobante_id'        => $c->id
						);
	
						$total  += $data['PrecioUnitario'][$i] * $data['Cantidad'][$i];
						$totalC += $data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i];
					}
				}

				$iva      = $c->ComprobanteTipo_id == 3 ? $data['Iva'] : 0;
				$SubTotal = $c->ComprobanteTipo_id == 3 ? $total / ($iva / 100 + 1) : 0;
				$IvaTotal = $c->ComprobanteTipo_id == 3 ? $total - $SubTotal : 0;

				// Actualizamos el Comprobante
				$cabecera = array(
					'Cliente_id'         => $data['Cliente_id'] != '' ? $data['Cliente_id'] : 0,
					'ClienteIdentidad'   => $data['ClienteIdentidad'],
					'ClienteNombre'      => $data['ClienteNombre'],
					'ClienteDireccion'   => $data['ClienteDireccion'],
					'Estado'             => $data['Estado'],
					'FechaEmitido'       => ToDate($data['FechaEmitido']),
					'Iva'                => $iva,
					'IvaTotal'           => $IvaTotal,
					'SubTotal'           => $SubTotal,
					'Total'              => $total,
					'TotalCompra'        => $totalC,
					'Usuario_id'         => $this->user->id,
					'Glosa'              => $data['Glosa'],
					'Ganancia'           => $total - $totalC,
					'FechaRegistro'      => date('Y/m/d')
				);

				// Actualizamos el comprobante
				$this->db->where('Empresa_id', $this->user->Empresa_id);
				$this->db->where('id', $data['id']);
				$this->db->update('comprobante', $cabecera);

				// Agregamos el detalle
				$this->db->where('comprobante_id', $data['id']);
				$this->db->delete('comprobantedetalle');

				// Registramos el stock
				if(HasModule('stock') && ($c->ComprobanteTipo_id == 2 || $c->ComprobanteTipo_id == 3 || $c->ComprobanteTipo_id == 4))
				{
					foreach($detalle as $d)
					{
						if($d['tipo'] == 1) // Solos los que sean productos
						{
							// Obtenemos el producto
							$this->db->where('id', $d['Producto_id']);
							$p = $this->db->get('producto')->row();

							// Vemos si hay el stock necesario
							$this->db->where('id', $d['Producto_id']);
							if(($p->Stock - $d['Cantidad']) >= 0) 
							{
								$this->db->set('stock', 'stock - ' . $d['Cantidad'], FALSE);
								$this->db->update('producto');
							}						
							else
							{
								$this->db->update('producto', array('Stock' => 0));
							}

							// Guardamos en el almacen
							$this->db->insert('almacen', array(
								'Tipo'            => 2,
								'Usuario_id'      => $this->user->id,
								'Producto_id'     => $d['Producto_id'],
								'ProductoNombre'  => $d['ProductoNombre'],
								'UnidadMedida_id' => $d['UnidadMedida_id'],
								'Cantidad'        => $d['Cantidad'],
								'Fecha'           => date('Y/m/d'),
								'Empresa_id'      => $this->user->Empresa_id,
								'Comprobante_id'  => $d['Comprobante_id'],
								'Precio'          => $d['PrecioTotal']
							));
						}
					}
				}

				foreach($detalle as $k => $d) $detalle[$k]['Comprobante_id'] = $data['id'];
				$this->db->insert_batch('comprobantedetalle', $detalle);

				$this->responsemodel->SetResponse(true);
				$this->responsemodel->href = 'self';
			}
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
			$this->responsemodel->href = null;
			$this->responsemodel->function = 'ComboEstadoDefault();';
		}
		
 		return $this->responsemodel;
	}
	public function Registrar($data)
	{
		$this->db->trans_start();

		$total  = 0;
		$totalC = 0;
		
		// Detalle
		$items = 0;
		
		foreach($data['Producto_id'] as $id)
		{
			if($id!='') $items++;
		}
		
		if($items == 0)
		{
			$this->responsemodel->message = 'El comprobante debe tener <b>un item</b> por lo menos.';
		}
		else
		{
			for($i = 0; $i < count($data['Producto_id']); $i++)
			{
				if($data['Producto_id'][$i] != '')
				{
					$detalle[] = array(
						'tipo'                  => $data['Tipo'][$i], // 1 Producto 2 Servicio
						'Producto_id'           => $data['Producto_id'][$i],
						'ProductoNombre'        => $data['ProductoNombre'][$i],
						'UnidadMedida_id' 	    => $data['UnidadMedida_id'][$i],
						'Cantidad'              => $data['Cantidad'][$i],
						'PrecioUnitarioCompra'  => $data['PrecioUnitarioCompra'][$i],
						'PrecioTotalCompra'     => $data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i],
						'PrecioUnitario'        => $data['PrecioUnitario'][$i],
						'PrecioTotal'           => $data['PrecioUnitario'][$i] * $data['Cantidad'][$i],
						'Ganancia'              => ($data['PrecioUnitario'][$i] * $data['Cantidad'][$i]) - ($data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i]),
					);
	
					$total  += $data['PrecioUnitario'][$i] * $data['Cantidad'][$i];
					$totalC += $data['PrecioUnitarioCompra'][$i] * $data['Cantidad'][$i];
				}
			}


			$iva = $data['ComprobanteTipo_id'] == 3 ? $data['Iva'] : 0;
			$SubTotal = $data['ComprobanteTipo_id'] == 3 ? $total / ($iva / 100 + 1) : 0;
			$IvaTotal = $data['ComprobanteTipo_id'] == 3 ? $total - $SubTotal : 0;
			
			// Actualizamos el Comprobante
			$cabecera = array(
				'ComprobanteTipo_id' => $data['ComprobanteTipo_id'],
				'Cliente_id'         => $data['Cliente_id'] != '' ? $data['Cliente_id'] : 0,
				'ClienteIdentidad'   => $data['ClienteIdentidad'],
				'ClienteNombre'      => $data['ClienteNombre'],
				'ClienteDireccion'   => $data['ClienteDireccion'],
				'Estado'             => 2,
				'FechaEmitido'       => ToDate($data['FechaEmitido']),
				'Iva'                => $iva,
				'IvaTotal'           => $IvaTotal,
				'SubTotal'           => $SubTotal,
				'Total'              => $total,
				'TotalCompra'        => $totalC,
				'Usuario_id'         => $this->user->id,
				'Glosa'              => $data['Glosa'],
				'Ganancia'           => $total - $totalC,
				'FechaRegistro'      => date('Y/m/d'),
				'Empresa_id'         => $this->user->Empresa_id
			);
			
			// Asignamos los correlativo al menudeo
			$cabecera['Serie']       = null;
			$cabecera['Correlativo'] = null;
			if($data['ComprobanteTipo_id'] != 2 && $data['ComprobanteTipo_id'] != 3)
			{
				$t = $this->db->query("SELECT MAX(Correlativo) + 1 Total FROM comprobante WHERE Empresa_id = " . $this->user->Empresa_id . " AND ComprobanteTipo_id = " . $data['ComprobanteTipo_id'])
							  ->row()->Total;
							  
				$cabecera['Serie']       = null;
				$cabecera['Correlativo'] = str_pad($t == NULL ? 1 : $t, $this->conf->Zeros, '0', STR_PAD_LEFT);
			}

			// Insertamos el comprobante
			$this->db->insert('comprobante', $cabecera);
			$last_id = $this->db->insert_id();
			
			// Agregamos el detalle
			foreach($detalle as $k => $d) $detalle[$k]['Comprobante_id'] = $last_id;
			$this->db->insert_batch('comprobantedetalle', $detalle);

			// Registramos el stock
			if(HasModule('stock') && ($data['ComprobanteTipo_id'] == 2 || $data['ComprobanteTipo_id'] == 3 || $data['ComprobanteTipo_id'] == 4))
			{
				foreach($detalle as $d)
				{
					if($d['tipo'] == 1) // Solos los que sean productos
					{
						// Obtenemos el producto
						$this->db->where('id', $d['Producto_id']);
						$p = $this->db->get('producto')->row();

						// Vemos si hay el stock necesario
						$this->db->where('id', $d['Producto_id']);
						if(($p->Stock - $d['Cantidad']) >= 0) 
						{
							$this->db->set('stock', 'stock - ' . $d['Cantidad'], FALSE);
							$this->db->update('producto');
						}						
						else
						{
							$this->db->update('producto', array('Stock' => 0));
						}

						// Guardamos en el almacen
						$this->db->insert('almacen', array(
							'Tipo'            => 2,
							'Usuario_id'      => $this->user->id,
							'Producto_id'     => $d['Producto_id'],
							'ProductoNombre'  => $d['ProductoNombre'],
							'UnidadMedida_id' => $d['UnidadMedida_id'],
							'Cantidad'        => $d['Cantidad'],
							'Fecha'           => date('Y/m/d'),
							'Empresa_id'      => $this->user->Empresa_id,
							'Comprobante_id'  => $d['Comprobante_id'],
							'Precio'          => $d['PrecioTotal']
						));
					}
				}
			}
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'ventas/comprobante/' . $last_id;
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
			$this->responsemodel->href = null;
		}
		
		return $this->responsemodel;
	}
	public function Obtener($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$c = $this->db->get('comprobante')->row();
		
		$this->db->where('comprobante_Id', $id);
		$c->{'Detalle'} = $this->db->get('comprobantedetalle')->result();
		
		$this->db->where('Value', $c->ComprobanteTipo_id);
		$this->db->where('relacion', 'comprobantetipo');
		$c->{'Tipo'} = $this->db->get('tabladato')->row();
		
		return $c;
	}
	public function ObtenerPrueba($tipo)
	{
		$f = date('Y/m/d');
		// Cabecera
		$c = array(
			'id'                  => 0,
			'Empresa_id'          => $this->conf->Empresa_id,
			'Serie'               => '002',
			'Correlativo'         => '00001',
			'Cliente_id'          => 1,
			'ClienteIdentidad'    => '12345678910',
			'ClienteNombre'       => 'Cliente de Prueba',
			'ClienteDireccion'    => 'Dirección de Prueba',
			'ComprobanteTipo_id'  => $tipo,
			'Estado'              => 2,   
			'FechaRegistro'       => $f,
			'FechaEmitido'        => $f,
			'Iva'                 => 18.00,      
			'IvaTotal'            => 180.00,
			'SubTotal'            => 820.00,
			'Total'               => 1000.00,
			'Impresion'           => 1
		);
		
		for($i = 1; $i <= 4; $i++)
		{
			$c['Detalle'][] = (object)array(
				'Tipo'                 => 1,
				'Comprobante_id'       => 0,
				'Producto_id'          => 0,
				'ProductoNombre'       => 'Item ' . $i,
				'UnidadMedida_id'      => 'UND',
				'PrecioUnitario'       => 200.00,
				'PrecioTotal'          => 200.00,
				'Cantidad'             => 1
			);
		}
		
		return (object)$c;
	}
	public function Eliminar($id)
	{
		$this->db->where('Empresa_id', $this->user->Empresa_id);
		$this->db->where('id', $id);
		$this->db->delete('comprobante');

		$this->db->where('comprobante_id', $id);
		$this->db->delete('comprobantedetalle');
		
		$this->responsemodel->SetResponse(true);
		$this->responsemodel->href   = 'ventas';
		
		return $this->responsemodel;
	}
	public function Listar()
	{
		$where = 'c.Empresa_id = ' . $this->user->Empresa_id . ' ';
		$this->filter = isset($_REQUEST['filters']) ? json_decode($_REQUEST['filters']) : null;

		if($this->filter != null)
		{
			foreach($this->filter->{'rules'} as $f)
			{
				if($f->field == 'Serie') $where .= "AND Serie LIKE '" . $f->data . "%' ";
				if($f->field == 'ClienteNombre') $where .= "AND ClienteNombre LIKE '" . $f->data . "%' ";
				if($f->field == 'ComprobanteTipo_id' && $f->data != 't') $where .= "AND ComprobanteTipo_id = '" . $f->data . "' ";
				if($f->field == 'EstadoNombre' && $f->data != 't') $where .= "AND Estado = '" . $f->data . "' ";
				if($f->field == 'FechaEmitido') $where .= "AND FechaEmitido = '" . ToDate($f->data) . "' ";
				if($f->field == 'Iva') $where .= "AND Iva = '" . $f->data . "' ";
				if($f->field == 'SubTotal') $where .= "AND SubTotal = '" . $f->data . "' ";
				if($f->field == 'Total') $where .= "AND Total = '" . $f->data . "' ";
			}
		}

		$this->db->where($where);
		$this->jqgridmodel->Config($this->db->SELECT('COUNT(*) Total FROM comprobante c')->get()->row()->Total);
		
		$sql = "
			SELECT 
				c.id,
				c.ComprobanteTipo_id,				
				IF (SERIE IS NULL, Correlativo, CONCAT(Serie, '-', Correlativo)) Codigo,
				IF (LENGTH(ClienteNombre) = 0, 'Sin Cliente', ClienteNombre) ClienteNombre,
				c.Estado,
				td.Nombre EstadoNombre,
				ct.Nombre Tipo,
				c.FechaEmitido,
				c.Iva,
				c.SubTotal,
				c.Total,
				c.Impresion,
				u.Nombre,
				u.Usuario
			FROM comprobante c
			LEFT JOIN tabladato ct
			ON c.ComprobanteTipo_id = ct.Value
			AND ct.Relacion = 'comprobantetipo'
			INNER JOIN tabladato td
			ON c.Estado = td.Value
			AND td.Relacion = 'comprobanteestado'
			INNER JOIN usuario u
			ON c.Usuario_id = u.id
			WHERE $where
			ORDER BY " . $this->jqgridmodel->sord . "
			LIMIT " . $this->jqgridmodel->start . "," . $this->jqgridmodel->limit;

		$this->jqgridmodel->DataSource($this->db->query($sql)->result());
		
		foreach($this->jqgridmodel->rows as $d)
		{
			$d->Total = number_format($d->Total, 2);
		}
			
		return $this->jqgridmodel;
	}
	public function Devolver($data)
	{
		$this->db->trans_start();

		$Finalizar = true;

		// Devolvemos productos al almacen
		for($i = 0; $i < count($data['detalle_id']); $i++)
		{
			// Traemos el detalle
			$this->db->where('id', $data['detalle_id'][$i]);
			$d = $this->db->get('comprobantedetalle')->row();

			// Verificamos si la cantidad a devolver es realmente la que disponemos
			if((float)$data['detalle_devuelto'][$i] <= $d->Cantidad)
			{
				// Actualizamos el comprobantedetalle
				$this->db->where('id', $d->id);
				$this->db->update('comprobantedetalle', array('Devuelto' => $data['detalle_devuelto'][$i]));

				// Agregamos la devolucion al almacen
				$this->db->insert('almacen', array(
					'Tipo'            => 3,
					'Usuario_id'      => $this->user->id,
					'Producto_id'     => $d->Producto_id,
					'ProductoNombre'  => $d->ProductoNombre,
					'UnidadMedida_id' => $d->UnidadMedida_id,
					'Cantidad'        => $data['detalle_devuelto'][$i],
					'Fecha'           => date('Y/m/d'),
					'Empresa_id'      => $this->user->Empresa_id,
					'Comprobante_id'  => $data['Comprobante_id']
				));

				// Regresamos el stock
				$this->db->where('id', $d->Producto_id);
				$this->db->set('stock', 'stock + ' . $data['detalle_devuelto'][$i], FALSE);
				$this->db->update('producto');
			}
			else if ((float)$data['detalle_devuelto'][$i] > $d->Cantidad)
			{
				$this->responsemodel->SetResponse(false, 'La cantidad a devolver no puede ser mayor a la que tiene actualmente para el producto "' . $d->ProductoNombre . '"');
				$Finalizar = false;
			}
			else if ((float)$data['detalle_devuelto'][$i] < 0)
			{
				$this->responsemodel->SetResponse(false, 'Usted esta intentado devolver cantidades menores a 0 para el producto "' . $d->ProductoNombre . '"');
				$Finalizar = false;
			}
		}

		if($Finalizar)
		{
			// Marcamos diciendo que ya no hay productos para devolver
			$this->db->where('id', $data['Comprobante_id']);
			$this->db->where('Empresa_id', $this->user->Empresa_id);
			$this->db->update('comprobante', array('devolucion' => '1'));
			
			$this->responsemodel->SetResponse(true);
			$this->responsemodel->href   = 'ventas/comprobante/' . $data['Comprobante_id'];
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			log_message('1', __CLASS__ . '->' . __METHOD__);
			$this->responsemodel->SetResponse(false);
			$this->responsemodel->href = null;
		}
		
		return $this->responsemodel;
	}
	public function ObtenerProductosParaDevolucion($comprobante_id)
	{
		$this->db->where('Comprobante_id', $comprobante_id);
		$this->db->where('Tipo', 1);
		return $this->db->get('comprobantedetalle')->result();
	}
	public function Tipos()
	{
		$this->db->where("relacion", 'comprobantetipo');
		return $this->db->get('tabladato')->result();
	}
	public function Estados()
	{
		$this->db->where("relacion", 'comprobanteestado');
		return $this->db->get('tabladato')->result();
	}
}