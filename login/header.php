<?php 
if((isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']=="http://avicars.app"){
    header("Location: https://avicars.app");
}
if($_SERVER['HTTP_HOST']=="www.avicars.app"){
    header("Location: https://avicars.app");
}
if(!isset($metasShare)){
    
    $metasShare=array(
        "og"    =>  array(
            "title" => "AVI cars by Infotraffic",
            "description" => "Vende compra y cuida tu carro con AVI cars",
            "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/img/metaimg.png",
            "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
            "site_name" => "AVI cars",
            "type" => "website"
        ),
        "tw"    =>  array(
            "title" => "AVI cars by Infotraffic",
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
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/styleProfile.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" href="/css/style.min.css?l=<?= LOADED_VERSION?>" />
    <link rel="stylesheet" href="/css/login.css?l=<?= LOADED_VERSION?>" />
    <link rel="stylesheet" href="/css/style.css?l=<?= LOADED_VERSION?>" />
    <link rel="stylesheet" type="text/css" href="/css/misGarages.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" type="text/css" href="/css/editAuto.css?l=<?= LOADED_VERSION?>">
    <link rel="stylesheet" href="/css/jquery-ui.css?l=<?= LOADED_VERSION?>" />
    <link rel="stylesheet" href="/js/jstree-master/dist/themes/default/style.min.css?l=<?= LOADED_VERSION?>" />
    <link rel="icon" href="/img/Home_Movil_logo_headline_sized_infotraffic__.png?l=<?= LOADED_VERSION?>"> 
    <link rel="stylesheet" type="text/css" href="/js/svg-with-js/css/fa-svg-with-js.css?l=<?= LOADED_VERSION?>">
    <script src="/js/jquery-1.12.4.min.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/jquery-ui.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/jquery.bootstrap.wizard.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/bootstrap.min.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/function.js?l=<?= LOADED_VERSION?>"></script>
    <script src="/js/carrusel.js?l=<?= LOADED_VERSION?>"></script>
    <link rel="stylesheet" type="text/css" href="/css/pnotify.custom.min.css?l=<?= LOADED_VERSION?>">
    <script src="/js/pnotify.custom.min.js?l=<?= LOADED_VERSION?>"></script>
    <script defer src="/js/svg-with-js/js/fontawesome-all.js?l=<?= LOADED_VERSION?>"></script>
    <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
    <meta name="google-signin-client_id" content="718589225630-1psterv4l5m27vq1nd8qjelbd6bc93am.apps.googleusercontent.com">
    <meta name="google-signin-scope" content="profile email">
    <script src="https://apis.google.com/js/platform.js" async defer></script>    
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-121900048-1"></script>
    <script type="text/javascript" src="/js/analytics.js?l=<?= LOADED_VERSION?>"></script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>
<nav class="navbar navbar-white navbar-default navbar-fixed-top top fondo-nav navbar-transparency search-nav visible-xs hidden-sm hidden-md hidden-lg">
    <div class="navbar nav-space row navi">
        <div class="nav-space top-header">
            <img class="avi-icon" src="/img/Home_Movil_logo_headline_sized_infotraffic.png" onclick="window.location.href='/'">
            <div class="busqueda-width">
                <form id="search" class="searchForm" action="/busqueda" method="GET">
                    <div class="grupo">
                        <span class="more-search">
                            
                        </span>
                        <input type="text" class="search-input" id="search-input" name="s"  value="<?= (isset($_GET["s"])) ? $_GET["s"] : ((isset($_GET["src"])) ? $_GET["src"] : "") ?>" placeholder="Buscar en AVIcars...">
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
<nav class="navbar navbar-white navbar-default navbar-fixed-top top fondo-nav navbar-transparency hidden-xs visible-sm visible-md visible-lg">
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
                            <input type="text" class="search-input" id="search-input" name="s" value="<?= (isset($_GET["s"])) ? $_GET["s"] : ((isset($_GET["src"])) ? $_GET["src"] : "") ?>" placeholder="Buscar en AVIcars...">
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
                <div id="registrate" class="registrate-style">
                    <form onsubmit="return false;" class="form-size">
                        <ul>
                            <li>
                                <div id="in_username" class="form-group username-mrg">
                                    <input type="text" class="form-control form-style" name="logInUsername" id="logInUsername" onkeypress="iniciarpress(event)" placeholder="Usuario / Email" maxlength="50">
                                </div>
                            </li>
                            <li>
                                <div id="in_pwdHeader" class="form-group pwd-mrg">
                                    <input type="password" class="form-control form-style" name="logInPassword" id="logInPassword" onkeypress="iniciarpress(event)" placeholder="Contrase&ntilde;a" maxlength="60">
                                    <p class="contra"><a class="a-contra" href="#" data-toggle="modal" data-target="#pwdModal">Olvid&eacute; mi contrase&ntilde;a</a></p>
                                </div>
                            </li> 
                            <li>
                                <button id="iniciar" type="submit" class="btn btn-default login-btn" <?= (isset($twitter)&&!$twitter) ? "onclick='conectar()'" : "" ?> > Iniciar Sesi&oacute;n </button>
                            </li> 
                        </ul>
                    </form> 
                </div>
            </li>
        </ul>
    </div>
</nav>

<nav class="navbar navbar-default navbar-fixed-top top fondo-nav navbar-transparency secondary-nav visible-xs hidden-sm hidden-md hidden-lg">
    <div class="navbar nav-space row navi">
        <div class="header-list-movil ">
            <div id="registrate" class="rowRegister nav navbar-nav">
                <form onsubmit="return false;" class="form-size">
                    <div class="rowRegister row rowLogin">
                        <div class="col-xs-4">
                            <div id="in_usernameHeader" class="form-group username-mrg">
                                <input type="text" class="form-control form-style" name="logInUsernameHeader" id="logInUsernameHeader" onkeypress="iniciarpressXs(event)" placeholder="Usuario / Email" maxlength="50">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div id="in_pwd" class="form-group pwd-mrg">
                                <input type="password" class="form-control form-style" name="logInPasswordHeader" id="logInPasswordHeader" onkeypress="iniciarpressXs(event)" placeholder="Contrase&ntilde;a" maxlength="60">
                                <p class="contra"><a class="a-contra" href="#" data-toggle="modal" data-target="#pwdModal">Olvid&eacute; mi contrase&ntilde;a</a></p>
                            </div>
                        </div> 
                        <div class="col-xs-4">
                            <button id="iniciarxs" type="submit" class="btn btn-default login-btn" <?= (isset($twitter)&&!$twitter) ? "onclick='conectar()'" : "" ?> > 
                                Iniciar Sesi&oacute;n 
                            </button>
                        </div> 
                    </div>
                </form> 
            </div>
        </div>
    </div>
</nav>
<div class="header head-form">
    <div class="login-cover" style="background-image: url('/img/todo_el_camino_1400.jpg')"></div>
</div>
<div class="container-fluid main-container">
    