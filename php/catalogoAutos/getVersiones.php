<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/catalogoAutos/auto.php";
$auto=new Auto;

if(!$POST["modelo"])
{
	echo '<option value="0">VERSI&Oacute;N</option>';
	echo '<option value="-1">Otra Versi&oacute;n</option>';
}
else
{
	$versiones=$auto->getVersiones($_POST["modelo"]);
	echo json_encode($versiones);
}


?>