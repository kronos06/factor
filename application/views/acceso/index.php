<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo SOFTWARE_NAME; ?></title>

    <!-- Bootstrap core CSS -->
    <?php echo link_tag('assets/bootstrap/css/bootstrap.min.css'); ?>
    <?php echo link_tag('assets/bootstrap/css/light/bootstrap-theme.min.css'); ?>
    <?php echo link_tag('assets/bootstrap/css/light/style.css'); ?>
    
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery-1.10.2.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap-addons.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/ini.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery.validator.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery.form.js'); ?>"></script>
    
    <script>
	    function base_url(url)
	    {
			return '<?php echo base_url('index.php'); ?>' + '/' + url;
		}

		var FormatoFecha = 'dd/mm/yyyy';
	</script>
  </head>

  <body>

    <div class="container">

		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<div class="page-header text-center">
							<h1>Acceso al Sistema</h1>
						</div>
						<?php if(IS_DEMO == 1): ?>
							<div class="alert alert-info text-center">
								<blink>Bienvenido a la versión de prueba de <b>Ventor</b>.<br/>Ingresa con el <b>usuario</b> <u>demo</u>, y <b>contraseña</b> <u>demo</u>.</blink>
							</div>
						<?php endif; ?>
						<div class="panel panel-default">
						  <div class="panel-heading">
						  		Ingrese sus datos para acceder al Sistema <b class="pull-right"><?php echo SOFTWARE_VERSION; ?></b>
						  </div>
						  <div class="panel-body">
							<?php echo form_open('acceso/ajax/Acceder', array('class' => 'upd form-horizontal')); ?>
							  <div class="form-group">
							    <label for="inputEmail3" class="col-sm-2 control-label">Empresa</label>
							    <div class="col-sm-10">
							      <?php echo Select('Empresa_id', $empresas, 'Nombre', 'id'); ?>
							    </div>
							  </div>
							  <div class="form-group">
							    <label for="inputEmail3" class="col-sm-2 control-label">Usuario</label>
							    <div class="col-sm-10">
							      <input type="text" name="Usuario" class="form-control required" placeholder="Ingrese su nombre de Usuario" value="<?php if(IS_DEMO == 1) echo 'demo'; ?>" />
							    </div>
							  </div>
							  <div class="form-group">
							    <label for="inputPassword3" class="col-sm-2 control-label">Contraseña</label>
							    <div class="col-sm-10">
							      <input type="password" name="Contrasena" class="form-control required" placeholder="Ingrese su Contraseña" value="<?php if(IS_DEMO == 1) echo 'demo'; ?>" />
							    </div>
							  </div>
							  <div class="form-group">
							    <div class="col-sm-offset-2 col-sm-10 text-right">
							      <button type="submit" class="btn btn-info submit-ajax-button">Acceder</button>
							    </div>
							  </div>
							<?php echo form_close(); ?>
						  </div>
						</div>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
		</div>
	
		<footer class="text-center">
			<hr />
			<img src="<?php echo base_url('assets/bootstrap/css/light/images/logo.png'); ?>" alt="Ventor" />
	    </footer>

    </div>

  </body>
</html>