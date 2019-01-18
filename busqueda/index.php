<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-01 15:44:54
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-12-20 16:05:32
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$Garage = new Garage;
$coder = new Coder();
$detalles = array();
$sess=true;
if(empty($_SESSION))
{
	$sess=false;
}
if(!isset($_SESSION["iduser"]))
{
	$sess=false;
}
$imgMeta="";

require_once ($_SERVER['DOCUMENT_ROOT']).'/php/auto/Anuncio.php';
$Anuncio = new Anuncio;
$ads=$Anuncio->getAdsForSearch($_GET["s"],0);
$k=0;
$totalAds=sizeof($ads);
while ($imgMeta==""&&$k<$totalAds) {
	if($ads[$k]["img"]!=""){
		$imgMeta=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$ads[$k]["img"];
	}
	$k++;
}
if($imgMeta==""){
	$imgMeta=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/img/main.jpg";
}
$metasShare=array(
    "og"    =>  array(
        "title" => "Encuentra el auto que buscas en AVI cars",
        "description" => "¿Est&aacute;s buscando un ".ucfirst($_GET["s"])."?, ¡Encu&eacute;ntralo en AVI cars!",
        "image" => $imgMeta,
        "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/busqueda/?s=".$_GET["s"],
        "site_name" => "AVI cars",
        "type" => "website"
    ),
    "tw"    =>  array(
        "title" => "Encuentra el auto que buscas en AVI cars",
        "description" => "¿Est&aacute;s buscando un ".ucfirst($_GET["s"])."?, ¡Encu&eacute;ntralo en AVI cars!",
        "image" => $imgMeta,
        "image:alt" => "AVI cars",
        "card" => "summary_large_image"
    )
);

if($sess)
{
	$cuenta = $_SESSION["iduser"];
	$Usuario = new Usuario;
    $nCuenta= $Usuario->getCuenta($cuenta);
    $nombreCuenta= $Usuario->getGarage();
    $agrega = $Usuario -> agregando($nCuenta, $cuenta);
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $infoPerfil = $Usuario->getInfoPerfil($_SESSION["iduser"]);
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1;
	$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
}
else{
	include ($_SERVER['DOCUMENT_ROOT']) . '/login/header.php';?>

  	<div class=" container-fluid main-container container-transparent">
	<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/Busqueda/headerBusqueda.php';
?>
<div class="search content content-no-margin content-no-publicity">
	<div class="row search-content" id="perfiles"></div>
	<div class="row search-content" id="garages"></div>
	<div class="row search-content active" id="autos">
		<div class="radio">
			<label class="radio-inline auto active">
				<input type="radio" name="autokind" class="hidden" value="0" id="firstCar" checked="true">
				Todos
			</label>
			<label class="">|</label>
			<label class="radio-inline anuncio ">
				<input type="radio" name="autokind" class="hidden" value="1">
				En Venta
			</label>
		</div>
	</div>
	<div class="row search-content " id="anuncios">
		<div class="radio">
			<label class="radio-inline auto active">
				<input type="radio" name="autokind" class="hidden" value="0" >
				Todos
			</label>
			<label class="">|</label>
			<label class="radio-inline anuncio ">
				<input type="radio" name="autokind" class="hidden" value="1" >
				En Venta
			</label>
		</div>
	</div>
<?php
if($sess)
{ ?>	
	<div class="row search-content" id="posts">
		
	</div>
	<div class="row advanced-search advanced-search-in">
		<div>
			<form class="busquedaAvanzada" method="POST" action="/busqueda/avanzada/">
	            <h3>Busqueda Avanzada</h3>
	            <div class="form-group selectdiv col-sm-3 col-xs-6">
	                <label class="control-label">Marca</label>
	                <select class="form-control form-style marcaSearch" name="marca">
	                    <option value="0" data-brand="0">Cualquiera</option>
	                <?php 
	                foreach ($marcas as $m => $marca) 
	                { 
	                    if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO')
	                    { ?>
	                        <option data-brand="<?= $marca?>" class="visible" value="<?= $m?>"><?= $marca?></option>
	                        <?php 
	                    }
	                } ?>
	                </select>
	            </div>
	            <div class="form-group selectdiv col-sm-3 col-xs-6" >
	                <label class="control-label">Modelo</label>
	                <select class="form-control form-style modeloSearch" name="modelo">
	                    <option value="0" data-marca="0">Cualquiera</option>
	                <?php 
	                foreach ($submarcas as $s => $submarca) 
	                { 
	                  ?>
	                    <option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
	                        <?php 
	                } ?>
	                </select>
	            </div>
	            <div class="form-group selectdiv col-sm-3 col-xs-6">
	                <label class="control-label">A&ntilde;o</label>
	                <select class="form-control form-style anoSearch" name="ano">
	                    <option value="0">Cualquiera</option>
	                </select>
	            </div>
	            <div class="form-group selectdiv col-sm-3 col-xs-6">
	                <label class="control-label">Clase</label>
	                <select class="form-control form-style" name="clase">
	                    <option value="0">Cualquiera</option>
	                <?php 
	                foreach ($clases as $c => $clase) 
	                { ?>
	                    <option class="visible" value="<?= $c?>"><?= $clase["description"] ?></option>
	                    <?php 
	                } ?>
	                </select>
	            </div>
	            <div class="form-group col-sm-3 col-xs-6">
	                <label class="control-label">Precio min.</label>
	                <input type="number" min="0" class="form-control form-style minprice" name="desde" placeholder="$ Desde">
	            </div>
	            <div class="form-group col-sm-3 col-xs-6">
	                <label class="control-label">Precio max.</label>
	                <input type="number" min="0" class="form-control form-style maxprice" name="hasta" placeholder="$ Hasta">
	            </div>
	            <div class="form-group selectdiv col-sm-3 col-xs-6">
	                <label class="control-label">Estado</label>
	                <select class="form-control form-style stateSearch" name="estado">
	                    <option value="0">Cualquiera</option>
	                <?php 
	                foreach ($estadosSearch as $e => $estado) 
	                { ?>
	                    <option class="visible" value="<?= $e?>"><?= $estado ?></option>
	                    <?php 
	                } ?>
	                </select>
	            </div>
	            <div class="form-group selectdiv col-sm-3 col-xs-6">
	                <label class="control-label">Municipio</label>
	                <select class="form-control form-style townSearch" name="municipio">
	                    <option value="0">Cualquiera</option>
	                </select>
	            </div>
	            <hr>            
	            <div class="form-group col-xs-12 col-sm-4 col-sm-offset-4 col-xs-offset-0 text-center ">
	               <button class="btn btn-block modal-btns"  type="submit">Buscar&nbsp;<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_busqueda_infotraffic.png"></button>
	            </div>
	        </form>
	   	</div>
	</div>
<?php } ?>
</div>
<?php if(!$sess)
{ ?>
</div>
<?php } ?>
<script type="text/javascript">
	var search='<?= $_GET["s"] ?>'
	var ptimes=[0,0,0,0,0];
	var loader='<div class="sk-cube-grid">'
            		+'<img id="wheel-reload" src="/img/me_enllanta_256px.gif" alt="">'
        		+'</div>';
    var dataFound=false;
	<?php
	if($sess)
	{ ?>
	function searchposts(){
		$("#posts .seemore").remove();
		$("#posts").append(loader);
		$.ajax({
			async : true,
			url : "posts.php",
			type : "post",
			data : "q="+search+"&t="+ptimes[4],
			dataType : "json",
			success : function(msg){
				$("#posts .sk-cube-grid").remove();
				$("#posts").append('<b class="search-title">POSTS</b>');
				var k=0;
				var result="";
				$.each(msg,function(i,e){
					img=(!e.img) ? "/img/icons/avatar1.png" : e.img;
					propietario=(e.owner) ? e.owner : '';
					precio='';
					if(e.precio!=null)
					{
						precio='<p class="text-justify">'+e.precio+'</p>';	
					}
					tipo=e.tipo;
					if(e.url!=null)
					{
						tipo='<a href="'+(e.url ? e.url : '#')+'">'+e.tipo+'</a>'
					}
					var comentarios=(e.comentarios>99) ? "+99" : e.comentarios;
					var likes=(e.likes>99) ? "+99" : e.likes;
					var shareds=(e.shareds>99) ? "+99" : e.shareds;
					result='<div class="col-xs-12 publication">'
						    	+'<div class="publication-header">'
						    		+'<img class="img-profile" src="'+img+'"></img>'
						    		+'<div class="head-info">'
						    			+'<div class="personal">'
						    				+e.autor+' > '+e.container
						    			+'</div>'
							    		+'<hr>'
							    		+'<div class="time">'
							    			+e.fecha
							    			+'<ul>'
						    					+'<li class="nocursor">'
						    						+'<span>'+comentarios+' </span>'
						    						+'<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/comment_yellow.png" alt="">'
						    					+'</li>'
						    					+'<li class="nocursor">'
						    						+'<span>'+likes+' </span>'
						    						+'<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">'
						    					+'</li>'
						    					+'<li class="nocursor">'
						    						+'<span>'+shareds+' </span>'
						    						+'<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_home_boton_sharepub_infotraffic_v2.png" alt="">'
						    					+'</li>'
						    				+'</ul>'
							    		+'</div>'
						    		+'</div>'
						    	+'</div>'
						    	+'<div class="publication-body">'
						    		+'<h5>'
						    			+tipo
									+'</h5>'
									+precio
									+'<p class="text-justify">'+e.texto+'</p>'
						    	+'</div>'
						    +'</div>';
					$("#posts").append(result);
					k++;
				});
				if(k>0)
				{
					dataFound=true;
					ptimes[4]++;
					$("#posts").append('<div class="col-xs-12 text-center seemore" onclick="searchposts()">Ver m&aacute;s</div>')
				}
				else if(ptimes[4]==0)
				{
					$("#posts").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound){
						$(".tabop[data-target=autos]").trigger("click");
						console.log("no");
						$(".advanced-search-in").addClass("active");
					}
				}
				//
			},
			error : function(){
				if(ptimes[4]==0)
				{
					$("#posts").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound){
						$(".tabop[data-target=autos]").trigger("click");
						console.log("no");
						$(".advanced-search-in").addClass("active");
					}
				}

			}
		});
	}
	<?php } ?>
	function searchProfile(){
		$("#perfiles .seemore").remove();
		$("#perfiles").append(loader);
		$.ajax({
			async : true,
			url : "perfiles.php",
			type : "post",
			data : "q="+search+"&t="+ptimes[0],
			dataType : "json",
			success : function(msg){
				$("#perfiles .sk-cube-grid").remove();
				$("#perfiles").append('<b class="search-title">PERSONAS</b>');
				var k=0;
				var result="";
				$.each(msg,function(i,e){
					img=(!e.img) ? "/img/icons/avatar1.png" : e.img;

					result='<div class="search-element" onclick="window.location.href=\'/perfil/?cuenta='+e.a_to+'\'"><div class="img-search-profile" style="background-image: url(\''+img+'\')"></div>'
					+'<p>'+e.name+' '+e.last_name+'<img src=" '+(e.privacidad == 1 ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png' : ( e.privacidad == 3 ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png' :'/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png') )+' " class="'+(e.privacidad == 1 ? 'private' : (e.privacidad == 3 ? 'secret' : 'public') )+'"></div>'
					$("#perfiles").append(result);
					k++;
				});
				if(k>0)
				{
					dataFound=true;
					ptimes[0]++;
					$("#perfiles").append('<div class="col-xs-12 text-center seemore" onclick="searchProfile()">Ver m&aacute;s</div>')
				}
				else if(ptimes[0]==0)
				{
					$("#perfiles").html("<h4 align='center'>No se encontraron datos</h4>");
					<?php
					if($sess)
					{ ?>
						if(!dataFound)
							$(".tabop[data-target=posts]").trigger("click");
					<?php }else{ ?>
						if(!dataFound){
							$(".tabop[data-target=anuncios]").trigger("click");
							console.log("no");
							$(".advanced-search-in").addClass("active");
						}
					<?php } ?>
				}
				//
			},
			error : function(){
				if(ptimes[0]==0)
				{
					$("#perfiles").html("<h4 align='center'>No se encontraron datos</h4>");
					<?php
					if($sess)
					{ ?>
						if(!dataFound)
							$(".tabop[data-target=posts]").trigger("click");
					<?php }else{ ?>
						if(!dataFound){
							$(".tabop[data-target=anuncios]").trigger("click");
							console.log("no");
							$(".advanced-search-in").addClass("active");
						}
					<?php } ?>
				}

			}
		});
	}
	function searchGarage(){
		$("#garages .seemore").remove();
		$("#garages").append(loader);
		$.ajax({
			async : true,
			url : "garages.php",
			type : "post",
			data : "q="+search+"&t="+ptimes[1],
			dataType : "json",
			success : function(msg){
				$("#garages .sk-cube-grid").remove();
				$("#garages").append('<b class="search-title">GARAGES</b>');
				var k=0;
				var result="";
				$.each(msg,function(i,e){
					img=(!e.img) ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : e.img;
					<?php
					if($sess)
					{ ?>	
					propietario=(e.name) ? e.name+" "+e.last_name : '';
					<?php }else{ ?>
					propietario="";
					<?php } ?>
					result='<div class="search-element" onclick="window.location.href=\'/perfil/garage/timeline/?cuenta='+e.a_to+'&garage='+e.g_to+'\'"><img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/MyGarages_Movil_boton_GarageX_infotraffic.png" class="garagebkgnd"><div class="img-search" style="background-image: url(\''+img+'\')"></div><p>'+e.nombre+'<img src=" '+(e.privacidad == 1 ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png' : ( e.privacidad == 3 ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png' :'/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png') )+' " class="'+(e.privacidad == 1 ? 'private' : (e.privacidad == 3 ? 'secret' : 'public') )+'"></p><span>'+propietario+'</span></div>'
					$("#garages").append(result);
					k++;
				});
				if(k>0)
				{
					dataFound=true;
					ptimes[1]++;
					$("#garages").append('<div class="col-xs-12 text-center seemore" onclick="searchGarage()">Ver m&aacute;s</div>')
				}
				else if(ptimes[1]==0)
				{
					$("#garages").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound)
						$(".tabop[data-target=perfiles]").trigger("click");
				}
				//
			},
			error : function(){
				if(ptimes[1]==0)
				{
					$("#garages").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound)
						$(".tabop[data-target=perfiles]").trigger("click");
				}

			}
		});
	}
	function searchCar(){
		$("#autos .seemore").remove();
		$("#autos").append(loader);
		$.ajax({
			async : true,
			url : "autos.php",
			type : "post",
			data : "q="+search+"&t="+ptimes[2],
			dataType : "json",
			success : function(msg){
				$("#autos .sk-cube-grid").remove();
				$("#autos").append('<b class="search-title">AUTOS</b>');
				var k=0;
				var result="";
				$.each(msg,function(i,e){
					img=(!e.img) ? "/img/noimage.png" : e.img;
					precio=(e.precio!=0) ? "<p class='text-green'><img src='/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyCars_Movil_viewport_features_icon-AUTOENVENTA_infotraffic.png' class='sell-icon-cars'>"+(e.currency=='EUR' ? '&#128; ' : '$ ')+e.precio+" "+e.currency+"</p>" : "";
					<?php
					if($sess)
					{ ?>
					propietario=(e.ownerName) ? e.ownerName : '';
					<?php }else{ ?>
					propietario="";
					<?php } ?>
					result='<div class="search-element '+((e.vendido==1) ? "sold" : "" )+'" onclick="window.location.href=\'/perfil/autos/detalles/?cuenta='+e.a_to+'&auto='+e.c_to+'\'"><div class="sello"></div><div class="img-search-car " style="background-image: url(\''+img+'\')"></div><p>'+e.nombre+'<img src=" '+(e.privacidad == 1 ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png' : ( e.privacidad == 3 ? '/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png' :'/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png') )+' " class="'+(e.privacidad == 1 ? 'private' : (e.privacidad == 3 ? 'secret' : 'public') )+'"></p>'+precio+'<span>'+propietario+'</span></div>'
					$("#autos").append(result);
					k++;
				});
				if(k>0)
				{
					dataFound=true;
					ptimes[2]++;
					$("#autos").append('<div class="col-xs-12 text-center seemore" onclick="searchCar()">Ver m&aacute;s</div>')
				}
				else if(ptimes[2]==0)
				{
					$("#autos").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound)
						$(".tabop[data-target=autos]").trigger("click");
				}
				//
			},
			error : function(){
				if(ptimes[2]==0)
				{
					$("#autos").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound)
						$(".tabop[data-target=autos]").trigger("click");
				}

			}
		});
	}
	function searchAd(){
		$("#anuncios .seemore").remove();
		$("#anuncios").append(loader);
		$.ajax({
			async : true,
			url : "anuncios.php",
			type : "post",
			data : "q="+search+"&t="+ptimes[3],
			dataType : "json",
			success : function(msg){
				$("#anuncios .sk-cube-grid").remove();
				$("#anuncios").append('<b class="search-title">ANUNCIOS</b>');
				var k=0;
				var result="";
				$.each(msg,function(i,e){
					var nombreAuto="";
					if(e.nombreMarca){
						nombreAuto+=e.nombreMarca;
					}
					if(e.nombreSubmarca){
						nombreAuto+=" "+e.nombreSubmarca;
					}
					if(e.nombreModelo){
						nombreAuto+=" "+e.nombreModelo;
					}
					if(e.nombreVersion){
						nombreAuto+=" "+e.nombreVersion;
					}
					if(nombreAuto==""){
						nombreAuto="Auto en venta";
					}
					ubicacion="";
					if(e.cp){
						ubicacion="<span style='font-weight:bolder; color:#00992c;'>"+e.municipio+", "+e.state+"</span><br>";
					}
					img=(!e.img) ? "/img/noimage.png" : e.img;
					precio=(e.precio!=0) ? "<p class='text-green'><img src='/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyCars_Movil_viewport_features_icon-AUTOENVENTA_infotraffic.png' class='sell-icon-cars'>"+(e.currency=='EUR' ? '&#128; ' : '$ ')+e.precio+" "+e.currency+"</p>" : "";
					garage="";

					if(e.nameGarage!=""){
						garage="<span>Garage: "+e.nameGarage+"</span>";
					}
					result='<div class="search-element" onclick="window.location.href=\'/anuncio/?a='+e.link+'\'"><div class="img-search-car" style="background-image: url(\''+img+'\')"></div><p>'+nombreAuto+'</p>'+precio+ubicacion+garage+'</div>'
					$("#anuncios").append(result);
					k++;
				});
				if(k>0)
				{
					dataFound=true;
					ptimes[3]++;
					$("#anuncios").append('<div class="col-xs-12 text-center seemore" onclick="searchAd()">Ver m&aacute;s</div>');
					
				}
				else if(ptimes[3]==0)
				{
					$("#anuncios").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound)
						$(".tabop[data-target=garages]").trigger("click");
				}
			},
			error : function(){
				if(ptimes[3]==0)
				{
					$("#anuncios").html("<h4 align='center'>No se encontraron datos</h4>");
					if(!dataFound)
						$(".tabop[data-target=garages]").trigger("click");
				}

			}
		});
	}
	$(document).ready(function(){
		$("input[name='autokind']").change(function(){
			if($(this).val()==0){
				$(".tabop").removeClass("active");
				$("li[data-target='autos']").addClass("active");
				$(".search-content").removeClass("active");
				$("#autos").addClass("active");
				$(".radio-inline.anuncio").removeClass("active");
				$(".radio-inline.auto").addClass("active");
			}
			else{
				$(".tabop").removeClass("active");
				$("li[data-target='autos']").addClass("active");
				$(".search-content").removeClass("active");
				$("#anuncios").addClass("active");
				$(".radio-inline.auto").removeClass("active");
				$(".radio-inline.anuncio").addClass("active");
			}
		})
		$(".tabop").click(function(){
			$(".tabop").removeClass("active");
			$(this).addClass("active");
			var target=$(this).data("target");
			$(".search-content").removeClass("active");
			$("#"+target).addClass("active");
			$(".radio-inline").removeClass("active");
			$(".radio-inline.auto").addClass("active");
			$("#firstCar").prop("checked","true");
		})
		searchCar();
		
		setTimeout(function(){
			searchAd();
		},500);
		setTimeout(function(){
			searchGarage();
		},1000);
		setTimeout(function(){
			searchProfile();
		},1500);

		
		
	<?php if($sess){ ?>
		setTimeout(function(){
			searchposts();
		},2000);
	<?php } ?>
	});
</script>
<?php 
if($sess)
{
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php'; 
}
else
{
	include ($_SERVER['DOCUMENT_ROOT']) . '/login/footer.php';
}

?>

