<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-20 17:10:30
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-23 12:45:38
 */
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/libraries/phpmailer/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/usuario.php";
class Notificacion{
	function addNotification($typeNotification, $text=null, $remitente, $destinatario, $garage=null, $auto=null, $link=null, $seen=0 ,$mail=false)
	{
		$database=new Database;
        $db=$database->connect();
        $insert="f_avi_notification_type_id, f_avi_notification_sender_user_id, f_avi_notification_addresses_user_id, f_avi_notification_link";
        $values="'$typeNotification','$remitente','$destinatario','$link'";
		if($text)
		{
			$text=substr($text, 0,80);
			$insert.=", f_avi_notification_text";
			$values.=", '$text'";
		}
		if($garage)
		{
			$insert.=", f_avi_notification_account_id";
			$values.=", '$garage'";
		}
		if($auto)
		{
			$insert.=", f_avi_notification_car_id";
			$values.=", '$auto'";
		}
		$query="INSERT INTO  f_avi_notification($insert) VALUES ($values);";
		if($result=$db->query($query))
		{
			$result=$db->insert_id;
			if($mail){
				$mailType=$this->getMailType($typeNotification);
				if($mailType>0){
					if($this->isAllowedMail($destinatario,$mailType)){
						$this->sendMailNotification($result);
					}
				}
				
			}
		}
		$db->close();
		
		return $result;
	}

	function getNotificationByIdUser($destinatario, $start=0)
	{
		$start*=10;
		$usuario=new Usuario;
		$notAllowedUsers=$usuario->notAccesibleUsers($destinatario);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$database=new Database;
        $db=$database->connect();
        $notification= array();
        $query= "SELECT 
				FAN.f_avi_notification_id idNotification,
				FAN.f_avi_notification_type_id idTipo,
				FAN.f_avi_notification_text texto, 
				FAN.f_avi_notification_link url,
				FAN.f_avi_notification_time fecha,
				FAN.f_avi_notification_seen visto,
				FAN.f_avi_notification_clicked clicked,
				FAN.f_avi_notification_sender_user_id idRemitente,
				FAN.f_avi_notification_addresses_user_id idDestinatario,
				FAN.f_avi_notification_account_id idGarage,
				FAN.f_avi_notification_car_id idAuto,
				CANT.c_avi_notification_type_description tipo,
                AAUP.a_avi_user_perfil_avatar avatarRemitente,
                OAU.o_avi_userdetail_name userName,
                OAU.o_avi_userdetail_last_name userLastName,
                (SELECT a_user_follow_acepted FROM a_user_follow_user WHERE a_user_following_user_id='$destinatario' AND a_user_follower_user_id=FAN.f_avi_notification_sender_user_id) followed
				FROM f_avi_notification FAN 
				LEFT JOIN c_avi_notification_type CANT ON  CANT.c_avi_notification_type_id = FAN.f_avi_notification_type_id
				LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=FAN.f_avi_notification_sender_user_id
				LEFT JOIN o_avi_userdetail OAU ON OAU.o_avi_userdetail_id_user=FAN.f_avi_notification_sender_user_id
				LEFT JOIN o_avi_user ON o_avi_user.o_avi_user_id=FAN.f_avi_notification_sender_user_id
				WHERE f_avi_notification_addresses_user_id='$destinatario' AND o_avi_user.o_avi_user_status in (1,3)
				AND o_avi_user.o_avi_user_id NOT IN ($notin)
				ORDER BY fecha DESC
				LIMIT $start, 10";
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$notification[]=$row;
				}
			}
		}
		$db->close();
		return $notification;
	}
	function getNotificationById($idNotification)
	{
		$database=new Database;
        $db=$database->connect();
        $notification= array();
        $query= "SELECT 
				FAN.f_avi_notification_id idNotification,
				FAN.f_avi_notification_type_id idTipo,
				FAN.f_avi_notification_text texto, 
				FAN.f_avi_notification_link url,
				FAN.f_avi_notification_time fecha,
				FAN.f_avi_notification_seen visto,
				FAN.f_avi_notification_clicked clicked,
				FAN.f_avi_notification_sender_user_id idRemitente,
				FAN.f_avi_notification_addresses_user_id idDestinatario,
				FAN.f_avi_notification_account_id idGarage,
				FAN.f_avi_notification_car_id idAuto,
				CANT.c_avi_notification_type_description tipo,
                AAUP.a_avi_user_perfil_avatar avatarRemitente,
                OAU.o_avi_user_username usernameRemitente,
                OAUR.o_avi_user_email email,
                OAUR.o_avi_user_username destinatario,
                CONCAT(OAUDR.o_avi_userdetail_name,' ',OAUDR.o_avi_userdetail_last_name) As name_destinatario,
                CONCAT(OAUD.o_avi_userdetail_name,' ',OAUD.o_avi_userdetail_last_name) As name_remitente
				FROM f_avi_notification FAN 
				LEFT JOIN c_avi_notification_type CANT ON  CANT.c_avi_notification_type_id = FAN.f_avi_notification_type_id
				LEFT JOIN a_avi_user_perfil AAUP ON AAUP.a_avi_user_id=FAN.f_avi_notification_sender_user_id
				LEFT JOIN o_avi_user OAU ON OAU.o_avi_user_id=FAN.f_avi_notification_sender_user_id
				LEFT JOIN o_avi_user OAUR ON OAUR.o_avi_user_id=FAN.f_avi_notification_addresses_user_id
				LEFT JOIN o_avi_userdetail OAUDR ON OAUDR.o_avi_userdetail_id_user=OAUR.o_avi_user_id
				LEFT JOIN o_avi_userdetail OAUD ON OAUD.o_avi_userdetail_id_user=OAU.o_avi_user_id
				WHERE FAN.f_avi_notification_id=$idNotification";
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$notification=$row;
				}
			}
		}
		$db->close();
		return $notification;
	}
	function getNotSeenNotifications($destinatario)
	{
		$usuario=new Usuario;
		$notAllowedUsers=$usuario->notAccesibleUsers($destinatario);
        $notin="0";
        foreach ($notAllowedUsers as $u => $userBlocked) {
            $notin.=",$userBlocked";
        }
		$database=new Database;
        $db=$database->connect();
        $cantidad=0;
        $query= "SELECT f_avi_notification_addresses_user_id, count(f_avi_notification_id) novistas
				FROM f_avi_notification FAN 
				WHERE f_avi_notification_addresses_user_id='$destinatario' AND f_avi_notification_seen=1 AND f_avi_notification_sender_user_id NOT IN ($notin)
				GROUP BY f_avi_notification_addresses_user_id";
		if($result=$db->query($query))
		{
			if($result->num_rows>0){
				while ($row=$result->fetch_assoc()) {
					$cantidad=$row["novistas"];
				}
			}
		}
		$db->close();
		return $cantidad;
	}
	function seeNotifications($user){
		$database=new Database;
        $db=$database->connect();
        $query="UPDATE f_avi_notification SET f_avi_notification_seen=0 WHERE f_avi_notification_addresses_user_id=$user";
        $result=false;
        if($db->query($query)){
        	$result=true;
        }
        $db->close();
        return $result;
	}

	function deleteIfUndone(){
		$database = new Database;
		$db=$database -> connect();
		$query ="DELETE FROM f_avi_notification WHERE f_";
	}
	function sendMailNotification($notification){
		$infoNot=$this->getNotificationById($notification);
		$asunto=$infoNot["name_remitente"]." ".$infoNot["tipo"];
		$texto=$infoNot["name_remitente"]." ".$infoNot["tipo"];
        $url=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$infoNot["url"];
        $foto= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$infoNot["avatarRemitente"];
		$html=file_get_contents($_SERVER['DOCUMENT_ROOT']."/html/mailNotification.html");
		$html=str_replace("--url--", $url, $html);
		$html=str_replace("--nombre--", $infoNot["name_destinatario"], $html);
		$html=str_replace("--mensaje--", $texto, $html);
		$html=str_replace("--foto--", $foto, $html);
		$acentos=array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&ntilde;","&ntilde;");
		$acentosto=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ");
		$asunto=str_replace($acentos, $acentosto, $asunto);
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSendMail();
        $mail->Host = 'smtp.1and1.mx';
        $mail->SMTPAuth = true;
        $mail->Username = 'juan.gonzalez@skytel.com.mx';
        $mail->Password = '52753689';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; 
        $mail->setFrom('noreply@avicars.app', 'Avi cars');
        $mail->addAddress($infoNot["email"]);
        $mail->isHTML(true);
        $mail->Subject = $asunto ;
        $mail->msgHTML($html);
        if($mail->send())
        {
            return true;
        }
        return false;
	}
	function getMailsConfirmation(){
		$database = new Database;
		$db=$database -> connect();
		$query="SELECT c_avi_mail_actions_id id, c_avi_mail_actions_description descripcion, c_avi_mail_actions_status estatus FROM c_avi_mail_actions WHERE c_avi_mail_actions_status=1";
		$actions=array();
		if($data=$db->query($query)){
			if($data->num_rows>0){
				while ($row=$data->fetch_assoc()) {
					$actions[]=$row;
				}
			}
		}
		$db->close();
		return $actions;
	}
	function getMailType($typeNotification){
		$mailType=0;
		$database = new Database;
		$db=$database -> connect();
		$query="SELECT c_avi_notification_type_mail mail FROM c_avi_notification_type WHERE c_avi_notification_type_id=$typeNotification";
		if($data=$db->query($query)){
			if($data->num_rows>0){
				while ($row=$data->fetch_assoc()) {
					$mailType=$row["mail"];
				}
			}
		}
		$db->close();
		return $mailType;
	}
	function isAllowedMail($user,$mailType){
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
        $isAllowed=false;
        if(empty($configuration))
        {
        	$configuration["mails"]="WyIxIiwiMiIsIjMiLCI0IiwiNSIsIjYiLCI3IiwiMTEiLCIxMiIsIjEzIiwiMTQiLCIyNCIsIjI1IiwiMjYiXQ==";
        }
        if(in_array($mailType, json_decode(base64_decode($configuration["mails"])))){
        	$isAllowed=true;
        }
        return $isAllowed;
	}
}
