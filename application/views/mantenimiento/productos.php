<script>
$(document).ready(function(){
	var colsNames = ['id','Nombre','Marca', 
		<?php if(HasModule('stock')): ?>
			'Stock',
		<?php endif; ?>
	'UDM', 'P.C', 'P.V','M.G (%)'];
	var colsModel = [ 
		{name:'id',index:'id', width:25, hidden: true},
		{name:'Nombre', index:'Nombre', sopt: 'like', formatter: function(cellvalue, options, rowObject){
				return jqGridCreateLink('mantenimiento/producto/' + rowObject.id, cellvalue);
			}},
		{name:'Marca', index:'Marca', width: 30},
		<?php if(HasModule('stock')): ?>
			{name:'Stock', index:'Stock', width: 45, align:"right", formatter: function(cellvalue, options, rowObject){
				return (cellvalue <= rowObject.StockMinimo ? '<span style="font-weight:bold;color:red;">' + cellvalue + '</span>' : '<span style="font-weight:bold;">' + cellvalue + '</span>') + '<a title="Abastecer Stock" class="btn btn-success btn-xs" style="margin-left:15px;" href="' + base_url('almacen/entrada/' + rowObject.id) + '" target="_blank"><i class="glyphicon glyphicon-plus"></i></a>';
			}},
		<?php endif; ?>
		{name:'UnidadMedida_id', index:'UnidadMedida_id', width: 30, search: false},
        {name:'PrecioCompra',index:'PrecioCompra', width: 30, align:"right", search: false, formatter:'decimal'},
		{name:'Precio',index:'Precio', width: 30, align:"right", search: false, formatter:'decimal'},
		{name:'MargenGanancia', index:'MargenGanancia', width: 30, align:"right", search: false, sortable: false, formatter: function(cellvalue, options, rowObject){
			return cellvalue + '%';
		}}
	];	
		
	var grid = jqGridStart('list', 'pager', 'mantenimiento/ajax/CargarProductos', colsNames, colsModel, '', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<a class="btn btn-default pull-right" href="<?php echo base_url('index.php/mantenimiento/producto'); ?>">
				<span class="glyphicon glyphicon-file"></span>
				Nuevo Producto
			</a>
			<h1>Productos</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Productos</li>
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

