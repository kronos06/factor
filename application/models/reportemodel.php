<?php
class ReporteModel extends CI_Model
{
	public function ReporteDiario($mes, $anio)
	{
		$sql = "
			SELECT 
				*,
				Boleta + Factura + Menudeo Vendido
			FROM 
			(
				SELECT
					FechaEmitido,
					SUM(CASE WHEN ComprobanteTipo_id = 2 THEN Total ELSE 0 END) Boleta,
					SUM(CASE WHEN ComprobanteTipo_id = 3 THEN Total ELSE 0 END) Factura,
					SUM(CASE WHEN ComprobanteTipo_id = 4 THEN Total ELSE 0 END) Menudeo,
					
					SUM(
						CASE 
							WHEN ComprobanteTipo_id = 2 THEN Ganancia
							WHEN ComprobanteTipo_id = 3 THEN Ganancia
							WHEN ComprobanteTipo_id = 4 THEN Ganancia
						END
					) Ganancia
					
				FROM comprobante
				WHERE MONTH(FechaEmitido) = $mes
				AND YEAR(FechaEmitido) = $anio
				AND Empresa_id = " . $this->user->Empresa_id . "
				AND ComprobanteTipo_id IN (2,3,4)
				AND Estado = 2
				AND Correlativo IS NOT NULL
				GROUP BY FechaEmitido
				ORDER BY FechaEmitido DESC
			) alias
		";
		
		$r['Tabla'] = $this->db->query($sql)->result();
		
		// Reporte Grafico
		$r['Grafica'] = array('Categoria' => '', 'Vendido' => '', 'Ganado' => '');		
		$i = 0;
		$x = 0;
		
		for($i = 0; $i <= date('t', strtotime("$anio/$mes/01")); $i++)
		{
			$encontrado = true;
			foreach($r['Tabla'] as $t)
			{
				$d = date('d', strtotime($t->FechaEmitido));
				
				if($i == $d)
				{
					$r['Grafica']['Categoria'] .= "'" . $i . "'" . ($i!=0 ? ',' : '');
					$r['Grafica']['Vendido']   .= $t->Vendido . ($i!=0 ? ',' : '');
					$r['Grafica']['Ganado']    .= $t->Ganancia . ($i!=0 ? ',' : '');
					
					$encontrado = false;
					break;
				}
			}
			
			if($encontrado == true && $i > 0)
			{
				$r['Grafica']['Categoria'] .= $i . ',';
				$r['Grafica']['Vendido']   .= '0' . ',';
				$r['Grafica']['Ganado']    .= '0' . ',';
			}
		}
		
		return $r;
	}
	public function ReporteDiarioDetalle($fecha)
	{
		$sql = "
			SELECT 
				cd.ProductoNombre Nombre,
				CONCAT(Cantidad, ' ', UnidadMedida_id) Cantidad,
				PrecioTotalCompra Ganado,
				PrecioTotal Vendido
			FROM comprobantedetalle cd
			INNER JOIN comprobante c
			ON c.id = cd.Comprobante_id
			WHERE c.FechaEmitido = '$fecha'
			AND ComprobanteTipo_id IN (2,3,4)
			AND Estado = 2
			AND Correlativo IS NOT NULL
			AND Empresa_id = " . $this->user->Empresa_id . "
			ORDER BY ProductoNombre
		";
		
		return $this->db->query($sql)->result();
	}
	public function ReporteMensual($anio)
	{
		$sql = "
			SELECT 
				*,
				Boleta + Factura + Menudeo Vendido
			FROM 
			(
				SELECT
					FechaEmitido,
					SUM(CASE WHEN ComprobanteTipo_id = 2 THEN Total ELSE 0 END) Boleta,
					SUM(CASE WHEN ComprobanteTipo_id = 3 THEN Total ELSE 0 END) Factura,
					SUM(CASE WHEN ComprobanteTipo_id = 4 THEN Total ELSE 0 END) Menudeo,
					
					SUM(
						CASE 
							WHEN ComprobanteTipo_id = 2 THEN Ganancia
							WHEN ComprobanteTipo_id = 3 THEN Ganancia
							WHEN ComprobanteTipo_id = 4 THEN Ganancia
						END
					) Ganancia
					
				FROM comprobante
				WHERE YEAR(FechaEmitido) = $anio
				AND Empresa_id = " . $this->user->Empresa_id . "
				AND ComprobanteTipo_id IN (2,3,4)
				AND Estado = 2
				AND Correlativo IS NOT NULL
				GROUP BY YEAR(FechaEmitido), MONTH(FechaEmitido)
				ORDER BY FechaEmitido DESC
			) alias
		";
		
		$r['Tabla'] = $this->db->query($sql)->result();
		
		// Reporte Grafico
		$r['Grafica'] = array('Categoria' => '', 'Vendido' => '', 'Ganado' => '');		
		$i = 0;
		$x = 0;
		
		for($i = 1; $i <= 12; $i++)
		{
			$encontrado = true;
			foreach($r['Tabla'] as $t)
			{
				$d = date('m', strtotime($t->FechaEmitido));
				
				if($i == $d)
				{
					$r['Grafica']['Categoria'] .= "'" . MonthToSpanish($i, true) . "'" . ($i!=0 ? ',' : '');
					$r['Grafica']['Vendido']   .= $t->Vendido . ($i!=0 ? ',' : '');
					$r['Grafica']['Ganado']    .= $t->Ganancia . ($i!=0 ? ',' : '');
					
					$encontrado = false;
					break;
				}
			}
			
			if($encontrado == true && $i > 0)
			{
				$r['Grafica']['Categoria'] .= "'" . MonthToSpanish($i, true) . "',";
				$r['Grafica']['Vendido']   .= '0' . ',';
				$r['Grafica']['Ganado']    .= '0' . ',';
			}
		}
		
		return $r;
	}
	public function ReporteAnual()
	{
		$sql = "
			SELECT 
				*,
				Boleta + Factura + Menudeo Vendido
			FROM 
			(
				SELECT
					FechaEmitido,
					SUM(CASE WHEN ComprobanteTipo_id = 2 THEN Total ELSE 0 END) Boleta,
					SUM(CASE WHEN ComprobanteTipo_id = 3 THEN Total ELSE 0 END) Factura,
					SUM(CASE WHEN ComprobanteTipo_id = 4 THEN Total ELSE 0 END) Menudeo,
					
					SUM(
						CASE 
							WHEN ComprobanteTipo_id = 2 THEN Ganancia
							WHEN ComprobanteTipo_id = 3 THEN Ganancia
							WHEN ComprobanteTipo_id = 4 THEN Ganancia
						END
					) Ganancia
					
				FROM comprobante
				WHERE Empresa_id = " . $this->user->Empresa_id . "
				AND ComprobanteTipo_id IN (2,3,4)
				AND Estado = 2
				AND Correlativo IS NOT NULL
				GROUP BY YEAR(FechaEmitido)
				ORDER BY FechaEmitido DESC
			) alias
		";
		
		$r['Tabla'] = $this->db->query($sql)->result();
		
		// Reporte Grafico
		$r['Grafica'] = array('Categoria' => '', 'Vendido' => '', 'Ganado' => '');		
		$i = 0;
		$x = 0;
		
		for($i = $this->conf->Anio; $i <= date('Y'); $i++)
		{
			$encontrado = true;
			foreach($r['Tabla'] as $t)
			{
				$d = date('Y', strtotime($t->FechaEmitido));
				
				if($i == $d)
				{
					$r['Grafica']['Categoria'] .= "'" . $i . "'" . ($i!=0 ? ',' : '');
					$r['Grafica']['Vendido']   .= $t->Vendido . ($i!=0 ? ',' : '');
					$r['Grafica']['Ganado']    .= $t->Ganancia . ($i!=0 ? ',' : '');
					
					$encontrado = false;
					break;
				}
			}
			
			if($encontrado == true && $i > 0)
			{
				$r['Grafica']['Categoria'] .= "'" . $i . "',";
				$r['Grafica']['Vendido']   .= '0' . ',';
				$r['Grafica']['Ganado']    .= '0' . ',';
			}
		}
		
		return $r;
	}
	public function ReporteResumenBasico()
	{
		$sql = "
			SELECT
				SUM(CASE WHEN Estado = 2 AND ComprobanteTipo_id IN (2,3,4) AND Correlativo IS NOT NULL THEN Total ELSE 0 END) Vendido,					
				SUM(
					CASE 
						WHEN Estado = 2 AND ComprobanteTipo_id IN (2,3,4) AND Correlativo IS NOT NULL THEN Ganancia
					END
				) Ganado,
				COUNT(
					CASE 
						WHEN Estado = 2 AND ComprobanteTipo_id IN (2,3,4) AND Correlativo IS NOT NULL THEN id
					END
				) Comprobantes,
				(SELECT COUNT(*) FROM cliente WHERE Empresa_id = " . $this->user->Empresa_id . " ) Clientes,
				(SELECT COUNT(*) FROM producto WHERE Empresa_id = " . $this->user->Empresa_id . " ) Productos,
				(SELECT COUNT(*) FROM servicio WHERE Empresa_id = " . $this->user->Empresa_id . " ) Servicios
			FROM comprobante
			WHERE Empresa_id = " . $this->user->Empresa_id . " 
			AND FechaEmitido = '" . date('Y/m/d') . "'";
		
		return $this->db->query($sql)->row();
	}
	public function ProductosMasVendidos($m, $y)
	{
		$sql = "
			SELECT 
				ProductoNombre Nombre,Tipo,
				SUM(Cantidad) Cantidad,
				UnidadMedida_id,
				SUM(cd.PrecioTotal) Vendido,
				SUM(cd.Ganancia) Ganado	
			FROM comprobantedetalle cd
			INNER JOIN comprobante c
			ON c.id = cd.Comprobante_id
			WHERE YEAR(c.FechaEmitido) = $y
			" . ($m > 0 ? " AND MONTH(c.FechaEmitido) = $m" : "") . "
			AND Empresa_id = " . $this->user->Empresa_id . "
			AND ComprobanteTipo_id IN (2,3,4)
			AND Estado = 2
			AND Correlativo IS NOT NULL
			GROUP BY Tipo,Producto_id,UnidadMedida_id
			ORDER BY Cantidad DESC
		";
		
		return $this->db->query($sql)->result();
	}
	public function MejoresClientes($m, $y)
	{
		$sql = "
			SELECT
				COUNT(*) Cantidad,
				ClienteNombre Nombre,			
				SUM(Total) Vendido,
				SUM(Ganancia) Ganado				
			FROM comprobante c
			WHERE ClienteNombre != '' AND YEAR(c.FechaEmitido) = $y
			" . ($m > 0 ? " AND MONTH(c.FechaEmitido) = $m" : "") . "
			AND Empresa_id = " . $this->user->Empresa_id . "
			AND ComprobanteTipo_id IN (2,3,4)
			AND Estado = 2
			AND Correlativo IS NOT NULL
			GROUP BY Cliente_id
			ORDER BY Cantidad DESC
		";
		
		return $this->db->query($sql)->result();
	}
	public function MejoresEmpleados($m, $y)
	{
		$sql = "
			SELECT 
				u.Nombre,
				COUNT(c.id) Cantidad,
				SUM(Total) Vendido,
				SUM(Ganancia) Ganado
			FROM comprobante c
			INNER JOIN usuario u
			ON c.Usuario_id = u.id
			WHERE YEAR(c.FechaEmitido) = $y
			" . ($m > 0 ? " AND MONTH(c.FechaEmitido) = $m" : "") . "
			AND c.Empresa_id = " . $this->user->Empresa_id . "
			AND ComprobanteTipo_id IN (2,3,4)
			AND Estado = 2
			AND Correlativo IS NOT NULL			
			GROUP BY c.Usuario_id
			ORDER BY Cantidad DESC
		";
		
		return $this->db->query($sql)->result();
	}
	public function ProductosRentablesPorTrimestre($year)
	{
		$estaciones = array('1er Trimestre' => array(), '2do Trimestre' => array(), '3er Trimestre' => array(), '4to Trimestre' => array());
		
		$sql = "
			SELECT cd.* FROM comprobantedetalle cd
			INNER JOIN comprobante c
			ON c.id = cd.Comprobante_id
			WHERE cd.Cantidad >= (SELECT AVG(Cantidad) FROM comprobantedetalle cd2 INNER JOIN comprobante c2 ON cd2.Comprobante_Id = c2.id WHERE cd2.UnidadMedida_id = cd.UnidadMedida_id AND MONTH(c2.FechaEmitido) BETWEEN 1 AND 3 AND YEAR(c2.FechaEmitido) = $year)
			AND Empresa_id = " . $this->user->Empresa_id . "
			AND MONTH(FechaEmitido) BETWEEN 1 AND 3
			AND YEAR(FechaEmitido) = $year
			AND (YEAR(CURDATE()) > $year OR (MONTH(CURDATE()) > 3))
			GROUP BY Producto_id, UnidadMedida_id
			ORDER BY Cantidad DESC
		";
		
		$estaciones['1er Trimestre'] = $this->db->query($sql)->result();
		
		$sql = "
			SELECT cd.* FROM comprobantedetalle cd
			INNER JOIN comprobante c
			ON c.id = cd.Comprobante_id
			WHERE cd.Cantidad >= (SELECT AVG(Cantidad) FROM comprobantedetalle cd2 INNER JOIN comprobante c2 ON cd2.Comprobante_Id = c2.id WHERE cd2.UnidadMedida_id = cd.UnidadMedida_id AND MONTH(c2.FechaEmitido) BETWEEN 4 AND 6 AND YEAR(c2.FechaEmitido) = $year)
			AND Empresa_id = " . $this->user->Empresa_id . "
			AND MONTH(FechaEmitido) BETWEEN 4 AND 6
			AND YEAR(FechaEmitido) = $year
			AND (YEAR(CURDATE()) > $year OR (MONTH(CURDATE()) > 6))
			GROUP BY Producto_id, UnidadMedida_id
			ORDER BY Cantidad DESC
		";
		
		$estaciones['2do Trimestre'] = $this->db->query($sql)->result();
		
		$sql = "
			SELECT cd.* FROM comprobantedetalle cd
			INNER JOIN comprobante c
			ON c.id = cd.Comprobante_id
			WHERE cd.Cantidad >= (SELECT AVG(Cantidad) FROM comprobantedetalle cd2 INNER JOIN comprobante c2 ON cd2.Comprobante_Id = c2.id WHERE cd2.UnidadMedida_id = cd.UnidadMedida_id AND MONTH(c2.FechaEmitido) BETWEEN 7 AND 9 AND YEAR(c2.FechaEmitido) = $year)
			AND Empresa_id = " . $this->user->Empresa_id . "
			AND MONTH(FechaEmitido) BETWEEN 7 AND 9
			AND YEAR(FechaEmitido) = $year
			AND (YEAR(CURDATE()) > $year OR (MONTH(CURDATE()) > 9))
			GROUP BY Producto_id, UnidadMedida_id
			ORDER BY Cantidad DESC
		";
		
		$estaciones['3er Trimestre'] = $this->db->query($sql)->result();
		
		$sql = "
			SELECT cd.* FROM comprobantedetalle cd
			INNER JOIN comprobante c
			ON c.id = cd.Comprobante_id
			WHERE cd.Cantidad >= (SELECT AVG(Cantidad) FROM comprobantedetalle cd2 INNER JOIN comprobante c2 ON cd2.Comprobante_Id = c2.id WHERE cd2.UnidadMedida_id = cd.UnidadMedida_id AND MONTH(c2.FechaEmitido) BETWEEN 10 AND 12 AND YEAR(c2.FechaEmitido) = $year)
			AND Empresa_id = " . $this->user->Empresa_id . "
			AND MONTH(FechaEmitido) BETWEEN 10 AND 12
			AND YEAR(FechaEmitido) = $year
			AND (YEAR(CURDATE()) > $year OR (MONTH(CURDATE()) = 12 AND DAY(CURDATE()) = 31))
			GROUP BY Producto_id, UnidadMedida_id
			ORDER BY Cantidad DESC
		";
		
		$estaciones['4to Trimestre'] = $this->db->query($sql)->result();
		
		return (object)$estaciones;
	}
}