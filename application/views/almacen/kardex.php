<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<div class="btn-group pull-right">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			  	<span class="glyphicon glyphicon-th"></span>
			    Acciones <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
			  	<li>
					<a href="<?php echo base_url('index.php/almacen/ajustar'); ?>">
						<span class="glyphicon glyphicon-wrench"></span>
						Ajustar Stock
					</a>
			  	</li>
			  	<li>
					<a href="<?php echo base_url('index.php/almacen/entrada'); ?>">
						<span class="glyphicon glyphicon-file"></span>
						Nueva Entrada
					</a>
			  	</li>
			  </ul>
			</div>
			<h1>Kardex</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Kardex</li>
		</ol>
		<div class="well well-sm text-center">
			Este reporte muestra las entradas/salidas de un producto en una fecha determinada.
		</div>
		<div class="row">
			<div class="col-md-4 hidden-print">
				<div class="form-group">
					<label>Reporte actual</label>
					<select id="sltReporte" class="form-control">
						<option value="1">Una Fecha Determinada</option>
						<option value="2">Un Rango de Fechas</option>
					</select>
				</div>
			</div>
			<div class="col-md-5 hidden-print">
				<div class="row">
					<div id="dvFecha" class="col-md-8">
						<label>&nbsp;</label>
						<div  class="input-group">
							<input id="txtF" autocomplete="off" class="form-control datepicker-today" type="text" placeholder="Fecha" data-hoy="<?php echo date('d/m/Y'); ?>" />
						  	<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
					</div>
					<div class="col-md-5 fecha-rango">
						<label>&nbsp;</label>
						<div class="input-group">
							<input id="txtFR1" autocomplete="off" class="form-control datepicker-today" type="text" value="" placeholder="Inicio" />
						  	<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
					</div>
					<div class="col-md-5 fecha-rango">
						<label>&nbsp;</label>
						<div class="input-group">
							<input id="txtFR2" autocomplete="off" class="form-control datepicker-today" type="text" value="" placeholder="Fin" />
						  	<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<button id="btnFiltrarKardex" type="button" class="btn btn-success" style="margin-top:23px;">
								<i class="glyphicon glyphicon-search"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-md-12">
				<div id="kardex" class="table-responsive">
					
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		CargarKardex('<?php echo date('d/m/Y'); ?>', '');
		$("#sltReporte").val('1');

		$("#txtF").val($("#txtF").data('hoy'));

		$("#sltReporte").change(function(){
			EsconderFechas();
		})

		$("#btnFiltrarKardex").click(function(){
			if($("#sltReporte").val() == '1')
			{
				CargarKardex($("#txtF").val() , '');
			}
			if($("#sltReporte").val() == '2')
			{
				if($("#txtFR1").val() != '' && $("#txtFR2").val() != '')
				{
					if($("#txtFR1").val() == $("#txtFR2").val())
					{
						alert('Las fechas a comparar no deben ser iguales');
						return;
					}
					if($("#txtFR1").val() == $("#txtFR2").val())
					{
						alert('Las a comparar no deben ser iguales');
						return;
					}
					var df = DateDiff($("#txtFR1").val(), $("#txtFR2").val());
					if(df < 0)
					{
						alert('La fecha inicio debe ser mayor a la de finalización');
						return;
					}else if(df > 130)
					{
						alert('Para evitar sobrecargar la base de datos no se puede hacer un reporte mayor a 4 meses.');
						return;
					}

					CargarKardex($("#txtFR1").val() , $("#txtFR2").val());
				}
			}
		})

		EsconderFechas();
	})

	function CargarKardex(f1, f2)
	{
		$("#kardex").prepend('<div class="block-loading"></div>');
		$.post(base_url('almacen/ajax/CargarKardex'), {
			f1: f1,
			f2: f2
		}, function(r){
			$("#kardex").html(r);
		})
	}

	function EsconderFechas()
	{
		if($("#sltReporte").val() == '1')
		{
			$(".fecha-rango input").val('');
			$(".fecha-rango").hide();
			$("#dvFecha").show();
		}
		if($("#sltReporte").val() == '2')
		{
			$(".fecha-rango").show();
			$("#dvFecha input").val($("#dvFecha input").data('hoy'));
			$("#dvFecha").hide();
		}
	}
</script>