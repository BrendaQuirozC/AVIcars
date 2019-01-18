<?php

require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicationDate.php';
date_default_timezone_set('America/Mexico_City');
if((isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']=="http://avicars.app"){
    header("Location: https://avicars.app");
}
if($_SERVER['HTTP_HOST']=="www.avicars.app"){
    header("Location: https://avicars.app");
}
if(!isset($_SESSION["iduser"]) && !isset($_GET["token"])){
    header("Location: /");
    exit;
}

$_SESSION["loads"]++;
$owner=false;
$imgPerfilOwnCuenta = NULL;
$following=false;
$statusGuest=null;
if(!empty($_SESSION) && isset($_GET["cuenta"]) && $_SESSION["iduser"]==$_GET["cuenta"])
{
    $owner=true;
}  
if(!empty($_SESSION) && !isset($_GET["cuenta"]) && isset($_GET["garage"]) && isset($garage["user"]) && $garage["user"]==$_SESSION["iduser"])
{
    $owner=true;
}  
if(!empty($_SESSION))
{
    $imgPerfilOwnCuenta = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $detallesOwner = $Garage -> getUserdetail($_SESSION["iduser"]);
    $typeFollow=json_decode($privacyToChange,true)["tipo"];
    if(!$owner)
    {
        if($typeFollow==1 && isset($_GET["cuenta"]))
        {  
            $statusGuest = $Usuario->getStatusUser($_GET["cuenta"]);
            $Seguidor = new Seguidor($typeFollow, $_SESSION["iduser"], $_GET["cuenta"]);
            if($Seguidor->idAquienSigues)
            {
                $following=true;
            }      
        }
        elseif($typeFollow==2 && isset($_GET["garage"]))
        {
            $statusGuest = $Usuario->getStatusUser($_GET["cuenta"]);
            $Seguidor = new Seguidor($typeFollow, $_SESSION["iduser"], $_GET["garage"]);
            if($Seguidor->idAquienSigues)
            {
                $following=true;
            } 
        }
        elseif($typeFollow==3 && isset($_GET["auto"]))
        {
            $statusGuest = $Usuario->getStatusUser($_GET["cuenta"]);
            $Seguidor = new Seguidor($typeFollow, $_SESSION["iduser"], $_GET["auto"]);
            if($Seguidor->idAquienSigues)
            {
                $following=true;
            } 
        }
    }
    $notAllowedUsers=$Usuario->notAccesibleUsers($_SESSION["iduser"]);
    $blocked=false;
    if(isset($_GET["cuenta"]))
    {
        if(in_array($_GET["cuenta"], $notAllowedUsers)){
            $blocked=true;
        }
    }

} 
if(!isset($metasShare)){
    
    $metasShare=array(
        "og"    =>  array(
            "title" => "AVI cars by Infotraffic | Timeline",
            "description" => "Vende compra y cuida tu carro con AVI cars",
            "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/img/metaimg.png",
            "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
            "site_name" => "AVI cars",
            "type" => "website"
        ),
        "tw"    =>  array(
            "title" => "AVI cars by Infotraffic | Timeline",
            "description" => "Vende compra y cuida tu carro con AVI cars",
            "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/img/metaimg.png",
            "image:alt" => "AVI cars",
            "card" => "summary_large_image"
        )
    );
}
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once  $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
$marcas=Auto::getMarcas();
$submarcas=Auto::getSubMarcas();
$modelos=Auto::getModels();
$versiones=Auto::knowVersion();
$clases = Auto::getClass();
$country=new Country;
$estadosSearch=$country->states();
const LOADED_VERSION=30;
?>
<!DOCTYPE html>
<html>
<head>
    <title> AVI cars</title>
    <!-- Latest compiled and minified CSS -->
    <meta charset="utf-8">
    <meta name="description" content="Vende compra y cuida tu carro con AVI cars." />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta property="fb:app_id" content="1651717514914692">
    <?php
    if(isset($metasShare)){
        foreach ($metasShare["og"] as $m => $meta) { ?>
            <meta property="og:<?= $m ?>" content="<?= $meta ?>">
        <?php }
        foreach ($metasShare["tw"] as $m => $meta) { ?>
            <meta name="twitter:<?= $m ?>" content="<?= $meta ?>">
        <?php }
    }
    ?>
    <link rel="stylesheet" href="/css/bootstrap.min.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" href="/css/dropzone.css?l=<?= LOADED_VERSION?>">   
    <link rel="stylesheet" type="text/css" href="/css/styleProfile.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/style.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/animate.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/carrusel.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/follow.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/js/svg-with-js/css/fa-svg-with-js.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" href="/js/jstree-master/dist/themes/default/style.min.css?l=<?= LOADED_VERSION?>" />
    <link rel="stylesheet" type="text/css" href="/css/pnotify.custom.min.css?l=<?= LOADED_VERSION?>">
    <link rel="icon" href="/img/Home_Movil_logo_headline_sized_infotraffic__.png?l=<?= LOADED_VERSION?>"> 
    <link rel="stylesheet" href="/css/catalogo.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/misGarages.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/editAuto.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/croppie.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/jquery.mentionsInput.css?l=<?= LOADED_VERSION?>">
    <script src="/js/share.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/functionCatalogo.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/jquery-3.1.1.min.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/jquery-ui.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/bootstrap.min.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/function.js?l=<?= LOADED_VERSION?>"></script>
    <script src='/js/jquery.mentionsInput.js' type='text/javascript'></script>
    <!--<link rel="stylesheet" href="/js/jstree-master/dist/themes/default/style.min.css" />-->
    <script src="/js/carrusel.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/pnotify.custom.min.js?l=<?= LOADED_VERSION?>"></script>
    <script defer src="/js/svg-with-js/js/fontawesome-all.js?l=<?= LOADED_VERSION?>"></script>
    <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
    <meta name="google-signin-client_id" content="718589225630-1psterv4l5m27vq1nd8qjelbd6bc93am.apps.googleusercontent.com">
    <meta name="google-signin-scope" content="profile email">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="/js/croppie/croppie.min.js?l=<?= LOADED_VERSION?>"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121900048-1"></script>
    <script type="text/javascript" src="/js/analytics.js?l=<?= LOADED_VERSION?>"></script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head> 
<body>
<nav class="navbar navbar-default navbar-fixed-top top fondo-nav navbar-transparency search-nav visible-xs hidden-sm hidden-md hidden-lg primary-nav">
    <div class="navbar nav-space row navi">
        <div class="nav-space top-header">
            <div class="busqueda-width">
                <form id="search" class="searchForm" action="/busqueda" method="GET">
                    <div class="grupo">
                        <span class="more-search">
                            
                        </span>
                        <input type="text" class="search-input" id="search-input" name="s" value="<?= (isset($_GET["s"])) ? $_GET["s"] : ((isset($_GET["src"])) ? $_GET["src"] : "") ?>">
                        <span>
                            <button type="submit">
                                <img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_busqueda_infotraffic.png" class="pointer">
                            </button>
                        </span>
                    </div>
                </form>
                <div class="advanced-search">
                    <form class="busquedaAvanzada" method="POST" action="/busqueda/avanzada/">
                        <h3>Busqueda Avanzada</h3>
                        <div class="form-group selectdiv col-xs-6">
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
                        <div class="form-group selectdiv col-xs-6" >
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
                        <div class="form-group selectdiv col-xs-6">
                            <label class="control-label">A&ntilde;o</label>
                            <select class="form-control form-style anoSearch" name="ano">
                                <option value="0">Cualquiera</option>
                            </select>
                        </div>
                        <div class="form-group selectdiv col-xs-6">
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
                        <div class="form-group col-xs-6">
                            <label class="control-label">Precio min.</label>
                            <input type="number" min="0" class="form-control form-style minprice" name="desde" placeholder="$ Desde">
                        </div>
                        <div class="form-group col-xs-6">
                            <label class="control-label">Precio max.</label>
                            <input type="number" min="0" class="form-control form-style maxprice" name="hasta" placeholder="$ Hasta">
                        </div>
                        <div class="form-group selectdiv col-xs-6">
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
                        <div class="form-group selectdiv col-xs-6">
                            <label class="control-label">Municipio</label>
                            <select class="form-control form-style townSearch" name="municipio">
                                <option value="0">Cualquiera</option>
                            </select>
                        </div>
                        <hr>            
                        <div class="form-group col-xs-12 text-center footer-btn">
                           <button class="btn btn-block modal-btns"  type="submit">Buscar&nbsp;<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_busqueda_infotraffic.png"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
<nav class="navbar navbar-default navbar-fixed-top top fondo-nav navbar-transparency hidden-xs visible-sm visible-md visible-lg">
    <div class="navbar nav-space row navi">
        <ul class="nav navbar-nav ">
            <li>
                <a href="/timeline" class="navbar-brand" style="padding-top: 0px">
                    <img src="/img/logo_horizontal.png" class="img-responsive img-logo">
                </a>
            </li>
            <li>
                <div class="busqueda-width">
                    <form id="search" class="searchForm" action="/busqueda" method="GET">
                        <div class="grupo">
                            <span class="more-search">
                                
                            </span>
                            <input type="text" class="search-input" id="search-input" name="s" value="<?= (isset($_GET["s"])) ? $_GET["s"] : ((isset($_GET["src"])) ? $_GET["src"] : "") ?>">
                            <span>
                                <button type="submit">
                                    <img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_busqueda_infotraffic.png" class="pointer">
                                </button>
                            </span>
                        </div>
                    </form>
                    <div class="advanced-search">
                        <form class="busquedaAvanzada" method="POST" action="/busqueda/avanzada/">
                            <h3>Busqueda Avanzada</h3>
                            <div class="form-group selectdiv col-xs-6">
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
                            <div class="form-group selectdiv col-xs-6">
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
                            <div class="form-group selectdiv col-xs-6">
                                <label class="control-label">A&ntilde;o</label>
                                <select class="form-control form-style anoSearch" name="ano">
                                    <option value="0">Cualquiera</option>
                                </select>
                            </div>
                            <div class="form-group selectdiv col-xs-6">
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
                            <div class="form-group col-xs-6">
                                <label class="control-label">Precio min.</label>
                                <input type="number" min="0" class="form-control form-style minprice" name="desde" placeholder="$ Desde">
                            </div>
                            <div class="form-group col-xs-6">
                                <label class="control-label">Precio max.</label>
                                <input type="number" min="0" class="form-control form-style maxprice" name="hasta" placeholder="$ Hasta">
                            </div>
                            <div class="form-group selectdiv col-xs-6">
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
                            <div class="form-group selectdiv col-xs-6">
                                <label class="control-label">Municipio</label>
                                <select class="form-control form-style townSearch" name="municipio">
                                    <option value="0">Cualquiera</option>
                                </select>
                            </div>
                            <hr>            
                            <div class="form-group col-xs-12 text-center footer-btn">
                               <button class="btn btn-block modal-btns" type="submit">Buscar&nbsp;<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_busqueda_infotraffic.png"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </li>
            <li>
                <a  href="/notificaciones" class="pointer">
                    <img class="<?= isset($follows) ? "actives" : ""?>" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/seguidores_white.png" class="icon-width active-followers">
                    <span class="counter" id="countNotifications">0</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="icon-user-btn" data-open="0">
                    <img src="<?= isset($imgPerfilOwnCuenta["avatar"]) ? $imgPerfilOwnCuenta["avatar"] : "/img/icons/avatar1.png" ?>" class="icon-width active-perfil icon-user">
                </a>
                <span class="submenu-caret-outer"></span>
                <span class="submenu-caret-inner"></span>
                <ul class="submenu-profile">
                    <li>
                        <a <?= !empty($_SESSION) ? 'href="/perfil/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                            <img src="<?= isset($imgPerfilOwnCuenta["avatar"]) ? $imgPerfilOwnCuenta["avatar"] : "/img/icons/avatar1.png" ?>" class="icon-width active-perfil icon-user profile-img">
                            Mi Perfil
                        </a>
                    </li>
                    <li>
                        <a <?= !empty($_SESSION) ? 'href="/perfil/seguidores/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                            <img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
                            Mis Seguidores
                        </a>
                    </li>
                    <li>
                        <a <?= !empty($_SESSION) ? 'href="/perfil/garage/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                            <img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="navigation-icon">
                            Mis Garages
                        </a>
                    </li>
                    <li>
                        <a <?= !empty($_SESSION) ? 'href="/perfil/autos/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                            <img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon">
                            Mis Autos
                        </a>
                    </li>
                    <li>
                        <a <?= !empty($_SESSION) ? 'href="/perfil/docs/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                            <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
                            Mi Expediente
                        </a>
                    </li>
                    <li class="divider">
                        <hr>
                    </li>
                    <li >
                        <a <?= !empty($_SESSION) ? 'href="/perfil/edit/cuenta/" target="_blank"' : 'href="/"' ?>>
                            <img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png" class="navigation-icon">
                            Configuraci&oacute;n
                        </a>
                    </li>
                    <li >
                        <a <?= !empty($_SESSION) ? 'href="https://apoyovial.net/acerca-de/" target="_blank"' : 'href="/"' ?>>
                            <img src="/img/icons/info_icon.png" class="navigation-icon">
                            Acerca de
                        </a>
                    </li>
                    <li >
                        <a <?= !empty($_SESSION) ? 'href="/ayuda/" target="_blank"' : 'href="/"' ?>>
                            <img src="/img/icons/question.png" class="navigation-icon">
                            Ayuda
                        </a>
                    </li>
                </ul>
            </li>  
            <li><a data-toggle="modal" class="pointer" onclick="logout();" href="#"><img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/log-out_.png" class="icon-width"></a></li> 
        </ul>
    </div>
</nav>
<nav class="navbar navbar-default navbar-fixed-top top fondo-nav navbar-transparency secondary-nav visible-xs hidden-sm hidden-md hidden-lg">
    <div class="navbar nav-space row navi">
        <div class="header-list-movil ">
            <ul class="nav navbar-nav">
                <li>
                    <a href="/timeline" class="">
                        <img src="/img/Home_Movil_logo_headline_sized_infotraffic.png" class="img-responsive center-block img-logo">
                    </a>
                </li>
                <li>
                    <a href="/notificaciones" class="pointer">
                        <img class="<?= isset($follows) ? "actives" : ""?>" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/seguidores_white.png" class="icon-width active-followers">
                        <span class="counter" id="countNotifications">0</span>
                    </a>
                </li>  
                <li>
                    <a href="#" class="icon-user-btn" data-open="0">
                        <img src="<?= isset($imgPerfilOwnCuenta["avatar"]) ? $imgPerfilOwnCuenta["avatar"] : "/img/icons/avatar1.png" ?>" class="icon-width active-perfil icon-user">
                    </a>
                    <span class="submenu-caret-outer"></span>
                    <span class="submenu-caret-inner"></span>
                    <ul class="submenu-profile">
                        <li>
                            <a <?= !empty($_SESSION) ? 'href="/perfil/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                                <img src="<?= isset($imgPerfilOwnCuenta["avatar"]) ? $imgPerfilOwnCuenta["avatar"] : "/img/icons/avatar1.png" ?>" class="icon-width active-perfil icon-user profile-img">
                                Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a <?= !empty($_SESSION) ? 'href="/perfil/seguidores/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                                <img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
                                Mis Seguidores
                            </a>
                        </li>
                        <li>
                            <a <?= !empty($_SESSION) ? 'href="/perfil/garage/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                                <img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="navigation-icon">
                                Mis Garages
                            </a>
                        </li>
                        <li>
                            <a <?= !empty($_SESSION) ? 'href="/perfil/autos/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                                <img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon">
                                Mis Autos
                            </a>
                        </li>
                        <li>
                            <a <?= !empty($_SESSION) ? 'href="/perfil/docs/?cuenta='.$_SESSION["usertkn"].'"' : 'href="/"' ?>>
                                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
                                Mi Expediente
                            </a>
                        </li>
                        <li class="divider">
                            <hr>
                        </li>
                        <li >
                            <a <?= !empty($_SESSION) ? 'href="/perfil/edit/cuenta/" target="_blank"' : 'href="/"' ?>>
                                <img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png" class="navigation-icon">
                                Configuraci&oacute;n
                            </a>
                        </li>
                        <li >
                            <a <?= !empty($_SESSION) ? 'href="https://apoyovial.net/acerca-de/" target="_blank"' : 'href="/"' ?>>
                                <img src="/img/icons/info_icon.png" class="navigation-icon">
                                Acerca de
                            </a>
                        </li>
                        <li >
                            <a <?= !empty($_SESSION) ? 'href="/ayuda/" target="_blank"' : 'href="/"' ?>>
                                <img src="/img/icons/question.png" class="navigation-icon">
                                Ayuda
                            </a>
                        </li>
                    </ul>
                </li>   
            </ul>
        </div>
    </div>
</nav>
<?php 
if(isset($_SESSION["user"]))
{
    $status = $Usuario->getStatusUser($_SESSION["iduser"]);
    if($status==3)
    {
    ?>
    <nav class="navbar navbar-fixed-top down-nav hidden-xs visible-sm visible-md visible-lg">
        <div class="col-md-12 col-xs-12 no-padding ">
            <div class="text-center title-warning">
                Revisa tu correo electr&oacute;nico <b><?= $_SESSION["mail"]?></b> y confirma tu registro o <a class="pointer link-warning" onclick="reenviarCorreo()">reenviar correo</a>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-fixed-top alert-nav visible-xs hidden-sm hidden-md hidden-lg">
        <div class="col-md-12 col-xs-12 no-padding ">
            <div class="text-center title-warning">
                Revisa tu correo electr&oacute;nico <b><?= $_SESSION["mail"]?></b> y confirma tu registro o <a class="pointer link-warning" onclick="reenviarCorreo()">reenviar correo</a>
            </div>
        </div>
    </nav>
    <?php
    } 
}
    include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
    include ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/contactUs.php';
?>
<div id="publication-Modal" class="publicationModal modalAv modal">
    <span class="close" onclick="closePublishSlide()">&times;</span>
    <div id="publishSlide" class="modal-content">
        <div id="posts-slide" class="carousel" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
            </div>
            <a class="left carousel-control" href="#posts-slide" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#posts-slide" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
  <div class=" container-fluid main-container">

