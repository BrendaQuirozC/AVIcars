<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 16/01/2018
 * Time: 12:54 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["garage"])){
	header("Location: /");
}
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/login/address.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$cuentaEncoded=$_GET["cuenta"];
$coder->decode($_GET["cuenta"]);
$_GET["cuenta"]=$coder->toEncode;
$garageEncoded=$_GET["garage"];
$coder->decode($_GET["garage"]);
$_GET["garage"]=$coder->toEncode;
$address = new Address;
$Garage = new Garage;
$detalles = array();
$Usuario = new Usuario;
$country=new Country;
$phoneCodes=$country->getPhoneCodes();
$garageCurr=0;
if(isset($_GET["garage"])){
	$garageCurr=$_GET["garage"];
	$garage=$Garage->accountById($garageCurr);
}
$niveles=$Garage->getTypeColaborators();
$privacyToChange=json_encode(array("tipo" =>2,"privacy"=>$garageCurr));
$show=true;
if(isset($_GET["cuenta"]) && isset($_GET["garage"]))
{
	$cuenta = $_GET["cuenta"];
    $nombreCuenta= $Usuario->getGarage($_GET["garage"]);
	$garage = $Garage ->accountById($_GET["garage"]);
	if(empty($garage)){
		$show=false;
	}
	else{
		
		$imgPerfil = $Usuario->getImgPerfil($garage["user"]);
		$detalles = $Garage -> getUserdetail($garage["user"]);
		$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
		if(empty($infoPerfil)){
			$show=false;
		}else{
			if(!empty($detalles))
			{
				$instancia = array();
				$llaveGarage = $_GET["garage"];
				$extrasGarage = $Garage->getGarageExtras($_GET["garage"]);
				$privacyGarage = $Garage -> getGarageInfo($_GET["garage"]);
				$active="configure";
				$editing = "yes";
				$Like = new Like;

			}
			else{
				$show=false;
			}
		}
	}
}
else{
	$show=false;
}
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],1);
if($garage["user"]!=$_GET["cuenta"]){
	$show=false;
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!$owner&&!$colaborador){ 
	$show=false;
}
else{
	$metasShare=array(
		"og"	=>	array(
			"title" => "AVI cars by Infotraffic | Perfil",
		    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
		    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
		    "site_name" => "AVI cars",
		    "type" => "website"
		),
		"tw"	=>	array(
			"title" => "AVI cars by Infotraffic | Perfil",
		    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
		    "image:alt" => "AVI cars",
		    "card" => "summary_large_image"
		)
	);
}
if($show){ 
	include_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/headerGarage.php";
	
	$colaboradores=$Garage->getColaborators($_GET["garage"]);
	
		?>
		<div class="content">
			<div class="alert alert-info text-justify">
				<h4>¿Qu&eacute; es un colaborador?</h4>
				<p>
					<strong>Contenido.</strong> Puede publicar contenido en el timeline del garage o en los timelines de los autos dentro del garage.
				</p>
				<p>
					<strong>Comercial.</strong> Todo lo que se puede hacer como Contenido adem&aacute;s de editar la informaci&oacute;n de los autos del garage, ponerlos en venta y solicitar servicios para ellos.
				</p>
				<p>
					<strong>Administrador.</strong> Todo lo que se puede hacer como Comercial adem&aacute;s de editar la informaci&oacute;n del Garage, imagenes de perfil y de portada asi como la privacidad de este.
				</p>
				<small> <div class="glyphicon glyphicon-info-sign"></div> S&oacute;lo tus seguidores pueden colaborar en tu garage.</small>
			</div>
		    <div class="form-send">
		    	<div class="col-xs-12 form-group form-list">
		    		<label class="control-label">Agregar Colaborador</label>
		    		<input type="text" name="memebers" class="form-style form-control " id="inputMembers" onkeyup="getUsers($(this));" data-g="<?= $garageEncoded?>" autofocus placeholder="Escribe para buscar">
		    		<div class="result-list" id="potenciales">
		    			
		    		</div>
		    	</div>
		    	<div class="toColaborate col-xs-12">
		    		<ul></ul>
		    		
		    	</div>
		    	<div class="col-xs-12 form-group selectdiv">
		    		<label class="control-label">Rol del Colaborador</label>
		    		<select class="form-control form-style" id="selectLevel">
		    		<?php foreach ($niveles as $n => $nivel) { ?>
		    			<option value="<?= $nivel["id"]?>"><?= $nivel["description"]?></option>
		    		<?php } ?>
		    		</select>
		    	</div>
		    	<div class="text-center col-xs-12">
		    		<button class="btn cuenta-btns" disabled id="addPendings" data-g="<?= $garageEncoded?>">Agregar</button>
		    	</div>
		    	<div class="members" data-g="<?= $garageEncoded?>">
		    		<h3>Colaboradores</h3>
		    	<?php foreach ($colaboradores as $c => $colaborador){ 
		    		$coder->encode($colaborador["id_user"]);
		    		$colaboradorEncoded=$coder->encoded;
		    		?>
		    		<div class="member" data-u='<?= $colaboradorEncoded?>'>
	    				<div class="img-search-profile" style="<?= ($colaborador["avatar"]!="") ? "background-image: url('".$colaborador["avatar"]."')" : ""?>"></div>
						<p>
							<span onclick="window.location.href='/perfil/?cuenta=<?= $colaboradorEncoded?>'">
								<?= $colaborador["name"]?> <?= $colaborador["last_name"]?>
							</span>

							<img src="<?= (($colaborador["privacidad"] == 1) ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png' : ( ($colaborador["privacidad"] == 3) ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png' : '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png') ) ?>" class="'<?= (($colaborador["privacidad"] == 1) ? 'private' : (($colaborador["privacidad"] == 3) ? 'secret' : 'public') )?>">
						</p>
						<?php if($colaborador["id_user"]==$_SESSION["iduser"]){?>
							<span class="kind"><?= $colaborador["nivel"]?></span>
						<?php }elseif($_GET["cuenta"]!=$colaborador["id_user"]){ 
							if($owner||($colaborador["nivelid"]>1)){?>
								<select class="level" >
								<?php foreach ($niveles as $n => $nivel) { 
									$selected="";
									if($colaborador["nivelid"]==$nivel["id"]){
										$selected="selected";
									}?>
					    			<option value="<?= $nivel["id"]?>" <?= $selected?>><?= $nivel["description"]?></option>
					    		<?php } ?>
								</select>
					    	<?php }else{ ?>
					    		<span class="kind"><?= $colaborador["nivel"]?></span>
					    	<?php } ?>
						<?php }else{ ?>
								<span class="kind"><?= $colaborador["nivel"]?></span>
						<?php } ?>
						<?php if($colaborador["id_user"]==$_SESSION["iduser"]){?>
							<span class="itsyou">Eres t&uacute;</span>
						<?php }elseif($_GET["cuenta"]==$colaborador["id_user"]){?>
							<span class="itsyou">Dueño</span>
						<?php }elseif($owner||($colaborador["nivelid"]>1)){?>
							<span class="deleteMember" data-toggle="modal" data-target="#deleteColaborador">Eliminar</span>
						<?php }else{ ?>
							<span class="itsyou">Admin</span>
						<?php } ?>
		    		</div>
		    	<?php } ?>
		    		
		    	</div>
			</div>
		</div>
		<div class='modal fade' id='deleteColaborador' role='dialog'> 
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="title-header modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4>Eliminar Colaborador</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<p class="col-xs-12">¿Seguro que deseas eliminar este colaborador?</p>
						</div>
					</div>
					<div class="footer-line modal-footer">
						<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button>
						| <button type="button" class="btn modal-btns" id="deleteCOlaboradorButton" data-dismiss="modal" data-u="" data-g="">Eliminar</button>
					</div>
				</div>
			</div>
		</div>
		<script src="/js/edit.js"></script>
		<script type="text/javascript" src="/js/colaboradores.js"></script>
<?php
		
}
else {
	?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>