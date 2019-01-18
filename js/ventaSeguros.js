function changeMarcaSegurosTimeline(e){
    var marcamodal=e.val();
    var modelo=0;
    var ano=$("#anoSegurosTimeline").val();
    var version=$("#versionSegurosTimeline").val();
    $("#anoSegurosTimeline").val(0);
    $("#versionSegurosTimeline").val(0);
    $.ajax({
        url : "/php/catalogoAutos/getModels.php/",
        async : false,
        type : "POST",
        data: "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#modeloSegurosTimeline").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/getYears.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#anoSegurosTimeline").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/knowVersion.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
        success : function(resp){
            $("#versionSegurosTimeline").html(resp);
        }
    })
    if(marcamodal!=-1)
    {
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearSegurosTimeline").addClass("hidden");
        $("#otherverSegurosTimeline").addClass("hidden");
    }
    if(e.val()=="-1")
    {
        $(".otraMarca").removeClass("hidden");
        $("#modeloSegurosTimeline").val("-1");
        $("#anoSegurosTimeline").val("0");
        $("#versionSegurosTimeline").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearSegurosTimeline").addClass("hidden");
        $("#otherverSegurosTimeline").removeClass("hidden");
    }
    if(e.val()=="0")
    {
        $("#anoSegurosTimeline").val("0");
        $("#versionSegurosTimeline").val("0");
        $("#modeloSegurosTimeline").val("0");
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearSegurosTimeline").addClass("hidden");
        $("#otherverSegurosTimeline").addClass("hidden");
    }
    $("#modeloSegurosTimeline").find("option").each(function(){

        if($(this).data("marca")==marcamodal||$(this).attr("value")==0)
        {
            $(this).show();
            $(this).remove("disabled",false);
            $(this).addClass("visible").removeClass("hidden");
        }
        else
        {
            if($(this).attr("value")!="-1")
            {
                $(this).hide();
                $(this).remove();
            }
        }
    });
}

function changeSubmarcaSegurosTimeline(e){
    var modelo=e.val();
    if(modelo==-1||modelo==0)
    {
        marcamodal=$("#marcaSegurosTimeline").val();
    }
    else
    {
        var marcamodal=e.find("option:selected").data("marca");
    }
    var ano=$("#anoSegurosTimeline").val();
    var version=$("#versionSegurosTimeline").val();
    $("#marcaSegurosTimeline").val(marcamodal);
    e.find("option").each(function(){
        if($(this).data("marca")==marcamodal||$(this).attr("value")==0)
        {
            $(this).show();
            $(this).remove("disabled",false);
            $(this).addClass("visible").removeClass("hidden");
        }
        else
        {
            if($(this).attr("value")!="-1")
            {
                $(this).hide();
                $(this).remove();
            }
        }
    })
    if(modelo!=1 && modelo!=0)
    {
        $(".otroModelo").addClass("hidden");
        $("#otheryearSegurosTimeline").addClass("hidden");
        $("#otherverSegurosTimeline").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#anoSegurosTimeline").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/knowVersion.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
            success : function(resp){
                $("#versionSegurosTimeline").html(resp);
            }
        })
    }
    if(modelo=="-1")
    {
        $("#anoSegurosTimeline").val("0");
        $("#versionSegurosTimeline").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearSegurosTimeline").addClass("hidden");
        $("#otherverSegurosTimeline").removeClass("hidden");
    }
    if(modelo=="0")
    {
        $("#anoSegurosTimeline").val("0");
        $("#versionSegurosTimeline").val("0");
        $(".otroModelo").addClass("hidden");
        $("#otheryearSegurosTimeline").addClass("hidden");
        $("#otherverSegurosTimeline").addClass("hidden");
    }
}

function changeAnoSegurosTimeline(e){
    var ano=e.val();
    var modelo=$("#modeloSegurosTimeline").val();
    var marcamodal=$("#marcaSegurosTimeline").val();
    if(ano!=0 && ano!=-1)
        $("#otherverSegurosTimeline").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getModels.php",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                if(modelo==="0")
                {
                    $("#modeloSegurosTimeline").html(resp);
                }
                else
                {
                    $("#versionSegurosTimeline").html(resp);
                }
            }
        });
    
    if(ano=="-1")
    {
        $("#versionSegurosTimeline").val("-1");
        $("#otheryearSegurosTimeline").removeClass("hidden");
        $("#otherverSegurosTimeline").removeClass("hidden");
    }
    if(ano=="0")
    {
        $("#versionSegurosTimeline").val(0);
        $("#otheryearSegurosTimeline").addClass("hidden");
        $("#otherverSegurosTimeline").addClass("hidden");
    }
}
function changeVersionSegurosTimeline(e){
    var version=e.val();
    $("#otroVersionInputSegurosTimeline").val($("#versionSegurosTimeline option:selected").text());
    if (version == -1) {
      $("#otherverSegurosTimeline").removeClass("hidden");
    }
    else{
      $("#otherverSegurosTimeline").addClass("hidden");
    }
}
$(".sig").click(function(){
    id=$("#steps-all").find("li.active").data("id");
    next=id+1;
    if($("#steps-all").find("li[data-id='"+next+"']").length>0){
        $("#steps-all").find("li").removeClass("active");
        $("#formpromomodal").find("div.step").removeClass("active");
        $("#steps-all").find("li[data-id='"+next+"']").addClass("active");
        $("#formpromomodal").find("div.step[data-id='"+next+"']").addClass("active");
    }
    next++;
    if($("#steps-all").find("li[data-id='"+next+"']").length==0){
        $(".sig").addClass("hidden");
        $(".last").removeClass("hidden");
    }
})

$(".prev").click(function(){
    $("#modalSellSeguros .gracias").addClass("hidden");
    $("#modalSellSeguros .cotizarModal").removeClass("hidden");
    id=$("#steps-all").find("li.active").data("id");
    last=id-1;
    if($("#steps-all").find("li[data-id='"+last+"']").length>0){
        $("#steps-all").find("li").removeClass("active");
        $("#formpromomodal").find("div.step").removeClass("active");
        $("#steps-all").find("li[data-id='"+last+"']").addClass("active");
        $("#formpromomodal").find("div.step[data-id='"+last+"']").addClass("active");
    }
    $(".sig").removeClass("hidden");
    $(".last").addClass("hidden");
});
$(".last").click(function(){
    $(".form-group").removeClass("has-error");
    var nombre=$("#formpromomodal input[name=nombre]").val();
    var apellido=$("#formpromomodal input[name=apellido]").val();
    var mail=$("#formpromomodal input[name=mail]").val();
    var edad=$("#formpromomodal input[name=edad]").val();
    var cp=$("#formpromomodal input[name=cp]").val();
    var telefono=$("#formpromomodal input[name=telefono]").val();
    var cuenta=($("#formpromomodal input[name=login]").is(":checked")) ? true : false;
    var numberRegex = new RegExp(/^[0-9]*$/g);
    var regexpPost = new RegExp(/^([^'´´\\"])*$/g);
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    var send=true;
    if(!regexpPost.test(nombre)||nombre.length==0){
        $("#formpromomodal input[name=nombre]").closest(".form-group").addClass("has-error");
        send=false;
    }
    var regexpPost = new RegExp(/^([^'´´\\"])*$/g);
    if(!regexpPost.test(apellido)||apellido.length==0){
        $("#formpromomodal input[name=apellido]").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(!emailRegex.test(mail)||mail.length==0){
        $("#formpromomodal input[name=mail]").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(!numberRegex.test(edad)||edad<18||edad>99||edad.length==0){
        $("#formpromomodal input[name=edad]").closest(".form-group").addClass("has-error");
        send=false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test(cp)||cp.length!=5){
        $("#formpromomodal input[name=cp]").closest(".form-group").addClass("has-error");
        send=false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test(telefono)||telefono.length<8||telefono.length>10){
        $("#formpromomodal input[name=telefono]").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(send){
        if(cuenta){
            var next=verifyMail()
            if(next==0&&next=="0"){
                $("#setPasswordSeguros").modal("show");
            }
            else{
                $("#formpromomodal input[name=login]").prop("checked",false);
                sendModalSegurosPromo();
            }
        }
        else{
            sendModalSegurosPromo();
        }
    }
})
function verifyMail(){
    xhr = new XMLHttpRequest();
    var mail=false;
    var url = "/php/perfil/checkMailLives.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            msg=this.response;
            mail=msg
        }
        else{
            mail=false;
        }
    }
    xhr.send("m="+$("#formpromomodal input[name=mail]").val());
    return mail;
}
function validatePasswords(){
    var pwd=$("#firstPassword").val();
    var pwd2=$("#secondPassword").val();
    if(pwd.length>=8 && pwd.length<=60 && pwd==pwd2){
        sendModalSegurosPromo();
    }
}
function sendModalSegurosPromo(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    xhr = new XMLHttpRequest();
    var url = "/php/perfil/sendModalSeguros.php";
    xhr.open("POST", url, true);
    xhr.responseType="json";
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
        $("#reload-band").addClass('hidden');
        $("#flag-reload").addClass('hidden');
        if(this.status==200)
        {
            msg=this.response;
            if(msg.Success){
                $("#modalSellSeguros .gracias").removeClass("hidden");
                $("#modalSellSeguros .cotizarModal").addClass("hidden");
                $("#setPasswordSeguros").modal("hide")
                if(msg.linkto){
                    $("#buttonFinishModalSeguros").attr("onclick","window.location.href='"+msg.linkto+"'");
                    $("#buttonFinishModalSeguros").text("Ir a mi perfil");
                }
                else{
                    
                    $("#buttonFinishModalSeguros").attr("onclick",'$(".prev").trigger("click")');
                    
                }
            }
            else{
                new PNotify({
                    title: 'AVI cars:',
                    text: 'No se pudo enviar la petici&oacute;n.',
                    type: 'error'
                });
            }
        }
        else{
            new PNotify({
                title: 'AVI cars:',
                text: 'No se pudo enviar la petici&oacute;n.',
                type: 'error'
            });
        }
    }
    xhr.send($("#formpromomodal").serialize()+"&"+$("#formSendPromoSegurosPwd").serialize());
}
$('#modalSuccessModalSeguros').on('hidden.bs.modal', function () {
    window.location.reload();
})
function statusChangeCallbackModal(f){
    checkAccount(false,f);
    
}
function checkLoginStateModal(){
  FB.getLoginStatus(function(response) {
    statusChangeCallbackModal(response);
  });
}
function onSignInModal(g) {
    checkAccount(g);
    
}
function signInTwitter(){
    xhr = new XMLHttpRequest();
    var url = "/php/perfil/loginTiwtterModal.php";
    xhr.open("POST", url, true);
    xhr.responseType="json";
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            window.location.href="/php/login/loginTwitter.php"
        }
        
    }
    xhr.send($("#formpromomodal").serialize());
}
function getGarages(){
    xhr = new XMLHttpRequest();
    var url = "/php/perfil/getSelectGarages.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            $(".step[data-name='contacto']").append(this.response);
        }
        
    }
    xhr.send();
}
function checkAccount(g=false, f=false, t=false){
    if(g){
        var profile = g.getBasicProfile();
        var mail=profile.getEmail();
        var name=profile.getGivenName();
        var lastname=profile.getFamilyName();
        var s="G+";
    }
    if(f){
        if (f.authResponse) 
        {
            if (f.status === 'connected') {
                var mail=f.mail;
                var name=f.first_name;
                var lastname=f.last_name;
                var s="FB";
            }
        }
    }
    if(t){
        var mail=t.m;
        var name=t.n;
        var lastname=t.l;
        var s="TW";
    }
    xhr = new XMLHttpRequest();
    var url = "/php/perfil/checkMail.php";
    xhr.open("POST", url, true);
    xhr.responseType="json";
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            msg=this.response;
            if(!msg.Error){
                $("#formpromomodal input[name=nombre]").val(msg.nombre);
                $("#formpromomodal input[name=apellido]").val(msg.apellido);
                $("#formpromomodal input[name=mail]").val(msg.mail);
                $("#formpromomodal input[name=edad]").val(msg.edad);
                $("#formpromomodal input[name=cp]").val(msg.cp);
                $("#formpromomodal input[name=telefono]").val(msg.telefono);
                $(".continue-with").remove();
                $(".icons-login-continue").remove();
                $(".cretaeaccountmodal").remove();
                getGarages();
            }
            else{
                $("#formpromomodal input[name=nombre]").val(name);
                $("#formpromomodal input[name=apellido]").val(lastname);
                $("#formpromomodal input[name=mail]").val(mail);
            }
        }
        else{
            $("#formpromomodal input[name=nombre]").val();
            $("#formpromomodal input[name=apellido]").val();
            $("#formpromomodal input[name=mail]").val();
        }
        
    }
    xhr.send("m="+encodeURIComponent(mail)+"&c="+encodeURIComponent(s));
}
$(document).ready(function(){
    if(carModalSeguros!==undefined){
        $("#marcaSegurosTimeline").val(carModalSeguros.marca);
        changeMarcaSegurosTimeline($("#marcaSegurosTimeline"));
        $("#modeloSegurosTimeline").val(carModalSeguros.submarca);
        changeSubmarcaSegurosTimeline($("#modeloSegurosTimeline"));
        $("#anoSegurosTimeline").val(carModalSeguros.modelo);
        changeAnoSegurosTimeline($("#anoSegurosTimeline"));
        $("#versionSegurosTimeline").val(carModalSeguros.subnombres);
        changeVersionSegurosTimeline($("#versionSegurosTimeline"));
        $("#otraMarcaInputSegurosTimeline").val(carModalSeguros.otraMarcaInput);
        $("#otroModeloInputSegurosTimeline").val(carModalSeguros.otroModeloInput);
        $("#otroAnoInputSegurosTimeline").val(carModalSeguros.otroAnoInput);
        $("#otroVersionInputSegurosTimeline").val(carModalSeguros.otroVersionInput);
        var t={"m":mailModalSeguros,"n":nameModalSeguros,"l":"","s":"TW"};
        checkAccount(false,false,t);
    }
})
$("#autoSegurosModal").change(function(){
    if($(this).val()<0){
        $(".carChar").removeClass("hidden");
        $("#saveCarInGarageModalSeguros").removeClass("hidden");
    }
    else{
        $(".carChar").addClass("hidden");
        $("#saveCarInGarageModalSeguros").addClass("hidden");
    }
})
function iniciarpressModalSeguros(e){
    var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
    if (key === 13) {
        $('#iniciarModalSeguros').trigger('click');
    }
}
function setPasswordSeguros(e){
    var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
    if (key === 13) {
        $('#sendModalPromoSeguros').trigger('click');
    }
}
function conectarModalSeguros() {
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
        url: '/php/perfil/loginUserModal.php',
        data: data,
        dataType : "json",
        success: function(ans) {
            if(ans.Error)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'El nombre de usuario/e-mail o contraseña es incorrecto.',
                    type: 'error'
                });
            }
            else
            {   
                $("#formpromomodal input[name=nombre]").val(ans.nombre);
                $("#formpromomodal input[name=apellido]").val(ans.apellido);
                $("#formpromomodal input[name=mail]").val(ans.mail);
                $("#formpromomodal input[name=edad]").val(ans.edad);
                $("#formpromomodal input[name=cp]").val(ans.cp);
                $("#formpromomodal input[name=telefono]").val(ans.telefono);
                $("#inicieSesionSeguros").modal("hide");
                $(".continue-with").remove();
                $(".icons-login-continue").remove();
                $(".cretaeaccountmodal").remove();
                getGarages();
            }
        }
    });
}