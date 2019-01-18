<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-05 16:28:27
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:53:11
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["garage"])){
	header("Location: /");
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();

$garageEncoded=$_GET["garage"];
$coder->decode($_GET["garage"]);
$_GET["garage"]=$coder->toEncode;
$Garage = new Garage;
$detalles = array();
if(isset($_GET["garage"]))
{
	$Usuario = new Usuario;
	$garage = $Garage ->accountById($_GET["garage"]);
    $imgPerfil = $Usuario->getImgPerfil($garage["user"]);
    $infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
	$detalles = $Garage -> getUserdetail($garage["user"]);
	$cuenta = $garage["user"];
	$privacyToChange=json_encode(array("tipo" =>2,"privacy"=>$_GET["garage"]));
	$coder->encode($garage["user"]);
	$cuentaEncoded=$coder->encoded;
	$garage["user"]=$coder->toEncode;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
	if(!empty($detalles))
	{
		if(!$owner && ((isset($garage["privacidad"])) ? $garage["privacidad"] : 1)==3){ ?>
		<div class="content">
			<div class="row" style="margin: 80px 0px 25px 0px;">
				<h3 class="text-center">
					Esta p&aacute;gina no est&aacute; disponible
				</h3>
			</div>
		</div>
		<?php
		}
		else
		{
			$instancia = array();
			$active=false;
			$extrasGarage = $Garage->getGarageExtras($_GET["garage"]);
			$Like = new Like;
			include_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/headerGarage.php";
		?>
			<div class="content">
				<table class="table">
					<tr>
						<th colspan="2"><h3>Descripci&oacute;n</h3></th>
					</tr>
					<tr>
						<td class="wordwrap"><?= isset($extrasGarage["description"]) ? $extrasGarage["description"]: ""?></td>
					</tr>
					<tr>
						<th colspan="2"><h3>Informaci&oacute;n B&aacute;sica</h3></th>
					</tr>
					<tr>
						<td>Uso del garage</td>
						<td><?=isset($extrasGarage["uso"]) ? $extrasGarage["uso"]: "Sin especificar"?></td>
					</tr>
					<tr>
						<td>Localidad</td>
						<td><?=isset($extrasGarage["street"]) ? $extrasGarage["street"]: "Sin especificar"?></td>
					</tr>
				</table>
				<?php if ($detalles["o_avi_userdetail_id_user"] != $_SESSION["iduser"]) { ?>
					<a class="pointer infoReport" data-perfil="<?=$detalles['o_avi_userdetail_id_user']?>" data-garage="<?=$_GET["garage"]?>" onclick="modalToReport($(this))">Reportar Garage</a>
				<?php } ?>
					<a class="pointer infoReport" data-perfil="<?=$detalles['o_avi_userdetail_id_user']?>" data-garage="<?=$_GET["garage"]?>" onclick="modalToReport($(this))">Reportar Garage</a>
			</div>
		<?php
		}
	}
}
else
{
	?>
	<script> location.replace("/"); </script>
	<div class="content">
		<div class="row" style="margin: 80px 0px 25px 0px;">
			<h3 class="text-center">
				Esta p&aacute;gina no est&aacute; disponible
			</h3>
		</div>
	</div>
	<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
?>