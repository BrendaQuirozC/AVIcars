<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-01 17:15:17
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:40:39
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(isset($_SESSION["iduser"])){
	$searcher=$_SESSION["iduser"];
}
else{
	$searcher=0;
}
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';

$Instancia = new Instancia;

$carros=$Instancia->getCarsFhorSearch($_POST["q"],$searcher,$_POST["t"]);
echo json_encode($carros);
