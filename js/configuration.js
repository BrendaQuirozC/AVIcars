/*
* @Author: Erik Viveros
* @Date:   2018-08-27 09:21:25
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-09-12 11:26:37
*/
$("#selectAll").click(function(){
	if($(this).is(":checked")){
		$("input[name='mails[]']").prop("checked",true);
	}
	else{
		$("input[name='mails[]']").prop("checked",false);
	}
});
$("#saveMails").click(function(){
	var xhr=new XMLHttpRequest();
	var response={};
    var url = "/php/perfil/configuracion/mails.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.responseType = "json";
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            response=this.response;
        }
        else
        {
            response={Error : "Ocurrio un error, por favor contacte al administrador."};
        }
       	if(response.Success){
            new PNotify({
       		    title: 'AVI cars:',
                text: 'Configuraci&oacute;n Guardada.',
                type: 'success'
            });
       	}
       	else
       	{
		  	new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un error, intente m&aacute;s tarde.',
                type: 'error'
            });
       	}
    }
    data=$("#formMails").serialize();
    xhr.send(data);
});
$("#toBlock").click(function(){
    if($("select[name='blocked[]'] option:selected").length>0){
        $("#modalBlocked").modal("show");    
    }
    
})
$("#quitBlocked").click(function(){
    var xhr=new XMLHttpRequest();
    var response={};
    var url = "/php/perfil/configuracion/unblock.php";
    xhr.open("POST",url,true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.responseType = "json";
    xhr.onreadystatechange = function (){
        if(this.status==200){
          response=this.response;
        }
        else{
          response={Error : true};
        }
        if(response.Success){
            new PNotify({
                title: 'AVI cars:',
                text: 'Configuraci&oacute;n Guardada.',
                type: 'success'
            });
            $("select[name='blocked[]'] option:selected").each(function(){
                $(this).remove();
            })
            $("#modalBlocked").modal("hide"); 
        }
        else
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un error, intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    }
    data=$("#blockedForm").serialize();
    xhr.send(data);
})