<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-07-26 13:57:06
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-05 17:34:56
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
class Country{
	public $name=null;
	public $id=null;
	public $currency=null;
	public $phoneCode=null;

	function __construc(){

	}

	public function getPhoneCodes(){
		$database=new Database;
        $db=$database->connect();
        $query="SELECT c_avi_country_id country_code, c_avi_country_phonecode phonecode FROM c_avi_country WHERE c_avi_country_phonecode IS NOT NULL ORDER BY c_avi_country_id";
        $country=array();
        if($data=$db->query($query)){
        	while ($row=$data->fetch_assoc()) {
        		$country[$row["country_code"]]=$row["phonecode"];
        	}
        }
        $db->close();
        return $country;
	}
    function states()
    {
        $database=new Database;
        $db=$database->connect();
        $query="SELECT c_avi_state_id, c_avi_state_name FROM c_avi_state WHERE c_avi_state_country_code='MX'";
        $estados=array();
        $queryDB=$db->query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $estados[$row["c_avi_state_id"]]=$row["c_avi_state_name"];
            }
        }
        $db->close();
        return $estados;
    }
    function getTownsByState($state){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT c_avi_zipcode_city town FROM c_avi_zipcode WHERE c_avi_zipcode_id_state='$state' GROUP BY c_avi_zipcode_city ORDER BY town";
        $municipios=array();
        $queryDB=$db->query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $municipios[]=$row["town"];
            }
        }
        $db->close();
        return $municipios;
        
    }
}