<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2018-10-03 12:25:26
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-10-10 09:49:29
 */
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/header.php";
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/content.php";
?>

<div class="paragraph">
	<a href="/ayuda">Servicio de Ayuda</a> > <a href="/ayuda/uso">Uso del Sitio</a> > <span>Timeline</span>
	<p class="titles">Timeline</p>
	<p>Las publicaciones que realices en tu perfil o en los garages se reflejar&aacute;n en el Timeline, estas aparecer&aacute;n en orden cronol&oacute;gico, lo que te dar&aacute; mayor control y organizaci&oacute;n. </p>
	<p>Existen 4 tipos de timeline distintos: </p>
	<table>
		<thead>
			<tr>
				<th>Timeline</th>
				<th>Contenido</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>General</td>
				<td>Ves todas las publicaciones tuyas y de las personas a quienes sigues.</td>
			</tr>
			<tr>
				<td>Perfil</td>
				<td>Ver tus publicaciones, publicaciones que compartes y  de quienes escriben en tu perfil.</td>
			</tr>
			<tr>
				<td>Garage</td>
				<td>Ver las publicaciones referentes a ese garare, publicaciones de los autos que pertenecen a ese garage, y de quienes escriben en ese garage.</td>
			</tr>
			<tr class="last">
				<td>Auto</td>
				<td>Ver las publicaciones referentes a ese auto.</td>
			</tr>
		</tbody>
	</table>
	<p>Para saber m&aacute;s informaci&oacute;n acerca de lo que se puede publicar en los timeline, click aqu&iacute; <a href="/ayuda/uso/publicaciones">Publicaciones</a>.</p>
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'])."/ayuda/layout/footer.php";