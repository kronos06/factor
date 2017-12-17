<script>
$(document).ready(function(){
	var colsNames = ['id','Nombre', 'Usuario', 'Rol'];
	var colsModel = [ 
		{name:'id',index:'id', width:55, hidden: true},
		{name:'Nombre', index:'Nombre', sopt: 'like', formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('mantenimiento/usuario/' + rowObject.id, cellvalue);
			}},
		{name:'Usuario', index:'Usuario', width: 30},
		{name:'Rol', index:'Tipo', width: 30, search: false}
	];	
		
	var grid = jqGridStart('list', 'pager', 'mantenimiento/ajax/CargarUsuarios', colsNames, colsModel, '', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/mantenimiento/usuario'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Usuario
			</a>
			<h1>Usuarios</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Usuarios</li>
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

