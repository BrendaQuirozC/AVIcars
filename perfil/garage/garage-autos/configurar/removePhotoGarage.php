<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-05-25 15:31:15
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:53:24
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
session_start();
$Garage = new Garage;
$photo = $_POST["ruta"];
$type= $_POST["tipo"];
$idGarage= $_POST["garage"];
if($Garage -> tmpGarageDelete($_SESSION["iduser"],$idGarage,$type))
{
	unlink(($_SERVER['DOCUMENT_ROOT']).$photo);
	echo "success";
}
else
{
	echo "error";
}
