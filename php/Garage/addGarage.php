<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 10/01/2018
 * Time: 08:59 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
$garage = new Garage;
$numOfGarages = $garage -> countGaragesPerUser($_SESSION["iduser"]);
$usosGarage=$garage->getAccountUses();
?>

<div class="modal-dialog top-modal">
    <div class="modal-content login-modal">
        <div class="title-header modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">CREAR UN GARAGE</h4>
            <h5 class="modal-title modalNewAccount">ORGANIZA TUS VEH&Iacute;CULOS CREANDO TODOS LOS ESPACIOS QUE NECESITES</h5>
        </div>
        <div class="modal-body">
            <div class="row">
                <form onsubmit="return false;">
                    <div class="form-group col-xs-12 ">
                        <label for="signUpGarage">Nombre del garage:</label>
                        <input type="text" class="form-control form-style" name="signUpGarage" id="signUpGarage" placeholder="Garage nuevo" maxlength="45" value="Mi Garage <?=$numOfGarages["numero"]?>">
                    </div>
                    <div class="form-group col-xs-12"> 
                        <label for="garageUse">Uso del garage:</label>
                        <div id="use_garage" class="text-center">  
                       <?php foreach ($usosGarage as $u => $uso) { ?>
                            <label class="radio-inline">
                                <input type="radio" class="" name="setUse" value="<?= $u ?>" <?= $uso == 'Personal' ? 'checked' : '' ?>> <?= $uso?>
                            </label>
                       <?php } ?>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="privacy">Privacidad:</label>
                        <div class="text-center"> 
                            <label class="radio-inline"><input id="privacy1" type="radio" name="setGraragePrivacy" value="2" >P&uacute;blico</label>
                            <label class="radio-inline"><input id="privacy2" type="radio" name="setGraragePrivacy" value="1" checked>Privado</label>
                            <label class="radio-inline"><input id="privacy4" type="radio" name="setGraragePrivacy" value="3" >Secreto</label>
                        </div>
                    </div>
                </form>
                <?php /*<div class="people col-xs-12">
                    <label for="privacy">Avatar:</label>
                    <img class="newGarageImg img-responsive avatar">
                </div>

                <form onsubmit="return false;" method="post" enctype="multipart/form-data" id="garageAvatar" class="text-center">
                    <div class="editGarageAvatarModal" id="innerCropGarage"> 
                        <label for="imagenGarage">
                            <img  class="edit-cover camera-icon" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
                            <input type='hidden' id='hiddenRouteAvatar' value=''/>
                            <input type='hidden' name='garage' id='garage' value='<?= $_GET["garage"]?>'/>
                        </label>
                        <input name="imagenGarage" id="imagenGarage" class="inputfile" type="file" onchange="avatarGarageModal()" />
                    </div>
                    <button id="left-rotate" class='crop-rotate btn btn-avicars hidden' data-deg='90'><img src='/img/icons/MyCars_Movil_viewport_features_giro-izq-64px_infotraffic.png'></button> 
                    <button id="right-rotate" class='crop-rotate btn btn-avicars hidden' data-deg='-90'><img src='/img/icons/MyCars_Movil_viewport_features_giro-der-64px_infotraffic.png'></button>
                    
                </form>*/?>

            </div>
        </div>
        <div class="footer-line modal-footer"> 
            <button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
            <button type="submit" class="btn modal-btns" onclick="createThis($(this))"> Crear </button>
        </div>
    </div>
</div>
