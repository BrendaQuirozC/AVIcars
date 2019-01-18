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
function sendSolicitud(e)
{
	window.open("/Promociones?v="+e.data("version")+"&c="+e.data("sent"),"_blank");
}
function makeAdvertisement(e){
	$(".modal").modal("hide");
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
    	$(".modal").modal("hide");
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
            $(file.previewElement).find(".dz-error-message").children("span").text("archivo no válido");
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