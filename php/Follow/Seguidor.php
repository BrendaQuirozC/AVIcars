<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-07 12:17:16
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:58:37
 */
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';

class Seguidor extends Garage
{
	public $idAquienSigues=null;
	public $acepted=null;
	private $follow=null;
	private $following=null;
	private $type=null;
	function __construct($type=1, $follow=null, $following=null)
	{
		$this->follow=$follow;
		$this->following=$following;
		$this->type=$type;
		if($follow && $following)
		{
			$siguiendoA=array();
			try
			{
				$siguiendoA=$this->followingTo();
				if(!empty($siguiendoA))
				{
					$this->acepted=$siguiendoA["aceptado"];
				}
			}
			catch(Exception $e)
			{
				//echo $e->getMessage();
			}
		}
	}
	function seguir($acepted=null, $follow=null, $following=null)
	{
		$database=new Database;
        $db=$database->connect();
     	$ret=false;
     	$table="";
     	$field="";
     	if(!$follow)
		{
			$follow=$this->follow;
		}
		if(!$following)
		{
			$following=$this->following;
		}
		if(!$following || !$follow)
		{
			throw new Exception("No data");
		}
		if(!$acepted)
		{
			$acepted=0;
		}
		switch ($this->type) {
			case 1:
				$table="a_user_follow_user";
				$field = "a_user_follower_user_id, a_user_following_user_id, a_user_follow_acepted";
				break;
			case 2: //garage
				$table="a_user_follow_account";
				$field="a_user_follower_acc_user_id, a_user_following_account_id, a_user_follow_acepted";
				break;
			case 3: //auto
				$table="a_user_follow_car";
				$field="a_user_follower_acc_user_id, a_user_following_i_car_id, a_user_follow_acepted";
				break;
			case 4: //ad
				$table="a_avi_user_follow_ad";
				$field="a_avi_user_follower_user_id, a_avi_user_following_ad_id, a_avi_user_follow_status";
				$acepted=1;
				break;
		}
		$query="INSERT INTO $table ($field) VALUES ('$follow','$following', $acepted)";
		if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
	}
	function unfollow($follow=null, $following=null)
	{
		$table="";
		$condition="";
		if(!$follow)
		{
			$follow=$this->follow;
		}
		if(!$following)
		{
			$following=$this->following;
		}
		if(!$following || !$follow)
		{
			throw new Exception("No data");
		}
		switch ($this->type) {
			case 1:
				$table="a_user_follow_user";
				$condition = "a_user_follower_user_id=$follow AND a_user_following_user_id=$following";
				break;
			case 2:
				$table="a_user_follow_account";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_account_id=$following";
				break;
			case 3:
				$table="a_user_follow_car";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_i_car_id=$following";
				break;
			case 3:
				$table="a_avi_user_follow_ad";
				$condition="a_avi_user_follower_user_id=$follow AND a_avi_user_following_ad_id=$following";
				break;
		}
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM $table WHERE $condition";
		if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
	}
	function seguidores($iduser,$t=1, $start=0)
	{
		$start*=10;
		$database=new Database;
        $db=$database->connect();
        switch ($t) {
        	case 1: //usuarios
        		$query="SELECT a_user_follow_id, a_user_follower_user_id seguidor, a_user_follow_acepted type, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar, a_avi_user_perfil.a_avi_user_perfil_privacy privacidad 
					FROM a_user_follow_user 
					LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_user.a_user_follower_user_id 
					LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_user.a_user_follower_user_id 
					LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
					LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_user.a_user_follower_user_id
					WHERE a_user_following_user_id='$iduser' AND a_user_follow_acepted = 1
					ORDER BY a_user_follow_id DESC
					LIMIT $start, 10;";
        		break;
        	case 2: //garages
        		$query="SELECT a_user_follower_acc_user_id seguidor, a_user_follow_acepted type, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar, a_avi_user_perfil.a_avi_user_perfil_privacy privacidad 
					FROM a_user_follow_account 
					LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_account.a_user_follower_acc_user_id 
					LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_account.a_user_follower_acc_user_id 
					LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
					LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_account.a_user_follower_acc_user_id
					WHERE a_user_following_account_id='$iduser' AND a_user_follow_acepted = 1
					ORDER BY a_user_follow_account_id DESC
					LIMIT $start, 10;";
        		break;
        	case 3: //autos
        		$query="SELECT a_user_follower_acc_user_id seguidor, a_user_follow_acepted type, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar, a_avi_user_perfil.a_avi_user_perfil_privacy privacidad 
					FROM a_user_follow_car
					LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_car.a_user_follower_acc_user_id 
					LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_car.a_user_follower_acc_user_id 
					LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
					LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_car.a_user_follower_acc_user_id
					WHERE a_user_following_i_car_id='$iduser' AND a_user_follow_acepted = 1
					ORDER BY a_user_follow_car_id DESC
					LIMIT $start, 10;";
        		break;
        	case 4: //anuncios
        		$query="SELECT a_avi_user_follower_user_id seguidor, 1 type, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar, a_avi_user_perfil.a_avi_user_perfil_privacy privacidad 
					FROM a_avi_user_follow_ad 
					LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_avi_user_follow_ad.a_avi_user_follower_user_id 
					LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_avi_user_follow_ad.a_avi_user_follower_user_id 
					LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
					LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_avi_user_follow_ad.a_avi_user_follower_user_id
					WHERE a_avi_user_following_ad_id='$iduser'
					ORDER BY a_avi_user_follow_ad_id DESC
					LIMIT $start, 10;";
        		break;
        	default: //usuarios
        		$query="SELECT a_user_follower_user_id seguidor, a_user_follow_acepted type, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar, a_avi_user_perfil.a_avi_user_perfil_privacy privacidad 
					FROM a_user_follow_user 
					LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_user.a_user_follower_user_id 
					LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_user.a_user_follower_user_id 
					LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
					LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_user.a_user_follower_user_id
					WHERE a_user_following_user_id='$iduser' AND a_user_follow_acepted = 1
					ORDER BY a_user_follower_user_id DESC
					LIMIT $start, 10;";
        		break;
        }
        
		$seguidores=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$seguidores[]=$row;
				}
			}
		}
		$db->close();
		return $seguidores;
	}
	function getCountFollowers($iduser,$t=1){
		$database=new Database;
        $db=$database->connect();
		switch ($t) {
			case 1:
				$query="SELECT count(a_user_follow_id) cuantos
						FROM a_user_follow_user
						WHERE a_user_following_user_id='$iduser' AND a_user_follow_acepted = 1";
				break;
			case 2:
				$query="SELECT count(a_user_follower_acc_user_id) cuantos
						FROM a_user_follow_account
						WHERE a_user_following_account_id='$iduser' AND a_user_follow_acepted = 1";
				break;
			case 3:
				$query="SELECT count(a_user_follower_acc_user_id) cuantos
						FROM a_user_follow_car
						WHERE a_user_following_i_car_id='$iduser' AND a_user_follow_acepted = 1";
				break;
			case 4:
				$query="SELECT count(a_avi_user_follower_user_id) cuantos
						FROM a_avi_user_follow_ad
						WHERE a_avi_user_follow_ad_id='$iduser'";
				break;			
			default:
				$query="SELECT count(a_user_follow_id) cuantos
						FROM a_user_follow_user
						WHERE a_user_following_account_id='$iduser' AND a_user_follow_acepted = 1";
				break;
		}
		$followers=0;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$followers=$row["cuantos"];
				}
			}
		}
		$db->close();
		return $followers;
	}
	function getCountwantFollowBy($iduser,$t=1){
		$database=new Database;
        $db=$database->connect();
		switch ($t) {
			case 1:
				$query="SELECT count(a_user_follow_id) cuantos
						FROM a_user_follow_user
						WHERE a_user_following_user_id='$iduser' AND a_user_follow_acepted = 0";
				break;
			case 2:
				$query="SELECT count(a_user_follower_acc_user_id) cuantos
						FROM a_user_follow_account
						WHERE a_user_following_account_id='$iduser' AND a_user_follow_acepted = 0";
				break;
			case 3:
				$query="SELECT count(a_user_follower_acc_user_id) cuantos
						FROM a_user_follow_car
						WHERE a_user_following_i_car_id='$iduser' AND a_user_follow_acepted = 0";
				break;
			case 4:
				$query="SELECT count(a_avi_user_follower_user_id) cuantos
						FROM a_avi_user_follow_ad
						WHERE a_avi_user_follow_ad_id='$iduser'";
				break;			
			default:
				$query="SELECT 0 cuantos
						FROM a_user_follow_user
						WHERE a_user_following_account_id='$iduser' AND a_user_follow_acepted = 0";
				break;
		}
		$followers=0;
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$followers=$row["cuantos"];
				}
			}
		}
		$db->close();
		return $followers;
	}
	function siguiendo($iduser,$start=0)
	{
		$database=new Database;
        $db=$database->connect();
		$query="SELECT a_user_following_user_id siguiendo, a_user_follow_acepted type, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar, a_avi_user_perfil_privacy privacidad
		FROM a_user_follow_user 
		LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_user.a_user_following_user_id 
		LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_user.a_user_following_user_id 
		LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
		LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_user.a_user_following_user_id
		WHERE a_user_follower_user_id='$iduser' AND a_user_follow_acepted = 1
		ORDER BY a_user_following_user_id DESC LIMIT $start, 9;";
		//echo $query;
		$seguidores=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$seguidores[]=$row;
				}
			}
		}
		$db->close();
		return $seguidores;
	}

	function alreadyFollowing($follow, $following, $type)
	{
		$database=new Database;
        $db=$database->connect();
		$query="SELECT a_user_follower_user_id seguidor, a_user_following_user_id siguiendo, a_user_follow_acepted type
		FROM a_user_follow_user
		WHERE a_user_follower_user_id='$follow' 
		AND a_user_following_user_id = '$following'
		AND a_user_follow_acepted = '$type';";
		$seguidores=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$seguidores[]=$row;
				}
			}
		}
		$db->close();
		return $seguidores;
	}

	function followingGarage($iduser, $start=0)
	{
		$database=new Database;
        $db=$database->connect();
		$query="SELECT a_user_following_account_id siguiendo, o_avi_userdetail_name nombre, o_avi_userdetail_id_user idPersona, o_avi_userdetail_last_name apellido, a_avi_user_perfil_avatar avatar, o_avi_account_name garageNombre, a_avi_accountdetail_avatar_img garageAvatar, o_avi_account_type_id privacidad
		FROM a_user_follow_account 
		LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = a_user_follow_account.a_user_following_account_id
		LEFT JOIN o_avi_user ON o_avi_user.o_avi_user_id = o_avi_account.o_avi_account_user_id
		LEFT JOIN a_avi_accountdetail ON a_avi_accountdetail.a_avi_account_id = o_avi_account.o_avi_account_id
		LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user =  o_avi_user.o_avi_user_id
		LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  o_avi_user.o_avi_user_id
		WHERE a_user_follower_acc_user_id='$iduser'
		ORDER BY a_user_follow_account_id DESC LiMIT $start, 9;";
		$seguidores=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$seguidores[]=$row;
				}
			}
		}
		$db->close();
		return $seguidores;
	}
	function followingCar($iduser, $start=0)
	{
		$database=new Database;
        $db=$database->connect();
		$query="SELECT IAAC.i_avi_account_car_id siguiendo, IAAC.i_avi_account_car_id siguiendo2, OAA.o_avi_account_user_id idUsuario, OAA.o_avi_account_id idGarage, OAA.o_avi_account_name garageNombre, IAAC.i_avi_account_car_alias aliasAuto, OACA.o_avi_car_ad_sold vendido, AACI.a_avi_car_img_car imgAuto, AAAD.a_avi_accountdetail_avatar_img garageAvatar, IAAC.i_avi_account_car_privacy privacidad, IAAC.i_avi_account_car_account_id idDueño
			FROM a_user_follow_car AUFC
			LEFT JOIN i_avi_account_car IAAC ON IAAC.i_avi_account_car_id=AUFC.a_user_following_i_car_id
			LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=IAAC.i_avi_account_car_account_id
			LEFT JOIN o_avi_car_ad OACA ON OACA.o_avi_car_ad_car_id=IAAC.i_avi_account_car_id
			LEFT JOIN a_avi_car_img AACI ON AACI.a_avi_car_img_account_car_id = IAAC.i_avi_account_car_id
			LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
			WHERE AUFC.a_user_follower_acc_user_id=$iduser
			GROUP BY IAAC.i_avi_account_car_id
			ORDER BY aliasAuto DESC
			LIMIT $start, 9";
		$seguidores=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$seguidores[]=$row;
				}
			}
		}
		$db->close();
		return $seguidores;
	}


	function nameOfGarage($idGarage)
	{
		$database=new Database;
        $db=$database->connect();
		$query="SELECT o_avi_account_name garageName, o_avi_account_user_id userId, o_avi_account_id garageId
				FROM o_avi_account
				LEFT JOIN f_avi_notification ON f_avi_notification.f_avi_notification_sender_user_id = o_avi_account.o_avi_account_user_id
				WHERE o_avi_account_id='$idGarage';";
		$seguidores=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$seguidores=$row;
				}
			}
		}
		$db->close();
		return $seguidores;
	}

	function wantFollowBy($iduser,$t=1,$start=0)
	{
		$start*=10;
		$database=new Database;
        $db=$database->connect();
        switch ($t) {
        	case 1: //usuario
        		$query="SELECT a_user_follower_user_id seguidor, a_user_follow_acepted accept, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar 
				FROM a_user_follow_user
				LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_user.a_user_follower_user_id 
				LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_user.a_user_follower_user_id 
				LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
				LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_user.a_user_follower_user_id 
				WHERE a_user_following_user_id='$iduser' AND a_user_follow_acepted=0
				ORDER BY a_user_follow_id DESC
					LIMIT $start, 10;";
        		break;
        	case 2: //garage
        		$query="SELECT a_user_follower_acc_user_id seguidor, a_user_follow_acepted accept, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar 
				FROM a_user_follow_account 
				LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_account.a_user_follower_acc_user_id 
				LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_account.a_user_follower_acc_user_id 
				LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
				LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_account.a_user_follower_acc_user_id
				WHERE a_user_following_account_id='$iduser' AND a_user_follow_acepted = 0
				ORDER BY a_user_follow_account_id DESC
					LIMIT $start, 10;";
				break;
				case 3: //autos
        		$query="SELECT a_user_follower_acc_user_id seguidor, a_user_follow_acepted accept, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar 
					FROM a_user_follow_car
					LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_car.a_user_follower_acc_user_id 
					LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_car.a_user_follower_acc_user_id 
					LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
					LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_car.a_user_follower_acc_user_id
					WHERE a_user_following_i_car_id='$iduser' AND a_user_follow_acepted = 0
					ORDER BY a_user_follow_car_id DESC
					LIMIT $start, 10;";
        		break;
			case 4: //anuncio
        		$query="SELECT a_avi_user_follower_user_id seguidor, 1 type, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar 
				FROM a_avi_user_follow_ad 
				LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_avi_user_follow_ad.a_avi_user_follower_user_id 
				LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_avi_user_follow_ad.a_avi_user_follower_user_id 
				LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
				LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_avi_user_follow_ad.a_avi_user_follower_user_id
				WHERE a_avi_user_following_ad_id='$iduser'
				ORDER BY a_avi_user_follow_ad_id DESC
					LIMIT $start, 10;";
				break;
        	default: //usuario
        		$query="SELECT a_user_follower_user_id seguidor, a_user_follow_acepted accept, o_avi_userdetail_name nombre, o_avi_userdetail_last_name apellido, a_avi_useraddress_zip_code zip, c_avi_zipcode_city city, a_avi_user_perfil_avatar avatar 
				FROM a_user_follow_user
				LEFT JOIN o_avi_userdetail ON o_avi_userdetail.o_avi_userdetail_id_user = a_user_follow_user.a_user_follower_user_id 
				LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = a_user_follow_user.a_user_follower_user_id 
				LEFT JOIN c_avi_zipcode ON c_avi_zipcode.c_avi_zipcode_id = a_avi_useraddress.a_avi_useraddress_zip_code
				LEFT JOIN a_avi_user_perfil ON a_avi_user_perfil.a_avi_user_id =  a_user_follow_user.a_user_follower_user_id 
				WHERE a_user_following_user_id='$iduser' AND a_user_follow_acepted=0
				ORDER BY a_user_follow_id DESC
					LIMIT $start, 10;";
        		break;
        }
        //echo $query;
		$seguidores=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$seguidores[]=$row;
				}
			}
		}
		$db->close();
		return $seguidores;
	}

	function followingTo($follow=null, $following=null)
	{
		$table="";
		$condition="";
		if(!$follow)
		{
			$follow=$this->follow;
		}
		if(!$following)
		{
			$following=$this->following;
		}
		if(!$following || !$follow)
		{
			throw new Exception("No data");
		}
		$database=new Database;
        $db=$database->connect();
        switch ($this->type) {
			case 1: //usuario
				$table="a_user_follow_user";
				$condition = "a_user_follower_user_id=$follow AND a_user_following_user_id=$following";
				break;
			case 2: //garage
				$table="a_user_follow_account";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_account_id=$following";
				break;
			case 3: //auto
				$table="a_user_follow_car";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_i_car_id=$following";
				break;
		}
		$query="SELECT a_user_follow_acepted aceptado FROM $table WHERE $condition;";
		$siguesA=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				$this->idAquienSigues=true;
				while ($row=$result->fetch_assoc()) {
					$siguesA=array(
						"aceptado"=>$row["aceptado"]
						);
				}
			}
		}
		$db->close();
		return $siguesA;
	}

	function reject($following=null,$follow=null, $type=null)
	{
		$database=new Database;
        $db=$database->connect();
        switch ($type) 
        {
			case 1:
				$table="a_user_follow_user";
				$condition = "a_user_follower_user_id=$follow AND a_user_following_user_id=$following";
				break;
			case 2: //garage
				$table="a_user_follow_account";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_account_id=$following";
				break;
			case 3: //auto
				$table="a_user_follow_car";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_i_car_id=$following";
				break;
		}
        $sql = "DELETE FROM $table WHERE $condition AND a_user_follow_acepted = 0;";
        $respuesta=false;
        if($db->query($sql))
        {
        	$respuesta=true;
        }
        return $respuesta;
        $db->close();	
	}

	function acceptFollower($following=null, $follow=null, $type=null)
	{
		if(!$follow)
		{
			$follow=$this->follow;
		}
		if(!$following)
		{
			$following=$this->following;
		}
		if(!$following || !$follow)
		{
			throw new Exception("No data");
		}
		$database=new Database;
        $db=$database->connect();
        switch ($type) 
        {
			case 1:
				$table="a_user_follow_user";
				$condition = "a_user_follower_user_id=$follow AND a_user_following_user_id=$following";
				break;
			case 2: //garage
				$table="a_user_follow_account";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_account_id=$following";
				break;
			case 3: //auto
				$table="a_user_follow_car";
				$condition="a_user_follower_acc_user_id=$follow AND a_user_following_i_car_id=$following";
				break;
		}
        $query="UPDATE $table SET a_user_follow_acepted=1 WHERE $condition ";
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        return $ret;
        $db->close();
	}

	function deleteRequestNotification($following=null,$follow=null, $type=null, $idGarage=null, $idCar=null)
	{
		$database=new Database;
        $db=$database->connect();
        $sql = "";
        switch ($type) 
        {
			case 1: //user
				$condition = " f_avi_notification_addresses_user_id = '$following'  AND f_avi_notification_sender_user_id = '$follow' ";
				$and = " (f_avi_notification_type_id = 12 OR f_avi_notification_type_id = 17 OR f_avi_notification_type_id = 1) ";
				break;
			case 2: //garage
				$condition=" f_avi_notification_addresses_user_id = '$following'  AND f_avi_notification_sender_user_id = '$follow' AND f_avi_notification_account_id = '$idGarage'" ;
				$and = "(f_avi_notification_type_id = 13 OR f_avi_notification_type_id = 18 OR f_avi_notification_type_id = 2)";
				break;
			case 3: //auto
				$condition=" f_avi_notification_addresses_user_id = '$following'  AND f_avi_notification_sender_user_id = '$follow' AND f_avi_notification_car_id = '$idCar'";
				$and = " (f_avi_notification_type_id = 24 OR f_avi_notification_type_id = 19 OR f_avi_notification_type_id = 3) ";
				break;
		}
        $sql="DELETE FROM f_avi_notification WHERE $condition AND $and ";
        $respuesta=false;
        if($db->query($sql))
        {
        	$respuesta=true;
        }
        return $respuesta;
        $db->close();	
	}

	function updateConfirmNotification($following=null,$follow=null, $type = null)
	{
		$database=new Database;
        $db=$database->connect();
        $sql = "";
        switch ($type) 
        {
			case 1: //user
				$set="f_avi_notification_type_id = 1";
				$condition = " f_avi_notification_addresses_user_id = $following  AND f_avi_notification_sender_user_id = $follow ";
				$type_condition = " f_avi_notification_type_id = 12";
				break;
			case 2: //garage
				$set="f_avi_notification_type_id = 2";
				$condition=" f_avi_notification_addresses_user_id = $following  AND f_avi_notification_sender_user_id = $follow ";
				$type_condition = " f_avi_notification_type_id = 13";
				break;
			case 3: //auto
				$set="f_avi_notification_type_id = 3";
				$condition=" f_avi_notification_addresses_user_id = $following  AND f_avi_notification_sender_user_id = $follow ";
				$type_condition = " f_avi_notification_type_id = 24";
				break;
		}
        $sql="UPDATE f_avi_notification SET $set WHERE $condition AND $type_condition ";
        $respuesta=false;
        if($db->query($sql))
        {
        	$respuesta=true;
        }
        return $respuesta;
        $db->close();	
	}

	function __destruct(){
		$this->idAquienSigues=null;
		$this->follow=null;
		$this->following=null;
		$this->acepted=null;
		$this->type=null;
	}
}
?>