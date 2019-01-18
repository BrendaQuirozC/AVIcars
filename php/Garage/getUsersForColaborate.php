<?php
/**
 * @Author: erikfer94
 * @Date:   2018-10-10 15:14:00
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-17 13:52:31
 */
session_start();
if(empty($_SESSION)){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');	
	exit;
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Garage = new Garage;
$coder->decode($_POST["g"]);
$garageDecoded=$coder->toEncode;
$garageData = $Garage ->accountById($garageDecoded);
if($garageData["user"]!=$_SESSION["iduser"] && !$Garage->getAUserAccount($_SESSION["iduser"], $garageDecoded,1))
{
	header('HTTP/1.0 403 Forbidden');
	exit;
}
$colaboradores=$Garage->getColaborators($garageDecoded);
$alreadyColaborate=array_keys($colaboradores);
$users=$Garage->getPotentialColaborators($_POST["t"],$garageDecoded, $garageData["user"]);
?>
<ul>
	<?php
	$notIn=json_decode($_POST["p"],true);
	foreach ($users as $u => $user) { 
		$coder->encode($user["iduser"]);
		$userPotencialCoded=$coder->encoded;
		if(!in_array($userPotencialCoded, $notIn))
		{ ?>
		<li data-u="<?= $userPotencialCoded?>" onclick="addColaborator($(this));"><img src="<?= ($user["img"]!="") ? $user["img"] : "/img/icons/avatar1.png"?>"><span><?= $user["name"]?> <?= $user["lastname"]?></span></li>
		<?php 
		}
	}
	if(empty($users))
	{ ?>
		<li class="empty"><i>No hay resultados</i></li>
		<?php
	} ?>
</ul>