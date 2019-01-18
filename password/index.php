<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
include ($_SERVER['DOCUMENT_ROOT']).'/login/header.php';
?>

<div class="container-fluid login-container" >
    <img src="/img/logo_horizontal.png" alt="" class="login-logo">
    <div id="registrate" class="container-fluid no-padd login-font" >
        <strong><h2 class="login-title">Cambiar tu contrase&ntilde;a de manera segura:</h2></strong> 
        <form onsubmit="return false;" class="form-size form-session">
            <div class="col-md-12 col-sm-12">
                <div id="check_new_pwd" class="form-group">
                    <input type="password" class="form-control form-style" name="newPassword" id="newPassword" placeholder="Nueva contrase&ntilde;a (Mínimo 8 carácteres)" onkeypress="registrarpress(event)">
                </div>
            </div>  
            <div class="col-md-12 col-sm-12">
                <div id="recheck_new_pwd" class="form-group">
                    <input type="password" class="form-control form-style" name="reNewPassword" id="reNewPassword" placeholder="Confirma tu nueva contrase&ntilde;a" onkeypress="registrarpress(event)">
                </div>
            </div>
        </form> 
        <div class="text-center">
            <button id="renovar" type="submit" class="btn btn-default login-btn" data-token="<?=$_GET["token"]?>" onclick="cambiar($(this))"> Cambiar Contrase&ntilde;a </button>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/password.js?l=<?= LOADED_VERSION?>"></script>
</div>
</body>
</html>