<?php //array_debug($reporte); ?>
<table class="table report">
	<tbody>
		<tr>
			<th>Producto</th>
			<th style="width:70px;">Cantidades</th>
			<th style="width:90px;" class="text-right">Vendido</th>
			<th style="width:90px;" class="text-right">Ganado</th>					
		</tr>
		<?php if(count($reporte)==0): ?>
		<tr>
			<th colspan="4" class="text-center">No hay resultados para este criterio</th>
		</tr>
		<?php endif; ?>
		<?php foreach($reporte as $r): ?>
		<tr>
			<th style="font-size:11px;" title="<?php echo $r->Nombre; ?>" ><?php echo strlen($r->Nombre) > 80 ? $r->Nombre . '..' : $r->Nombre; ?></th>
			<td style="font-size:11px;" ><?php echo $r->Cantidad; ?></td>
			<td style="font-size:11px;" class="text-right"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Vendido, 2); ?></td>
			<td style="font-size:11px;" class="text-right"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Ganado, 2); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>