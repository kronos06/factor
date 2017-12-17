<h2 class="page-header" style="margin-top:0;">Instalación</h2>
<h3>Requerimientos</h3>
<ul>
	<li>Se requiere obligatoriamente una conexión a Internet.</li>
	<li>Un monitor Widescreen de 17' a más.</li>
	<li>Una PC que como mínimo debería ser usada para Ofimática (Office 2007) y trabaje sin problema.<br />En otras palabras mejores que Pentium IV.</li>
</ul>
<h3>Guía de Instalación</h3>
<p>Para poder hacer funcionar <b>Ventor</b> necesitamos crear un servidor <b>web apache</b> con una base de datos <b>MySQL</b>.</p>
<p>Para nosotros sera una tarea muy fácil ya que existen diversos programas que configuran todo esto por nosotros en este caso usaremos <b>Wamp</b>.</p>
<ol>
	<li>Instalar la versión de Wamp Server dependiedo de nuestro Windows. (64Bits/32Bits).</li>
	<li>
		Copiar la carpeta <b>Ventor</b> que viene en CD de instalación en "C:\wamp\www".
		<?php Info('Cuando instalo Wamp Server por defecto la carpeta de instalación es la mencionada anteriormente, del caso contrario muevalo a la carpeta donde usted determinó la instalación.'); ?>
	</li>
	<li>
		Ir al menu inicio y click en start Wamp Server.<br /><br />
		<?php Imagen('inicio_start_wampserver'); ?><br /><br />
	</li>
	<li>
		En la barra de tareas en la parte derecha hacemos click en el icono de "Mostrar iconos ocultos".
		<br /><br />
		<div class="thumbnail">
			<?php Imagen('barra_tarea_iconos_ocultos_click'); ?>
		</div>
	</li>
	<li>
		Luego de eso hacemos click al icono de Wamp Server <?php Imagen('icono_wamp_server_launch'); ?> y le damos click en <b>Start All Services</b>.
		<br /><br />
		<?php Imagen('wamp_start_all_services'); ?>
		<br /><br />
	</li>
	<li>Abra su navegador <b>Firefox</b> o <b>Google Chrome</b> e ingrese la siguiente URL 'http://localhost/ventor'.<br /><br />
		<?php Imagen('navegador_localhost_url'); ?><br /><br />
	</li>
	<li>Listo, el programa comenzo a funcionar.</li>
</ol>
<?php Info('<b>Recomendamos</b> crear un acceso directo al escritorio o establecer como página predeterminada.<br />Puede leer un tutorial de como hacerlo en el siguiente enlace: <a target="_blank" href="http://es.kioskea.net/faq/3739-crear-un-acceso-directo-a-una-pagina-web-en-el-escritorio">http://es.kioskea.net/faq/3739-crear-un-acceso-directo-a-una-pagina-web-en-el-escritorio</a>'); ?>
<h3>Configurar Wamp Server para que se inicie automaticamente</h3>
<p>Para evitar repetir el paso 3, 4 y 5 cada ves que se inicie la PC podemos hacer que Wamp Server se ejecute automaticamente.</p>
<ol>
	<li>Vamos a Inicio → Panel de control.</li>
	<li>Herramientas administrativas.</li>
	<li>Servicios.</li>
	<li>Buscamos <b>wampapache</b> y le damos doble click.</li>
	<li>En “Tipo de inicio” selecciona la opción “Automático” y dale click al botón “Aceptar”.</li>
	<li>Repíte los pasos 4 y 5 pero esta vez con el servicio <b>“wampmysqld”</b>.</li>
</ol>
<?php Imagen('wamp_services_automatic'); ?>}
<h3>Otros softwares</h3>
<p>También podemos usar <b>XAMMP</b> en ves de Wamp Server para hacer correr a nuestro aplicativo.</p>