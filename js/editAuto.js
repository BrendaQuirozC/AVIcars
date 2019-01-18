$(document).ready(function(){
	$("#carrusel").carrusel();
	$(".carrusel-marca-img").click(function(){
        var marca=$(this).data("marca");
        $(".img-carrusel").removeClass("selected");
        $(this).addClass("selected");
        $("#marca").closest(".form-group").addClass("has-warning");
        $("#marca").val(marca).trigger("change");
        setTimeout(function(){
            $("#marca").closest(".form-group").removeClass("has-warning");
            $("#modelo").focus();
        },500);
    })
	$("#engineCar").change(function(){
   		$("#typeEngine").html($(this).find("option:selected").text());
   	});
   	$("#combustible").change(function(){
   		$("#typeFuel").html($(this).find("option:selected").text());
   	});
   	$(".clases-auto").click(function(){
   		var clase = $(this).data("clase");
   		var imagen = $(this).find("img").attr("src");
   		var nombre = $(this).text();
   		$("#classcar").val(nombre);
   		$("#get-classcar").html("<input type='hidden' name='clasecar' value="+clase+"><img onclick='tipoClase()' class='clases-img-colors' src="+imagen+" >");
   		$.ajax({
   			type: "POST",
   			url: "/php/catalogoAutos/doors.php",
   			data: "clase="+ clase,
   			success: function(ans){
   				if(ans)
   				{
   					$("#puertas").html(ans);
   					if($("#puertas").val()!=0){
   						$("#showPuertas").html($("#puertas").val());
   					}
   					else
   					{
   						$("#showPuertas").html("");
   					}
   				}
   			},
   		});
   	});
   	$(".colores").click(function(){
   		var clase = $(this).data("clase");
   		var imagen = $(this).find("img").attr("src");
   		var nombre = $(this).text();
   		$("#color").val(nombre);
   		$("#get-colorcar").html("<input value="+clase+" type='hidden' name='color'><img onclick='colorCatalogo()' class='clases-img-colors' src="+imagen+" >");
   	});
   	$("#transmision").change(function()
   	{
   		$("#showtypeTransmision").html($(this).find("option:selected").text());
   	});
   	$("#puertas").change(function()
   	{
   		$("#showPuertas").html($(this).val());
   	});
    $("#ventanas").change(function()
    {
      $("#showtypeWindows").html($(this).val());
    });
    $("#interior").change(function()
    {
      $("#showTypeInteriors").html($(this).val());
    });
    $('[data-toggle="tooltip"]').tooltip();  
    $(".borrar").click(function(){
      $("#modalBorrarAuto").modal('show');
      $("#eliminar").data("eliminar", $(this).data("car"));
      $("#eliminar").data("garage", $(this).data("garage"));
    });
    $("#eliminar").click(function(){
      $("#reload-band").removeClass('hidden');
      $("#flag-reload").removeClass('hidden');
      $todelete = $(this).data("eliminar");
      $garage = $(this).data("garage");
      $.ajax({
          type: 'POST',
          url: '/php/catalogoAutos/deleteAuto.php',
          data: "auto=" + encodeURIComponent($todelete)+ "&garage=" + encodeURIComponent($garage),
          dataType : "json",
          success: function(resp) {
            $("#reload-band").addClass('hidden');
              $("#flag-reload").addClass('hidden');
               if(resp.Error){
                      new PNotify({
                          title: 'AVI cars:',
                          text: resp.Error,
                          type: 'error'

                      });
                  }
                  if(resp.Success){
                      new PNotify({
                          title: 'AVI cars:',
                          text: "El auto se ha eliminado",
                          type: 'success'
                      });
                      setTimeout(function(){window.location.href="/perfil/garage/garage-autos/?cuenta="+resp.Success.c+"&garage="+resp.Success.g;},1000);
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

    $("#del-ad").click(function(){
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
            setTimeout(function(){window.location.href="/perfil/autos/detalles/"+location.search},500);
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
    var brandSelected = $("#marca option:selected").text();
    if ($("#marca option:selected").val() == 0) {
      $("#brandImg").attr("src","");
    }
    else if($("#marca option:selected").val() == -1){
      $("#brandImg").attr("src","");
      $(".otraMarca").removeClass("hidden");
    }
    else{
      $("#brandImg").attr("src","/img/logos/"+brandSelected+".png");
    }

    if ($(".submarca option:selected").val() == -1) {
      $(".otroModelo").removeClass("hidden");
    }
    else{
      $(".otroModelo").addClass("hidden");
    }

    if ($("#ano option:selected").val() == -1) {
      $(".otroAno").removeClass("hidden");
    }
    else{
      $(".otroAno").addClass("hidden");
    }
    if ($("#version option:selected").val() == -1) {
      $("#otherver").removeClass("hidden");
    }
    else{
      $("#otherver").addClass("hidden");
    }

    $("#changeGarage").click(function(){
        var garage=$("#changeGarageSelect").val();
        var car=$(this).data("car");
        $("#reload-band").removeClass('hidden');
        $("#flag-reload").removeClass('hidden');
        $.ajax({
            url : "/php/auto/changeGarage.php",
            data : "g="+encodeURIComponent(garage)+"&c="+encodeURIComponent(car),
            async : true,
            dataType : "json",
            type : "POST",
            success : function(msg){
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                if(msg.Error){
                    new PNotify({
                        title: 'AVI cars:',
                        text: "No se pudo mover el auto de garage.",
                        type: 'error'
                    });
                }
                else{
                    new PNotify({
                        title: 'AVI cars:',
                        text: "El auto ha sido a movido de garage.",
                        type: 'success'
                    });
                    setTimeout(function(){
                        window.location.href="/perfil/autos/detalles/?cuenta="+msg.c+"&auto="+msg.a;
                    },3000);
                }
                
            },
            error : function(){
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                new PNotify({
                    title: 'AVI cars:',
                    text: "No se pudo mover el auto de garage.",
                    type: 'error'
                });
            }
        })
    })
 });

function selectMarca(){
    var imgB = $("#marca option:selected").text();

    if ($("#marca option:selected").val() == 0) {
      $("#brandImg").attr("src","");
      $(".otraMarca").addClass("hidden");
      $(".otroModelo").addClass("hidden");
      $(".otroAno").addClass("hidden");
      $("#otroModeloInput").val("");
      $("#otroAnoInput").val("");
    }
    else if($("#marca option:selected").val() == -1){
      $("#brandImg").attr("src","");
      $("#otherver").removeClass("hidden");
      $(".otraMarca").removeClass("hidden");
      $(".otroModelo").removeClass("hidden");
      $("#ano option:selected").val("-1");
    }
    else {
      $("#brandImg").attr("src","/img/logos/"+imgB+".png");
      $(".otraMarca").addClass("hidden");
      $("#otherver").addClass("hidden");
      $(".otroModelo").addClass("hidden");
      $(".otroAno").addClass("hidden");
      $("#otroModeloInput").val("");
      $("#otroAnoInput").val("");
    }
}
function selectSubmarca(){
  if ($(".submarca option:selected").val() == -1) {
    $(".otroModelo").removeClass("hidden");
    $("#otherver").removeClass("hidden");
    $(".otroAno").addClass("hidden");
  }
  else {
    $(".otroModelo").addClass("hidden");
    $(".otraMarca").addClass("hidden");
    $(".otroAno").addClass("hidden");
    $("#otherver").addClass("hidden");
    $("#otroAnoInput").val("");
  }
}
function selectModelo(){
  if ($("#ano option:selected").val() == -1) {
    $(".otroAno").removeClass("hidden");
      $("#otherver").removeClass("hidden");
  }
  else {
    $(".otroAno").addClass("hidden");
    $("#otherver").addClass("hidden");
  }
}
function colorCatalogo()
{
    $("#colorsCatalogo").modal("show");
}
function tipoClase()
{
  	$("#tipoClase").modal("show");
}
 function addOther(e)
{
    contenedor=e.closest(".agregar");
    valor=contenedor.find("input").val();
    contenedorHtml=contenedor.html();
    contenedorHtml=contenedorHtml.replace(valor,"");
    contenedorHtml=contenedorHtml.replace("glyphicon-plus","glyphicon-trash");
    contenedorHtml=contenedorHtml.replace("btn-success","btn-danger");
    contenedorHtml=contenedorHtml.replace("addOther","removeThis");
    contenedor.parent().append("<div class='agregar'>"+contenedorHtml+"</div>");
}
function removeThis(e)
{
    e.closest(".agregar").remove();
}
function addCharacteristics(e)
{
	e.siblings(".space-throw").html("" +
    "<div class='agregar'>" +
    " <div class='input-group'>" +
    "   <input type = 'text' name='"+e.data("aniadir")+"[]' class='form-control form-style "+e.data("class")+"' placeholder='Añadir' /> " +
    "   <span class='input-group-btn repara-espaciotop'>" +
    "     <button type = 'button' name='agregar' class='btn btn-success glyphicon glyphicon-plus 'onclick='addOther($(this))'> </button>" +
    "   </span>" +
    " </div>" +
    "</div>" +
    "");
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
function changeGarageCar(){
  $("#modalChangeGarage").modal("show");
}
function changeEstado(nivel){
  $("#check_starsitas p").find("img").remove();
  
  for (var i=1; i <= 5; i++) {
    if (i<=nivel) {
      $("#check_starsitas p").append('<img onclick="changeEstado('+i+')" src="/img/auto-icons/black_star.png">\n');
    }
    else
    {
      $("#check_starsitas p").append('<img onclick="changeEstado('+i+')" src="/img/auto-icons/black_star_outline.png">\n');
    }
  }
  switch(nivel){
    case 5:
      $("input[name='estado'][value='1']").prop("checked",true);
      $("#check_starsitas span").remove();
      $("#check_starsitas label").after('<span>&nbsp;Impecable</span>');
      break;
    case 4:
      $("input[name='estado'][value='2']").prop("checked",true);
      $("#check_starsitas span").remove();
      $("#check_starsitas label").after('<span>&nbsp;Bueno</span>');
      break;
    case 3:
      $("input[name='estado'][value='3']").prop("checked",true);
      $("#check_starsitas span").remove();
      $("#check_starsitas label").after('<span>&nbsp;Regular</span>');
      break;
    case 2:
      $("input[name='estado'][value='4']").prop("checked",true);
      $("#check_starsitas span").remove();
      $("#check_starsitas label").after('<span>&nbsp;Malo</span>');
      break;
    case 1:
      $("input[name='estado'][value='5']").prop("checked",true);
      $("#check_starsitas span").remove();
      $("#check_starsitas label").after('<span>&nbsp;Accidentado</span>');
      break;
    case 0:
      $("input[name='estado'][value='6']").prop("checked",true);
      $("#check_starsitas span").remove();
      $("#check_starsitas label").after('<span>&nbsp;No camina</span>');
      break;
  }
}