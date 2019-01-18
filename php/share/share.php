<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-12 16:45:56
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-16 09:35:34
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/share.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/notification/Notification.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_POST["p"]);
$toEncode=$coder->toEncode;
$notification = new Notificacion;
$usuario=new Usuario;
$garage=new Garage;
$instancia=new Instancia;
$publicacion=new Publicacion;
$share=new Share;
session_start();
$user=$_SESSION["iduser"];
$tipo=$_POST["t"];
$toShare=false;
if($usuario->getStatusUser($_SESSION["iduser"])==3){
	$response=array("Error"=>true);
}
else{
	foreach ($_POST["share"] as $s => $sharings) {
		foreach ($sharings as $sh => $idToShare) {
			switch ($tipo) {
				case 1: //perfil
					if($usuario->UserAccessToUser($_SESSION["iduser"],$toEncode) && $usuario->getPrivacyUser($toEncode)!=3){
						$target=$_POST["p"];
						$url="/perfil/?cuenta=$target";
						$toShare=true;
						$notify=14;
						$coder->decode($_POST["p"]);
						$receptionNot=$coder->toEncode;
					}
					break;
				case 2: //garage
					if($garage->UserAccessToGarage($_SESSION["iduser"],$toEncode) && $garage->getPrivacyGarage($toEncode)!=3){
						$padre=$_POST["f"];
						$target=$_POST["p"];
						$url="/perfil/garage/timeline/?cuenta=$padre&garage=$target";
						$toShare=true;
						$notify=15;
						$coder->decode($_POST["f"]);
						$receptionNot=$coder->toEncode;
					}
					break;
				case 3: //auto
					if($instancia->UserAccessToInstance($_SESSION["iduser"],$toEncode)){
						$padre=$_POST["f"];
						$target=$_POST["p"];
						$url="/perfil/autos/detalles/?cuenta=$padre&auto=$target";
						$toShare=true;
						$notify=16;
						$coder->decode($_POST["f"]);
						$receptionNot=$coder->toEncode;
					}
					break;
				case 4: //post
					if($publicacion->UserAccessToPublication($_SESSION["iduser"],$toEncode)){
						$infoPost=$publicacion->getPublicationByID($toEncode);
						$target=$_POST["p"];
						$url="/post/?p=$target";
						$toShare=true;
						$notify=11;
						$receptionNot=$infoPost["usuarioAutor"];
					}
					break;
				case 5: //anuncio
					$toShare=true;
					$target=$_POST["p"];
					$url="/anuncio/?a=$target";
					$notify=20;
					$anuncio=$instancia->getAdvertisementById($toEncode);
					$receptionNot=$anuncio["ownerid"];
					break;
				default:
					
					break;
			}
			if($toShare){
				$idToShare=$coder->decode($idToShare);
				$post=false;
				switch ($s) {
					case 'p':
						if($idToShare==$user){
							if($sharing=$share->doSharing($tipo,$user,$toEncode,$url)){
								
								$post=$publicacion->addPublicacion("",15,$user,null,null,$url,2,null,null,null,null,$sharing);
									
							}
						}
						break;
					case "g":
						$owner=$garage->getAUserAccount($user,$idToShare);
						$colaborator=$garage->getAUserAccount($user,$idToShare,3);
						if($owner){
							if($sharing=$share->doSharing($tipo,$user,$toEncode,$url)){
							
								$post=$publicacion->addPublicacion("",15,$user,null,null,$url,2,$idToShare,$idToShare,null,null,$sharing);
									
							}
						}
						elseif($colaborator){
							$infoCuenta=$garage->accountById($idToShare);
							if($sharing=$share->doSharing($tipo,$user,$toEncode,$url)){
							
								$post=$publicacion->addPublicacion("",15,$infoCuenta["user"],null,null,$url,2,$idToShare,$idToShare,null,null,$sharing,null,$user);
									
							}
						}
						break;
					case "c":
						$infoInstance=$instancia->getInfoinstance($idToShare);
						$owner=$garage->getAUserAccount($user,$infoInstance["garageId"]);
						$colaborator=$garage->getAUserAccount($user,$infoInstance["garageId"],3);
						if($owner){
							if($sharing=$share->doSharing($tipo,$user,$toEncode,$url)){
							
								$post=$publicacion->addPublicacion("",15,$user,null,null,$url,2,$infoInstance["garageId"],$infoInstance["garageId"],$idToShare,null,$sharing);
									
							}
						}
						elseif($colaborator){
							$infoCuenta=$garage->accountById($infoInstance["garageId"]);
							if($sharing=$share->doSharing($tipo,$user,$toEncode,$url)){
							
								$post=$publicacion->addPublicacion("",15,$infoCuenta["user"],null,null,$url,2,$infoInstance["garageId"],$infoInstance["garageId"],$idToShare,null,$sharing,null,$user);
							}
						}
						break;
					default:
						# code...
						break;
				}
				if($post){
					$coder->encode($post);
					$urlNotification="/post/?p=".$coder->encoded;
					if($_SESSION["iduser"]!=$receptionNot){
						$notification->addNotification($notify,"",$_SESSION["iduser"],$receptionNot, NULL, NULL, $urlNotification,1,true);
					}
					$response=array("Success"=>$coder->encoded);
				}
			}
		}
	}
	$response=array("Success"=>true);
	
}
echo json_encode($response);