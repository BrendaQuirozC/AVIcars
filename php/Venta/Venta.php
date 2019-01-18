<?php
/**
 * created by Cairo Gonzalez Resendiz
 * Date: 14/02/2018
 * Hour: 11:08 AM
 * Clase de venta
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
class Venta extends Usuario
{

    function getfotos($tipo, $account, $iduser=null)
    {
        $database=new Database;
        $db=$database->connect();
        $sql1 = "SELECT tmp_avi_car_img_car FROM tmp_avi_car_img WHERE tmp_avi_car_img_type='$tipo' and tmp_avi_car_img_account_car_id='$account'";
        if($iduser){
            $sql1.="and tmp_avi_user_id='$iduser'";
        }
        $queryDB = $db->query($sql1);
        $imagen = array();
        if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$imagen[]=$row["tmp_avi_car_img_car"];
			}
		}
        $db->close();
        return $imagen;
    }
    function getfotoVin($rutaimagen, $carID)
    {
        $database=new Database;
        $db=$database->connect();
        $sql1 = "UPDATE o_avi_car SET o_avi_car_vin_img = '$rutaimagen' WHERE o_avi_car_id = '$carID'";
        $db -> query($sql1);
        $db -> close();
    }

    function getfotoCirculacion($rutaimagen, $carID)
    {
        $database=new Database;
        $db=$database->connect();
        $sql1 = "UPDATE o_avi_car SET o_avi_car_circulation_card_img = '$rutaimagen' WHERE o_avi_car_id = '$carID'";
        $db -> query($sql1);
        $db -> close();
    }
    function cardetail($idVersion=null, $color=null,$vin=null, $km=null, $facturaimg=null, $vinimg=null, $marca=null, $submarca=null, $modelo=null, $version=null, $otraMarca=null, $otraSubmarca=null, $otroModelo=null, $otraVersion=null)
    {
        $database=new Database;
        $db=$database->connect();
        $insert="";
        $values="";
        if($vin){
            $insert.=",o_avi_car_vin";
            $values.=",'$vin'";
        }
        if($facturaimg){
            $insert.=",o_avi_car_bill_img";
            $values.=",'$facturaimg'";
        }
        if($vinimg){
            $insert.=",o_avi_car_vin_img";
            $values.=",'$vinimg'";
        }
        if($km){
            $insert.=",o_avi_car_km";
            $values.=",$km";
        }
        if($color){
            $insert.=",o_avi_car_color";
            $values.=",$color";
        }
        if($idVersion){
            $insert.=",o_avi_car_version_id";
            $values.=",$idVersion";
        }
        if($marca)
        {
            $insert.=",o_avi_car_brand_id";
            $values.=",$marca";
        }
        if($otraMarca)
        {
            $insert.=",o_avi_car_name_brand";
            $values.=",'$otraMarca'";
        }
        if($submarca)
        {
            $insert.=",o_avi_car_subbrand_id";
            $values.=",$submarca";
        }
        if($otraSubmarca)
        {
            $insert.=",o_avi_car_name_subbrand";
            $values.=",'$otraSubmarca'";
        }
        if($modelo)
        {
            $insert.=",o_avi_car_model_id";
            $values.=",$modelo";   
        }
        if($otroModelo)
        {
            $insert.=",o_avi_car_name_model";
            $values.=",'$otroModelo'";
        }
        if($version)
        {
            $insert.=",o_avi_car_version_id";
            $values.=",$version";   
        }
        if($otraVersion)
        {
            $insert.=",o_avi_car_name_version";
            $values.=",'$otraVersion'";
        }
        if(strlen($insert)>0)
        {
            $insert=substr($insert, 1);
            $values=substr($values, 1);
        }

        $sql1 = "INSERT INTO o_avi_car ($insert) VALUES ($values)";
        $db -> query($sql1);
        $last_id = $db -> insert_id;
        $db -> close();
        return $last_id;
    }

    function placasCarro($placas, $estadoID, $imagenTargeta, $idObjetoCarro)
    {
        $database=new Database;
        $db=$database->connect();
        $query = "INSERT INTO a_avi_car_plate(a_avi_car_plate, a_avi_car_plate_state_id, a_avi_car_plate_circulation_card_img, a_avi_car_plate_car_id, a_avi_car_plate_entry_date) VALUES
        ('$placas', '$estadoID', '$imagenTargeta', '$idObjetoCarro', NOW())";
        $db -> query($query);
        $db -> close();
    }
    
    function stateCar()
    {
        $database=new Database;
        $db=$database->connect();
        $estado = array();
        $sql = "SELECT c_avi_car_state_id, c_avi_car_state FROM c_avi_car_state";
        $queryDB = $db->query($sql);
        if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$estado[$row["c_avi_car_state_id"]]=$row["c_avi_car_state"];
			}
		}
        $db->close();
        return $estado;
    }

    function colorCar()
    {
        $database=new Database;
        $db=$database->connect();
        $colores = array();
        $sql = "SELECT c_avi_color_id, c_avi_color_name, c_avi_color_img FROM c_avi_color ORDER BY c_avi_color_name ASC ";
        $queryDB = $db->query($sql);
        if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$colores[$row["c_avi_color_id"]]=array("nombre"=>$row["c_avi_color_name"], "img"=>$row["c_avi_color_img"]);
			}
		}
        $db->close();
        return $colores;
    }
    function checkImgCar($idUser, $ruta)
    {
        $database=new Database;
        $db=$database->connect();
        $colores = array();
        $sql = "SELECT COUNT(a_avi_car_img_id) FROM a_avi_car_img WHERE a_avi_car_img_car='$ruta' AND a_avi_car_img_account_id = '$idUser'";
        $queryDB = $db->query($sql);
        $row = $queryDB->fetch_array(MYSQLI_ASSOC);
        /* liberar la serie de resultados */
        $queryDB->free();
        /* cerrar la conexión */
        $db->close();
        return $row["COUNT(a_avi_car_img_id)"];
    }
    function imagenAuto($idUser, $ruta)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "INSERT INTO a_avi_car_img (a_avi_car_img_car, a_avi_car_img_account_car_id) VALUES('$ruta', $idUser)";
        $queryDB = $db->query($sql);
        $db->close();
    }

    function imagenAutoTmp($idUser, $ruta, $tipo)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "INSERT INTO tmp_avi_car_img (tmp_avi_car_img_car, tmp_avi_car_img_account_car_id, tmp_avi_car_img_type) VALUES('$ruta', '$idUser', '$tipo')";
        $queryDB = $db->query($sql);
        $db->close();
    }
    function checkImgCarTmp($idUser, $ruta)
    {
        $database=new Database;
        $db=$database->connect();
        $colores = array();
        $sql = "SELECT COUNT(tmp_avi_car_img_id) FROM tmp_avi_car_img WHERE tmp_avi_car_img_car='$ruta' AND tmp_avi_car_img_account_car_id  = '$idUser'";
        $queryDB = $db->query($sql);
        if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
                $img = $row["COUNT(tmp_avi_car_img_id)"];
            }
        }
        /* liberar la serie de resultados */
        $queryDB->free();
        /* cerrar la conexión */
        $db->close();
        return $img;
    }

    function userImgCarTmpbyRuta($ruta)
    {
        $database=new Database;
        $db=$database->connect();
        $colores = array();
        $sql = "SELECT tmp_avi_user_id FROM tmp_avi_car_img WHERE tmp_avi_car_img_car='$ruta'";
        $queryDB = $db->query($sql);
        $img="";
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $img = $row["tmp_avi_user_id"];
            }
        }
        /* liberar la serie de resultados */
        $queryDB->free();
        /* cerrar la conexión */
        $db->close();
        return $img;
    }
    function deletImgTmp($userAccount)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "DELETE FROM tmp_avi_car_img WHERE  tmp_avi_car_img_account_car_id = '$userAccount'";
        $queryDB = $db->query($sql);
        $db->close();
    }
    function deletOneImgTmp($ruta)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "DELETE FROM tmp_avi_car_img WHERE  tmp_avi_car_img_car = '$ruta'";
        $queryDB = $db->query($sql);
        $db->close();
    }
    function carAccount($idAccount, $idCar, $alias, $estado=null, $sell=null, $privacidad=null, $colaborador=null)
    {
        $database=new Database;
        $db=$database->connect();
        $insert="i_avi_account_car_account_id, i_avi_account_car_car_id, i_avi_account_car_alias";
        $values="$idAccount, $idCar, '$alias'";
        //$sql = "INSERT INTO i_avi_account_car (i_avi_account_car_account_id, i_avi_account_car_car_id) VALUES('$idAccount', '$idCar')";
        if($estado)
        {
            $insert.=", i_avi_account_car_state";
            $values.=", $estado";
        }
        if($sell)
        {
            $insert.=", i_avi_account_car_status";
            $values.=", $sell"; 
        }
        if($privacidad)
        {
            $insert.=", i_avi_account_car_privacy";
            $values.=", $privacidad";
        }
        if($colaborador)
        {
            $insert.=", i_avi_account_car_colaborator";
            $values.=", $colaborador";
        }
        $sql ="INSERT INTO i_avi_account_car ($insert) VALUES($values)";
        $queryDB = $db->query($sql);
        $last_id = $db->insert_id;
        $db -> close();
        return $last_id;
    }

    function levantarVenta($idAccount,$precio,$currency,$colaborador=null)
    {
        $database=new Database;
        $db=$database->connect();
        $this->bajaVenta($idAccount);
        if(!$precio)
        {
            $precio=0;
        }
        $insert="a_avi_sell_car_account_car_id , a_avi_sell_detaill_price, a_avi_sell_car_load_date, a_avi_sell_car_currency";
        $values="'$idAccount', '$precio', NOW(), '$currency'";
        if($colaborador){
            $insert.=", a_avi_sell_car_colaborator";
            $values.=", $colaborador";
        }
        $sql ="INSERT INTO a_avi_sell_car ($insert) VALUES($values)";
        $response=false;
        if($queryDB = $db->query($sql)){
            $response=true;
        }
        $db -> close();
        return $response;
        //$last_id = $db -> insert_id;
        //return $last_id;
    }
    function bajaVenta($idAccount)
    {
        $database=new Database;
        $db=$database->connect();
        $sql ="UPDATE a_avi_sell_car SET a_avi_sell_car_status=0 WHERE a_avi_sell_car_account_car_id=$idAccount and a_avi_sell_car_status=1";
        $response=false;
        if($queryDB = $db->query($sql)){
            $response=true;
        }
        return $response;
        $db -> close();
    }

    function changeStatusVenta($idInstance)
    {
        $database=new Database;
        $db=$database->connect();
        $sql ="UPDATE i_avi_account_car SET i_avi_account_car_status=1 WHERE i_avi_account_car_id=$idInstance";
        $response=false;
        if($queryDB = $db->query($sql)){
            $response=true;
        }
        //$last_id = $db -> insert_id;
        //return $last_id;
        $db -> close();
        return $response;
    }
    function changePriceVenta($idInstance, $precio, $currency)
    {
        $database=new Database;
        $db=$database->connect();
        $sql =" UPDATE a_avi_sell_car SET a_avi_sell_detaill_price='$precio', a_avi_sell_car_currency = '$currency' WHERE a_avi_sell_car_account_car_id=$idInstance";
        $response=false;
        if($queryDB = $db->query($sql)){
            $response=true;
        }
        //$last_id = $db -> insert_id;
        //return $last_id;
        $db -> close();
        return $response;
    }
    function levantarAgain($idInstance)
    {
        $database=new Database;
        $db=$database->connect();
        $sql ="UPDATE a_avi_sell_car SET a_avi_sell_car_status ='1' WHERE a_avi_sell_car_account_car_id=$idInstance";
        $response=false;
        if($queryDB = $db->query($sql)){
            $response=true;
        }
        //$last_id = $db -> insert_id;
        //return $last_id;
        $db -> close();
        return $response;
    }
    function vendido($idInstance)
    {
        $database=new Database;
        $db=$database->connect();
        $sql ="UPDATE a_avi_sell_car SET a_avi_sell_car_status ='2' WHERE a_avi_sell_car_account_car_id=$idInstance";
        $response=false;
        if($queryDB = $db->query($sql)){
            $response=true;
        }
        $db -> close();
        return $response;
    }
}

?>
