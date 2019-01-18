<?php

/**
 * @Author: erikfer94
 * @Date:   2019-01-10 14:02:05
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2019-01-10 15:37:17
 */
session_start();

if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');
	exit();
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();

$resp=array();

$Garage=new Garage;
$allGaragesMS = $Garage->account($_SESSION["iduser"]);
$garageColaboradorMS = $Garage->colaboratingGarage($_SESSION["iduser"]); ?>
<div class="form-group selectdiv col-xs-12" id="saveCarInGarageModalSeguros">
	<label class="control-label">En qu&eacute; garage quieres guardar el auto?</label>
	<select class="form-control form-style" id="garageNewCarSeguros" name="garage">
		<?php 
		foreach ($allGaragesMS as $keyAllGarages => $someOfAllGarage) 
		{
			$coder->encode($someOfAllGarage["idAccount"]);
			$codeGarage=$coder->encoded;
			?>
			<option class='visible' value='<?= $codeGarage?>' ><?=$someOfAllGarage["nameAccount"]?></option>
			<?php
		}
		foreach ($garageColaboradorMS as $keyColaborating => $colaborating) {
			if($colaborating["nivel"]<3){
				$coder->encode($colaborating["idAccount"]);
				$codeGarage=$coder->encoded;
				?>
				<option class='visible' value='<?= $codeGarage?>' ><?=$colaborating["nameGarage"]?> de <?=$colaborating["ownerName"]?> <?=$colaborating["ownerLastName"]?></option>
				<?php
			}
		}
		?>
		<option class="visible" value="0">Nuevo Garage</option>								
	</select>
</div>