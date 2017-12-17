<div class="row">
	<div class="col-md-12">
		  <div class="form-group">
		    <label>Nombre</label>
		    <input readonly="readonly" type="text" class="form-control" value="<?php echo $producto->Nombre; ?>" />
		  </div>
		  <?php if($tipo == 1): ?>
		  <div class="form-group">
		    <label>Marca</label>
		    <input readonly="readonly" type="text" class="form-control" value="<?php echo $producto->Marca; ?>" />
		  </div>
		  <?php endif; ?>
		  <div class="form-group">
		    <label>UDM</label>
		    <input readonly="readonly" type="text" class="form-control" value="<?php echo $producto->UnidadMedida_id; ?>" />
		  </div>
		  	<?php if(HasModule('stock')): ?>
          <div class="form-group">
            <label>Stock Actual</label>
            <div class="input-group">
              <input type="text" class="form-control" value="<?php echo $producto->Stock; ?> <?php echo $producto->UnidadMedida_id; ?>" disabled="disabled" />
              <span class="input-group-btn">
                <a target="_blank" href="<?php echo base_url('index.php/almacen/entrada/' . $producto->id); ?>" class="btn btn-success">
                    <i class="glyphicon glyphicon-plus"></i> Abastecer
                </a>
              </span>
            </div>
          </div>
          <div class="form-group">
            <label>Stock Mínimo</label>
            <input name="StockMinimo" type="text" class="form-control price" value="<?php echo $producto->StockMinimo; ?>" />
          </div>
      		<?php endif; ?>
		  <div class="form-group">
		    <label>Costo</label>
		    <input readonly="readonly" type="text" class="form-control" value="<?php echo $producto->PrecioCompra; ?>" />
		  </div>
		  <div class="form-group">
		    <label>Precio de Venta</label>
		    <input readonly="readonly" type="text" class="form-control" value="<?php echo $producto->Precio; ?>" />
		    <span class="help-block">Existe un margen de ganancia aproximadamente del <?php echo MargenDeGanancia($producto->Precio, $producto->PrecioCompra); ?>%</span>
		  </div>
		  <div class="form-group">
		    <label>Ganancia</label>
		    <input readonly="readonly" type="text" class="form-control" value="<?php echo number_format($producto->Precio - $producto->PrecioCompra, 2) ?>" />
		  </div>
	</div>
</div>