<!DOCTYPE html>
<html>
	<head>
		<title>Vista Preliminar</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head>
	<body>
		<table style="width:100%;border:2px solid #d3d3d3;padding:10px;">
			<tr>
				<td style="width:70%;">
					<table style="width:100%;">
						<tr>
							<th style="font-size:28px;"><?php echo $this->conf->RazonSocial; ?></td>
						</tr>
						<tr>
							<td style="text-align:center;"><?php echo $this->conf->Direccion; ?></td>
						</tr>
					</table>				
				</td>
				<td>
					<table style="width:100%;border:1px solid #ddd;">
						<tr>
							<td style="text-align:center;"><b>RUC:</b> <?php echo $this->conf->Ruc; ?></td>
						</tr>
						<tr>
							<th style="font-size:18px;">PROFORMA</th>
						</tr>
						<tr>
							<td style="text-align:center;"><b>Nº</b>: <?php echo $comprobante->Correlativo; ?></td>
						</tr>
					</table>				
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table style="width:100%;">
						<tr>
							<td style="width:100px;height:30px;"><b>Señores:</b></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $comprobante->ClienteNombre; ?></td>
						</tr>
						<tr>
							<td style="width:100px;height:30px;"><b>Dirección:</b></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $comprobante->ClienteDireccion; ?></td>
						</tr>
						<tr>
							<td style="width:100px;height:30px;"><b>Emitido:</b></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $comprobante->FechaEmitido; ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table style="width:100%;">
						<tr>
							<th style="height:30px;text-align:left;border-bottom:1px solid #ddd;">Descripción</th>
							<th style="width:40px;text-align:left;border-bottom:1px solid #ddd;">UND</th>
							<th style="width:40px;text-align:left;border-bottom:1px solid #ddd;">Cantidad</th>
							<th style="width:80px;text-align:right;border-bottom:1px solid #ddd;">P. Unitario</th>
							<th style="width:80px;text-align:right;border-bottom:1px solid #ddd;">Total</th>
						</tr>
						<?php foreach($comprobante->Detalle as $k => $c):?>
						<tr>
							<td style="height:20px;border-bottom:1px solid #ddd;"><?php echo $c->ProductoNombre; ?></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $c->UnidadMedida_id; ?></td>
							<td style="border-bottom:1px solid #ddd;"><?php echo $c->Cantidad; ?></td>
							<td style="text-align:right;border-bottom:1px solid #ddd;"><?php echo number_format($c->PrecioUnitario, 2); ?></td>
							<td style="text-align:right;border-bottom:1px solid #ddd;"><?php echo number_format($c->PrecioUnitario * $c->Cantidad, 2); ?></td>
						</tr>
						<?php endforeach; ?>
						<?php for($i= count($comprobante->Detalle)+1; $i <= $this->conf->Lineas; $i++):?>
						<tr>
							<td style="height:20px;border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
							<td style="border-bottom:1px solid #ddd;"></td>
						</tr>
						<?php endfor; ?>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table style="width:100%;">
						<tr>
							<th style="height:30px;text-align:right;" colspan="4">Total</th>
							<td style="border:1px solid #ddd;text-align:right;width:140px;"><?php echo number_format($comprobante->Total, 2); ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>