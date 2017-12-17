<script>
	$(document).ready(function(){
		$("#btnRespaldar").click(function(){
			if(confirm('Este proceso puede tardar varios minutos, dependiendo la información que contenga.'))
			{
				$.post(base_url('respaldo/respaldar'), function(r){
					if(r.response)
					{
						alert("Se ha creado la copia con éxito, el proceso ha tardado " + r.message + 's.');
						location.reload(); 
					}
					else
					{
						alert(r.message);	
					}
				}, 'json')
			}

			return false;
		})
	})
</script>
<div class="row">
	<div class="col-md-12">
		<div class="page-header">
			<h1>
				Copias de seguridad <small>estas permiten respaldar su información.</small>
				<button id="btnRespaldar" class="pull-right btn btn-primary">
					<i class="glyphicon glyphicon-compressed"></i> Respaldar
				</button>
			</h1>
		</div>
		<ol class="breadcrumb">
		  <li><a href="<?php echo base_url('index.php'); ?>">Inicio</a></li>
		  <li class="active">Respaldo</li>
		</ol>
		
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php if(IS_DEMO == 1){ ?>
				<div class="alert alert-info text-center">
					<blink>Esta versión de prueba no permite realizar copias de seguridad.</blink>
				</div>
			<?php } else { ?>
				<?php if(!$copias->response): ?>
					<div class="alert alert-danger text-center">
						<?php echo $copias->message; ?>
					</div>
				<?php endif; ?>
				<?php if($copias->response): ?>
					<div class="alert alert-info text-center">
						Se <b>recomienda</b> que haga este proceso los <b>fines de semana/mes</b> siempre y cuando no se este usando el sistema.
						<br />El sistema <b>creara una copia de seguridad</b> para que pueda respaldar su información y mantenerla a salvo.
					</div>
					<div class="well well-sm text-center">Las <b>copias de seguridad</b> se encuentra en la carpeta <i><u>respaldos</u></i> a la raíz del proyecto.</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Respaldo</th>
								<th style="width:200px;">Fecha</th>
							</tr>
						</thead>
						<tbody>

							<?php if(count($copias->result) == 0): ?>
								<tr>
									<td colspan="2" class="text-center">
										No se han encontrado copias de seguridad.
									</td>
								<tr>
							<?php endif; ?>
							<?php if(count($copias->result) > 0): ?>
								<?php foreach($copias->result as $r): ?>
									<tr>
										<td>
											<a href="<?php echo base_url('respaldos/' .$r->Archivo); ?> ">
												<?php echo $r->Archivo; ?>
											</a>
										</td>
										<td><?php echo $r->Fecha; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>

						</tbody>
						<tfoot>
							<tr>
								<th>Respaldo</th>
								<th style="width:200px;">Fecha</th>
							</tr>
						</tfoot>
					</table>
				<?php endif; ?>
			<?php } ?>
		</div>
	</div>
</div>