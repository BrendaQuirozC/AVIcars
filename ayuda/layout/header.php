<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-16 15:45:51
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-10 11:09:38
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
?>
<!DOCTYPE html>
<html>
	<head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<meta property="og:title" content="AVI cars by Infotraffic | Ayuda">
	    <meta property="og:description" content="Vende compra y cuida tu carro con AVI cars">
	    <meta property="og:image" content="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/img/main.jpg" ?>">
	    <meta property="og:url" content="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'] ?>">
	    <meta property="og:site_name" content="ApoyoVial.">
	    <meta property="og:type" content="website" />
	    <meta name="twitter:title" content="AVI cars by Infotraffic | Ayuda">
	    <meta name="twitter:description" content="Vende compra y cuida tu carro con AVI cars">
	    <meta name="twitter:image" content=" <?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/img/main.jpg" ?>">
	    <meta name="twitter:image:alt" content="AVI cars">
	    <meta name="twitter:card" content="summary_large_image">
		<title>Avi cars | Ayuda</title>
		<script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
   		<script type="text/javascript" src="/js/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="/ayuda/css/style.css">
	</head>
	<body>
		<nav>
			<div class="nav-content">
				<a href="/ayuda">
					<img class="logo logo-bg" src="/img/logo_horizontal.png">
				</a>
				<p>Ayuda</p>
				<ul>
					<li class="hidden-695">
						<input type="text" name="busqueda" placeholder="B&uacute;squeda">
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_busqueda_infotraffic.png">
					</li>
					<li>
						<a href="/">Inicar Sesi&oacute;n</a>
					</li>
				</ul>
			</div>
		</nav>
		<div class="nav-title">
			<h1>Servicio de Ayuda</h1>
			<div class="show-695">
				<input type="text" name="busqueda" placeholder="B&uacute;squeda">
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_busqueda_infotraffic.png">
			</div>
		</div>
		<div class="topnavbar submenu hidden-bg show-md">
			<p>Servicios</p>
			<img class="arrow" src="/img/icons/down.png">
			<ul class="ul-submenu ul-hidden">
				<li>
					Uso del Sitio
					<img src="/img/icons/down.png" class="icon more">
					<ul>
						<li>
							<a href="/ayuda/uso/timeline">Timeline</a>
						</li>
						<li>
							<a href="/ayuda/uso/perfil">Perfil</a>
						</li>
						<li>
							<a href="/ayuda/uso/garages">Garages</a>
						</li>
						<li>
							<a href="/ayuda/uso/autos">Autos</a>
						</li>
						<li>
							<a href="/ayuda/uso/anuncios">Anuncios</a>
						</li>
						<li>
							<a href="/ayuda/uso/publicaciones">Publicaciones</a>
						</li>
						<li>
							<a href="/ayuda/uso/seguidores">Seguidores</a>
						</li>
						<li>
							<a href="/ayuda/uso/comunidad">Comunidad</a>
						</li>
					</ul>
				</li>
				<li>
					Cuenta
					<img src="/img/icons/down.png" class="icon more">
					<ul>
						<li>
							<a href="/ayuda/cuenta/crear">Crear cuenta</a>
						</li>
						<li>
							<a href="/ayuda/cuenta/configurar">Configuraci&oacute;n</a>
						</li>
						<li>
							<a href="/ayuda/cuenta/usodedatos">Uso de mis datos</a>
						</li>
						<li>
							<a href="/ayuda/cuenta/registroGoogle">Registro con Google</a>
						</li>
						<li>
							<a href="/ayuda/cuenta/registroFacebook">Registro con Facebook</a>
						</li>
						<li>
							<a href="/ayuda/cuenta/inhabilitar">Inhabilitar mi cuenta</a>
						</li>
						<li>
							<a href="/ayuda/cuenta/borrar">Borrar cuenta</a>
						</li>
					</ul>
				</li>
				<li>
					Seguridad
					<img src="/img/icons/down.png" class="icon more">
					<ul>
						<li>
							<a href="/ayuda/seguridad/privacidad">Privacidad</a>
						</li>
						<li>
							<a href="/ayuda/seguridad/reportar">Reportar</a>
						</li>
					</ul>
				</li>
				<li>
					Pol&iacute;ticas y Reglas
					<img src="/img/icons/down.png" class="icon more">
					<ul>
						<li>
							<a href="https://apoyovial.net/acerca-de/" target="_blank">Acerca de</a>
						</li>
						<li>
							<a href="/Terminos_y_condiciones_AVIcars.pdf">Aviso de Privacidad</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="/buzon" target="_blank">Sugerencias</a>
				</li>
			</ul>
			
		</div>
		