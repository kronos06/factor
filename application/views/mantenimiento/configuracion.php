<?php 
	//array_debug($this->conf);
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>Configuración</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Configuración</li>
		</ol>
		<div class="row">
			<div class="col-md-12">

				 <div class="well well-sm">(*) Campos obligatorios</div>
			
				<!-- Nav tabs -->
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#cempresa" data-toggle="tab">Mi Empresa</a></li>
				  <li><a href="#ccomprobante" data-toggle="tab">Comprobantes</a></li>
				</ul>
				
				<!-- Tab panes -->
				<div class="tab-content">
				  <div class="tab-pane active" id="cempresa" style="padding-top:15px;">
					<?php echo form_open('mantenimiento/configuracionactualizar', array('class' => 'upd')); ?>
					  <div class="form-group">
					    <label>Empresa (*)</label>
					    <input autocomplete="off" name="RazonSocial" type="text" class="form-control" placeholder="Nombre de la empresa" value="<?php echo $this->conf->RazonSocial; ?>" />
					  </div>
					  <div class="form-group">
					    <label>RUC (*)</label>
					    <input maxlength="11" autocomplete="off"  name="Ruc"  type="text" class="form-control required" placeholder="Número de RUC" value="<?php echo $this->conf->Ruc; ?>" />
					  </div>
					  <div class="form-group">
					    <label>IVA (*)</label>
						<input maxlength="5" autocomplete="off"  name="Iva"  type="text" class="form-control required price" placeholder="Impuesto al Valor Agregado" value="<?php echo $this->conf->Iva; ?>" maxlength="5" />
					  </div>
					  <div class="form-group">
					    <label>Moneda Actual (*)</label>
					    <input type="text" disabled="disabled" value="<?php echo $this->conf->Moneda_id . ' ' . $this->conf->moneda->Nombre; ?>" class="form-control" />
					  </div>
					  <div class="form-group">
					    <label>Dirección (*)</label>
					    <textarea name="Direccion"  class="form-control" placeholder="La dirección de su Empresa"><?php echo $this->conf->Direccion; ?></textarea>
					  </div>
					  <div class="text-right">
	  					  <button type="submit" class="btn btn-default submit-ajax-button">Guardar</button>
					  </div>
					<?php echo form_close(); ?>
				  </div>
				  <div class="tab-pane" id="ccomprobante" style="padding-top:15px;">
					<?php echo form_open('mantenimiento/configuracionactualizar', array('class' => 'upd')); ?>
					  <?php /* <div class="form-group">
					    <label>Reglas de Impresión</label>
					    <select name="Impresion" class="form-control">
					    	<option value="1">Todos usan una sola impresora</option>
					    	<option value="2">Cada vendedor maneja su impresora</option>
					    </select>
					  </div>
					  */ ?>
					  <div class="form-group">
						<label>Imagen de su Boleta <?php if($this->conf->BoletaFoto != ''): ?><a target="_blank" href="<?php echo base_url('uploads/' . $this->conf->BoletaFoto); ?>">(Ver Imagen)</a><?php endif; ?></label>
						<input name="Boleta" type="file" autocomplete="off" />
						<span class="help-block">Para ajustar la hoja de impresión de su Boleta haga click al <?php echo anchor('mantenimiento/impresora/2', 'siguiente enlace'); ?></span>
					  </div>
					  <div class="form-group">
					    <label>Imagen de su Factura <?php if($this->conf->FacturaFoto != ''): ?><a target="_blank" href="<?php echo base_url('uploads/' . $this->conf->FacturaFoto); ?>">(Ver Imagen)</a><?php endif; ?></label>
						<input name="Factura" type="file" autocomplete="off" />
						<span class="help-block">Para ajustar la hoja de impresión de su Factura haga click al <?php echo anchor('mantenimiento/impresora/3', 'siguiente enlace'); ?></span>
					  </div>
					<?php /*
					  <div class="form-group">
					    <label>N° de Serie (Boleta) (*)</label>
					    <div class="row">
					    	<div class="col-md-1">
					    		<input autocomplete="off"  name="SBoleta"  type="text" class="form-control" placeholder="Serie" value="<?php echo $this->conf->SBoleta; ?>" maxlength="3" />
					    	</div>
					    	<div class="col-md-11">
					    		<input autocomplete="off"  name="NBoleta"  type="text" class="form-control" placeholder="Último correlativo registrado" value="<?php echo $this->conf->NBoleta; ?>" />
					    	</div>
					    </div>
					  </div>
					  <div class="form-group">
					    <label>N° de Serie (Factura) (*)</label>
					    <div class="row">
					    	<div class="col-md-1">
					    		<input autocomplete="off"  name="SFactura"  type="text" class="form-control" placeholder="Serie" value="<?php echo $this->conf->SFactura; ?>" maxlength="3" />
					    	</div>
					    	<div class="col-md-11">
					    		<input autocomplete="off"  name="NFactura"  type="text" class="form-control" placeholder="Último correlativo registrado" value="<?php echo $this->conf->NFactura; ?>" />
					    	</div>
					    </div>
					  </div>
					*/ ?>
					  <!-- <div class="form-group">
					    <label>Pie de Comprobante (*)</label>
					    <textarea name="PieComprobante"  class="form-control" placeholder="Este sera mostrado en el pie de las facturas, boletas o cualquier otro comprobante"><?php echo $this->conf->PieComprobante; ?></textarea>
					  </div> -->
					  <div class="text-right">
	  					  <button type="submit" class="btn btn-default submit-ajax-button">Guardar</button>
					  </div>
					<?php echo form_close(); ?>
				  </div>
				</div>
			</div>
		</div>
	</div>
</div>