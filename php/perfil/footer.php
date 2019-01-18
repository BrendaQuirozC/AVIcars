<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 16/02/2018
 * Time: 01:56 PM
 */

?>

<div id="flag-reload" class="loader">
	<img id="wheel-reload" src="/img/me_enllanta_256px.gif" alt="">
    <!--<div id="reload-band" class="sk-cube-grid">
        <div class="sk-cube sk-cube1"></div>
        <div class="sk-cube sk-cube2"></div>
        <div class="sk-cube sk-cube3"></div>
        <div class="sk-cube sk-cube4"></div>
        <div class="sk-cube sk-cube5"></div>
        <div class="sk-cube sk-cube6"></div>
        <div class="sk-cube sk-cube7"></div>
        <div class="sk-cube sk-cube8"></div>
        <div class="sk-cube sk-cube9"></div>
    </div>-->
</div>
<?php
if(!empty($_SESSION["iduser"]))
{
	require_once  $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
	require_once  $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/insurance.php";
	$country=new Country;
	$Insurance=new Insurance;
	$GarageInFoot=new Garage;
	$carriers=$Insurance->getAllInsuranceCarriers();
	$phoneCodes=$country->getPhoneCodes();
	$allGarages = $GarageInFoot->account($_SESSION["iduser"]);
	$allGaragesNoVerified = $GarageInFoot->accountNoVerifiedOrPending($_SESSION["iduser"],0);
	$allGaragesPending = $GarageInFoot->accountNoVerifiedOrPending($_SESSION["iduser"],3);
	$numAutos = $GarageInFoot->countCarsPerUser($_SESSION["iduser"]);
	$garageColaborador = $GarageInFoot->colaboratingGarage($_SESSION["iduser"]);
	$allAutos=$GarageInFoot->getCarsEditableByUser($_SESSION["iduser"],true,false);
	$allAutosNoVerified=$GarageInFoot->getCarsNoVerifiedOrPendingEditableByUser($_SESSION["iduser"],true,false,0);
	$allAutosPending=$GarageInFoot->getCarsNoVerifiedOrPendingEditableByUser($_SESSION["iduser"],true,false,3);
	$profileUser=$GarageInFoot->getProfile($_SESSION["iduser"],0);
	$profileUserPending=$GarageInFoot->getProfile($_SESSION["iduser"],3);
	$marcas=Auto::getMarcas();
	$submarcas=Auto::getSubMarcas();
	$modelos=Auto::getModels();
	$versiones=Auto::knowVersion();
	$esColaborador=false;
	$privacyToChangeNoJson=json_decode($privacyToChange,true);
	if($privacyToChangeNoJson["tipo"]==2){
		foreach ($garageColaborador as $keyColaborating => $colaborating) {
			if($colaborating["idAccount"]==$garage["idAccount"]){
				$esColaborador=true;
			}
		}
	}
	$userEncoded = $coder->encode($_SESSION["iduser"]);
	$currAuto=isset($_GET["auto"]) ? $_GET["auto"] : 0;
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/report.php";
	$Report = new Report;
	/*foreach ($allGarages as $ag => $agrge) {
		$allAutos=array_merge($allAutos,$GarageInFoot->accountInstancia($agrge["idAccount"],true,false));
	}*/
?>
	<div class="multiple-action-buttons">
		<?php if(Usuario::getStatusUser($_SESSION["iduser"])!=3){ ?>
		<div class="action-buttons">
			<button class="action-button action-1" id="newGarageFloat" onclick='create($(this))'></button>
			<span class="txt-action-button txt-action-1">Nuevo Garage</span>
			<button class="action-button action-2" id="newAutoFloat" onclick="newCarModal($(this))"></button>
			<span class="txt-action-button txt-action-2">Nuevo Auto</span>
			<button class="action-button action-3" id="newMoneyFloat" onclick="moneyCar($(this))"></button>
			<span class="txt-action-button txt-action-3">Servicios</span>
			<button class="action-button action-4" id="newPublicationFloat"></button>
			<span class="txt-action-button txt-action-4">Publicar</span>
		</div>
		<?php } ?>
		<button class="flotante add <?= (Usuario::getStatusUser($_SESSION["iduser"])==3) ? "noconfirm" : "" ?>" id="show-buttons"></button>	
	</div>
	<?php if(Usuario::getStatusUser($_SESSION["iduser"])==3){ ?>

	<div class='modal fade' id='confirmMailModal' role='dialog'> 
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Confirma tu correo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<p class="col-xs-12">Para poder hacer uso completo de AVI cars debes confirmar tu correo.</p>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" id="closeModalMail">Cerrar</button>
					| <button type="button" class="btn modal-btns" id="sendMailModal" data-dismiss="modal" >Confirmar</button>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div id="doPublication" class="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<div>
						<ul class="title-autor">
							<li>Publicar en:</li>
							<li>Autor:</li>
						</ul>
					</div>
					<?php 
					
					if($privacyToChangeNoJson["tipo"]==1)
					{
						//echo "es perfil";
						?>
						<div class="modal-publish col-xs-6 text-left">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<?php if($owner){ ?>
							<img src="/img/icons/down.png" class="navigation-icon sub-navigation-icon pointer" onclick="showGaragesToPublish($(this))"/>
							<?php } ?>
							<span id="nombreGarage">
								<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
							</span>
							<ul class="navigation-list" id="listGarages"> </ul>
						</div>
						<div class="modal-publish col-xs-6 text-right">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
						</div>
						<?php
					}
					elseif($privacyToChangeNoJson["tipo"]==2)
					{
						$privacidad=(isset($garage["privacidad"])) ? $garage["privacidad"] : 1;
						if($owner||$esColaborador)
						{
							$coder->encode($garage["idAccount"]);
							$garageCodedPost=$coder->encoded;
						?>
						<div class="modal-publish col-xs-6 text-left">
							<img class="img-profile publishImg" src="<?= (!isset($extrasGarage["avatar"]) || $extrasGarage["avatar"]=="") ? "/img/icons/avatar1.png" : $extrasGarage["avatar"] ?>">
							<span id="nombreGarage" data-garagetopublish="<?= $garageCodedPost?>"><?= $garage["nameAccount"]?></span>
						</div>
						<div class="modal-publish col-xs-6 text-right">
							<img class="img-profile publishImg" src="<?= (!isset($extrasGarage["avatar"]) || $extrasGarage["avatar"]=="") ? "/img/icons/avatar1.png" : $extrasGarage["avatar"] ?>">
							<img src="/img/icons/down.png" class="navigation-icon sub-navigation-icon pointer" onclick="listUsrs($(this))">
							<input id="garage" type="hidden" value="<?= $garageCodedPost?>">
							<ul class="ul-complement navigation-list" id="listUsuarios">
								<li>
									<a class="whoPublish pointer" data-garage='<?= $garageCodedPost?>'>
										<?php if(isset($extrasGarage["avatar"])){?>
											<img src="<?=$extrasGarage["avatar"]?>" alt="imagen-<?= $garage["nameAccount"]?>">
										<?php }
										else{ ?>
											<img src="/img/icons/avatar1.png" alt="imagen-<?= $garage["nameAccount"]?>">
										<?php } ?>
										<p>
											<?= $garage["nameAccount"]?>
										</p>
									</a>
								</li>
								<li>
									<a class="whoPublish pointer" data-usr='<?=$_SESSION["usertkn"]?>'>
										<?php if(isset($imgPerfilOwnCuenta["avatar"])){?>
											<img src="<?=$imgPerfilOwnCuenta["avatar"]?>" alt="imagen-<?= $_SESSION["iduser"]?>">
										<?php }
										else{ ?>
											<img src="/img/icons/avatar1.png" alt="imagen-<?= $_SESSION["iduser"]?>">
										<?php } ?>
										<p>
											<?= $detallesOwner["o_avi_userdetail_name"]?>&ensp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
										</p>
									</a>
								</li>
							</ul>
							<span id="nombreAutor"><?= $garage["nameAccount"]?></span>
						</div>
						<?php
						}
						else
						{
						?>
						<div class="modal-publish col-xs-6 text-left">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<?php if($owner){ ?>
							<img src="/img/icons/down.png" class="navigation-icon sub-navigation-icon pointer" onclick="showGaragesToPublish($(this))"/>
							<?php } ?>
							<span id="nombreGarage">
								<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
							</span>
							<ul class="navigation-list" id="listGarages"> </ul>
						</div>
						<div class="modal-publish col-xs-6 text-right">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
						</div>
						<?php
						}
					}
					elseif($privacyToChangeNoJson["tipo"]==3)
					{
						if($owner||$colaboradorCont)
						{
							$coder->encode($garageContain[0]["o_avi_account_id"]);
							$garageCodedPost=$coder->encoded;
						?>
						<div class="modal-publish col-xs-6 text-left">
							<img class="img-profile publishImg"src="<?= ($notImage) ? $notImage : $imagenes[0]["a_avi_car_img_car"] ?>">
							<span id="nombreGarage" data-garagetopublish="<?= $garageCodedPost?>"><?= $garageContain[0]["i_avi_account_car_alias"]?></span>
						</div>
						<div class="modal-publish col-xs-6 text-right">
							<img class="img-profile publishImg" src="<?= !empty($extrasGarage) ? ($extrasGarage["avatar"]=="") ? "/img/icons/avatar1.png" : $extrasGarage["avatar"] : "/img/icons/avatar1.png" ?>">
							<img src="/img/icons/down.png" class="navigation-icon sub-navigation-icon pointer" onclick="listUsrs($(this))">
							<ul class="ul-complement navigation-list" id="listUsuarios">
								<li>
									<a class="whoPublish pointer" data-garage='<?= $garageCodedPost?>'>
										<?php if(isset($extrasGarage["avatar"])){?>
											<img src="<?=$extrasGarage["avatar"]?>" alt="imagen-<?= $garage["nameAccount"]?>">
										<?php }
										else{ ?>
											<img src="/img/icons/avatar1.png" alt="imagen-<?= $garage["nameAccount"]?>">
										<?php } ?>
										<p>
											<?= $garage["nameAccount"]?>
										</p>
									</a>
								</li>
								<li>
									<a class="whoPublish pointer" data-usr='<?=$_SESSION["usertkn"]?>'>
										<?php if(isset($imgPerfilOwnCuenta["avatar"])){?>
											<img src="<?=$imgPerfilOwnCuenta["avatar"]?>" alt="imagen-<?= $_SESSION["iduser"]?>">
										<?php }
										else{ ?>
											<img src="/img/icons/avatar1.png" alt="imagen-<?= $_SESSION["iduser"]?>">
										<?php } ?>
										<p><?= $detallesOwner["o_avi_userdetail_name"]?>&ensp;<?= $detallesOwner["o_avi_userdetail_last_name"]?></p>
									</a>
								</li>
							</ul>
							<span id="nombreAutor"><?= $garage["nameAccount"]?></span>
							<input id="garage" type="hidden" value="">
						</div>
					<?php
						}
						else
						{
						?>
						<div class="modal-publish col-xs-6 text-left">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<?php if($owner){ ?>
							<img src="/img/icons/down.png" class="navigation-icon sub-navigation-icon pointer" onclick="showGaragesToPublish($(this))"/>
							<?php } ?>			
							<span id="nombreGarage">
								<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
							</span>
							<ul class="navigation-list" id="listGarages"> </ul>
						</div>
						<div class="modal-publish col-xs-6 text-right">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
						</div>
						<?php
						} 
					}
					else{?>
						<div class="modal-publish col-xs-6 text-left">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<img src="/img/icons/down.png" class="navigation-icon sub-navigation-icon pointer" onclick="showGaragesToPublish($(this))"/>
							<span id="nombreGarage">
								<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
							</span>
							<ul class="navigation-list" id="listGarages"> </ul>
						</div>
						<div class="modal-publish col-xs-6 text-right">
							<img class="img-profile publishImg" src="<?= ($imgPerfilOwnCuenta["avatar"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $imgPerfilOwnCuenta["avatar"] ?>"/>
							<?= $detallesOwner["o_avi_userdetail_name"]?>&nbsp;<?= $detallesOwner["o_avi_userdetail_last_name"]?>
						</div>
					<?php } ?>
				</div>
				<div class="modal-body">
					<div class="publicar form-list">
						<textarea class="mention" id="publication" rows="5" placeholder="Comparte que est&aacute;s pensando..." onkeyup="getMentionedUser($(this));" data-u="<?= $userEncoded?>"></textarea>
						<!--<div class="result-list" id="potencialMencion">
			    			
			    		</div>-->
						<button onclick="addImage()" class="icon" id="iconImg"><i class="glyphicon glyphicon-picture"></i></button>
						<div class="remove" id="imgDrop">
		                	<form id="imgPublic" action="/php/perfil/publicacion/uploadImage.php" class="dropzone needsclick" method="post" enctype="multipart/form-data">

								<button class="icon" type="button" onclick="removeAllImages()">x</button>
		                		<div class="fallback">
								    <input name="file"  type="file" multiple />
							   	</div>
		                	</form>
	                	</div>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
					<button type="button" class="btn modal-btns" onclick="publish($(this));" disabled="">Publicar</button>
				</div>
			</div>
		</div>
	</div>
	<div id="editPublication" class="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Editar</h4>
				</div>
				<div class="modal-body">
					<div class="publicar">
						<textarea class="" id="republication" rows="5" placeholder="Comparte que est&aacute;s pensando..."></textarea>
						<!--<button onclick="addImage()" class="icon" id="iconImg"><i class="glyphicon glyphicon-picture"></i></button>
						<div class="remove" id="imgDrop">
		                	<form id="imgPublic" action="/php/perfil/publicacion/uploadImage.php" class="dropzone needsclick" method="post" enctype="multipart/form-data">

								<button class="icon" type="button" onclick="removeAllImages()">x</button>
		                		<div class="fallback">
								    <input name="file"  type="file" multiple />
							   	</div>
		                	</form>
	                	</div>-->
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" onclick="republication($(this));" disabled="">Publicar</button>
				</div>
			</div>
		</div>
	</div>
	<div class='modal fade' id='addCar' role='dialog'> 
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Crear un Auto</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<form id="nuevoAutoModal">
							<div class="form-group col-xs-12 selectdiv">
								<label class="control-label no-padding">Garage</label>
								<select class="form-control form-style" name="garage" id="garageCarModal">
								<?php 
								$actualGarage=(isset($garage["idAccount"])) ? $garage["idAccount"] : "";
								foreach ($allGarages as $keyAllGarages => $someOfAllGarage) 
								{
									$coder->encode($someOfAllGarage["idAccount"]);
									$codeGarage=$coder->encoded;
									$selected="";
									if($actualGarage==$someOfAllGarage["idAccount"]){
										$selected="selected";
									}
									?>
									<option class='visible' value='<?= $codeGarage?>' <?= $selected?>><?=$someOfAllGarage["nameAccount"]?></option>
									<?php
								}
								foreach ($garageColaborador as $keyColaborating => $colaborating) {
									if($colaborating["nivel"]<3){
										$coder->encode($colaborating["idAccount"]);
										$codeGarage=$coder->encoded;
										$selected="";
										if($actualGarage==$colaborating["idAccount"]){
											$selected="selected";
										}
										?>
										<option class='visible' value='<?= $codeGarage?>' <?= $selected?>><?=$colaborating["nameGarage"]?> de <?=$colaborating["ownerName"]?> <?=$colaborating["ownerLastName"]?></option>
										<?php
									}
								}
								?>
								</select>
								<label class="control-label no-padding hidden">Alias</label>
								<input class="form-control form-style hidden" type="text" name="alias" value="Mi Auto <?= $numAutos["numero"]?>" placeholder="El nombre de tu auto">
								<div id="extraFeatures" data-open="0">
									
								</div>
							</div>
						</form>
						<div class="editVehiclePhotos" id="editingPhotosCarModal">
							<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="photoCarModal">
								<div class="inline-logos col-xs-6">
									<?php
										$coder->encode($actualGarage);
										$actualGarageCoded=$coder->encoded;
										$coder->encode($allGarages[0]["idAccount"]);
									?>
									<label for="imagenAutoModal" class="carImgLabel">
										<img class="camara center-block" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
										<input type='hidden' name='garage' id='garageinsertcar' value='<?= ($actualGarage!="") ? $actualGarageCoded :  $coder->encoded ?>'/>
									</label>
									<input name="imagenAutoModal" id="imagenAutoModal" class="inputfile" type="file" onchange="addimgtempcar()" />
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" data-form="nuevoAutoModal" onclick="nuevoAuto($(this))">Crear</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade"  id='deletePublication' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Eliminar publicaci&oacute;n</h4>
				</div>
				<div class="modal-body">
					¿Seguro que quieres eliminar esta publicaci&oacute;n?
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" onclick="deletePublication($(this))">Eliminar</button>
				</div>
			</div>
		</div>
	</div> 
	<div class="modal fade" id="monetizar" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Servicios</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group selectdiv col-xs-12">
							<label class="control-label">Auto</label>
							<select class="form-control form-style" id="autoServicios">
								<option value="0">Selecciona un auto</option>
							<?php foreach ($allAutos as $a => $auto) { 
								$selected="";
								$coder->encode($auto["i_avi_account_car_id"]);
								$autoCodedSelect=$coder->encoded;
								if($currAuto==$auto["i_avi_account_car_id"]){
									$selected="selected";
								}
								?>
								<option <?= $selected ?> value="<?= $autoCodedSelect?>"><?= $auto["alias"]?>. Garage: <?= $auto["garageName"]?><?= (!$auto["propio"]) ? " de ".$auto["garageOwner"] : ""?></option>
							<?php } ?>
								<option value="-1">Agregar Auto</option>
							</select>
						</div>
						<div class="monetizar" id="listaMonetizar">
							<h5>Protegemos tu auto</h5>
							<button class="seguro" onclick="seguroModal()"><span>Seguros cobertura Amplia</span></button>
							<h5>Capitaliza</h5>
							<button class="prestamo"><span>Pr&eacute;stamo inmediato</span></button>
							<button class="compra" onclick="compraModal()"><span>Compra inmediata</span></button>
							<button class="anuncia" onclick="newCarAd()" ><span>Anuncia tu auto</span></button>
						</div>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="respuestaMonetizar" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Servicios</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<h5 class="text-center" id="responseTextMonetizar">En breve nos pondremos en contacto contigo</h5>
						<div class="img-response" id="responseImgMonetizar">
							
						</div>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalMovilidad" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Contrata Movilidad AIG con ApoyoVial</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<form id="formMovilidad">
							
							<div class="wizard" id="wizardMovilidad">
								<div class="wizard-steps">
									<ul>
										<li class="active" >
											<a href="#autoMovilidad" data-target="autoMovilidad">1</a>
										</li>
										<li> 
											<a href="#insuranceMovilidad" data-target="insuranceMovilidad">2</a>
										</li>
										<li>
											<a href="#personalMovilidad" data-target="personalMovilidad">3</a>
										</li>
									</ul>
								</div>
								<div class="wizard-body">
									<div id="autoMovilidad" class="active">
										<img class="leftPromo" src="/img/infotrafficaApoyoVial200px.png">
										<img class="rightPromo" src="/img/AIG200px.png">
										<p class="mainPromo">
											Por $1,999 MXN anuales extiende tu seguro y adquiere los siguientes beneficios:
										</p>
										<ul class="listPromo">
											<li>
												Reembolso de deducible en caso de p&eacute;rdida total por da&ntilde;o material.
											</li>
											<li>
												Da&ntilde;os a llantas y rines.
											</li>
											<li>
												Asistencia vial ilimitada.
											</li>
											<li>
												Auto sustituto hasta por 15 d&iacute;as.
											</li>
											<li>
												Reembolso en caso de p&eacute;rdida total.
											</li>
											<li>
												Traslado del asegurado y veh&iacute;culo en caso de ebriedad.
											</li>
										</ul>
										<p class="infoPromo">
											Si requieres la asistencia de Movilidad AIG con ApoyoVial llama al:
										</p>
										<p class="infoPromo">
											<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_Telephone_infotraffic.png">
											(01)&nbsp;555488400
										</p>
										<br>
										<p class="promoModal">
											Producto suscrito por AIG Seguros M&eacute;xico S.A. de C.V. con n&uacute;mero de registro PPAQ-S0012-0096-2016.
										</p>
										<p class="promoModal">
											Aplica condiciones y exclusiones generales de la p&oacute;liza que se pueden consultar en <a href="https://www.aig.com.mx/content/dam/aig/lac/mexico/documents/brochures/dc-seguro-de-automoviles-movilidad-aig.pdf">www.AIG.com.mx </a>
										</p>
										<p class="promoModal">
											Para poder hacer uso de los beneficios de este producto debes contar con un seguro de cobertura amplia.  
										</p>
										<p class="promoModal">
											<a href="https://apoyovial.net/condiciones-generales-del-seguro-de-automoviles-movilidad-aig-con-apoyovialseguro-de-automoviles-movilidad-aig-con-apoyovial-condiciones-generales/">Condiciones generales del Seguro de automóviles Movilidad AIG</a>
										</p>
										<input type="hidden" id="otraMarcaInputmodalMov" name="otraMarcaInput">
										<input type="hidden" id="otroModeloInputmodalMov" name="otroModeloInput">
										<input type="hidden" id="otroAnoInputmodalMov" name="otroAnoInput">
										<input type="hidden" id="otroVersionInputmodalMov" name="otroVersionInput">
									</div>
									<div id="insuranceMovilidad">
										<h4 class="no-margin text-center">Tu Seguro</h4>
										<div class="form-group text-center">
											<label class="checkbox"><input type="checkbox" name="asegurado">Cuento con seguro de cobertura amplia</label>
										</div>
										<div class="form-group col-xs-12 selectdiv">
											<label class="control-label">Aseguradora</label>
											<select id="aseguradoraSelect" name="aseguradora" class="form-control form-style">
												<option value="N/A">Selecciona una aseguradora</option>
											<?php foreach ($carriers as $c => $carrier) { ?>
												<option value="<?= $carrier["nombre_completo"] ?>"><?= $carrier["nombre"] ?></option>
											<?php } ?>
												<option value="OTRA">OTRA</option>
											</select>
										</div>
										
									</div>
									<div id="personalMovilidad">
										<h4 class="no-margin text-center">Contacto</h4>
										<div class="form-group col-xs-12">
											<input type="text" id="nombreModalMov" maxlength="50" name="nombre" class="form-control form-style" placeholder="Nombre">
										</div>
										<div class="form-group col-xs-12">
											<input type="text" id="apellidoModalMov" maxlength="50" name="apellido" class="form-control form-style" placeholder="Apellido">
										</div>
										<div class="form-group col-xs-6">
											<input type="number" id="edadModalMov" name="edad" max="99" min="18" class="form-control form-style" placeholder="Edad">
										</div>
										<div class="form-group col-xs-6">
											<input type="text" id="cpModalMov" name="cp" maxlength="5" class="form-control form-style" placeholder="C&oacute;digo Postal">
										</div>
										<div class="form-group col-xs-12">
											<input type="text" id="telefonoModalMov"  maxlength="10" name="telefono" class="form-control form-style" placeholder="Tel&eacute;fono">
										</div>
										<div class="form-group col-xs-12">
											<input type="email" id="emailModalMov" name="email" maxlength="50" class="form-control form-style" placeholder="Correo Electr&oacute;nico" value="">
										</div>
									</div>
								</div>
								<div class="wizard-actions">
									<button class="btn modal-btns previous" type="button" disabled>Anterior</button> |
									<button class="btn modal-btns next" type="button">Siguiente</button>
									
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalCompra" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Compra Inmediata</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<form id="formCompra">
							
							<div class="wizard" id="wizardCompra">
								<div class="wizard-steps">
									<ul>
										<li class="active" >
											<a href="#autoCompra" data-target="autoCompra">1</a>
										</li>
										<li> 
											<a href="#revisionComrpa" data-target="revisionComrpa">2</a>
										</li>
										<li>
											<a href="#personalCompra" data-target="personalCompra">3</a>
										</li>
									</ul>
								</div>
								<div class="wizard-body">
									<div id="autoCompra" class="active">
										<h4 class="no-margin text-center">Tu Auto</h4>
										<p class="text-center">En AVI Cars buscaremos un comprador para tu auto de forma inmediata. Completa la siguiente informaci&oacute;n para iniciar la b&uacute;squeda.</p>
										<div id="check_marcamodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style" id="marcamodalCompra" name="marca" onchange="changeMarcaCompra($(this))">
												<option class="visible" value="0">MARCA</option>
											<?php 
											foreach ($marcas as $m => $marca) 
											{ 
												if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO')
												{ ?>
													<option data-brand="<?= $marca?>" class="visible" value="<?= $m?>"><?= $marca?></option>
													<?php 
												}
											} ?>
											<option class="visible" value="-1">Otra Marca</option>
											</select>
											<div class="otraMarca hidden">
												<label class='control-label'>Especifica la marca</label>
												<input type='text' class='form-control' id='otraMarcaInputmodalCompra' name='otraMarcaInput' value='' placeholder="Otra Marca"/>
											</div>
										</div>
										<div id="check_submarcamodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style submarca" id="modelomodalCompra" name="submarca" onchange="changeSubmarcaCompra($(this))">
												<option value="0">MODELO</option>
											<?php 
											foreach ($submarcas as $sm => $submarca) 
											{  ?>
												<option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
											<?php 
											} ?>
												<option class="visible" value="-1">Otro Modelo</option>
											</select>
											<div class='otroModelo hidden'>
												<label class='control-label otroModelo'>Especifica el modelo</label>
												<input type='text' class='form-control' id='otroModeloInputmodalCompra' name='otroModeloInput' value='' placeholder="Otro Modelo"/>
											</div>
										</div>
										<div id="check_anomodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style" id="anomodalCompra" name="modelo" onchange="changeAnoCompra($(this))">
												<option value="0">A&Ntilde;O</option>
												<?php 
												foreach ($modelos as $md => $modelo) 
												{ ?>
													<option data-modelo="<?= $modelo["modelo"]?>" value="<?= $modelo["id"]?>"> <?= $modelo["modelo"]?> </option>
												<?php 
												} ?>
												<option class="visible" value="-1">Otro a&ntilde;o</option>								
											</select>
											<div id='otheryearmodalCompra' class='otroAno hidden'>
												<label class='control-label'>Especifica el a&ntilde;o</label>
												<input type='text' class='form-control' id='otroAnoInputmodalCompra' name='otroAnoInput'  value='' placeholder="Otro A&ntilde;o"/>
											</div>
										</div>
										<div id="check_versionmodal" class="form-group selectdiv selecposition col-xs-12">
											<select class="form-control form-style" id="versionmodalCompra" name="subnombres" onchange="changeVersionCompra($(this))">
												<option value="0">VERSI&Oacute;N</option>
											<?php 
											foreach ($versiones as $vr => $version) 
											{ 
											?>
												<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>" > <?= $version["version"]?> <?= $version["subnombre"]?> </option>
											<?php 
											} ?>
												<option class="visible" value="-1">Otra Versi&oacute;n</option>		
											</select>
											<div id='othervermodalCompra' class='otraVersion col-xs-12 hidden'>
												<label class='control-label col-xs-12'>Especifica la versi&oacute;n</label>
												<input type='text' maxlength="50" class='form-control' id='otroVersionInputmodalCompra' name='otroVersionInput' value=''placeholder="Otra Versi&oacute;n"/>
											</div>
										</div>
										
									</div>
									<div id="revisionCompra">
										<h4 class="no-margin text-center">Revisi&oacute;n</h4>
										<div class="form-group col-xs-12 selectdiv">
											<label class='control-label col-xs-12'>Donde quieres llevar tu auto a revisi&oacute;n</label>
											<select class="form-control form-style" id="revisionSelectCompra" name="revisionCompra">
												<option value="Interlomas">Interlomas</option>
												<option value="Polanco">Polanco</option>
												<option value="Tlalnepantla">Tlalnepantla</option>
												<option value="Toluca">Toluca</option>
											</select>
										</div>
										
									</div>
									<div id="personalCompra">
										<h4 class="no-margin text-center">Contacto</h4>
										<div class="form-group col-xs-12">
											<input type="text" id="nombreModalCompra" maxlength="50" name="nombre" class="form-control form-style" placeholder="Nombre">
										</div>
										<div class="form-group col-xs-12">
											<input type="text" id="apellidoModalCompra" maxlength="50" name="apellido" class="form-control form-style" placeholder="Apellido">
										</div>
										<div class="form-group col-xs-6">
											<input type="number" id="edadModalCompra" name="edad" max="99" min="18" class="form-control form-style" placeholder="Edad">
										</div>
										<div class="form-group col-xs-6">
											<input type="text" id="cpModalCompra" name="cp" maxlength="5" class="form-control form-style" placeholder="C&oacute;digo Postal">
										</div>
										<div class="form-group col-xs-12">
											<input type="text" id="telefonoModalCompra"  maxlength="10" name="telefono" class="form-control form-style" placeholder="Tel&eacute;fono">
										</div>
										<div class="form-group col-xs-12">
											<input type="email" id="emailModalCompra" name="email" maxlength="50" class="form-control form-style" placeholder="Correo Electr&oacute;nico" value="">
										</div>
									</div>
								</div>
								<div class="wizard-actions">
									<button class="btn modal-btns previous" type="button" disabled>Anterior</button> |
									<button class="btn modal-btns next" type="button">Siguiente</button>
									
								</div>
							</div>
							<p class="col-xs-12">
								Est&aacute;s apunto de compartir tu informaci&oacute;n con Turbo Prestamos
								<center>Ver <a href="/Aviso_de_Privacidad_AVIcars.pdf" target="_blank">Aviso de Privacidad</a></center>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalSeguros" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Contrata tu seguro con ApoyoVial</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<form id="formSeguros">
							
							<div class="wizard" id="wizardSeguros">
								<div class="wizard-steps">
									<ul>
										<li class="active" >
											<a href="#autoSeguros" data-target="autoSeguros">1</a>
										</li>
										<li>
											<a href="#personalSeguros" data-target="personalSeguros">2</a>
										</li>
									</ul>
								</div>
								<div class="wizard-body">
									<div id="autoSeguros" class="active">
										<h4 class="no-margin text-center">Comparte con nosotros tus datos para cotizar tu seguro.</h4>
										<div id="check_marcamodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style" id="marcamodalIns" name="marca" onchange="changeMarcaIns($(this))">
												<option class="visible" value="0">MARCA</option>
											<?php 
											foreach ($marcas as $m => $marca) 
											{ 
												if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO')
												{ ?>
													<option data-brand="<?= $marca?>" class="visible" value="<?= $m?>"><?= $marca?></option>
													<?php 
												}
											} ?>
											<option class="visible" value="-1">Otra</option>
											</select>
											<div class="otraMarca hidden">
												<label class='control-label'>Especifica la marca</label>
												<input type='text' class='form-control' id='otraMarcaInputmodalIns' name='otraMarcaInput' value=''/>
											</div>
										</div>
										<div id="check_submarcamodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style submarca" id="modelomodalIns" name="submarca" onchange="changeSubmarcaIns($(this))">
												<option value="0">MODELO</option>
											<?php 
											foreach ($submarcas as $sm => $submarca) 
											{  ?>
												<option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
											<?php 
											} ?>
												<option class="visible" value="-1">Otro Modelo</option>
											</select>
											<div class='otroModelo hidden'>
												<label class='control-label otroModelo'>Especifica el modelo</label>
												<input type='text' class='form-control' id='otroModeloInputmodalIns' name='otroModeloInput' value=''/>
											</div>
										</div>
										<div id="check_anomodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style" id="anomodalIns" name="modelo" onchange="changeAnoIns($(this))">
												<option value="0">A&Ntilde;O</option>
												<?php 
												foreach ($modelos as $md => $modelo) 
												{ ?>
													<option data-modelo="<?= $modelo["modelo"]?>" value="<?= $modelo["id"]?>"> <?= $modelo["modelo"]?> </option>
												<?php 
												} ?>
												<option class="visible" value="-1">Otro a&ntilde;o</option>								
											</select>
											<div id='otheryearmodalIns' class='otroAno hidden'>
												<label class='control-label'>Especifica el a&ntilde;o</label>
												<input type='text' class='form-control' id='otroAnoInputmodalIns' name='otroAnoInput'  value=''/>
											</div>
										</div>
										<div id="check_versionmodal" class="form-group selectdiv selecposition col-xs-12">
											<select class="form-control form-style" id="versionmodalIns" name="subnombres" onchange="changeVersionIns($(this))">
												<option value="0">VERSI&Oacute;N</option>
											<?php 
											foreach ($versiones as $vr => $version) 
											{ 
											?>
												<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>" > <?= $version["version"]?> <?= $version["subnombre"]?> </option>
											<?php 
											} ?>
												<option class="visible" value="-1">Otra Versi&oacute;n</option>		
											</select>
											<div id='othervermodalIns' class='otraVersion col-xs-12 hidden'>
												<label class='control-label col-xs-12'>Especifica la versi&oacute;n</label>
												<input type='text' maxlength="50" class='form-control' id='otroVersionInputmodalIns' name='otroVersionInput' value=''/>
											</div>
										</div>
										
									</div>
									<div id="personalSeguros">
										<h4 class="no-margin text-center">Comparte con nosotros tus datos para cotizar tu seguro.</h4>
										<div class="form-group col-xs-12">
											<input type="text" id="nombreModalIns" maxlength="50" name="nombre" class="form-control form-style" placeholder="Nombre">
										</div>
										<div class="form-group col-xs-12">
											<input type="text" id="apellidoModalIns" maxlength="50" name="apellido" class="form-control form-style" placeholder="Apellido">
										</div>
										<div class="form-group col-xs-6">
											<input type="number" id="edadModalIns" name="edad" max="99" min="18" class="form-control form-style" placeholder="Edad">
										</div>
										<div class="form-group col-xs-6">
											<input type="text" id="cpModalIns" name="cp" maxlength="5" class="form-control form-style" placeholder="C&oacute;digo Postal">
										</div>
										<div class="form-group col-xs-12">
											<input type="text" id="telefonoModalIns"  maxlength="10" name="telefono" class="form-control form-style" placeholder="Tel&eacute;fono">
										</div>
										<div class="form-group col-xs-12">
											<input type="email" id="emailModalIns" name="email" maxlength="50" class="form-control form-style" placeholder="Correo Electr&oacute;nico" value="">
										</div>
									</div>
								</div>
								<div class="wizard-actions">
									<button class="btn modal-btns previous" type="button" disabled>Anterior</button> |
									<button class="btn modal-btns next" type="button">Siguiente</button>
									
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalPrestamo" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Obt&eacute;n en 48 horas un pr&eacute;stamo con el respaldo de tu auto.</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<form id="formPrestamo">
							<div class="wizard" id="wizardPestamo">
								<div class="wizard-steps">
									<ul>
										<li class="active" >
											<a href="#montoPrestamo" data-target="montoPrestamo">1</a>
										</li>
										<li> 
											<a href="#autoPrestamo" data-target="autoPrestamo">2</a>
										</li>
										<li>
											<a href="#personalPrestamo" data-target="personalPrestamo">3</a>
										</li>
									</ul>
								</div>
								<div class="wizard-body">
									<div id="montoPrestamo" class="active">
										<div class="form-group col-xs-12 less-margin">
											<label class="control-label">Cu&aacute;nto dinero necesitas?</label>
											<input type="text" id="n1" maxlength="7" onkeyup="fAgrega3();" placeholder="50000" class="form-control form-style number5">
											<input type="hidden" id="montoPrestamoInput" maxlength="6" name="description" placeholder="50000" class="form-control form-style">
											<span class="alert-danger hidden">El monto debe estar entre 10,000 y 300,000</span>
										</div>
									</div>
									<div id="autoPrestamo">
										<div class="form-group col-xs-12 less-margin">
											Marca
											<input type="text" id="marcaPrestamo" maxlength="50" name="carbrand_c" class="form-control form-style" value="">
										</div>
										<div class="form-group col-xs-12 less-margin">
											Modelo
											<input type="text" id="submarcaPrestamo" maxlength="50" name="carsubbrand_c" class="form-control form-style" value="">
										</div>
										<div class="form-group col-xs-12 less-margin">
											A&ntilde;o
											<input type="text" id="modeloPrestamo" maxlength="4" name="carmodel_c" class="form-control form-style" value="">
										</div>
										<div class="form-group col-xs-12 less-margin">
											Version
											<input type="text" id="versionPrestamo" maxlength="100" name="carversion_c" class="form-control form-style" value="">
										</div>
										<div class="form-group col-xs-12 less-margin">
											Estado
											<select class="form-control form-style" name="description">
												<option>Bueno</option>
												<option>Regular</option>
												<option>Malo</option>
											</select>
										</div>
										<div class="form-group col-xs-12 less-margin">
											Tarjeta de circulaci&oacute;n
											<input type="file" >
										</div>
									</div>
									<div id="personalPrestamo">
										<div class="col-xs-12 form-group less-margin">
											<label class="control-label">Nombre</label>
											<input type="text" id="nombrePrestamo" name="last_name" class="form-control form-style" value="">
										</div>
										<div class="col-xs-12 form-group less-margin">
											<label class="control-label">Tel&eacute;fono</label>
											<input type="text" id="telefonoPrestamo" name="phone_mobile" class="form-control form-style" value="">
										</div>
										<div class="col-xs-12 form-group less-margin">
											<label class="control-label">Correo Electr&oacute;nico</label>
											<input type="text" id="mailPrestamo" name="email1" class="form-control form-style" value="">
										</div>
										<div class="col-xs-12 form-group less-margin">
											<label class="control-label">Edad</label>
											<input type="number" id="edadPrestamo" name="age_c" class="form-control form-style"  value="">
										</div>
										<div class="col-xs-12 form-group less-margin">
											<label class="control-label">Direcci&oacute;n</label>
											<input type="text" id="addressPrestamo" name="primary_address_street" class="form-control form-style"  value="">
										</div>
									</div>
								</div>
								<div class="wizard-actions">
									<button class="btn modal-btns previous" type="button" disabled>Anterior</button> |
									<button class="btn modal-btns next" type="button">Siguiente</button>
									
								</div>
							</div>
							
							<p class="col-xs-12">
								<input id="campaign_id" type="hidden" name="campaign_id" value="ac1aeacd-380a-3524-4ae7-5af453fe8895" />
								<input id="assigned_user_id" type="hidden" name="assigned_user_id" value="b5bdf87e-582a-ff53-658a-50eeff11e9c4" />
								Est&aacute;s apunto de compartir tu informaci&oacute;n con BenditoCoche
								<center>Ver <a href="/Aviso_de_Privacidad_AVIcars.pdf" target="_blank">Aviso de Privacidad</a></center>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="modalCrearAnuncio" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Crear un Anuncio</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<p class="col-xs-12 text-center">Al anunciar un auto toda la informaci&oacute;n de este se volver&aacute; p&uacute;blica</p>
						<div class="wizard" id="wizardAdvertisement">
							<div class="wizard-steps">
								<ul>
									<li class="active" >
										<a href="#autoAdvertisement" data-target="autoAdvertisement">1</a>
									</li>
									<li> 
										<a href="#adAdvertisement" data-target="adAdvertisement">2</a>
									</li>
									<li>
										<a href="#personalAdvertisement" data-target="personalAdvertisement">3</a>
									</li>
								</ul>
							</div>
							<div class="wizard-body">
								<div id="autoAdvertisement" class="active">
									<form id="nuevoAnuncioModalCar">
										<h4 class="no-margin text-center">Tu Auto</h4>
										<div id="check_marcamodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style" id="marcamodalAd" name="marca" onchange="changeMarcaAd($(this))">
												<option class="visible" value="0">MARCA</option>
											<?php 
											foreach ($marcas as $m => $marca) 
											{ 
												if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO')
												{ ?>
													<option data-brand="<?= $marca?>" class="visible" value="<?= $m?>"><?= $marca?></option>
													<?php 
												}
											} ?>
											<option class="visible" value="-1">Otra</option>
											</select>
											<div class="otraMarca hidden">
												<label class='control-label'>Especifica la marca</label>
												<input type='text' class='form-control' id='otraMarcaInputmodalAd' name='otraMarcaInput' maxlength="50" value=''/>
											</div>
										</div>
										<div id="check_submarcamodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style submarca" id="modelomodalAd" name="submarca" onchange="changeSubmarcaAd($(this))">
												<option value="0">MODELO</option>
											<?php 
											foreach ($submarcas as $sm => $submarca) 
											{  ?>
												<option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
											<?php 
											} ?>
												<option class="visible" value="-1">Otro Modelo</option>
											</select>
											<div class='otroModelo hidden'>
												<label class='control-label otroModelo'>Especifica el modelo</label>
												<input type='text' class='form-control' id='otroModeloInputmodalAd' name='otroModeloInput' maxlength="50" value=''/>
											</div>
										</div>
										<div id="check_anomodal" class="form-group selectdiv selecposition col-xs-12 top-bottom-space">
											<select class="form-control form-style" id="anomodalAd" name="modelo" onchange="changeAnoAd($(this))">
												<option value="0">A&Ntilde;O</option>
												<?php 
												foreach ($modelos as $md => $modelo) 
												{ ?>
													<option data-modelo="<?= $modelo["modelo"]?>" value="<?= $modelo["id"]?>"> <?= $modelo["modelo"]?> </option>
												<?php 
												} ?>
												<option class="visible" value="-1">Otro A&ntilde;o</option>								
											</select>
											<div id='otheryearmodalAd' class='otroAno hidden'>
												<label class='control-label'>Especifica el a&ntilde;o</label>
												<input type='text' class='form-control' id='otroAnoInputmodalAd' name='otroAnoInput' maxlength="4"  value=''/>
											</div>
										</div>
										<div id="check_versionmodal" class="form-group selectdiv selecposition col-xs-12">
											<select class="form-control form-style" id="versionmodalAd" name="subnombres" onchange="changeVersionAd($(this))">
												<option value="0">VERSI&Oacute;N</option>
											<?php 
											foreach ($versiones as $vr => $version) 
											{ 
											?>
												<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>" > <?= $version["version"]?> <?= $version["subnombre"]?> </option>
											<?php 
											} ?>
												<option class="visible" value="-1">Otra Versi&oacute;n</option>		
											</select>
											<div id='othervermodalAd' class='otraVersion col-xs-12 hidden'>
												<label class='control-label col-xs-12'>Especifica la versi&oacute;n</label>
												<input type='text' maxlength="50" class='form-control' id='otroVersionInputmodalAd' name='otroVersionInput' value=''/>
											</div>
										</div>
										<input type="hidden" id="autoModal" name="auto" value="">
										<input type="hidden" id="cuentaModal" name="cuenta" value="">
										<input type="hidden" id="imgs" name="imgs" value="" class="imgsAd">
										<input type="hidden" class="form-control form-style" name="alias" id="aliasModal" placeholder="Alias" maxlength="45" value="">
									</form>
									<div class="editVehiclePhotos" id="editingPhotosCarModalAd">
										<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="photoCarModalAd">
											<div class="inline-logos col-xs-6">
												<label for="imagenAutoModalAd" class="carImgLabel">
													<img class="camara center-block" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
													<input type='hidden' name='garage' id='garageinsertcarModal' value=''/>
													<input type="hidden" name="car" id="carModal" value="">
												</label>
												<input name="imagenAutoModalAd" id="imagenAutoModalAd" class="inputfile" type="file" onchange="addimgtempcarAd()" />
											</div>
										</form>
									</div>
								</div>
								<div id="adAdvertisement">
									<form id="nuevoAnuncioModalAd">
										<h4 class="no-margin text-center">Anuncio</h4>
										<div id="check_priceModal" class="form-group col-xs-12">
											<label class="control-label col-xs-12">Precio</label>
											<input class="form-control form-style number2" onkeyup="fAgrega();" type="text" maxlength="9" id="n2" onchange="minPrice()">
											<input class="form-control form-style" type="hidden" maxlength="7" id="precioModal" name="precio" value="" onchange="minPrice()">
											<span class="alert-danger hidden">El precio no debe ir vac&iacute;o</span>
											<span class="alert-info hidden"></span>
											<select id="moneda" class="moneda_car" name="moneda">
												<option value="MXN">MXN</option>
												<option value="USD">USD</option>
												<option value="EUR">EUR</option>
											</select>
										</div>
										
										<div id="" class="form-group col-xs-12">
											<label>Escribe aqu&iacute; tu anuncio</label>
											<textarea class="form-control form-style" placeholder="320 caracteres" cols="3" resize="false" maxlength="320" id="textAdModal" name="anunciotext"></textarea>
										</div>
									</form>
								</div>
								<div id="personalAdvertisement">
									<form id="nuevoAnuncioModalContact">
										<h4 class="no-margin text-center">Contacto</h4>
										<div class="form-group form-in-table">
											<select class=" form-style" name="phonecode" id="phonecodeModal">
												<?php 
												foreach ($phoneCodes as $c => $code) { ?>
													<option value="<?= $c?>"><?= $c?> <?= $code?></option>
												<?php }
												 ?>
											</select>
											<input type="text" id="phoneModal" name="phone" maxlength="10" class=" form-style" placeholder="TEL&Eacute;FONO 1" value="" >
											<label class="checkbox">
												<input type="checkbox" name="phonewa" id="phonewaModal">
												<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp_infotraffic.png">
											</label>
										</div>
										<div class="form-group form-in-table">
											<select class=" form-style" name="phone2code" id="phone2codeModal">
												<?php 
												foreach ($phoneCodes as $c => $code) { ?>
													<option value="<?= $c?>"><?= $c?> <?= $code?></option>
												<?php }
												 ?>
											</select>
											<input type="text" id="phone2Modal" name="phone2" maxlength="10" class=" form-style" placeholder="TEL&Eacute;FONO 2" value="" >
											<label class="checkbox">
												<input type="checkbox" name="phone2wa" id="phone2waModal">
												<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp_infotraffic.png">
										</label>
										</div>	
										<div class="form-group form-in-table">
											<select class=" form-style" name="phone3code" id="phone3codeModal">
												<?php 
												foreach ($phoneCodes as $c => $code) { ?>
													<option value="<?= $c?>"><?= $c?> <?= $code?></option>
												<?php }
												 ?>
											</select>
											<input type="text" id="phone3Modal" name="phone3" maxlength="10" class=" form-style" placeholder="TEL&Eacute;FONO 3" value="" >
											<label class="checkbox">
												<input type="checkbox" name="phone3wa" id="phone3waModal">
												<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp_infotraffic.png">
											</label>
										</div>
										<div class="form-group">
											<input type="email" id="emailModal" name="email" maxlength="50" class="form-control form-style" placeholder="CORREO ELECTR&Oacute;NICO (OPCIONAL)" value="">
										</div>

										<div class="form-group">
											<input type="text" id="zipcodeModal" name="zipcode" maxlength="50" class="form-control form-style" placeholder="C&Oacute;DIGO POSTAL" value="" maxlength="5">
											<input type="hidden" name="estado" id="estadoModal">
											<input type="hidden" name="calle" id="calleModal">
											<input type="hidden" name="colonia" id="coloniaModal">
											<input type="hidden" name="locationreference">
											
										</div>
									</form>
								</div>
							</div>
							<div class="wizard-actions">
								<button class="btn modal-btns previous" type="button" disabled>Anterior</button> |
								<button class="btn modal-btns next" type="button">Siguiente</button>
								
							</div>
						</div>
						<div id="modalDeleteImgCarAd" class="modal fade" role="dialog" style="z-index: 10009;position: fixed; top: calc(90% - 500px);">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="title-header modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Eliminar Foto</h4>
									</div>
									<div class="modal-body">
										<p>¿Est&aacute; seguro que desea eliminar la imagen?</p>
									</div>
									<div class="footer-line modal-footer">
										<button type="button" class="btn modal-btns" onclick="hideimgCarAd($(this))">cerrar</button> |
										<button type="button" id="eliminar" class="btn modal-btns" onclick="deletedImgTmpCarAd($(this))">aceptar</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="changes-modal"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Cualquier cambio afectar&aacute; en los datos de su veh&iacute;culo.</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade"  id='successAd' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>&iexcl;&Eacute;xito!</h4>
				</div>
				<div class="modal-body text-center">
					<p>Tu anuncio ha sido creado y publicado.</p>
					<img style="width: 30px;" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/foco.png" alt="">
					&iexcl;A&ntilde;ade detalles a tu anuncio para mejorar la b&uacute;squeda!
					<div class="img-response">
						<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" onclick="goToTimeline()">Continuar</button> |
					<button type="button" class="btn modal-btns" onclick="goToAdvertisement()">A&ntilde;adir detalles</button> 
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade"  id='successCar' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>&iexcl;&Eacute;xito!</h4>
				</div>
				<div class="modal-body text-center">
					<p>Tu veh&iacute;culo ha sido creado y publicado.</p>
					<img class="lightball-icon" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/foco.png" alt="">
					&iexcl;Para mejorar la experiencia te recomendamos a&ntilde;adir detalles a tu auto o incluir alg&uacute;n servicio! &iquest;Qu&eacute; deseas?
					<div class="monetizar" id="listaMonetizar">
						<button type="button" class="btn modal-btns" onclick="" id="buttonAdDetailCar">A&ntilde;adir detalles del auto</button>
						<input type="text" class="hidden" id="autoServiciosNuevoAuto" value=""> 
						<h4>Servicios:</h4>
						<h5 class="text-left">Protegemos tu auto</h5>
						<button class="vende" onclick="movilidadModal()"><span>Asistencia @ApoyoVial</span></button>
						<button class="seguro" onclick="seguroModal()"><span>Seguros cobertura Amplia</span></button>
						<h5 class="text-left">Capitaliza</h5>
						<button class="prestamo"><span>Pr&eacute;stamo inmediato</span></button>
						<button class="compra" onclick="compraModal()"><span>Compra inmediata</span></button>
						<button class="anuncia" onclick="newCarAd()" ><span>Anuncia tu auto</span></button>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" onclick="goToTimeline()">Ir a timeline</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade"  id='successGarage' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>&iexcl;&Eacute;xito!</h4>
				</div>
				<div class="modal-body text-center">
					<p>Tu garage ha sido creado y publicado satisfactoriamente.</p>
					<img style="width: 30px;" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/foco.png" alt="">
					&iexcl;A&ntilde;ade tu primer auto al garage!
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" onclick="goToTimeline()">Continuar</button> |
					<button type="button" class="btn modal-btns" onclick="" id="buttonAddDetailGarage">A&ntilde;adir auto</button> 
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade loadimgmodal-crop" id="loadImage" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Recorta tu imagen</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 text-center">
							<div id="img-to-upload" ></div>
							<button class="crop-rotate btn btn-avicars" data-deg="90"><img src="/img/icons/MyCars_Movil_viewport_features_giro-izq-64px_infotraffic.png"></button>
							<button class="crop-rotate btn btn-avicars" data-deg="-90"><img src="/img/icons/MyCars_Movil_viewport_features_giro-der-64px_infotraffic.png"></button>
						</div>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
					<button type="button" id="docrop" class="btn modal-btns doCrop" onclick="this.disabled='disabled'">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="loadImageCover" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Recorta tu imagen</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 text-center">
							<div id="img-to-upload-cover" ></div>
							
						</div>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
					<button type="button" id="saveCoverProfile" class="btn modal-btns doCrop" onclick="this.disabled='disabled'">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade"  id='reporting' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Reportar</h4>
				</div>
				<div class="modal-body report-modal">
					Por favor escoge por qu&eacute; es inapropiado: 
					<div class="form-group">
						<textarea class="form-control form-style" id="whyreportcomment" maxlength="140" placeholder="Escribe un comentario (Opcional)" rows="2"></textarea>
					</div>
					<ul>
						<?php
						$selectReport = $Report -> reportType();
						foreach ($selectReport as $r => $reportName) {
						?>
							<li data-type="<?=$reportName['id']?>" onclick="Reported($(this))">
								<?=$reportName["nombre"]?>
							</li>
						<?php
						} ?>
					</ul>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button>
				</div>
			</div>
		</div>
	</div> 
	<div class="modal fade"  id='deleteComment' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Eliminar comentario</h4>
				</div>
				<div class="modal-body">
					¿Seguro que quieres eliminar este comentario?
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" onclick="deleteComment($(this))">Eliminar</button>
				</div>
			</div>
		</div>
	</div> 
	<div class="modal fade"  id='deleteCommentAd' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Eliminar comentario</h4>
				</div>
				<div class="modal-body">
					¿Seguro que quieres eliminar este comentario?
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" onclick="deleteCommentAd($(this))">Eliminar</button>
				</div>
			</div>
		</div>
	</div> 
	<div class="modal fade" id="modalToblock" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Bloquear</h4>
				</div>
				<div class="modal-body">
					<p>¿Seguro que quieres bloquear a este usuario?</p>
					<p>Una vez hecho esto, &eacute;l no tendra acceso a tu contenido ni tu al suyo.</p>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" id="blockuser" data-to="">Bloquear</button>
				</div>
			</div>
		</div>
	</div>

	<?php 
	if($owner||(json_decode($privacyToChange,true)["tipo"]==2&&$colaborador)||(json_decode($privacyToChange,true)["tipo"]==3&&$colaborador)){ ?>
	<div class="modal fade" id='privacidad' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Actualizar privacidad</h4>
				</div>
				<div class="modal-body">
					<label for="privacy">Privacidad</label>
		            <div class=" text-center"> 
		           		<ul class="ul-list">
		           			<li class="list-privacy">
		           				<label for="privacy1" class="contenido-privacidad">
		           					<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_icon_Perfil-Pública_infotraffic.png"  class="modal-privacidad-img settings" class="img-list" alt="Privacidad">
	           						<p class="modal-privacidad-texto">P&uacute;blico&nbsp;<input id="privacy1" type="radio" name="updatePrivacy" value="2" <?= ($privacidad==2) ? "checked" : "" ?> data-actual="<?= ($privacidad==2) ? "1" : "0" ?>"></p>	           					
	           						<p class="modal-privacidad-texto p-list">
	           							Todos te pueden encontrar. Todos te pueden seguir.
	           						</p>
		           				</label>
		           			</li>
		           			<li class="list-privacy">
		           				<label for="privacy2" class="contenido-privacidad">
		           					<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png" class="modal-privacidad-img settings" alt="Privacidad">
		           					<p class="modal-privacidad-texto">Privado&ensp;
		           							<input id="privacy2" type="radio" name="updatePrivacy" value="1" <?= ($privacidad==1) ? "checked" : "" ?> data-actual="<?= ($privacidad==1) ? "1" : "0" ?>">
	           						</p> 
	           						<p class="p-list modal-privacidad-texto">Todos te pueden encontrar. T&uacute; decides si te pueden seguir.</p>
		           				</label>		           				
		           			</li>
		           			<li class="list-privacy"> 
		           				<label for="privacy4" class="contenido-privacidad">
		           					
	           						<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_Ojo-INvisible_infotraffic.png" class="modal-privacidad-img settings" style="width: 38px;" alt="Privacidad">
	           						<p class="modal-privacidad-texto">Secreto&ensp;
	           							<input id="privacy4" type="radio" name="updatePrivacy" value="3" <?= ($privacidad==3) ? "checked" : "" ?> data-actual="<?= ($privacidad==3) ? "1" : "0" ?>"> 
	           						</p> 
	           						<p class="p-list modal-privacidad-texto">Nadie te puede encontrar. Nadie te puede seguir.</p>      					
		           				</label>
		           				
		           			</li>
		           		</ul>
		            </div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
					<button type="button" class="btn modal-btns" onclick="abrir();" id='<?= $privacyToChange?>' onclick='getUpdatePrivacy(this.id)'>Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class='modal fade' id='addAccount' role='dialog'> 
	</div> 
	<div class='modal fade' id='editGarage' role='dialog'> 
	</div>
	<div id="Modal_private_p" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="private_profile"></p>Tendr&aacute que solicitar de nuevo autorizaci&oacuten para poder ver la informaci&oacuten. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="private_data1" name="private_data1">
                <input type="hidden" id="private_data2" name="private_data2">
                <input type="hidden" id="private_data3" name="private_data3">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowing(private_data1.value,private_data2.value,private_data3.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_public_p" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacutes seguro de dejar de seguir a <p id="public_profile"></p></p>
                <input type="hidden" id="public_data1" name="public_data1">
                <input type="hidden" id="public_data2" name="public_data2">
                <input type="hidden" id="public_data3" name="public_data3">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowing(public_data1.value,public_data2.value,public_data3.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_secret_p" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="secret_profile"></p>Usted no podr&aacute solicitar seguir de nuevo ya que es un perfil secreto. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="secret_data1" name="secret_data1">
                <input type="hidden" id="secret_data2" name="secret_data2">
                <input type="hidden" id="secret_data3" name="secret_data3">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowing(secret_data1.value,secret_data2.value,secret_data3.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_private_p_followers" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="private_profile_followers"></p>Tendr&aacute que solicitar de nuevo autorizaci&oacuten para poder ver la informaci&oacuten. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="private_data1_followers" name="private_data1_followers">
                <input type="hidden" id="private_data2_followers" name="private_data2_followers">
                <input type="hidden" id="private_data3_followers" name="private_data3_followers">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowingFollower(private_data1_followers.value,private_data2_followers.value,private_data3_followers.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_public_p_followers" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacutes seguro de dejar de seguir a <p id="public_profile_followers"></p></p>
                <input type="hidden" id="public_data1_followers" name="public_data1_followers">
                <input type="hidden" id="public_data2_followers" name="public_data2_followers">
                <input type="hidden" id="public_data3_followers" name="public_data3_followers">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowingFollower(public_data1_followers.value,public_data2_followers.value,public_data3_followers.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_secret_p_followers" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="secret_profile_followers"></p>Usted no podr&aacute solicitar seguir de nuevo ya que es un perfil secreto. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="secret_data1_followers" name="secret_data1_followers">
                <input type="hidden" id="secret_data2_followers" name="secret_data2_followers">
                <input type="hidden" id="secret_data3_followers" name="secret_data3_followers">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowingFollower(secret_data1_followers.value,secret_data2_followers.value,secret_data3_followers.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_private_g" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="private_garage"></p>Tendr&aacute que solicitar de nuevo autorizaci&oacuten para poder ver la informaci&oacuten. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="private_garage1" name="private_garage1">
                <input type="hidden" id="private_garage2" name="private_garage2">
                <input type="hidden" id="private_garage3" name="private_garage3">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowingGarage(private_garage1.value,private_garage2.value,private_garage3.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_public_g" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacutes seguro de dejar de seguir a <p id="public_garage"></p></p>
                <input type="hidden" id="public_garage1" name="public_garage1">
                <input type="hidden" id="public_garage2" name="public_garage2">
                <input type="hidden" id="public_garage3" name="public_garage3">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowingGarage(public_garage1.value,public_garage2.value,public_garage3.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_secret_g" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="secret_garage"></p>Usted no podr&aacute solicitar seguir de nuevo ya que es un garage secreto. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="secret_garage1" name="secret_garage1">
                <input type="hidden" id="secret_garage2" name="secret_garage2">
                <input type="hidden" id="secret_garage3" name="secret_garage3">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unfollowingGarage(secret_garage1.value,secret_garage2.value,secret_garage3.value);" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_private_a" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="private_car"></p>Tendr&aacute que solicitar de nuevo autorizaci&oacuten para poder ver la informaci&oacuten. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="private_car1" name="private_car1">
                <input type="hidden" id="private_car2" name="private_car2">
                <input type="hidden" id="private_car3" name="private_car3">
                <input type="hidden" id="owner" name="owner">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unlikingFromSiguiendo(private_car1.value,private_car2.value,private_car3.value);unfollowCar(private_car1.value,private_car2.value,private_car3.value,owner.value)" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_public_a" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Estás seguro de dejar de seguir a <p id="public_car"></p></p>
                <input type="hidden" id="public_car1" name="public_car1">
                <input type="hidden" id="public_car2" name="public_car2">
                <input type="hidden" id="public_car3" name="public_car3">
                <input type="hidden" id="owner2" name="owner2">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unlikingFromSiguiendo(public_car1.value,public_car2.value,public_car3.value);unfollowCar(public_car1.value,public_car2.value,public_car3.value,owner2.value)" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_secret_a" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p id="secret_car"></p>Usted no podr&aacute solicitar seguir de nuevo ya que es un auto secreto. ¿Desea dejar de seguir?</p>
                <input type="hidden" id="secret_car1" name="secret_car1">
                <input type="hidden" id="secret_car2" name="secret_car2">
                <input type="hidden" id="secret_car3" name="secret_car3">
                <input type="hidden" id="owner3" name="owner3">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button onclick="unlikingFromSiguiendo(secret_car1.value,secret_car2.value,secret_car3.value);unfollowCar(secret_car1.value,secret_car2.value,secret_car3.value,owner3.value)" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_Confirm_Privacidad" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>¿Est&aacute seguro que desea cambiar su estado de privacidad?</p>
                <input type="hidden" name="valor" id="valor">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button data-privacy='<?= $privacyToChange?>' onclick="updatePrivacy($(this))" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_Repeat_Privacy" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Error</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute eligiendo la misma privacidad</p>
                <input type="hidden" name="valor" id="valor">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_private_a_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p><h6><?= @$garageContain[0]["i_avi_account_car_alias"]?></h6></p>Tendr&aacute que solicitar de nuevo autorizaci&oacuten para poder ver la informaci&oacuten. ¿Desea dejar de seguir?</p>
                <input type="hidden" name="valor" id="valor">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button data-owner="<?= @$cuentaEncoded?>" <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$autoEncoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$autoEncoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$autoEncoded.'\','.@$typeFollow.')"' ?> class="btn modal-btns" data-dismiss="modal">Aceptar
              	</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_public_a_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Estás seguro de dejar de seguir a <p><h6><?= @$garageContain[0]["i_avi_account_car_alias"]?></h6></p></p>
                <input type="hidden" name="valor" id="valor">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button data-owner="<?= @$cuentaEncoded?>" <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$autoEncoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$autoEncoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$autoEncoded.'\','.@$typeFollow.')"' ?> class="btn modal-btns" data-dismiss="modal">Aceptar
              	</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_secret_a_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atenci&oacuten</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p><h6><?= @$garageContain[0]["i_avi_account_car_alias"]?></h6></p>Usted no podr&aacute solicitar seguir de nuevo ya que es un auto secreto. ¿Desea dejar de seguir?</p>
                <input type="hidden" name="valor" id="valor">
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button data-owner="<?= @$cuentaEncoded?>" <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$autoEncoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$autoEncoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$autoEncoded.'\','.@$typeFollow.')"' ?> class="btn modal-btns" data-dismiss="modal">Aceptar
              	</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_private_p_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p><h6><?= @$detalles["o_avi_userdetail_name"]?>&ensp;<?= @$detalles["o_avi_userdetail_last_name"]?></h6></p>Tendr&aacute que solicitar de nuevo autorizaci&oacuten para poder ver la informaci&oacuten. ¿Desea dejar de seguir?</p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$cuentaCoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$cuentaCoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$cuentaCoded.'\','.@$typeFollow.')"' ?>  class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_public_p_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Estás seguro de dejar de seguir a <p><h6><?= @$detalles["o_avi_userdetail_name"]?>&ensp;<?= @$detalles["o_avi_userdetail_last_name"]?></h6></p></p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$cuentaCoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$cuentaCoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$cuentaCoded.'\','.@$typeFollow.')"' ?>  class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_secret_p_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p><h6><?= @$detalles["o_avi_userdetail_name"]?>&ensp;<?= @$detalles["o_avi_userdetail_last_name"]?></h6></p>Usted no podr&aacute solicitar seguir de nuevo ya que es un auto secreto. ¿Desea dejar de seguir?</p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$cuentaCoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$cuentaCoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$cuentaCoded.'\','.@$typeFollow.')"' ?>  class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_private_g_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p><h6><?= @$garage["nameAccount"]?></h6></p>Tendr&aacute que solicitar de nuevo autorizaci&oacuten para poder ver la informaci&oacuten. ¿Desea dejar de seguir?</p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$garageEncoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$garageEncoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$garageEncoded.'\','.@$typeFollow.')"' ?> class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_public_g_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Estás seguro de dejar de seguir a <p><h6><?= @$garage["nameAccount"]?></h6></p></p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$garageEncoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$garageEncoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$garageEncoded.'\','.@$typeFollow.')"' ?> class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>
	<div id="Modal_secret_g_header" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Est&aacute apunto de dejar de Seguir a <p><h6><?= @$garage["nameAccount"]?></h6></p>Usted no podr&aacute solicitar seguir de nuevo ya que es un auto secreto. ¿Desea dejar de seguir?</p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
              	 |
              	<button <?= @$Seguidor->acepted ? 'onclick="unfollow($(this),\''.@$garageEncoded.'\',  '.@$typeFollow.'); unlike($(this), \''.@$garageEncoded.'\',  '.@$typeFollow.')"' : 'onclick="unfollow($(this),\''.@$garageEncoded.'\','.@$typeFollow.')"' ?> class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>

	<div id="Modal_privacidad_header_profile_garage_car_public" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Cualquier persona puede ver el contenido de este (Perfil, Garage o Auto). Cualquier persona lo puede seguir sin autorizaci&oacute;n</p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>

	<div id="Modal_privacidad_header_profile_garage_car_private" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Este (Perfil, Garage o Auto) solo puede ser visto por personas que han sido aceptadas para verlo puede ser encontrado por terceros en el buscador. la informaci&oacute;n general ser&aacute; desplegada</p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>

	<div id="Modal_privacidad_header_profile_garage_car_secret" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="title-header modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Atención</h4>
              </div>
              <div class="modal-body">
                <p>Este (Perfil, Garage o Auto) solo puede ser visto por invitaci&oacute;n, nadie lo puede ver o encontrar</p>
              </div>
              <div class="footer-line modal-footer">
              	<button type="button" class="btn modal-btns" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
        </div>
	</div>

	<div id="modalBeforeShare" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4>Compartir en AVI cars</h4>
				</div>
				<div class="modal-body" id="divShareBeforeModal">
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cerrar</button>
					|
					<button type="button" class="btn modal-btns" data-f="" data-p="" data-t="" data-dismiss="modal" id="sendShareButton" onclick="sendShare($(this))">Compartir</button>
				</div>
            </div>
        </div>
	</div>

	<script src="/js/dropzone.js?l=<?= LOADED_VERSION?>"></script>
	<script type="text/javascript">
		<?php
		$coder->encode($privacyToChangeNoJson["privacy"]);
		$whoprivacy=$coder->encoded;
		 ?>
		var accountNumber='<?= $_SESSION["usertkn"]?>';
		var whoprivacy='<?= $whoprivacy?>';
		var tipoPublish=<?= $privacyToChangeNoJson["tipo"]?>;
		var currCar=<?=$currAuto?>;
		var imgsNewCar={};
		var count=0;
		var curCar='';

	</script>
	<script type="text/javascript" src="/js/footer.js?l=<?= LOADED_VERSION?>"></script>
	<script>
    <?php if($_SESSION["loads"]==1)
    { ?>    
        setTimeout(function(){
        	$(".flotante").trigger("click");
        },1000);
        setTimeout(function(){
        	$(".flotante").trigger("click");
        },5000);
	    <?php 
	} ?>
	</script>
	<script src="/js/seguidores.js?l=<?= LOADED_VERSION?>"></script>
<?php
}
else
{
	$twitter=false;
	if(isset($_SESSION["tokenTwitter"]) && isset($_SESSION["previuos"])){
	    if($_SESSION["previuos"]){
	        $twitter=true;
	    }
	}
	if(!$twitter){
		session_unset();
		session_destroy();
	}
?>
	<div class="login-please">
		<p class="title">Crea tu perfil en AVIcars con:</p>
		<div class="login-hiding" onclick="hideBanner($(this))" title="ver menos">&times;</div>
		<div class="login-buttons otherlogin">
			<ul>
                <li>
                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fb_infotraffic.png" id="facebookBtnLogin">
                    <div id="fbLink"  class="fb-login-button facebook-btn" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" login_text="Facebook" scope="public_profile,email" onlogin="checkLoginState();" href="javascript:void(0);"></div>
                </li>
                 <li>
                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fotraffic.png" onclick="window.open(window.location.href='/','_blank')">
                </li>
                <li>
                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_google_infotraffic.png">
                    <div class=" btn g-signin2" id="loginG" data-onsuccess="onSignIn"></div>
                    
                </li>
                <li>
                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_twitter_infotraffic.png" id="twitterBtnLogin" onclick='window.open("/php/login/loginTwitter.php?u="+encodeURIComponent(location.pathname+location.search),"_self");'>
                </li>
            </ul>
        </div>
        <p class="subtitle">Al ingresar usted acepta los <a href="/Terminos_y_condiciones_AVIcars.pdf" target="_blank">t&eacute;rminos y condiciones</a>.</p>
	</div>
	<div id="fb-root"></div>
	<?php
	
	if(!$twitter){
	?>
	<script type="text/javascript" src="/js/login.js?l=<?= LOADED_VERSION?>"></script>
	<?php }
	else{ ?>
	<script type="text/javascript" src="/js/loginTwitter.js?l=<?= LOADED_VERSION?>"></script>
	<?php }
	?>
	<script>
		$(document).ready(function(){
			$("#reload-band").addClass('hidden');
	        $("#flag-reload").addClass('hidden');
	    });
	</script>
<?php
}
?>
</div>
</body>
</html>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/js/bootstrap-select.js"></script>
<script>
	$("#formCertify").submit(function (event){
		event.preventDefault();
		$.ajax({
    		url:'/php/Utilities/requestcertificationcontroller.php',
    		type:$("#formCertify").attr("method"),
    		data:$("#formCertify").serialize(),
    		async:false,
    		dataType: "json",
    		success:function(respuesta){
        		if(respuesta.Error){
       				new PNotify({
            			title: 'AVI cars:',
            			text: respuesta.Error,
            			type: 'error'
        			});     
      			}
      			else{
      				//new PNotify({
            		//	title: 'AVI cars:',
            		//	text: respuesta.Success,
            		//	type: 'success'
        			//});
      			}
    		}
		});
	});
	function certificar(){
		if(document.getElementById("selectAutos") != undefined && document.getElementById("selectGarages") != undefined && document.getElementById("checkProfile") != undefined){
			var autos = document.getElementById("selectAutos");
			var garages = document.getElementById("selectGarages");
			var checkbox = document.getElementById("checkProfile");
			var checked = checkbox.checked;
			if(autos.value == "" && garages.value == "" && !checked){
				new PNotify({
		            title: 'AVI cars:',
		            text: 'No se seleccion&oacute; ning&uacute;n dato',
		            type: 'error'
		        });
			}
			else{
				$("#formCertify").submit();
				new PNotify({
            		title: 'AVI cars:',
            		text: 'Se ha enviado una solicitud para certificar sus datos',
            		type: 'success'
        		});
        		setTimeout(function(){
        			location.reload();
        		},2000);
	    	}
	    	return;
		}
		if(document.getElementById("selectGarages") != undefined && document.getElementById("selectAutos") != undefined && document.getElementById("checkProfile") == undefined){
			var autos = document.getElementById("selectAutos");
			var garages = document.getElementById("selectGarages");
			if(autos.value == "" && garages.value == ""){
				new PNotify({
		            title: 'AVI cars:',
		            text: 'No se seleccion&oacute; ning&uacute;n dato',
		            type: 'error'
		        });
			}
			else{
				$("#formCertify").submit();
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Se ha enviado una solicitud para certificar sus datos',
		            type: 'success'
		        });
		        setTimeout(function(){
		        	location.reload();
		        },2000);
	    	}
	    	return;
		}
		if(document.getElementById("selectAutos") != undefined && document.getElementById("selectGarages") == undefined && document.getElementById("checkProfile") == undefined){
			var autos = document.getElementById("selectAutos");
			if(autos.value == ""){
				new PNotify({
		            title: 'AVI cars:',
		            text: 'No se seleccion&oacute; ning&uacute;n dato',
		            type: 'error'
		        });
			}
			else{
				$('#formCertify').submit();
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Se ha enviado una solicitud para certificar sus datos',
		            type: 'success'
		        });
		        setTimeout(function(){
		        	location.reload();
		        },2000);
	    	}
	    	return;
		}
		if(document.getElementById("selectAutos") == undefined && document.getElementById("selectGarages") != undefined && document.getElementById("checkProfile") == undefined){
			var garages = document.getElementById("selectGarages");
			if(garages.value == ""){
				new PNotify({
		            title: 'AVI cars:',
		            text: 'No se seleccion&oacute; ning&uacute;n dato',
		            type: 'error'
		        });
			}
			else{
				$("#formCertify").submit();
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Se ha enviado una solicitud para certificar sus datos',
		            type: 'success'
		        });
		        setTimeout(function(){
		        	location.reload();
		        },2000);
	    	}
	    	return;
		}
		if(document.getElementById("selectAutos") == undefined && document.getElementById("selectGarages") == undefined && document.getElementById("checkProfile") != undefined){
			var checkbox = document.getElementById("checkProfile");
			var checked = checkbox.checked;
			if(!checked){
				new PNotify({
		            title: 'AVI cars:',
		            text: 'No se seleccion&oacute; ning&uacute;n dato',
		            type: 'error'
		        });
			}
			else{
				$("#formCertify").submit();
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Se ha enviado una solicitud para certificar sus datos',
		            type: 'success'
		        });
		        setTimeout(function(){
		        	location.reload();
		        },2000);
	    	}
	    	return;
		}
		if(document.getElementById("selectAutos") == undefined && document.getElementById("selectGarages") != undefined && document.getElementById("checkProfile") != undefined){
			var garages = document.getElementById("selectGarages");
			var checkbox = document.getElementById("checkProfile");
			var checked = checkbox.checked;
			if(garages.value == "" && !checked){
				new PNotify({
		            title: 'AVI cars:',
		            text: 'No se seleccion&oacute; ning&uacute;n dato',
		            type: 'error'
		        });
			}
			else{
				$("#formCertify").submit();
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Se ha enviado una solicitud para certificar sus datos',
		            type: 'success'
		        });
		        setTimeout(function(){
		        	location.reload();
		        },2000);
	    	}
	    	return;
		}
		if(document.getElementById("selectAutos") != undefined || document.getElementById("selectGarages") == undefined || document.getElementById("checkProfile") != undefined){
			var autos = document.getElementById("selectAutos");
			var checkbox = document.getElementById("checkProfile");
			var checked = checkbox.checked;
			if(autos.value == "" && !checked){
				new PNotify({
		            title: 'AVI cars:',
		            text: 'No se seleccion&oacute; ning&uacute;n dato',
		            type: 'error'
		        });
			}
			else{
				$("#formCertify").submit();
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Se ha enviado una solicitud para certificar sus datos',
		            type: 'success'
		        });
		        setTimeout(function(){
		        	location.reload();
		        },2000);
	    	}
	    	return;
		}
	}
</script>