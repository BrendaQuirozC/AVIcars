<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/catalogoAutos/auto.php";
$auto=new Auto;
$marca=$_POST["marca"];
$modelo=null;
$ano=null;
if($_POST["marca"]!="0")
{
	$marca=$_POST["marca"];
}
if($_POST["modelo"]!="0"||$_POST["modelo"]!=null)
{
	$modelo=$_POST["modelo"];
}
if($_POST["ano"]!="-1"&&($_POST["ano"]!=0||$_POST["ano"]!=null))
{
	$ano=$_POST["ano"];
}
$versiones=$auto->knowVersion($_POST["modelo"]);
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
?>