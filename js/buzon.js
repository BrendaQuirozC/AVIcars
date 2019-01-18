/*
* @Author: Erik Viveros
* @Date:   2018-08-14 11:49:20
* @Last Modified by:   BrendaQuiroz
* @Last Modified time: 2018-12-13 10:20:51
*/
var mainContainer=document.getElementById("registerCaptcha");
var widgetId1;

var onloadCallback = function() {

	widgetId1 = grecaptcha.render(
		mainContainer,
		{
			"sitekey" : "6LezcEUUAAAAAPmmOzTQckUo9MQDMqVjpRXxvY6D",
			"theme" : "dark",
			"size" : "normal"
		}
	) 

}
function sendComment(){
	$(".form-group").removeClass('has-error');
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    //var nameRegex = new RegExp(/^(\s*|[a-zA-Z0-9 ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ])/g);
    //var lastnameRegex = new RegExp(/^(\s*|[a-zA-Z0-9 ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ])/g);
    var commentRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    var response=grecaptcha.getResponse(widgetId1);
    if(!emailRegex.test($("#mailBuzon").val())||$("#mailBuzon").val()==="")
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'Correo elctrónico no válido.',
                type: 'error'
            });
        $("#mailBuzon").closest(".form-group").addClass('has-error');
        $("#mailBuzon").focus();
        return false;
    }
    if(!commentRegex.test($("#commentBuzon").val())||$("#commentBuzon").val()===""){
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, comentario no v&aacute;lido.',
            type: 'error'
        });
        $("#commentBuzon").closest(".form-group").addClass('has-error');
        $("#commentBuzon").focus();
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
    data = $("#buzonForm").serialize();
    $.ajax({
        type: 'POST',
        url: 'http://crm.infosite.com.mx//index.php?entryPoint=WebToLeadCapture',
        data: data,
        success: function(resp) {
            grecaptcha.reset(widgetId1);
            new PNotify({
                title: 'AVI cars:',
                text: '¡Gracias por tu ayuda! Nuestro equipo estudiar&aacute; tus sugerencias.',
                type: 'success'
            });
            setTimeout(function(){location.replace("/buzon")},2000);
        },
        error: function(){
            grecaptcha.reset(widgetId1);
        	new PNotify({
                title: 'AVI cars:',
                text: '¡Gracias por tu ayuda! Nuestro equipo estudiar&aacute; tus sugerencias.',
                type: 'success'
            });
            setTimeout(function(){location.replace("/buzon")},2000);
        }
    });
}
function sendAdSolicitude(){
    $(".form-group").removeClass('has-error');
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    var nameRegex = new RegExp(/^(\s*|[a-zA-Z0-9 ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ])/g);
    var lastnameRegex = new RegExp(/^(\s*|[a-zA-Z0-9 ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ])/g);
    var enterpriseRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    var urlRegex=new RegExp(/(https?|ftp):\/\/(-\.)?([^\s/?\.#-]+\.?)+(\/[^\s]*)?$/g);
    var commentRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    //return false;
    var response=grecaptcha.getResponse(widgetId1);
    if(!emailRegex.test($("#mail").val())||$("#mail").val()==="")
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'Correo electrónico no válido.',
                type: 'error'
            });
        $("#mail").closest(".form-group").addClass('has-error');
        $("#mail").focus();
        return false;
    }
    if($("#telefono").val()===""||isNaN($("#telefono").val())||$("#telefono").val().length!=10)
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'Tel&eacute;fono no válido.',
                type: 'error'
            });
        $("#telefono").closest(".form-group").addClass('has-error');
        $("#telefono").focus();
        return false;
    }
    if(!enterpriseRegex.test($("#department").val())||$("#department").val()==="")
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'El nombre de la empresa contiene caracteres no válidos.',
                type: 'error'
            });
        $("#department").closest(".form-group").addClass('has-error');
        $("#department").focus();
        return false;
    }
    if(!urlRegex.test($("#website").val())&&$("#website").val()!="")
    {
        $next=false;
        new PNotify({
                title: 'AVI cars:',
                text: 'Url no valida.',
                type: 'error'
            });
        $("#website").closest(".form-group").addClass('has-error');
        $("#website").focus();
        return false;
    }
    if(!nameRegex.test($("#nombre").val()))
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, nombre no v&aacute;lido.',
            type: 'error'
        });
        
        $("#nombre").closest(".form-group").addClass('has-error');
        $("#nombre").focus();
        return false;
    }
    if(!lastnameRegex.test($("#apellido").val())){
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, apeliido(s) no v&aacute;lido(s).',
            type: 'error'
        });
        $("#apellido").closest(".form-group").addClass('has-error');
        $("#apellido").focus();
        return false;
    }
    if(!commentRegex.test($("#comentario").val())||$("#comentario").val()===""){
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, comentario no v&aacute;lido.',
            type: 'error'
        });
        $("#comentario").closest(".form-group").addClass('has-error');
        $("#comentario").focus();
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
    data = $("#buzonForm").serialize();
    $.ajax({
        type: 'POST',
        url: 'solicitud.php',
        data: data,
        success: function(resp) {
            grecaptcha.reset(widgetId1);
            new PNotify({
                title: 'AVI cars:',
                text: '¡Muchas gracias por tu inter&eacute;s! Un especialista en breve se pondr&aacute; en contacto contigo.',
                type: 'success'
            });
            setTimeout(function(){location.replace("/anunciate")},2000);
        },
        error: function(){
            grecaptcha.reset(widgetId1);
            new PNotify({
                title: 'AVI cars:',
                text: '¡Muchas gracias por tu inter&eacute;s! Un especialista en breve se pondr&aacute; en contacto contigo.',
                type: 'success'
            });
        }
    });
}