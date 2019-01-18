<?php
//require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
/**
 *
 */
class Garage extends Usuario
{
    function account($idUsuario)
    {
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $query = "SELECT o_avi_account_id, o_avi_account_name, o_avi_account_user_id, o_avi_account_father padre, a_avi_accountdetail_avatar_img, o_avi_account_verified,
         (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_account_id=o_avi_account.o_avi_account_id) likes,
         (SELECT count(f_avi_share_account_shared) FROM f_avi_share WHERE f_avi_share_account_shared=o_avi_account.o_avi_account_id) shareds
        FROM o_avi_account
        LEFT JOIN a_avi_accountdetail ON  a_avi_accountdetail.a_avi_account_id = o_avi_account.o_avi_account_id
        WHERE o_avi_account_user_id = '$idUsuario' AND o_avi_account_status = '1' order by o_avi_account_name";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account[]=array("idAccount" => $row["o_avi_account_id"], "nameAccount" => $row["o_avi_account_name"], "userOwner" => $row["o_avi_account_user_id"], "likes"=>$row["likes"], "shared" => $row["shareds"], "padre"=> $row["padre"], "avatar"=> $row["a_avi_accountdetail_avatar_img"], "verified"=> $row["o_avi_account_verified"]);
            }
        }
        $db->close();
        return $account;
    }
    function accountNoVerifiedOrPending($idUsuario,$status)
    {
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $query = "SELECT o_avi_account_id, o_avi_account_name, o_avi_account_user_id, o_avi_account_father padre, a_avi_accountdetail_avatar_img, o_avi_account_verified,
         (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_account_id=o_avi_account.o_avi_account_id) likes,
         (SELECT count(f_avi_share_account_shared) FROM f_avi_share WHERE f_avi_share_account_shared=o_avi_account.o_avi_account_id) shareds
        FROM o_avi_account
        LEFT JOIN a_avi_accountdetail ON  a_avi_accountdetail.a_avi_account_id = o_avi_account.o_avi_account_id
        WHERE o_avi_account_user_id = '$idUsuario' AND o_avi_account_status = '1' AND o_avi_account_verified = $status order by o_avi_account_name";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account[]=array("idAccount" => $row["o_avi_account_id"], "nameAccount" => $row["o_avi_account_name"], "userOwner" => $row["o_avi_account_user_id"], "likes"=>$row["likes"], "shared" => $row["shareds"], "padre"=> $row["padre"], "avatar"=> $row["a_avi_accountdetail_avatar_img"], "verified"=> $row["o_avi_account_verified"]);
            }
        }
        $db->close();
        return $account;
    }
    function accountByTen($idUsuario,$t)
    {
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $query = "SELECT o_avi_account_id, o_avi_account_name, o_avi_account_user_id, o_avi_account_father padre, a_avi_accountdetail_avatar_img, o_avi_account_verified,
         (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_account_id=o_avi_account.o_avi_account_id) likes,
         (SELECT count(f_avi_share_account_shared) FROM f_avi_share WHERE f_avi_share_account_shared=o_avi_account.o_avi_account_id) shareds
        FROM o_avi_account
        LEFT JOIN a_avi_accountdetail ON  a_avi_accountdetail.a_avi_account_id = o_avi_account.o_avi_account_id
        WHERE o_avi_account_user_id = '$idUsuario' AND o_avi_account_status = '1' order by o_avi_account_name LIMIT $t,10";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account[]=array("idAccount" => $row["o_avi_account_id"], "nameAccount" => $row["o_avi_account_name"], "userOwner" => $row["o_avi_account_user_id"], "likes"=>$row["likes"], "shared" => $row["shareds"], "padre"=> $row["padre"], "avatar"=> $row["a_avi_accountdetail_avatar_img"], "verified"=> $row["o_avi_account_verified"]);
            }
        }
        $db->close();
        return $account;
    }
    function getTotalGaragesUser($user){
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $total=0;
        $query = "SELECT count(o_avi_account_id) cuantos
        FROM o_avi_account
        WHERE o_avi_account_user_id = '$user' AND o_avi_account_status = '1'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $total=$row["cuantos"];
            }
        }
        $db->close();
        return $total;
    }
    function getTotalNoSecretGaragesUser($user){
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $total=0;
        $query = "SELECT count(o_avi_account_id) cuantos
        FROM o_avi_account
        WHERE o_avi_account_user_id = '$user' AND o_avi_account_status = '1' AND o_avi_account_type_id !=3";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $total=$row["cuantos"];
            }
        }
        $db->close();
        return $total;
    }
    function garagesWithoutSecrets($idUsuario)
    {
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $query = "SELECT o_avi_account_name
        FROM o_avi_account
        WHERE o_avi_account_user_id = '$idUsuario' AND o_avi_account_type_id !=3 AND o_avi_account_status = '1' order by o_avi_account_created_date";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account[]=array( "nameAccount" => $row["o_avi_account_name"]);
            }
        }
        $db->close();
        return $account;
    }
    function accountInstancia($idAccountUsr,$following=true,$sold=true)
    {
        $database=new Database;
        $db=$database->connect();
        $accountInst = array();
        $query ="SELECT i_avi_account_car_id, o_avi_car_version_id, c_avi_car_state, a_avi_car_img_car, c_avi_color_name,   i_avi_account_car_status, a_avi_sell_detaill_price, a_avi_sell_car_currency, a_avi_sell_car_status, i_avi_account_car_alias alias, i_avi_account_car_privacy privacidad, o_avi_account_user_id, o_avi_car_name_brand, o_avi_car_name_subbrand, o_avi_car_name_model, i_avi_account_car_verified verificado,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_car_id=i_avi_account_car.i_avi_account_car_id) likes,
            (SELECT count(f_avi_share_car_shared) FROM f_avi_share WHERE f_avi_share_car_shared=i_avi_account_car.i_avi_account_car_id) shared, OACA.o_avi_car_ad_sold vendido
         FROM i_avi_account_car
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_car_id
        LEFT JOIN a_avi_car_img ON  a_avi_car_img.a_avi_car_img_account_car_id = i_avi_account_car.i_avi_account_car_id
        LEFT JOIN c_avi_car_state ON c_avi_car_state.c_avi_car_state_id = i_avi_account_car.i_avi_account_car_state
        LEFT JOIN a_avi_sell_car ON (a_avi_sell_car.a_avi_sell_car_account_car_id = i_avi_account_car.i_avi_account_car_id AND a_avi_sell_car.a_avi_sell_car_status=1)
        LEFT JOIN c_avi_color ON c_avi_color.c_avi_color_id = o_avi_car.o_avi_car_color
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id 
        LEFT JOIN o_avi_car_ad OACA ON i_avi_account_car.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
        WHERE i_avi_account_car_account_id = '$idAccountUsr'  AND i_avi_account_car_status=1 ";
        if(!$following){

            $query.=" AND i_avi_account_car_privacy IN (1,2)";
        }
        if(!$sold){

            $query.=" AND (o_avi_car_ad_sold = 0 OR o_avi_car_ad_sold is null)";
        }
        $query.=" group by i_avi_account_car_id ORDER BY i_avi_account_car_alias";
        //echo $query;
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $accountInst[]=$row;
            }
        }
        $db->close();
        return $accountInst;
        unset($accountInst);
    }
    function secretlessAccountInstancia($idAccountUsr)
    {
        $database=new Database;
        $db=$database->connect();
        $accountInst = array();
        $query ="SELECT i_avi_account_car_id,i_avi_account_car_privacy privacidad
        FROM i_avi_account_car 
        WHERE i_avi_account_car_account_id = '$idAccountUsr'  AND i_avi_account_car_status=1 AND i_avi_account_car_privacy!=3 group by i_avi_account_car_id";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $accountInst[]=$row;
            }
        }
        $db->close();
        return $accountInst;
    }
    function instanciaById($id)
    {
        $database=new Database;
        $db=$database->connect();
        $Inst = array();
        $query ="SELECT 
	        	i_avi_account_car_id, 
	        	i_avi_account_car_account_id, 
	        	i_avi_account_car_car_id, 
	        	o_avi_car_km,
                o_avi_car_number_owner dueno,
                o_avi_car_horsepower potencia,
	        	o_avi_account_name, 
                o_avi_account_type_id privacyGarage,
                o_avi_account_verified verified,
	        	o_avi_account_id,
                a_avi_accountdetail_avatar_img avatarGarage,
                o_avi_userdetail_name nameUSer,
                o_avi_userdetail_last_name lastNameUser,
                a_avi_user_perfil_privacy PrivacyUser,
	        	o_avi_car_version_id, 
	        	o_avi_car_brand_id brand, 
	        	o_avi_car_subbrand_id subbrand, 
	        	o_avi_car_model_id model,
				o_avi_car_class_id clase,
				o_avi_car_color,
				o_avi_car_vin,
				o_avi_car_engine_type_id engineType,
                o_avi_car_doors doors,
                o_avi_car_fuel fuel,
                o_avi_car_transmission trans,
                o_avi_car_windows ventanas,
                o_avi_car_interior interior,
                o_avi_car_name_brand nombreMarca,
                o_avi_car_name_subbrand nombreSubmarca,
                o_avi_car_name_model nombreModelo,
                o_avi_car_name_version nombreVersion,
				c_avi_car_state,
				c_avi_color_name,
				i_avi_account_car_alias,
				i_avi_account_car_status,
				a_avi_sell_detaill_price,
                a_avi_sell_car_status status_sell,
                a_avi_sell_car_currency currency,
                i_avi_account_car_plate placa,
                i_avi_account_car_hologram hologram,
				i_avi_account_car_state,
				i_avi_account_car_privacy privacidad,
                i_avi_account_factura_aseguradora faseguradora,
                i_avi_account_factura_empresa fempresa,
                i_avi_account_factura_lote flote,
                i_avi_account_factura_personafisica fpersonafisica,
				i_avi_account_car_detail_stole recuperadoStole,
				i_avi_account_car_detail_rebuilt reconstruido,
				i_avi_account_car_detail_legalized legalizado,
				i_avi_account_car_verified verificado,
                i_avi_account_car_extra_detail extras,
                OAU.o_avi_user_id user,
                (SELECT a_avi_car_img_car FROM a_avi_car_img WHERE a_avi_car_img_account_car_id='$id' LIMIT 1) avatar
				FROM i_avi_account_car
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id=i_avi_account_car.i_avi_account_car_account_id
        LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=o_avi_account.o_avi_account_id
        LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=o_avi_account.o_avi_account_user_id
        LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
        LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id = OAU.o_avi_user_id
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_car_id
		LEFT JOIN c_avi_car_state ON c_avi_car_state.c_avi_car_state_id = i_avi_account_car.i_avi_account_car_state
        LEFT JOIN c_avi_color ON c_avi_color.c_avi_color_id = o_avi_car.o_avi_car_color
        LEFT JOIN a_avi_sell_car ON (a_avi_sell_car.a_avi_sell_car_account_car_id = i_avi_account_car.i_avi_account_car_id AND a_avi_sell_car.a_avi_sell_car_status in (1,2))
        WHERE i_avi_account_car_id = '$id' AND i_avi_account_car_status=1 AND o_avi_account_status=1 group by i_avi_account_car_id";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $Inst[]=$row;
            }
        }
        $db->close();
        return $Inst;
    }
    function getColorCar($id)
    {
        $database=new Database;
        $db=$database->connect();
        $Inst = array();
        $query ="SELECT c_avi_color_name colorname FROM c_avi_color WHERE c_avi_color_id = '$id'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $Inst=$row["colorname"];
            }
        }
        $db->close();
        return $Inst;
    }
    function imagenesGenerales($id)
    {
        $database=new Database;
        $db=$database->connect();
        $Inst = array();
        $query ="SELECT a_avi_car_img_car, a_avi_car_img_id imagenId,a_avi_car_img_account_car_id  FROM a_avi_car_img WHERE a_avi_car_img_account_car_id = '$id' AND a_avi_car_img_type = 5";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $Inst[]=$row;
            }
        }
        $db->close();
        return $Inst;
    }
    function imagenPortada($id)
    {
        $database=new Database;
        $db=$database->connect();
        $Inst = array();
        $query ="SELECT a_avi_car_img_car, a_avi_car_img_id imagenId,a_avi_car_img_account_car_id  FROM a_avi_car_img WHERE a_avi_car_img_account_car_id = '$id' AND a_avi_car_img_type = 6";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $Inst[]=$row;
            }
        }
        $db->close();
        return $Inst;
    }
    function imagenPorCoche($id,$idImg)
    {
        $database=new Database;
        $db=$database->connect();
        $Inst = "";
        $query ="SELECT a_avi_car_img_car url FROM a_avi_car_img WHERE a_avi_car_img_account_car_id = '$id' AND a_avi_car_img_id = '$idImg'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $Inst=$row["url"];
            }
        }
        $db->close();
        return $Inst;
        unset($Inst);
    }
    function accountById($id)
    {
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $query = "SELECT o_avi_account_id,o_avi_account_user_id ,o_avi_account_name, o_avi_account_type_id,o_avi_account_verified FROM o_avi_account
        WHERE o_avi_account_id = '$id' AND o_avi_account_status = 1";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account=array("idAccount" => $row["o_avi_account_id"], "user" => $row["o_avi_account_user_id"],"nameAccount" => $row["o_avi_account_name"],"privacidad" => $row["o_avi_account_type_id"], "verified" => $row["o_avi_account_verified"]);
            }
        }
        $db->close();
        return $account;
        unset($account);
    }
    function accountByFather($father)
    {

        $database=new Database;
        $db=$database->connect();
        $account=array();
        $query="SELECT o_avi_account_id, o_avi_account_name, o_avi_account_type_id, a_avi_accountdetail_avatar_img,
            (SELECT count(f_avi_share_account_shared) FROM f_avi_share WHERE f_avi_share_account_shared=o_avi_account.o_avi_account_id) sharedGarage,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_account_id=o_avi_account.o_avi_account_id) likesGarage
            FROM o_avi_account LEFT JOIN a_avi_accountdetail ON  a_avi_accountdetail.a_avi_account_id = o_avi_account.o_avi_account_id WHERE o_avi_account_father = '$father' AND o_avi_account_status = '1'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account[]=array("idAccount" => $row["o_avi_account_id"], "nameAccount" => $row["o_avi_account_name"], "type" => $row["o_avi_account_type_id"], "avatar" => $row["a_avi_accountdetail_avatar_img"], "sharedGarage"=>$row["sharedGarage"], "likesGarage"=>$row["likesGarage"]);
            }
        }
        $db->close();
        return $account;
    }
    function secretlessByFather($father)
    {
        $database=new Database;
        $db=$database->connect();
        $account=array();
        $query="SELECT o_avi_account_name FROM o_avi_account WHERE o_avi_account_father = '$father' AND o_avi_account_type_id !=3 AND o_avi_account_status = '1'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account[]=array("nameAccount" => $row["o_avi_account_name"]);
            }
        }
        $db->close();
        return $account;
    }
    function confGarageExtras($idgarage, $description)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_accountdetail_id, a_avi_account_id, a_avi_accountdetail_description FROM a_avi_accountdetail WHERE a_avi_account_id='$idgarage'";
        $queryDB=$db->query($select);
        $count=0;
        $sql="";
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $sql = "UPDATE a_avi_accountdetail SET ";

                if($description!="" && $row["a_avi_accountdetail_description"] != $description)
                {
                    $count++; 
                    $sql.="a_avi_accountdetail_description='$description'";
                }
                $sql.= " WHERE a_avi_account_id='$idgarage'";
            }
        }
        else
        {
            $sql = "INSERT INTO a_avi_accountdetail (a_avi_accountdetail_description, a_avi_account_id) VALUES ('$description', $idgarage)";
            $count++;
        } 
        $ret=false;
        if($count>=1)
        {
            if($db->query($sql))
            {
                $ret=true;
            }
        }
        else
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function getGarageExtras($idgarage)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_accountdetail_id, a_avi_account_id, a_avi_accountdetail_description, a_avi_accountdetail_cover_img, a_avi_accountdetail_avatar_img, a_avi_accountdetail_street ,a_avi_accountdetail_zip_code, c_avi_account_use_name  FROM a_avi_accountdetail
            LEFT JOIN c_avi_account_use ON c_avi_account_use.c_avi_account_use_id=a_avi_accountdetail.a_avi_accountdetail_use_id
         WHERE a_avi_account_id='$idgarage'";
        $queryDB=$db->query($select);
        $count=0;
        $garageExtras=array();
        $sql="";
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $garageExtras = array("avatar"=> $row["a_avi_accountdetail_avatar_img"], "cover"=> $row["a_avi_accountdetail_cover_img"], "description"=>$row["a_avi_accountdetail_description"], "street"=>$row["a_avi_accountdetail_street"], "cp"=>$row["a_avi_accountdetail_zip_code"], "uso"=>$row["c_avi_account_use_name"], "idAccount"=>$row["a_avi_account_id"]);
            }
        }
        $db->close();
        return $garageExtras;
    }
    function confGarageInfo($idgarage,$street,$zip,$use,$phone,$cellphone)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_accountdetail_id, a_avi_account_id, a_avi_accountdetail_street, a_avi_accountdetail_zip_code, a_avi_accountdetail_use_id, a_avi_accountdetail_phone FROM a_avi_accountdetail WHERE a_avi_account_id='$idgarage'";
        $queryDB=$db->query($select);
        $count=0;
        $sql="";
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $sql = "UPDATE a_avi_accountdetail SET ";

                if($street!="")
                { 
                    $count++; 
                    $sql.="a_avi_accountdetail_street='$street'";
                }
                if($zip!="")
                {
                    if($count>0)
                    {
                        $sql.=", ";
                    }
                    $sql.="a_avi_accountdetail_zip_code='$zip'";
                    $count++;
                }
                if($use!="")
                {
                    if($count>0)
                    {
                        $sql.=", ";
                    }
                    $sql.="a_avi_accountdetail_use_id='$use'";
                    $count++;
                }
                if($phone[0]!="")
                {
                    if($count>0)
                    {
                        $sql.=", ";
                    }
                    $sql.="a_avi_accountdetail_phone='".$phone[0]."', a_avi_accountdetail_phone_code='".$phone[1]."'";
                    $count++;
                }
                if($cellphone[0]!="")
                {
                    if($count>0)
                    {
                        $sql.=", ";
                    }
                    $sql.="a_avi_accountdetail_cellphone='".$cellphone[0]."', a_avi_accountdetail_cellphone_code='".$cellphone[1]."', a_avi_accountdetail_cellphone_wa='".$cellphone[2]."'";
                    $count++;
                }
                $sql.= " WHERE a_avi_account_id='$idgarage'; ";
            }
        }
        $ret=false;
        if($count>=1)
        {
            if($db->query($sql))
            {
                $ret=true;
                $sql2 = "UPDATE o_avi_account SET o_avi_account_verified=0 WHERE o_avi_account_id = $idgarage";
                $db->query($sql2);
            }
        }
        $db->close();
        return $ret;
    }
    function changePrivacyId($privacyId,$idCuenta)
    {
        $database=new Database;
        $db=$database->connect();
        $ret=false;
        $sql="UPDATE o_avi_account SET o_avi_account_type_id=$privacyId WHERE o_avi_account_id=$idCuenta";
        if($db->query($sql)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function confGarageName($idgarage,$garageName)
    {
        $database=new Database;
        $db=$database->connect();
        $ret=false;
        if($garageName!="")
        {
            $sql="UPDATE o_avi_account SET o_avi_account_name='$garageName' WHERE o_avi_account_id='$idgarage'";
            if($db->query($sql))
            {
                $ret=true;
                $sql2 = "UPDATE o_avi_account SET o_avi_account_verified=0 WHERE o_avi_account_id = $idgarage";
                $db->query($sql2);
            }
        }
        $db->close();
        return $ret;
    }

    function updateGarageAliasCar($idCarGarage,$nuevoAlias,$idInstancia)
    {
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE i_avi_account_car SET i_avi_account_car_alias='$nuevoAlias' WHERE i_avi_account_car_id=$idCarGarage";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
            $sql = "UPDATE i_avi_account_car SET i_avi_account_car_verified = 0 WHERE i_avi_account_car_car_id = $idInstancia";
            $db->query($sql);
        }
        $db->close();
        return $resp;
    }

    function updateGarageStateCar($idCarGarage,$nuevoEstado,$idInstancia)
    {
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE i_avi_account_car SET i_avi_account_car_state='$nuevoEstado' WHERE i_avi_account_car_id=$idCarGarage";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
            $sql = "UPDATE i_avi_account_car SET i_avi_account_car_verified = 0 WHERE i_avi_account_car_car_id = $idInstancia";
            $db->query($sql);
        }
        $db->close();
        return $resp;
    }
    function getGarageInfo($idgarage)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_accountdetail_id, a_avi_account_id, o_avi_account_name, a_avi_accountdetail_street, a_avi_accountdetail_zip_code, a_avi_accountdetail_use_id, o_avi_account_type_id, a_avi_accountdetail_phone, a_avi_accountdetail_phone_code, a_avi_accountdetail_cellphone, a_avi_accountdetail_cellphone_code, a_avi_accountdetail_cellphone_wa
                FROM o_avi_account 
                LEFT JOIN a_avi_accountdetail ON o_avi_account.o_avi_account_id = a_avi_accountdetail.a_avi_account_id 
                WHERE o_avi_account_id='$idgarage'  AND o_avi_account_status=1";
        $queryDB=$db->query($select);
        $count=0;
        $garageExtras=array();
        $sql="";
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $garageExtras = array("calle"=> $row["a_avi_accountdetail_street"], "zip"=> $row["a_avi_accountdetail_zip_code"], "usoId"=>$row["a_avi_accountdetail_use_id"], "nombre"=>$row["o_avi_account_name"], "tipo"=>$row["o_avi_account_type_id"], "telefono" => $row["a_avi_accountdetail_phone"], "telefonocode" => $row["a_avi_accountdetail_phone_code"], "celular" => $row["a_avi_accountdetail_cellphone"], "celularcode" => $row["a_avi_accountdetail_cellphone_code"], "celularwa" => $row["a_avi_accountdetail_cellphone_wa"]);
            }
        }
        $db->close();
        return $garageExtras;
    }

    function deleteCarGarage($idCarGarage)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM i_avi_account_car WHERE i_avi_account_car_id=$idCarGarage";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    
    function getCarIdbyInstancia($id)
    {
        $database=new Database;
        $db=$database->connect();
        $Inst = array();
        $query ="SELECT i_avi_account_car_car_id  FROM i_avi_account_car
            WHERE i_avi_account_car_id = '$id'";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $Inst=$row["i_avi_account_car_car_id"];
            }
        }
        $db->close();
        return $Inst;
        unset($Inst);
    }

    function privacyInstanceCar($instancia, $privacidad)
    {
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE i_avi_account_car SET i_avi_account_car_privacy='$privacidad' WHERE i_avi_account_car_id='$instancia'";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    /**haccer clase de instancia de auto y heredar la clase del objeto auto**/
    function deleteCarObject($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM o_avi_car WHERE o_avi_car_id=$idCar";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function deleteGarageCarImages($idAccountCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_avi_car_img WHERE a_avi_car_img_account_car_id=$idAccountCar";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function deleteSellCar($idAccountCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM a_avi_sell_car WHERE a_avi_sell_car_account_car_id=$idAccountCar";
        $resp=false;
        if($result=$db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function getCarsEditableByUser($idUser,$following=true,$sold=true){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($idUser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $accountInst = array();
        $query ="
        SELECT i_avi_account_car_id, o_avi_car_version_id, c_avi_car_state, a_avi_car_img_car, c_avi_color_name, i_avi_account_car_status, a_avi_sell_car_status, a_avi_sell_detaill_price, a_avi_sell_car_currency, i_avi_account_car_alias alias, i_avi_account_car_account_id garage, o_avi_account_user_id, i_avi_account_car_privacy, o_avi_car_name_brand, o_avi_car_name_subbrand, o_avi_car_name_model, i_avi_account_car_verified verificado,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_car_id=i_avi_account_car.i_avi_account_car_id) likes,
            (SELECT count(f_avi_share_car_shared) FROM f_avi_share WHERE f_avi_share_car_shared=i_avi_account_car.i_avi_account_car_id) shared, OACA.o_avi_car_ad_sold vendido,
            IF(AAUA.a_avi_user_id=o_avi_account_user_id, 1, 0) propio, o_avi_account_name garageName, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name) garageOwner
            FROM a_avi_user_account AAUA
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id=AAUA.a_avi_account_id
        LEFT JOIN i_avi_account_car ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id 
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_car_id 
        LEFT JOIN a_avi_car_img ON a_avi_car_img.a_avi_car_img_account_car_id = i_avi_account_car.i_avi_account_car_id 
        LEFT JOIN c_avi_car_state ON c_avi_car_state.c_avi_car_state_id = i_avi_account_car.i_avi_account_car_state 
        LEFT JOIN a_avi_sell_car ON (a_avi_sell_car.a_avi_sell_car_account_car_id = i_avi_account_car.i_avi_account_car_id AND a_avi_sell_car.a_avi_sell_car_status=1) 
        LEFT JOIN c_avi_color ON c_avi_color.c_avi_color_id = o_avi_car.o_avi_car_color 
        LEFT JOIN o_avi_car_ad OACA ON i_avi_account_car.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
        LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=o_avi_account.o_avi_account_user_id
        WHERE AAUA.a_avi_user_id = '$idUser'  AND o_avi_account_status=1 AND o_avi_account_user_id NOT IN ($notin)";
        if(!$following){

            $query.=" AND i_avi_account_car_privacy = 2";
        }
        if(!$sold){

            $query.=" AND (o_avi_car_ad_sold = 0 OR o_avi_car_ad_sold is null)";
        }
        $query.=" group by i_avi_account_car_id ORDER BY i_avi_account_car_alias";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $accountInst[]=$row;
            }
        }
        //echo $query;
        $db->close();
        return $accountInst;
    }
        function getCarsNoVerifiedOrPendingEditableByUser($idUser,$following=true,$sold=true,$status){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($idUser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $accountInst = array();
        $query ="
        SELECT i_avi_account_car_id, o_avi_car_version_id, c_avi_car_state, a_avi_car_img_car, c_avi_color_name, i_avi_account_car_status, a_avi_sell_car_status, a_avi_sell_detaill_price, a_avi_sell_car_currency, i_avi_account_car_alias alias, i_avi_account_car_account_id garage, o_avi_account_user_id, i_avi_account_car_privacy, o_avi_car_name_brand, o_avi_car_name_subbrand, o_avi_car_name_model, i_avi_account_car_verified verificado,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_car_id=i_avi_account_car.i_avi_account_car_id) likes,
            (SELECT count(f_avi_share_car_shared) FROM f_avi_share WHERE f_avi_share_car_shared=i_avi_account_car.i_avi_account_car_id) shared, OACA.o_avi_car_ad_sold vendido,
            IF(AAUA.a_avi_user_id=o_avi_account_user_id, 1, 0) propio, o_avi_account_name garageName, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name) garageOwner
            FROM a_avi_user_account AAUA
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id=AAUA.a_avi_account_id
        LEFT JOIN i_avi_account_car ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id 
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_car_id 
        LEFT JOIN a_avi_car_img ON a_avi_car_img.a_avi_car_img_account_car_id = i_avi_account_car.i_avi_account_car_id 
        LEFT JOIN c_avi_car_state ON c_avi_car_state.c_avi_car_state_id = i_avi_account_car.i_avi_account_car_state 
        LEFT JOIN a_avi_sell_car ON (a_avi_sell_car.a_avi_sell_car_account_car_id = i_avi_account_car.i_avi_account_car_id AND a_avi_sell_car.a_avi_sell_car_status=1) 
        LEFT JOIN c_avi_color ON c_avi_color.c_avi_color_id = o_avi_car.o_avi_car_color 
        LEFT JOIN o_avi_car_ad OACA ON i_avi_account_car.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
        LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=o_avi_account.o_avi_account_user_id
        WHERE AAUA.a_avi_user_id = '$idUser'  AND o_avi_account_status=1 AND i_avi_account_car_verified = $status AND o_avi_account_user_id NOT IN ($notin)";
        if(!$following){

            $query.=" AND i_avi_account_car_privacy = 2";
        }
        if(!$sold){

            $query.=" AND (o_avi_car_ad_sold = 0 OR o_avi_car_ad_sold is null)";
        }
        $query.=" group by i_avi_account_car_id ORDER BY i_avi_account_car_alias";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $accountInst[]=$row;
            }
        }
        //echo $query;
        $db->close();
        return $accountInst;
    }
    function getProfile($idUser,$status){
        $database = new Database();
        $db=$database->connect();
        $userInst = array();
        $query = "SELECT o_avi_user_id, o_avi_user_username,o_avi_user_status, o_avi_user_verified,o_avi_userdetail_name, o_avi_userdetail_last_name FROM o_avi_user,o_avi_userdetail WHERE o_avi_user_verified = $status AND o_avi_user_id = $idUser AND o_avi_userdetail_id_user = $idUser";
        $queryDB = $db->query($query);
        if($queryDB->num_rows>0){
            while($row=$queryDB->fetch_assoc()){
                $userInst[]=$row;
            }
        }
        $db->close();
        return $userInst;
    }
    function accountsByUser($idUser,$following=true,$sold=true)
    {
        $database=new Database;
        $db=$database->connect();
        $accountInst = array();
        $query ="
        SELECT i_avi_account_car_id, o_avi_car_version_id, c_avi_car_state, a_avi_car_img_car, c_avi_color_name, i_avi_account_car_status, a_avi_sell_car_status, a_avi_sell_detaill_price, a_avi_sell_car_currency, i_avi_account_car_alias alias, i_avi_account_car_account_id garage, o_avi_account_user_id, i_avi_account_car_privacy, o_avi_car_name_brand, o_avi_car_name_subbrand, o_avi_car_name_model, i_avi_account_car_verified verificado,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_car_id=i_avi_account_car.i_avi_account_car_id) likes,
            (SELECT count(f_avi_share_car_shared) FROM f_avi_share WHERE f_avi_share_car_shared=i_avi_account_car.i_avi_account_car_id) shared, OACA.o_avi_car_ad_sold vendido
            FROM i_avi_account_car
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_car_id 
        LEFT JOIN a_avi_car_img ON a_avi_car_img.a_avi_car_img_account_car_id = i_avi_account_car.i_avi_account_car_id 
        LEFT JOIN c_avi_car_state ON c_avi_car_state.c_avi_car_state_id = i_avi_account_car.i_avi_account_car_state 
        LEFT JOIN a_avi_sell_car ON (a_avi_sell_car.a_avi_sell_car_account_car_id = i_avi_account_car.i_avi_account_car_id AND a_avi_sell_car.a_avi_sell_car_status=1) 
        LEFT JOIN c_avi_color ON c_avi_color.c_avi_color_id = o_avi_car.o_avi_car_color 
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id 
        LEFT JOIN o_avi_car_ad OACA ON i_avi_account_car.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
        WHERE o_avi_account_user_id = '$idUser'  AND o_avi_account_status=1";
        if(!$following){

            $query.=" AND i_avi_account_car_privacy = 2";
        }
        if(!$sold){

            $query.=" AND (o_avi_car_ad_sold = 0 OR o_avi_car_ad_sold is null)";
        }
        $query.=" group by i_avi_account_car_id ORDER BY i_avi_account_car_alias";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $accountInst[]=$row;
            }
        }
        //echo $query;
        $db->close();
        return $accountInst;
        unset($accountInst);
    }
    function accountsByUserByTen($idUser,$t=0,$garage=null,$following=true,$sold=true)
    {
        $database=new Database;
        $db=$database->connect();
        $accountInst = array();
        $query ="
        SELECT i_avi_account_car_id, o_avi_car_version_id, c_avi_car_state, a_avi_car_img_car, c_avi_color_name, i_avi_account_car_status, a_avi_sell_car_status, a_avi_sell_detaill_price, a_avi_sell_car_currency, i_avi_account_car_alias alias, i_avi_account_car_account_id garage, o_avi_account_user_id, i_avi_account_car_privacy, o_avi_car_name_brand, o_avi_car_name_subbrand, o_avi_car_name_model, i_avi_account_car_verified verificado,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_car_id=i_avi_account_car.i_avi_account_car_id) likes,
            (SELECT count(f_avi_share_car_shared) FROM f_avi_share WHERE f_avi_share_car_shared=i_avi_account_car.i_avi_account_car_id) shared, OACA.o_avi_car_ad_sold vendido
            FROM i_avi_account_car
        LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_car_id 
        LEFT JOIN a_avi_car_img ON a_avi_car_img.a_avi_car_img_account_car_id = i_avi_account_car.i_avi_account_car_id 
        LEFT JOIN c_avi_car_state ON c_avi_car_state.c_avi_car_state_id = i_avi_account_car.i_avi_account_car_state 
        LEFT JOIN a_avi_sell_car ON (a_avi_sell_car.a_avi_sell_car_account_car_id = i_avi_account_car.i_avi_account_car_id AND a_avi_sell_car.a_avi_sell_car_status=1) 
        LEFT JOIN c_avi_color ON c_avi_color.c_avi_color_id = o_avi_car.o_avi_car_color 
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id 
        LEFT JOIN o_avi_car_ad OACA ON i_avi_account_car.i_avi_account_car_id=OACA.o_avi_car_ad_car_id
        WHERE o_avi_account_user_id = '$idUser'  AND o_avi_account_status=1";
        //echo $query;
        if($garage){
            $query.=" AND i_avi_account_car_account_id = $garage";
        }
        if(!$following){

            $query.=" AND i_avi_account_car_privacy = 2";
        }
        if(!$sold){

            $query.=" AND (o_avi_car_ad_sold = 0 OR o_avi_car_ad_sold is null)";
        }
        $query.=" group by i_avi_account_car_id ORDER BY i_avi_account_car_alias LIMIT $t, 10";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $accountInst[]=$row;
            }
        }
        //echo $query;
        $db->close();
        return $accountInst;
        unset($accountInst);
    }
    function secretlessByUser($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $accountInst = array();
        $query ="
        SELECT i_avi_account_car_id FROM i_avi_account_car
        LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id 
        WHERE o_avi_account_user_id = '$idUser'  AND o_avi_account_status=1 AND i_avi_account_car_privacy !=3 group by i_avi_account_car_id ";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $accountInst[]=$row;
            }
        }
        $db->close();
        return $accountInst;
        unset($accountInst);
    }
    /**Termina --- haccer clase de instancia de auto y heredar la clase del objeto auto**/
    function aUserAccount($idUser, $idAccount, $limit=0)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="INSERT INTO a_avi_user_account(a_avi_user_id, a_avi_account_id,registered_date, a_avi_user_account_level) VALUES ($idUser, $idAccount,NOW(),$limit)";
        $resp=false;
        if($db -> query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function getTypeColaborators(){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT c_avi_colaborator_level_id id, c_avi_colaborator_level_description description FROM c_avi_colaborator_level WHERE c_avi_colaborator_level_id>0 AND c_avi_colaborator_level_status=1";
        $niveles=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $niveles[]=$row;
                }
            }
        }
        $db->close();
        return $niveles;
    }
    function getAUserAccount($idUser, $idCuenta, $limit=0)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="
        SELECT a_avi_user_id 
        FROM a_avi_user_account
        WHERE a_avi_user_id = '$idUser' AND a_avi_account_id='$idCuenta' AND status=1 AND a_avi_user_account_level<=$limit";
        $queryDB = $db -> query($query);
        $resp = false;
        if($queryDB->num_rows>0)
        {
            $resp = true;
        }
        else
        {
            $resp = false;
        }
        $db->close();
        return $resp;
    }
    function getAUserCollabByCar($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="
        SELECT a_avi_user_id FROM a_avi_user_account 
        LEFT JOIN i_avi_account_car ON a_avi_user_account.a_avi_account_id = i_avi_account_car.i_avi_account_car_account_id
        WHERE i_avi_account_car_id = '$idCar' AND a_avi_user_account_level>=1 AND  a_avi_user_account_level<=2";
        $queryDB = $db -> query($query);
        $resp = false;
        if($queryDB->num_rows>0)
        {
            $resp = true;
        }
        else
        {
            $resp = false;
        }
        $db->close();
        return $resp;
    }
    function getAllFathers($cuenta){
        $database=new Database;
        $db=$database->connect();
        $arrayPadres=array();
        while($padre=$this->getFather($cuenta,$db))
        {
            $cuenta=$padre["id"];
            $arrayPadres[]=$padre;
        }
        $db->close();
        return $arrayPadres;
    }
    function getFather($cuenta,$conexion){
        $query="SELECT OAA.o_avi_account_father padreID, OAA2.o_avi_account_name padre, AAA.a_avi_accountdetail_avatar_img img
                FROM o_avi_account OAA 
                LEFT JOIN o_avi_account OAA2 ON OAA2.o_avi_account_id=OAA.o_avi_account_father
                LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAA2.o_avi_account_id
                WHERE OAA.o_avi_account_id=$cuenta  AND OAA.o_avi_account_status=1";
        $queryDB=$conexion->query($query);
        $padre=false;
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $padre=($row["padreID"]!="") ? array("id"=>$row["padreID"],"nombre"=>$row["padre"],"img"=>$row["img"]) : false;
                
            }
        }
        return $padre;
    }
    function getAllChildren($cuenta){
        $database=new Database;
        $db=$database->connect();
        $arrayHijos=array();
        while($hijo=$this->getChildren($cuenta,$db))
        {
            $cuenta=$hijo["id"];
            $arrayHijos[]=$hijo;
        }
        $db->close();
        return $arrayHijos;
    }
    function getChildren($cuenta,$conexion){
        $query="SELECT OAA.o_avi_account_id hijoID, OAA.o_avi_account_name hijo, a_avi_accountdetail_avatar_img imagen
                FROM o_avi_account OAA 
                LEFT JOIN a_avi_accountdetail ON a_avi_accountdetail.a_avi_account_id = OAA.o_avi_account_id
                WHERE OAA.o_avi_account_father=$cuenta AND o_avi_account_status=1";
        $queryDB=$conexion->query($query);
        $hijo=false;
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $hijo=($row["hijoID"]!="") ? array("id"=>$row["hijoID"],"nombre"=>$row["hijo"], "imgAvatar" => $row["imagen"]) : false;
                
            }
        }
        return $hijo;
    }
    function tmpCoverGarage($imgName, $id, $idGarage, $type)
    {
        $database=new Database;
        $db=$database->connect();   
        $select ="SELECT tmp_avi_car_img_car, tmp_avi_user_id, tmp_avi_car_img_account_car_id, tmp_avi_car_img_type 
            FROM tmp_avi_car_img 
            WHERE tmp_avi_user_id='$id' AND tmp_avi_car_img_account_car_id= '$idGarage'; ";
        $queryDB=$db->query($select);
        $count=0;
        if($queryDB->num_rows>0)
        { 
            $sql = "UPDATE tmp_avi_car_img SET tmp_avi_car_img_car ='$imgName' WHERE tmp_avi_user_id='$id' AND tmp_avi_car_img_type = $type AND tmp_avi_car_img_account_car_id= '$idGarage'";   
        }
        else
        {
            $sql = "INSERT INTO tmp_avi_car_img (tmp_avi_car_img_car, tmp_avi_user_id, tmp_avi_car_img_account_car_id, tmp_avi_car_img_type) 
                VALUES ('$imgName','$id','$idGarage',$type); ";
        }
        $resp=false;
        if($db -> query($sql))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function tmpGarageDelete($id,$idGarage,$type)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "DELETE FROM tmp_avi_car_img WHERE  tmp_avi_user_id = '$id' AND tmp_avi_car_img_account_car_id='$idGarage' AND tmp_avi_car_img_type = '$type'; ";
        $ret=false;             
        if($db->query($sql))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function savePhotoGarage($idGarage, $rutaAvatar, $rutaCover)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_account_id, a_avi_accountdetail_cover_img, a_avi_accountdetail_avatar_img FROM a_avi_accountdetail WHERE a_avi_account_id='$idGarage'";
        $queryDB=$db->query($select);
        $count=0;
        $sql="";
        if($queryDB->num_rows>0)
        {
            if($rutaAvatar == NULL)
            {
                $sql = "UPDATE a_avi_accountdetail SET a_avi_accountdetail_cover_img='$rutaCover' WHERE a_avi_account_id='$idGarage'; ";
            }   
            elseif ($rutaCover == NULL) 
            {
                $sql = "UPDATE a_avi_accountdetail SET a_avi_accountdetail_avatar_img='$rutaAvatar' WHERE a_avi_account_id='$idGarage'; ";
            }
        }
        else
        {
            if($rutaAvatar == NULL)
            {
                $sql = "INSERT INTO a_avi_accountdetail (a_avi_accountdetail_cover_img, a_avi_account_id) VALUES ('$rutaCover', '$idGarage'); ";
            }   
            elseif ($rutaCover == NULL) 
            {
                $sql = "INSERT INTO a_avi_accountdetail (a_avi_accountdetail_avatar_img, a_avi_account_id) VALUES ('$rutaAvatar', '$idGarage'); ";
            }
        }
        $ret=false;              
        if($db->query($sql))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function garageNameAndImageByUsr($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $account = array();
        $query = "SELECT o_avi_account_id, o_avi_account_name, a_avi_accountdetail_avatar_img FROM o_avi_account
        LEFT JOIN a_avi_accountdetail ON a_avi_accountdetail.a_avi_account_id = o_avi_account.o_avi_account_id
        WHERE o_avi_account_user_id = '$idUser' AND o_avi_account_status = '1' order by o_avi_account_created_date;";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $account[$row["o_avi_account_id"]]=array
                    (
                        "nameAccount" => $row["o_avi_account_name"],
                        "imgAvatar" => $row["a_avi_accountdetail_avatar_img"]
                    );
            }
        }
        $db->close();
        return $account;
        unset($account);
    }
    function deleteCarImg($idCar,$idImg)
    {
        $database=new Database;
        $db=$database->connect();
        $ret= false;
        $sql = "DELETE FROM a_avi_car_img WHERE a_avi_car_img_account_car_id = '$idCar' AND  a_avi_car_img_id='$idImg'";
        if($db->query($sql)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function deleteAllCarImg($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $ret= false;
        $sql = "DELETE FROM tmp_avi_car_img WHERE tmp_avi_car_img_account_car_id = '$idCar' ";
        if($db->query($sql)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function selectAllImgTmp($user, $idGarage){
        $database=new Database;
        $db=$database->connect();
        $sql= "SELECT tmp_avi_car_img_car FROM tmp_avi_car_img WHERE tmp_avi_user_id = '$user' AND tmp_avi_car_img_account_car_id = '$idGarage' ";
        $queryDB = $db->query($sql);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $estado[] =$row["tmp_avi_car_img_car"];
            }
        }
        $db->close();
        return $estado;;
    }

    function getLastCoverCar($imgUrl,$idCar, $imgType){
        $database=new Database;
        $db=$database->connect();
        $query ="SELECT a_avi_car_img_car url, a_avi_car_img_account_car_id idCar, a_avi_car_img_type type FROM a_avi_car_img WHERE a_avi_car_img_account_car_id='$idCar' AND a_avi_car_img_type = '$imgType'";
        $last=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $last=$row;
                }
            }
        }
        $db->close();
        return $last;
    }
    function addCarImg($imgUrl,$idCar, $imgType=5)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_car_img_car, a_avi_car_img_account_car_id, a_avi_car_img_type FROM a_avi_car_img WHERE a_avi_car_img_account_car_id='$idCar' AND a_avi_car_img_type = '$imgType'";
        $queryDB=$db->query($select);
        $count=0;
        $sql="";
        if($imgType == 6)
        {
            if($queryDB->num_rows>0)
            {
                while($row=$queryDB->fetch_assoc())
                {
                    $sql = "UPDATE a_avi_car_img SET ";

                    if($row["a_avi_car_img_type"] == 6 && $row["a_avi_car_img_car"] != $imgUrl)
                    {
                        $count++; 
                        $sql.="a_avi_car_img_car = '$imgUrl'";
                    }
                    $sql.= " WHERE a_avi_car_img_account_car_id='$idCar' AND a_avi_car_img_type = '$imgType';";
             
                }
            }
            else
            {
                $sql = "INSERT INTO a_avi_car_img(a_avi_car_img_car, a_avi_car_img_account_car_id,a_avi_car_img_type) VALUES ('$imgUrl','$idCar','$imgType')";
                $count++;
            }
        }
        else
        {
            $sql = "INSERT INTO a_avi_car_img(a_avi_car_img_car, a_avi_car_img_account_car_id,a_avi_car_img_type) VALUES ('$imgUrl','$idCar','$imgType')";
            $count++;
        } 
        $ret=false;
        if($count>=1)
        {
            if($db->query($sql))
            {
                $ret=true;
            }
        }
        else
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function imagenTmp($idgarage=null, $ruta, $tipo,$iduser)
    {
        //1 es para carro, 2 para publicacion
        $database=new Database;
        $db=$database->connect();
        $sql = "INSERT INTO tmp_avi_car_img (tmp_avi_car_img_car, tmp_avi_car_img_account_car_id, tmp_avi_car_img_type, tmp_avi_user_id) VALUES('$ruta', '$idgarage', '$tipo', '$iduser')";
        if($db->query($sql)){
            $resp=$db->insert_id;
        }
        $db->close();
        return $resp;
    }
    function getGaragesForSearch($search=null,$searcher=null,$time=0)
    {
        $users=array();
        $coder=new Coder;
        $search=strtolower($search);
        if(!$search||$search==="")
        {
            throw new Exception("Empty search", 1);   
        }
        $inicio=10*$time;
        $notAllowedUsers=$this->notAccesibleUsers($searcher);
        $notin="0";
        foreach ($notAllowedUsers as $u => $user) {
            $notin.=",$user";
        }
        $database=new Database;
        $db=$database->connect();
        $query="SELECT AAAD.a_avi_accountdetail_avatar_img img, 
                    OAA.o_avi_account_name nombre,
                    OAU.o_avi_user_id a_to, 
                    OAA.o_avi_account_id g_to,
                    OAA.o_avi_account_type_id privacidad,
                    IF(AAUP.a_avi_user_perfil_privacy IN (1,2) AND (OAA.o_avi_account_type_id=1 OR AUFU.a_user_follower_user_id=$searcher OR OAU.o_avi_user_id=$searcher), OAU.o_avi_user_username, NULL)  alias, 
                    IF(AAUP.a_avi_user_perfil_privacy=2 AND (OAA.o_avi_account_type_id=2 OR AUFU.a_user_follower_user_id=$searcher OR OAU.o_avi_user_id=$searcher), OAUD.o_avi_userdetail_name, NULL)  name, 
                    IF(AAUP.a_avi_user_perfil_privacy=2 AND (OAA.o_avi_account_type_id=2 OR AUFU.a_user_follower_user_id=$searcher OR OAU.o_avi_user_id=$searcher), OAUD.o_avi_userdetail_last_name, NULL) last_name,
                    IF(OAU.o_avi_user_id=$searcher, 1, 0) owner,
                    IF(AUFU.a_user_follower_user_id IS NULL, 0, 1) followOwner,
                    IF(AUFA.a_user_follower_acc_user_id IS NULL, 0, 1) followGarage,
                    OAA.o_avi_account_verified verifiedGarage,
                    OAU.o_avi_user_verified verifiedUser
                FROM o_avi_user OAU 
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id 
                LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
                LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_user_id=OAU.o_avi_user_id
                LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
                LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_following_user_id=OAU.o_avi_user_id AND AUFU.a_user_follower_user_id=$searcher
                LEFT JOIN a_user_follow_account AUFA ON AUFA.a_user_following_account_id=OAA.o_avi_account_id AND AUFA.a_user_follower_acc_user_id=$searcher
                WHERE 
                (OAU.o_avi_user_status=1 OR OAU.o_avi_user_id=$searcher) AND 
                OAA.o_avi_account_status=1 AND 
                (
                    OAA.o_avi_account_type_id IN (1,2) 
                    OR (OAA.o_avi_account_type_id=3 AND OAA.o_avi_account_id IN (SELECT a_user_following_account_id FROM a_user_follow_account WHERE a_user_follower_acc_user_id = $searcher))
                    OR $searcher=OAA.o_avi_account_user_id
                )
                AND 
                (
                    (LOWER(OAU.o_avi_user_username) LIKE '%$search%' AND AAUP.a_avi_user_perfil_privacy<>3)
                    OR (LOWER(OAUD.o_avi_userdetail_name) LIKE '%$search%' AND AAUP.a_avi_user_perfil_privacy<>3)
                    OR (LOWER(OAUD.o_avi_userdetail_last_name) LIKE '%$search%' AND AAUP.a_avi_user_perfil_privacy<>3)
                    OR LOWER(OAA.o_avi_account_name) LIKE '%$search%'
                )
                AND OAU.o_avi_user_id NOT IN ($notin)
                ORDER BY owner DESC, followGarage DESC, followOwner DESC, verifiedGarage DESC, verifiedUser DESC, nombre
                LIMIT $inicio, 10";
        //echo $query;
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                    $coder -> encode($row["a_to"]);
                    $row["a_to"] = $coder-> encoded;
                    $coder -> encode($row["g_to"]);
                    $row["g_to"] = $coder-> encoded;
                    $users[]=$row;
                }
            }
        }
        $db->close();
        return $users;
    }
    function getInfoGarage($cuenta){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT OAA.o_avi_account_id padreID, OAA.o_avi_account_name padre, AAA.a_avi_accountdetail_avatar_img img , OAU.o_avi_user_id owner
                FROM o_avi_account OAA 
                LEFT JOIN a_avi_accountdetail AAA ON AAA.a_avi_account_id=OAA.o_avi_account_id
                LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAA.o_avi_account_user_id
                WHERE OAA.o_avi_account_id=$cuenta AND o_avi_account_status=1";
        $queryDB=$db->query($query);
        $padre=false;
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                 $padre=($row["padreID"]!="") ? array("id"=>$row["padreID"],"nombre"=>$row["padre"],"img"=>$row["img"],"owner"=>$row["owner"]) : false;
                
            }
        }
        return $padre;
    }
    function UserAccessToGarage($user,$garage){
        $database=new Database;
        $db=$database->connect();
        $hasAccess=false;
        $query="SELECT o_avi_account_type_id privacidad, o_avi_account_user_id user FROM o_avi_account WHERE o_avi_account_id=$garage";
        $dataGarage=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $dataGarage=$row;
                }
            }
        }
        if(!empty($dataGarage)){
            if($dataGarage["user"]==$user){
                $hasAccess=true;
            }
            elseif($dataGarage["privacidad"]==2){
                $hasAccess=true;
            }
            else{
                $query="SELECT a_user_follower_acc_user_id FROM a_user_follow_account WHERE a_user_follower_acc_user_id=$user AND a_user_following_account_id=$garage";
                    if($data=$db->query($query)){
                        if($data->num_rows>0){
                            $hasAccess=true;
                        }
                    }
            }
        }
        $db->close();
        return $hasAccess;
    }
    function getPrivacyGarage($garage){

        $database=new Database;
        $db=$database->connect();
        $query="SELECT o_avi_account_type_id privacidad FROM o_avi_account WHERE o_avi_account_id=$garage";
        $privacy=0;
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                   $privacy=$row["privacidad"];
                }
            }
        }
        //echo $query;
        $db->close();
        return $privacy;
    }
    function countGaragesPerUser($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="SELECT count(o_avi_account_name) numero FROM o_avi_account WHERE o_avi_account_user_id = '$idUser' AND o_avi_account_status=1";
        $dataGarage=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $dataGarage=$row;
                }
            }
        }
        $db->close();
        return $dataGarage;
    }
    function countCarsPerUser($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="SELECT count(i_avi_account_car_account_id) numero FROM i_avi_account_car
            LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id
            WHERE o_avi_account_user_id = '$idUser' AND i_avi_account_car_status=1";
        $dataGarage=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $dataGarage=$row;
                }
            }
        }
        $db->close();
        return $dataGarage;
    }
    function getPrivacyByCar($idCar)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="SELECT 
            i_avi_account_car_privacy privacidad, 
            i_avi_account_car_alias nombre, 
            o_avi_account_user_id dueno, 
            i_avi_account_car_id carId,
            i_avi_account_car_account_id  garage,
            a_avi_sell_detaill_price price,
            a_avi_sell_car_status status_sell,
            a_avi_sell_car_currency currency,
            o_avi_car_version_id version, 
            o_avi_car_brand_id brand, 
            o_avi_car_subbrand_id subbrand, 
            o_avi_car_model_id model,
            o_avi_car_name_brand nombreMarca,
            o_avi_car_name_subbrand nombreSubmarca,
            o_avi_car_name_model nombreModelo,
            o_avi_car_name_version nombreVersion 
            FROM i_avi_account_car
            LEFT JOIN o_avi_account ON o_avi_account.o_avi_account_id = i_avi_account_car.i_avi_account_car_account_id
            LEFT JOIN o_avi_car ON o_avi_car.o_avi_car_id = i_avi_account_car.i_avi_account_car_car_id
            LEFT JOIN a_avi_sell_car ON a_avi_sell_car.a_avi_sell_car_account_car_id = i_avi_account_car.i_avi_account_car_id
            WHERE i_avi_account_car_id = '$idCar'";
        //echo $query;
        $privacidad=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $privacidad=$row;
                }
            }
        }
        $db->close();
        return $privacidad;
    }
    function getInfoCar($id)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="SELECT i_avi_account_car_id car , i_avi_account_car_account_id  garage FROM i_avi_account_car WHERE i_avi_account_car_id = '$id'";
        $carInfo=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $carInfo=$row;
                 }
            }
        }
        $db->close();
        return $carInfo;
    }
    function getAccountUses(){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT c_avi_account_use_id id, c_avi_account_use_name uso FROM c_avi_account_use WHERE c_avi_account_use_status=1";
        $usos=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $usos[$row["id"]]=$row["uso"];
                 }
            }
        }
        $db->close();
        return $usos;
    }
    function getColaborators($garage){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT OAU.o_avi_user_username username, OAUD.o_avi_userdetail_name name, OAUD.o_avi_userdetail_last_name last_name, AAUP.a_avi_user_perfil_avatar avatar, OAU.o_avi_user_id id_user, AAUP.a_avi_user_perfil_privacy privacidad, AAUA.a_avi_user_account_level nivelid, CACL.c_avi_colaborator_level_description nivel
                FROM a_avi_user_account AAUA 
                LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=AAUA.a_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
                LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
                LEFT JOIN c_avi_colaborator_level CACL ON CACL.c_avi_colaborator_level_id=AAUA.a_avi_user_account_level
                WHERE AAUA.a_avi_account_id=$garage AND AAUA.status=1";
        $colaboradores=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $colaboradores[$row["id_user"]]=$row;
                }
            }
        }
        $db->close();
        return $colaboradores;
    }
    function getPotentialColaborators($text,$garage,$user){
        $database=new Database;
        $db=$database->connect();
        $actuales=$this->getColaborators($garage);
        $idsActuales=array_keys($actuales);
        $notIn="0";
        foreach ($idsActuales as $a => $actual) {
            $notIn.=",$actual";
        }

        $notAllowedUsers=$this->notAccesibleUsers($user);
        foreach ($notAllowedUsers as $u => $us) {
            $notIn.=",$us";
        }
        $query="SELECT OAU.o_avi_user_id iduser, 
            OAU.o_avi_user_username username, 
            OAUD.o_avi_userdetail_name name, 
            OAUD.o_avi_userdetail_last_name lastname, 
            IF(AUFU.a_user_follow_id IS NULL, 0,1) sigo, 
            IF(AUFUR.a_user_follow_id IS NULL, 0, 1) mesigue, 
            IF(AUFA.a_user_follow_account_id IS NULL, 0, 1) siguegarage, 
            OAU.o_avi_user_verified verificado, 
            AAUP.a_avi_user_perfil_avatar img
            FROM o_avi_user OAU
            LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
            LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
            LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_follower_user_id=$user AND AUFU.a_user_following_user_id=OAU.o_avi_user_id AND AUFU.a_user_follow_acepted=1
            LEFT JOIN a_user_follow_user AUFUR ON AUFUR.a_user_follower_user_id=OAU.o_avi_user_id AND AUFUR.a_user_following_user_id=$user AND AUFUR.a_user_follow_acepted=1
            LEFT JOIN a_user_follow_account AUFA ON AUFA.a_user_follower_acc_user_id=OAU.o_avi_user_id AND AUFA.a_user_following_account_id=$garage AND AUFA.a_user_follow_acepted=1
            WHERE (OAU.o_avi_user_username LIKE '$text%' OR CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name) LIKE '$text%' OR OAUD.o_avi_userdetail_last_name LIKE '$text%') 
            AND OAU.o_avi_user_id <> $user 
            AND (AAUP.a_avi_user_perfil_privacy <> 3 OR AUFU.a_user_follow_id IS NOT NULL) 
            AND AUFA.a_user_follow_acepted = 1
            AND OAU.o_avi_user_id NOT IN ($notIn) AND OAU.o_avi_user_status = 1
            ORDER BY sigo DESC, siguegarage DESC, mesigue DESC, verificado DESC, username, name, lastname";
        $potenciales=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while($row=$data->fetch_assoc()){
                    $potenciales[]=$row;
                }
            }
        }
        $db->close();
        return $potenciales;
    }
    function addColaborator($user,$garage,$nivel=0){
        if(!$this->getAUserAccount($user,$garage,3)){
            return $this->aUserAccount($user,$garage,$nivel);
        }
        return false;
    }
    function deleteAUserAccount($user,$garage){
        $database=new Database;
        $db=$database->connect();
        $query ="UPDATE a_avi_user_account SET status=0 WHERE a_avi_user_id = '$user' AND a_avi_account_id='$garage' AND status=1";
        $resp=false;
        if($db -> query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function editAUserAccount($user,$garage,$level){
        $database=new Database;
        $db=$database->connect();
        $query ="UPDATE a_avi_user_account SET a_avi_user_account_level=$level WHERE a_avi_user_id = '$user' AND a_avi_account_id='$garage' AND status=1";
        //echo $query;
        $resp=false;
        if($db -> query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function deleteColaborator($user,$garage){
        if($this->getAUserAccount($user,$garage,3)){
            return $this->deleteAUserAccount($user,$garage);
        }
        return false;
        
    }
    function editLevelColaborator($user,$garage,$level){
        if($this->getAUserAccount($user,$garage,3)){
            return $this->editAUserAccount($user,$garage,$level);
        }
        return false;
    }
    function colaboratingGarage($user){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $query="SELECT OAA.o_avi_account_id idAccount, OAA.o_avi_account_name nameGarage, OAU.o_avi_user_username ownerUser, OAU.o_avi_user_id owner, OAUD.o_avi_userdetail_name ownerName, OAUD.o_avi_userdetail_last_name ownerLastName, AAAD.a_avi_accountdetail_avatar_img avatarImg, AAUA.a_avi_user_account_level nivel
            FROM a_avi_user_account AAUA
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=AAUA.a_avi_account_id
            LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAA.o_avi_account_user_id
            LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
            LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
            WHERE AAUA.a_avi_user_id=$user AND AAUA.a_avi_user_id<>OAU.o_avi_user_id AND AAUA.status=1 AND OAA.o_avi_account_status=1 AND OAU.o_avi_user_id NOT IN ($notin)";
        $garages=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $garages[]=$row;
                }
            }
        }
        $db->close();
        return $garages;
    }
    function TotalColaboratingGarage($user){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $query="SELECT count(OAA.o_avi_account_id) cuantos
            FROM a_avi_user_account AAUA
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=AAUA.a_avi_account_id
            LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAA.o_avi_account_user_id
            WHERE AAUA.a_avi_user_id=$user AND AAUA.a_avi_user_id<>OAU.o_avi_user_id AND AAUA.status=1 AND OAA.o_avi_account_status=1 AND OAU.o_avi_user_id NOT IN ($notin)";
        $total=0;
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $total=$row["cuantos"];
                }
            }
        }
        $db->close();
        return $total;
    }

    function colaboratingGarageByTen($user,$k=0,$colaborating=true){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $where="";
        if(!$colaborating){
            $where=" AND AAUA.a_avi_user_account_level = 0 ";
        }
        $query="SELECT OAA.o_avi_account_id idAccount, OAA.o_avi_account_name nameAccount, OAU.o_avi_user_username ownerUser, OAU.o_avi_user_id userOwner, OAUD.o_avi_userdetail_name ownerName, OAUD.o_avi_userdetail_last_name ownerLastName, AAAD.a_avi_accountdetail_avatar_img avatar, AAUA.a_avi_user_account_level nivel, o_avi_account_verified verified,
            (SELECT count(f_avi_user_like_id) FROM f_avi_user_like WHERE f_avi_user_account_id=OAA.o_avi_account_id) likes,
            (SELECT count(f_avi_share_account_shared) FROM f_avi_share WHERE f_avi_share_account_shared=OAA.o_avi_account_id) shared
            FROM a_avi_user_account AAUA
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=AAUA.a_avi_account_id
            LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAA.o_avi_account_user_id
            LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
            LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
            WHERE AAUA.a_avi_user_id=$user AND AAUA.status=1 AND OAA.o_avi_account_status=1 AND OAU.o_avi_user_id NOT IN ($notin) $where
            ORDER BY nameAccount
            LIMIT $k,10";
        //echo $query;
        $garages=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $garages[]=$row;
                }
            }
        }
        $db->close();
        return $garages;
    }
    function getAllSharablesGaragesByUser($idUser){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($idUser);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $query="SELECT OAA.o_avi_account_id id, OAA.o_avi_account_name name, IF(OAA.o_avi_account_user_id=$idUser, 1, 0) owner, AAAD.a_avi_accountdetail_avatar_img avatar
            FROM a_avi_user_account AAUA
            LEFT JOIN o_avi_account OAA ON OAA.o_avi_account_id=AAUA.a_avi_account_id
            LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
            WHERE AAUA.a_avi_user_id=$idUser AND AAUA.status=1 AND AAUA.a_avi_user_account_level<=3 AND OAA.o_avi_account_status=1 AND OAA.o_avi_account_user_id NOT IN ($notin)";
        $garages=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $garages[]=$row;
                }
            }
        }
        $db->close();
        return $garages;
    }
    function GaragesRecomended($user){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $sugerencias=array();
        $query="SELECT OAA.o_avi_account_id id, OAA.o_avi_account_name name, AAAD.a_avi_accountdetail_avatar_img avatar, OAU.o_avi_user_id userId, OAUD.o_avi_userdetail_name userName, OAUD.o_avi_userdetail_last_name userLastName, AAUP.a_avi_user_perfil_privacy userPrivacy, RAND()*(IF(OAA.o_avi_account_type_id=2,2,1)) position
                FROM o_avi_account OAA
                LEFT JOIN a_avi_accountdetail AAAD ON AAAD.a_avi_account_id=OAA.o_avi_account_id
                LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=OAA.o_avi_account_user_id
                LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
                WHERE OAA.o_avi_account_user_id <> $user AND OAA.o_avi_account_id NOT IN (SELECT a_user_following_account_id FROM a_user_follow_account WHERE a_user_follower_acc_user_id=$user) AND OAA.o_avi_account_type_id in (1,2) AND OAU.o_avi_user_id NOT IN ($notin)
                ORDER BY OAA.o_avi_account_type_id DESC, position
                LIMIT 10";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $sugerencias[]=$row;
                }
            }
        }
        $db->close();
        return $sugerencias;
    }
    function getGarageOfCar($car){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT OAA.o_avi_account_id id, OAA.o_avi_account_name name, OAA.o_avi_account_type_id privacy 
            FROM i_avi_account_car IAAC 
            LEFT JOIN o_avi_account OAA ON IAAC.i_avi_account_car_account_id=OAA.o_avi_account_id
            WHERE IAAC.i_avi_account_car_id=$car";
        $garage = array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $garage=$row;
                }
            }
        }
        $db->close();
        return $garage;
    }
}