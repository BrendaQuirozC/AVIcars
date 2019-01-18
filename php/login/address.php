<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 10/01/2018
 * Time: 12:12 PM
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
class Address
{
    function add($code)
    {
        $database=new Database;
        $db=$database->connect();
        $add = "";
        $query = "SELECT c_avi_zipcode.c_avi_zipcode_city, c_avi_state.c_avi_state_name , c_avi_country.c_avi_country_name 
                  FROM c_avi_zipcode 
                  LEFT JOIN c_avi_state on  c_avi_zipcode.c_avi_zipcode_id_state=c_avi_state.c_avi_state_id
                  LEFT JOIN c_avi_country on c_avi_zipcode.c_avi_zipcode_id_country=c_avi_country.c_avi_country_id
                  WHERE c_avi_zipcode_id='$code'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $add=array("city" => $row["c_avi_zipcode_city"], "state" =>  $row["c_avi_state_name"], "country" => $row["c_avi_country_name"]);
            }
            return $add;
        }
    }


    function verifyZip($zip_v){
        $database=new Database;
        $db=$database->connect();
        $sql2="SELECT c_avi_zipcode_id FROM c_avi_zipcode WHERE c_avi_zipcode_id =('$zip_v')";
        $queryDB = $db -> query($sql2);
        if($queryDB->num_rows>0) {
            while ($row = $queryDB->fetch_assoc()) {
                $z_code = array("signZipcode" => $row["c_avi_zipcode_id"]);
            }
            return $z_code;
        }
    }
}