<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2018-10-05 16:10:48
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-10 11:07:51
 */
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/header.php";
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/content.php";
?>
<div class="categorias">
	<div class="single-page">
		<div class="paragraph">
			<a href="/ayuda">Servicio de Ayuda</a> > <span>Cuenta</span>
			<p class="titles">Cuenta</p>
		</div>
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
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/footer.php";