<?php
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Utilities/requestcertificationmodel.php';
$certification = new Certification();
$response = array();
if(!isset($_POST['perfil'])){
	$id_profile = 'NULL';
}
else{
	$id_profile = $_POST['perfil'];
	if(!$certification->getRequestCertificationProfileInserted($id_profile)){
		if($certification->insertRequestCertification($id_profile, $id_car = 'NULL', $id_garage = 'NULL')){
			if($certification->updateStatusUserVerified($id_profile)){
				//$response["Success"] = "Se ha enviado una solicitud para certificar sus datos";
			}
		}
		else{
			$response["Error"] = "Ocurri&oacute; un error al enviar la solicitud de certificaci&oacute;n de sus datos";
		}
	}
	echo json_encode($response);	
}
if(!isset($_POST['autos'])){
	$id_car = 'NULL';
}
else{
	$id_car = $_POST['autos'];
	for ($i=0;$i<count($id_car);$i++){
		if(!$certification->getRequestCertificationCarInserted($id_car[$i])){ 
			if($certification->insertRequestCertification($id_profile = 'NULL', $id_car[$i], $id_garage = 'NULL')){
				if($certification->updateStatusCarVerified($id_car[$i])){
					//$response["Success"] = "Se ha enviado una solicitud para certificar sus datos";
				}
			}
			else{
				$response["Error"] = "Ocurri&oacute; un error al enviar la solicitud de certificaci&oacute;n de sus datos";
			}
		}
		echo json_encode($response); 
	} 	
}
if(!isset($_POST['garages'])){
	$id_garage = 'NULL';
}
else{
	$id_garage = $_POST['garages'];
	for ($i=0;$i<count($id_garage);$i++){
		if(!$certification->getRequestCertificationGarageInserted($id_garage[$i])){ 
			if($certification->insertRequestCertification($id_profile = 'NULL', $id_car = 'NULL', $id_garage[$i])){
				if($certification->updateStatusAccountVerified($id_garage[$i])){
					//$response["Success"] = "Se ha enviado una solicitud para certificar sus datos";
				}
			}
			else{
				$response["Error"] = "Ocurri&oacute; un error al enviar la solicitud de certificaci&oacute;n de sus datos";
			}
		}
		echo json_encode($response); 
	}
}
?>