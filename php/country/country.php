<?php
/**
 * Created by Cairo Gonzalez
 * Date: 13/02/2018
 * Time: 04:05 PM
 * CLass to get all about countries and states
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
class Country
{
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
}

?>
