<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 10/01/2018
 * Time: 12:10 PM
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
class Usuario
{
    function notAccesibleUsers($user){
        $notAllowed=array();
        $database=new Database;
        $db=$database->connect();
        $query="SELECT f_avi_blocked_users_blocker user FROM f_avi_blocked_users WHERE f_avi_blocked_users_blocked=$user AND f_avi_blocked_users_status=1";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $notAllowed[]=$row["user"];
                }
            }
        }
        $query="SELECT f_avi_blocked_users_blocked user FROM f_avi_blocked_users WHERE f_avi_blocked_users_blocker=$user AND f_avi_blocked_users_status=1";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $notAllowed[]=$row["user"];
                }
            }
        }
        $db->close();
        return $notAllowed;
    }
    function createRandom()
    {
        $chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $clen   = strlen( $chars )-1;
        $id  = '';
        for ($i = 0; $i < 10; $i++) {
              $id .= $chars[mt_rand(0,$clen)];
        }
        return $id;
    }
    function sendConfirmationMail($eMailTo,$user, $iduser, $refresToken=null)
    {
        if($refresToken)
        {
            $type=2;
            $token=$this->updateToken($iduser, $type, $eMailTo);
        }
        else
        {
            $token = $this->createTokenConfEmail($eMailTo,$iduser,2);
        }
        if ($token)
        {
            ini_set("display_errors",0);
            require ($_SERVER['DOCUMENT_ROOT']) . '/libraries/phpmailer/vendor/autoload.php';
            $url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/confirm/?token=".urlencode(base64_encode($token))."&account=".urlencode(base64_encode('activate'));
            $html=file_get_contents($_SERVER['DOCUMENT_ROOT']."/html/mailWelcome.html");
            $html=str_replace("--url--", $url, $html);
            $html=str_replace("--nombre--", $user, $html);
            $acentos=array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&ntilde;","&ntilde;");
            $acentosto=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ");
            //$asunto=str_replace($acentos, $acentosto, $asunto);
            $mail = new PHPMailer();
            $resp=null;
            $mail->SMTPDebug = 0;
            $mail->isSendMail();
            $mail->Host = 'smtp.1and1.mx';
            $mail->SMTPAuth = true;
            $mail->Username = 'juan.gonzalez@skytel.com.mx';
            $mail->Password = '52753689';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587; 
            $mail->setFrom('noreply@avicars.app', 'AVI cars');
            $mail->addAddress($eMailTo, $user);
            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu correo para Apoyo Vial';
            $mail->msgHTML($html);
            if($mail->send())
            {
                return true;
            }
            return false;
        }
        else
        {
            return false;
        }
    }
    function sendNewMailMail($eMailTo,$user, $iduser, $refresToken=null){
        if($refresToken)
        {
            $type=2;
            $token=$this->updateToken($iduser, $type, $eMailTo);
        }
        else
        {
            $token = $this->createTokenConfEmail($eMailTo,$iduser,2);
        }
        if ($token)
        {
            ini_set("display_errors",0);
            require ($_SERVER['DOCUMENT_ROOT']) . '/libraries/phpmailer/vendor/autoload.php';
            $url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/confirm/?token=".urlencode(base64_encode($token))."&account=".urlencode(base64_encode('activate'));
            $html=file_get_contents($_SERVER['DOCUMENT_ROOT']."/html/mailNewMail.html");
            $html=str_replace("--url--", $url, $html);
            $html=str_replace("--nombre--", $user, $html);
            $acentos=array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&ntilde;","&ntilde;");
            $acentosto=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ");
            //$asunto=str_replace($acentos, $acentosto, $asunto);
            $mail = new PHPMailer();
            $resp=null;
            $mail->SMTPDebug = 0;
            $mail->isSendMail();
            $mail->Host = 'smtp.1and1.mx';
            $mail->SMTPAuth = true;
            $mail->Username = 'juan.gonzalez@skytel.com.mx';
            $mail->Password = '52753689';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587; 
            $mail->setFrom('noreply@avicars.app', 'AVI cars');
            $mail->addAddress($eMailTo, $user);
            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu correo para Apoyo Vial';
            $mail->msgHTML($html);
            if($mail->send())
            {
                return true;
            }
            return false;
        }
        else
        {
            return false;
        }
    }
    function enviarUser($signUpUsername,$signUpPassword=null,$signUpEmail,$method="WP",$externalID=null,$status=3) {
        $database=new Database;
        $db=$database->connect();
        if($method!="WP")
        {
            $randomString = $this->createRandom();
            $pw = $externalID . "|-|" . $signUpUsername . "|-|" . strtotime("now").$randomString;
            $pw = str_shuffle($pw);
            $signUpPassword = $this->hashPassword($pw);

        }
        $last_id=false;
        $sql = "INSERT INTO o_avi_user (o_avi_user_username,o_avi_user_password,o_avi_user_email, o_avi_user_status,o_avi_user_registered_method,o_avi_user_registered)
                VALUES ('$signUpUsername', '$signUpPassword', '$signUpEmail', '$status' ,'$method',NOW()) ";
        if($db -> query($sql)){
            $last_id = $db->insert_id;    
        }
        
        $db->close();
        return $last_id;
    }
    function createToken($mail,$type){
        $database=new Database;
        $db=$database->connect();
        $VerifCorreo= $this-> verifyEmail($mail);
        $id= $this -> getIdByMail($mail);
        $token=null;
        if($VerifCorreo)
        {
            $randStr = $this-> createRandom();
            $token = $mail.$randStr.strtotime("now");
            $query="INSERT INTO f_avi_token(f_avi_token,f_avi_token_user_id, f_avi_token_type_id, f_avi_token_expiration_date)
                VALUES ('$token','$id', $type, NOW() + INTERVAL 1 DAY)";
            $queryDB=$db-> query($query);
            if(!$queryDB)
            {
                $token=null;
            }
        }
        return array("token" => $token,"id" => $id);
    }
    function getNameAndLastNameUser($id){
        $database=new Database;
        $db=$database->connect();
        $query= "SELECT o_avi_userdetail_name,o_avi_userdetail_last_name FROM o_avi_userdetail WHERE o_avi_userdetail_id_user = $id";
        $queryDB = $db -> query($query);
        if($queryDB->num_rows>0) 
        {
            while($row=$queryDB->fetch_assoc())
            {
                $nombre=$row["o_avi_userdetail_name"];
                $apellido = $row["o_avi_userdetail_last_name"];
            }
        }
        $db->close();
        $nombre_completo = $nombre." ".$apellido;
        return $nombre_completo;
    }
    function updateToken($iduser, $type, $mail)
    {
        $database=new Database;
        $db=$database->connect();
        $VerifCorreo= $this-> verifyEmail($mail);
        $id= $this -> getIdByMail($mail);
        $token=null;
        if($VerifCorreo)
        {
            $query="UPDATE f_avi_token SET f_avi_token_expiration_date=NOW() + INTERVAL 1 DAY WHERE f_avi_token_user_id='$iduser' AND f_avi_token_type_id='$type'";
            $queryDB=$db-> query($query);
            if($queryDB)
            {
                $token=null;
                $sql="SELECT f_avi_token
                      FROM f_avi_token
                      WHERE f_avi_token_type_id='$type' AND f_avi_token_user_id='$iduser'";
                if($dbQuery=$db -> query($sql))
                {
                    while($row=$dbQuery->fetch_assoc())
                    {
                        $token=$row["f_avi_token"];
                    }
                }
                else
                {
                    $token=null;
                }
            }
            else
            {
               $token=null;
            }
        }
        return $token;
    }
    function createTokenConfEmail($mail,$id,$type){
        $database=new Database;
        $db=$database->connect();
        $token=null;
        $randStr = $this-> createRandom();
        $token = $mail.$randStr.strtotime("now");
        $query="INSERT INTO f_avi_token(f_avi_token,f_avi_token_user_id, f_avi_token_type_id, f_avi_token_expiration_date)
            VALUES ('$token','$id', $type, NOW() + INTERVAL 1 DAY)";
        $queryDB=$db-> query($query);
        if(!$queryDB)
        {
            $token=null;
        }
        return $token;
    }
    function getIdByMail($mail){
        $database=new Database;
        $db=$database->connect();
        $query= "SELECT o_avi_user_id FROM o_avi_user WHERE o_avi_user_email='$mail'";
        $queryDB = $db -> query($query);
        $resp=false;
        if($queryDB->num_rows>0) 
        {
            while($row=$queryDB->fetch_assoc())
            {
                $resp=$row["o_avi_user_id"];
            }
        }
        $db->close();
        return $resp;
           
    }
    function getStatusUser($id){
        $database=new Database;
        $db=$database->connect();
        $query= "SELECT o_avi_user_status FROM o_avi_user WHERE o_avi_user_id='$id'";
        $queryDB = $db -> query($query);
        $resp=false;
        if($queryDB->num_rows>0) 
        {
            while($row=$queryDB->fetch_assoc())
            {
                $resp=$row["o_avi_user_status"];
            }
        }
        $db->close();
        return $resp;
           
    }
    function recoverPwd($signUpEmail,$token,$name){
        ini_set("display_errors",0);
        require ($_SERVER['DOCUMENT_ROOT']) . '/libraries/phpmailer/vendor/autoload.php';
        $mail = new PHPMailer();
        $resp=null;
        $mail->SMTPDebug = 0;
        $mail->isSendMail();
        $mail->Host = 'smtp.1and1.mx';
        $mail->SMTPAuth = true;
        $mail->Username = 'juan.gonzalez@skytel.com.mx';
        $mail->Password = '52753689';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; 
        $mail->setFrom('noreply@avicars.app', 'AVI cars');
        $mail->addAddress($signUpEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Cambia tu contrasena de AVI cars';
        $url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER["SERVER_NAME"]."/password/?token=".urlencode(base64_encode($token));
        $html=file_get_contents($_SERVER['DOCUMENT_ROOT']."/html/mailPassword.html");
        $html=str_replace("--url--", $url, $html);
        $html=str_replace("--nombre--",$name, $html);
        $mail->msgHTML($html);
        if($mail->send())
        {
            return true;
        }
        return false;
    }
    
    function verifyToken($token, $type){
        $database=new Database;
        $db=$database->connect();
        $sql="SELECT f_avi_token_user_id, o_avi_user_username, o_avi_user_email
              FROM f_avi_token
              LEFT JOIN o_avi_user ON o_avi_user.o_avi_user_id = f_avi_token.f_avi_token_user_id
              WHERE f_avi_token='$token' AND f_avi_token_type_id='$type'  AND (f_avi_token_expiration_date)>NOW()";
         $infoToken= array();
        if($dbQuery=$db -> query($sql))
        {
            while($row=$dbQuery->fetch_assoc())
            {
                $infoToken=array
                (
                    'idUser'=>$row["f_avi_token_user_id"],
                    'userName'=>$row["o_avi_user_username"],
                    'userMail'=>$row["o_avi_user_email"]
                );
            }
        }
        $db->close();
        return $infoToken;
    }
    function verifyTokenByUser($iduser, $type){
        $database=new Database;
        $db=$database->connect();
        $sql="SELECT f_avi_token_user_id
              FROM f_avi_token
              WHERE f_avi_token_user_id='$iduser' AND f_avi_token_type_id='$type'";
        $resp= false;
        $queryDB = $db -> query($sql);
        if($queryDB->num_rows>0)
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function deleteToken($token, $type){
        $database=new Database;
        $db=$database->connect();
        $sql="DELETE FROM f_avi_token WHERE f_avi_token='$token' AND f_avi_token_type_id='$type'";
        $resp=false;
        if ($dbQuery=$db->query($sql)) 
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function changePassword($userId,$pwd){
        $database=new Database;
        $db=$database->connect();
        $pwd = $this->hashPassword($pwd);
        $sql="UPDATE o_avi_user SET o_avi_user_password='$pwd' WHERE o_avi_user_id='$userId'";
        $ret=false;
        if($db->query($sql)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }

    function verifyPassword($id,$pwd){
        $database=new Database;
        $db=$database->connect();
        $query = "SELECT o_avi_user_password
                  FROM o_avi_user WHERE o_avi_user_id ='$id'";
        if($dbQuery=$db -> query($query))
        {
            while($row=$dbQuery->fetch_assoc())
            {
                $hash=$row["o_avi_user_password"];
            }
        }
        if ($this->comparePasswords($pwd, $hash)) {
            return true;
        }
        $db->close();
        return false;

        
    }

    function enviarUserDetails($last_id,$data=array()) {
        $database=new Database;
        $db=$database->connect();
        $insert="o_avi_userdetail_id_user";
        $values=$last_id;
        foreach ($data as $attr => $valor) {
            $insert.=",$attr";
            $values.=",'$valor'";
        }
        $sql1 = "INSERT INTO o_avi_userdetail ($insert)
                VALUES ($values)";
        $ret=false;
        if($db -> query($sql1))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }

    function completarUserDetail($id ,$signUpPhone,$signUpCellPhone,$selectGender,$name, $lastname, $birth=null)
    {
        $database = new Database;
        $db = $database->connect();
        $sql2="";
        $sql2 = "UPDATE o_avi_userdetail SET";
        if($selectGender != 0)
        {
            $sql2.=" o_avi_userdetail_gender='$selectGender',";
        }
        if($birth){
            if(strtotime($birth)){
                $sql2.=" o_avi_userdetail_birth_date='$birth',";
            }
        }
        if($signUpPhone[0]!=""){
            $sql2.=" o_avi_userdetail_phone='".$signUpPhone[0]."', o_avi_userdetail_phone_code='".$signUpPhone[1]."',";
        }
        if($signUpCellPhone[0]!=""){
            $sql2.=" o_avi_userdetail_cellphone='".$signUpCellPhone[0]."', o_avi_userdetail_cellphone_code='".$signUpCellPhone[1]."',o_avi_userdetail_cellphone_wa='".$signUpCellPhone[2]."',";
        }
        $sql2.=" o_avi_userdetail_name = '$name', o_avi_userdetail_last_name = '$lastname' WHERE o_avi_userdetail_id_user='$id'";
        $ret=false;
        if($db->query($sql2)){
            $ret=true;
            $sql3 = "UPDATE o_avi_user SET o_avi_user_verified=0 WHERE o_avi_user_id = $id";
            $db->query($sql3);
        }
        $db->close();
        return $ret;
    }

    function enviarUserAddress($id,$signUpStreet,$signUpZipcode) {
        $database=new Database;
        $db=$database->connect();
        $sql1 = "INSERT INTO a_avi_useraddress (a_avi_useraddress_id_user,a_avi_useraddress_street,a_avi_useraddress_zip_code) VALUES ('$id' ,'$signUpStreet','$signUpZipcode')";
        $ret=false;
        if($db -> query($sql1))
        {
            $ret=true;
            $sql2 = "UPDATE o_avi_user SET o_avi_user_verified=0 WHERE o_avi_user_id = $id";
            $db->query($sql2);
        }
        $db->close();
        return $ret;
    }

    function completarUserAddress($id,$signUpStreet,$signUpZipcode) {
        $database=new Database;
        $db=$database->connect();
        $sql1 = "UPDATE a_avi_useraddress SET a_avi_useraddress_street='$signUpStreet',a_avi_useraddress_zip_code='$signUpZipcode' WHERE a_avi_useraddress_id_user= '$id' ";
        $ret=false;
        if($db -> query($sql1))
        {
            $ret=true;
            $sql2 = "UPDATE o_avi_user SET o_avi_user_verified=0 WHERE o_avi_user_id = $id";
            $db->query($sql2);
        }
        $db->close();
        return $ret;
    }

    function crearCuenta($last_id,$signUpUsername,$accountType,$accountStatus){
        $database=new Database;
        $db=$database->connect();
        $sql2= "INSERT INTO o_avi_account(o_avi_account_user_id, o_avi_account_name,o_avi_account_type_id,o_avi_account_status)
              VALUES ('$last_id' ,'$signUpUsername','$accountType','$accountStatus')";
        $idGarage=false;
        if($db -> query($sql2))
        {
            $idGarage = $db->insert_id;;
        }
        $db->close();
        return $idGarage;
    }

    function nombreCuenta($id,$signUpUsername){
        $database=new Database;
        $db=$database->connect();
        $sql2= "UPDATE o_avi_account SET o_avi_account_name= '$signUpUsername'
              WHERE o_avi_account_id='$id'";
        $ret=false;
        if($db -> query($sql2))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function privacyFather($padreId)
    {
        $database=new Database;
        $db=$database->connect();
        $sql="SELECT o_avi_account_type_id privacidad
              FROM o_avi_account
              WHERE o_avi_account_id='$padreId'";
        $tipoPrivacidad="";
        $queryDB = $db -> query($sql);
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $tipoPrivacidad=$row["privacidad"];
            }
        }
        $db->close();
        return $tipoPrivacidad;
    }
    function nuevaCuenta($last_id,$signUpUsername,$accountType,$accountStatus,$padre=null){
        $database=new Database;
        $db=$database->connect();
        if(!$padre)
            $sql2= "INSERT INTO o_avi_account(o_avi_account_user_id, o_avi_account_name,o_avi_account_type_id,o_avi_account_status)
                  VALUES ('$last_id' ,'$signUpUsername','$accountType','$accountStatus')";
        else
            $sql2= "INSERT INTO o_avi_account(o_avi_account_user_id, o_avi_account_name, o_avi_account_type_id, o_avi_account_status, o_avi_account_father ) VALUES ('$last_id' ,'$signUpUsername','$accountType','$accountStatus','$padre')";
        $ret=false;
        if($db -> query($sql2))
        {
            $ret=$db -> insert_id;
        }
        $db->close();
        return $ret;
    }

    function getCuenta($id,$padre=NULL){
       /* $database=new Database;
        $db=$database->connect();
        if(!$padre)
            $sql3="SELECT o_avi_account_id, o_avi_account_name,o_avi_account_father FROM o_avi_account WHERE o_avi_account_user_id= '$id' AND o_avi_account_father is null AND o_avi_account_status = '1' ;";
        else
            $sql3="SELECT o_avi_account_id, o_avi_account_name,o_avi_account_father FROM o_avi_account WHERE o_avi_account_user_id= '$id' AND o_avi_account_father=$padre AND o_avi_account_status = '1'";
        $queryDB = $db -> query($sql3);*/
        $infoCuenta= array();

        /*if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                
                $infoCuenta[$row["o_avi_account_id"]]=array(
                    'idgarage'=>$row["o_avi_account_id"],
                    'nombre'=>$row["o_avi_account_name"],
                    'padre'=>$row["o_avi_account_father"],
                    'hijos'=>$this->getCuenta($id,$row["o_avi_account_id"])
                );
            }
        }
        $db->close();*/
        return $infoCuenta;
    }

    function getGarage()
    {
        $cuentaGrg =  $this->getCuenta(0);
        return $cuentaGrg;

    }

    function deshabilitarCuenta($id){
        $database=new Database;
        $db=$database->connect();
        $sql2="UPDATE o_avi_account SET o_avi_account_status='2'  WHERE o_avi_account_id='$id' ";
        $ret=false;
        if($db -> query($sql2))
        {
            $ret=true;
        }
        $db->close();
        return $ret;   
    }

    function deleteGarage($id,$padre){
        $database=new Database;
        $db=$database->connect();
        $return=$this->recorrer($id,$padre,$db);
        $db->close();
        return $return;
    }
    function recorrer($id,$padre,$db){
        if(!$padre)
            $sql3="SELECT o_avi_account_id, o_avi_account_name,o_avi_account_father FROM o_avi_account WHERE o_avi_account_user_id= '$id' AND o_avi_account_father is null AND o_avi_account_status = '1'";
        else
            $sql3="SELECT o_avi_account_id, o_avi_account_name,o_avi_account_father FROM o_avi_account WHERE o_avi_account_user_id= '$id' AND o_avi_account_father='$padre' AND o_avi_account_status = '1'";
        $queryDB = $db -> query($sql3);
        $nc= array();
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                
                $nc[$row["o_avi_account_id"]]=array(
                    'nombre'=>$row["o_avi_account_name"],
                    'padre'=>$row["o_avi_account_father"],
                    'hijos'=>$this->recorrer($id,$row["o_avi_account_id"],$db)
                );
                //echo $row["o_avi_account_id"];
                $this -> deshabilitarCuenta($row["o_avi_account_id"]);
                //$this -> deshabilitarCuenta($row["o_avi_account_father"]);
            }
        }
        elseif($queryDB->num_rows===0 && $padre)
        {
            $this -> deshabilitarCuenta($padre);
        }
        return $nc;
    }


    function agregando($nombreCuenta, $idusuario)
    {
        $html="";
        foreach($nombreCuenta as $nc => $gar){
            $html.="<li data-jstree='{ \"icon\": \"/img/icons/Garage-16.png\"}'>
                    <span  >
                        <strong >".$gar["nombre"]. "  </strong> 
                    </span>";
            if(isset($_SESSION["iduser"]) && isset($_GET["cuenta"]) && $_SESSION["iduser"]==$_GET["cuenta"])
            {
            $html.= "<span class='glyphicon glyphicon-pencil pencil' data-garage='".$gar["nombre"]. "' data-padre='".$nc."' onclick='editChangeGarage($(this))'></span>";
            }
            $html.="<ul>";
            if(!empty($gar["hijos"]))
            {
                $html.=$this->agregando($gar["hijos"], $idusuario);
            }
            if(isset($_SESSION["iduser"]) && isset($_GET["cuenta"]) && $_SESSION["iduser"]==$_GET["cuenta"])
            {
            $html.="<li data-jstree='{\"icon\":\"fas fa-plus fa-2x\"}' onclick='create($(this))' data-padre='".$nc."' >Agregar garage</li> 
                <li data-jstree='{\"icon\":\"fas fa-car fa-2x\"}' fa-car onclick='agregarCarro(".$idusuario.",".$nc.")'>Agregar auto</li>";
            }
            $html.="</ul>
                </li>
                ";
        }
        return $html;
    }

    function getnombreCuenta($id){
        $database=new Database;
        $db=$database->connect();
        $sql3="SELECT o_avi_account_name FROM o_avi_account WHERE o_avi_account_user_id= '$id'";
        $queryDB = $db -> query($sql3);
        $nombreCuenta=null;
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $nombreCuenta[]=$row["o_avi_account_name"];
            }
        }
        $db->close();
        return $nombreCuenta;
    }

    function verifyAccountName($id,$nombeCuenta){
        $database=new Database;
        $db=$database->connect();
        $sql2="SELECT count(o_avi_account_name) FROM o_avi_account
               WHERE o_avi_account_name='$nombeCuenta' AND  o_avi_account_user_id='$id'";
        $queryDB = $db -> query($sql2);
        $existeCuenta=false;
        if($queryDB->num_rows>0) {
            $existeCuenta=true;
        }
        $db->close();
        return $existeCuenta;
    }
    function verifyUserName($user, $id=NULL){
        $database=new Database;
        $db=$database->connect();
        $sql2 = "";
        $sql2 = "SELECT o_avi_user_username FROM o_avi_user WHERE o_avi_user_username='$user'";
        if($id)
        {
            $sql2.= "and o_avi_user_id <> '$id'";
        }
        $queryDB = $db -> query($sql2);
        $existeUser=false;
        if($queryDB->num_rows>0) {
            $existeUser=true;
        }
        $db->close();
        return $existeUser;
    }

    function verifyEmail($mail, $id=NULL){
        $database=new Database;
        $db=$database->connect();
        $sql3 = "";
        $sql3.="SELECT o_avi_user_email FROM o_avi_user WHERE o_avi_user_email=('$mail')";
        if($id)
        {
            $sql3.="and o_avi_user_id <> '$id'";
        }
        $queryDB = $db -> query($sql3);
        $ret=false;
        if($queryDB->num_rows>0) {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function verifyAdress($id)
    {
        $database=new Database;
        $db=$database->connect();
        $query = "SELECT `a_avi_useraddress_id` FROM `a_avi_useraddress` WHERE a_avi_useraddress_id_user ='$id'";
        $queryDB = $db -> query($query);
        $ret=false;
        if($queryDB->num_rows>0) {
            $ret=true;
        }
        $db->close();
        return $ret;
    }

    function getPhone($id){
        $database=new Database;
        $db=$database->connect();
        $sql3="SELECT o_avi_userdetail_phone FROM o_avi_userdetail WHERE o_avi_userdetail_id_user= '$id'";
        $queryDB = $db -> query($sql3);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $em=array("phone" => $row["o_avi_userdetail_phone"]);
            }
            return $em;
        }
        $db->close();
    }

    function cGetGender(){
        $database=new Database;
        $db=$database->connect();
        $sql3="SELECT c_avi_gender_name, c_avi_gender_id FROM c_avi_gender ORDER BY c_avi_gender_name ASC";
        $queryDB = $db -> query($sql3);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $gen[$row["c_avi_gender_id"]]=array("genero" => $row["c_avi_gender_name"]);
            }
            return $gen;
        }
        $db->close();
    }

    function getGenderUser($id){
        $database=new Database;
        $db=$database->connect();
        $sql3="SELECT o_avi_userdetail_gender FROM o_avi_userdetail WHERE o_avi_userdetail_id_user='$id'";
        $queryDB = $db -> query($sql3);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $gen=array("genero" => $row["o_avi_userdetail_gender"]);
            }
            return $gen;
        }
        $db->close();
    }

    function getStreet($id){
        $database=new Database;
        $db=$database->connect();
        $sql3="SELECT a_avi_useraddress_street FROM a_avi_useraddress WHERE a_avi_useraddress_id_user= '$id'";
        $queryDB = $db -> query($sql3);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $em=array("street" => $row["a_avi_useraddress_street"]);
            }
            return $em;
        }
        $db->close();
    }

    function getZipCode($id){
        $database=new Database;
        $db=$database->connect();
        $sql3="SELECT a_avi_useraddress_zip_code FROM a_avi_useraddress WHERE a_avi_useraddress_id_user= '$id'";
        $queryDB = $db -> query($sql3);
        if($queryDB->num_rows>0) {
            while($row=$queryDB->fetch_assoc())
            {
                $em=array("zipcode" => $row["a_avi_useraddress_zip_code"]);
            }
            return $em;
        }
        $db->close();
    }

    public function hashPassword($pwd=null){
        if(!$pwd){
            return 0;
        }
        $options = ['cost' => 12, ];
        $pwdhashed=password_hash($pwd, PASSWORD_BCRYPT, $options);
        return $pwdhashed;
    }

    public function login($username = null, $mail = null, $pwd = null, $islogin=null)
    {
        if (!$pwd) {
            return 0;
        }
        $database=new Database;
        $db=$database->connect();
        if ($username) {
            $query = "SELECT o_avi_user_id, o_avi_user_password, o_avi_user_username, o_avi_user_email, o_avi_user_status
			FROM o_avi_user WHERE o_avi_user_status in (1,2,3) AND o_avi_user_username = '" . $username . "'";
        } elseif ($mail) {
            $query = "SELECT o_avi_user_id, o_avi_user_password, o_avi_user_username, o_avi_user_email, o_avi_user_status
			FROM o_avi_user WHERE o_avi_user_status in (1,2,3) AND o_avi_user_email = '" . $mail . "'";
        } else {
            return 0;
        }
        $result = $db->query($query);

        if ($result->num_rows > 0) {

            // output data of each row
            while($row = $result->fetch_assoc()) {
                $hash=$row["o_avi_user_password"];
                $id=$row["o_avi_user_id"];
                $user=$row["o_avi_user_username"];
                $mail=$row["o_avi_user_email"];
                $status = $row["o_avi_user_status"];
            }
        } else {
            $db->close();
            return false;
        }
        $db->close();
        if ($this->comparePasswords($pwd, $hash)) {
            if(!$islogin)
            {
                $this->createSession($id,$user,$mail, $status,"WP");
            }
            return true;
        }
        //$this->logout();
        return false;
    }



    public function loginExternal($username = null, $mail = null, $pwd = null, $method=null)
    {
        $database=new Database;
        $db=$database->connect();
        if ($mail) {
            $query = "SELECT o_avi_user_id, o_avi_user_password, o_avi_user_username, o_avi_user_email, o_avi_user_status
            FROM o_avi_user WHERE o_avi_user_status in (1,2,3) AND o_avi_user_email = '" . $mail . "'";
        } else {
            return 0;
        }
        $result = $db->query($query);

        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {
                $hash=$row["o_avi_user_password"];


                $id=$row["o_avi_user_id"];
                $user=$row["o_avi_user_username"];
                $mail=$row["o_avi_user_email"];
                $status = $row["o_avi_user_status"];
            }
        } else {
            $db->close();
            return false;
        }
        $db->close();
        $this->createSession($id,$user,$mail, $status, $method);
        return true;
    }

    public function createSession($userid = null, $username = null, $mail = null, $status=null, $method=null)
    {
        if (!$username && !$mail) {
            return 0;
        }
        $coder = new Coder($userid);
        $this->enableAccount($userid);
        session_start();
        $_SESSION["user"] = $username;
        $_SESSION["iduser"] = $userid;
        $_SESSION["mail"] = $mail;
        $_SESSION["status"] = $status;
        $_SESSION["sessionkey"] = base64_encode($userid . "|-|" . $username . "|-|" . strtotime("now"));
        $_SESSION["site"] = 4;
        $_SESSION["method"]=$method;
        $_SESSION["loads"]=0;
        $_SESSION["usertkn"]=$coder->encoded;
        //$_SESSION["permisos"]=$modulos;
    }

    public function comparePasswords($pwd=null,$hash=null)
    {
        if(!$pwd||!$hash){
            return 0;
        }
        if(!password_verify($pwd,$hash)){
            return false;
        }
        return true;
    }
    function userAccount($usuarioID)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "SELECT o_avi_account_id, o_avi_account_name FROM o_avi_account WHERE o_avi_account_user_id= '$usuarioID'";
        $queryDB=$db->query($sql);
        $cuentaUsuario = array();
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$cuentaUsuario[$row["o_avi_account_id"]]=$row["o_avi_account_name"];
			}
		}
		$db->close();
        return $cuentaUsuario;
    }
    public function insertCar($userVersion)
    {
        $database=new Database;
        $db=$database->connect();
        $sql1 = "INSERT INTO o_avi_car (o_avi_car_version_id) VALUES ('$userVersion')";
        $db -> query($sql1);
        $last_id = $db -> insert_id;
        $db -> close();
        return $last_id;
    }

    function vendeCar($userVersion)
    {
        $database=new Database;
        $db=$database->connect();
        $sql1 = "INSERT INTO o_avi_car (o_avi_car_version_id) VALUES ('$userVersion')";
        $db -> query($sql1);
        $last_id = $db -> insert_id;
        $db -> close();
    }

    function userVenta($id)
    {
        $database=new Database;
		$db=$database->connect();
        $ventaUsuario = array();
		$sql = "SELECT i_avi_account_car_id, i_avi_account_car_account_id FROM i_avi_account_car WHERE i_avi_account_car_account_id='$id' ";
		$queryDB=$db->query($sql);
		if($queryDB->num_rows>0)
		{
			while($row=$queryDB->fetch_assoc())
			{
				$ventaUsuario[$row["i_avi_account_car_account_id"]]=$row["i_avi_account_car_id"];
			}
		}
		$db->close();
		return $ventaUsuario;
    }

    function createDir($id)
    {
        if(!file_exists($_SERVER['DOCUMENT_ROOT']."/users/$id"))
            mkdir(($_SERVER['DOCUMENT_ROOT'])."/users/$id", 0757);
        if(!file_exists($_SERVER['DOCUMENT_ROOT']."/users/$id/fotoCar"))
            mkdir(($_SERVER['DOCUMENT_ROOT'])."/users/$id/fotoCar", 0757);
        if(!file_exists($_SERVER['DOCUMENT_ROOT']."/users/$id/FotoFactura"))
            mkdir(($_SERVER['DOCUMENT_ROOT'])."/users/$id/FotoFactura", 0757);
        if(!file_exists($_SERVER['DOCUMENT_ROOT']."/users/$id/FotoVin"))
            mkdir(($_SERVER['DOCUMENT_ROOT'])."/users/$id/FotoVin", 0757);
        if(!file_exists($_SERVER['DOCUMENT_ROOT']."/users/$id/Avatar"))
            mkdir(($_SERVER['DOCUMENT_ROOT'])."/users/$id/Avatar", 0757);
        if(!file_exists($_SERVER['DOCUMENT_ROOT']."/users/$id/Cover"))
            mkdir(($_SERVER['DOCUMENT_ROOT'])."/users/$id/Cover", 0757);
    }

    function getUserdetail($idUser)
    {
        $database=new Database;
		$db=$database->connect();
        $cuentaUsr = array();
		$sql = "SELECT o_avi_userdetail_id_user, 
        o_avi_userdetail_name, o_avi_userdetail_last_name, 
        o_avi_userdetail_phone, o_avi_user_email, 
        a_avi_useraddress_street, 
        a_avi_useraddress_zip_code, 
        c_avi_gender_name genero, 
        o_avi_userdetail_birth_date fechaNacimiento, 
        o_avi_userdetail_gender generoid, 
        o_avi_user_verified verified, 
        AAUP.a_avi_user_perfil_bio bio, 
        CAZ.c_avi_zipcode_city municipio, 
        CAS.c_avi_state_name estado, 
        o_avi_userdetail_phone phone, 
        o_avi_userdetail_phone_code phonecode, 
        o_avi_userdetail_cellphone cellphone, 
        o_avi_userdetail_cellphone_code cellphonecode, 
        o_avi_userdetail_cellphone_wa cellphonewa
        FROM o_avi_userdetail
        LEFT JOIN c_avi_gender ON c_avi_gender.c_avi_gender_id=o_avi_userdetail.o_avi_userdetail_gender
        LEFT JOIN o_avi_user ON o_avi_user.o_avi_user_id = o_avi_userdetail.o_avi_userdetail_id_user
        LEFT JOIN a_avi_useraddress ON a_avi_useraddress.a_avi_useraddress_id_user = o_avi_user.o_avi_user_id
        LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=o_avi_user.o_avi_user_id
        LEFT JOIN c_avi_zipcode CAZ ON CAZ.c_avi_zipcode_id=a_avi_useraddress.a_avi_useraddress_zip_code
        LEFT JOIN c_avi_state CAS ON CAS.c_avi_state_id=CAZ.c_avi_zipcode_id_state 
        WHERE o_avi_userdetail_id_user='$idUser' AND o_avi_user_status in (1,3)";
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
    function confirmAccount($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "UPDATE o_avi_user SET o_avi_user_status='1' WHERE o_avi_user_id='$idUser'";
        $ret=false;
        if($db -> query($sql))
        {
            $ret=true;
        }
        $db -> close();
        return $ret;
    }

    function updateEmail($idUser, $email)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "UPDATE o_avi_user SET o_avi_user_email='$email' WHERE o_avi_user_id='$idUser'";
        $ret=false;
        if($db -> query($sql))
        {
            $ret=true;
            $sql2 = "UPDATE o_avi_user SET o_avi_user_verified=0 WHERE o_avi_user_id = $idUser";
            $db->query($sql2);
        }
        $db -> close();
        return $ret;
    }
    function updateUserName($idUser, $username)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "UPDATE o_avi_user SET o_avi_user_username='$username' WHERE o_avi_user_id='$idUser'";
        $ret=false;
        if($db -> query($sql))
        {
            $ret=true;
            $sql2 = "UPDATE o_avi_user SET o_avi_user_verified=0 WHERE o_avi_user_id = $idUser";
            $db->query($sql2);
        }
        $db -> close();
        return $ret;
    }
    function perfilUsuario($idUser, $bio=null, $privacidad=null)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_user_id, a_avi_user_perfil_bio, a_avi_user_perfil_privacy FROM a_avi_user_perfil WHERE a_avi_user_id='$idUser'";
        $queryDB=$db->query($select);
        $count=0;
        $sql="";
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $sql = "UPDATE a_avi_user_perfil SET ";

                if($bio!="" && $row["a_avi_user_perfil_bio"] != $bio)
                {
                    $count++; 
                    $sql.="a_avi_user_perfil_bio='$bio'";
                }
                if($privacidad!="" && $row["a_avi_user_perfil_privacy"] != $privacidad)
                {
                    $count++; 
                    $sql.="a_avi_user_perfil_privacy='$privacidad'";
                }
                $sql.= " WHERE a_avi_user_id='$idUser'; ";
            }
        }
        else
        {
            $insert="a_avi_user_perfil_bio, a_avi_user_id";
            $values="'$bio', $idUser";
            if($privacidad)
            {
                $insert .=", a_avi_user_perfil_privacy";
                $values .=", $privacidad";
            }
            $sql.= "INSERT INTO a_avi_user_perfil ($insert) VALUES ($values)"; 

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
    function getImgPerfil($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="SELECT a_avi_user_perfil_avatar, a_avi_user_perfil_cover FROM a_avi_user_perfil WHERE a_avi_user_id='$idUser'";
        $queryDB=$db->query($query);
        $perfilImg=array();
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $perfilImg=array("avatar"=>$row["a_avi_user_perfil_avatar"], "cover"=>$row["a_avi_user_perfil_cover"]);
            }
        }
        $db->close();
        return $perfilImg;
    }

    function getInfoPerfil($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $query ="SELECT a_avi_user_id,a_avi_user_perfil_bio, a_avi_user_perfil_privacy FROM a_avi_user_perfil LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=a_avi_user_id WHERE a_avi_user_id='$idUser' AND OAU.o_avi_user_status IN (1,3)";
        $queryDB=$db->query($query);
        $perfilInfo=array();
        if($queryDB->num_rows>0)
        {
            while($row=$queryDB->fetch_assoc())
            {
                $perfilInfo=array("bio"=>$row["a_avi_user_perfil_bio"], "privacidad"=>$row["a_avi_user_perfil_privacy"]);
            }
        }
        $db->close();
        return $perfilInfo;
    }

    function changedPasswordMail($signUpEmail,$user){
        ini_set("display_errors",0);
        require ($_SERVER['DOCUMENT_ROOT']) . '/libraries/phpmailer/vendor/autoload.php';
        $mail = new PHPMailer();
        $resp=null;
        $mail->SMTPDebug = 0;
        $mail->isSendMail();
        $mail->Host = 'smtp.1and1.mx';
        $mail->SMTPAuth = true;
        $mail->Username = 'juan.gonzalez@skytel.com.mx';
        $mail->Password = '52753689';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; 
        $mail->setFrom('noreply@avicars.app', 'Avi cars');
        $mail->addAddress($signUpEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Cambios en tu cuenta de Apoyo Vial';
        $url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER["SERVER_NAME"];
        $html=file_get_contents($_SERVER['DOCUMENT_ROOT']."/html/mailPasswordChanged.html");
        $html=str_replace("--url--", $url, $html);
        $html=str_replace("--nombre--",$user, $html);
        $mail->msgHTML($html);
        if($mail->send())
        {
            return true;
        }
        return false;
    }

    function crearDetallesCuenta($idGarage,$uso=1)
    {
        $database=new Database;
        $db=$database->connect();
        $sql2= "INSERT INTO a_avi_accountdetail (a_avi_account_id, a_avi_accountdetail_use_id) VALUES ('$idGarage','$uso');";
        $resp=false;
        if($db -> query($sql2))
        {
            $resp=true;
        }
        $db->close();
        return $resp;       
    }

    function tmpCover($imgName, $id,$type)
    {
        $database=new Database;
        $db=$database->connect();   
        $select ="SELECT tmp_avi_car_img_car, tmp_avi_user_id, tmp_avi_car_img_type 
            FROM tmp_avi_car_img 
            WHERE tmp_avi_user_id='$id' AND tmp_avi_car_img_type='$type'";
        $queryDB=$db->query($select);
        $count=0;
        if($queryDB->num_rows>0)
        { 
            $sql = "UPDATE tmp_avi_car_img SET tmp_avi_car_img_car ='$imgName' WHERE tmp_avi_user_id='$id' AND tmp_avi_car_img_type = $type ";   
        }
        else
        {
            $sql = "INSERT INTO tmp_avi_car_img (tmp_avi_car_img_car, tmp_avi_user_id, tmp_avi_car_img_type) 
                VALUES ('$imgName','$id',$type); ";
        }
        $resp=false;
        if($db -> query($sql))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }

    function savePhoto($idUser, $rutaAvatar, $rutaCover)
    {
        $database=new Database;
        $db=$database->connect();
        $select ="SELECT a_avi_user_id, a_avi_user_perfil_avatar, a_avi_user_perfil_cover FROM a_avi_user_perfil WHERE a_avi_user_id='$idUser'";
        $queryDB=$db->query($select);
        $count=0;
        $sql="";
        if($queryDB->num_rows>0)
        {
            if($rutaAvatar == NULL)
            {
                $sql = "UPDATE a_avi_user_perfil SET a_avi_user_perfil_cover='$rutaCover' WHERE a_avi_user_id='$idUser'; ";
            }   
            elseif ($rutaCover == NULL) 
            {
                $sql = "UPDATE a_avi_user_perfil SET a_avi_user_perfil_avatar='$rutaAvatar' WHERE a_avi_user_id='$idUser'; ";
            }
        }
        else
        {
            if($rutaAvatar == NULL)
            {
                $sql = "INSERT INTO a_avi_user_perfil (a_avi_user_perfil_cover, a_avi_user_id) VALUES ('$rutaCover', '$idUser'); ";
            }   
            elseif ($rutaCover == NULL) 
            {
                $sql = "INSERT INTO a_avi_user_perfil (a_avi_user_perfil_avatar, a_avi_user_id) VALUES ('$rutaAvatar', '$idUser'); ";
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

    function tmpDelete($id,$type)
    {
        $database=new Database;
        $db=$database->connect();
        $sql = "DELETE FROM tmp_avi_car_img WHERE  tmp_avi_user_id = '$id' AND tmp_avi_car_img_type = '$type'";
        $ret=false;             
        if($db->query($sql))
        {
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function getUsersForSearch($search=null,$searcher=null,$time=0)
    {
        $users=array();
        $coder=new Coder;
        $search=strtolower($search);
        if(!$search||$search==="")
        {
            throw new Exception("Empty search", 1);
            
        }
        $notAllowedUsers=$this->notAccesibleUsers($searcher);
        $notin="0";
        foreach ($notAllowedUsers as $u => $user) {
            $notin.=",$user";
        }
        $inicio=10*$time;
        $database=new Database;
        $db=$database->connect();
        $query="SELECT AAUP.a_avi_user_perfil_avatar img, 
                    OAU.o_avi_user_id a_to, 
                    OAU.o_avi_user_username alias, 
                    AAUP.a_avi_user_perfil_privacy privacidad,
                    OAUD.o_avi_userdetail_name  name, 
                    OAUD.o_avi_userdetail_last_name last_name,
                    IF(OAU.o_avi_user_id=$searcher,1,0) self,
                    IF(AUFU.a_user_follower_user_id IS NULL, 0, 1) follower,
                    OAU.o_avi_user_verified verified
                FROM o_avi_user OAU 
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id 
                LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
                LEFT JOIN a_user_follow_user AUFU ON AUFU.a_user_following_user_id=OAU.o_avi_user_id AND AUFU.a_user_follower_user_id=$searcher
                WHERE 
                (OAU.o_avi_user_status=1 OR OAU.o_avi_user_id=$searcher) AND 
                (
                    AAUP.a_avi_user_perfil_privacy IN (1,2) 
                    OR AAUP.a_avi_user_perfil_privacy IS NULL
                    OR (
                       AAUP.a_avi_user_perfil_privacy=3 AND  OAU.o_avi_user_id IN (SELECT a_user_following_user_id FROM a_user_follow_user WHERE a_user_follower_user_id = $searcher)
                        )
                        
                    OR OAU.o_avi_user_id=$searcher
                )
                AND (
                    LOWER(OAU.o_avi_user_username) LIKE '%$search%' 
                    OR LOWER(OAUD.o_avi_userdetail_name) LIKE '%$search%' 
                    OR LOWER(OAUD.o_avi_userdetail_last_name) LIKE '%$search%'
                    OR LOWER(CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name)) LIKE '%$search%' 
                )
                AND OAU.o_avi_user_id not in ($notin)
                ORDER BY self DESC, follower DESC, verified DESC, alias
                LIMIT $inicio, 10";
        //echo $query;
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {

                    $coder -> encode($row["a_to"]);
                    $row["a_to"] = $coder-> encoded;
                    $users[]=$row;
                }
            }
        }
        $db->close();
        return $users;
    }
    function getUserBasic($user){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT OAU.o_avi_user_id id, OAU.o_avi_user_username username, CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name) name, OAU.o_avi_user_id uid, AAUP.a_avi_user_perfil_avatar img
                FROM o_avi_user OAU
                LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id 
                LEFT JOIN a_avi_user_perfil AAUP ON OAU.o_avi_user_id=AAUP.a_avi_user_id
                WHERE OAU.o_avi_user_id=$user";
        $usuario=array();
        if($data=$db->query($query)){
            if($data->num_rows>0)
            {
                while ($row=$data->fetch_assoc()) {
                    $usuario=$row;
                }
            }
        }
        $db->close();
        return $usuario;
    }
    function UserAccessToUser($user,$accesTo){
        $hasAccess=false;
        if($user==$accesTo){
            $hasAccess=true;
        }
        else{
            $database=new Database;
            $db=$database->connect();
            $query="SELECT a_avi_user_perfil_privacy privacidad FROM a_avi_user_perfil WHERE a_avi_user_id=$accesTo";
            $privacy=0;
            if($data=$db->query($query)){
                if($data->num_rows>0){
                    while ($row=$data->fetch_assoc()) {
                       $privacy=$row["privacidad"];
                    }
                }
            }
            if($privacy==2){
                $hasAccess=true;
            }
            else{
                $query="SELECT a_user_follower_user_id FROM a_user_follow_user WHERE a_user_follower_user_id=$user AND a_user_following_user_id=$accesTo";
                if($data=$db->query($query)){
                    if($data->num_rows>0){
                        $hasAccess=true;
                    }
                }
            }
            $db->close();
        }
        return $hasAccess;
    }
    function getPrivacyUser($user){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT a_avi_user_perfil_privacy privacidad FROM a_avi_user_perfil WHERE a_avi_user_id=$user";
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
    function addTokenDelete($user,$tkn){
        $database=new Database;
        $db=$database->connect();
        $due=date("Y-m-d H:i:s",strtotime("now")+3600);
        $query="INSERT INTO f_avi_token_delete_profile(f_avi_token_delete_profile_token, f_avi_token_delete_profile_profile, f_avi_token_delete_profile_due) VALUES ('$tkn',$user,'$due')";
        //echo $query;
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function getTokenToDelete($token,$user){
        $database=new Database;
        $db=$database->connect();
        $due=date("Y-m-d H:i:s",strtotime("now"));
        $query="SELECT f_avi_token_delete_profile_token token, f_avi_token_delete_profile_profile profile, f_avi_token_delete_profile_registered inicio, f_avi_token_delete_profile_due fin, f_avi_token_delete_profile_status status FROM f_avi_token_delete_profile WHERE f_avi_token_delete_profile_token='$token' AND f_avi_token_delete_profile_profile=$user AND f_avi_token_delete_profile_due>'$due' AND f_avi_token_delete_profile_used=0";
        //echo $query;
        $ret=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ( $row = $data->fetch_assoc() ) {
                    $ret=$row;
                }
            }           
        }
        $db->close();
        return $ret;
    }
    function getReasonToLeave(){
        $database=new Database;
        $db=$database->connect();
        $due=date("Y-m-d H:i:s",strtotime("now"));
        $query="SELECT c_avi_leaving_reason_id id, c_avi_leaving_reason_description razon FROM c_avi_leaving_reason WHERE c_avi_leaving_reason_status=1";
        //echo $query;
        $ret=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ( $row = $data->fetch_assoc() ) {
                    $ret[]=$row;
                }
            }           
        }
        $db->close();
        return $ret;
    }
    function updateTokenStatus($user,$token,$status){
        $database=new Database;
        $db=$database->connect();
        $due=date("Y-m-d H:i:s",strtotime("now")+3600);
        $query="UPDATE f_avi_token_delete_profile SET f_avi_token_delete_profile_status=$status WHERE f_avi_token_delete_profile_token='$token' AND f_avi_token_delete_profile_profile=$user";
        //echo $query;
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function disableUser($user){
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE o_avi_user SET o_avi_user_status=2 WHERE o_avi_user_id=$user";
        //echo $query;
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function uncofirmUser($user){
        $database=new Database;
        $db=$database->connect();
        $query="UPDATE o_avi_user SET o_avi_user_status=3 WHERE o_avi_user_id=$user";
        //echo $query;
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    function desableToken($tkn, $user, $reasons=null){
        $database=new Database;
        $db=$database->connect();
        $used=date("Y-m-d H:i:s",strtotime("now"));
        $query="UPDATE f_avi_token_delete_profile SET f_avi_token_delete_profile_used=1, f_avi_token_delete_profile_reasons='$reasons', f_avi_token_delete_profile_used_time='$used' WHERE f_avi_token_delete_profile_token='$tkn' ANd f_avi_token_delete_profile_profile=$user";
        //echo $query;
        $ret=false;
        if($db->query($query)){
            $ret=true;
        }
        $db->close();
        return $ret;
    }
    private function enableAccount($idUser){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT o_avi_user_status status FROM o_avi_user WHERE o_avi_user_id=$idUser";
        $status=0;
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $status=$row["status"];
                }
            }
        }
        $resp=false;
        if($status==2){
            if($this->deleteTokenDeleteProfile($idUser))
            {
                $query="UPDATE o_avi_user SET o_avi_user_status=3 WHERE o_avi_user_id=$idUser";
                if($db->query($query)){
                    $resp=true;
                }
            }
        }
        $db->close();
        return $resp;
    }
    private function deleteTokenDeleteProfile($idUser)
    {
        $database=new Database;
        $db=$database->connect();
        $query="DELETE FROM f_avi_token_delete_profile WHERE f_avi_token_delete_profile_profile='$idUser';";
        if($db->query($query)){
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function sendDeleteMsg($status,$user, $eMailTo)
    {
        
        ini_set("display_errors",0);
        require ($_SERVER['DOCUMENT_ROOT']) . '/libraries/phpmailer/vendor/autoload.php';
        $mail = new PHPMailer();
        $resp=null;
        $mail->SMTPDebug = 0;
        $mail->isSendMail();
        $mail->Host = 'smtp.1and1.mx';
        $mail->SMTPAuth = true;
        $mail->Username = 'juan.gonzalez@skytel.com.mx';
        $mail->Password = '52753689';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; 
        $mail->setFrom('noreply@avicars.app', 'apoyovial');
        $mail->addAddress($eMailTo, $user);
        $mail->isHTML(true);
        $mail->Subject='Tu cuenta AVIcars se ha eliminado';
        $url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER["SERVER_NAME"];
        $html=file_get_contents($_SERVER['DOCUMENT_ROOT']."/html/mailDeletedProfile.html");
        $html=str_replace("--url--", $url, $html);
        $html=str_replace("--nombre--",$user, $html);
        if($status==2)
        {
            $mensaje.="Para volver a activar tu cuenta s&oacute;lo necesitas ingresar de nuevo.";
        }
        elseif($status==3)
        {
            $mensaje.="<p>Durante los pr&oacute;ximos 15 d&iacute;as tu cuenta ser&aacute; borrada definitivamente a menos que ingreses de nuevo.</p>
            <p>Te enviaremos un correo de verificaci&oacute;n cuando tu cuenta se borre completamente.</p>";
        }
        else
        {
            $mensaje.="Tu cuenta de AVIcars ha sido borrada completamente.";
        }
        $html=str_replace("--mensaje--",$mensaje, $html);
        $mail->msgHTML($html);
        if($mail->send())
        {
            return true;
        }
        return false;
    }
    function getDeleteCompleteProfile()
    {
        $database=new Database;
        $db=$database->connect();
        $query="SELECT f_avi_token_delete_profile_reasons reasons ,f_avi_token_delete_profile_profile profile FROM f_avi_token_delete_profile WHERE f_avi_token_delete_profile_registered<(NOW() - INTERVAL 15 DAY) AND f_avi_token_delete_profile_used=1 AND f_avi_token_delete_profile_status=3";
        $ret=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ( $row = $data->fetch_assoc() ) {
                    $ret[]=$row;
                }
            }           
        }
        $db->close();
        return $ret;
    }

    function insertUserDeletedInfo($reason,$idUser,$mail=null, $gender=null, $birthdate=null, $zipcode=null, $permanently=null)
    {
        $database=new Database;
        $db=$database->connect();
        $value="";
        $into="f_avi_user_deleted_leaving_reason, f_avi_user_deleted_user_id";
        $value.="'$reason', $idUser";
        if($mail)
        {
            $into.=", f_avi_user_deleted_email";
            $value.=", '$mail'";
        }
        if($gender)
        {
            $into.=", f_avi_user_deleted_gender";
            $value.=", $gender";
        }
        if($birthdate)
        {
            $into.=", f_avi_user_deleted_birthdate";
            $value.=", '$birthdate'";
        }
        if($zipcode)
        {
            $into.=", f_avi_user_deleted_zipcode";
            $value.=", $zipcode";
        }
        if($permanently)
        {
            $into.=", f_avi_user_deleted_permanently";
            $value.=", 1";
        }
        $query="INSERT INTO f_avi_user_deleted($into) VALUES ($value)";
        $resp=false;
        if($db->query($query))
        {
            $resp=true;
        }
        $db->close();
        return $resp;
    }
    function getUserConfiguration($user){
        $database=new Database;
        $db=$database->connect();
        $query="SELECT a_avi_user_configuration_user user, a_avi_user_configuration_mails mails 
                FROM  a_avi_user_configuration 
                WHERE a_avi_user_configuration_user=$user";
        $configuration=array();
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    $configuration=$row;
                }
            }
        }
        $db->close();
        if(!isset($configuration["mails"])){
            $configuration["mails"]="WyIxIiwiMiIsIjMiLCI0IiwiNSIsIjYiLCI3IiwiMTEiLCIxMiIsIjEzIiwiMTQiXQ==";
            $configuration["exists"]=false;
        }
        else{
            $configuration["exists"]=true;
        }
        return $configuration;
    }
    function saveConfigurationMails($user,$mailArray){
        $database=new Database;
        $db=$database->connect();
        $mails=base64_encode(json_encode($mailArray));
        $configuration=$this->getUserConfiguration($user);
        if(!$configuration["exists"]){
            $query="INSERT INTO a_avi_user_configuration (a_avi_user_configuration_user, a_avi_user_configuration_mails)  VALUES ($user, '$mails')";
        }
        else{
            $query="UPDATE a_avi_user_configuration SET a_avi_user_configuration_mails='$mails' WHERE a_avi_user_configuration_user=$user";
        }
        
        $return=false;
        if($db->query($query)){
            $return=true;
        }
        $db->close();
        return $return;
    }
    function isNewNameOrMail($id,$mail,$user){
        $database=new Database;
        $db=$database->connect();
        $changed=false;
        $query="SELECT o_avi_user_username, o_avi_user_email FROM o_avi_user WHERE o_avi_user_id=$id";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    if($mail!=$row["o_avi_user_email"]){
                        $changed=true;
                    }
                    if($user!=$row["o_avi_user_username"]){
                        $changed=true;
                    }
                }
            }
        }
        $db->close();
        return $changed;
    }
    function isNewMail($id,$mail){
        $database=new Database;
        $db=$database->connect();
        $changed=false;
        $query="SELECT o_avi_user_email FROM o_avi_user WHERE o_avi_user_id=$id";
        if($data=$db->query($query)){
            if($data->num_rows>0){
                while ($row=$data->fetch_assoc()) {
                    if($mail!=$row["o_avi_user_email"]){
                        $changed=true;
                    }
                }
            }
        }
        $db->close();
        return $changed;
    }
    function UsersRecomended($user){
        $database=new Database;
        $db=$database->connect();
        $notAllowedUsers=$this->notAccesibleUsers($user);
        $notin="0";
        foreach ($notAllowedUsers as $u => $us) {
            $notin.=",$us";
        }
        $sugerencias=array();
        $query="SELECT OAU.o_avi_user_id id, OAU.o_avi_user_username username, OAUD.o_avi_userdetail_name name, OAUD.o_avi_userdetail_last_name lastname, AAUP.a_avi_user_perfil_avatar avatar, AAUP.a_avi_user_perfil_cover cover
                FROM o_avi_user OAU
                LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
                LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=OAU.o_avi_user_id
                WHERE OAU.o_avi_user_id <> $user AND OAU.o_avi_user_id NOT IN (SELECT a_user_following_user_id FROM a_user_follow_user WHERE a_user_follower_user_id=$user) AND AAUP.a_avi_user_perfil_privacy in (1,2) AND OAU.o_avi_user_id NOT IN ($notin)
                ORDER BY AAUP.a_avi_user_perfil_privacy, RAND()
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
}
