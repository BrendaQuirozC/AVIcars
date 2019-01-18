<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 17/01/2018
 * Time: 04:37 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$arrayMes=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
if(isset($_SESSION["user"]))
{
    $cuentaCoded=$_SESSION["usertkn"];
    $Usuario = new Usuario;
    $Garage = new Garage;
    $id = $_SESSION["iduser"];
    $telefono = $Usuario->getPhone($id);
    $genero = $Usuario->cGetGender();
    $calle = $Usuario->getStreet($id);
    $cp = $Usuario->getZipCode($id);
    $generoUser = $Usuario->getGenderUser($id);
    $cuenta = $_SESSION["iduser"];
    $detalles = $Usuario -> getUserdetail($cuenta);
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $infoPerfil = $Usuario->getInfoPerfil($_SESSION["iduser"]);
    $privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));     
    if(!empty($detalles)){
        $metasShare=array(
            "og"    =>  array(
                "title" => "AVI cars by Infotraffic | Perfil",
                "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
                "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
                "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
                "site_name" => "AVI cars",
                "type" => "website"
            ),
            "tw"    =>  array(
                "title" => "AVI cars by Infotraffic | Perfil",
                "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
                "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
                "image:alt" => "AVI cars",
                "card" => "summary_large_image"
            )
        );
    }     
    include ($_SERVER['DOCUMENT_ROOT']).'/php/perfil/header.php';
    $active="profile";
    $editing = "yes";
    $owner=true;
    $Like = new Like;
    $country=new Country;
    $phoneCodes=$country->getPhoneCodes();
    include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/headerProfile.php';
    ?>
    <div class="content">
        <div class="row form-send">
            <form method="POST" enctype="multipart/form-data" id="editProfile">
                <div id="name_user" class="form-group col-md-6 col-xs-12">
                    <label for="changeName">NOMBRE</label>
                    <input type="text" class="form-control form-style" name="changeName" autocomplete="off" id="changeName" placeholder="Nombre(s)" value="<?= $detalles["o_avi_userdetail_name"]?>" maxlength="45" >
                </div>
                <div id="lastname_user" class="form-group col-md-6 col-xs-12">
                    <label for="changeLastName">APELLIDO</label>
                    <input type="text" class="form-control form-style" name="changeLastName" autocomplete="off" id="changeLastName" placeholder="Apellido(s)" value="<?= $detalles["o_avi_userdetail_last_name"]?>" maxlength="45">
                </div>
                <div id="username_user" class="form-group col-md-6 col-xs-12">
                    <label for="changeNameUser">NOMBRE DE USUARIO</label>
                    <input type="text" class="form-control form-style" name="changeNameUser" autocomplete="off" id="changeNameUser" placeholder="Insertar Nombre de Usuario" value="<?= $_SESSION["user"]?>" maxlength="45">
                </div>
                <div id="change_email" class="form-group col-md-6 col-xs-12">
                    <label for="signUpEmail">CORREO ELECTR&Oacute;NICO</label>
                    <input type="text" class="form-control form-style" name="signUpEmail" autocomplete="off" id="signUpEmail" placeholder="ex.ample@hostmail.com" value="<?= $detalles["o_avi_user_email"]?>" maxlength="50">
                </div>
                <div id="change_bio" class="form-group col-md-6 col-xs-12 ">
                    <label class="control-label col-xs-12 no-padding">BIOGRAF&Iacute;A</label>
                    <textarea class="form-control form-style-box biograf" name="biografia" id="biografia" placeholder="Agrega tu biografía" maxlength="160" rows="3"><?= (isset($infoPerfil["bio"])) ? $infoPerfil["bio"] : "" ?></textarea>
                        
                </div>
                <div id="change_phone" class="form-group col-md-6 col-xs-12 form-group-block">
                    <label for="signUpPhone">TELEF&Oacute;NO (OPCIONAL)</label>
                    <select class="form-control form-style" name="phonecode">
                        <?php 
                        foreach ($phoneCodes as $c => $code) { ?>
                            <option value="<?= $c?>" <?= ($c==$detalles["phonecode"]) ? "selected" : "" ?>><?= $c?> <?= $code?></option>
                        <?php }
                         ?>
                    </select>
                    <input type="text" class="form-control form-style" maxlength="10" name="signUpPhone" autocomplete="off" id="signUpPhone" placeholder="(10 dígitos)" value="<?= $detalles["phone"]?>">
                </div>
                <div id="change_phone" class="form-group col-md-6 col-xs-12 form-group-block">
                    <label for="signUpPhone">TELEF&Oacute;NO CELULAR (OPCIONAL)</label>
                    <select class="form-control form-style" name="cellphonecode">
                        <?php 
                        foreach ($phoneCodes as $c => $code) { ?>
                            <option value="<?= $c?>" <?= ($c==$detalles["cellphonecode"]) ? "selected" : "" ?>><?= $c?> <?= $code?></option>
                        <?php }
                         ?>
                    </select>
                    <input type="text" class="form-control form-style" maxlength="10" name="signUpCellPhone" autocomplete="off" id="signUpCellPhone" placeholder="(10 dígitos)" value="<?= $detalles["cellphone"]?>">
                    <div class="checkbox checkbox-contact">
                        <input type="checkbox" name="cellphonewa" value="1" <?= ($detalles["cellphonewa"]==1) ? "checked" : "" ?>><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp_infotraffic.png">
                    </div>
                </div>
                <div id="check_gender" class="form-group col-md-6 col-xs-12 selectdiv">
                    <label for="signUpGender">G&Eacute;NERO</label>
                    <select name="selectGender" id="selectGender" class="form-control form-style">
                        <option value="0">Seleccionar</option>
                        <?php
                        foreach ($genero as $gen => $gg)
                        {
                            if($generoUser["genero"]==$gen)
                            {
                            ?>
                            <option value="<?= $gen?>" selected><?= $gg["genero"]?></option>
                        <?php
                            }
                            else{
                        ?>
                            <option value="<?= $gen?>"><?= $gg["genero"]?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div id="check_birth" class="form-group col-xs-12 col-md-6 date-input">
                    <label for="signUpdate1">Fecha de Nacimiento</label>
                    <?php
                    $yearSelected = ($detalles["fechaNacimiento"] != "") ? date("Y",strtotime($detalles["fechaNacimiento"])) : 0;
                    $year1 = date("Y",strtotime("- 16 years"));
                    $year2 =date("Y",strtotime("- 99 years"));
                    $mes= ($detalles["fechaNacimiento"] != "") ? date("m",strtotime($detalles["fechaNacimiento"])) : 0;
                    $dia=($detalles["fechaNacimiento"] != "") ? date("d",strtotime($detalles["fechaNacimiento"])) : 0;
                    ?>
                    <ul class="select-table">
                        <li>
                            <div class="selectdiv">
                                <select class="dateInput form-control form-style" id="anoNac" name="anoNac">
                                    <option>A&ntilde;o</option>
                                <?php for ($i=$year1; $i>=$year2; $i--) { ?>
                                    <option value="<?= $i?>" <?= ($yearSelected==$i) ? "selected" : "" ?>><?= $i?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="selectdiv">
                                <select class="dateInput form-control form-style" id="mesNac" name="mesNac">
                                    <option>Mes</option>
                                <?php for ($i=0; $i<12; $i++) { ?>
                                    <option value="<?= ($i+1) ?>" <?= (($i+1)==$mes) ? "selected" : "" ?>><?= $arrayMes[$i]?></option>
                                <?php } ?>
                                </select>        
                            </div>
                        </li>
                        <li>
                            <div class="selectdiv">
                                <select class="dateInput form-control form-style" id="diaNac" name="diaNac">
                                    <option>D&iacute;a</option>
                                <?php for ($i=1; $i<=31; $i++) { ?>
                                    <option value="<?= $i ?>" <?= ($i==$dia) ? "selected" : "" ?>><?= str_pad($i, 2, "0", STR_PAD_LEFT)?></option>
                                <?php } ?>
                                </select>        
                            </div>
                        </li>
                        <input type="hidden" id="signUpBirthdate" name="signUpBirthdate">
                    </ul>
                </div>
                <div class="form-group ">
                    <label for="signUpAddress" class="col-md-6 col-xs-12">DIRECCI&Oacute;N</label>
                    <div id="check_street" class="col-md-12 col-xs-12">                          
                        <input type="text" class="form-control form-style" name="signUpStreet" id="signUpStreet" placeholder="Calle, n&uacute;mero y colonia" value="<?= $calle["street"]?>"  maxlength="50">
                    </div>
                    <div id="check_zipcode" class="col-md-6 col-xs-12">
                        <input type="text" class="form-control form-style" name="signUpZipcode" id="signUpZipcode" placeholder="C&oacute;digo Postal" onchange="zip($(this))" maxlength="5" value="<?= $cp["zipcode"]?>"  maxlength="7">
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <input id="delegacion" type="text" class="form-control form-style" name="signUpCity" placeholder="Delegaci&oacute;n / Municipio" disabled>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <input id="estado" type="text" class="form-control form-style" name="signUpState" placeholder="Estado" disabled>
                    </div>
                    <div class="col-md-6 col-xs-12" >
                        <input id="pais" type="text" class="form-control form-style" name="signUpCountry" placeholder="Pa&iacute;s" disabled>
                    </div>
                </div>
                <div class="col-xs-12 text-center" style="padding-top: 30px;">
                    <?php if($detalles["verified"]){ ?>
                    <button class="btn cuenta-btns" type="button" data-toggle="modal" data-target="#update_info_profile_verified">Guardar</button>
                    <?php }else{ ?>
                    <button class="btn cuenta-btns" type="button" onclick="completar()">Guardar</button>
                    <?php } ?>
                    <button class="btn cuenta-btns changePwd-border" type="button" data-toggle="modal" data-target="#NewPwdModal">Cambiar mi Contrase&ntilde;a</button>
                    <button class="btn cuenta-btns " type="button" onclick="logout();">
                        Cerrar Sesi&oacute;n
                        <img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/log-out_.png" style="height: 22px;" alt="Cerrar Sesi&oacute;n">
                    </button>
                    <span id="delete-account" class="delete-account" data-c="<?= $_SESSION["iduser"]?>">Eliminar mi Cuenta</span>
                </div>    
            </form>
            <div class="infoApoyoVial text-center"> 
                <a class="acercade" href="/perfil/edit/configuracion"> Configuraci&oacute;n </a>
                <a class="acercade" target="_blank" href="/Terminos_y_condiciones_AVIcars.pdf"> T&eacute;rminos y Condiciones </a>
                <a class="acercade" target="_blank" href="https://apoyovial.net/acerca-de/"> Acerca de </a>
                <a class="acercade" target="_blank" href="/Aviso_de_Privacidad_AVIcars.pdf"> Aviso de Privacidad </a>
                <a class="acercade" target="_blank" href="/buzon"> Sugerencias </a>
                <a class="acercade" target="_blank" href="/ayuda"> Ayuda </a>
            </div>
        </div>
    <div class="modal-verify">
        
    </div>
    <div id="pwdModal" class="modal fade" role="dialog">
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="title-header modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    Olvid&eacute; mi contrase&ntilde;a
                </div>  
                <div class="modal-body">
                    <form action="forgotPassword_submit" id="formResetPwd">
                        <p>Por favor ingresa tu correo que usas en el sitio:</p>
                        <div id="pwd" class="form-group"> 
                            <input type="email" class="form-control form-style" name="getPwd" id="getPwd" placeholder="E-mail">
                        </div>
                        <div class="captcha-margin"  >
                            <div id="captchaPWD" class="captcha"></div>
                        </div> 
                    </form>
                    <hr>
                        <button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button> |
                        <button type="button" class="btn modal-btns" onclick="recuperarPwInPage()">Recuperar contrase&ntilde;a</button>
                </div>  
            </div>
        </div>  
    </div>
    <script type="text/javascript" src="/js/password.js?l=<?= LOADED_VERSION?>"></script>
    <script type="text/javascript" src="/js/editCuenta.js?l=<?= LOADED_VERSION?>"></script>
    <?php
    include ($_SERVER['DOCUMENT_ROOT']) . '/php/login/updateNewPassword.php';
    include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
}
else 
{
    ?>
     <script> location.replace("../../"); </script>
    <?php
}
?>
