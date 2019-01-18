<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-07 13:01:06
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-04 14:04:43
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
$image=new Image;

$nombre_archivo = $_SERVER["DOCUMENT_ROOT"].'/users/erikfer94/Cover/inidce.jpg'; //ARCHIVO A CONVERTIR

//$nombre_archivo = $_SERVER["DOCUMENT_ROOT"].'/img/portada.jpg';

$destino="/img/".strtotime("now").".jpg";//DONDE SE GUARDA EL ARCHIVO
$image->reduce($nombre_archivo, $_SERVER["DOCUMENT_ROOT"].$destino); //SE HACE LA CONVERSION
unlink($nombre_archivo); //SE BORRA EL ARCHIVO ORIGINAL
