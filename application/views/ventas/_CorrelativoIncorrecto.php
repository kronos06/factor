<script>
$(document).ready(function(){
	$("#sltOpcionCorrelativo").focus();
	$("#sltOpcionCorrelativo").change(function(){
		var v = $(this).val();
		switch(v)
		{
			case '1':
				$("#dvOpcionCorrelativo_2").hide();
				$("#dvOpcionCorrelativo_1").show();
				$("#dvOpcionCorrelativo_1 input").eq(1).focus();
				$("#dvOpcionCorrelativo_1 input:not(.serie),textarea").val('');
				$("#dvOpcionCorrelativo_1 input").removeClass('failed').addClass('required');
				break;
			case '2':
				$("#dvOpcionCorrelativo_2").show();
				$("#dvOpcionCorrelativo_1").hide();
				$("#dvOpcionCorrelativo_1 input").removeClass('required');
				break;
			default:
				$("#dvOpcionCorrelativo_2").hide();
				$("#dvOpcionCorrelativo_1").hide();
				$("#dvOpcionCorrelativo_1 input").removeClass('required');
				break;
		}
	})
	$("#btnCorrelativoCancelar").click(function(){
		var id = <?php echo $id; ?>;
		$.post(base_url('ventas/ajax/CancelarImpresion'),{
			id: id
		}, function(r){
			if(r.response)
			{
				$("#mpCorrelativo").modal('hide');
			}
			else
			{
				alert(r.message);
			}
		}, 'json')
	})
})
</script>
<div class="row">
	<div class="col-md-12">
		<?php echo form_open('ventas/ajax/CorregirCorrelativo', array('class' => 'upd form-horizontal')); ?>
		  <input type="hidden" name="id" value="<?php echo $id; ?>" />
		  <input type="hidden" name="CorrelativoActual" value="<?php echo $correlativo; ?>" />
		  <input type="hidden" name="Tipo" value="<?php echo $tipo; ?>" />
		  <div class="form-group">
		    <label class="col-sm-2 control-label">Razones</label>
		    <div class="col-sm-10">
		      <select name="Razon" id="sltOpcionCorrelativo" class="form-control required">
		      	<option selected="selected" value="0">Seleccione una opción</option>
		      	<option value="1">El correlativo del talonario es mayor al del sistema</option>
		      	<option value="2">El sistema arroja un correlativo mayor al del talonario</option>
		      </select>
		    </div>
		  </div>
		  <div id="dvOpcionCorrelativo_2" class="none">
		    <div class="col-sm-offset-2 col-sm-10">
		  		<div class="alert alert-info text-center">En este caso tendra que <b>ANULAR</b> los talonarios hasta coincidir con el correlativo actual del sistema: <b>#<?php echo $correlativo; ?></b></div>		      
		    </div>
		  </div>
		  <div id="dvOpcionCorrelativo_1" class="none">
			  <div class="form-group">
			    <label class="col-sm-2 control-label">Correlativo</label>
			    <div class="col-sm-2">
			      <input type="text" class="form-control serie" value="<?php echo $tipo == 2 ? $this->conf->SBoleta : $this->conf->SFactura; ?>" disabled="disabled" />
			    </div>
			    <div class="col-sm-8">
			      <input name="CorrelativoNuevo[]" type="text" class="form-control numeric" placeholder="Ingrese el correlativo que indica su Talonario.">
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label">Confirmar</label>
			    <div class="col-sm-2">
			      <input type="text" class="form-control serie" value="<?php echo $tipo == 2 ? $this->conf->SBoleta : $this->conf->SFactura; ?>" disabled="disabled" />
			    </div>
			    <div class="col-sm-8">
			      <input name="CorrelativoNuevo[]" type="text" class="form-control numeric" placeholder="Escriba nuevamente el correlativo del Talonario.">
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label">Confirmar</label>
			    <div class="col-sm-10">
      			    <textarea name="Glosa" rows="2" class="form-control" value="" placeholder="Ingrese un comentario"></textarea>
			    </div>
			  </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10 text-right">
		      <button aria-hidden="true" id="btnCorrelativoCancelar" type="button" class="btn btn-danger">Cancelar</button>
		      <button type="submit" class="btn submit-ajax-button btn-info">Siguiente <span class="glyphicon glyphicon-chevron-right"></span></button>
		    </div>
		  </div>
		<?php echo form_close(); ?>
	</div>
</div>