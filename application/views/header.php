<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo SOFTWARE_NAME . ' - ' . $this->conf->RazonSocial; ?></title>

    <!-- Bootstrap core CSS -->
    <?php echo link_tag('assets/bootstrap/css/bootstrap.min.css'); ?>
    <?php echo link_tag('assets/bootstrap/css/light/bootstrap-theme.min.css'); ?>
    <?php echo link_tag('assets/bootstrap/css/light/style.css'); ?>
    <?php echo link_tag('assets/bootstrap/css/light/jqgrid/ui.jqgrid.css'); ?>
   
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery-1.10.2.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap-addons.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/ini.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery.validator.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery.form.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/jquery.jqGrid-4.5.4/js/jquery.jqGrid.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/jquery.jqGrid-4.5.4/src/i18n/grid.locale-es.js'); ?>"></script>
    
    <!-- Hight Charts -->
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/highcharts/highcharts.js'); ?>"></script>
    
    <script>
	    function base_url(url)
	    {
			return '<?php echo base_url('index.php'); ?>' + '/' + url;
		}

		var FormatoFecha = 'dd/mm/yyyy';
		var moneda = '<?php echo $this->conf->Moneda_id; ?>';
		var Modulos = '<?php echo MODULES; ?>';
	</script>
  </head>

  <body>

    <div class="container">
    
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		  <div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		      <span class="sr-only">Toggle navigation</span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="#"><?php echo $this->conf->RazonSocial; ?></a>
		  </div>
		
		  <!-- Collect the nav links, forms, and other content for toggling -->
		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		    <ul class="nav navbar-nav">
		      <?php foreach($this->menu as $m1): ?>
		      	  <?php if($m1->Url != '#'){ ?>
			      <li class="<?php echo $this->router->class == $m1->Class ? 'active' : ''; ?>">
			      	<a href="<?php echo base_url($m1->Url); ?>">
			      		<span class="glyphicon <?php echo $m1->Css; ?>"></span> <?php echo $m1->Nombre; ?>
		      		</a>
		      	  </li>
		      	  <?php }else{ ?>
			      <li class="dropdown <?php echo $this->router->class == $m1->Class ? 'active' : ''; ?>">
			        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon <?php echo $m1->Css; ?>"></span> <?php echo $m1->Nombre; ?> <b class="caret"></b></a>
			        <ul class="dropdown-menu">
					<?php foreach($m1->Hijos as $m2): ?>
				      <?php if($m2->Separador == 1): ?>
				      <li class="divider"></li>
				      <?php endif; ?>
				      <li><?php echo anchor($m2->Url, $m2->Nombre); ?></li>
					<?php endforeach; ?>
			        </ul>
			      </li>
		      	  <?php } ?>
		      <?php endforeach; ?>
		      <li>
		      	<a href="http://anexsoft.com/ventor/doc" target="_blank">
		      		<i class="glyphicon glyphicon-question-sign"></i> Manual del Usuario
		      	</a>
		      </li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
		      <li><a>Bienvenido <b title="<?php echo $this->user->Nombre; ?>"><?php echo $this->user->Usuario; ?></b></a></li>
		      <li><?php echo anchor('acceso/logout', 'Desconectarse'); ?></li>
		    </ul>
		  </div><!-- /.navbar-collapse -->
		</nav>
        <div class="row">
            <div class="col-md-12" style="margin-top:40px;">
                <?php if($this->version != '' && $this->version != SOFTWARE_VERSION): ?>
                    <div class="alert alert-info text-center">
                        Ya se encuentra disponible la versión <b><?php echo $this->version; ?></b> de Ventor, contáctese con su <b>proveedor</b> para adquirir la actualización.
                    </div>
                <?php endif; ?>
                <?php if(IS_DEMO == 1): ?>
                    <div class="alert alert-info text-center">
                        <blink>Esta es la <u>versión de prueba</u> para que puedas conocer que es lo que trae <b>Ventor</b>.</blink>
                    </div>
                    <a target="_blank" href="http://anexsoft.com/p/16/software-de-ventas-e-inventarios-para-php" class="btn btn-block btn-primary btn-lg">
                        Quiero ADQUIRIR el software VENTOR
                    </a>
                <?php endif; ?>
            </div>
        </div>