<?php
	//array_debug($servicio); 
?>
<script>
$(document).ready(function(){
	BuscarServicios();
})
function BuscarServicios()
{
	var input = $("#txtServicio");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/Servicios'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            id: item.id,
                            value: item.Nombre,
                            und: item.UnidadMedida_id,
                            precio: item.Precio
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
        	return false;
        }
    })
}
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1><?php echo $servicio == null ? "Nuevo Servicio" : $servicio->Nombre; ?></h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento/Servicios'); ?>">Servicios</a></li>
		  <li class="active"><?php echo $servicio == null ? "Nuevo Servicio" : $servicio->Nombre; ?></li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<div class="well well-sm">(*) Campos obligatorios</div>
				<?php echo form_open('mantenimiento/Serviciocrud', array('class' => 'upd')); ?>
				<?php if($servicio != null): ?>
				<input type="hidden" name="id" value="<?php echo $servicio->id; ?>" />
				<?php endif; ?>
				  <div class="form-group">
				    <label>Nombre (*)</label>
				    <input autocomplete="off" id="txtServicio" name="nombre" type="text" class="form-control required" placeholder="Nombre del Servicio" value="<?php echo $servicio != null ? $servicio->Nombre : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Costo (*)</label>
				    <input autocomplete="off" name="PrecioCompra"  type="text" class="form-control required price" placeholder="Costo del Producto" value="<?php echo $servicio != null ? $servicio->PrecioCompra : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Precio (*)</label>
				    <input autocomplete="off" name="Precio"  type="text" class="form-control required price" placeholder="Precio de Venta" value="<?php echo $servicio != null ? $servicio->Precio : null; ?>" />
				  </div>
				  <?php if($servicio != null): ?>
				  <div class="form-group">
				    <label>Ganancia</label>
				    <input disabled="disabled" type="text" class="form-control required price" value="<?php echo number_format($servicio->Precio - $servicio->PrecioCompra, 2) ?>" />
				    <span class="help-block">Existe un margen de ganancia aproximadamente del <?php echo MargenDeGanancia($servicio->Precio, $servicio->PrecioCompra); ?>%</span>
				  </div>
				  <?php endif; ?>
				  <div class="clearfix text-right">
				  <?php if(isset($servicio)): ?>
				  	<button type="button" class="btn btn-danger submit-ajax-button del" value="<?php echo base_url('index.php/mantenimiento/servicioeliminar/' . $servicio->id); ?>">Eliminar</button>
			  	  <?php endif; ?>
				  	<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
				  </div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>