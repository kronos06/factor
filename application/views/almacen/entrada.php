<?php
	//array_debug($producto); 
?>
<script>
$(document).ready(function(){
	BuscarProductos();
})
function BuscarProductos()
{
	var input = $("#txtProducto");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/productos'),
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
                            nombre: item.Nombre,
                            marca: item.Marca,
                            pc: item.PrecioCompra,
                            stock: item.Stock
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
        	$("#producto_id").val(ui.item.id);
        	
            input.attr('data-name', ui.item.value);
            input.attr('data-id', ui.item.id);

        	input.val(ui.item.nombre);
        	$("#txtUnidadMedida_id").val(ui.item.und);
        	$("#txtCosto").val(ui.item.costo);
        	$("#txtPrecioCompra").val(ui.item.pc);
            $("#txtStock").val(ui.item.stock);
        	return false;
        }
    })

    input.focus(function () {
        $(this).val('');
    });
    input.blur(function () {
        $(this).val($(this).attr('data-name'));
    });
}
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1><?php echo $this->router->method == 'entrada' ? 'Nueva Entrada' : 'Ajustar Stock'; ?></h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/almacen/index'); ?>">Entrada/Salida</a></li>
		  <li class="active">Nueva Entrada</li>
		</ol>
		<div class="well well-sm">(*) Campos obligatorios</div>
        <?php if($this->router->method == 'ajustar'): ?>
            <div class="alert alert-warning text-center">
                <b>TENGA CUIDADO</b> con usar esta opción, solo se debe usarse si el <b>STOCK</b> no cuadra con el <u>inventario real</u>.
            </div>
        <?php endif; ?>
		<?php echo form_open('almacen/' . ($this->router->method == 'entrada' ? 'entradacrud' : 'ajustarcrud')  , array('class' => 'upd')); ?>
		  <div class="form-group">
		    <label>Producto (*)</label>
		    <input type="hidden" id="producto_id" name="Producto_id" value="<?php echo isset($producto) ? $producto->id : '' ?>" />
		    <input autocomplete="off" id="txtProducto" name="ProductoNombre" type="text" class="form-control required" placeholder="Escriba el nombre del producto para registrar la entrada." value="<?php echo isset($producto) ? $producto->NombreCompleto : '' ?>" maxlength="10" <?php echo isset($producto) ? 'readonly="readonly"' : '' ?> />
		    <span class="help-block">Escriba un nombre para buscarlo en la base de datos.</span>
		  </div>
		  <div class="form-group">
		    <label>Cantidad (*)</label>
		    <input autocomplete="off" id="txtCantidad" name="Cantidad" type="text" class="form-control required price" placeholder="Ingrese la cantidad a registrar para esta entrada." value="" />
		  </div>
        <?php if($this->router->method == 'entrada'): ?>
          <div class="form-group">
            <label>Precio Compra (*)</label>
            <input autocomplete="off" id="txtPrecioCompra" name="Precio" type="text" class="form-control required price" placeholder="Ingrese el precio de compra del Producto." value="<?php echo isset($producto) ? $producto->PrecioCompra : '' ?>" />
          </div>
        <?php endif; ?>
		  <div class="form-group">
		    <label>UDM</label>
		    <input id="txtUnidadMedida_id" autocomplete="off" name="UnidadMedida_id" type="text" class="form-control" placeholder="Unidad de Medida" value="<?php echo isset($producto) ? $producto->UnidadMedida_id : '' ?>" maxlength="5" readonly="readonly" />
		  </div>
          <div class="form-group">
            <label>Stock Actual</label>
            <input id="txtStock" type="text" class="form-control" value="<?php echo isset($producto) ? $producto->Stock : '' ?>" readonly="readonly" />
          </div>
		  <div class="clearfix text-right">
			<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
		  </div>
		<?php echo form_close(); ?>
	</div>
</div>