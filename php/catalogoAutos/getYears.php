<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/catalogoAutos/auto.php";
$auto=new Auto;
$marca=$_POST["marca"];
$modelo=null;
if(($marca == -1)|| ($_POST["modelo"]==-1))
{
	foreach (range(1919, date("Y")+1) as $years) {
		echo "<option value='-1'>".$years."</option>";
	}
}

if($_POST["modelo"]!="0")
{
	$modelo=$_POST["modelo"];
}

$anos=$auto->getModels($marca,$modelo);
echo '<option value="0">A&Ntilde;O</option>';
foreach ($anos as $a => $ano) {
	$selected="";
	if($_POST["ano"]==$ano["id"])
	{
		$selected="selected";
	}
	echo "<option value='".$ano["id"]."' ".$selected.">".$ano["modelo"]."</option>";
}
echo '<option value="-1">Otro A&ntilde;o</option>';
?>