<?php
	//array_debug($usuario); 
?>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1><?php echo $usuario == null ? "Nuevo Usuario" : $usuario->Nombre; ?></h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li><a href="<?php echo base_url('index.php/mantenimiento/usuarios'); ?>">Usuarios</a></li>
		  <li class="active"><?php echo $usuario == null ? "Nuevo Item" : $usuario->Nombre; ?></li>
		</ol>
		<div class="row">
			<div class="col-md-12">
				<div class="well well-sm">(*) Campos obligatorios</div>
				<?php echo form_open('mantenimiento/usuariocrud', array('class' => 'upd')); ?>
				<?php if($usuario != null): ?>
				<input type="hidden" name="id" value="<?php echo $usuario->id; ?>" />
				<?php endif; ?>
				  <div class="form-group">
				    <label>Tipo de Usuario (*)</label>
				    <?php echo Select('Tipo', $tipos, 'Nombre', 'Value', isset($usuario) ? $usuario->Tipo : 0); ?>
				  </div>
				  <div class="form-group">
				    <label>Nombre (*)</label>
				    <input autocomplete="off" name="Nombre" type="text" class="form-control required" placeholder="Ingrese el Nombre y Apellido" value="<?php echo $usuario != null ? $usuario->Nombre : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Usuario (*)</label>
				    <input autocomplete="off" name="Usuario" type="text" class="form-control required" placeholder="Usuario de Acceso" value="<?php echo $usuario != null ? $usuario->Usuario : null; ?>" />
				  </div>
				  <div class="form-group">
				    <label>Contraseña <?php echo isset($usuario) ? '' : '(*)'; ?></label>
				    <input autocomplete="off" name="Contrasena" type="password" class="form-control password <?php echo !isset($usuario) ? 'required' : ''; ?>" placeholder="Contraseña de ingreso" value="" />
					<?php if(isset($usuario)): ?>
						<span class="helptext">Si desea actualizar la contraseña ingrese una nueva</span>
					<?php endif; ?>
				  </div>
				  <?php if(isset($usuario)): ?>
				  <div class="form-group">
				    <label>Confirmar Contraseña</label>
				    <input autocomplete="off" name="Contrasena" type="password" class="form-control password" placeholder="Confirmar contraseña de ingreso" value="" />
				  </div>
				  <?php endif; ?>
				  	<div class="clearfix text-right">
					  <?php if(isset($usuario)): ?>
					  	<button type="button" class="btn btn-danger submit-ajax-button del" value="<?php echo base_url('index.php/mantenimiento/usuarioeliminar/' . $usuario->id); ?>">Eliminar</button>
				  	  <?php endif; ?>
					  	<button type="submit" class="btn btn-info submit-ajax-button">Guardar</button>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>