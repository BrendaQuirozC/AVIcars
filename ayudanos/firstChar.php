<?php 
require_once 'FuncionRecursiva.php';
$recursive = new Recursividad;
$arrayVersion = $recursive->featureVersion($_POST["versionID"]);
echo $arrayVersion[$_POST["versionID"]]["c_vehicle_versions_extraSpecifications"];
?>