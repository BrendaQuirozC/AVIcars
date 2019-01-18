<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2019-01-14 13:44:08
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-14 15:43:44
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

$related=$Publicacion->getPublicacionesForHashtag($_POST["q"],$searcher,$_POST["t"]);
//$publicaciones=array();
echo json_encode($related);
