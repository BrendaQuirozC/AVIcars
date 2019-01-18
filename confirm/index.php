<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2014-03-05 13:46:41
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:41:06
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(isset($_GET["token"])){
	$token = base64_decode(urldecode($_GET["token"]));	
}
else{
	header("Location: /");
}
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
$Usuario = new Usuario;
$Garage = new Garage;
include ($_SERVER['DOCUMENT_ROOT']).'/login/header.php';
$usuario = $Usuario->verifyToken($token, 2);
$confirmado=true;
if(!empty($usuario))
{
	$activate = $_GET["account"];
	if(base64_decode(urldecode($activate))=="activate")
	{
		$confirmado=!$Usuario -> confirmAccount($usuario["idUser"]);
	}
}
if(!$confirmado)
{
	 $Usuario -> deleteToken($token,2);
	?>
	<div class="row" style="margin: 100px 0px 25px 0px; ">
		<div class="col-sm-offset-2 col-lg-offset-4 col-md-offset-3 col-xs-10 col-sm-8 col-md-6 col-lg-4 col-xs-offset-1" style="background-color: rgba(32,32,32); color: #fff9f9; padding:20px; height: 175px;border-radius: 7px;">
            <div class="textwhite text-center">
            	<h3>!CORREO CONFIRMADO!</h3>
            	<h4>GRACIAS POR PERTENECER A NUESTRA FAMILIA</h4>
            	<h4>&hearts;</h4>
            </div>
        </div>
		<div class="col-md-12 text-center " style="margin-top: 50px;">
			<button class="btn btn-avi btn-avi-save space" onclick="window.location.href='/'">CONTINUAR A TU PERFIL</button>
		</div>
		
	</div>
	
	<?php
}
else
{
	?>
    	<script> location.replace("/"); </script>
	<?php 
}
?>
</div>
<?php
include ($_SERVER['DOCUMENT_ROOT']).'/login/footer.php';
