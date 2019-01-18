/*
* @Author: Erik Viveros
* @Date:   2018-08-14 12:37:06
* @Last Modified by:   BrendaQuiroz
* @Last Modified time: 2018-12-10 09:39:36
*/
var imgsAnunciar={};
var cont=0;
function adverticing(){
	xhr = new XMLHttpRequest();
    var response={};
    var url = "/perfil/autos/anunciar.php";
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
       		window.location.href="/";
       	}
       	$("#doPublication").modal("hide");
    }
    var data = "version="+$("#versionInput").val()+"&price="+$("#precio").val()+"&publicacion="+$("#publicationAnunciar").val()+"&img="+JSON.stringify(imgsAnunciar);
    xhr.send(data);
}
function toSold(e){
  $(".modal").modal("hide");
  var ad=e.data("ad");
  var car=e.data("car");
  $("#soldCar").data("a",ad);
  $("#soldCar").attr("onclick","sold('"+car+"')");
  $("#sellCarModal").modal("show");
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
function sendSolicitud(e)
{
	window.open("/Promociones?v="+e.data("version")+"&c="+e.data("sent"),"_blank");
}
function makeAdvertisement(e){
	$("#mensaje").modal("show");
	$("#versionInput").val(e.data("version"));
}
function removeAllImagesAnunciar()
{
	$("#imgDropAnunciar").hide();
}
function addImageAnunciar()
{
	$("#imgDropAnunciar").show();
}
function sideScroll(element,direction,speed,distance,step)
{
    scrollAmount = 0;
    var slideTimer = setInterval(function(){
        if(direction == 'left'){
            element.scrollLeft -= step;
        } else {
            element.scrollLeft += step;
        }
        scrollAmount += step;
        if(scrollAmount >= distance){
            window.clearInterval(slideTimer);
        }
    }, speed);
}
function rightSlide() 
{
    var container = document.getElementById('slider-thumbs');
    sideScroll(container,'right',25,100,10);
};
function leftSlide() 
{
    var container = document.getElementById('slider-thumbs');
    sideScroll(container,'left',25,100,10);
};
$(document).ready(function()
{
	Dropzone.autoDiscover = false;
    $("#new").click(function(){
    	$("#imgDropAnunciar").hide();
		$("#doPublication").modal("show");
	});
	$("#imgPublicAnunciar").dropzone({
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        dictRemoveFile: "Eliminar",
        url: "/php/perfil/publicacion/uploadImage.php",
        success: function (file, resp) {
        	response=JSON.parse(resp);
            if(response.Success)
            {
                file.previewElement.classList.add("dz-success");
                imgsAnunciar[cont]=response.Success;
                cont++;
                //console.log("Successfully uploaded :" + response);
            }
            else
            {
                file.previewElement.classList.add("dz-error");
                $(file.previewElement).find(".dz-error-message").children("span").text("error en imagen");
            }
            
        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
            $(file.previewElement).find(".dz-error-message").children("span").text("documento no válido");
        }
    });
	$(".activar").click(function()
	{
		var imgRuta = $(this).find("img").attr("src");
		var imgHold = $(this).data("garage");
		var keygarage = $(this).data("keygarage");
		$(".title-img").each(function(){
			if($(this).data("garage")==imgHold)
			{
				$(this).siblings(".img-car").removeClass("active");
				$(this).find("img").attr("src", imgRuta);
				$(this).children("a").data("garage", keygarage);
			}
		});
		$(this).parents(".img-car").addClass("active");
	});

	$(".chevron-action").click(function(){
		if($(this).children("i").hasClass("glyphicon-chevron-up"))
		{
	        $(this).children("i").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
	    }
	    else
	    {
	    	$(".chevron-action").each(function(index){
		    	if($(this).find("i").hasClass("glyphicon-chevron-up"))
		    	{
		    		$(this).click();
		    	}
	    	});
	        $(this).children("i").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
	    }
	});
    $('[id^=carousel-selector-]').click( function(){
        var id = this.id.substr(this.id.lastIndexOf("-") + 1);
        var id = parseInt(id);
        $('#promosCarrusel').carousel(id);
    });
})