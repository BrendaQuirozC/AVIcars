<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-18 09:53:39
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 11:58:51
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/php/auto/Anuncio.php';
require_once ($_SERVER["DOCUMENT_ROOT"])."/php/Utilities/coder.php";
$coder = new Coder();
$auto = new Auto;
$anuncio = new Anuncio;
$coder->decode($_POST["auto"]);
$idAuto = $coder->toEncode;
$coder->decode($_POST["garage"]);
$idGarage = $coder->toEncode;
$Instancia = new Instancia($idAuto);
$instanciasCar = $Instancia->intanceByCar;
session_start();
$response=array();
$Garage = new Garage;
if(sizeof($instanciasCar)<2)
{
	if($instanciasCar)
	{
		$garageByInstance = $instanciasCar[key($instanciasCar)]["garage"];
		$garageInfo=$Garage->accountById($garageByInstance);
		$coder->encode($garageInfo["user"]);
		$ownerGarage=$coder->encoded;
		if($_SESSION["iduser"]==$garageInfo["user"]||$Garage->getAUserAccount($_SESSION["iduser"],$garageByInstance,2)) 
		{
			//verificar que el usuario contenga el Garage de la instancia
			if($Instancia->deleteInstanciaById($instanciasCar[key($instanciasCar)]["auto"]))
			{
				//borrar la instancia
				$idCarObjet = $Garage->getCarIdbyInstancia($instanciasCar[key($instanciasCar)]["auto"]); //obtenerObjetoCarro
				if($Garage->deleteSellCar($instanciasCar[key($instanciasCar)]["auto"]))
				{
					//borrar seguidores del auto
					if($auto->deleteFollowersCar($instanciasCar[key($instanciasCar)]["auto"]))
					{
						$imagenes = $Garage->imagenesGenerales($instanciasCar[key($instanciasCar)]["auto"]);
						foreach ($imagenes as $img => $image) {
							unlink($_SERVER['DOCUMENT_ROOT'].$image["a_avi_car_img_car"]); //Borrar imagenes de carpeta
						}
						if($Garage->deleteGarageCarImages($instanciasCar[key($instanciasCar)]["auto"])) //borrar imagenes
						{
							if($auto->deleteFiles($instanciasCar[key($instanciasCar)]["auto"]))
							{
								if($Garage->deleteCarGarage($instanciasCar[key($instanciasCar)]["auto"]))
								{
									//borrar el auto del garage 
									if($auto->deletePlacas($idCarObjet))
									{
										if($Garage->deleteCarObject($idCarObjet))//eliminar El objeto auto
										{
											$adDetailCar=$auto->adCar($idAuto);
											if(isset($adDetailCar["idAnuncio"]))
											{
												if($anuncio->deleteAdContact($adDetailCar["idAnuncio"]) && $anuncio->deleteAdLocation($adDetailCar["idAnuncio"]))
												{
													if($anuncio->deleteAdbyCar($idAuto))
													{
														$response["Success"]=array("g"=>$_POST["garage"],"c"=>$ownerGarage);
													}
												}
											}
											else
											{
												$response["Success"]=array("g"=>$_POST["garage"],"c"=>$ownerGarage);
											}
										} 
										else
										{
											$response["Error"] = "fallo borrar objeto";
										}
									}
									else
									{
										$response["Error"] ="Error al eliminar informaci&oacute;n del auto.";
									}
								}
								else
								{
									$response["Error"] ="Falla al borrar auto.";
								}
							}
							else
							{
								$response["Error"] = "Error al eliminar.";
							}
						}
						else
						{
							$response["Error"] = "Fallo al eliminar.";
						}
					}
					else
					{
						$response["Error"] = "Error al eliminar informaci&oacute;n.";
					}
				}
				else
				{
					$response["Error"] = "Fallo al borrar";
				}
			}
			else
			{
				$response["Error"] = "Falla al borrar";
			}
		}
		else
		{
			$response["Error"] = "No eres el dueño de este Garage.";
		}	
	}
	else
	{
		$response["Error"] ="No pudimos borrar tu auto, intente m&aacute;s tarde.";
	}
}
else
{
	foreach ($instanciasCar as $key => $Instancia) 
	{
		if($Instancia["garage"]==$idGarage)
		{
			$garageInfo=$Garage->accountById($Instancia["garage"]);
			if($_SESSION["iduser"]==$garageInfo["user"]||$Garage->getAUserAccount($_SESSION["iduser"],$Instancia["garage"],2)) 
			{
				if($Garage->deleteSellCar($Instancia["auto"]))
				{
					//borrar seguidores del auto
					if($auto->deleteFollowersCar($instanciasCar[key($instanciasCar)]["auto"]))
					{
						$imagenes = $Garage->imagenesGenerales($instanciasCar[key($instanciasCar)]["auto"]);
						foreach ($imagenes as $img => $image) {
							unlink($_SERVER['DOCUMENT_ROOT'].$image["a_avi_car_img_car"]); //Borrar imagenes de carpeta
						}
						if($auto->deleteFiles($instanciasCar[key($instanciasCar)]["auto"]))
						{
							if($Garage->deleteGarageCarImages($Instancia["auto"])) //borrar imagenes
							{
								if($Garage->deleteCarGarage($Instancia["auto"]))
								{
									$response["Success"]=array("g"=>$_POST["garage"],"c"=>$ownerGarage);
									$adDetailCar=$auto->adCar($Instancia["auto"]);
									if(isset($adDetailCar["idAnuncio"]))
									{
										if($anuncio->deleteAdContact($adDetailCar["idAnuncio"]) && $anuncio->deleteAdLocation($adDetailCar["idAnuncio"]))
										{
											if($anuncio->deleteAdbyCar($Instancia["auto"]))
											{
												$response["Success"]=array("g"=>$_POST["garage"],"c"=>$ownerGarage);
											}
										}
									}
									else
									{
										$response["Success"]=array("g"=>$_POST["garage"],"c"=>$ownerGarage);
									}
								}
								else
								{
									$response["Error"] = "No se pudo eliminar auto";
								}
							}
							else
							{
								$response["Error"] = "Error al eliminar Imagenes";
							}
						}
						else
						{
							$response["Error"] = "Error al borrar archivos";
						}
					}
					else
					{
						$response["Error"] = "Error al eliminar informaci&oacute;n.";
					}
				}
				else
				{
					$response["Error"] = "No se pudo eliminar Venta";
				}
			}
			else
			{
				$response["Error"] = "No eres el dueño de este Garage";
			}
		}
	}
}
echo json_encode($response);

?>