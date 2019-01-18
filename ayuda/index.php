<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-16 15:43:29
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-10 11:07:20
 */
include __DIR__."/layout/header.php";
include __DIR__."/layout/content.php";
$active= "uso";
?>
	<h1>
		Preguntas Frecuentes
	</h1>
	<ul class="preguntas">
		<li id="howToRegister">
			¿C&oacute;mo me registro en Avi cars?
		</li>
		<div id="howToRegister-info" style="display:none">
			<b>¿C&oacute;mo me registro en Avi cars?</b>
			<p>Puedes registrarte en AVIcars desde tu dispositivo m&oacute;vil o computadora, s&oacute;lo tienes que ingresar a  AVIcars.app y elegir el medio que utilizar&aacute;s para crear la cuenta. Puedes hacerlo ingresando tus datos manualmente o utilizando tu perfil de Facebook o Google. </p>
			<p>Para tener acceso a todas las funciones de AVIcars debes confirmar tu correo electr&oacute;nico y aceptar los t&eacute;rminos y condiciones.</p>
		</div>
		<li id="whoSeesMe">
			¿Qui&eacute;n puede ver mi informaci&oacute;n?
		</li>
		<div id="whoSeesMe-info" class="paragraph" style="display:none">
			<b>¿Qui&eacute;n puede ver mi informaci&oacute;n?</b>
			<p>Tú puedes determinar la privacidad y el estatus de la información que compartes en AVIcars.  Para tal efecto, tenemos tres niveles de seguridad:</p>
			<ul>
				<li>
					P&uacute;blico
				</li>
				<p class="list">Con el perfil p&uacute;blico toda la comunidad AVIcars puede ver, comentar y compartir el contenido que compartes en tu perfil y garages.</p>
				<li>
					Privado
				</li>
				<p class="list">El perfil privado restringe tu contenido, brind&aacute;ndote mayor seguridad, pero reduce el alcance de tus publicaciones.</p>
				<li>
					Secreto
				</li>
				<p class="list">El perfil secreto restringe a todos los tus seguidores y usuarios de AVIcars el contenido de tus publicaciones.</p>
			</ul>
		</div>
		<li id="howToSell">
			¿C&oacute;mo vendo mi auto?
		</li>
		<div id="howToSell-info" style="display:none">
			<b>¿C&oacute;mo vendo mi auto?</b>
			<p>Para vender tu auto dale clic al bot&oacute;n contextual y selecciona la opci&oacute;n “Servicios”, al hacerlo, abrir&aacute; una ventana emergente en la que deber&aacute;s elegir el auto que venderás y seleccionar el bot&oacute;n “Anuncia tu auto”.</p>
			<p>Ingresa el precio del veh&iacute;culo, la marca, submarca, a&ntilde;o, versi&oacute;n y algunas fotograf&iacute;as que muestren todos los &aacute;ngulos del auto. En el cuadro de texto puedes poner una breve descripci&oacute;n del auto o informaci&oacute;n de contacto.  Dale clic en “Anunciar” y ¡Listo!  Ahora s&oacute;lo espera la mejor oferta.</p>
		</div>
		<li id="cantAccess">
			No puedo ingresar a mi cuenta
		</li>
		<div id="cantAccess-info" style="display:none">
			<b>No puedo ingresar a mi cuenta</b>
			<p>Para iniciar sesi&oacute;n en AVIcars debes ingresar tu correo electr&oacute;nico o nombre de usuario y la contrase&ntilde;a que registraste. Si no recuerdas esta informaci&oacute;n, dale clic a la opci&oacute;n de “Recuperar Contrase&ntilde;a” e ingresa el correo electr&oacute;nico con el que validaste la cuenta. Con esa informaci&oacute;n, podr&aacute;s cambiar la contrase&ntilde;a y entrar a tu cuenta.</p>
		</div>
		<li id="beingBlocked">
			Han bloqueado mi cuenta
		</li>
		<div id="beingBlocked-info" style="display:none">
			<b>Han bloqueado mi cuenta</b>
			<p style="font-size: 22px;"> &iexcl;Pr&oacute;ximamente m&aacute;s informaci&oacute;n!</p>
		</div>
		<li id="certifiedGarages">
			Quiero certificar mis garages
		</li>
		<div id="certifiedGarages-info" style="display:none">
			<b>Quiero certificar mis garages</b>
			<p style="font-size: 22px;"> &iexcl;Pr&oacute;ximamente m&aacute;s informaci&oacute;n!</p>
		</div>
	</ul>
	<h1>
		Categor&iacute;as
	</h1>
	<div class="categorias">
		<div class="category">
			<h3>Uso del Sitio</h3>
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
		</div>
		<div class="category">
			<h3>Cuenta</h3>
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
		</div>
		<div class="category">
			<h3>Seguridad</h3>
			<ul>
				<li>
					<a href="/ayuda/seguridad/privacidad">Privacidad</a>
				</li>
				<li>
					<a href="/ayuda/seguridad/reportar">Reportar</a>
				</li>
			</ul>
		</div>
		<div class="category">
			<h3>Pol&iacute;ticas y Reglas</h3>
			<ul>
				<li>
					<a href="https://apoyovial.net/acerca-de/" target="_blank">Acerca de</a>
				</li>
				<li>
					<a href="/Terminos_y_condiciones_AVIcars.pdf">Aviso de Privacidad</a>
				</li>
			</ul>
		</div>
	</div>
<?php
include __DIR__."/layout/footer.php";