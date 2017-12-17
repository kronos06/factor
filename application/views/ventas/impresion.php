<?php
	$format = '';
	if($comprobante->ComprobanteTipo_id == 2)
	{
		$format = $this->conf->BoletaFormato;
		
		// Correlativo Temporal
		if($comprobante->Correlativo == '') 
		{
			$comprobante->Serie = $this->conf->SBoleta;
			$comprobante->Correlativo = str_pad($this->conf->NBoleta, $this->conf->Zeros, '0', STR_PAD_LEFT);
		}	
	}
	if($comprobante->ComprobanteTipo_id == 3)
	{
		$format = $this->conf->FacturaFormato;
		
		//Correlativo Temporal
		if($comprobante->Correlativo == '') 
		{
			$comprobante->Serie = $this->conf->NFactura;
			$comprobante->Correlativo = str_pad($this->conf->NFactura, $this->conf->Zeros, '0', STR_PAD_LEFT);
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Vista Preliminar</title>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    
		<?php echo link_tag('assets/bootstrap/css/print.css'); ?>		
		<?php echo link_tag('assets/bootstrap/css/ui-lightness/jquery-ui-1.10.4.custom.min.css'); ?>
		
		<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery-1.10.2.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery-ui-1.10.3.custom.min.js'); ?>"></script>
		
		<script>
			var id = <?php echo $comprobante->id; ?>;
			var base_url = '<?php echo base_url('index.php') . '/'; ?>';
			
			$(document).ready(function(){
				$(".absolute").draggable();
				$(".row").resizable({
					 resize: function(event, ui) {
					 	ui.size.width = ui.originalSize.width;
					 }
				})
				$(".ui-icon-gripsmall-diagonal-s,.ui-icon-gripsmall-diagonal-se").remove();
				$("#btnImprimirCancelar").click(function(){
					<?php if($comprobante->id != 0){ ?>
						if($(this).data('impresion') == '1')
						{
							$.post(base_url + 'ventas/ajax/CancelarImpresion',{
								id: id
							}, function(r){
								Volver();						
							}, 'json');						
						}else
						{
							Volver();						
						}
					<?php }else{ ?>
						Volver();
					<?php } ?>
				})
				$("#btnImprimir").click(function(){
					var f = '';

					f += '#fecha?' + $("#fecha").attr('style') + '|';
					f += '#cliente?' + $("#cliente").attr('style') + '|';
					f += '#ruc?' + $("#ruc").attr('style') + '|';
					f += '#direccion?' + $("#direccion").attr('style') + '|';
					f += '#serie?' + $("#serie").attr('style') + '|';
					f += '#SubTotal?' + $("#SubTotal").attr('style') + '|';
					f += '#total?' + $("#total").attr('style') + '|';
					f += '#TotalLetras?' + $("#TotalLetras").attr('style') + '|';
					f += '#IvaTotal?' + $("#IvaTotal").attr('style') + '|';
					f += '#Iva?' + $("#Iva").attr('style') + '|';
					f += '#detalle?' + $("#detalle").attr('style') + '|';
					f += '#detalle .row?';
					
					$('#detalle .row').each(function(){
						f += $(this).attr('style') + '!';
					})
					
					if($('#detalle .row').size() > 0)
					{
						f = f.substring(0,f.length - 1);
					}

					var button = $(this);

					<?php if($comprobante->id != 0){ ?>
						$.post(base_url + 'ventas/ajax/Imprimir',{
							id: id,
							f: f
						}, function(r){
							if(r.response)
							{
								PrepararHoja();
								window.print();
								alert('La impresión ha sido enviada, lo redireccionaremos a la página anterior.');
								Volver();
							}else
							{
								alert(r.message);
							}
						}, 'json');
					<?php }else{ ?>
						$.post(base_url + 'mantenimiento/ajax/GuardarConfiguracionImpresora',{
							tipo: <?php echo $comprobante->ComprobanteTipo_id; ?>,
							f: f
						}, function(r){
							if(!r.response)
							{
								alert(r.message);
							}else
							{
								PrepararHoja();
								window.print();	
							}
						}, 'json');
					<?php } ?>
				})
				SetearImpresion();
			})
			
			function Volver()
			{
				<?php if($comprobante->id == 0){ ?>
					window.location.href = base_url + 'mantenimiento/configuracion';
				<?php }else{ ?>
					window.location.href = base_url + 'ventas/comprobante/' + id;
				<?php } ?>
			}
			function PrepararHoja()
			{
				$(".hidden").hide();
				$("body, .absolute, .row").css('background', 'none');
				$(".row,#container").css('border', 'none');
			}
			function SetearImpresion()
			{
				var f = '<?php echo $format; ?>'.split('|');
				for(var i = 0; i < f.length; i++)
				{
					var data = f[i].split('?');
					if(data[0] != '#detalle .row')
					{
						$(data[0]).attr('style', data[1]);						
					}else
					{
						var w = data[1].split('!');
						$('#detalle .row').each(function(i){
							$(this).attr('style',w[i]);
						})
					}
				}
			}
		</script>
		<style type="text/css" media="print">
			.no-print{ display: none; }
			@page{margin: 0;padding:0;}
		</style>
	</head>
	<body>
		<img class="no-print" id="boceto" src="../../../uploads/<?php echo $comprobante->ComprobanteTipo_id == 2 ? $this->conf->BoletaFoto : $this->conf->FacturaFoto; ?>" />
		<div id="botones" class="no-print">
			<button data-impresion="<?php echo $comprobante->Impresion; ?>" id="btnImprimirCancelar">Cancelar</button>
			<button data-impresion="<?php echo $comprobante->Impresion; ?>" id="btnImprimir">Imprimir</button>
		</div>
		<div id="container">
			<div class="margin-left margin no-print"></div>
			<div class="margin-right margin no-print"></div>
			<div title="Fecha de Emisión" class="absolute" id="fecha" style="left:80px;top:147px;"><?php echo ToDate($comprobante->FechaEmitido); ?></div>
			<div title="Nombre del Cliente" class="absolute" id="cliente" style="left:80px;top:127px;"><?php echo $comprobante->ClienteNombre; ?></div>
			<div title="<?php echo $comprobante->ComprobanteTipo_id == 3 ? 'RUC' : 'DNI' ?> del Cliente" class="absolute" id="ruc" class="text-right"  style="left:420px;top:127px;"><?php echo $comprobante->ClienteIdentidad; ?></div>
			<div title="Dirección del Cliente" class="absolute" id="direccion" style="left:80px;top:153px;"><?php echo $comprobante->ClienteDireccion; ?></div>
			<div title="Correlativo del Sistema" class="absolute" id="serie" class="text-right" style="left:500px;top:63px;"><?php echo $comprobante->Serie . '-' . $comprobante->Correlativo; ?></div>
			<div title="Sub Total" class="absolute" id="SubTotal" class="text-right" style="left:440px;top:410px;"><?php echo $this->conf->Moneda_id; ?><?php echo number_format($comprobante->SubTotal, 2); ?></div>
			<div title="Total a Pagar" class="absolute" id="total" class="text-right" style="left:540px;top:410px;"><?php echo $this->conf->Moneda_id; ?><?php echo number_format($comprobante->Total, 2); ?></div>
			<div title="Impuesto Total"  class="absolute" id="IvaTotal" class="text-right" style="left:400px;top:410px;"><?php echo $this->conf->Moneda_id; ?><?php echo $comprobante->ComprobanteTipo_id == 3 ? number_format($comprobante->IvaTotal, 2) : ''; ?></div>
			<div title="Impuesto" class="absolute" id="Iva" class="text-right" style="left:285px;top:410px;"><?php echo $comprobante->ComprobanteTipo_id == 3 ? $comprobante->Iva : ''; ?></div>
			<div title="Importe Total en Letras" class="absolute" id="TotalLetras" style="left:90px;top:192px;"><?php echo $comprobante->ComprobanteTipo_id == 3 ? $EnLetras->ValorEnLetras($comprobante->Total, $this->conf->moneda->Nombre) : ''; ?></div>
			<div title="Detalle del Comprobante"  style="left:90px;top:192px;" class="absolute" id="detalle">
				<div <?php echo 'style="width:60px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div><?php echo $c->Cantidad; ?> <?php echo $c->UnidadMedida_id; ?></div>
					<?php endforeach;?>
				</div>
				<div <?php echo 'style="width:280px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div><?php echo $c->ProductoNombre; ?></div>
					<?php endforeach;?>
				</div>
				
				<div <?php echo 'style="width:74px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div class="text-right"><?php echo $this->conf->Moneda_id; ?><?php echo number_format($c->PrecioUnitario, 2); ?></div>
					<?php endforeach;?>
				</div>
				
				<div <?php echo 'style="width:74px;"'; ?> class="row">
					<?php foreach($comprobante->Detalle as $k => $c):?>
						<div class="text-right"><?php echo $this->conf->Moneda_id; ?><?php echo number_format($c->PrecioUnitario * $c->Cantidad, 2); ?></div>
					<?php endforeach;?>
				</div>
			</div>		
		</div>
	</body>
</html>
