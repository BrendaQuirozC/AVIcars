<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-21 11:31:35
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-06 17:03:55
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_SESSION))
{
	header("Location: /");
}
else
{
	require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
	require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/auto.php';
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Venta/Venta.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
	require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Utilities/coder.php";
	$venta=new Venta;
	$Garage=new Garage;
	$Auto = new Auto;
	$publicacion=new Publicacion;
	$coder = new Coder();
	$coder->decode($_POST["auto"]);
	$_POST["auto"]=$coder->toEncode;
	$garageContain= $Garage->instanciaById($_POST["auto"]);
	if($Garage->getAUserAccount($_SESSION["iduser"],$garageContain[0]["i_avi_account_car_account_id"],2))
	{
		$detallesExtra=array();
		$detallesExtra["interiores"]=
			array(
				"num pasajeros"=>$_POST["pasajeros"],
				"filas asientos"=>$_POST["filas"]
			);
		if(isset($_POST["referendo"]))
		{
			$detallesExtra["pagos"]["referendo"]=($_POST["referendo"]>=0 || $_POST["referendo"]<2) ? $_POST["referendo"] : 0;
		}
		if(isset($_POST["multas"]))
		{
			$detallesExtra["pagos"]["multas"]=($_POST["multas"]>=0 || $_POST["multas"]<2) ? $_POST["multas"] : 0;
		}
		$detallesExtra["Exteriores"]=
			array(
				"Distancia entre Ejes"=>isset($_POST["distanciaEjes"]) ? $_POST["distanciaEjes"] : NULL,
				"Ancho entre Vías Delanteras" =>isset($_POST["anchoViasDel"]) ? $_POST["anchoViasDel"] : NULL, 
				"Ancho entre Vías Traseras"=>isset($_POST["anchoViasTra"]) ? $_POST["anchoViasTra"] : NULL,
				"Altura Total"=>isset($_POST["alturaTotal"]) ? $_POST["alturaTotal"] : NULL, 
				"Distancia al piso"=> isset($_POST["distanciaPiso"]) ? $_POST["distanciaPiso"] : NULL, 
				"Angulo max de ataque"=>isset($_POST["anguloAtaque"]) ? $_POST["anguloAtaque"] : NULL, 
				"Circunferencia de Giro" =>isset($_POST["circunferenciaGiro"]) ? $_POST["circunferenciaGiro"] : NULL, 
				"Peso Eje Delatero"=>isset($_POST["pesoEjeDelatero"]) ? $_POST["pesoEjeDelatero"] : NULL
			);
		$detallesExtra["garantia"]=
			array(
				"fabrica"=>isset($_POST["garantiaFabrica"]) ? $_POST["garantiaFabrica"] : NULL, 
				"vendedor"=>isset($_POST["garantiaVendedor"]) ? $_POST["garantiaFabrica"] : NULL, 
				"usuario"=>isset($_POST["garantiaUsuario"]) ? $_POST["garantiaUsuario"] : NULL
			);
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
		$Auto->updateInsGarageAuto(
			$garageContain[0]["i_avi_account_car_car_id"],
			(isset($_POST["recuperadorobo"]) && $garageContain[0]["recuperadoStole"]!=$_POST["recuperadorobo"] && $_POST["recuperadorobo"]>=0 && $_POST["recuperadorobo"]<2) ? $_POST["recuperadorobo"] : NULL, 
			(isset($_POST["reconstruido"]) && $garageContain[0]["reconstruido"]!=$_POST["reconstruido"] && $_POST["reconstruido"]>=0 && $_POST["reconstruido"]<2) ? $_POST["reconstruido"]: NULL, 
			(isset($_POST["legalizado"]) && $garageContain[0]["legalizado"]!=$_POST["legalizado"] && $_POST["legalizado"]>=0 && $_POST["legalizado"]<2) ? $_POST["legalizado"] : null, 
			(isset($_POST["facturaEmpresa"])) ? "1" : "0", 
			(isset($_POST["facturaLote"])) ? "1" : "0",
			(isset($_POST["facturaPfisica"])) ? "1" : "0",
			(isset($_POST["facturaAseguradora"])) ? "1" : "0",
			(isset($_POST["placa"]) && $garageContain[0]["placa"]!=$_POST["placa"]) ? $_POST["placa"] : NULL,
			(isset($_POST["holograma"]) && $_POST["holograma"]!=-1 && $garageContain[0]["hologram"]!==$_POST["holograma"]) ? $_POST["holograma"] : NULL,
			$detallesExtra
		);
		if($garageContain[0]["i_avi_account_car_alias"]!=$_POST["alias"])
		{
			$Garage->updateGarageAliasCar($_POST["auto"],$_POST["alias"],$garageContain[0]["i_avi_account_car_car_id"]);
		}
		if(isset($_POST["estado"]) && $garageContain[0]["i_avi_account_car_state"]!=$_POST["estado"])
		{
			$Garage->updateGarageStateCar($_POST["auto"],$_POST["estado"],$garageContain[0]["i_avi_account_car_car_id"]);
		}
		$Auto->updateObjCar(
			$garageContain[0]["i_avi_account_car_car_id"], 
			(isset($_POST["color"]) && $garageContain[0]["o_avi_car_color"]!=$_POST["color"]) ? $_POST["color"] : NULL ,
			isset($_POST["subnombres"]) ? $_POST["subnombres"]: NULL,
			isset($_POST["vin"]) && $garageContain[0]["o_avi_car_vin"]!=$_POST["vin"] ? $_POST["vin"] : NULL,
			$garageContain[0]["engineType"]!=$_POST["engineCar"] ? $_POST["engineCar"] : NULL,
			(isset($_POST["clasecar"]) && $garageContain[0]["clase"]!=$_POST["clasecar"]) ? $_POST["clasecar"] : NULL,
			isset($_POST["marca"]) ? $_POST["marca"] : NULL,
			isset($_POST["submarca"]) ? $_POST["submarca"] : NULL,
			isset($_POST["modelo"]) ? $_POST["modelo"] : NULL,
			$garageContain[0]["fuel"]!=$_POST["combustible"] ? $_POST["combustible"] : NULL,
			$garageContain[0]["trans"]!=$_POST["transmision"] ? $_POST["transmision"] : NULL,
			$garageContain[0]["doors"]!=$_POST["puertas"] ? $_POST["puertas"] : NULL,
			$garageContain[0]["ventanas"]!=$_POST["ventanas"] ? $_POST["ventanas"] : NULL,
			$garageContain[0]["interior"]!=$_POST["interior"] ? $_POST["interior"] : NULL,
			$garageContain[0]["o_avi_car_km"]!=$_POST["kilometraje"] ? $_POST["kilometraje"] : NULL,
			$garageContain[0]["dueno"]!=$_POST["duenos"] ? $_POST["duenos"] : NULL,
			$garageContain[0]["potencia"]!=$_POST["potencia"] ? $_POST["potencia"] : NULL,
			isset($_POST["otraMarcaInput"]) ? $_POST["otraMarcaInput"] : NULL,
			isset($_POST["otroModeloInput"]) ? $_POST["otroModeloInput"] : NULL,
			isset($_POST["otroAnoInput"]) ? $_POST["otroAnoInput"] : NULL,
			isset($_POST["otroVersionInput"]) ? $_POST["otroVersionInput"] : NULL
		);
		if ($Auto->adCar($_POST["auto"])) 
		{
			if(isset($_POST["precio"]))
			{
				$venta -> changePriceVenta($_POST["auto"], $_POST["precio"], $_POST["moneda"]);
			}
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
			$adDetailCar=$Auto->adCar($_POST["auto"]);
			$payMethod=array();
			$payMethod["debTransfer"]=isset($_POST["debTransfer"]) ? $_POST["debTransfer"] : NULL;
			$payMethod["credit"]=isset($_POST["credit"]) ? $_POST["credit"] : NULL;
			$payMethod["bankCredit"]=isset($_POST["bankCredit"]) ? $_POST["bankCredit"] : NULL;
			$payMethod["carfinance"]=isset($_POST["carfinance"]) ? $_POST["carfinance"] : NULL;
			$payMethod["changeHighPrice"]=isset($_POST["changeHighPrice"]) ? $_POST["changeHighPrice"] : NULL;
			$payMethod["changeLowPrice"]=isset($_POST["changeLowPrice"]) ? $_POST["changeLowPrice"] : NULL;
			$payMethod["leasing"]=isset($_POST["leasing"]) ? $_POST["leasing"] : NULL;
			if(!isset($adDetailCar["idauto"]))/*si aun no tiene esa info, inserts*/
			{
				$idAnuncio=$Auto->createAdCar($_POST["auto"], isset($_POST["anunciotext"]) ? $_POST["anunciotext"] : NULL, $payMethod, isset($_POST["negociable"]) ? $_POST["negociable"] : 1, NULL);
				if($idAnuncio)
				{	
					$Auto->contactoAd($idAnuncio,$arrayPhones, $arrayEmail);
					if(isset($_POST["zipcode"]))
						$Auto->locationAd($idAnuncio,$_POST["calle"], $_POST["colonia"], $_POST["zipcode"], $_POST["locationreference"]);
				}
			}
			else
			{
				$contacto=false;
				$location=false;
				$updateAuto  = $Auto->updateAdCar($_POST["auto"], (isset($_POST["anunciotext"]) && $adDetailCar["texto"]!=$_POST["anunciotext"]) ? $_POST["anunciotext"] : NULL, $payMethod, isset($_POST["negociable"]) ? $_POST["negociable"] : NULL);
				if ($updateAuto) 
				{
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
				}
				else
				{
					echo '{"Error":"Revise los datos"}';
				}
			}
		}
		else{
			echo "Not An Ad";
		}
			
	}
}