<?php
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/databaseadmin.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
class Certification{
	public function insertRequestCertification($id_profile, $id_car, $id_garage){
		$databaseadmin = new DatabaseAdmin();
		$db = $databaseadmin->connect();
		$sql = "INSERT INTO c_avi_admin_request_certification VALUES('',$id_profile,$id_garage,$id_car,now(),NULL,0)";
		$query = $db->query($sql);
		$ret = false;
		if($query){
			$ret = true;
		}
		$db->close();
		return $ret;
	}
	public function updateStatusUserVerified($id_user){
		$database = new Database();
		$db = $database->connect();
		$sql = "UPDATE o_avi_user SET o_avi_user_verified = 3 WHERE o_avi_user_id = $id_user";
		$query = $db->query($sql);
		$ret = false;
		if($query){
			$ret = true;
		}
		$db->close();
		return $ret;
	}
	public function updateStatusCarVerified($id_car){
		$database = new Database();
		$db = $database->connect();
		$sql = "UPDATE i_avi_account_car SET i_avi_account_car_verified = 3 WHERE i_avi_account_car_id = $id_car";
		$query = $db->query($sql);
		$ret = false;
		if($query){
			$ret = true;
		}
		$db->close();
		return $ret;
	}
	public function updateStatusAccountVerified($id_garage){
		$database = new Database();
		$db = $database->connect();
		$sql = "UPDATE o_avi_account SET o_avi_account_verified = 3 WHERE o_avi_account_id = $id_garage";
		$query = $db->query($sql);
		$ret = false;
		if($query){
			$ret = true;
		}
		$db->close();
		return $ret;
	}
	public function getRequestCertificationProfileInserted($id_profile){
		$databaseadmin = new DatabaseAdmin();
		$db = $databaseadmin->connect();
		$sql = "SELECT * FROM c_avi_admin_request_certification WHERE c_avi_admin_request_certification_user_id = $id_profile";
		$query = $db->query($sql);
		$existenciaperfil = false;
		if($query->num_rows > 0){
			$existenciaperfil = true;
		}
		$db->close();
		return $existenciaperfil;
	}
	public function getRequestCertificationCarInserted($id_car){
		$databaseadmin = new DatabaseAdmin();
		$db = $databaseadmin->connect();
		$sql = "SELECT * FROM c_avi_admin_request_certification WHERE c_avi_admin_request_certification_car_id = $id_car";
		$query = $db->query($sql);
		$existenciaauto = false;
		if($query->num_rows > 0){
			$existenciaauto = true;
		}
		$db->close();
		return $existenciaauto;
	}
	public function getRequestCertificationGarageInserted($id_garage){
		$databaseadmin = new DatabaseAdmin();
		$db = $databaseadmin->connect();
		$sql = "SELECT * FROM c_avi_admin_request_certification WHERE c_avi_admin_request_certification_account_id = $id_garage";
		$query = $db->query($sql);
		$existenciagarage = false;
		if($query->num_rows > 0){
			$existenciagarage = true;
		}
		$db->close();
		return $existenciagarage;
	}
}
?>