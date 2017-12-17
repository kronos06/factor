<?php
	$tEntrada = 0;
	$tSalida  = 0;
?>
<table class="table report">
	<thead>
		<tr>
			<th class="bg-none"></th>
			<th colspan="2" class="text-center" style="border-radius:6px 0 0 0;">Entradas</th>
			<th colspan="2" class="text-center" style="border-radius:0 6px 0 0;">Salidas</th>
			<th class="bg-none"></th>
			<th class="bg-none"></th>
		</tr>
		<tr class="sub-legend">
			<th>Item</th>
			<th style="width:100px;" class="text-right">Cantidad</th>
			<th style="width:150px;" class="text-right">Compra</th>
			<th style="width:100px;" class="text-right">Cantidad</th>
			<th style="width:150px;" class="text-right">Venta</th>
			<th style="width:100px;" class="text-right">Stock</th>
			<th class="text-right" style="width:80px;">Destino</th>
		</tr>
	</thead>
	<tbody>
	<?php if(count($kardex)>0): ?>
	<?php foreach($kardex as $k): ?>
		<tr>
			<td><?php echo $k->ProductoNombre; ?></td>
			<td class="text-right bg-earned"><?php echo $k->Tipo == 1 ? $k->Cantidad . ' ' . $k->UnidadMedida_id : ''; ?></td>
			<td class="text-right bg-earned"><?php echo $k->Tipo == 1 ? $this->conf->Moneda_id . ' ' .  number_format($k->Precio, 2) : ''; $tEntrada += ($k->Tipo == 1 ? $k->Precio : 0); ?></td>
			<td class="text-right bg-sold"><?php echo $k->Tipo == 2 ? $k->Cantidad . ' ' . $k->UnidadMedida_id : ''; ?></td>
			<td class="text-right bg-sold"><?php echo $k->Tipo == 2 ? $this->conf->Moneda_id . ' ' .  number_format($k->Precio, 2) : ''; $tSalida += ($k->Tipo == 2 ? $k->Precio : 0); ?></td>
			<td class="text-right"><?php echo $k->Stock . ' ' . $k->UnidadMedida_id; ?></td>
			<td class="text-right">
				<?php if($k->Tipo == 1): ?>
					<i>Entrada</i>
				<?php endif; ?>
				<?php if($k->Tipo == 2): ?>
					<i>Salida</i>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
		<tr>
			<td></td>
			<td></td>
			<td class="text-right bg-earned-total total"><?php echo $this->conf->Moneda_id . ' ' .  number_format($tEntrada, 2); ?></td>
			<td></td>
			<td class="text-right bg-sold-total total"><?php echo $this->conf->Moneda_id . ' ' .  number_format($tSalida, 2); ?></td>
			<td></td>
			<td></td>
		</tr>
	<?php endif; ?>
	<?php if(count($kardex)==0): ?>
		<tr>
			<td colspan="7" class="text-center">No se encontraron registros</td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>