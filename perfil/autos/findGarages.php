<?php 
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
$garage = new Garage;
$garages=array();
$garages = $garage->account($_SESSION["iduser"]);
?>
<select class='form-control' id=''>
	<option class='visible' value='0'>Garage donde va ir tu auto</option>
	<?php 
	foreach ($garages as $keygarage => $garage) 
	{
		?>
		<option class='visible' value='' onclick='agregarCarro(<?= $_SESSION["iduser"]?>,<?=$garage["idAccount"]?>)'><?=$garage["nameAccount"]?></option>
		<?php
	}
	?>
</select>