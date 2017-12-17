<script type="text/javascript" src="<?php echo base_url('assets/scripts/venta/comprobante.js'); ?>"></script>
<script>
	var ComprobanteTipo = 0;
	<?php if($comprobante != null): ?>
		ComprobanteTipo = '<?php echo $comprobante->ComprobanteTipo_id;?>';
	<?php endif; ?>
</script>
<?php //array_debug($comprobante); ?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<div class="pull-right">
			  <?php	if($comprobante!=null): ?>
			  	<?php if($comprobante->Correlativo != null AND ($comprobante->Estado == 2 || $comprobante->Estado == 3 )):?>
				<a title="Nuevo Comprobante" class="btn btn-default" href="<?php echo base_url('index.php/ventas/comprobante'); ?>">
					<span class="glyphicon glyphicon-file"></span>
				</a>
				<?php endif; ?>
			  	<?php if($comprobante->Estado != 4 && $comprobante->ComprobanteTipo_id != 4 && $comprobante->ComprobanteTipo_id != 1):?>
				<button id="btnImprimirComprobante" data-correlativo="<?php echo $comprobante->Correlativo; ?>"  data-impresion="<?php echo $comprobante->Impresion; ?>" data-id="<?php echo $comprobante->id; ?>" data-estado="<?php echo $comprobante->Estado; ?>" data-tipo="<?php echo $comprobante->ComprobanteTipo_id; ?>" class="btn btn-info" title="Imprimir">
					<span class="glyphicon glyphicon-print"></span>
				</button>
				<?php endif; ?>
			  	<?php if($comprobante->ComprobanteTipo_id == 1):?>
				<a class="btn btn-info" target="_blank" title="Descargar PDF" href="<?php echo base_url('index.php/ventas/proforma/' . $comprobante->id); ?>">
					<span class="glyphicon glyphicon-download-alt"></span>
				</a>
				<?php endif; ?>
			  <?php endif; ?>
				<div class="btn-group" title="Clientes">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    <span class="glyphicon glyphicon-user"></span> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
				    <li><a href="<?php echo base_url('index.php/mantenimiento/clientes'); ?>" target="_blank">Mis Clientes</a></li>
				    <li><a href="<?php echo base_url('index.php/mantenimiento/cliente'); ?>" target="_blank">Cliente Nuevo</a></li>
				  </ul>
				</div>
				<div class="btn-group" title="Productos">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    <span class="glyphicon glyphicon-shopping-cart"></span> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
				    <li><a href="<?php echo base_url('index.php/mantenimiento/productos'); ?>" target="_blank">Mis Productos</a></li>
				    <li><a href="<?php echo base_url('index.php/mantenimiento/producto'); ?>" target="_blank">Producto Nuevo</a></li>
				  </ul>
				</div>
				<div class="btn-group" title="Servicios">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				     <span class="glyphicon glyphicon-briefcase"></span> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
				    <li><a href="<?php echo base_url('index.php/mantenimiento/servicios'); ?>" target="_blank">Mis Servicios</a></li>
				    <li><a href="<?php echo base_url('index.php/mantenimiento/servicio'); ?>" target="_blank">Servicio Nuevo</a></li>
				  </ul>
				</div>
			</div>
			<h1>
				<?php 
					if(!isset($comprobante))
					{
						echo 'Nuevo Comprobante';
					}
					else
					{
						if($comprobante->Correlativo == '')
						{
							echo $comprobante->Tipo->Nombre;	
						}
						else if($comprobante->ComprobanteTipo_id == 2)
						{
							echo $comprobante->Tipo->Nombre . ($comprobante->Serie != '' ? ': #' . $comprobante->Serie . '-' . $comprobante->Correlativo : '');
						}
						else if($comprobante->ComprobanteTipo_id == 3)
						{
							echo $comprobante->Tipo->Nombre . ($comprobante->Serie != '' ? ': #' . $comprobante->Serie . '-' . $comprobante->Correlativo : '');
						}
						else
						{
							echo $comprobante->Tipo->Nombre . ': #' . $comprobante->Correlativo;
						}
					}
				?>
			</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/ventas/comprobantes'); ?>">Comprobantes</a></li>
		  <li class="active"><?php echo $comprobante == null ? "Nuevo Comprobante" : $comprobante->Tipo->Nombre; ?></li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<?php echo form_open('ventas/comprobantecrud', array('class' => 'upd')); ?>
				<?php if($comprobante != null): ?>
					<input type="hidden" name="id" value="<?php echo $comprobante->id; ?>" />
					<?php if($comprobante->Estado == 4): ?>
						<div class="alert alert-warning text-center">Este comprobante se encuentra <b>en modo revisión</b>, actualice la información que crea conveniente y luego <b>cambie el estado</b> para salir del modo revisión.</div>
					<?php endif; ?>
					<?php if($comprobante->Impresion == 2 && $comprobante->ComprobanteTipo_id != 1): ?>
						<div class="alert alert-info text-center">Ya se ha <b>generado una impresión</b> de este comprobante.</div>
					<?php endif; ?>
					<?php if($comprobante->Impresion == 1 && $comprobante->ComprobanteTipo_id != 4): ?>
						<div class="alert alert-info text-center">Este comprobante tiene una impresion <b>PENDIENTE</b>.</div>
						<script>
							<?php if($comprobante->UsuarioImprimiendo_id == $this->user->id){ ?>
								ImpresionPendiente();
							<?php } ?>
						</script>
					<?php endif; ?>
					<?php if($comprobante->Impresion == 0 && $comprobante->Estado == 2  && $comprobante->ComprobanteTipo_id != 4 && $comprobante->ComprobanteTipo_id != 1): ?>
						<div class="alert alert-warning text-center">No se ha <b>generado una impresión</b> de este comprobante.</div>
					<?php endif; ?>
				<?php endif; ?>
				<div class="well well-sm">(*) Campos obligatorios</div>
				<div class="row">
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Comprobante (*)</label>
					    <?php
					    	if(isset($comprobante))
					    	{
					    		echo '<input class="form-control" type="text" disabled="disabled" value="' . $comprobante->Tipo->Nombre . '" />';
					    	}else
					    	{
					    		echo Select('ComprobanteTipo_id', $tipos, 'Nombre', 'Value', 0, true, null, array('id' => 'sltComprobante'));
					    	}
					    ?>
					  </div>
					</div>
					<div class="col-md-4">
					  <div class="form-group">
					    <label>Cliente <span id="spClienteRequerido">(*)</span></label>
					    <?php if(!isset($comprobante)){?>
						    <div class="input-group">
						      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required" placeholder="Nombre del Cliente" value="" maxlenght="100">
						      <span class="input-group-btn">
						        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
						        	<span class="glyphicon glyphicon-remove"></span>
						        </button>
						      </span>
						    </div>
					    <?php }else{ ?>
					    	<?php if($comprobante->Estado == 4) {?>
							    <div class="input-group">
							      <input id="txtCliente" autocomplete="off" name="ClienteNombre" type="text" class="form-control required" placeholder="Nombre del Cliente" value="" maxlenght="100">
							      <span class="input-group-btn">
							        <button id="btnClienteLimpiar" class="btn btn-default" type="button">
							        	<span class="glyphicon glyphicon-remove"></span>
							        </button>
							      </span>
							    </div>
					    	<?php }else{?>
								<input disabled="disabled id="txtCliente" name="ClienteNombre" type="text" class="form-control" placeholder="Nombre del Cliente" value="<?php echo $comprobante->ClienteNombre; ?>" maxlenght="100" />
					    	<?php }?>
					    <?php } ?>
    						<input id="hdCliente_id" type="hidden" name="Cliente_id" value="" />
						  </div>
						</div>
						<div class="col-md-2">
						  <div class="form-group">
						    <label><span id="spIdentidad">RUC</span> <span id="spRucRequerido">(*)</span></label>
						    <input id="txtRuc" readonly="readonly" id="txtRuc" autocomplete="off" name="ClienteIdentidad" type="text" class="form-control required" placeholder="RUC" value="<?php echo $comprobante != null ? $comprobante->ClienteIdentidad : null; ?>" maxlenght="11" />
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Fecha</label>
					    <?php if(!isset($comprobante)){?>
							<input autocomplete="off" name="FechaEmitido" type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo date(DATE); ?>" maxlenght="10" />
					    <?php }else{ ?>
							<input <?php echo $comprobante->Estado != 4 ? 'disabled="disabled"' : 'name="FechaEmitido"'; ?> type="text" class="form-control required datepicker" placeholder="Fecha de Emisión" value="<?php echo toDate($comprobante->FechaEmitido); ?>" maxlenght="10" />
					    <?php } ?>
					  </div>
					</div>
					<div class="col-md-2">
					  <div class="form-group">
					    <label>Estado (*)</label>
					    <?php 
						    if($comprobante != NULL)
						    {
							    if($comprobante->Estado == 3)
							    {
							    	echo '<input type="text" style="color:red;" value="Anulado" disabled="disabled" class="form-control" />';
							    }else if($comprobante->Estado == 1)
							    {
							    	echo '<input type="text" style="color:orange;" value="Pendiente" disabled="disabled" class="form-control" />';
							    }
							    else if($comprobante->Estado == 2)
							    {
							    	unset($estados[2]);
							    	unset($estados[3]);
						    		echo Select('Estado', $estados, 'Nombre', 'Value', $comprobante->Estado, null, '', array('id' => 'sltEstado', 'data-estado' => $comprobante->Estado));								    	
							    }else
							    {
							    	unset($estados[2]);
						    		echo Select('Estado', $estados, 'Nombre', 'Value', $comprobante->Estado, null, '', array('id' => 'sltEstado', 'data-estado' => $comprobante->Estado));							    	
							    }
						    }
						    else
						    {
						    	echo '<input style="color:orange;" type="text" value="Pendiente" disabled="disabled" class="form-control" />';
						    }
					    ?>
					  </div>
					</div>
				</div>
				  <div class="form-group">
				    <label>Dirección <span id="spDireccionRequerido">(*)</span></label>
					<input id="txtDireccion" type="text" autocomplete="off" id="txtDireccion" name="ClienteDireccion" class="form-control" value="<?php echo isset($comprobante) ? $comprobante->ClienteDireccion : ''; ?>" readonly="readonly" />
				  </div>
				  
				  <!-- Detalle Factura -->
				  <table class="table">
			  		<thead style="background:#eee;">
				  	<tr>
				  		<th style="width:20px;background:#eee;">#</th>
				  		<th style="width:20px;background:#eee;"></th>
				  		<th>Item</th>
				  		<th style="width:100px;">CNT</th>
				  		<th style="width:84px;">UND</th>
				  		<th class="text-right" style="width:140px;">P.U (<?php echo $this->conf->Moneda_id; ?>)</th>
				  		<th class="text-right" style="width:140px;">P.T (<?php echo $this->conf->Moneda_id; ?>)</th>
				  	</tr>
			  		</thead>
			  		<tbody>
				    <?php if($comprobante != false): ?>
					  	<?php foreach($comprobante->Detalle as $k => $c): ?>
				  			<tr>
				  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $k+1; ?></td>
				  				<td><span class="glyphicon glyphicon-chevron-right"></span></td>
				  				<td><?php echo $c->ProductoNombre; ?></td>
				  				<td><?php echo number_format($c->Cantidad, 2); ?></td>
				  				<td><?php echo $c->UnidadMedida_id; ?></td>
				  				<td class="text-right"><?php echo number_format($c->PrecioUnitario, 2); ?></td>
				  				<td class="text-right"><?php echo number_format($c->PrecioTotal, 2); ?></td>
				  			</tr>
			  			<?php endforeach; ?>
			        <?php endif; ?>
					<?php for($i= isset($comprobante) ? count($comprobante->Detalle)+1 : 1; $i <= $this->conf->Lineas; $i++):?>
						<?php if($comprobante == false){ ?>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="0" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto" value="" placeholder="Escriba el nombre de un producto" />
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]"  type="hidden" class="hdTipo" value="" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="5" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<?php }else if($comprobante->Estado != 4){ ?>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  			</tr>
			  			<?php }else { ?>
			  			<tr>
			  				<td class="text-right" style="background:#eee;padding:2px 4px;"><?php echo $i; ?></td>
			  				<td>
			  					<button type="button" class="btn btn-default btn-sm btnProductoQuitar" title="Remover este item">
			  						<span class="glyphicon glyphicon-remove"></span>
			  					</button>
			  				</td>
			  				<td>
			  					<input id="txtProducto_<?php echo $i; ?>" data-id="0" autocomplete="off" name="ProductoNombre[]" type="text" class="form-control input-sm txtProducto" value="" placeholder="Escriba el nombre de un producto" />
			  					<input name="Producto_id[]"  type="hidden" class="hdProducto_id" value="" />
			  				</td>
			  				<td>
								<input name="PrecioUnitarioCompra[]"  type="hidden" class="hdPrecioUnitarioCompra" value="" />
			  					<input autocomplete="off" name="Cantidad[]" type="text" class="form-control input-sm txtCantidad price" value="" placeholder="Cantidad"  maxlength="5"/>
			  					<input name="Tipo[]" type="hidden" class="hdTipo" value="" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="UnidadMedida_id[]" type="text" class="form-control input-sm txtUnidad" value="" placeholder="UND" readonly="readonly" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" name="PrecioUnitario[]" type="text" class="form-control input-sm price text-right txtPrecioUnitario" value="" placeholder="Precio Unitario" maxlength="5" />
			  				</td>
			  				<td>
			  					<input autocomplete="off" type="text" class="form-control input-sm price text-right txtTotal" value="" placeholder="Total" readonly="readonly" />
			  				</td>
			  			</tr>
			  			<?php } ?>
		  			<?php endfor; ?>
			  		</tbody>
			  		<tfoot style="background:#eee;">
					  	<?php if($comprobante == false){?>
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Glosa</label>
							    <textarea name="Glosa" rows="2" cols="" class="form-control"></textarea>
							  </div>
			  				</td>
			  			</tr>
						<?php }else if ($comprobante->Glosa != '' && $comprobante->Estado != 4){ ?>
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Glosa</label>
								<p style="background:#fff;padding:4px;border-radius:4px;"><?php echo $comprobante->Glosa; ?></p>
							  </div>
			  				</td>
			  			</tr>
						<?php }else if($comprobante->Estado == 4){ ?>
			  			<tr>
			  				<td colspan="7">
							  <div class="form-group">
								<label>Glosa</label>
							    <textarea name="Glosa" rows="2" cols="" <?php echo $comprobante->Glosa != '' ? 'readonly="readonly"' : ''; ?> class="form-control"><?php echo $comprobante->Glosa; ?></textarea>
							  </div>
			  				</td>
			  			</tr>
						<?php } ?>	
			  			<tr id="trSubTotal">
			  				<th class="text-right" colspan="6">Sub Total (<?php echo $this->conf->Moneda_id; ?>)</th>
			  				<td class="text-right">
			  					<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtSubTotal" class="form-control text-right input-sm" value="0.00" readonly="readonly" />
			  					<?php }else if($comprobante->Estado != 4) {?>
		  							<?php echo number_format($comprobante->SubTotal, 2); ?>
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtSubTotal" class="form-control text-right input-sm price" value="<?php echo number_format($comprobante->SubTotal, 2); ?>" name="SubTotal"  />
			  					<?php } ?>
							</td>
			  			</tr>
			  			<tr id="trIva">
			  				<th class="text-right" colspan="6">
			  					IVA (%)
								<?php if($comprobante == null){ ?>
									<input id="txtIva" name="Iva" style="width:54px;margin-left:10px;" class="form-control text-right input-sm required price pull-right" value="<?php echo $this->conf->Iva; ?>" />
			  					<?php }else if($comprobante->Estado != 4) {?>
		  							<span style="font-weight:normal;"><?php echo number_format($comprobante->Iva, 2); ?></span>
			  					<?php }else{ ?>
			  						<input id="txtIva" name="Iva" style="width:54px;margin-left:10px;" class="form-control text-right input-sm required price pull-right" value="<?php echo $this->conf->Iva; ?>" />
			  					<?php } ?>
			  				</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtIvaSubTotal" readonly="readonly" class="form-control text-right input-sm" value="0.00" />
			  					<?php }else if($comprobante->Estado != 4) {?>
		  							<?php echo number_format($comprobante->IvaTotal, 2); ?>
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtIvaSubTotal" readonly="readonly" class="form-control text-right input-sm" value="<?php echo number_format($comprobante->IvaTotal, 2); ?>" />
			  					<?php } ?>
			  				</td>
			  			</tr>
			  			<tr>
			  				<th class="text-right" colspan="6">Total (<?php echo $this->conf->Moneda_id; ?>)</th>
			  				<td class="text-right">
								<?php if($comprobante == null){ ?>
									<input autocomplete="off" id="txtTotal" readonly="readonly" class="form-control text-right input-sm" value="0.00" />
			  					<?php }else if($comprobante->Estado != 4) {?>
		  							<?php echo number_format($comprobante->Total, 2); ?>
			  					<?php }else{ ?>
			  						<input autocomplete="off" id="txtTotal" readonly="readonly" class="form-control text-right input-sm" value="<?php echo number_format($comprobante->Total, 2); ?>" />
			  					<?php } ?>
			  				</td>
			  			</tr>
			  		</tfoot>
				  </table>
				  <div class="clearfix text-right">
					  <?php	if($comprobante!=null){ ?>
			  			<button id="btnGuardar" type="submit" class="submit-ajax-button none">Guardar</button>
					  <?php }else{ ?>
					  	<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
					  <?php }?>
				  </div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<?php if(HasModule('stock') && $comprobante != null): ?>
	<?php if($comprobante->Devolucion == 0 && $comprobante->Estado == 3 && ($comprobante->ComprobanteTipo_id == 2 || $comprobante->ComprobanteTipo_id == 3 || $comprobante->ComprobanteTipo_id == 4)): ?>
		<script>
			$(document).ready(function(){
				AjaxPopupModalDontClose('mDevolucion', 'Productos para devolver al almacén', 'ventas/ajax/CargarDetalleParaDevolver', { comprobante_id : <?php echo $comprobante->id; ?>})
			})
		</script>
	<?php endif; ?>
<?php endif; ?>