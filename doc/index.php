<?php 
  define('page', isset($_GET['p']) ? $_GET['p'] . '.php' : 'bienvenido.php');

  function Info($m)
  {
    echo "<div class=\"text-info well well-sm\" style=\"font-size:12px;margin:5px 0 10px 0;\">$m</div>";
  }

  function Imagen($img)
  {
    echo "<img src=\"images/$img.png\" />";
  }

  function Active($menu)
  {
    echo str_replace('.php', '', page) == $menu ? 'active' : '';
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Documentación oficial del Software Ventor.">
    <meta name="author" content="">

    <title>Documentación de Ventor</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/bootstrap-theme.min.css" rel="stylesheet" />
    
    <script type="text/javascript" src="assets/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/ini.js"></script>
    <script type="text/javascript" src="assets/js/jquery.validator.js"></script>
    <script type="text/javascript" src="assets/js/jquery.form.js"></script>

    <style>
      body{background:url(images/bg.jpg);}
      .list-group-item.active a{color:#eee;}
    </style>
  </head>
  <body>   

	<div class="container" style="width:90%;">
	
		<div class="row">
      <div class="page-header">
        <h1>Documentación de Ventor</h1>
      </div>
			<div class="col-md-2">

        <div class="panel panel-default">
          <div class="panel-heading">Menu de Ayuda</div>
          <ul class="list-group">
            <li class="list-group-item <?php Active('inicio'); ?>"><a href="?p=inicio">Inicio</a></li>
            <li class="list-group-item <?php Active('extensiones'); ?>"><a href="?p=extensiones">Extensiones</a></li>
            <li class="list-group-item <?php Active('instalacion'); ?>"><a href="?p=instalacion">Instalación</a></li>
          </ul>

          <div class="panel-heading">Ventas</div>
          <ul class="list-group">
            <li class="list-group-item <?php Active('comprobantes'); ?>"><a href="?p=comprobantes">Comprobantes</a></li>
            <li class="list-group-item <?php Active('reportes'); ?>"><a href="?p=reportes">Reportes</a></li>
          </ul>
          <div class="panel-heading">Almacen</div>
          <ul class="list-group">
            <li class="list-group-item <?php Active('entrada_salida'); ?>"><a href="?p=entrada_salida">Entrada/Salida</a></li>
            <li class="list-group-item <?php Active('kardex'); ?>"><a href="?p=kardex">Kardex</a></li>
          </ul>
          <div class="panel-heading">Mantenimiento</div>
          <ul class="list-group">
            <li class="list-group-item <?php Active('usuario'); ?>"><a href="?p=usuario">Usuario</a></li>
            <li class="list-group-item <?php Active('clientes'); ?>"><a href="?p=clientes">Clientes</a></li>
            <li class="list-group-item <?php Active('productos'); ?>"><a href="?p=productos">Productos</a></li>
            <li class="list-group-item <?php Active('servicios'); ?>"><a href="?p=servicios">Servicios</a></li>
            <li class="list-group-item <?php Active('backup'); ?>"><a href="?p=backup">Copias de Seguridad</a></li>
            <li class="list-group-item <?php Active('configuracion'); ?>"><a href="?p=configuracion">Configuración</a></li>
          </ul>
        </div>

			</div>
			<div class="col-md-10">
          <?php require_once 'include/' . page; ?>
          <footer class="text-center">
            <hr />
            <img src="http://anexsoft.com/ventor/assets/bootstrap/css/light/images/logo.png" alt="Ventor" />
          </footer>
			</div>
		</div>
	
    </div>

  </body>
</html>