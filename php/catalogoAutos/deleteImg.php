<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-01 17:47:46
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:57:04
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
$Garage = new Garage;
$idCar = $_POST["idCar"];
$idImg = $_POST["idImg"];
$urlImg = $Garage -> imagenPorCoche($idCar,$idImg);
$borrado = $Garage -> deleteCarImg($idCar,$idImg);
if($borrado)
{
	unlink($_SERVER["DOCUMENT_ROOT"]."/".$urlImg);
	echo "success";
}
else
{
	echo "error";
}

unset ($idCar);
unset ($idImg);
unset ($borrado);