<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-29 14:06:39
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-01 14:26:25
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Archivo/archivo.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder=new Coder;
$coder->decode($_POST["object"]);
$_POST["object"]=$coder->toEncode;
$Garage = new Garage;
$archivo=new Archivo;
$allowed=array("xls","xlsx","pdf","png","jpg","jpeg","doc","docx","ppt","pptx","zip","rar","txt");
$filename=strtotime("now").str_pad((rand(0,100)*$_POST["object"]),3,0,STR_PAD_LEFT);
//print_r($_POST);
$toChange=array("ñ","á","Á","é","É","í","Í","ó","Ó","ú","Ú");
$changed=array("&ntilde;","&aacute;","&Aacute;","&eacute;","&Eacute;","&iacute;","&Iacute;","&oacute;","&Oacute;","&uacute;","&Uacute;");
$response=array();
if(!empty($_FILES)){
	if(!$_FILES["file"]["error"]){
		$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$extension=strtolower($extension);
		if(!in_array($extension, $allowed)){
			$response["Error"]="NO NO NO NO!!!!";
		}
		else{
			try{
				$arrTipo=$archivo->getType($_POST["type"]);	
			}
			catch(Exception $e){
				$response["Error"]=$e->getMessage();
			}
			if(!isset($response["Error"]))
			{
				$objectType=$arrTipo["objeto"];
				switch ($objectType) {
					case 1:
						$garageContain= $Garage-> instanciaById($_POST["object"]);
						if($Garage->getAUserAccount($_SESSION["iduser"],$garageContain[0]['o_avi_account_id'],2)){
							if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/users/".$garageContain[0]["user"]))
							{
								mkdir($_SERVER["DOCUMENT_ROOT"]."/users/".$garageContain[0]["user"]);
							}
							if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/users/".$garageContain[0]["user"]."/".$garageContain[0]["o_avi_account_id"]))
							{
								mkdir($_SERVER["DOCUMENT_ROOT"]."/users/".$garageContain[0]["user"]."/".$garageContain[0]["o_avi_account_id"]);
							}
							if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/users/".$garageContain[0]["user"]."/".$garageContain[0]["o_avi_account_id"]."/".$_POST["object"]))
							{
								mkdir($_SERVER["DOCUMENT_ROOT"]."/users/".$garageContain[0]["user"]."/".$garageContain[0]["o_avi_account_id"]."/".$_POST["object"]);
							}
							$destino="/users/".$garageContain[0]["user"]."/".$garageContain[0]["o_avi_account_id"]."/".$_POST["object"]."/".$filename.".".$extension;
						}
						else{
							$destino=false;
						}
						break;
					case 2:
						$garage=$Garage->accountById($_POST["object"]);
						if($_SESSION["iduser"]==$garage["user"]){
							if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/users/".$garage["user"]))
							{
								mkdir($_SERVER["DOCUMENT_ROOT"]."/users/".$garage["user"]);
							}
							if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/users/".$garage["user"]."/".$_POST["object"]))
							{
								mkdir($_SERVER["DOCUMENT_ROOT"]."/users/".$garage["user"]."/".$_POST["object"]);
							}
							$destino="/users/".$garage["user"]."/".$_POST["object"]."/".$filename.".".$extension;
						}
						else{
							$destino=false;
						}
						break;
					case 3:
						if($_SESSION["iduser"]==$_POST["object"]){
							if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/users/".$_POST["object"]))
							{
								mkdir($_SERVER["DOCUMENT_ROOT"]."/users/".$_POST["object"]);
							}
							$destino="/users/".$_POST["object"]."/".$filename.".".$extension;
						}
						else{
							$destino=false;
						}
						break;
					default:
						$destino=false;
						break;
				}
				//var_dump($destino);
				if($destino)
				{
					if(move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"].$destino)){
						$save=false;
						try{
							$extras=array();
							if(isset($_POST["extras"])){
								$extras=$_POST["extras"];
							}
							if($_POST["nombre"]=="")
							{
								$save=$archivo->add($destino,$_POST["type"],$_POST["object"],$extras);
							}
							else
							{
								$save=$archivo->add($destino,$_POST["type"],$_POST["object"],$extras,$_POST["nombre"]);
							}
						}
						catch(Exception $e){
							$response["Error"]=$e->getMessage();
						}
						finally{
							$response["Success"]=$save;
						}
					}
				}
			}
		}
	}
}
echo json_encode($response);