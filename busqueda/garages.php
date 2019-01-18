<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-01 17:14:59
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:40:42
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(isset($_SESSION["iduser"])){
	$searcher=$_SESSION["iduser"];
}
else{
	$searcher=0;
}
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';

$Garage = new Garage;

$garages=$Garage->getGaragesForSearch($_POST["q"],$searcher,$_POST["t"]);
echo json_encode($garages);
