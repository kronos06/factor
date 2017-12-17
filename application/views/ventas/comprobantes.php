<script>
$(document).ready(function(){
	var colsNames = ['id', 'Serie', 'Cliente','Usuario','Tipo','Emitido', 'Estado', 'Total', 'Impreso'];
	var colsModel = [
		{name:'id',index:'c.id', width:55, hidden: true},
		{name:'Codigo',index:'Codigo', width:50},
		{name:'ClienteNombre', index:'ClienteNombre', formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('ventas/comprobante/' + rowObject.id, cellvalue);
			}},
		{name:'Usuario', index:'Usuario', width: 70, formatter: function(cellvalue, options, rowObject){
			return '<span title="' + rowObject.Nombre + '">' + cellvalue + '</span>';
		}},
		{name:'Tipo',index:'ComprobanteTipo_id', width:60,
			stype: 'select',
	        searchoptions: {
	            value: "<?php echo jqGrid_searchoptions($tipos, 'Value', 'Nombre'); ?>",
	            defaultValue: 't'
        }},
		{name:'FechaEmitido', index:'FechaEmitido', width: 50,
    		formatter: function(cellvalue, options, rowObject){
    			return ParseDate(cellvalue);
        		}},
		{name:'EstadoNombre',index:'EstadoNombre', width:60,
			stype: 'select',
	        searchoptions: {
	            value: "<?php echo jqGrid_searchoptions($estados, 'Value', 'Nombre'); ?>",
	            defaultValue: 't'
        },
		formatter: function(cellvalue, options, rowObject){
			if(rowObject.Estado==1) return '<span style="color:#D15600;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
			if(rowObject.Estado==2) return '<span style="color:#006E2E;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
			if(rowObject.Estado==3) return '<span style="color:#CC0000;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
			if(rowObject.Estado==4) return '<span style="color:purple;font-weight:bold;">' + rowObject.EstadoNombre + '</span>';
		}},
		{name:'Total', index:'Total', width: 40, align:"right", search: false},
		{name:'Impresion', index:'Impresion', width: 30, align:"center", search: false, formatter: function(cellvalue, options, rowObject){
			if(rowObject.ComprobanteTipo_id == 4) return '';
			if(rowObject.ComprobanteTipo_id == 1) return '<span class="glyphicon glyphicon-download-alt" style="color:green;"></span>';
			if(rowObject.Impresion == 0) return '<span title="Sin imprimir" style="color:#ddd;" class="glyphicon glyphicon-print"></span>';
			if(rowObject.Impresion == 1) return '<span title="Imprimiendo" style="color:orange;" class="glyphicon glyphicon-print"></span>';
			if(rowObject.Impresion == 2) return '<span title="Impreso" style="color:green;" class="glyphicon glyphicon-print"></span>';
		}}
	];	
		
	var grid = jqGridStart('list', 'pager', 'ventas/ajax/CargarComprobantes', colsNames, colsModel, '', '');

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/ventas/comprobante'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Comprobante
			</a>
			<h1>Comprobantes</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Comprobantes</li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="list"></table>
					<div id="pager"></div>
				</div>
			</div>
		</div>
	</div>
</div>

