<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2018-10-03 12:25:26
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-10 15:46:46
 */
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/header.php";
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/content.php";
?>

<div class="paragraph">
	<a href="/ayuda">Servicio de Ayuda</a> > <a href="/ayuda/uso">Uso del Sitio</a> > <span>Perfil</span>
	<p class="titles">Perfil</p>
	<p>Las publicaciones que realices en tu perfil o en los garages se reflejar&aacute;n en el Timeline, estas aparecer&aacute;n en orden cronol&oacute;gico, lo que te dar&aacute; mayor control y organizaci&oacute;n. A continuaci&oacute;n te presentamos las funciones que puedes realizar en tu perfil:  </p>
	<b>Men&uacute; en tu perfil</b>
	<table>
		<thead>
			<tr>
				<th>Icono</th>
				<th>Nombre</th>
				<th>Descripci&oacute;n</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><img class="icon" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png"></td>
				<td>Mis seguidores</td>
				<td>Ver&aacute;s tu lista de seguidores y solicitudes pendientes (si tu perfil es privado). <a href="/ayuda/uso/seguidores">Ver m&aacute;s</a></td>
			</tr>
			<tr>
				<td><img class="icon" src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png"></td>
				<td>Mis garages</td>
				<td>Se enlistan tus garages, con su privacidad, su foto de avatar, su n&uacute;mero de "me enllanta" y n&uacute;mero de veces compartido. <a href="/ayuda/uso/garages">Ver m&aacute;s</a></td>
			</tr>
			<tr>
				<td><img class="icon" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png"></td>
				<td>Mis autos</td>
				<td>Se enlistan tus autos, con su privacidad, su foto de avatar, su n&uacute;mero de "me enllanta" y n&uacute;mero de veces compartido. Adem&aacute;s de su marca, modelo, a&ntilde;o y precio (si se est&aacute; en venta). <a href="/ayuda/uso/autos">Ver m&aacute;s</a></td>
			</tr>
			<tr>
				<td><img class="icon" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png"></td>
				<td>Mi expediente</td>
				<td>Hay dos listas: <b>Anuncios</b>, ver&aacute;s cu&aacute;les autos has anunciado y si ya fueron vendidos, y <b>Documentos</b>, ver&aacute;s los documentos que has cargado. <a href="#">Ver m&aacute;s</a></td>
			</tr>
		</tbody>
	</table>

	<b>&iquest;C&oacute;mo editar mi perfil?</b>
	<ul class="numbers">
		<li>Entra a tu perfil.</li>
		<li>Clic al bot&oacute;n editar <img class="icon" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png"> ubicado a la izquierda de tu foto de perfil.</li> 
		<li>A&ntilde;adir informaci&oacute;n:
			<ul>
				<li>&#8226; Campos Obligatorios: Nombre, Apellido, Nombre de usuario y Correo eletr&oacute;nico.</li>
				<li>&#8226; Campos Opcionales: Biograf&iacute;a, Tel&eacute;fonos, G&eacute;nero, Fecha de Nacimiento y Direcci&oacute;n.</li>
			</ul>
		</li>
		<li>Clic en "Guardar".</li>
	</ul>
	<b>&iquest;C&oacute;mo edito la privacidad de mi perfil?</b>
	<ul class="numbers">
		<li>Entra a tu perfil.</li>
		<li>Clic al bot&oacute;n privacidad (<img class="icon" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png">, <img class="icon" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_icon_Perfil-Pública_infotraffic.png"> &oacute; <img class="icon" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_Ojo-INvisible_infotraffic.png">) ubicado a la derecha de tu foto de perfil.</li> 
		<li>Escoger entre p&uacute;blico, privado o secreto.</li>
		<li>Clic en "Guardar".</li>
		<li>Confirmar este cambio haciendo clic en "Aceptar".</li>
	</ul>
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/footer.php";