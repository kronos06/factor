		<footer class="text-right">
			<hr />
			<img src="<?php echo base_url('assets/bootstrap/css/light/images/logo.png'); ?>" alt="Ventor" width="150" /><br />
	    	Desarrollado por el equipo <a target="blank" href="http://www.servicompsolucionescom">Servicomp</a><br /><br />
	    	<b>Ultima Actualización</b>: <?php echo LAST_UPDATED; ?><br />
	    	<b>Versión Instalada</b>: <?php echo SOFTWARE_VERSION; ?><br />
	    </footer>

		<?php if(IS_DEMO == 1): ?>
			<script>
				function FinContacto()
				{
					$("#mContact .modal-body").html('Muchas Gracias, pronto estaremos en contacto con usted.');
					$("#mContact .modal-footer .btn-default").text('Cerrar');
					$("#mContact .modal-footer .submit-ajax-button").fadeOut();
				}
			</script>

		<?php endif; ?>

    </div>

  </body>
</html>
