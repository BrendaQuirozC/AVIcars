<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-04 18:21:26
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:40:49
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(isset($_SESSION["iduser"])){
	$searcher=$_SESSION["iduser"];
}
else{
	$searcher=0;
}
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicacion.php';

$Publicacion = new Publicacion;

$publicaciones=$Publicacion->getPublicacionesForSearch($_POST["q"],$searcher,$_POST["t"]);
//$publicaciones=array();
echo json_encode($publicaciones);
