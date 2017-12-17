<?php //echo array_debug($detalle); ?>
<?php echo form_open('ventas/ajax/Devolver', array('class' => 'upd')); ?>
<input type="hidden" name="Comprobante_id" value="<?php echo $comprobante_id; ?>" ?>
<table class="table">
	<thead>
		<th>Producto</th>
		<th class="text-right">CNT</th>
		<th class="text-right">Devolviendo</th>
	</thead>
	<tbody>
	<?php foreach($detalle as $k => $d): ?>
		<tr>
			<td>
				<?php echo $d->ProductoNombre; ?>
			</td>
			<td class="text-right"><?php echo $d->Cantidad; ?></td>
			<td style="width:80px;">
				<input name="detalle_id[]" type="hidden" value="<?php echo $d->id; ?>" />
				<input name="detalle_devuelto[]" id="<?php echo 'd' . $k; ?>" type="text" class="form-control input-sm price text-right" value="<?php echo $d->Cantidad; ?>" />
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<div class="text-right">
	<button data-confirm="Una ves realizada la devolución no podra volverlo hacer, por eso verifique bien la cantidad a devolver. ¿Desea continuar?" type="submit" id="btnDevolucionGuardar" class="btn btn-primary submit-ajax-button"><i class="glyphicon glyphicon-refresh"></i> Guardar</button>
</div>
<?php echo form_close(); ?>


<script>
$(document).ready(function(){
	$("#btnDevolucionGuardar").click(function(){

	})
})
</script>