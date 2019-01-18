<?php
/**
 * Created by Juan Cairo Gonzalez.
 * Date: 12/02/2018
 * Time: 12:54 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';

$database=new Database;
$db=$database->connect();
$usr = new Usuario;
if(isset($_POST["username"])){
    $usrname = $_POST["username"];
    $email=NULL;
}else{
    $email=$_POST["mail"];
    $usrname=NULL;
}
$pwd= $_POST["password"];
$session =$usr -> login($usrname, $email, $pwd);
if($session==FALSE)
{
    echo 0;
}
else{
    ?>
    <?php 
        if(isset($_SESSION["user"]) && $_SESSION["status"]==3)
        {
            ?>  
            <div class="col-md-12 col-xs-12 no-padding">
                <div class="text-center title-warning">
                    Revisa tu correo electr&oacute;nico <b><?= $_SESSION["mail"]?></b> y confirma tu registro
                </div>
            </div>
            <?php
        }               
        ?>
        <div class="col-md-12">
            <div class="col-md-3 col-sm-4 hidden-xs visible-sm visible-md visible-lg nav-space">
                <a href="/" class="navbar-brand" style="padding-top: 0px"><img src="/img/logo_horizontal.png" class="img-responsive img-header center-block" style="max-width: 40%;"></a>
            </div>
            <div class="text-left col-md-4 col-sm-2 nav-space">
                <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn negativ-top">
                        <button class="btn btn-default glyphicon glyphicon-search" type="button"></button>
                    </span>
                </div>
            </div>
            <div class="col-md-4 col-sm-2 pull-right list-head nav-space">
                <a href="#" class="pull-right btn-header-avi"  type="button" data-toggle="dropdown"><img class="icon-with-21" src="/img/icons/inicioSesion.png" style="max-width: 24px;" ><?= $_SESSION["user"]?>
                            <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/perfil/edit/cuenta/index.php">Informaci&oacute;n de perfil</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/php/userLogout.php">Cerrar sesi&oacute;n</a></li>
                </ul>
            </div>
        </div>
    <?php
}
