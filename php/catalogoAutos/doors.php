<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-19 10:30:43
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:57:07
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
$auto=new Auto;
if($puerta=$auto->getDoorsByClass($_POST["clase"]))
{
	$MinimoPuertas=$puerta["MinimoP"];
	$MaximoPuertas=$puerta["MaximoP"];
	$nombreClase=$puerta["ClassName"];
	if($MaximoPuertas==$MinimoPuertas)
	{
	?>
	    <option class="visible" value="<?= $MinimoPuertas ?>" selected><?= $MinimoPuertas ?> Puertas</option>
	<?php
	}
	else
	{
	?>
		<option class="visible" value="0">Puertas</option>
		<option class="visible" value="<?= $MinimoPuertas ?>"><?= $MinimoPuertas ?> Puertas</option>
		<option class="visible" value="<?= $MaximoPuertas ?>"><?= $MaximoPuertas ?> Puertas</option>
	    <?php
	}
}
else
{
	echo 0;
}
?>