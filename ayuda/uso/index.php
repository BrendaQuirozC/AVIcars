<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2018-10-05 16:17:52
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-08 13:58:57
 */
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/header.php";
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/content.php";
?>
<div class="categorias">
	<div class="single-page">
		<div class="paragraph">
			<a href="/ayuda">Servicio de Ayuda</a> > <span>Uso del Sitio</span>
			<p class="titles">Uso del Sitio</p>
		</div>
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
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/footer.php";