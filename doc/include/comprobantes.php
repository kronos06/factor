<h2 class="page-header" style="margin-top:0;">Comprobantes</h2>
<p>Es un documento que se genera por la realización de una venta.</p>
<p>En <b>Ventor</b> disponemos de 4 tipos de comprobantes:</p>
<ul>
	<li><b>Proforma</b>: son las cotizaciones que realizamos a nuestros clientes, estas no se imprimen directamente, lo que hacen es generar un <b>PDF</b> para su post-impresión.</li>
	<li>
		<b>Boleta</b>: son aquellas ventas que generan un comprobante definido por un <u>correlativo</u>.
	 	<br />Para finalizar esta venta se necesitara colocar el talonario de boleta en la <u>impresora</u> verificando previamente que el correlativo del talonario coincida con el del sistema.
	 </li>
	<li>
		<b>Factura</b>: son aquellas ventas que generan un comprobante para las empresas (<b>Razon Social</b> + <b>Ruc</b>) definido por un <u>correlativo</u>.
	 	<br />Para finalizar esta venta se necesitara colocar el talonario de factura en la <u>impresora</u> verificando previamente que el correlativo del talonario coincida con el del sistema.
	 </li>
	<li><b>Menudeo</b>: son aquellas ventas que no requiere un comprobante físico y solo quedan registrados en el sistema.</li>
</ul>
<h3>Estados</h3>
<p>Los comprobantes manejan <b>estados</b>:</p>
<ul>
	<li><b style="color:#D15600;">Pendiente</b>: luego de crear un factura/boleta estas pasan a estado de <b>pendiente</b>, estas pueden cambiar su estado si se <u>anula/aprueba</u>.</li>
	<li><b style="color:#006E2E;">Aprobado</b>: generalmente se da cuando el comprobante ha sido impreso, en el caso de las <u>proformas/menudeo</u> luego de crearlas estas pasan a estar aprobadas automaticamente.</li>
	<li>
		<b style="color:#CC0000;">Anulado</b>: es un comprobante que ha sido dado de baja.
		<?php Info('Si se tiene activado la extensión de Stock el sistema nos solicitara devolver la mercadería.') ?>
	 </li>
	<li>
		<b style="color:purple;">Revisión</b>: son aquellos comprobantes que no coincidieron con el <b>correlativo</b>, por lo tanto se marcan con este estado para su post-revisión.
	 </li>
</ul>
<h3>¿Como agrego un producto?</h3>
<p>Haga click en la caja de texto dentro del area de <b>items</b> y escriba una letra, el sistema se encargara de buscar los productos.</p>
<p>Luego cuando aparesca la ventana con los productos, usted podra hacer <b>CTRL + CLICK</b> para ver el detalle completo del producto.</p>
<p>A la derecha se muestra información adicional como <u>precio de compra</u>, <u>precio de compra</u>, <u>stock</u>.</p>
<div class="well well-sm text-center">
	<?php Imagen('buscar_producto'); ?>
</div>
<?php Info('La imagen puede variar segun la versión de Ventor que tenga instalada.') ?>
<h3>Glosa</h3>
<p>Podemos dejar un comentario en el comprobate, este no se vera reflejado en la impresión.</p>

<h3>¿Mi correlativo no coincide?</h3>
<p>Siempre antes de realizar una impresión y el comprobante no tenga un correlativo previamente asignado, el sistema nos pedira la confirmación. Si estos correlativos no coinciden
aparecera una ventana popup para dar solución a este problema.
</p>
<div class="well well-sm text-center">
	<?php Imagen('correlativo_incorrecto'); ?>
</div>
<p>Si usted marco la alternativa <b>El correlativo del talonario es mayor al del sistema</b>, se crearan comprobantes en estado de <b style="color:purple;">revisión</b>.</p>
