function GoToDeleteAd(e){
	var ad=e.data("ad");
	$("#borrarAd").data("a",ad);
}
function loadFile(){
	$(".modal").modal("hide");
	$("#modalAddFile").modal("show");
}
function toSold(e){
	$(".modal").modal("hide");
	var ad=e.data("ad");
	var car=e.data("car");
	$("#soldCar").data("a",ad);
	$("#soldCar").attr("onclick","sold('"+car+"')");
	$("#sellCarModal").modal("show");
}
function subir(){
	$("#reload-band").removeClass('hidden');
	$("#flag-reload").removeClass('hidden');
	var upload=true;
	if( document.getElementById("filesToUploadCar").files.length == 0 ){
	    upload=false;
	}
	if($("#fileToUploadName").val()==0)
	{
		upload=false;
	}
	var files=true;
	var fileInput=$("#filesToUploadCar");
    var maxSize=20000000;
    for(var k=0; k < fileInput.get(0).files.length ; k++){
    	var fileSize = fileInput.get(0).files[k].size;
    	if(fileSize>maxSize){
    		upload=false;
    		files=false;
    	}
    }
	if(upload){
		var dataForm = new FormData($("#formFiles")[0]);
	    $.ajax({
	        type: 'POST',
	        url: '/php/Archivo/upload.php',
	        data: dataForm,
	        async:false,
	        cache: false,
	        contentType: false,
	        processData: false,
	        dataType : "json",
	        success: function(ans) {
	        	$("#reload-band").addClass('hidden');
            	$("#flag-reload").addClass('hidden');
	            if(!ans.Success)
	            {
	                new PNotify({
	                    title: 'AVI cars:',
	                    text: 'El tipo de archivo que intenta subir no est&aacute; permitido.',
	                    type: 'error'
	                });
	            }
	            else
	            {
	            	$("#modalAddFile").modal("hide");
	                new PNotify({
	                    title: 'AVI cars:',
	                    text: '¡Se ha cargado el documento correctamente!',
	                    type: 'success'
	                });
	                setTimeout(function(){location.reload()},1600);
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
	else{
		if(files){
			new PNotify({
	            title: 'AVI cars:',
	            text: "Completa los datos",
	            type: 'error'
	    	});
	    	$("#reload-band").addClass('hidden');
	        $("#flag-reload").addClass('hidden');
		}
		else{
			new PNotify({
	            title: 'AVI cars:',
	            text: 'El archivo es demasiado grande, solo puedes subir im&aacute;genes de 20 MB o menos.',
	            type: 'error'
	        });
	        $("#reload-band").addClass('hidden');
	        $("#flag-reload").addClass('hidden');
		}
	}
}
function download(e){
	$("#file-u").val(e.data("u"));
	$("#file-f").val(e.data("f"));
	$("#file-t").val(e.data("t"));
	$.ajax({
		url: "/perfil/docs/download/checkAccess.php",
		type: "POST",
		async: false,
		data : "pwd="+$("#pwd").val(),
		dataType : "json",
		success : function(msg){
			if(msg.Error){
				new PNotify({
	                title: 'AVI cars:',
	                text: msg.Error,
	                type: 'error'
	            });
			}
			else{
				if($("#descargar").submit())
				{
					$("#pwd").val("");
					$("#descargarfile").modal("hide");
				}

			}
		},
		error: function(){
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
function openFile(u,f,t){
	$("#descargaBoton").data("u",u);
	$("#descargaBoton").data("t",t);
	$("#descargaBoton").data("f",f);
}
function modalDeleteFile(u,f,t){
	$("#borrarBoton").data("u",u);
	$("#borrarBoton").data("t",t);
	$("#borrarBoton").data("f",f);
	$("#modalDeleteFile").modal("show");
}
function deleteFile(e){
	$("#reload-band").removeClass('hidden');
	$("#flag-reload").removeClass('hidden');
	u=e.data("u");
	t=e.data("t");
	f=e.data("f");
	$.ajax({
		url : "/perfil/docs/delete.php",
		data : "u="+u+"&f="+f+"&t="+t+"&pwd="+$("#pwdDel").val(),
		type : "POST",
		async : false,
		dataType : "json",
		success : function(msg){
			$("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
			if(msg.Success){
				new PNotify({
                    title: 'AVI cars:',
                    text: '¡Se ha borrado el documento correctamente!',
                    type: 'success'
                });
                $("#pwdDel").val("");
                setTimeout(function(){location.reload()},1600);
			}
			else{
				new PNotify({
                    title: 'AVI cars:',
                    text: 'Contrase&ntilde;a incorrecta',
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
	})
}
function sold(idauto)
{
  $.ajax({
    type: "POST",
    url: "/php/auto/carSold.php",
    data: "a="+ encodeURIComponent(idauto),
    dataType: "json",
    success: function(ans){
      $("#reload-band").addClass('hidden');
      $("#flag-reload").addClass('hidden');
      if(ans.Error)
      {
        new PNotify({
            title: 'AVI cars:',
            text: ans.Error,
            type: 'error'
        });
      }
      else
      {
        new PNotify({
            title: 'AVI cars:',
            text: ans.Success,
            type: 'success'
        });
        setTimeout(function(){location.replace("/perfil/autos/detalles/"+ans.url)},500);
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
$(document).ready(function(){
	$("#borrarAd").click(function(){
		$("#reload-band").removeClass('hidden');
		$("#flag-reload").removeClass('hidden');

		$.ajax({
			type: "POST",
			url: "/php/auto/deleteAd.php",
			data: "a="+ $(this).data("a"),
			dataType: "json",
			success: function(ans){
				$("#reload-band").addClass('hidden');
				$("#flag-reload").addClass('hidden');
				if(ans.Error)
				{
					new PNotify({
					    title: 'AVI cars:',
					    text: ans.Error,
					    type: 'error'
					});
				}
				else
				{
					new PNotify({
					    title: 'AVI cars:',
					    text: ans.Success,
					    type: 'success'
					});
					setTimeout(function(){window.location.reload()},500);
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
	});
	var containerPWD=document.getElementById("captchaPWD");
    var widgetId2;
    var getPwd=false;
    setTimeout(function(){
        widgetId2 = grecaptcha.render(
            containerPWD,
            {
                "sitekey" : "6LezcEUUAAAAAPmmOzTQckUo9MQDMqVjpRXxvY6D",
                "theme" : "dark",
                "size" : "normal",
                "callback" : function(){
                    getPwd=true;
                },
                "expired-callback" : function(){
                    getPwd=false;
                }
            }
        );
    },3000)
})
$("#fileToUploadName").change(function(){
	$("#extradata").html("");
	var extras=$(this).find("option:selected").data("extras");
	extrasObj=[];
	if(extras!==undefined){
		extras=atob(extras);
		extrasObj=JSON.parse(extras);
	}
	extrasObj.forEach(function(val){
		$("#extradata").append("<div class='col-xs-6 form-group'><label class='control-label'>"+val+"</label><input type='text' name='extras["+val+"]' class='form-control form-style'/></div>")
	})
});
function verExtras(e){
	$("#adicional").html("");
	var extras=e.data("extras");
	if(extras==""){
		$("#adicional").html("<p>No hay informaci&oacute;n</p>");
		return false;	
	}
	extras=atob(extras);
	extrasObj=JSON.parse(extras);
	var k=0;
	for(let i in extrasObj){
		k++;
		$("#adicional").append("<p>"+i+": "+extrasObj[i]+"</p>");
	}
	if(k==0){
		$("#adicional").html("<p>No hay informaci&oacute;n</p>");
	}
}