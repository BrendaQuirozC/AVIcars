Number.prototype.str_pad = function(l, t){
    var n = this, 
        str = "" + n,
        pad = "";
        for(i=0;i<l;i++)
        {
            pad=pad+t;
            //console.log(pad);
        }
        var ans = pad.substring(0, l - str.length) + str
        return ans;
};
function zip(e) {
    var code = e.val();
    $.ajax({
        type: 'POST',
        url: '/php/signup/zipControl.php',
        data: "code=" + code,
        success: function(resp) {
            if(resp!=0)
            {
                var add1 = resp;
                var add1json = JSON.parse(add1);
                $("#delegacion").val(add1json["city"]);
                $("#estado").val(add1json["state"]);
                $("#pais").val(add1json["country"]);
            }
            else{
                $("#delegacion").val("");
                $("#estado").val("");
                $("#pais").val("");
            }
        }
    });
}
function iniciarpress(e){
    var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
    if (key === 13) {
        $('#iniciar').trigger('click');
    }
}
function iniciarpressXs(e){
    var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
    if (key === 13) {
        $('#iniciarxs').trigger('click');
    }
}
function iniciarpressModal(e){
    var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
    if (key === 13) {
        $('#iniciarModal').trigger('click');
    }
}
function registrarpress(e){
    var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
    if (key === 13) {
        $('#regis').trigger('click');
    }
}
function enviar(){
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    var d = new Date();
    var actualyear = d.getFullYear();
    var mes=$("#mesNac").val();
    var ano=$("#anoNac").val();
    var dia=$("#diaNac").val();
    mes*=1;
    ano*=1;
    dia*=1;
    bdate=ano+"-"+mes.str_pad(2,0)+"-"+dia.str_pad(2,0);
    //return false;
    var response=grecaptcha.getResponse(widgetId1);
    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!nameRegex.test($("#signUpName").val()) || $("#signUpName").val() === "" || !isNaN($("#signUpName").val()))
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, nombre no v&aacute;lido.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_name").addClass('has-error');
        $("#signUpName").focus();
        return false;
    }
    if($("#signUpName").val().length <= 1){
        $(".form-group").removeClass('has-error');
        new PNotify({
            title: 'AVI cars:',
            text: 'El nombre es demasiado corto.',
            type: 'error'
        });
        $("#check_name").addClass('has-error');
        $("#signUpName").focus();
        return false;
    }
    var lastnameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!lastnameRegex.test($("#signUpLastName").val()) || $("#signUpLastName").val()==="" || !isNaN($("#signUpLastName").val())){
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, apeliido(s) no v&aacute;lido(s).',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_lastname").addClass('has-error');
        $("#signUpLastName").focus();
        return false;
    }
    if($("#signUpLastName").val().length <= 1){
        $(".form-group").removeClass('has-error');
        new PNotify({
                title: 'AVI cars:',
                text: 'El apellido es demasiado corto.',
                type: 'error'
            });
        $("#check_lastname").addClass('has-error');
        $("#signUpLastName").focus();
        return false;
    }
    if($("#signUpEmail").val()===""){
        new PNotify({
                title: 'AVI cars:',
                text: 'Hay un campo vac&iacute;o.',
                type: 'error'
            });
        $(".form-group").removeClass('has-error');
        $("#check_mail").addClass('has-error');
        $("#signUpEmail").focus();
        return false;
    }
    if(!emailRegex.test($("#signUpEmail").val()))
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'Correo elctrónico no v&aacute;lido.',
                type: 'error'
            });
        $("#mail").closest(".form-group").addClass("has-error");
        $("#signUpEmail").focus();
        return false;
    }
    var usernameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^])*$/g);
    if($("#signUpUsername").val()===""){
        new PNotify({
            title: 'AVI cars:',
            text: 'Hay un campo vac&iacute;o.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_username").addClass('has-error');
        $("#signUpUsername").focus();
        return false;
    }
    if(!usernameRegex.test($("#signUpUsername").val()))
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'Usuario no vac&iacute;o.',
                type: 'error'
            });
        $("#mail").closest(".form-group").addClass("has-error");
        $("#signUpEmail").focus();
        return false;
    }
    var pwdRegExp = new RegExp(/^([a-zA-Z0-9]|[$&@*\-_()!?¿¡.,;:])*$/g);
    if($("#signUpPassword").val()===""){
        new PNotify({
            title: 'AVI cars:',
            text: 'Hay un campo vac&iacute;o.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_pwd").addClass('has-error');
        $("#signUpPassword").focus();

        return false;
    }
    if(!pwdRegExp.test($("#signUpPassword").val()))
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'contraseña no vac&iacute;o.',
                type: 'error'
            });
        $("#mail").closest(".form-group").addClass("has-error");
        $("#signUpEmail").focus();
        return false;
    }
    if($("#signUpPassword").val().length < 8){
        $(".form-group").removeClass('has-error');
        new PNotify({
            title: 'AVI cars:',
            text: 'La contraseña debe tener un mínimo de 8 carácteres.',
            type: 'error'
        });
        $("#check_pwd").addClass('has-error');
        $("#signUpPassword").focus();
        return false;
    }
    if($("#terminos:checked").val()!="on")
    {
        $(".form-group").removeClass('has-error');
        $("#terminos").addClass('has-error');
        new PNotify({
            title: 'AVI cars:',
            text: 'Necesitas aceptar terminos y condiciones',
            type: 'error'
        });
        $("#terminos").focus();
        return false;
    }
    if($("#reSignUpPassword").val()==="" ||  $("#reSignUpPassword").val()!==$("#signUpPassword").val())
    {
        $(".form-group").removeClass('has-error');
        new PNotify({
            title: 'AVI cars:',
            text: 'Las contraseñas no coinciden.',
            type: 'error'
        });
        $("#recheck_pwd").addClass('has-error');
        $("#reSignUpPassword").focus();
        return false;
    }
    var regExDate = /^\d{4}-\d{2}-\d{2}$/;
    if(!bdate.match(regExDate)){
        $(".form-group").removeClass('has-error');
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Por favor ingrese una fecha de nacimiento válida.',
                    type: 'error'

                });
        $(".dateInput").addClass('has-error');
        $(".dateInput").focus();
        return false;
    }
    var dateBirth = new Date(bdate);
    if(!dateBirth.getTime() && dateBirth.getTime() !== 0)
    {
        $(".form-group").removeClass('has-error');
        new PNotify({
            title: 'AVI cars:',
            text: 'Por favor ingrese una fecha de nacimiento válida.',
            type: 'error'
        });
        $(".dateInput").addClass('has-error');
        $(".dateInput").focus();
        return false;
    }
    if(!response)
    {
        $(".g-recaptcha").children("div").addClass("error-captcha");
        new PNotify({
            title: 'AVI cars:',
            text: '¡Parece que eres un robot!',
            type: 'error'
        });
        return false;                                    
    }
    else
    {
        $(".g-recaptcha").children("div").removeClass("error-captcha");
    }
    $("#signUpBirthdate").val(bdate);
    data = $("form").serialize();
    $.ajax({
        type: 'POST',
        url: '/php/signup/userRegister.php',
        data: data,
        dataType: "json",
        success: function(resp) {
            if(resp.Error)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: resp.Error,
                    type: 'error'
                });
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Enviamos un correo de confirmación revisa tu bandeja de entrada.',
                    type: 'info'
                });
                if(location.pathname == "/login/")
                {
                    location.replace("../perfil/?cuenta="+resp.Success);
                }
                else
                {
                    setTimeout(function(){
                        window.location.href="../perfil/?cuenta="+resp.Success;
                    },2500);
                }
            }
        }
    });
}

/*function sendMail(){
    $.ajax({
        type: 'POST',
        url: '/libraries/phpmailer/sendmail.php',
        success: function(resp) {
            
        }
    })
}*/

function confirmmail(e) {
    
        $("#signUpUsername").val(apodo(e.val()));
    
}

function apodo(email){
    new_username = [];
    for(i=0; i<email.length;i++)
    {
        if(email[i]!="@"){
            new_username.push(email[i]);
        }else
        {
            break;
        }
    }
    nusn=new_username.join('');
    return nusn;
}

function completar(){
    var $modal = false;
    $(".form-group").removeClass('has-error');
    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^])*$/g);
    if(!nameRegex.test($("#changeName").val()) || $("#changeName").val()==="" || $("#changeName").val().length <= 1)
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Nombre no v&aacute;lido.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#changeName").closest(".form-group").addClass("has-error");
        $("#changeName").focus();
        return false;
    }

    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^])*$/g);
    if(!nameRegex.test($("#changeLastName").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Apellido no v&aacute;lido.',
            type: 'error'
            });
        $(".form-group").removeClass('has-error');
        $("#changeLastName").closest(".form-group").addClass("has-error");
        $("#changeLastName").focus();
        return false;
    }
    var usernameRegex =new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^])*$/g);
    if($("#changeNameUser").val()==="" || !usernameRegex.test($("#changeNameUser").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Nombre de usuario no v&aacute;lido. Se aceptan todos los caracteres excepto comillas y el signo = .',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#username_user").addClass('has-error');
        $("#changeNameUser").focus();
        return false;
    }
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    if(!emailRegex.test($("#signUpEmail").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Correo elctrónico no válido.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#signUpEmail").closest(".form-group").addClass("has-error");
        $("#signUpEmail").focus();
        return false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test($("#signUpPhone").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Tel&eacute;fono no v&aacute;lido. &Uacute;nicamente n&uacute;meros sin espacios.',
                    type: 'error'

                });
        $(".form-group").removeClass('has-error');
        $("#signUpPhone").closest(".form-group").addClass("has-error");
        $("#signUpPhone").focus();
        return false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test($("#signUpCellPhone").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Tel&eacute;fono no v&aacute;lido. &Uacute;nicamente n&uacute;meros sin espacios.',
                    type: 'error'

                });
        $(".form-group").removeClass('has-error');
        $("#signUpCellPhone").closest(".form-group").addClass("has-error");
        $("#signUpCellPhone").focus();
        return false;
    }
    var bioRegex = new RegExp(/^([^'"=])*$/g);
    if(!bioRegex.test($("#biografia").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en biograf&iacute;a. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#biografia").closest(".form-group").addClass("has-error");
        $("#biografia").focus();
        return false;
    }
    var bioRegex = new RegExp(/^([^'"=])*$/g);
    if(!bioRegex.test($("#signUpStreet").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en direcci&oacute;n. No se permite el uso de comillas o signo = .',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#signUpStreet").closest(".form-group").addClass("has-error");
        $("#signUpStreet").focus();
        return false;
    }
    var cpRegex = new RegExp(/^[0-9]*$/g);
    if(!cpRegex.test($("#signUpZipcode").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en c&oacute;digo postal. &Uacute;nicamente n&uacute;meros sin espacios.',
            type: 'error'
        });
        $("#signUpZipcode").closest(".form-group").addClass("has-error");
        $("#signUpZipcode").focus();
        return false;
    }
    
    $.ajax({
        type: 'POST',
        url: '/php/login/loginMethod.php',
        async: false,
        data : $("#editProfile").serialize(),
        success: function(resp)
        {
            if(resp==="WP")
            {
                modalVerifyPw();
                $modal = true;    
            }
            else
            {
                $modal = false;
            }
        }
    });
    if($modal)
    {
        $("#sendData").click(function(){
            if($("#veryfiPw").val()=="")
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Debes ingresar tu contraseña',
                    type: 'error'
                });
            }
            else
            {
                var dataForm = new FormData($("#editProfile")[0]);
                dataForm.append("pw",$("#veryfiPw").val());
                sendData(dataForm);
            }
        })
    } 
    else
    {
        var dataForm = new FormData($("#editProfile")[0]);
        sendData(dataForm);
    }
}

function sendData(data1)
{
    $.ajax({
        type: 'POST',
        url: '/php/login/userInfo.php',
        data: data1,
        dataType: "json",
        async:false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(resp) {
            if(resp.Error)
            {
                $("#changeNameUser").next("span.alert-danger").remove();
                $('#modalverifyPW').modal('hide');
                if(resp.Error=="El usuario ya existe.")
                {
                    $("#changeNameUser").focus();
                    $("#changeNameUser").after('<span class="alert-danger" style="position:absolute;">Este nombre de usuario se encuentra en uso</span>');
                }
                new PNotify({
                    title: 'AVI cars:',
                    text: resp.Error,
                    type: 'error'
                });
            } 
            else 
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: '¡Informaci&oacute;n actualizada!',
                    type: 'success'
                });
                if(resp.Message){
                    new PNotify({
                        title: 'AVI cars:',
                        text: resp.Message,
                        type: 'info'
                    });
                }
                setTimeout(function(){window.location.href="/perfil/?cuenta="+resp.Success},2000);
            }
        }
    });
}
function modalVerifyPw()
{
    $htmlModal ='<div class="modal fade" id="modalverifyPW" tabindex="-1" role="dialog">' +
        '<div class="modal-dialog" role="document">'+
        '   <div class="modal-content">'+
        '       <div class="title-header modal-header">'+
        '           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '           Confirma que eres administrador de esta cuenta'+
        '       </div>'+
        '       <div class="modal-body">' + 
        '           <label for="verify">Ingresa tu contraseña:</label>'+
        '           <input type="password" class="form-control form-style" name="veryfiPw" id="veryfiPw">'+
        '       </div>'+
        '   <div class="footer-line modal-footer">'+
        '       <button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |'+
        '       <button id="sendData" type="button" class="btn modal-btns">Guardar cambios</button>'+
        '   </div>'+
        '</div>'+
    '</div>';
    $(".modal-verify").html($htmlModal);
    $('#modalverifyPW').modal('show');
}
function nameCuenta(e) {
    $("#signUpAccount").val()==$("#signUpUsername").val(cuenta(e.val()));

}

function cambiarNombre(e){
    id = e.data("padre");
    name= $("#garage-name").val();
    $.ajax({
        type: 'POST',
        url: '/php/Garage/nameGarage.php',
        data: "id="+id+"&nombre="+name,
        success: function(resp)
        {
            if(resp==="changed")
            {
                location.reload();
            }
        }
    });
}

function borrarNombre(e){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    id = e.data("padre");
    $.ajax({
        type: 'POST',
        url: '/php/Garage/deleteGarage.php',
        data: "id="+id,
        dataType: 'json',
        success: function(resp)
        {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(resp.Success)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: "Se ha borrardo el garage",
                    type: 'success'
                });
                setTimeout(function(){
                    window.location.href="/perfil/garage/?cuenta="+resp.usuario;
                },1600);
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: resp.Error,
                    type: 'error'
                });
            }
        },
        error: function()
        {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}

function createThis(e){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var garageName= $("#signUpGarage").val();  
    var garageUse = $('input[name=setUse]:checked').val(); 
    var garagePrivacy = $('input[name=setGraragePrivacy]:checked').val(); 
    $.ajax({
        type: 'POST',
        url: '/php/Garage/newGarage.php',
        data: "garageName="+ encodeURIComponent(garageName) + "&garageUse=" + garageUse + "&garagePrivacy=" + garagePrivacy,
        dataType : "json",
        success: function(resp) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(resp.Respuesta)
            {
                $("#garageCarModal").append("<option value='"+resp.garage+"'>"+garageName+"</option>")
                $("#garageCarModal").val(resp.garage);
                $("#garageinsertcar").val(resp.garage);
                $("#buttonAddDetailGarage").attr("onclick","newCarModal($(this))");
                $(".modal").modal("hide");
                $("#successGarage").modal("show");
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: resp.Respuesta,
                    type: 'error'
                });
            }
        },
        error: function()
        {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}

function create(e){
    $.ajax({
        type: 'POST',
        url: '/php/Garage/addGarage.php',
        success: function(resp) {
            $("#addAccount").html(resp);
            $(".modal").modal("hide");
            $("#addAccount").modal("show");
        }
    });
}

function editChangeGarage(e){
    var padre=e.data("padre"); //numero de cuenta / garage
    var name= e.data("garage"); //nombre del garage
    var data="nombre="+ encodeURIComponent(name) + "&padre=" + encodeURIComponent(padre);
    $.ajax({
        type: 'POST',
        url: '/php/Garage/editGarage.php',
        data: data,
        success: function(resp) {
            $("#editGarage").html(resp);
            $(".modal").modal("hide");
            $("#editGarage").modal("show");
        }
    });
}

function editGarage(e) {

    var valchange = e.val();
    var garage = e.data("garage");
    var padre = e.data("padre");

    if(valchange==="name")
    {
        $(".hoja").remove();
        $("#modHoja").append("" +
            "<div class='hoja form-group'>\n" +
        "       <br/><h4>Escribir nuevo nombre</h4><input type='text' id='garage-name'  name='garage-name' class='form-control' value='"+ garage +"'/>\n" +
        "    </div><div class='modal-footer hoja'><button type='submit' class='btn btn-success' data-garage='"+garage+"' data-padre='"+padre+"' onclick='cambiarNombre($(this))'> Guardar cambios </button></div>");
    }
    else if(valchange==="borra")
    {
        $(".hoja").remove();
        $("#modHoja").append("" +
            "<div class='hoja alert alert-danger' role='alert'>¡Cuidado! Se eliminar&aacute;n todos los sub-garages que contenga el garage seleccionado. \n ¿Est&aacute;s seguro que desea eliminar <strong>'"+garage+"'</strong> ?\n" +
            "       <input type='hidden' name='name' value='"+ garage +"'/>\n" +
            "    </div><div class='modal-footer hoja'><button type='submit' class='btn btn-success'   data-padre='"+padre+"' onclick='borrarNombre($(this))'> Guardar cambios </button></div>");
    }
}

function conectar() {
    
    if ($("logInPassword").val()!="" && $("#logInUsername").val()!="") 
    {
        pw = $("#logInPassword").val();
        var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
        if(!emailRegex.test($("#logInUsername").val()))
        {
            username = $("#logInUsername").val();
            data= "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(pw);
        }else{
            mail = $("#logInUsername").val();
            data= "mail=" + mail + "&password=" + encodeURIComponent(pw);
        }
        var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^])*$/g);
        if(!nameRegex.test($("#logInUsername").val()) || $("#logInUsername").val()==="")
        {
            new PNotify({
                    title: 'AVI cars:',
                    text: 'Campo no v&aacute;lido.',
                    type: 'error'
                });
            $(".form-group").removeClass('has-error');
            $("#in_username").addClass('has-error');
            $("#logInUsername").focus();
            return false;
        }
        if($("#logInPassword").val()===""){
            $(".form-group").removeClass('has-error');
            $("#in_pwd").addClass('has-error');
            $("#logInPassword").focus();
            return false;
        }
    }
    else if($("logInPasswordHeader").val()!="" && $("logInUsernameHeader").val()!="")
    {
        pw = $("#logInPasswordHeader").val();
        var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
        if(!emailRegex.test($("#logInUsernameHeader").val()))
        {
            username = $("#logInUsernameHeader").val();
            data= "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(pw);
        }else{
            mail = $("#logInUsernameHeader").val();
            data= "mail=" + mail + "&password=" + encodeURIComponent(pw);
        }
        var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^])*$/g);
        if(!nameRegex.test($("#logInUsernameHeader").val()) || $("#logInUsernameHeader").val()===""){
            new PNotify({
                        title: 'AVI cars:',
                        text: 'Campo no v&aacute;lido.',
                        type: 'error'
                    });
            $(".form-group").removeClass('has-error');
            $("#in_usernameHeader").addClass('has-error');
            $("#logInUsernameHeader").focus();
            return false;
        }
        if($("#logInPasswordHeader").val()===""){
            $(".form-group").removeClass('has-error');
            $("#in_pwdHeader").addClass('has-error');
            $("#logInPasswordHeader").focus();
            return false;
        }
    }
    else{
        new PNotify({
                title: 'AVI cars:',
                text: 'Campo(s) no v&aacute;lido(s).',
                type: 'error'
            });
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/php/login/userLogin.php',
        data: data,
        success: function(ans) {
            if(ans==="0")
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'El nombre de usuario/e-mail o contraseña es incorrecto.',
                    type: 'error'
                });
            }
            else
            {   
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Bienvenido de nuevo :)',
                    type: 'success'
                });
                if(location.pathname == "/login/")
                {
                    location.replace("/timeline");
                }
                else
                {
                    setTimeout(function(){location.reload()},2000);
                }
            }
        }
    });
}

function conectarModal() {
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    pw = $("#logInPasswordModal").val();
    if(!emailRegex.test($("#logInUsernameModal").val()))
    {
        username = $("#logInUsernameModal").val();
        data= "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(pw);
    }else{
        mail = $("#logInUsernameModal").val();
        data= "mail=" + mail + "&password=" + encodeURIComponent(pw);
    }
    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^])*$/g);
    if(!nameRegex.test($("#logInUsernameModal").val()) || $("#logInUsernameModal").val()===""){
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Campo no v&aacute;lidado.',
                    type: 'error'
                });
        $(".form-group").removeClass('has-error');
        $("#in_usernameModal").addClass('has-error');
        $("#logInUsernameModal").focus();
        return false;
    }
    if($("#logInPasswordModal").val()===""){
        $(".form-group").removeClass('has-error');
        $("#in_pwdModal").addClass('has-error');
        $("#logInPasswordModal").focus();
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/php/login/userLogin.php',
        data: data,
        success: function(ans) {
            if(ans==="0")
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'El nombre de usuario/e-mail o contraseña es incorrecto.',
                    type: 'error'
                });
            }
            else
            {   
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Bienvenido de nuevo :)',
                    type: 'success'
                });
                if(location.pathname == "/login/")
                {
                    location.replace("/timeline");
                }
                else
                {
                    setTimeout(function(){location.reload()},2000);
                }
            }
        }
    });
}

function showVersion()
{
    $.ajax({
        url : "/php/catalogoAutos/getVersiones.php/",
        async : false,
        type : "POST",
        data : "modelo="+$("#ano").val(),
        dataType : "json",
        success : function(resp){
            var k=0;
            var dataHtml="<h1>Selecciona Version</h1>";
            if(resp=="")
            {
                $("#makeCar").addClass("hidden");
            }
            else
            {
                $.each(resp,function(i,val){
                    if(k==0)
                    {
                        $("#makeCar").removeClass("hidden");
                        
                        $('html,body').animate({
                            scrollTop: $("#AgregarGarage").offset().top
                        }, 2000);
                    }
                    k++;
                    dataHtml+="<tr>"
                                    +"<td onclick='selectVersion($(this))' class='version"+i+" pointer'>"
                                        +"<input type='checkbox' value='"+i+"' class='hidden' name='version'>"
                                        +val
                                    +"</td>"
                                +"</tr>";
                    $("#AgregarGarage").find("tbody").html(dataHtml);
                })
            }
        }
    })
    
}

function selectVersion(e)
{
    $("#AgregarGarage").find("input[type=checkbox]").prop("checked",false);
    $("#AgregarGarage").find("tr").removeClass("versionSelected");
    $("#AgregarGarage").find("td").removeClass("fa fa-check");
    e.closest("tr").addClass("versionSelected");
    e.closest("td").addClass("fa fa-check");
    //$("#otrasOpciones").prop("checked",false);
    e.find("input[type=checkbox]").prop("checked",true);
    //$(".finish").find("a").removeClass("hidden");
    //$(".finish").find("a").data("version", e.find("input").val());

}

function ChangeUpAndDown(){
    if($("#chevron").hasClass("glyphicon-chevron-up")){
        $("#chevron").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
    }else{
        $("#chevron").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
    }
}
//facebook login////
function statusChangeCallback(response){
    if (response.authResponse) 
    {
        if (response.status === 'connected') {
            FB.api('/me?fields=id,first_name,last_name,email,gender', function(response) {
                $.ajax({
                    type: 'POST',
                    url: '/login/fb/fb-callback.php',
                    data: "first_name=" + encodeURIComponent(response.first_name) + "&last_name="+ encodeURIComponent(response.last_name)+ "&email="+response.email + "&id=" + response.id+ "&gender=" + response.gender ,
                    dataType: "json",
                    success: function(ans) {   
                        if(ans.Error){
                            new PNotify({
                                title: 'AVI cars:',
                                text: ans.Error,
                                type: 'error'

                            });
                        }
                        else
                        { 
                            //if(ans.nuevo)
                            //{
                              //  sendMail();
                            //}
                            new PNotify({
                                title: 'AVI cars:',
                                text: 'Bienvenido :)',
                                type: 'success'
                            });
                            if(location.pathname == "/login/")
                            {
                                location.replace("../perfil/?cuenta="+ans.Success);
                            }
                            else
                            {
                                setTimeout(function(){location.reload()},2000);
                            }
                        }
                    }
                });
            });
        }
    } 
    else 
    {
        new PNotify({
            title: 'AVI cars:',
            text: "Algo salio mal.",
            type: 'error'

        });
    }
    
}
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}
function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    if(googleUser)
    {
        $.ajax({
            url : "/php/signup/google.php",
            data : "id="+profile.getId()+"&name="+profile.getGivenName()+"&mail="+profile.getEmail()+"&lname="+profile.getFamilyName(),
            type : "POST",
            async : false,
            dataType : "json",
            success : function(msg){
                if(msg.Error)
                {
                    new PNotify({
                        title: 'AVI cars:',
                        text: msg.Error,
                        type: 'error'
                    });
                }
                else
                {
                    /*if(msg.nuevo)
                    {
                        sendMail();
                    }*/
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'Bienvenido :)',
                        type: 'success'
                    });
                    if(location.pathname == "/login/")
                    {
                        location.replace("../perfil/?cuenta="+msg.Success);
                    }
                    else
                    {
                        setTimeout(function(){location.reload()},2000);
                    }
                }
            }            
        })
    }
}
function loginTwitter(){
    window.location.href="/php/login/loginTwitter.php";
}
function updatePrivacy(e)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    idToChange = e.data("privacy");
    idToChange["privacyType"]=$("input:radio[name=updatePrivacy]:checked").val();
    $.ajax({
        url : "/php/perfil/privacy.php",
        data : idToChange,
        type : "POST",
        async : false,
        dataType : "json",
        success : function(msg)
        {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(msg.Success)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: msg.Success,
                    type: 'success'
                });
            }
            setTimeout(function(){location.reload()},2000);
        },
        error: function()
        {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    })
}

function getUpdatePrivacy(x)
{
    $("#valor").val(x);
    $("#privacidad").modal("hide");
    if($("#privacidad").find("input[type=radio][data-actual=1]").is(":checked")){
        return false;
    }
}

function openPublishSlide()
{   
    $(".flotante").css("display", "none");
    $(".publicationModal").css("display", "block");
}
function showPublishSlides(n)
{
    var i;
    var slides = document.getElementsByClassName("PublicationSlide");
    if (n >= slides.length) 
    {
        var n = 0;
    }
    if(n==(slides.length-1))
    {
        $(".nextSlide").attr("onclick", "disabled");
    }
    else
    {
         $(".nextSlide").attr("onclick", "plusPublishSlide(1)");
    }
    if (n < 0) 
    {
        var n = slides.length-1;
    }
    if (n == 0) 
    {
        $(".prevSlide").attr("onclick", "disabled");
    }
    else
    {
        $(".prevSlide").attr("onclick", "plusPublishSlide(-1)");
    }
    for (i = 0; i < slides.length; i++) 
    {
         slides[i].style.display = "none";
    }
    
    slides[n].style.display = "block";
}
function plusPublishSlide(n)
{
    showPublishSlides(slideIndex += n);
}
function currPublishSlide(n)
{
    showPublishSlides(slideIndex = n);
}
function closePublishSlide()
{
    $(".flotante").css("display", "block");
    $(".publicationModal").css("display", "none");
    $(".modal-backdrop").modal("hide");  
    $(".modPublish").attr("src", ''); 
}