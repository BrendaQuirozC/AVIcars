/*
* @Author: erikfer94
* @Date:   2018-10-09 11:11:31
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-10-23 11:26:11
*/
var l=0;
var search=true;
function getGarages(){
	$(".seemore").remove();
	xhr = new XMLHttpRequest();
    var url = "/php/perfil/getGaragesColaborador.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            $("#garagesContent").append(msg);
            l+=10;
        }
        else{
        	search=false;
        }
    	
    }
    xhr.send("c="+encodeURIComponent(c)+"&t="+l);
    
}
function toStopColaborating(e){
    var colaborador=e.data("u");
    var garage=e.data("g");
    $("#deleteCOlaboradorButton").data("g",garage);
    $("#deleteCOlaboradorButton").data("u",colaborador);
}
$(document).ready(function(){
	getGarages();
    $("#deleteCOlaboradorButton").click(function(){
        $("#reload-band").removeClass('hidden');
        $("#flag-reload").removeClass('hidden');
        var g=$(this).data("g");
        var u=$(this).data("u");
        $.ajax({
            url : "/php/Garage/deleteColaborator.php",
            data : "g="+encodeURIComponent(g)+"&u="+encodeURIComponent(u),
            async : true,
            type : "POST",
            dataType : "json",
            success: function(msg){
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                if(msg.Success){
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'Se ha borrado con &eacute;xito al colaborador.',
                        type: 'success'
                    });

                    window.location.reload();
                }
                else{
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'Lo sentimos, algo salio mal.',
                        type: 'error'
                    });
                    $(this).attr("disabled",false);
                }
            },
            error: function(){
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Lo sentimos, algo salio mal.',
                    type: 'error'
                });
                $(this).attr("disabled",false);
            }
        })
    });
});