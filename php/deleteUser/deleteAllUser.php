<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-07-02 15:59:56
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:57:52
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once __DIR__."/deleteUser.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/php/auto/Anuncio.php';
$Garage = new Garage;
$Usuario = new Usuario;
$usuariosAEliminar=$Usuario->getDeleteCompleteProfile();
function rmrf($root)
{
	if(is_dir($root))
	{
		$directorio = scandir($root);
		foreach ($directorio as $d => $dir) {
			if($dir!="." && $dir!="..")
			{
				if(is_dir($root."/".$dir))
				{
					rmrf($root."/".$dir);
				}
				else
				{
					unlink($root."/".$dir);
				}
			}
		}
		rmdir($root);
	}
}

if(!empty($usuariosAEliminar)){
	foreach ($usuariosAEliminar as $usrkey => $usuarioDel) 
	{
		$mensaje="Borrando usuario con id=".$usuarioDel["profile"]."\n";
		$deleteUser=new DeleteUser($usuarioDel["profile"]);
		$publicaciones=$deleteUser->findPublication();
		//ELIMINA LIKES DE USUARIO
		if($deleteUser->iDidLikes())
		{
			if(!$deleteUser->likes($usuarioDel["profile"], 1))
			{
				$mensaje.= "Error al borrar Likes a mi perfil \n";
			}
		}
		else
		{
			$mensaje.= "Error al borrar Likes propios \n";
		}
		//ELIMINA SHARES DE USUARIO
		if($deleteUser->shareOwn())
		{
			if(!$deleteUser->deleteSharing($usuarioDel["profile"], 1))
			{
				$mensaje.= "Error al borrar te han compartido \n";
			}
		}
		else
		{
			$mensaje.= "Error al borrar propios y compartidos \n";
		}
		//ELIMINA SEGUIDOS Y SEGUIDORES
		if(!$deleteUser->follower() && $deleteUser->following())
		{

			$mensaje.= "error al borrar seguidores y seguidos \n";
		}
		//ELIMINA CUENTAS SEGUIDAS POR EL USUARIO
		if(!$deleteUser->followerAccount())
		{
			$mensaje.= "error al borrar cuentas segudias por el usuario \n";
		}
		//ELIMINA ANUNCIOS SEGUIDOS
		if(!$deleteUser->userFollowAd())
		{
			$mensaje.= "Error en eliminar anuncios seguidos \n";
		}
		if(!$deleteUser->userAdCommets())
		{
			$mensaje.= "Error en eliminar comentarios de anuncios \n";
		}
		//ELIMINA PUBLICACIONES
		if(!empty($publicaciones))
		{
			$faltaBorrar = $deleteUser->eachPublicationDelete($publicaciones); // borra publicaciones y likes que tiene la publicacion que se borro
			if(!$faltaBorrar)
			{
				foreach ($publicaciones as $pub => $idpub) 
				{
					if(!$deleteUser->deleteSharing($idpub, 4))
					{
						$mensaje.= "Error al borrar publicacion compartida $idpub \n";
					}
				}
			}
			else
			{
				$mensaje.= "faltaron por borrarse ".$faltaBorrar." publicaciones \n";
			}
			
		}
		//ELIMINA COMENTARIOS DE USUARIO
		if(!$deleteUser->comments())
		{
			$mensaje.= "Error en borrrar comentarios \n";
		}
		//ELIMINA ARCHIVOS DE USUARIO
		if(!$deleteUser->deleteFiles())
		{
			$mensaje.= "Error en borrrar comentarios \n";
		}
		//ELIMINA AUTOS
		$autos = $Garage->accountsByUser($usuarioDel["profile"]);
		if(!empty($autos))
		{
			$auto = new Auto;
			$anuncio = new Anuncio;
			foreach ($autos as $at => $automovil) 
			{
				$idAuto = $automovil["i_avi_account_car_id"];
				$Instancia = new Instancia($idAuto);
				$instanciasCar = $Instancia->intanceByCar;
				$response=array();
				if(sizeof($instanciasCar)<2)
				{
					if($instanciasCar)
					{
						$garageByInstance = $instanciasCar[key($instanciasCar)]["garage"];
						$accountGarage=$Garage->accountById($garageByInstance);
						if($usuarioDel["profile"]==$accountGarage["user"]) 
						{
							//verificar que el usuario contenga el Garage de la instancia
							if($Instancia->deleteInstanciaById(key($instanciasCar)))
							{
								//borrar la instancia
								$idCarObjet = $Garage->getCarIdbyInstancia($instanciasCar[key($instanciasCar)]["auto"]); //obtenerObjetoCarro
								if($Garage->deleteSellCar($instanciasCar[key($instanciasCar)]["auto"]))
								{
									if($automovil["likes"])
									{
										$deleteUser->likes($idAuto,3);
									}
									if($automovil["shared"])
									{
										$deleteUser->deleteSharing($idAuto,3);
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
															$deleteUser->likes($adDetailCar["idAnuncio"],5);
															$deleteUser->deleteSharing($adDetailCar["idAnuncio"],5);
															if($anuncio->deleteAdContact($adDetailCar["idAnuncio"]) && $anuncio->deleteAdLocation($adDetailCar["idAnuncio"]))
															{
																if(!$anuncio->deleteAdbyCar($idAuto))
																{
																	$mensaje.="No elimino el auto y sus anuncios id instancia auto $idAuto \n";
																}
															}

														}
													} 
													else
													{
														$mensaje.= "fallo borrar objeto id object auto $idCarObjet \n";
													}
												}
												else
												{
													$mensaje.= "fallo borrar placas auto $idCarObjet \n";
												}
											}
											else
											{
												$mensaje.="fallo borrar auto garage id instancia auto $idAuto  \n";
											}
										}
										else
										{
											$mensaje.= "Error al borrar archivos id instancia auto $idAuto  \n";
										}
									}
									else
									{
										$mensaje.= "fallo borrar imagenes id instancia auto $idAuto  \n";
									}
								}
								else
								{
									$mensaje.= "fallo borrar venta id instancia auto $idAuto  \n";
								}
							}
							else
							{
								$mensaje.= "fallo borrar instancia id ".key($instanciasCar)." \n";
							}
						}
						else
						{
							$mensaje.= "No eres el dueño de este Garage \n";
						}
						
					}
					else
					{
						$mensaje.="No pudimos borrar tu auto con id instancia $idAuto \n";
					}
				}
				else
				{
					foreach ($instanciasCar as $key => $Instancia) 
					{
						if($Instancia["garage"]==$_POST["garage"])
						{
							$accountGarage=$Garage->accountById($Instancia["garage"]);
							if($usuarioDel["profile"]==$accountGarage["user"]) 
							{
								if($Garage->deleteSellCar($Instancia["auto"]))
								{
									if($automovil["likes"])
									{
										$deleteUser->likes($Instancia["auto"],3);
									}
									if($automovil["shared"])
									{
										$deleteUser->deleteSharing($Instancia["auto"],3);
									}	
									if($Garage->deleteGarageCarImages($Instancia["auto"])) //borrar imagenes
									{

										if(!$Garage->deleteCarGarage($Instancia["auto"]))
										{
											$mensaje.="No se elimino en este Garage ".$Instancia["garage"]." con id instancia auto ".$Instancia["auto"]."\n";
										}
										else
										{
											$mensaje.= "No se pudo eliminar auto id instancia auto ".$Instancia["auto"]." \n";
										}

									}
									else
									{
										$mensaje.= "Error al eliminar Imagenes id instancia auto ".$Instancia["auto"]."\n";
									}
								}
								else
								{
									$mensaje.= "No se pudo eliminar Venta id instancia auto ".$Instancia["auto"]." \n";
								}
							}
							else
							{
								$mensaje.= $usuarioDel["profile"]." no es el dueño  de este Garage ".$Instancia["garage"]. " \n";
							}
						}
					}
				}
			}
		}
		//ELIMINA GARAGES
		$garages=$Garage->account($usuarioDel["profile"]);
		if(!empty($garages))
		{
			if($deleteUser->deleteAttributeUserAccount($usuarioDel["profile"]))// BORRA LOS ATRIBUTOS DEL USUARIO CON CUENTA
			{
				foreach ($garages as $g => $garage) {
					if($garage["padre"])
					{
						$mensaje.=$deleteUser->deleteOGarages($usuarioDel["profile"],$garage["padre"]);
					}
					else
					{
						$mensaje.=$deleteUser->deleteOGarages($usuarioDel["profile"],$garage["idAccount"]);
					}
				}
			}
		}
		//ElIMINAR USUARIO
		$getinfoUser = $deleteUser->getUserDesactivatedetail($usuarioDel["profile"]);
	 	$Usuario->insertUserDeletedInfo($usuarioDel["reasons"], $usuarioDel["profile"],$getinfoUser["o_avi_user_email"], $getinfoUser["generoid"], $getinfoUser["fechaNacimiento"], $getinfoUser["a_avi_useraddress_zip_code"], true);
		if($deleteUser->userDetail())
		{
			if($deleteUser->userPerfil())
			{
				if($deleteUser->userAddress())
				{
					if($deleteUser->userToken())
					{
						if($deleteUser->userTokenDelete())
						{
							if($deleteUser->deleteObjUser())
							{
								$mensaje.= "El usuario con email = ". $getinfoUser["o_avi_user_email"]. " y con id=".$usuarioDel["profile"]." fue borrado correctamente a las ".date("M d, Y - H:i\h\\r\\s",strtotime("now")). "\n";
								if(is_dir($_SERVER["DOCUMENT_ROOT"]."/users/".$usuarioDel["profile"]."/"))
								{
									chmod($_SERVER["DOCUMENT_ROOT"]."/users/".$usuarioDel["profile"]."/", 0777);
									$root=$_SERVER["DOCUMENT_ROOT"]."/users/".$usuarioDel["profile"]."/";
									rmrf($root);
								}
								if(!$Usuario->sendDeleteMsg(0,$usuarioDel["profile"], $getinfoUser["o_avi_user_email"]))
								{
									$mensaje.= "Error al enviar correo de verficacion \n";
								}
							}
							else
							{
								$mensaje.= "Error al borrar Usuario \n";
							}
						}
						else
						{
							$mensaje.= "Error al borrar delete Tokens \n";
						}
					}
					else
					{
						$mensaje.= "Error al borrar Tokens generales \n";
					}
				}
				else
				{
					$mensaje.= "Error al borrar Direccion \n";
				}
			}
			else
			{
				$mensaje.= "Error al perfil \n";
			}
		}
		else
		{
			$mensaje.= "Error al borrar Detalles \n";
		}

		//Eliminar extras de usuario, cotizaciones, seguros
		if(!$deleteUser->userCotizaciones())
		{
			$mensaje.= "Error al borrar cotizaciones \n";
		}
		if(!$deleteUser->userInsuredVehicle())
		{
			$mensaje.= "Error al borrar asegurados \n";
		}
		$deleteUser->logDeleteUsr($mensaje);
	}
}

