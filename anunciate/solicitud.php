<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-24 09:10:29
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:37:44
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';

$dataSend="";
$dataSend.="phone_mobile=".urlencode($_POST["phone_mobile"]);
$dataSend.="&email1=".urlencode($_POST["email1"]);
$dataSend.="&first_name=".urlencode($_POST["first_name"]);
$dataSend.="&last_name=".urlencode($_POST["last_name"]);
$dataSend.="&website=".urlencode($_POST["website"]);
$dataSend.="&department=".urlencode($_POST["department"]);
$dataSend.="&description=".urlencode($_POST["description"]);
$dataSend.="&campaign_id=a93b800b-5cda-c512-9d79-5ba28fc757f7";
$dataSend.="&assigned_user_id=c87c3bdb-11ec-fbde-b9b8-58b6bcd15f01";
$dataSend.="&lead_source=AVI cars";
$curl=curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => "http://crm.infosite.com.mx//index.php?entryPoint=WebToLeadCapture",
	CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_SSL_VERIFYPEER => 0,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => $dataSend,
	CURLOPT_HTTPHEADER => array(
			"Content-Type" => "application/x-www-form-urlencoded"
		)
	)
);
curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);
//echo $error;