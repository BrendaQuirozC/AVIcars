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
echo '<option value="0" selected>Cualquiera</option>';
if($marca&&$modelo){
	$anos=$auto->getModels($marca,$modelo);

	foreach ($anos as $a => $ano) {
		$selected="";
		echo "<option value='".$ano["id"]."'>".$ano["modelo"]."</option>";
	}
}
?>

