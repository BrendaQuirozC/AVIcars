<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-29 21:13:46
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:55:53
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
    <?php if($dataToken["status"]==3){?>
        <h2 class="title-warning">Tu cuenta y su contenido se eliminar&aacute;n permanentemente.</h2>
    <?php } ?>
    	<h2 class="text-center"><?= ($dataToken["status"]==3) ? "Eliminar Cuenta" : "Inhabilitar Cuenta" ?></h2>
    	<h4 class=" text-center">¿Por qu&eacute; nos dejas? Tu opini&oacute;n es muy importante para ayudarnos a mejorar.</h4>
    	<form id="formDelete">
	    	<ul>
	    	<?php foreach ($razones as $r => $razon) { ?>
	    		<li class=" text-left"><label class="checkbox"><input type="checkbox" name="razon" value="<?= $razon["id"] ?>"> <?= $razon["razon"] ?></label></li>
	    	<?php } ?>
	    	</ul>
	    	<input type="hidden" name="t" value="<?= $_GET["t"]?>">
	    </form>
    	<button onclick="window.location.href='/timeline'" class="btn btn-block canceldel-btn">Cancelar</button>
    	<span class="delete-account delete"><?= ($dataToken["status"]==3) ? "Eliminar mi Cuenta" : "Inhabilitar mi Cuenta" ?></span>
    </div>
    <script type="text/javascript" src="/js/delete/step2.js?l=<?= LOADED_VERSION?>"></script>
<?php }
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>
