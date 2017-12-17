<script>
$(document).ready(function(){
	var colsNames = ['id','Nombre','Precio'];
	var colsModel = [ 
		{name:'id',index:'id', width:25, hidden: true},
		{name:'Nombre', index:'Nombre', formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('mantenimiento/servicio/' + rowObject.id, cellvalue);
			}},
		{name:'Precio',index:'Precio', width: 30, align:"right", search: false, formatter:'decimal'}
	];	
		
	var grid = jqGridStart('list', 'pager', 'mantenimiento/ajax/CargarServicios', colsNames, colsModel, '', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/mantenimiento/servicio'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Servicio
			</a>
			<h1>Servicios</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Servicios</li>
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

