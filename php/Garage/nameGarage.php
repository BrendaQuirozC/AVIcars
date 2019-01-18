<?php
/**
 * User: Brenda Quiroz
 * Date: 22/02/2018
 * Time: 10:21 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';
$usr = new Usuario;

session_start();
$user_id=$_SESSION["iduser"];
$idGarage= $_POST["id"];
$garagename=$_POST["nombre"];

$usr->nombreCuenta($idGarage,$garagename);

echo "changed";
