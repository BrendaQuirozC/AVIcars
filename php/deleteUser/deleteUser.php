<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-07-02 14:12:18
 * @Last Modified by:   Cairo G. Resendiz
 * @Last Modified time: 2018-07-05 14:42:31
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
class DeleteUser
{
	public $idUser=null;
	function __construct($idUser)
	{
		if($idUser)
		{
			$this->idUser=$idUser;
		}
	}
	function shareOwn()
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM f_avi_share WHERE f_avi_share_sharer='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
		$db->close();
		return $resp;
	}
	function comments()
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM f_avi_publication_comment WHERE f_avi_publication_comment_user='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
		$db->close();
		return $resp;
	}
	function deletePublication($idPublication)
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM o_avi_publication WHERE o_avi_publication_id='$idPublication';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();	
		return $resp;
	}
	function follower()
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_user_follow_user WHERE a_user_follower_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
		$db->close();
		return $resp;
	}
	function following()
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_user_follow_user WHERE a_user_following_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
	}
	function followingAccount($idAccount)
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_user_follow_account WHERE a_user_following_account_id='$idAccount';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
	}
	function followerAccount()
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_user_follow_account WHERE a_user_follower_acc_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
	}
	/*
	function doEachSharingDelete($publicacionesId)
	{
		$database=new Database;
        $db=$database->connect();
        $this->eachSharingDelete(1, $db, $publicacionesId);
        $db->close();
	}
	function eachSharingDelete($i, $db, $publicacionesId)
	{
		if($i==6)
		{
			return 0;
		}
		else
		{
			$ipub=0;
			$allPublication=sizeof($publicacionesId);
			while ($ipub < $allPublication) {
				$this->deleteSharing($publicacionesId[$ipub], $i, $db);
				$ipub++;
				if($ipub==$allPublication)
				{
					$i++;
					$this->eachSharingDelete($i, $db, $publicacionesId);
				}
			}
		}
	}*/
	function deleteSharing($id, $typeShared)
	{	
		$database=new Database;
        $db=$database->connect();
		$field="";
		switch ($typeShared) {
			case 1:
				$field="f_avi_share_user_shared";
				break;
			case 2:
				$field="f_avi_share_account_shared";
				break;
			case 3:
				$field="f_avi_share_car_shared";
				break;
			case 4:
				$field="f_avi_share_post_shared";
				break;
			case 5:
				$field="f_avi_share_ad";
				break;
			default:
				break;
		}
		$query="DELETE FROM f_avi_share WHERE $field='$id';";
		
		$resp=false;

		if($db->query($query))
		{
			$resp=true;
		}
		$db->close();
		return $resp;
	}
	function findPublication()
	{
		$database=new Database;
        $db=$database->connect();
		$query="SELECT o_avi_publication_id FROM o_avi_publication WHERE o_avi_publication_author_user='$this->idUser'";
		$publicacionesId=array();
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) 
				{
					$publicacionesId[]=$row["o_avi_publication_id"];
				}
			}
		}
		$db->close();
		return $publicacionesId;
	}
	function eachPublicationDelete($publicacionesId)
	{
		$allPublication = sizeof($publicacionesId);
		foreach ($publicacionesId as $pb => $idpub) 
		{
			if($this->deletePublication($idpub))
			{
				$allPublication-=1;
				$this->likes($idpub, 4);
			}
		}
		if(!$allPublication)
		{
			return 0;
		}
		else
		{
			return $allPublication;
		}
	}
	function likes($objLike,$typeLike)
	{
		$field="";
		switch ($typeLike) {
			case 1:
				$field="f_avi_user_profile_id";
				break;
			case 2:
				$field="f_avi_user_account_id";
				break;
			case 3:
				$field="f_avi_user_car_id";
				break;
			case 4:
				$field="f_avi_user_publication_id";
				break;
			case 5:
				$field="f_avi_user_car_ad_id";
				break;
			default:
				break;
		}
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM f_avi_user_like WHERE $field='$objLike';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
	}
	function iDidLikes()
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM f_avi_user_like WHERE f_avi_user_liker_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
	}
	function deleteFiles()
	{
		$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_avi_user_file WHERE a_avi_user_file_user='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
	}
    function eliminarCuenta($id){
        $database=new Database;
        $db=$database->connect();
        $sql2="";
        $sql2="DELETE FROM o_avi_account WHERE o_avi_account_id='$id' ";
        $ret=false;
        if($db -> query($sql2))
        {
            $ret=true;
        }
        $db->close();
        return $ret;   
    }
    function deleteAttributeUserAccount($id)
    {
    	$database=new Database;
        $db=$database->connect();
        $sql2="";
        $sql2="DELETE FROM a_avi_user_account WHERE a_avi_user_id='$id'";
        $ret=false;
        if($db -> query($sql2))
        {
            $ret=true;
        }
        $db->close();
        return $ret;   
    }
    function deleteOGarages($id,$padre){
        $database=new Database;
        $db=$database->connect();
        $return=$this->recorrer($id,$padre,$db, "");
        $db->close();
        return $return;
    }
    function recorrer($id,$padre,$db,$mensaje){
        if(!$padre)
            $sql3="SELECT o_avi_account_id, o_avi_account_name,o_avi_account_father FROM o_avi_account WHERE o_avi_account_user_id= '$id' AND o_avi_account_father is null";
        else
            $sql3="SELECT o_avi_account_id, o_avi_account_name,o_avi_account_father FROM o_avi_account WHERE o_avi_account_user_id= '$id' AND o_avi_account_father='$padre'";
        $queryDB = $db -> query($sql3);
        $nc= array();
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                
                $nc[$row["o_avi_account_id"]]=array(
                    'nombre'=>$row["o_avi_account_name"],
                    'padre'=>$row["o_avi_account_father"],
                    'hijos'=>$this->recorrer($id,$row["o_avi_account_id"],$db,$mensaje)
                );

				$this->likes($row["o_avi_account_id"],2);
				$this->deleteSharing($row["o_avi_account_id"],2);
				if($this->deleteAccountDetail($row["o_avi_account_id"]))
				{
					if(!$this->deleteAccountFile($row["o_avi_account_id"]))
					{
						$mensaje.="\n Error al borrar archivo de Garage ".$row["o_avi_account_id"];
					}
					if(!$this->followingAccount($row["o_avi_account_id"]))
					{
						$mensaje.= "\n Error al borrar Seguidores de Garage ".$row["o_avi_account_id"];
					}
					if(!$this -> eliminarCuenta($row["o_avi_account_id"]))
					{
						$mensaje.= "\n Error al borrar Garage ".$row["o_avi_account_id"];
					}
				}
                
            }
        }
        elseif($queryDB->num_rows===0 && $padre)
        {
        	$this->likes($padre,2);
			$this->deleteSharing($padre,2);
        	if($this->deleteAccountDetail($padre))
			{
				if(!$this->deleteAccountFile($padre))
				{
					$mensaje.= "\n Error al borrar archivo de Garage ".$padre;
				}
				if(!$this->followingAccount($padre))
				{
					$mensaje.= "\n Error al borrar Seguidores de Garage ".$padre;
				}
				if(!$this->eliminarCuenta($padre))
				{
					$mensaje.="\n Error al borrar Garage ".$padre;
				}
			}
            
        }
        return $mensaje;
    }
    function deleteAccountDetail($id)
    {
    	$database=new Database;
        $db=$database->connect();
        $sql2="";
        $sql2="DELETE FROM a_avi_accountdetail WHERE a_avi_account_id='$id' ";
        $ret=false;
        if($db -> query($sql2))
        {
            $ret=true;
        }
        $db->close();
        return $ret;  
    }
    function deleteAccountFile($id)
    {
    	$database=new Database;
        $db=$database->connect();
        $sql2="";
        $sql2="DELETE FROM a_avi_account_file WHERE a_avi_account_file_account='$id'";
        $ret=false;
        if($db -> query($sql2))
        {
            $ret=true;
        }
        $db->close();
        return $ret;  
    }
    function userDetail()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM o_avi_userdetail WHERE o_avi_userdetail_id_user='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userPerfil()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_avi_user_perfil WHERE a_avi_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userAddress()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_avi_useraddress WHERE a_avi_useraddress_id_user='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userToken()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM f_avi_token WHERE f_avi_token_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userTokenDelete()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM f_avi_token_delete_profile WHERE f_avi_token_delete_profile_profile='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userFollowAd()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM a_avi_user_follow_ad WHERE a_avi_user_follower_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userAdCommets()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM f_avi_ad_comment WHERE f_avi_ad_comment_user='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userCotizaciones()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM o_avi_quotation WHERE o_avi_quotation_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function userInsuredVehicle()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM o_avi_insured_vehicle WHERE o_avi_insured_vehicle_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function deleteObjUser()
    {
    	$database=new Database;
        $db=$database->connect();
		$query="DELETE FROM o_avi_user WHERE o_avi_user_id='$this->idUser';";
		$resp=false;
		if($db->query($query))
		{
			$resp=true;
		}
	 	$db->close();
		return $resp;
    }
    function getUserDesactivatedetail($idUser)
    {
        $database=new Database;
		$db=$database->connect();
        $cuentaUsr = array();
		$sql = "SELECT o_avi_user_username username, o_avi_userdetail_id_user, o_avi_userdetail_name, o_avi_userdetail_last_name, o_avi_userdetail_phone, o_avi_user_email, a_avi_useraddress_street, a_avi_useraddress_zip_code, c_avi_gender_name genero, o_avi_userdetail_birth_date fechaNacimiento, o_avi_userdetail_gender generoid
        FROM o_avi_userdetail
        LEFT JOIN c_avi_gender ON c_avi_gender.c_avi_gender_id=o_avi_userdetail.o_avi_userdetail_gender
        LEFT JOIN o_avi_user ON o_avi_user.o_avi_user_id = o_avi_userdetail.o_avi_userdetail_id_user
        LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = o_avi_user.o_avi_user_id
        WHERE o_avi_userdetail_id_user='$idUser' AND o_avi_user_status in (2)";
		$queryDB=$db->query($sql);
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$cuentaUsr=$row;
			}
		}
		$db->close();
		return $cuentaUsr;
    }

   	function logDeleteUsr($message){
		$errorFile=$_SERVER["DOCUMENT_ROOT"]."/error/usrDeleted/usersDeleted.log";
		$error=$message;
		$fp=fopen($errorFile, "a");
		fputs($fp,$error."\n");
		fclose($fp);
	}

}