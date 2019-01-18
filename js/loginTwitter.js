function loginTwitter(){
	xhr = new XMLHttpRequest();
    var url = "/php/signup/twitter.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.responseType = "json";
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            if(msg.Success){
            	new PNotify({
                    title: 'AVI cars:',
                    text: 'Bienvenido :)',
                    type: 'success'
                });
                if(msg.nuevo){
                	new PNotify({
	                    title: 'AVI cars:',
	                    text: 'Enviamos un correo de confirmación revisa tu bandeja de entrada.',
	                    type: 'info'
	                });
                }
                if(msg.redirect==""){
               		setTimeout(function(){location.reload()},2000);
               	}
               	else{
               		setTimeout(function(){location.href=msg.redirect},2000);
               	}
            }
            else{
            	new PNotify({
                    title: 'AVI cars:',
                    text: msg.Error,
                    type: 'error'
                });
            }
            
        }
        else{
        	new PNotify({
                title: 'AVI cars:',
                text: "No pudimos iniciar sesi&oacute;n con tu cuenta de Twitter.",
                type: 'error'
            });
        }
    	
    }
    xhr.send();
}
$(document).ready(function(){
	loginTwitter();
})