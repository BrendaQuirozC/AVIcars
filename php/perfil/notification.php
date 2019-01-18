<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-08 17:47:03
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 10:14:54
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/notification/Notification.php';
session_start();
$notification=new Notificacion;
if(!isset($_SESSION["iduser"])){
	$total=-1;
}
else{
	$total=$notification->getNotSeenNotifications($_SESSION["iduser"]);	
}

echo "data: {$total}\n\n";
flush();

?>