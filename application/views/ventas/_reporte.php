<?php
	$TGanancia = 0;
	$TVendido = 0;
	
	$TBoleta = 0;
	$TFactura = 0;
	$TMenudeo = 0;
	
	//array_debug($reporte);
?>
<script>
	$(document).ready(function(){
		$("#dvFiltro select").change(function(){
			Reporte($("#dvAnio select").val(), $("#dvMes select").val());
		})
		$(".aReporteDiarioDetalle").click(function(){
			var fecha = $(this).data('fecha');
			AjaxPopupModal('aReporteDiarioDetalle', 'Detalle de Productos Vendidos', 'ventas/ajax/SubReporte', { tipo: 'reportediariodetalle', fecha: fecha });
		})

		<?php if($tipo == 1 or $tipo == 2 or $tipo == 3): ?>
		$("#liGrafica").click(function(){
			if(!$(this).hasClass('loared'))
			{
				$(this).addClass('loaded');
				setTimeout(function(){
					CargarGrafica();					
				})
			}
		})
		<?php endif; ?>
	})
	
	<?php if($tipo == 1 or $tipo == 2 or $tipo == 3): ?>
	function CargarGrafica()
	{
		$(function () {
		    var chart;
		    $(document).ready(function() {
		        chart = new Highcharts.Chart({
		            chart: {
		                renderTo: 'grafica',
		                type: 'line',
		                marginLeft: 60,
		                marginRight: 110,
		                marginBottom: 50,
		                marginTop: 50,
		            },
		            title: null,
		            xAxis: {
		                categories: [<?php echo $reporte['Grafica']['Categoria']; ?>]
		            },
		            yAxis: {
		                title: null,
		                plotLines: [{
		                    value: 0,
		                    width: 1,
		                    color: '#808080'
		                }],
		                min: 0
		            },
		            tooltip: {
		                formatter: function() {
		                    return '<b>'+ this.series.name +'</b><br /><b><?php
		                    	if($tipo == 1) echo 'Día';
		                    	if($tipo == 2) echo 'Mes';
		                    	if($tipo == 3) echo 'Año';
		                     ?></b>: ' + this.x + ' <br/><?php echo $this->conf->Moneda_id; ?> ' + Highcharts.numberFormat(this.y, 2);
		                }
		            },
		            legend: {
		                layout: 'vertical',
		                align: 'right',
		                verticalAlign: 'middle',
		                borderWidth: 0
		            },
		            series: [
	 		            {
		 		            name: 'Vendido',
			                data: [<?php echo $reporte['Grafica']['Vendido']; ?>]
		            	},
	 		            {
		 		            name: 'Ganado',
			                data: [<?php echo $reporte['Grafica']['Ganado']; ?>]
		            	},
		            ]
		        });
		    });
		    
		});	
	}
	<?php endif; ?>
</script>
<div id="dvFiltro" class="row">
	<div class="col-md-7"></div>
	<div class="col-md-3">
		<?php if($tipo == 1): ?>
		<div id="dvMes" class="form-group">
			<?php echo Select('mes', Months(), 'mes', 'valor', $m); ?>
		</div>
		<?php endif; ?>
		<?php if($tipo==4 || $tipo==5 || $tipo==7): ?>
		<div id="dvMes" class="form-group">
			<?php echo Select('mes', Months(), 'mes', 'valor', $m, true); ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="col-md-2">
		<?php if($tipo == 1 || $tipo == 2 || $tipo == 4 || $tipo == 5 || $tipo == 6 || $tipo == 7): ?>
		<div id="dvAnio" class="form-group">
			<?php echo Select('anio', Years($this->conf->Anio), 'anio', 'anio', $y); ?>
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="table-responsive">
<?php if($tipo==1 || $tipo==2 || $tipo==3){ ?>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
		  <li class="active"><a href="#r1" data-toggle="tab">Tabla</a></li>
		  <li id="liGrafica"><a href="#r2" data-toggle="tab">Gráfíca</a></li>
		</ul>
		
		<!-- Tab panes -->
		<div class="tab-content">
		  <div class="tab-pane in active" id="r1">
			<table class="table report">
				<thead>
					<tr>
						<th colspan="6"><?php echo $titulo; ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th></th>
						<th style="width:140px;" class="text-right">Boleta</th>
						<th style="width:140px;" class="text-right">Factura</th>
						<th style="width:140px;" class="text-right">Menudeo</th>
						<th style="width:140px;" class="text-right">Ganado</th>
						<th style="width:140px;" class="text-right">Vendido</th>
					</tr>
					<?php if(count($reporte['Tabla'])==0): ?>
					<tr>
						<th colspan="6" class="text-center">No hay resultados para este criterio</th>
					</tr>
					<?php endif; ?>
					<?php foreach($reporte['Tabla'] as $r): ?>
					<tr class="
						<?php
							if($tipo == 1)
							{
								echo toDate($r->FechaEmitido) == date('d/m/Y') ? 'today' : '';	
							}
							if($tipo == 2)
							{
								$f = explode('/', toDate($r->FechaEmitido));
								echo  $f[1] . '/' . $f[2] == date('m/Y') ? 'today' : '';	
							}
							if($tipo == 3)
							{
								$f = explode('/', toDate($r->FechaEmitido));
								echo  $f[2] == date('Y') ? 'today' : '';	
							}
						?>
					">
						<th class="text-right" style="width:60px;">
							<?php if($tipo == 1){ ?>
								<a class="aReporteDiarioDetalle" title="Haga click para ver un resumen de los productos vendidos" href="#" data-fecha="<?php echo $r->FechaEmitido; ?>">
									<?php echo DateFormat(toDate($r->FechaEmitido), $tipo); ?>
								</a>
							<?php }else {?>
								<?php echo DateFormat(toDate($r->FechaEmitido), $tipo); ?>
							<?php } ?>
						</th>
						<td class="text-right"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Boleta, 2); $TBoleta += $r->Boleta; ?></td>
						<td class="text-right"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Factura, 2); $TFactura += $r->Factura;  ?></td>
						<td class="text-right"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Menudeo, 2); $TMenudeo += $r->Menudeo;  ?></td>
						<td class="text-right bg-earned"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Ganancia, 2); $TGanancia += $r->Ganancia; ?></td>
						<td class="text-right bg-sold"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Vendido, 2); $TVendido += $r->Vendido; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th class="text-right">Total</th>
						<td class="text-right"><?php echo $this->conf->Moneda_id; ?> <b><?php echo number_format($TBoleta, 2); ?></b></td>
						<td class="text-right"><?php echo $this->conf->Moneda_id; ?> <b><?php echo number_format($TFactura, 2); ?></b></td>
						<td class="text-right"><?php echo $this->conf->Moneda_id; ?> <b><?php echo number_format($TMenudeo, 2); ?></b></td>
						<td style="width:140px;" class="total text-right bg-earned-total"><?php echo $this->conf->Moneda_id; ?> <b><?php echo number_format($TGanancia, 2); ?></b></td>
						<td style="width:140px;" class="total text-right bg-sold-total"><?php echo $this->conf->Moneda_id; ?> <b><?php echo number_format($TVendido, 2); ?></b></td>
					</tr>
				</tfoot>
			</table>		  
		  </div>
		  <div class="tab-pane" id="r2">
		  	<div id="grafica"></div>	  	
		  </div>
		</div>
	<?php }else if($tipo==4 || $tipo==5 || $tipo==7){ ?>
		<table class="table report">
			<thead>
				<tr>
					<th colspan="4"><?php echo $titulo; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Descripción</th>
					<th style="width:100px;">
						<?php if($tipo==7) echo 'N de Ventas'; ?>
						<?php if($tipo==5) echo 'N de Veces'; ?>
						<?php if($tipo==4) echo 'Cantidades'; ?>
					</th>
					<th style="width:200px;" class="text-right">Ganado</th>
					<th style="width:200px;" class="text-right">Vendido</th>
				</tr>
				<?php if(count($reporte)==0): ?>
				<tr>
					<th colspan="4" class="text-center">No hay resultados para este criterio</th>
				</tr>
				<?php endif; ?>
				<?php foreach($reporte as $r): ?>
				<tr>
					<th style="font-size:11px;" title="<?php echo $r->Nombre; ?>" ><?php echo strlen($r->Nombre) > 80 ? $r->Nombre . '..' : $r->Nombre; ?></th>
					<td><?php echo $r->Cantidad; ?><?php if($tipo == 4) echo ' ' . $r->UnidadMedida_id; ?></td>
					<td class="text-right bg-earned"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Ganado, 2); ?></td>
					<td class="text-right bg-sold"><?php echo $this->conf->Moneda_id; ?> <?php echo number_format($r->Vendido, 2); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php }else if($tipo == 6){ ?>
		<div class="well well-sm text-center">Son los productos más vendidos dentro de un trimestre.</div>
		<table class="table report">
			<thead>
				<tr>
					<th colspan="4"><?php echo $titulo; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($reporte as $k => $r1): ?>
					<tr class="sub-legend">
						<th colspan="3"><?php echo $k; ?></th>
						<th class="text-right">
							<?php if($k == '1er Trimestre') echo '[Enero-Marzo]'; ?>
							<?php if($k == '2do Trimestre') echo '[Abril-Junio]'; ?>
							<?php if($k == '3er Trimestre') echo '[Julio-Setiembre]'; ?>
							<?php if($k == '4to Trimestre') echo '[Octubre-Diciembre]'; ?>
						</th>
					</tr>
					<tr>
						<th>Descripción</th>
						<th style="width:150px;" class="text-right">Cantidad</th>
						<th style="width:150px;" class="text-right">Ganado</th>
						<th style="width:150px;" class="text-right">Vendido</th>
					</tr>
					<?php if(count($r1) == 0): ?>
					<tr>
						<td colspan="4" class="text-center">
							<?php echo date('Y') == $y ? 'Aún no hay suficientes datos para generar un reporte del trimestre actual.' : 'No se han encontrado datos guardados para este trimestre.' ?></td>
					</tr>
					<?php endif; ?>
					<?php foreach($r1 as $r2): ?>
					<tr>
						<td><?php echo $r2->ProductoNombre; ?></td>
						<td class="text-right"><?php echo $r2->Cantidad . ' ' . $r2->UnidadMedida_id; ?></td>
						<td class="text-right bg-earned"><?php echo $this->conf->Moneda_id . ' ' . number_format($r2->Ganancia, 2); ?></td>
						<td class="text-right bg-sold"><?php echo $this->conf->Moneda_id . ' ' .  number_format($r2->PrecioTotal, 2); ?></td>
					</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php } ?>
</div>