<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-21 12:39:59
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-07 09:52:09
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder=new Coder;
$Venta = new Venta;
$publicacion=new Publicacion;
$Garage=new Garage;
$vende=true;
$vin=null;
$precio=null;
$km=null;
$color=null;
$version=null;
$estado=null;

$alias=$_POST["alias"]." ";
$coder->decode($_POST["garage"]);
$garage=$coder->toEncode;
if($Garage->getAUserAccount($_SESSION["iduser"], $garage,2))
{
	$otraMarca=null;
	$otraSubmarca=null;
	$otroModelo=null;
	$otraVersion=null;
	if(isset($_POST["otraMarcaInput"])&& $_POST["otraMarcaInput"]!="")
	{
		$alias="";
		$otraMarca=$_POST["otraMarcaInput"];
	}
	if(isset($_POST["otroModeloInput"])&& $_POST["otroModeloInput"]!="")
	{
		$alias="";
		$otraSubmarca=$_POST["otroModeloInput"];
	}
	if(isset($_POST["otroAnoInputmodal"])&& $_POST["otroAnoInputmodal"]!="")
	{
		$alias="";
		$otroModelo=$_POST["otroAnoInputmodal"];
	}
	if($otraMarca){
		$alias.=$otraMarca." ";
	}
	if($otraSubmarca){
		$alias.=$otraSubmarca." ";
	}
	if($otroModelo){
		$alias.=$otroModelo." ";
	}
	$alias=substr($alias, 0 ,-1);
	if(isset($_POST["otroVersionInputmodal"]) && $_POST["otroVersionInputmodal"]!="")
	{
		$otraVersion = $_POST["otroVersionInputmodal"];
	}

	$idCardetail = $Venta -> cardetail($version,$color,$vin,null,null,null, (isset($_POST["marca"]) && $_POST["marca"]!=-1) ? $_POST["marca"] : null, (isset($_POST["submarca"]) && $_POST["submarca"]!=-1) ? $_POST["submarca"] : null, (isset($_POST["modelo"]) &&  $_POST["modelo"]!=-1) ? $_POST["modelo"] : null, (isset($_POST["subnombres"]) &&  $_POST["subnombres"]!=-1) ? $_POST["subnombres"] : null, $otraMarca, $otraSubmarca, $otroModelo, $otraVersion);
	//print_r($_SESSION);
	if($idCardetail)
	{
		$usr = new Usuario;
		if($garage)
		{
			$accountType=$usr->privacyFather($garage);
		}
		else
		{
			$accountType=2;
		}

		$garagePoster = $Garage ->accountById($garage);
		$userPoster=$garagePoster["user"];
		$userNotification=$_SESSION["usertkn"];
		if($userPoster==$_SESSION["iduser"]){
			$instanciaUsrAuto=$Venta -> carAccount($garage, $idCardetail, $alias, $estado, 0, $accountType);
		}
		else{
			$coder->encode($userPoster);
			$userNotification=$coder->encoded;
			$instanciaUsrAuto=$Venta -> carAccount($garage, $idCardetail, $alias, $estado, 0, $accountType,$_SESSION["iduser"]);
		}
		
		$imgAuto =array();
		$imgAuto = $Venta -> getfotos(1, $garage, $_SESSION["iduser"]);
		if($instanciaUsrAuto)
		{

			$Instancia = new Instancia;
			$Instancia->createInstance($instanciaUsrAuto, $garage);
		}
		if(isset($_POST["img"])){
			$imagnesPost = json_decode($_POST["img"]);
			$imagenRectificada=array();
			foreach ($imagnesPost as $key => $imagenAuto) {
				if(in_array($imagenAuto, $imgAuto))
				{
					$nuevaFoto = $Garage -> addCarImg('/users/'.$_SESSION["iduser"].'/fotoCar/'.$imagenAuto, $instanciaUsrAuto);
					if($nuevaFoto)
					{
						$Venta -> deletOneImgTmp($imagenAuto);
						copy($_SERVER["DOCUMENT_ROOT"].'/users/'.$_SESSION["iduser"].'/fotoCar/'.$imagenAuto, $_SERVER["DOCUMENT_ROOT"].'/users/'.$_SESSION["iduser"].'/fotoCar/p_'.$imagenAuto);
						$imagenRectificada[]='/users/'.$_SESSION["iduser"].'/fotoCar/p_'.$imagenAuto;
					}
					else
					{
						echo "imagen corrupta";
					}
				}
			}
		}
		//En funcion placas de carro Campo null falta agregar ruta de la imagen de la targeta
		//$Venta->placasCarro($_POST["placas"], $_POST["eFederale"], NULL, $idCardetail);
		$coder_1 = new Coder($instanciaUsrAuto);
		$url = "/perfil/autos/detalles/?cuenta=".$userNotification."&auto=".$coder_1->encoded;
		$Venta ->deletImgTmp($garage);
		if(isset($_POST["anunciarAndCreate"]))
		{
			$response=array("Success"=>"/perfil/autos/detalles/editar/?cuenta=".$userNotification."&auto=".$coder_1->encoded);
		}
		else
		{
			$response=array("Success"=>"/perfil/autos/detalles/editar/?cuenta=".$userNotification."&auto=".$coder_1->encoded, "auto"=> $coder_1->encoded );
		}
		$publicacion->addPublicacion($alias,3,$userPoster,!empty($imagenRectificada) ? $imagenRectificada : null , null, $url,2, $garage, $garage, null,$userPoster);

		/*$publicacion->addPublicacion($alias,9,$_SESSION["iduser"],!empty($imagenRectificada) ? $imagenRectificada : null , null, $url,2,$garage,$garage,$instanciaUsrAuto,$_SESSION["iduser"]);
		unset($_SESSION["venta"]);*/
	}
	else
	{
		$response=array("Error" => "ErrorInsertCar");
	}
}
else
{
	$response=array("Error" => "ErrorInsertCar");
}
echo json_encode($response);

unset($imgAuto);
unset($idCardetail);
unset($vinimg);
unset($facturaimg);
unset($km);
unset($precio);
unset($instanciaUsrAuto);
?>
