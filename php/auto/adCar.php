<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-25 13:41:14
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-06 17:05:44
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_SESSION))
{
	echo 0;
}
else
{
	require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';
	require_once ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/auto.php';
	require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/notification/Notification.php";
	require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Utilities/coder.php";
	$Garage=new Garage;
	$Instancia=new Instancia;
	$Auto = new Auto;
	$notification = new Notificacion;
	$coder = new Coder();
	$coder->decode($_POST["auto"]);
	$_POST["auto"]=$coder->toEncode;
	$garageContain= $Garage->instanciaById($_POST["auto"]);
	$coder->encode($garageContain[0]["user"]);
	$ownerCarCode=$coder->encoded;
	if(!$Garage->getAUserAccount($_SESSION["iduser"],$garageContain[0]["i_avi_account_car_account_id"],2)){
		header('HTTP/1.0 403 Forbidden');
		echo "Tu no puedes ver esto! D;";
		exit;	
	}
	$imgsAuto = $Garage->imagenesGenerales($garageContain[0]["i_avi_account_car_id"]);
	$arrayPhones=array();
	$arrayEmail=array();
	if(isset($_POST["phone"]) || isset($_POST["phonecode"]))
	{
		for($k=0; $k<3; $k++){
			$var="phone";
			if($k){
				$var.=($k+1);
			}
			$arrayPhones[$k]=array("code"=>$_POST[$var."code"],"number"=>$_POST[$var],"wa"=>(isset($_POST[$var."wa"])) ? 1 : 0);
		}
	}
	if(isset($_POST["email"]))
	{
		for($e=0;$e<2;$e++){
			$var="email";
			if($e){
				$var.=($e+1);
			}
			if(isset($_POST[$var]))
				$arrayEmail[$e]=$_POST[$var];
		}
	}
	/****ANUNCIAR Y LEVANTAR VENTA*******/
	function AnunciarlevantarVenta($anuncio, $imgsAuto, $garageContain, $precio, $textoAnuncio,$currency=null)
	{
		$coderAdCuenta = new Coder($garageContain[0]["user"]);
		$coderAdAuto = new Coder($garageContain[0]["i_avi_account_car_id"]);
		$coderAdId = new Coder($anuncio);
		$url="/perfil/autos/detalles/?cuenta=".$coderAdCuenta->encoded."&auto=".$coderAdAuto->encoded;
		$urlAd="/anuncio/?a=".$coderAdId->encoded;
		require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/php/Venta/Venta.php";
		require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/share.php";
		$venta=new Venta;
		$publicacion=new Publicacion;
		$share=new Share;
		$resp=false;
		if(!empty($imgsAuto))
		{
			foreach ($imgsAuto as $key => $img) {
				$imgs[]=$img["a_avi_car_img_car"];
			}
		}
		else
		{
			$imgs=null;
		}
		$colaborador=null;
		if($_SESSION["iduser"]!=$garageContain[0]["user"]){
			$colaborador=$_SESSION["iduser"];
		}
		if($venta->levantarVenta($garageContain[0]["i_avi_account_car_id"], $precio, $_POST["moneda"], $colaborador ))
		{
			
			if($sharing=$share->doSharing(5,$_SESSION["iduser"],$anuncio,$urlAd)){
				$publicacion->addPublicacion($textoAnuncio,5,$garageContain[0]["user"],$imgs, $precio, $urlAd, 2, $garageContain[0]["o_avi_account_id"], $garageContain[0]["o_avi_account_id"], $garageContain[0]["i_avi_account_car_id"],null,$sharing,$currency,$colaborador);
				$resp=true;
			}
		}
		
		else{
			$resp=true;
		}
		return $resp;
	}			
	/*************************************/
	/*******************************DETALLES EXTRAS******************************************/
	if (isset($_POST["pasajeros"]) || isset($_POST["filas"])) {
		$detallesExtra=array();
		$detallesExtra["interiores"]=
			array(
				"num pasajeros"=>$_POST["pasajeros"],
				"filas asientos"=>$_POST["filas"]
			);
	}
		
	if(isset($_POST["referendo"]))
	{
		$detallesExtra["pagos"]["referendo"]=($_POST["referendo"]>=0 || $_POST["referendo"]<2) ? $_POST["referendo"] : 0;
	}
	if(isset($_POST["multas"]))
	{
		$detallesExtra["pagos"]["multas"]=($_POST["multas"]>=0 || $_POST["multas"]<2) ? $_POST["multas"] : 0;
	}
	if(isset($_POST["distanciaEjes"])){
		$detallesExtra["Exteriores"]=
			array(
				"Distancia entre Ejes"=>$_POST["distanciaEjes"],
				"Ancho entre Vías Delanteras" =>$_POST["anchoViasDel"], 
				"Ancho entre Vías Traseras"=>$_POST["anchoViasTra"],
				"Altura Total"=>$_POST["alturaTotal"], 
				"Distancia al piso"=> $_POST["distanciaPiso"], 
				"Angulo max de ataque"=>$_POST["anguloAtaque"], 
				"Circunferencia de Giro" =>$_POST["circunferenciaGiro"], 
				"Peso Eje Delatero"=>$_POST["pesoEjeDelatero"]
			);
	}
	if(isset($_POST["garantiaFabrica"]))
	{
		$detallesExtra["garantia"]=
			array(
				"fabrica"=>$_POST["garantiaFabrica"], 
				"vendedor"=>$_POST["garantiaVendedor"], 
				"usuario"=>$_POST["garantiaUsuario"]
			);
	}
	if(isset($_POST["piesas"]))
	{
		$piesas=array();
		if(sizeof($_POST["piesas"])>1){
			for ($i=0; $i < sizeof($_POST["piesas"]) ; $i++) { 
				if($_POST["piesas"][$i]!="")
				{
					$piesas[]=$_POST["piesas"][$i];
				}
			}
		}
		elseif($_POST["piesas"][0]!=""){
			$piesas=$_POST["piesas"];
		}
		else
		{
			$piesas=0;
		}
		$detallesExtra["piesas"]=$piesas;
	}
	if(isset($_POST["fallasmenores"]))
	{
		$fallasmenores=array();
		if(sizeof($_POST["fallasmenores"])>1){
			for ($i=0; $i < sizeof($_POST["fallasmenores"]) ; $i++) { 
				if($_POST["fallasmenores"][$i]!="")
				{
					$fallasmenores[]=$_POST["fallasmenores"][$i];
				}
			}
		}
		elseif($_POST["fallasmenores"][0]!=""){
			$fallasmenores=$_POST["fallasmenores"];
		}
		else
		{
			$fallasmenores=0;
		}
		$detallesExtra["fallasmenores"]=$fallasmenores;
	}
	if(isset($_POST["fallasmayores"]))
	{
		$fallasmayores=array();
		if(sizeof($_POST["fallasmayores"])>1){
			for ($i=0; $i < sizeof($_POST["fallasmayores"]) ; $i++) { 
				if($_POST["fallasmayores"][$i]!="")
				{
					$fallasmayores[]=$_POST["fallasmayores"][$i];
				}
			}
		}
		elseif($_POST["fallasmayores"][0]!=""){
			$fallasmayores=$_POST["fallasmayores"];
		}
		else
		{
			$fallasmayores=0;
		}
		$detallesExtra["fallasmayores"]=$fallasmayores;
	}
	/*************************************************************************/
	$adDetailCar=$Auto->adCar($_POST["auto"]);
	$payMethod=array();
	$payMethod["debTransfer"]=isset($_POST["debTransfer"]) ? $_POST["debTransfer"] : NULL;
	$payMethod["credit"]=isset($_POST["credit"]) ? $_POST["credit"] : NULL;
	$payMethod["bankCredit"]=isset($_POST["bankCredit"]) ? $_POST["bankCredit"] : NULL;
	$payMethod["carfinance"]=isset($_POST["carfinance"]) ? $_POST["carfinance"] : NULL;
	$payMethod["changeHighPrice"]=isset($_POST["changeHighPrice"]) ? $_POST["changeHighPrice"] : NULL;
	$payMethod["changeLowPrice"]=isset($_POST["changeLowPrice"]) ? $_POST["changeLowPrice"] : NULL;
	$payMethod["leasing"]=isset($_POST["leasing"]) ? $_POST["leasing"] : NULL;
	if(!isset($adDetailCar["idauto"]))
	{
		$colaborador=null;
		if($_SESSION["iduser"]!=$garageContain[0]["user"]){
			$colaborador=$_SESSION["iduser"];
		}
		$idAnuncio=$Auto->createAdCar($_POST["auto"], isset($_POST["anunciotext"]) ? $_POST["anunciotext"] : NULL, $payMethod, isset($_POST["negociable"]) ? $_POST["negociable"] : 1, $colaborador);
		if($idAnuncio)
		{	
			$Auto->contactoAd($idAnuncio,$arrayPhones, $arrayEmail);
			if(isset($_POST["zipcode"]))
				$Auto->locationAd($idAnuncio,$_POST["calle"], $_POST["colonia"], $_POST["zipcode"], $_POST["locationreference"]);
			
			if(AnunciarlevantarVenta($idAnuncio, $imgsAuto, $garageContain, $_POST["precio"], $_POST["anunciotext"],$_POST["moneda"]))
			{
				$coder=new Coder($idAnuncio);
				$carLikers=$Instancia->getCarFollowers($_POST["auto"]);
				foreach ($carLikers as $cl => $liker) {
					if($liker!=$_SESSION["iduser"])
					{
						$notification->addNotification(21,"Un carro que te gusta esta en venta",$_SESSION["iduser"],$liker, NULL, NULL, "/anuncio/?a=".$coder->encoded,1,true);	
					}
					
				}
				echo '{"success":"Se cre&oacute; anuncio.","u":"'.$ownerCarCode.'"}';
			}
		}
		else
		{
			echo '{"error":"Revise los datos."}';
		}
	}
	else
	{
		$contacto=false;
		$location=false;
		if($Auto->updateAdCar($_POST["auto"], (isset($_POST["anunciotext"]) && $adDetailCar["texto"]!=$_POST["anunciotext"]) ? $_POST["anunciotext"] : NULL, $payMethod, isset($_POST["negociable"]) ? $_POST["negociable"] : NULL))
		{
			//echo $adDetailCar["idContact"];
			if($adDetailCar["idContact"])
			{
				if($Auto->updateContactoAdCar($adDetailCar["idContact"],$arrayPhones, $arrayEmail))
				{
					$contacto=true;
				}
			}
			elseif($Auto->contactoAd($adDetailCar["idAnuncio"],$arrayPhones, $arrayEmail))
			{
				$contacto=true;
			}
			if($adDetailCar["idLocation"])
			{
				if($Auto->updateLocationAdCar($adDetailCar["idLocation"],isset($_POST["calle"]) ? $_POST["calle"] : NULL, isset($_POST["colonia"]) ? $_POST["colonia"] : NULL, isset($_POST["zipcode"]) ? $_POST["zipcode"] : NULL, isset($_POST["locationreference"]) ? $_POST["locationreference"] : NULL))
				{
					$location=true;
				}
			}
			elseif($Auto->locationAd($adDetailCar["idAnuncio"],isset($_POST["calle"]) ? $_POST["calle"] : NULL, isset($_POST["colonia"]) ? $_POST["colonia"] : NULL, isset($_POST["zipcode"]) ? $_POST["zipcode"] : NULL, isset($_POST["locationreference"]) ? $_POST["locationreference"] : NULL))
			{
				$location=true;
			}
			if(AnunciarlevantarVenta($adDetailCar["idAnuncio"], $imgsAuto, $garageContain, isset($_POST["precio"]) ? $_POST["precio"] : NULL, isset($_POST["anunciotext"]) ? $_POST["anunciotext"] : NULL,$_POST["moneda"]))
			{
				$coder=new Coder($adDetailCar["idAnuncio"]);
				$carLikers=$Instancia->getCarFollowers($_POST["auto"]);
				foreach ($carLikers as $cl => $liker) {
					if($liker!=$_SESSION["iduser"])
					{
						$notification->addNotification(29,"Un carro que te gusta tiene un nuevo precio",$_SESSION["iduser"],$liker, NULL, NULL, "/anuncio/?a=".$coder->encoded,1,true);
					}
				}
				$adLikers=$Instancia->getAdFollowers($adDetailCar["idAnuncio"]);
				foreach ($adLikers as $al => $liker) 
				{
					if($liker!=$_SESSION["iduser"])
					{
						$notification->addNotification(29,"Un carro que te gusta tiene un nuevo precio",$_SESSION["iduser"],$liker, NULL, NULL,"/anuncio/?a=".$coder->encoded,1,true);
					}
				}
				echo '{"success":"Se creo anuncio","u":"'.$ownerCarCode.'"}';
			}
			else
			{
				echo '{"error": "Error al anunciar"}';
			}
		}
		else
		{
			echo '{"Error":"Revise los datos"}';
		}
	}
	$Auto->updateInsGarageAuto(
		$garageContain[0]["i_avi_account_car_car_id"],
		((isset($_POST["recuperadorobo"]) && $garageContain[0]["recuperadoStole"]!=$_POST["recuperadorobo"] && $_POST["recuperadorobo"]>=0 && $_POST["recuperadorobo"]<2)) ? $_POST["recuperadorobo"] : NULL, 
		((isset($_POST["reconstruido"]) && $garageContain[0]["reconstruido"]!=$_POST["reconstruido"] && $_POST["reconstruido"]>=0 && $_POST["reconstruido"]<2)) ? $_POST["reconstruido"]: NULL, 
		((isset($_POST["legalizado"]) && $garageContain[0]["legalizado"]!=$_POST["legalizado"] && $_POST["legalizado"]>=0 && $_POST["legalizado"]<2)) ? $_POST["legalizado"] : null, 
		(isset($_POST["facturaEmpresa"])) ? "1" : "0", 
		(isset($_POST["facturaLote"])) ? "1" : "0",
		(isset($_POST["facturaPfisica"])) ? "1" : "0",
		(isset($_POST["facturaAseguradora"])) ? "1" : "0",
		((isset($_POST["placa"]) && $garageContain[0]["placa"]!=$_POST["placa"])) ? $_POST["placa"] : NULL,
		((isset($_POST["holograma"]) && $_POST["holograma"]!=-1 && $garageContain[0]["hologram"]!==$_POST["holograma"])) ? $_POST["holograma"] : NULL,
		isset($detallesExtra) ? $detallesExtra : NULL
	);
	
	if(isset($_POST["alias"]) && $garageContain[0]["i_avi_account_car_alias"]!=$_POST["alias"])
	{
		$Garage->updateGarageAliasCar($_POST["auto"],$_POST["alias"]);
	}
	if(isset($_POST["estado"]) && $garageContain[0]["i_avi_account_car_state"]!=$_POST["estado"])
	{
		//$Garage->updateGarageStateCar($_POST["auto"],$_POST["estado"]);
	}
	$Auto->updateObjCar(
		$garageContain[0]["i_avi_account_car_car_id"], 
		(isset($_POST["color"]) && $garageContain[0]["o_avi_car_color"]!=$_POST["color"]) ? $_POST["color"] : NULL ,
		isset($_POST["subnombres"]) ? $_POST["subnombres"]: NULL,
		(isset($_POST["vin"]) && $garageContain[0]["o_avi_car_vin"]!=$_POST["vin"]) ? $_POST["vin"] : NULL,
		(isset($_POST["engineCar"]) && $garageContain[0]["engineType"]!=$_POST["engineCar"]) ? $_POST["engineCar"] : NULL,
		(isset($_POST["clasecar"]) && $garageContain[0]["clase"]!=$_POST["clasecar"]) ? $_POST["clasecar"] : NULL,
		isset($_POST["marca"]) ? $_POST["marca"] : NULL,
		isset($_POST["submarca"]) ? $_POST["submarca"] : NULL,
		isset($_POST["modelo"]) ? $_POST["modelo"] : NULL,
		(isset($_POST["combustible"]) && $garageContain[0]["fuel"]!=$_POST["combustible"]) ? $_POST["combustible"] : NULL,
		(isset($_POST["transmision"]) && $garageContain[0]["trans"]!=$_POST["transmision"]) ? $_POST["transmision"] : NULL,
		(isset($_POST["puertas"]) && $garageContain[0]["doors"]!=$_POST["puertas"]) ? $_POST["puertas"] : NULL,
		(isset($_POST["ventanas"]) && $garageContain[0]["ventanas"]!=$_POST["ventanas"]) ? $_POST["ventanas"] : NULL,
		(isset($_POST["interior"]) && $garageContain[0]["interior"]!=$_POST["interior"]) ? $_POST["interior"] : NULL,
		(isset($_POST["kilometraje"]) && $garageContain[0]["o_avi_car_km"]!=$_POST["kilometraje"]) ? $_POST["kilometraje"] : NULL,
		(isset($_POST["dueños"]) && $garageContain[0]["dueno"]!=$_POST["dueños"]) ? $_POST["dueños"] : NULL,
		(isset($_POST["potencia"]) && $garageContain[0]["potencia"]!=$_POST["potencia"]) ? $_POST["potencia"] : NULL,
		isset($_POST["otraMarcaInput"]) ? $_POST["otraMarcaInput"] : NULL,
		isset($_POST["otroModeloInput"]) ? $_POST["otroModeloInput"] : NULL,
		isset($_POST["otroAnoInput"]) ? $_POST["otroAnoInput"] : NULL,
		isset($_POST["otroVersionInput"]) ? $_POST["otroVersionInput"] : NULL
	);
}