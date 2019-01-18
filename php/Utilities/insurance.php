<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-25 11:55:57
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-09-25 11:59:48
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
class Insurance{

 	function getAllInsuranceCarriers(){
 		$database=new Database;
        $db=$database->connect();
        $query="SELECT CAIC.c_avi_insurance_carrier_name nombre, CAIC.c_avi_insurance_carrier_id id, CAIC.c_avi_insurance_carrier_description nombre_completo FROM c_avi_insurance_carrier CAIC WHERE CAIC.c_avi_insurance_carrier_status=1 ORDER BY nombre";
        $carrier=array();
        if($data=$db->query($query)){
        	while ($row=$data->fetch_assoc()) {
        		$carrier[$row["id"]]=array("nombre"=>$row["nombre"],"nombre_completo"=>$row["nombre_completo"]);
        	}
        }
        $db->close();
        return $carrier;
 	}
 }