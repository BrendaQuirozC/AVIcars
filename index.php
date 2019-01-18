<?php
/**
 * Created by Juan Gonzalez.
 * Date: 09/01/2018
 * Time: 08:20 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(isset($_SESSION["user"]))
    header("Location: /timeline");
$twitter=false;
if(isset($_SESSION["tokenTwitter"])&& isset($_SESSION["previuos"])){
    if($_SESSION["previuos"]){
        $twitter=true;
    }
}
include ($_SERVER['DOCUMENT_ROOT']).'/login/header.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
$arrayMes=array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
date_default_timezone_set('America/Mexico_City');
$Version = new Version;
$coder = new Coder();
?>
<div class="sidebar sidebar-login hidden-xs visible-sm visible-md visible-lg" id="sidebar">
    <ul>
        <li>
            <a href="/>">
                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/etiqueta_infotraffic.png" class="icons-login"> 
                <span class="login-s">Anuncia tu Auto</span>
            </a>
        </li>
        <li>
            <a href="https://seguros.apoyovial.app/" target="_blank">
                <span class="login-s">Seguros cobertura amplia</span> 
                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/seguro_infotraffic_40px.png" class="icons-login"> 
            </a>
        </li>
        <li>
            <a href="/"> 
                <span class="login-s">Pr&eacute;stamo inmediato</span> 
                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/moneda_infotraffic.png" class="navigation-icon">
            </a>
        </li>
        <li>
            <a href="/">
                <span class="login-s">Compra inmediata</span> 
                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/billete_infotraffic.png" class="icons-login" > 
            </a>
        </li>
    </ul>
    <p><a href="https://apoyovial.net/acerca-de/" target="_blank">Acerca de</a></p>
    <p><a href="/Terminos_y_condiciones_AVIcars.pdf" target="_blank">T&eacute;rminos y Condiciones</a></p>
    <p><a href="/buzon" target="_blank">Sugerencias</a></p>
    <p><a href="/Aviso_de_Privacidad_AVIcars.pdf" target="_blank">Aviso de Privacidad</a></p>
    <p><a href="/ayuda" target="_blank">Ayuda</a></p>
    <p class="marca">ApoyoVial&reg; 2018</p>
</div>

<div class="sidebar sidebar-login sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
    <?php
    if(empty($_SESSION["iduser"]))
    { ?>
        <p class="pointer">
            <img data-toggle="modal" data-target="#inicieSesion" src="/img/Banner_registro_AVI.png">
        </p>
    <?php } ?>
    <p>
        <a class="perfilpromo" href="/anunciate" target="_blank">
            <img src="/img/ads/promo_ad/<?= rand(1,3)?>.png">
            Clic aqu&iacute;
        </a>
    </p>
</div>
<div class="content" id="posts">
</div>
<div class='modal fade' id='inicieSesion' role='dialog'> 
    <div class="modal-dialog">
        <div class="modal-content modal-login">
            <div class="nopad-login title-header modal-header">
                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2>&iexcl;INICIA SESI&Oacute;N!</h2>
            </div>
            <div class="modal-body-login modal-body">
                <div class="login-buttons otherlogin">
                    <ul>
                        <li>
                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fb_infotraffic.png" id="facebookBtnLogin">
                            <div id="fbLink"  class="fb-login-button facebook-btn" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" login_text="Facebook" scope="public_profile,email" onlogin="checkLoginState();" href="javascript:void(0);"></div>
                        </li>
                         <li>
                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fotraffic.png" onclick="showRegister()">
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
                <p>¿Ya tienes cuenta?</p>
                <form onsubmit="return false;" class="form-size">
                    <div id="in_usernameModal" class="form-group username-mrg">
                        <input type="text" class="form-control form-style-login" name="logInUsernameModal" id="logInUsernameModal" onkeypress="iniciarpressModal(event)" placeholder="Usuario / Email" maxlength="50">
                    </div>
                    <div id="in_pwdModal" class="form-group pwd-mrg">
                        <input type="password" class="form-control form-style-login" name="logInPasswordModal" id="logInPasswordModal" onkeypress="iniciarpressModal(event)" placeholder="Contrase&ntilde;a" maxlength="60">
                        <p class="contra"><a class="a-contra" href="#" data-toggle="modal" data-target="#pwdModal">Olvid&eacute; mi contrase&ntilde;a</a></p>
                    </div>
                </form> 
                &Oacute; <a onclick="showRegister()" class="regis-now">¡Reg&iacute;strate!</a>
            </div>
            <div class="footer-line modal-footer">
                <button id="iniciarModal" type="submit" class="btn modal-btns-login" <?= (!$twitter) ? "onclick='conectarModal()'" : "" ?> > 
                    Iniciar Sesi&oacute;n 
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade"  id='logeate' role='dialog'>
    <div class="modal-dialog">
        <div class="modal-content modal-login">
            <div class="nopad-login title-header modal-header">
                <h2>&iexcl;REG&Iacute;STRATE!</h2>
                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body-login modal-body">
                <form onsubmit="return false;" class="form-size" > 
                    <div id="check_name" class="form-group"> 
                        <input type="text" class="form-control form-style-login" name="signUpName"  id="signUpName" placeholder="Nombre(s)*" onkeypress="registrarpress(event)" maxlength="45">
                    </div>
                    <div id="check_lastname" class="form-group">
                        <input type="text" class="form-control form-style-login" name="signUpLastName" id="signUpLastName" placeholder="Apellidos*" onkeypress="registrarpress(event)" maxlength="45">
                    </div>
                    <div id="check_mail" class="form-group"> 
                        <input type="email" class="form-control form-style-login" name="signUpEmail" id="signUpEmail" placeholder="Correo Electrónico*" onchange="confirmmail($(this))" onkeypress="registrarpress(event)" maxlength="50">
                    </div>
                    <div id="check_username" class="form-group"> 
                        <input type="text" class="form-control form-style-login" name="signUpUsername" id="signUpUsername" placeholder="Nombre de Usuario*" onkeypress="registrarpress(event)" maxlength="45">
                    </div>
                    <div id="check_pwd" class="form-group">
                        <input type="password" class="form-control form-style-login" name="signUpPassword" id="signUpPassword" placeholder="Contrase&ntilde;a (entre 8 y 60 caracteres)*" onkeypress="registrarpress(event)" maxlength="60">
                    </div>
                    <div id="recheck_pwd" class="form-group">
                        <input type="password" class="form-control form-style-login" name="reSignUpPassword" id="reSignUpPassword" placeholder="Confirma tu Contrase&ntilde;a*" onkeypress="registrarpress(event)" maxlength="60">
                    </div>
                    <div id="check_birth" class="form-group date-input">
                        <label for="signUpdate1">Fecha de Nacimiento *</label>
                        <?php
                        $year1 = date("Y",strtotime("- 16 years"));
                        //$year1=2000;
                        $year2 =date("Y",strtotime("- 99 years"));
                        $mes= date("m");
                        $dia=date("d");
                        ?>
                        <ul>
                            <li>
                                <div class="selectdiv-login selectdiv">
                                    <select onkeypress="registrarpress(event)" class="dateInput form-control form-style-login" id="anoNac">
                                        <option>A&ntilde;o</option>
                                    <?php for ($i=$year1; $i>=$year2; $i--) { ?>
                                        <option value="<?= $i?>"><?= $i?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div class="selectdiv-login selectdiv">
                                    <select onkeypress="registrarpress(event)" class="dateInput form-control form-style-login" id="mesNac">
                                        <option>Mes</option>
                                    <?php for ($i=0; $i<12; $i++) { ?>
                                        <option value="<?= ($i+1) ?>" ><?= $arrayMes[$i]?></option>
                                    <?php } ?>
                                    </select>        
                                </div>
                            </li>
                            <li>
                                <div class="selectdiv-login selectdiv">
                                    <select onkeypress="registrarpress(event)" class="dateInput form-control form-style-login" id="diaNac">
                                        <option>D&iacute;a</option>
                                    <?php for ($i=1; $i<=31; $i++) { ?>
                                        <option value="<?= $i ?>"><?= str_pad($i, 2, "0", STR_PAD_LEFT)?></option>
                                    <?php } ?>
                                    </select>        
                                </div>
                            </li>
                            <input type="hidden" id="signUpBirthdate" name="signUpBirthdate">
                        </ul>
                    </div>
                    <input type="checkbox" id="terminos" class=""> Acepto T&eacute;rminos y Condiciones *
                    <div class="captcha-margin register" >
                        <div id="registerCaptcha" class="captcha"></div>
                    </div> 
                </form>
                ¿Ya tienes cuenta? <a onclick="showLogin()" class="regis-now">¡Inicia Sesi&oacute;n!</a>
            </div>
            <div class="footer-line modal-footer">
                <button id="regis" type="submit" class="btn modal-btns-login" <?= (!$twitter) ? "onclick='enviar()'" : "" ?>" >Registrarse</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var lastPost=0;
    var search=true;
    var s="i";
    var u=0;
</script>
<script type="text/javascript" src="/js/timeline.js?l=<?= LOADED_VERSION?>"></script>

<?php
include ($_SERVER['DOCUMENT_ROOT']) . '/php/login/forgotPassword.php';
include ($_SERVER['DOCUMENT_ROOT']).'/login/footer.php';
?>