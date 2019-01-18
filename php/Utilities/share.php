<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-12 16:48:32
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-15 13:22:51
 */
class Share{
	function doSharing($tipo,$user,$target,$url=null){
		$insert="f_avi_share_sharer";
		$values="$user";
		switch ($tipo) {
			case 1:
				$insert.=",f_avi_share_user_shared";
				$values.=",$target";
				break;
			case 2:
				$insert.=",f_avi_share_account_shared";
				$values.=",$target";
				break;
			case 3:
				$insert.=",f_avi_share_car_shared";
				$values.=",$target";
				break;
			case 4:
				$insert.=",f_avi_share_post_shared";
				$values.=",$target";
				break;
			case 5:
				$insert.=",f_avi_share_ad";
				$values.=",$target";
				break;
			default:
				throw new Exception("No se puede compartir", 1);
				break;
		}
		if($url){
			$insert.=",f_avi_share_url";
			$values.=",'$url'";
		}
		$resp=false;
		$database=new Database;
        $db=$database->connect();
        $query="INSERT INTO f_avi_share ($insert) VALUES ($values)";
        if($data=$db->query($query)){
        	$resp=$db->insert_id;
        }
        $db->close();
        return $resp;
	}
	function getShared($shareID){
		$query="SELECT f_avi_share_sharer sharer, f_avi_share_account_shared garage, f_avi_share_user_shared user, f_avi_share_post_shared post, f_avi_share_ad ad, f_avi_share_car_shared car FROM f_avi_share WHERE f_avi_share_id=$shareID";
		
		$database=new Database;
        $db=$database->connect();
        $share=array();
        if($data=$db->query($query)){
        	if($data->num_rows>0){
        		while ($row=$data->fetch_assoc()) {
        			$share=$row;
        			if($row["garage"] != ""){
        				$type=2;
        			}
        			elseif ($row["user"] != "") {
        				$type=1;
        			}
        			elseif ($row["car"] != "") {
        				$type=3;
        			}
        			elseif ($row["post"] != "") {
        				$type=4;
        			}
        			elseif ($row["ad"] != "") {
        				$type=5;
        			}
        			else{
        				throw new Exception("Error al compartir", 1);
        				
        			}
        			$share["type"]=$type;
        		}
        	}
        }
        $db->close();
        return $share;
	}
}