<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-29 18:41:10
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:55:44
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once ($_SERVER["DOCUMENT_ROOT"])."/php/Utilities/coder.php";
$coder = new Coder();
if(isset($_SESSION["user"]))
{
    $Usuario = new Usuario;
    $Garage = new Garage;
    $id = $_SESSION["iduser"];
    $tkn=$_GET["t"];
    $dataToken=$Usuario->getTokenToDelete($tkn,$id);
    //print_r($dataToken);
    $privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));          
    include ($_SERVER['DOCUMENT_ROOT']).'/php/perfil/header.php';
    $razones=$Usuario->getReasonToLeave();
}
if(empty($dataToken)){ ?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
<?php }
else{ ?>
    <div class="content content-no-header content-no-margin text-center">
    	<h2 class="text-center">Cerrar Cuenta</h2>
    	<h3 class="text-center del-padding">¡Que triste que te vayas! :'(</h3>
    	<p class="text-center">Tu cuenta ser&aacute; cerrada totalmente, nadie podr&aacute; ver tu informaci&oacute;n ni la de tus autos.</p>
        <p class="text-center">¡Si deseas volver, s&oacute;lo ingresa nuevamente!
        </p>
        </p>
    	<button class="btn del-btn delete" data-r="2">Inhabilitar mi cuenta</button>
        <button onclick="window.location.href='/timeline'" class="btn canceldel-btn">Cancelar</button>
    	<span class="delete-account delete" data-r="3">Deseo eliminar mi cuenta definitivamente</span>
    </div>
    <script type="text/javascript">
        var tkn='<?= $_GET["t"]?>'
    </script>
    <script type="text/javascript" src="/js/delete/step1.js?l=<?= LOADED_VERSION?>"></script>
<?php }
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>
