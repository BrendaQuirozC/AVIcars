<?php

/**
 * @Author: erikfer94
 * @Date:   2018-11-15 12:02:22
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-16 12:04:25
 */

session_start();
$user=$_SESSION["iduser"];
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Instancia=new Instancia;
$userData=$Instancia->getUserBasic($user);
$garages=$Instancia->getAllSharablesGaragesByUser($user);
$cars=$Instancia->getAllSharablesCarsByUser($user);
?>
<div class="row">
	<div class="col-xs-12 form-group search">
		<input type="text" name="search" class="form-control form-style" placeholder="Buscar..." onkeyup="searchToShare($(this));">
	</div>
</div>
<form class="sendDataToShare">
	<div class="row">
		<p>En mi perfil</p>
		<div class="col-xs-12 optionShare">
			<img src="<?= $userData["img"] ?>" onclick="chooseToShare($(this))">
			<span><?= ($userData["name"]=="") ? '/img/icons/avatar1.png' : $userData["name"] ?></span>
			<input type="checkbox" class="hidden" name="share[p][]" value="<?= $coder->encode($userData["id"])?>">
		</div>
	</div>
	<hr>
	<div class="row">
		<p>En mis garages</p>
		<?php
		foreach ($garages as $g => $garage) { ?>
			<div class="col-xs-3 optionShare">
				<img src="<?= ($garage["avatar"]=="") ? '/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg' : $garage["avatar"] ?>" onclick="chooseToShare($(this))">
				<span><?= $garage["name"] ?></span>
				<input type="checkbox" class="hidden" name="share[g][]" value="<?= $coder->encode($garage["id"])?>">
			</div>
		<?php 
		} ?>
	</div>
	<div class="row">
		<p>En mis autos</p>
		<?php
		foreach ($cars as $c => $car) { ?>
			<div class="col-xs-3 optionShare">
				<img src="<?= ($car["img"]=="") ? '/img/noimage.png' : $car["img"] ?>" onclick="chooseToShare($(this))">
				<span><?= $car["alias"] ?></span>
				<input type="checkbox" class="hidden" name="share[c][]"  value="<?= $coder->encode($car["id"])?>">
			</div>
		<?php 
		} ?>
	</div>
</form>