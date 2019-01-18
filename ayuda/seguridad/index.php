<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2018-10-05 16:19:10
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-09 12:28:53
 */
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/header.php";
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/content.php";
?>
<div class="categorias">
	<div class="single-page">
		<div class="paragraph">
			<a href="/ayuda">Servicio de Ayuda</a> > <span>Seguridad</span>
			<p class="titles">Seguridad</p>
		</div>
		<ul>
			<li>
				<a href="/ayuda/seguridad/privacidad">Privacidad</a>
			</li>
			<li>
				<a href="/ayuda/seguridad/reportar">Reportar</a>
			</li>
		</ul>
	</div>
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/footer.php";