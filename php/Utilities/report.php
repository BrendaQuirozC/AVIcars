<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-28 10:59:25
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-09-21 15:59:10
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";

class Report{
	function doReport($idUser, $type, $profile, $text=null, $publication=null, $garage=null, $auto=null, $ad=null, $comment=null, $adcomment=null ){
		$coder = new Coder();
		$insert="f_avi_report_informer_id, f_avi_report_type_id, f_avi_report_profile_id ";
		$values="$idUser, $type, $profile";
		if($publication)
		{
			$insert.=", f_avi_report_publication_id";
			$values.=", $publication";
		}
		if($garage){
			$insert.=", f_avi_report_garage_id";
			$values.=", $garage";
		}	
		if($auto)
		{
			$insert.=", f_avi_report_car_id";
			$values.=", $auto";
		}
		if($ad)
		{
			$coder->decode($ad);
			$ad=$coder->toEncode;
			$insert.=", f_avi_report_ad_id";
			$values.=", $ad";
		}
		if($comment)
		{
			$insert.=", f_avi_report_comment";
			$values.=", $comment";
		}
		if($adcomment)
		{
			$insert.=", f_avi_report_ad_comment";
			$values.=", $adcomment";
		}
		if($text && $text!=""){
			$insert.=", f_avi_report_text";
			$values.=", '$text'";
		}
		$resp=false;
		$database=new Database;
        $db=$database->connect();
        $query="INSERT INTO f_avi_report ($insert) VALUES ($values)";
        if($data=$db->query($query)){
        	$resp=$db->insert_id;
        }
        $db->close();
        return $resp;
	}

	function reportType(){
		$database=new Database;
        $db=$database->connect();
        $query = "SELECT c_avi_report_type_id, c_avi_report_type_name FROM c_avi_report_type WHERE c_avi_report_type_status=1 ORDER BY c_avi_report_type_id ASC";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $reportName[$row["c_avi_report_type_id"]]=array("id" => $row["c_avi_report_type_id"],"nombre" => $row["c_avi_report_type_name"]);
            }
            return $reportName;
        }
        $db->close();
	}
	function isBlockedNow($blocker,$blocked){
		$database=new Database;
        $db=$database->connect();
        $query="SELECT f_avi_blocked_users_id
			FROM f_avi_blocked_users
			WHERE f_avi_blocked_users_blocker=$blocker AND f_avi_blocked_users_status=1 AND f_avi_blocked_users_blocked=$blocked";
        $ret=false;
        if($data=$db->query($query)){
        	if($data->num_rows>0){
        		$ret=true;
        	}
        }
        $db->close();
        return $ret;
	}
	function blockUser($blocker,$blocked){
		$ret=false;
		if(!$this->isBlockedNow($blocker,$blocked)){
			$database=new Database;
	        $db=$database->connect();
	        $query="INSERT INTO f_avi_blocked_users (f_avi_blocked_users_blocker, f_avi_blocked_users_blocked, f_avi_blocked_users_date) VALUES ($blocker, $blocked, NOW())";
	        if($db->query($query)){
	        	$ret=true;
	        }
	        $db->close();
		}
	    return $ret;
	}
	function unBlock($blocker,$blocked){
        $ret=false;
		if($this->isBlockedNow($blocker,$blocked)){
			$database=new Database;
	        $db=$database->connect();
	        $query="UPDATE f_avi_blocked_users SET f_avi_blocked_users_status=0 WHERE f_avi_blocked_users_blocker=$blocker AND f_avi_blocked_users_blocked=$blocked";
	       	//echo $query;
	        if($db->query($query)){
	        	$ret=true;
	        }
	        $db->close();
		}
	    return $ret;
	}
	function getBlockeds($blocker){
		$database=new Database;
        $db=$database->connect();
        $query="SELECT FABU.f_avi_blocked_users_blocked iduser, OAU.o_avi_user_username username, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name) name
			FROM f_avi_blocked_users FABU
			LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=FABU.f_avi_blocked_users_blocked
			LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
			WHERE FABU.f_avi_blocked_users_blocker=$blocker AND FABU.f_avi_blocked_users_status=1";
        $bloqueados=array();
        if($data=$db->query($query)){
        	if($data->num_rows>0){
        		while ($row=$data->fetch_assoc()) {
        			$bloqueados[]=$row;
        		}
        	}
        }
        $db->close();
        return $bloqueados;
	}
}