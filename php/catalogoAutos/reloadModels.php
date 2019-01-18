<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/catalogoAutos/auto.php";
$auto=new Auto;

$submarcas=$auto->getSubMarcas();
echo '<option value="0">MODELO</option>';
foreach ($submarcas as $s => $subm) {
	echo "<option data-marca='".$subm["marca"]."' value='".$subm["id"]."'>".$subm["submarca"]."</option>";
}
echo '<option value="-1">Otro Modelo</option>';


?>