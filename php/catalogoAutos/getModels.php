<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/catalogoAutos/auto.php";
$auto=new Auto;
//echo $_POST["marca"];
$marca=null;
$ano=null;
if($_POST["marca"]!="0")
{
	$marca=$_POST["marca"];
}
if($_POST["ano"]!="-1"&&$_POST["ano"]!=0)
{
	$ano=$_POST["ano"];
}
if($marca!=0 && $_POST["modelo"]!=0)
{
	$versiones=$auto->knowVersion($ano);
	echo '<option value="0">VERSI&Oacute;N</option>';
	foreach ($versiones as $vr => $version) {
		$selected="";
		if($_POST["version"]==$version["id"])
		{
			$selected="selected";
		}
		echo "<option data-modelo='".$version["modelo"]."' value='".$version["id"]."' ".$selected.">".$version["version"]." ".$version["subnombre"]."</option>";
	}
	echo '<option value="-1">Otra Versi&oacute;n</option>';
}
else
{
	$submarcas=$auto->getSubMarcas($marca);
	echo '<option value="0">MODELO</option>';
	foreach ($submarcas as $s => $subm) {
		$selected="";
		if($_POST["modelo"]==$subm["id"])
		{
			$selected="selected";
		}
		echo "<option data-marca='".$subm["marca"]."' value='".$subm["id"]."' ".$selected.">".$subm["submarca"]."</option>";
	}
	echo '<option value="-1">Otro Modelo</option>';
}

?>