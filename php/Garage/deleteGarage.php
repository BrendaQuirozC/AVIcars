<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
$Garage = new Garage;

$usr = new Usuario;
$database=new Database;
$db=$database->connect();

session_start();
$user_id=$_SESSION["iduser"];
$idGarage= $_POST["id"];

if($Garage->getAUserAccount($user_id, $idGarage,1))
{
	$usr->deleteGarage($user_id,$idGarage);
	echo '{"Success": "deleted", "usuario": "'.$_SESSION["usertkn"].'"}';
}
else
{
	echo '{"Error": "No tienes permiso para eliminar este Garage"}';
}

