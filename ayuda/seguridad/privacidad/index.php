<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2018-10-03 12:25:26
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-05 16:33:54
 */
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/header.php";
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/content.php";
?>

<div class="paragraph">
	<a href="/ayuda">Servicio de Ayuda</a> > <a href="/ayuda/seguridad">Seguridad</a> > <span>Privacidad</span>
	<p class="titles">Privacidad</p>
	<p>El control de privacidad te permite determinar el estatus de tus publicaciones y el uso que se le da a la informaci&oacute;n que compartes. Tenemos tres niveles de privacidad&colon;</p>
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

<?php
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/footer.php";