<?php //array_debug($tipos); ?>
<script>
$(document).ready(function(){
	var colsNames = ['id', 'Tipo', 'Producto', 'Fecha', 'CNT', 'UDM', 'Usuario', 'Comprobante'];
	var colsModel = [ 
		{name:'id',index:'id', width:25, hidden: true},
		{name:'Tipo', index:'Tipo', width:60,
		stype: 'select',
	        searchoptions: {
	            value: "<?php echo jqGrid_searchoptions($tipos, 'Value', 'Nombre'); ?>",
	            defaultValue: 't'
        }, formatter: function(cellvalue, options, rowObject){
				switch(cellvalue)
				{
					case '1':
						return '<b style="color:#3F4C6B;">Entrada</b>'
						break;
					case '2':
						return '<b style="color:#006E2E;">Salida</b>'
						break;
					case '3':
						return '<b style="color:purple;">Devolución</b>'
						break;
				}
			}},
		{name:'ProductoNombre', index:'ProductoNombre', sopt: 'like', formatter: function(cellvalue, options, rowObject){
				return '<a target="_blank" href="' + base_url('mantenimiento/producto/' + rowObject.Producto_id) + '">' + cellvalue + '</a>';
			}},
		{name:'Fecha',index:'Fecha', width: 50,
    		formatter: function(cellvalue, options, rowObject){
    			return ParseDate(cellvalue);
        		}},
		{name:'Cantidad', index:'Cantidad', width: 45, align:'right'},
		{name:'UnidadMedida_id', index:'UnidadMedida_id', width: 30, search: false},
		{name:'UsuarioNombre',index:'UsuarioNombre', width: 70},
		{name:'Comprobante_id',index:'Comprobante_id', width: 50, align: 'center', formatter: function(cellvalue, options, rowObject){
			return cellvalue != null ? '<a title="Ver Comprobante" target="_blank" href="' + base_url('ventas/comprobante/' + cellvalue) + '"><i class="glyphicon glyphicon-search"></i></a>' : '';
		}},
	];	
		
	var grid = jqGridStart('list', 'pager', 'almacen/ajax/CargarAlmacen', colsNames, colsModel, '', '' );

	grid.jqGrid('filterToolbar', {stringResult: true, searchOnEnter: true});
})
</script>
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
			<h1>Almacén</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Entrada/Salida</li>
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