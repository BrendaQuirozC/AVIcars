<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-08 13:12:22
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:58:44
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_POST["unfollow"]);
$_POST["unfollow"]=$coder->toEncode;
session_start();
if(!empty($_SESSION) && isset($_POST["unfollow"]) && isset($_POST["type"]) && ($_POST["type"]>0 && $_POST["type"]<4))
{
	try 
	{
		$Seguidor = new Seguidor($_POST["type"],$_SESSION["iduser"],$_POST["unfollow"]);
	} 
	catch (Exception $e) 
	{
		throw new Exception("Error: ".$e->getMessage());
	}
	if($Seguidor->idAquienSigues)
	{	
		if($Seguidor->unfollow())
		{
			$idGarage=null;
			$idAuto=null;
			if($_POST["type"]==1)
			{
				$dueno = $_POST["unfollow"];
			}
			if($_POST["type"]==2)
			{
				$idGarage=$_POST["unfollow"];
				$idAuto=null;	

				if(isset($_POST["dueno"])){

					$coder->decode($_POST["dueno"]);
					$_POST["dueno"]=$coder->toEncode;
					$dueno = $_POST["dueno"];
				}
				else{
					$dueno = $_POST["unfollow"];
				}
			}
			elseif ($_POST["type"]==3) {
				$idGarage=null;
				$idAuto=$_POST["unfollow"];
				
				if(isset($_POST["dueno"])){

					$coder->decode($_POST["dueno"]);
					$_POST["dueno"]=$coder->toEncode;
					$dueno = $_POST["dueno"];
				}
				else{
					$dueno = $_POST["unfollow"];
				}
			}
			$Seguidor -> deleteRequestNotification($dueno,$_SESSION["iduser"], $_POST["type"], $idGarage, $idAuto);
			echo "Seguir";
		}
	}
}
