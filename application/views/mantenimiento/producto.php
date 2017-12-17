<?php
	//var_dump($asignada); 
?>
<script>
$(document).ready(function(){
	BuscarProductos();
	BuscarMarcas();
	BuscarMedidas();
})
function BuscarMedidas()
{
	var input = $("#txtUnidadMedida_id");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/medidas'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.UnidadMedida_id
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function(e, ui){
            input.blur();
		}
    })
}
function BuscarMarcas()
{
	var input = $("#txtMarca");

    input.autocomplete({
        dataType: 'JSON',
        source: function (request, response) {
            jQuery.ajax({
                url: base_url('services/marcas'),
                type: "post",
                dataType: "json",
                data: {
                    criterio: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            value: item.Marca
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function(e, ui){
            input.blur();
		}
    })
}
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
                            nombre: item.NombreSimple,
                            precio: item.Precio
                        }
                    }))
                }
            })
        },
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (e, ui) {
        	input.val(ui.item.nombre);
        	return false;
        }
    })
}
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1><?php echo $producto == null ? "Nuevo Producto" : $producto->Nombre; ?></h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento/productos'); ?>">Productos</a></li>
		  <li class="active"><?php echo $producto == null ? "Nuevo Producto" : $producto->Nombre; ?></li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<div class="well well-sm">(*) Campos obligatorios</div>
				<?php echo form_open('mantenimiento/productocrud', array('class' => 'upd')); ?>
				<?php if($producto != null): ?>
				<input type="hidden" name="id" value="<?php echo $producto->id; ?>" />
				<?php endif; ?>
				  <div class="form-group">
				    <label>Nombre (*)</label>
				    <input autocomplete="off" id="txtProducto" name="nombre" type="text" class="form-control required" placeholder="Nombre del producto" value="<?php echo $producto != null ? $producto->Nombre : null; ?>" />
				  </div>
                  <?php if(!$asignada): ?>
				  <div class="form-group">
				    <label>Marca</label>
				    <input id="txtMarca" autocomplete="off" name="Marca" type="text" class="form-control" placeholder="Marca" value="<?php echo $producto != null ? $producto->Marca : null; ?>" />
				    <span class="help-block">Si no desea registrar una marca deje esta casilla en blanco, el sistema la reconocera con el prefijo de <b>S/M</b>.</span>
				  </div>
				  <div class="form-group">
				    <label>UDM (*)</label>
				    <input id="txtUnidadMedida_id" autocomplete="off" name="UnidadMedida_id" type="text" class="form-control required" placeholder="Unidad de Medida" value="<?php echo $producto != null ? $producto->UnidadMedida_id : null; ?>" maxlength="5" />
				  </div>
                  <?php endif; ?>
                  <?php if($asignada): ?>
                  <div class="form-group">
                    <label>Marca</label>
                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $producto != null ? $producto->Marca : null; ?>" />
                  </div>
                  <div class="form-group">
                    <label>UDM (*)</label>
                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $producto != null ? $producto->UnidadMedida_id : null; ?>" maxlength="5" />
                  </div>
                  <?php endif; ?>
                <?php if(HasModule('stock') && $producto == null): ?>
                  <div class="form-group">
                    <label>Stock Inicial</label>
                    <input type="text" class="form-control" name="Stock" class="price" value="0.00" />
                    <span class="help-block">Este no es registrado como una <b>entrada</b>, si desea hacerlo de esta manera después de crear el producto registra la entrada y deje este en 0.00.</span>
                  </div>
                <?php endif; ?>
                <?php if(HasModule('stock') && $producto != null): ?>
                  <div class="form-group">
                    <label>Stock Actual</label>
                    <div class="input-group">
                      <input type="text" class="form-control" value="<?php echo $producto != null ? $producto->Stock : null; ?> <?php echo $producto != null ? $producto->UnidadMedida_id : null; ?>" disabled="disabled" />
                      <span class="input-group-btn">
                        <a target="_blank" href="<?php echo base_url('index.php/almacen/entrada/' . $producto->id); ?>" class="btn btn-success">
                            <i class="glyphicon glyphicon-plus"></i> Abastecer
                        </a>
                      </span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Stock Mínimo</label>
                    <input name="StockMinimo" type="text" class="form-control price" value="<?php echo $producto != null ? $producto->StockMinimo : '0.00'; ?>" />
                  </div>
                <?php endif; ?>
				  <div class="form-group">
				    <label>Costo (*)</label>
				    <input autocomplete="off" name="PrecioCompra"  type="text" class="form-control required price" placeholder="Costo del Producto" value="<?php echo $producto != null ? $producto->PrecioCompra : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Precio de Venta (*)</label>
				    <input autocomplete="off" name="Precio"  type="text" class="form-control required price" placeholder="Precio de Venta" value="<?php echo $producto != null ? $producto->Precio : null; ?>" />
					  <?php if($producto != null): ?>
					    <span class="help-block">Existe un margen de ganancia aproximadamente del <?php echo MargenDeGanancia($producto->Precio, $producto->PrecioCompra); ?>%</span>
					  <?php endif; ?>
				  </div>
				  <?php if($producto != null): ?>
				  <div class="form-group">
				    <label>Ganancia</label>
				    <input disabled="disabled" type="text" class="form-control required price" value="<?php echo number_format($producto->Precio - $producto->PrecioCompra, 2) ?>" />
				  </div>
				  <?php endif; ?>
				  <div class="clearfix text-right">
				  <?php if(isset($producto)): ?>
				  	<button type="button" class="btn btn-danger submit-ajax-button del" value="<?php echo base_url('index.php/mantenimiento/productoeliminar/' . $producto->id); ?>">Eliminar</button>
			  	  <?php endif; ?>
				  	<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
				  </div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>