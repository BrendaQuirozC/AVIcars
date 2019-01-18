/*
* @Author: Erik Viveros
* @Date:   2018-08-14 16:42:45
* @Last Modified by:   BrendaQuiroz
* @Last Modified time: 2019-01-18 12:02:34
*/
$(".icon-user-btn").click(function(){
    if($(this).data("open")==1){
        $(this).siblings("span").hide();
        $(this).siblings("ul").hide();
        $(this).data("open",0);
    }
    else{
        $(this).siblings("span").show();
        $(this).siblings("ul").show();
        $(this).data("open",1);
    }
    
});
$(document).click(function(event) {
    //alert("no");
    if(!$(event.target).closest('.icon-user-btn').length && !$(event.target).is('.icon-user-btn')) {
        if(!$(event.target).is('.submenu-profile')) {
            $(".submenu-profile").hide();
            $(".submenu-caret-outer").hide();
            $(".submenu-caret-inner").hide();
            $(".icon-user-btn").data("open",0);
        }   
    }
});
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var imgs={};
var cont=0;
var navbarHeight = $('.search-nav').outerHeight();
function nuevoAuto(e){
	xhr = new XMLHttpRequest();
    var response={};
    var url = "/php/perfil/nuevoAuto.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.responseType = "json";
    $("#otraMarcaInputmodal").next("span.alert-danger").remove();
    $("#otroModeloInputmodal").next("span.alert-danger").remove();
    $("#otroVersionInputmodal").next("span.alert-danger").remove();
    var marcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!marcaRegex.test($("#otraMarcaInputmodal").val()) && $("#otraMarcaInputmodal").val() != "")
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, marca no v&aacute;lida.',
            type: 'error'
        });
        $("#otraMarcaInputmodal").after("<span class='alert-danger'>Marca no v&aacute;lida</span>");
        $("#otraMarcaInputmodal").focus();
        return false;
    }
    var submarcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!submarcaRegex.test($("#otroModeloInputmodal").val()) && $("#otroModeloInputmodal").val() != "")
    {
        $next=false;
        $("#otroModeloInputmodal").after("<span class='alert-danger'>Submarca no v&aacute;lida</span>");
        $("#otroModeloInputmodal").focus();
        return false;
    }
    var otroAno=$("#otroAnoInputmodal").val()*1;
	var modeloVal=$("#anomodal").val();
	send=true;
	var year = new Date().getFullYear();
    if($("#otroAnoInputmodal").val() != "" && modeloVal==-1)
    {
    	$("#otroAnoInputmodal").next("span.alert-danger").remove();
	    if(!Number.isInteger(otroAno)){
	        send=false;
	        }
	        if(send && (otroAno < 1890 || otroAno > year+1 ))
	        {
	          	send=false;
	        }
	        if(!send){
                new PNotify({
                title: 'AVI cars:',
                text: 'Lo sentimos, a&ntilde;o no v&aacute;lida.',
                type: 'error'
            });
	        	$("#otroAnoInputmodal").after("<span class='alert-danger'>Año incorrecto</span>");
	            $("#otroAnoInputmodal").focus();
	            return false;
	        }
	        else{
	          $("#otroAnoInputmodal").removeClass('has-error');
        }
    }
    var versionRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!versionRegex.test($("#otroVersionInputmodal").val()) && $("#otroVersionInputmodal").val() != "")
    {
        $next=false;
        $("#otroVersionInputmodal").after("<span class='alert-danger'>Versi&oacute;n no v&aacute;lida</span>");
        $("#otroVersionInputmodal").focus();
        return false;
    }
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            response=this.response;
        }
        else
        {
            response={Error : "Ocurrio un error, por favor contacte al administrador."};
        }

        var respsuccess= response.Success;
       	if(respsuccess !=null){
       		$("#buttonAdDetailCar").attr("onclick","window.location.href='"+response.Success+"'");
            $("#autoServiciosNuevoAuto").attr("value",response.auto);
            $(".modal").modal("hide");
            $("#successCar").modal("show");   
       	}
       	else
       	{
		 	new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un error, intente m&aacute;s tarde.',
                type: 'error'
            });
       	}
       	$("#addCar").modal("hide");
    }
    if(e.data("form")=="nuevoAutoModal")
    {
    	var data = $("#"+e.data("form")).serialize() +"&img="+JSON.stringify(imgsNewCar) ;
    }
    else
    {
    	var data = $("#"+e.data("form")).serialize();
    }
    xhr.send(data);
}
function moneyCar(){
	$(".modal").modal("hide");
	$("#monetizar").modal("show");
    $("#extraFeaturesAd").data("open","0");
}
function returnFromAd(e){
    $(".modal").modal("hide");
    $("#monetizar").modal("show");
    $("#extraFeaturesAd").data("open","0");
}
function newCarModal(e){
    $("#extraFeatures").data("open","0");
    $(".newcarphotos").remove();
	if($("#extraFeatures").data("open")=="0")
	{
		$.ajax({
			type:"POST",
			url: "/php/auto/createNewCar.php",
			success : function(resp){
				$("#extraFeatures").html(resp);
				$("#extraFeatures").data("open","1");
			}
		});
	}
	$(".modal").modal("hide");
	$("#addCar").modal("show");
	//agregarCarro(e.data("cuenta"),e.data("garage"));
}
function newCarAd(){
    var idCar ="";
    var autoServicio="";
    $(".wizard .wizard-steps").find("li").removeClass("active");
    $(".wizard .wizard-steps").find("li:first-child").addClass("active");
    $(".wizard .wizard-body").find("div").removeClass("active");
    $(".wizard .wizard-body").find("div:first-child").addClass("active");
    $(".wizard .next").prop("disabled",false);
    $(".wizard .previous").prop("disabled",true);
    $("#wizardAdvertisement .next").removeClass("finish");
    $("#wizardAdvertisement .next").text("Siguiente");
    if($("#autoServiciosNuevoAuto").val() != "undefined" && $("#autoServiciosNuevoAuto").val() != ""){
        var idCar = $("#autoServiciosNuevoAuto").val();
        var autoServicio = $("#autoServiciosNuevoAuto");
    }
    else{
        var idCar = $("#autoServicios").val();
        var autoServicio = $("#autoServicios")
    }
    curCar=idCar;
    $("#autoServicios").next("span.alert-danger").remove();
    if(autoServicio.val()!=0 && autoServicio.val()!=-1)
    {
        $(".seeAd").remove();
        $("#editingPhotosCarModalAd").find(".inline-logos-ad").remove();
        $.ajax({
            type:"POST",
            async : true,
            data: "idCar=" + encodeURIComponent(idCar),
            dataType : "json",
            url: "/php/auto/createNewAdd.php",
            success : function(resp)
            {
                if(resp.AlreadyAd == true)
                {
                    $("#autoServicios").focus();
                    $("#autoServicios").after("<span class='alert-danger'>Este auto ya fue anunciado.</span> <a class='seeAd' href='/anuncio/?a="+resp.adCoded+"' >Ver anuncio.</a>");
                }
                else
                {
                    $("#aliasModal").val(resp.Alias);
                    $("#precioModal").val(resp.Precio);
                    $("#check_priceModal option[value ="+resp.Currency+"]").attr("selected","selected");
                    $("#autoModal").val(resp.auto);
                    $("#cuentaModal").val(resp.garage);
                    $("#garageinsertcarModal").val(resp.Garageid);
                    $("#carModal").val(resp.Car);
                    $("#phonecodeModal").val(resp.phone1.code);
                    $("#phoneModal").val(resp.phone1.phone);
                    $("#phonewaModal").val(resp.phone1.wa);
                    $("#phone2codeModal").val(resp.phone2.code);
                    $("#phone2Modal").val(resp.phone2.phone);
                    $("#phone2waModal").val(resp.phone2.wa);
                    $("#phone3codeModal").val(resp.phone3.code);
                    $("#phone3Modal").val(resp.phone3.phone);
                    $("#phone3waModal").val(resp.phone3.wa);
                    $("#emailModal").val(resp.mail1);
                    $("#textAdModal").val(resp.texto);
                    $("#zipcodeModal").val(resp.address.cp);
                    if(resp.phone1.wa){
                        $("#phonewaModal").prop("checked",true);
                    }
                    else{
                        $("#phonewaModal").prop("checked",false);
                    }
                    if(resp.phone2.wa){
                        $("#phone2waModal").prop("checked",true);
                    }
                    else{
                        $("#phone2waModal").prop("checked",false);
                    }
                    if(resp.phone3.wa){
                        $("#phone3waModal").prop("checked",true);
                    }
                    else{
                        $("#phone3waModal").prop("checked",false);
                    }
                    $.each(resp.Imagenes,function(e,el)
                    {
                        var html = '';
                        html += '<div class="inline-logos-ad col-xs-6 newImgEdit" style="background-image: url('+el.a_avi_car_img_car+')">';
                            html +='<div class="font-trash">'
                            html +=' <a class="icon-trash icon-trash-car" data-img="'+el.imagenId+'"  data-vehicle="'+resp.auto+'" onclick="showModalTrashAd($(this))" title="Borrar"></a>'
                            html +='</div>'
                        html +='</div>';
                        $("#editingPhotosCarModalAd").prepend(html);
                    })
                    $("#extraFeaturesAd").data("open","1");
                    setTimeout(function(){
                        $("#marcamodalAd").val(resp.brand);
                        $("#otraMarcaInputmodalAd").val(resp.Marca);
                        changeMarcaAd($("#marcamodalAd"));
                        $("#modelomodalAd").val(resp.subbrand);
                        $("#otroModeloInputmodalAd").val(resp.Submarca);
                        changeSubmarcaAd($("#modelomodalAd"));
                        $("#anomodalAd").val(resp.model);
                        $("#otroAnoInputmodalAd").val(resp.Modelo);
                        changeAnoAd($("#anomodalAd"));
                        $("#versionmodalAd").val(resp.versionmd); 
                        $("#otroVersionInputmodalAd").val(resp.Version);
                    },300);
                    setTimeout(function(){
                        if($("#marcamodalAd").val()=="-1")
                        {
                            $(".otraMarca").removeClass("hidden");
                            $("#modelomodalAd").val("-1");
                            $("#anomodalAd").val("-1");
                            $("#versionmodalAd").val("-1");
                            $(".otroModelo").removeClass("hidden");
                            $("#otheryearmodalAd").removeClass("hidden");
                            $("#othervermodalAd").removeClass("hidden");
                        }
                        if($("#modelomodalAd").val()=="-1")
                        {
                            $("#anomodalAd").val("-1");
                            $("#versionmodalAd").val("-1");
                            $(".otroModelo").removeClass("hidden");
                            $("#otheryearmodalAd").removeClass("hidden");
                            $("#othervermodalAd").removeClass("hidden");
                        }
                        if($("#anomodalAd").val()=="-1")
                        {
                            $("#versionmodalAd").val("-1");
                            $("#modelomodalAd").val("-1");
                            $("#otheryearmodalAd").removeClass("hidden");
                        }
                        if($("#versionmodalAd").val() == -1) 
                        {
                            $("#othervermodalAd").removeClass("hidden");
                        }
                    },350);
                    $(".modal").modal("hide");
                    $("#modalCrearAnuncio").modal("show");
                }
            }
        });
        
    }
    else
    {
        if(autoServicio.val()==-1)
        {
            $(".modal").modal("hide");
            if($("#extraFeatures").data("open")=="0")
            {
                $.ajax({
                    type:"POST",
                    url: "/php/auto/createNewCar.php",
                    success : function(resp){
                        $("#extraFeatures").html(resp);
                        $("#extraFeatures").data("open","1");
                    }
                });
            }
            $("#addCar").modal("show");               
        }
        else if(autoServicio.val()==0)
        {
            $("#autoServicios").focus();
            $("#autoServicios").after("<span class='alert-danger'>Debes seleccionar un auto para anunciar</span>");
        }
    }  
}
function movilidadModal(){
    var idCar ="";
    var autoServicio="";
    $(".wizard .wizard-steps").find("li").removeClass("active");
    $(".wizard .wizard-steps").find("li:first-child").addClass("active");
    $(".wizard .wizard-body").find("div").removeClass("active");
    $(".wizard .wizard-body").find("div:first-child").addClass("active");
    $(".wizard .next").prop("disabled",false);
    $(".wizard .previous").prop("disabled",true);
    $(".wizard .next").removeClass("finish");
    $(".wizard .next").text("Siguiente");
    if($("#autoServiciosNuevoAuto").val() != "undefined" && $("#autoServiciosNuevoAuto").val() != ""){
        var idCar = $("#autoServiciosNuevoAuto").val();
        var autoServicio = $("#autoServiciosNuevoAuto");
    }
    else{
        var idCar = $("#autoServicios").val();
        var autoServicio = $("#autoServicios")
    }
    curCar=idCar;
    $("#autoServicios").next("span.alert-danger").remove();
    if(autoServicio.val()!=0 && autoServicio.val()!=-1)
    {
        $.ajax({
            type:"POST",
            async : true,
            data: "idCar=" + encodeURIComponent(idCar),
            dataType : "json",
            url: "/php/auto/createNewAdd.php",
            success : function(resp)
            {
                $("#nombreModalMov").val(resp.nombre);
                $("#apellidoModalMov").val(resp.apellido);
                $("#edadModalMov").val(resp.age);
                $("#cpModalMov").val(resp.cp);
                $("#telefonoModalMov").val(resp.mainPhone);
                $("#emailModalMov").val(resp.mainMail);
                setTimeout(function(){
                    $("#marcamodalMov").val(resp.brand);
                    $("#otraMarcaInputmodalMov").val(resp.Marca);
                    changeMarcaMov($("#marcamodalMov"));
                    $("#modelomodalMov").val(resp.subbrand);
                    $("#otroModeloInputmodalMov").val(resp.Submarca);
                    changeSubmarcaMov($("#modelomodalMov"));
                    $("#anomodalMov").val(resp.model);
                    $("#otroAnoInputmodalMov").val(resp.Modelo);
                    changeAnoMov($("#anomodalMov"));
                    $("#versionmodalMov").val(resp.versionmd); 
                    $("#otroVersionInputmodalMov").val(resp.Version);
                },300);
                setTimeout(function(){
                    if($("#marcamodalMov").val()=="-1")
                    {
                        $(".otraMarca").removeClass("hidden");
                        $("#modelomodalMov").val("-1");
                        $("#anomodalMov").val("-1");
                        $("#versionmodalMov").val("-1");
                        $(".otroModelo").removeClass("hidden");
                        $("#otheryearmodalMov").removeClass("hidden");
                        $("#othervermodalMov").removeClass("hidden");
                    }
                    if($("#modelomodalMov").val()=="-1")
                    {
                        $("#anomodalMov").val("-1");
                        $("#versionmodalMov").val("-1");
                        $(".otroModelo").removeClass("hidden");
                        $("#otheryearmodalMov").removeClass("hidden");
                        $("#othervermodalMov").removeClass("hidden");
                    }
                    if($("#anomodalMov").val()=="-1")
                    {
                        $("#versionmodalMov").val("-1");
                        $("#modelomodalMov").val("-1");
                        $("#otheryearmodalMov").removeClass("hidden");
                    }
                    if($("#versionmodalMov").val() == -1) 
                    {
                        $("#othervermodalMov").removeClass("hidden");
                    }
                },350);
                $(".modal").modal("hide");
                $("#modalMovilidad").modal("show");
            }
        });
        
    }
    else
    {   
        if(autoServicio.val()==-1)
        {
            $(".modal").modal("hide");
            if($("#extraFeatures").data("open")=="0")
            {
                $.ajax({
                    type:"POST",
                    url: "/php/auto/createNewCar.php",
                    success : function(resp){
                        $("#extraFeatures").html(resp);
                        $("#extraFeatures").data("open","1");
                    }
                });
            }
            $("#addCar").modal("show");               
        }
        else if(autoServicio.val()==0)
        {
            $("#autoServicios").focus();
            $("#autoServicios").after("<span class='alert-danger'>Debes seleccionar un auto para continuar</span>");
        }
    }  
}
function seguroModal(){
    var idCar ="";
    var autoServicio="";
    $(".wizard .wizard-steps").find("li").removeClass("active");
    $(".wizard .wizard-steps").find("li:first-child").addClass("active");
    $(".wizard .wizard-body").find("div").removeClass("active");
    $(".wizard .wizard-body").find("div:first-child").addClass("active");
    $(".wizard .next").prop("disabled",false);
    $(".wizard .previous").prop("disabled",true);
    $(".wizard .next").removeClass("finish");
    $(".wizard .next").text("Siguiente");
    if($("#autoServiciosNuevoAuto").val() != "undefined" && $("#autoServiciosNuevoAuto").val() != ""){
        var idCar = $("#autoServiciosNuevoAuto").val();
        var autoServicio = $("#autoServiciosNuevoAuto");
    }
    else{
        var idCar = $("#autoServicios").val();
        var autoServicio = $("#autoServicios")
    }
    curCar=idCar;
    $("#autoServicios").next("span.alert-danger").remove();
    if(autoServicio.val()!=0 && autoServicio.val()!=-1)
    {
        $.ajax({
            type:"POST",
            async : true,
            data: "idCar=" + encodeURIComponent(idCar),
            dataType : "json",
            url: "/php/auto/createNewAdd.php",
            success : function(resp)
            {
                $("#nombreModalIns").val(resp.nombre);
                $("#apellidoModalIns").val(resp.apellido);
                $("#edadModalIns").val(resp.age);
                $("#cpModalIns").val(resp.cp);
                $("#telefonoModalMov").val(resp.mainPhone);
                $("#emailModalMov").val(resp.mainMail);
                setTimeout(function(){
                    $("#marcamodalIns").val(resp.brand);
                    $("#otraMarcaInputmodalIns").val(resp.Marca);
                    changeMarcaIns($("#marcamodalIns"));
                    $("#modelomodalIns").val(resp.subbrand);
                    $("#otroModeloInputmodalIns").val(resp.Submarca);
                    changeSubmarcaIns($("#modelomodalIns"));
                    $("#anomodalIns").val(resp.model);
                    $("#otroAnoInputmodalIns").val(resp.Modelo);
                    changeAnoIns($("#anomodalIns"));
                    $("#versionmodalIns").val(resp.versionmd); 
                    $("#otroVersionInputmodalIns").val(resp.Version);
                },300);
                setTimeout(function(){
                    if($("#marcamodalIns").val()=="-1")
                    {
                        $(".otraMarca").removeClass("hidden");
                        $("#modelomodalIns").val("-1");
                        $("#anomodalIns").val("-1");
                        $("#versionmodalIns").val("-1");
                        $(".otroModelo").removeClass("hidden");
                        $("#otheryearmodalIns").removeClass("hidden");
                        $("#othervermodalIns").removeClass("hidden");
                    }
                    if($("#modelomodalIns").val()=="-1")
                    {
                        $("#anomodalIns").val("-1");
                        $("#versionmodalIns").val("-1");
                        $(".otroModelo").removeClass("hidden");
                        $("#otheryearmodalIns").removeClass("hidden");
                        $("#othervermodalIns").removeClass("hidden");
                    }
                    if($("#anomodalIns").val()=="-1")
                    {
                        $("#versionmodalIns").val("-1");
                        $("#modelomodalIns").val("-1");
                        $("#otheryearmodalIns").removeClass("hidden");
                    }
                    if($("#versionmodalIns").val() == -1) 
                    {
                        $("#othervermodalIns").removeClass("hidden");
                    }
                },350);
                $(".modal").modal("hide");
                $("#modalSeguros").modal("show");
            }
        });
    }
    else
    {
        if(autoServicio.val()==-1)
        {
            $(".modal").modal("hide");
            if($("#extraFeatures").data("open")=="0")
            {
                $.ajax({
                    type:"POST",
                    url: "/php/auto/createNewCar.php",
                    success : function(resp){
                        $("#extraFeatures").html(resp);
                        $("#extraFeatures").data("open","1");
                    }
                });
            }
            $("#addCar").modal("show");               
        }
        else if(autoServicio.val()==0)
        {
            $("#autoServicios").focus();
            $("#autoServicios").after("<span class='alert-danger'>Debes seleccionar un auto para asegurar</span>");
        }
    }  
}

function compraModal(){
    var idCar ="";
    var autoServicio="";
    $(".wizard .wizard-steps").find("li").removeClass("active");
    $(".wizard .wizard-steps").find("li:first-child").addClass("active");
    $(".wizard .wizard-body").find("div").removeClass("active");
    $(".wizard .wizard-body").find("div:first-child").addClass("active");
    $(".wizard .next").prop("disabled",false);
    $(".wizard .previous").prop("disabled",true);
    $(".wizard .next").removeClass("finish");
    $(".wizard .next").text("Siguiente");
    if($("#autoServiciosNuevoAuto").val() != "undefined" && $("#autoServiciosNuevoAuto").val() != ""){
        var idCar = $("#autoServiciosNuevoAuto").val();
        var autoServicio = $("#autoServiciosNuevoAuto");
    }
    else{
        var idCar = $("#autoServicios").val();
        var autoServicio = $("#autoServicios")
    }
    curCar=idCar;
    $("#autoServicios").next("span.alert-danger").remove();
    if(autoServicio.val()!=0 && autoServicio.val()!=-1)
    {
        $.ajax({
            type:"POST",
            async : true,
            data: "idCar=" + encodeURIComponent(idCar),
            dataType : "json",
            url: "/php/auto/createNewAdd.php",
            success : function(resp)
            {
                $("#nombreModalCompra").val(resp.nombre);
                $("#apellidoModalCompra").val(resp.apellido);
                $("#edadModalCompra").val(resp.age);
                $("#cpModalCompra").val(resp.cp);
                $("#telefonoModalMov").val(resp.mainPhone);
                $("#emailModalMov").val(resp.mainMail);
                setTimeout(function(){
                    $("#marcamodalCompra").val(resp.brand);
                    $("#otraMarcaInputmodalCompra").val(resp.Marca);
                    changeMarcaCompra($("#marcamodalCompra"));
                    $("#modelomodalCompra").val(resp.subbrand);
                    $("#otroModeloInputmodalCompra").val(resp.Submarca);
                    changeSubmarcaCompra($("#modelomodalCompra"));
                    $("#anomodalCompra").val(resp.model);
                    $("#otroAnoInputmodalCompra").val(resp.Modelo);
                    changeAnoCompra($("#anomodalCompra"));
                    $("#versionmodalCompra").val(resp.versionmd); 
                    $("#otroVersionInputmodalCompra").val(resp.Version);
                },300);
                setTimeout(function(){
                    if($("#marcamodalCompra").val()=="-1")
                    {
                        $(".otraMarca").removeClass("hidden");
                        $("#modelomodalCompra").val("-1");
                        $("#anomodalCompra").val("-1");
                        $("#versionmodalCompra").val("-1");
                        $(".otroModelo").removeClass("hidden");
                        $("#otheryearmodalCompra").removeClass("hidden");
                        $("#othervermodalCompra").removeClass("hidden");
                    }
                    if($("#modelomodalCompra").val()=="-1")
                    {
                        $("#anomodalCompra").val("-1");
                        $("#versionmodalCompra").val("-1");
                        $(".otroModelo").removeClass("hidden");
                        $("#otheryearmodalCompra").removeClass("hidden");
                        $("#othervermodalCompra").removeClass("hidden");
                    }
                    if($("#anomodalCompra").val()=="-1")
                    {
                        $("#versionmodalCompra").val("-1");
                        $("#modelomodalCompra").val("-1");
                        $("#otheryearmodalCompra").removeClass("hidden");
                    }
                    if($("#versionmodalCompra").val() == -1) 
                    {
                        $("#othervermodalCompra").removeClass("hidden");
                    }
                },350);
                $(".modal").modal("hide");
                $("#modalCompra").modal("show");
            }
        });
    }
    else
    {
        if(autoServicio.val()==-1)
        {
            $(".modal").modal("hide");
            if($("#extraFeatures").data("open")=="0")
            {
                $.ajax({
                    type:"POST",
                    url: "/php/auto/createNewCar.php",
                    success : function(resp){
                        $("#extraFeatures").html(resp);
                        $("#extraFeatures").data("open","1");
                    }
                });
            }
            $("#addCar").modal("show");               
        }
        else if(autoServicio.val()==0)
        {
            $("#autoServicios").focus();
            $("#autoServicios").after("<span class='alert-danger'>Debes seleccionar un auto para continuar</span>");
        }
    }  
}

var floatMenu=document.getElementById("header-list");
var floatSidebar=document.getElementById("sidebar");

if(floatMenu===null)
{
	startMenu=0;
}
else{
	var startMenu=floatMenu.getBoundingClientRect().top+$(this).scrollTop();

}
if(floatSidebar===null)
{
	startSidebar=0;
}
else{
	var startSidebar=floatSidebar.getBoundingClientRect().top+$(this).scrollTop();
}
var $header=$("html").find("div.container-fluid").children('div');
if($header.hasClass("header head-form"))
{
	var $scrollToDisplay=417;
}
else{
    if($header.hasClass("head-form-garage"))
    {
        var $scrollToDisplay=403;
    }
    else
    {
    	var $scrollToDisplay=30;
    }
}
$(document).scroll(function() {
	var scrollDistance = $(document).scrollTop();
    var stickyMenu = $("#sidebar");
    if(scrollDistance >$scrollToDisplay)  {
        $(".header-list").addClass("navbar-fixed-top nav-ul");
        $(".sidebar").addClass("sidebar-fixed");
        var ListMenu=true;
    } else {
        $(".header-list").removeClass("navbar-fixed-top nav-ul")
        $(".sidebar").removeClass("sidebar-fixed");
    	var ListMenu=false;
    }
    if(scrollDistance > lastScrollTop && scrollDistance > navbarHeight){
        // Scroll Down
        $('.search-nav').removeClass('nav-down').addClass('nav-up');
        $(".secondary-nav").css("top","0px");
        if(ListMenu)
        {
        	$(".header-list").css("top","54px");
        }

        $(".alert-nav").css("top","54px");
    } else {
        // Scroll Up
        if(scrollDistance + $(window).height() < $(document).height()) {
            $('.search-nav').removeClass('nav-up').addClass('nav-down');

        	$(".secondary-nav").css("top","54px");
        	if(ListMenu)
	        {
	        	$(".header-list").css("top","108px");
	        }
        	$(".alert-nav").css("top","108px");
        }
    }
    lastScrollTop = scrollDistance;
});

function publish(e){
	$("#reload-band").removeClass('hidden');
	$("#flag-reload").removeClass('hidden');
	xhr = new XMLHttpRequest();
    var response={};
    var post=$("#publication").val();
    var regexpPost = new RegExp(/^([^'´´\\"])*$/g);
    if(!regexpPost.test(post)){
        new PNotify({
            title: 'AVI cars:',
            text: "Error al publicar",
            type: 'error'
        });
        $("#reload-band").addClass('hidden');
        $("#flag-reload").addClass('hidden');
        return false;
    }
    var url = "/php/perfil/publicar.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.responseType = "json";
    xhr.onreadystatechange = function () { 
    	$("#reload-band").addClass('hidden');
	    $("#flag-reload").addClass('hidden');
        if(this.status==200)
        {
            response=this.response;
        }
        else
        {
            response={Error : "Ocurrio un error, por favor contacte al administrador."};
        }
       	if(response.Success){
       		window.location.href=response.url;	
       	}
       	else if(response.Error)
       	{
       		new PNotify({
                title: 'AVI cars:',
                text: response.Tipo,
                type: 'error'
        	});
       	}    
       	$("#doPublication").modal("hide");
    }
    var data = "publicacion="+encodeURIComponent(post)+"&img="+JSON.stringify(imgs) + "&who=" + encodeURIComponent(whoprivacy) + "&tipo=" + tipoPublish;
    if(dataGarageToPublish=$("#nombreGarage").data("garagetopublish"))
    {
    	data +="&garage=" + encodeURIComponent(dataGarageToPublish);
    }
    if($("#nombreAutor").data("usr-publish"))
    {
    	data+="&usuario="+ encodeURIComponent($("#nombreAutor").data("usr-publish"));
    }
    xhr.send(data);
}
function removeAllImages()
{
	$("#imgDrop").hide();
}
function addImage()
{
	$("#imgDrop").show();
}
function modalToDeletePub(id)
{
	$("#deletePublication").modal("show");
    $("#deletePublication").find("button:last").data("publicacion",id);
}

function modalToDeleteCom(id)
{
    $("#deleteComment").modal("show");
    $("#deleteComment").find("button:last").data("comment",id);
}
function modalToDeleteComAd(id)
{
    $("#deleteCommentAd").modal("show");
    $("#deleteCommentAd").find("button:last").data("comment",id);
}
function deleteComment(e){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    $("#deleteComment").modal("hide");
    var id = e.data("comment");
    $.ajax({
        url : "/php/perfil/publicacion/deleteComment.php",
        async : false,
        type : "POST",
        data: "c="+encodeURIComponent(id),
        dataType:"json",
        success : function(resp){
            //alert(resp);
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(resp.Success)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: "Comentario borrado.",
                    type: 'success'
                });
                $("li[data-comment='"+id+"']").remove();
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: "No se pudo borrar el comentario",
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
function deleteCommentAd(e){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    $("#deleteCommentAd").modal("hide");
    var id = e.data("comment");
    $.ajax({
        url : "/php/auto/deleteComment.php",
        async : false,
        type : "POST",
        data: "c="+encodeURIComponent(id),
        dataType:"json",
        success : function(resp){
            //alert(resp);
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(resp.Success)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: "Comentario borrado.",
                    type: 'success'
                });
                $("li[data-comment='"+id+"']").remove();
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: "No se pudo borrar el comentario",
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
function modalToReport(e,perfil,publicacion)
{
	$("#reporting").modal("show");
	$("#reporting").find("li").attr("data-perfil", e.data('perfil'));
	$("#reporting").find("li").attr("data-publicacion", e.data('publicacion'));
    $("#reporting").find("li").attr("data-garage", e.data('garage'));
    $("#reporting").find("li").attr("data-comment", e.data('comment'));
    $("#reporting").find("li").attr("data-car", e.data('car'));
    $("#reporting").find("li").attr("data-ad", e.data('ad'));
    $("#reporting").find("li").attr("data-adcomment", e.data('adcomment'));
}
function Reported(e)
{
	var idPerfil=e.data("perfil");
	var idPublicacion=e.data("publicacion");
	var idGarage=e.data("garage");
    var comment=e.data("comment");
    var car=e.data("car");
    var ad=e.data("ad");
    var adcomment=e.data("adcomment");
    var text=$("#whyreportcomment").val();
    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
	if(idGarage===undefined){
		idGarage=0;
	}
	if(idPublicacion===undefined){
		idPublicacion=0;
	}
    if(comment===undefined){
        comment=0;
    }
    if(car===undefined){
        car=0;
    }
    if(ad===undefined){
        ad=0;
    }
    if(adcomment===undefined){
        adcomment=0;
    }
    if(!nameRegex.test(text))
    {
        $("#whyreportcomment").focus();
        $("#whyreportcomment").closest("form-control").addClass("has-error");
        new PNotify({
            title: 'AVI cars:',
            text: "Texto no v&aacute;lido",
            type: 'error'
        });
        return false;
    }
	var type= e.data("type");
	$("#reporting").modal("hide");
	$.ajax({
        url : "/php/report/reported.php",
        async : false,
        type : "POST",
        data: "text="+encodeURIComponent(text)+"&perfil=" + encodeURIComponent(idPerfil) + "&garage=" + encodeURIComponent(idGarage) + "&car=" + encodeURIComponent(car) + "&ad=" + encodeURIComponent(ad) + "&publicacion=" + encodeURIComponent(idPublicacion) + "&comentario=" + encodeURIComponent(comment) + "&adcomment=" + encodeURIComponent(adcomment) + "&type=" + type,
        dataType:"json",
        success : function(resp){
        	if(resp.Success)
        	{
        		new PNotify({
                    title: '¡Gracias por tu reporte!',
                    text: resp.Success,
                    type: 'success'
            	});
        	}
        	else
        	{
        		new PNotify({
                    title: 'AVI cars:',
                    text: resp.Error,
                    type: 'error'
            	});
        	}
        }
    });
}
function deletePublication(e)
{
	$("#reload-band").removeClass('hidden');
	$("#flag-reload").removeClass('hidden');
	$("#deletePublication").modal("hide");
	var id = e.data("publicacion");
	$.ajax({
        url : "/php/perfil/publicacion/deletePublication.php",
        async : false,
        type : "POST",
        data: "publicacion="+encodeURIComponent(id),
        dataType:"json",
        success : function(resp){
            //alert(resp);
         	$("#reload-band").addClass('hidden');
	        $("#flag-reload").addClass('hidden');
            if(resp.Success)
            {
            	new PNotify({
                    title: 'AVI cars:',
                    text: resp.Success,
                    type: 'success'
            	});
                console.log(window.location.pathname);
                if(window.location.pathname=="/post/"){
                    setTimeout(function(){window.location.href="/timeline"},2000); 
                }
                else{
                    setTimeout(function(){location.reload()},2000);     
                }
            	
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
    })
}
function editPublication(id)
{
	$("#reload-band").addClass('hidden');
    $("#flag-reload").addClass('hidden');
	$.ajax({
        url : "/php/perfil/publicacion/editPublication.php",
        async : false,
        type : "POST",
        data: "publicacion="+encodeURIComponent(id)+"&get="+true,
        dataType:"json",
        success : function(resp){
        	$("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            $("#editPublication").modal("show");
            $("#editPublication").find("button:last").data("publicacion",id);
            $("#republication").text(resp.contenido);
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
function republication(e)
{
    var post=$("#republication").val();
    var regexpPost = new RegExp(/^([^'´´\\"])*$/g);
    if(!regexpPost.test(post)){
        new PNotify({
            title: 'AVI cars:',
            text: "Error al publicar. No se admiten comillas o signos '=' .",
            type: 'error'
        });
        $("#reload-band").addClass('hidden');
        $("#flag-reload").addClass('hidden');
        return false;
    }
	$("#reload-band").removeClass('hidden');
	$("#flag-reload").removeClass('hidden');
	publicacion = e.data("publicacion");
	xhr = new XMLHttpRequest();
    var response={};
    var url = "/php/perfil/publicacion/editPublication.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.responseType = "json";
    xhr.onreadystatechange = function () { 
	 	$("#reload-band").addClass('hidden');
        $("#flag-reload").addClass('hidden');
        if(this.status==200)
        {
            response=this.response;
        }
        else
        {
            response={Error : "Ocurrio un error, por favor contacte al administrador."};
        }
       	if(response.Success){
       		setTimeout(function(){location.reload()},500);
       	}
       	else if(response.Error)
       	{
       		new PNotify({
                title: 'AVI cars:',
                text: "Error inesperado, intente de nuevo",
                type: 'error'
        	});
       	}
       	$("#editPublication").modal("hide");
    }
    var data = "publicaciontext="+$("#republication").val() + "&publicacion=" + publicacion ;
    xhr.send(data);
}
function sendinfoToPrestamo(){
    monto=$("#montoPrestamoInput").val();    
    marca=$("#marcaPrestamo").val();
    submarca=$("#submarcaPrestamo").val();
    model=$("#modeloPrestamo").val();
    version=$("#versionPrestamo").val();
    nombre=$("#nombrePrestamo").val();
    telefono=$("#telefonoPrestamo").val();
    mail=$("#mailPrestamo").val();
    edad=$("#edadPrestamo").val();
    address=$("#addressPrestamo").val();
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    if($("#montoPrestamoInput").val()=="" || isNaN($("#montoPrestamoInput").val()) || $("#montoPrestamoInput").val()<10000 || $("#montoPrestamoInput").val()>300000){
        $("#montoPrestamoInput").closest(".form-group").addClass("has-error");
        $("#montoPrestamoInput").siblings("span").removeClass("hidden");
        next=false;
    }
    if(marca==""||marca.length>50){
        $("#marcaPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(submarca==""||submarca.length>50){
        $("#submarcaPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(modelo==""||isNaN(modelo)||modelo<1900||modelo>2020){
        $("#modeloPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(version==""||version.length>100){
        $("#versionPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(nombre==""){
        $("#nombrePrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(telefono=="" || isNaN(telefono)){
        $("#telefonoPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(mail==""||!emailRegex.test($("#mailPrestamo").val())){
        $("#mailPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(edad=="" || isNaN(edad) || edad<18 || edad>99){
        $("#edadPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }
    if(address==""){
        $("#addressPrestamo").closest(".form-group").addClass("has-error");
        next=false;
    }

    var next=true;
    if(next){
        $.ajax({
            url : "/php/auto/prestamo.php?c="+curCar,
            type : "POST",
            async : true,
            data : $("#formPrestamo").serialize(),
            success : function(){
                $(".modal").modal("hide");
                $("#responseTextMonetizar").text("Muchas gracias por solicitar un préstamo con BenditoCoche. En Breve un asesor se pondrá en contacto contigo.")
                $("#responseImgMonetizar").html('<a target="_blank" href="https://benditocoche.com/ventajas.html"><img src="/img/benditocoche.png"></a>')
                $("#respuestaMonetizar").modal("show");
                
            },
            error : function(){
                $(".modal").modal("hide");
                $("#responseTextMonetizar").text("No tienes permiso para realizar esta acción, contacta al dueño del auto.")
                $("#responseImgMonetizar").html('<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>')
                $("#respuestaMonetizar").modal("show");
            }
        });
        
        
    }
} 
const number2 = document.querySelector('.number2');
function formatNumber2 (n2) {
    n2 = String(n2).replace(/\D/g, "");
    return n2 === '' ? n2 : Number(n2).toLocaleString('en-US');
}
number2.addEventListener('keyup', (e2) => {
    const element2 = e2.target;
    const value2 = element2.value;
    element2.value = formatNumber2(value2);
});
function fAgrega()
{
    document.getElementById("precioModal").value = document.getElementById("n2").value.replace(/[^0-9]/g,"");
}

$(document).ready(function(){
   

	Dropzone.autoDiscover = false;
    $(".prestamo").click(function(){
        var idCar = "";
        var autoServicio = "";
        $(".wizard .wizard-steps").find("li").removeClass("active");
        $(".wizard .wizard-steps").find("li:first-child").addClass("active");
        $(".wizard .wizard-body").find("div").removeClass("active");
        $(".wizard .wizard-body").find("div:first-child").addClass("active");
        $(".wizard .next").prop("disabled",false);
        $(".wizard .previous").prop("disabled",true);
        $(".wizard .next").removeClass("finish");
        $(".wizard .next").text("Siguiente");
        $("#autoServicios").next("span.alert-danger").remove();
        if($("#autoServiciosNuevoAuto").val() != "undefined" && $("#autoServiciosNuevoAuto").val() != ""){
            var idCar = $("#autoServiciosNuevoAuto").val();
            var autoServicio = $("#autoServiciosNuevoAuto");

        }
        else{
            var idCar = $("#autoServicios").val();
            var autoServicio = $("#autoServicios");
        }
        curCar=idCar;
        if(autoServicio.val()!=0 && autoServicio.val()!=-1){
            $.ajax({
                type:"POST",
                async : true,
                data: "idCar=" + encodeURIComponent(idCar),
                url: "/php/auto/getinfoGeneral.php",
                dataType : "json",
                success : function(resp){
                    $("#marcaPrestamo").val(resp.marca);
                    $("#submarcaPrestamo").val(resp.submarca);
                    $("#modeloPrestamo").val(resp.model);
                    $("#versionPrestamo").val(resp.version);
                    $("#nombrePrestamo").val(resp.nombre+" "+resp.apellido);
                    $("#telefonoPrestamo").val(resp.telefono);
                    $("#mailPrestamo").val(resp.mail);
                    $("#edadPrestamo").val(resp.edad);
                    $("#addressPrestamo").val(resp.calle+", "+resp.municipio+", "+resp.estado+", "+resp.pais+", C.P. "+resp.cp);
                    $(".modal").modal("hide");
                    $("#modalPrestamo").modal("show");

                }
            });
        }
        else
        {
            if(autoServicio.val()==-1)
            {
                $(".modal").modal("hide");
                if($("#extraFeatures").data("open")=="0")
                {
                    $.ajax({
                        type:"POST",
                        url: "/php/auto/createNewCar.php",
                        success : function(resp){
                            $("#extraFeatures").html(resp);
                            $("#extraFeatures").data("open","1");
                        }
                    });
                }
                $("#addCar").modal("show");               
            }
            else
            {
                autoServicio.focus();
                autoServicio.after("<span class='alert-danger'>Debes seleccionar un auto para solicitar</span>");
            }
        }  
    })
    $(".flotante.noconfirm").click(function(){
        if(!$(this).hasClass("active")){
            $(".modal").modal("hide");
            $("#confirmMailModal").modal("show");
        }
        else{
            $(".modal").modal("hide");
        }  
    });
    $("#closeModalMail").click(function(){
        $(".flotante").prop("disabled",true);
        $(".flotante").removeClass("active");
        setTimeout(function(){
            $(".flotante").prop("disabled",false);
        },700);
    });
    $("#sendMailModal").click(function(){
        reenviarCorreo();
        $(".flotante").prop("disabled",true);
        $(".flotante").removeClass("active");
        setTimeout(function(){
            $(".flotante").prop("disabled",false);
        },700);
    });
	$(".flotante").click(function(){
		if(!$(this).hasClass("active"))
		{
			$(this).prop("disabled",true);
			$(this).addClass("active");
			$(".action-buttons").addClass("show");
            $(".action-buttons span").css('display', 'block');
			$(".action-buttons button").css('visibility', 'visible');
            
			setTimeout(function(){
				$(".flotante").prop("disabled",false);
			},700)
		}
		else
		{
			$(this).prop("disabled",true);
			$(this).removeClass("active");
			$(".action-buttons").removeClass("show");
			$(".action-buttons button").css('visibility', 'hidden');
			
            setTimeout(function(){
                $(".action-buttons span").css('display', 'none');
            },300)
            setTimeout(function(){
                $(".flotante").prop("disabled",false);
            },700)
		}
	});
	$("#listaMonetizar button.noaction").click(function(){
		$(".modal").modal("hide");
		$("#respuestaMonetizar").modal("show");
	})
    $("#newPublicationFloat").click(function(){
    	$("#imgDrop").hide();
    	$(".modal").modal("hide");
		$("#doPublication").modal("show");
	});
    $(".whoPublish").click(function(){
		var nombreAutor = $(this).text();
		if($("#nombreAutor").html(nombreAutor))
		{
			var urlImagenSelect = $(this).find("img").attr("src");
			$("#nombreAutor").siblings(".img-profile").attr("src", urlImagenSelect);
			$("#nombreAutor").siblings("img:last").removeClass("open");
			$('#listUsuarios').css('visibility','hidden');
			if($(this).data("garage"))
			{
				$("#nombreAutor").data("usr-publish",0)
				$("#nombreAutor").data("garage-Publish",$(this).data("garage"));
			}
			else if($(this).data("usr"))
			{
				$("#nombreAutor").data("garage-Publish",0)
				$("#nombreAutor").data("usr-publish",$(this).data("usr"));
			}
		}
	});
	$("#imgPublic").dropzone({
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        dictRemoveFile: "Eliminar",
        url: "/php/perfil/publicacion/uploadImage.php",
        success: function (file, resp) {
        	response=JSON.parse(resp);
            if(response.Success)
            {
                file.previewElement.classList.add("dz-success");
                imgs[cont]=response.Success;
                cont++;
                if(JSON.stringify(Object.keys(imgs).length)>0)
                {
                	$("#doPublication").find("button:last").attr("disabled",false);
                }
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
        },
     	removedfile: function(file) {
     		var name = file.name; 
            xhr = new XMLHttpRequest();
		    var response={};
		    var url = '/php/perfil/publicacion/dropImage.php';
		    xhr.open("POST", url, true);
		    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    xhr.responseType = "json";
		    xhr.onreadystatechange = function () { 
		    	$("#reload-band").removeClass('hidden');
    			$("#flag-reload").removeClass('hidden');
		        if(this.status==200)
		        {
		            response=this.response;
		        }
		        else
		        {
		            response={Error : "Ocurrio un error, por favor contacte al administrador."};
		        }
		       	if(response.Success){
		       		validate=true;
		       		iter=0;
		       		do
		       		{
		       			
		       			if((imgs[iter]!=undefined) && imgs[iter].toString()==response.Success)
		       			{
		       				delete imgs[iter]
		       				validate=false;
		       			}
		       			else
		       			{
		       				iter +=1;
		       			}
		       		}
		       		while(validate);
                	if(JSON.stringify(Object.keys(imgs).length)==0 && $("#publication").val()=="")
	                {
	                	$("#doPublication").find("button:last").attr("disabled",true);
	                }

		       	}   
		       	setTimeout(function () {
				        $("#reload-band").addClass('hidden');
				        $("#flag-reload").addClass('hidden');
		    	}, 2100)	
		    }
		    var data = "name="+name;
		    xhr.send(data);
            var _ref;
   			return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
		}
		
    });
    $("#publication").keyup(function(){
    	if($(this).val()!="")
    	{
    		$("#doPublication").find("button:last").attr("disabled",false);
    	}
    	else
    	{
    		$("#doPublication").find("button:last").attr("disabled",true);
    	}
	});
	$("#republication").keyup(function(){
    	if($(this).val()!="")
    	{
    		$("#editPublication").find("button:last").attr("disabled",false);
    	}
    	else
    	{
    		$("#editPublication").find("button:last").attr("disabled",true);
    	}
	});
	$(".action-button").click(function(){
		$(".flotante").prop("disabled",true);
		$(".flotante").removeClass("active");
		$(".action-buttons").removeClass("show");
		$(".action-buttons button").css('visibility', 'hidden');
        setTimeout(function(){
            $(".action-buttons span").css('display', 'none');
        },300)
        setTimeout(function(){
            $(".flotante").prop("disabled",false);
        },700)
	});
	$("#reload-band").addClass('hidden');
    $("#flag-reload").addClass('hidden');
    $(".profile-name span").click(function(){
    	e=$(this);
    	if(e.hasClass("open"))
		{
			e.removeClass("open");
			e.find(".navigation-list").css('visibility','hidden');
		}
		else
		{
			e.addClass("open");
			e.find(".navigation-list").css('visibility','visible');
		}
    })
    $(".share span").click(function(){
    	e=$(this);
    	if(e.hasClass("open"))
		{
			e.removeClass("open");
			e.find(".navigation-list").css('visibility','hidden');
		}
		else
		{
			e.addClass("open");
			e.find(".navigation-list").css('visibility','visible');
		}
    })
    $(".menu-side-bar a").click(function(){
    	e=$(this);
    	if(e.hasClass("open"))
		{
			e.removeClass("open");
			e.find(".navigation-list").css('visibility','hidden');
		}
		else
		{
			e.addClass("open");
			e.find(".navigation-list").css('visibility','visible');
		}
    })


})
function reenviarCorreo()
{
	$("#reload-band").removeClass('hidden');
	$("#flag-reload").removeClass('hidden');
	$.ajax({
		url : "/php/signup/sendMail.php",
        async : false,
        type : "POST",
        success : function(resp){
        	$("#reload-band").addClass('hidden');
	        $("#flag-reload").addClass('hidden');
        	if(resp=="1")
        	{
        		new PNotify({
                    title: 'AVI cars:',
                    text: 'Reenviamos tu correo de confirmación revisa tu bandeja de entrada.',
                    type: 'info'
                });
        	}
        	else
        	{
        		new PNotify({
                    title: 'AVI cars:',
                    text: "Error inesperado, intente de nuevo",
                    type: 'error'
            	});
        	}

        	 
        }
	});
}
function logout(){
	try 
	{
		var auth2 = gapi.auth2.getAuthInstance();
	    auth2.signOut().then(function () {
	    	window.location.href="/php/userLogout.php";
	    });
    }
    catch(err) 
    {
	    console.log(err.message);
	}
	finally
	{
		window.location.href="/php/userLogout.php";
	}
}
setTimeout(function(){
	gapi.load('auth2', function() {
        gapi.auth2.init();
    });
	
},2000);

function showGaragesToPublish(e){
	if(e.hasClass("open"))
	{
		e.removeClass("open");
		$('#listGarages').css('visibility','hidden');
	}
	else
	{
		e.addClass("open");
		$('#listGarages').css('visibility','visible');
	}
	if(!e.data("opened"))
	{

		e.data("opened", 1);
		$.ajax({
			url : "/php/perfil/publicacion/getGarageToPublish.php",
            async : false,
            type : "POST",
            success : function(resp){
            	$("#reload-band").removeClass('hidden');
    			$("#flag-reload").removeClass('hidden');
                setTimeout(function () {
				        $("#reload-band").addClass('hidden');
				        $("#flag-reload").addClass('hidden');
				        $("#listGarages").html(resp);
		    	}, 2100)
            }

		});
	}
}
function showGaragesJerarquica(e){
	if(e.hasClass("open"))
	{
		e.removeClass("open");
		$('#listGarages').css('visibility','hidden');
	}
	else
	{
		e.addClass("open");
		$('#listGarages').css('visibility','visible');
	}
	if(!e.data("opened"))
	{
		e.data("opened", 1);
		$.ajax({
			url : "/php/perfil/publicacion/getGarageJeraqui.php",
            async : false,
            type : "POST",
            data: "garage="+ $("#garage").val(),
            success : function(resp){
            	$("#reload-band").removeClass('hidden');
    			$("#flag-reload").removeClass('hidden');
                setTimeout(function () {
				        $("#reload-band").addClass('hidden');
				        $("#flag-reload").addClass('hidden');
				        $("#listGarages").html(resp);
		    	}, 2100)
            }
		});
	}
}
function listUsrs(e)
{
	if(e.hasClass("open"))
	{
		e.removeClass("open");
		$('#listUsuarios').css('visibility','hidden');
	}
	else
	{
		e.addClass("open");
		$('#listUsuarios').css('visibility','visible');
	}
}
function goComment(e){
	e.attr("onclick","");
	doComment(e);
	e.closest(".publication").find(".doCommentBtn").attr("onclick","");
}
function doComment(e){

	e.closest(".publication").find(".publication-comments").show()
	e.closest(".publication").find(".publication-comments ul").hide();
	e.closest(".publication").find(".publication-comments").append('<p class="loading-dots"><span>.</span><span>.</span><span>.</span></p>');
	var last=e.data("last");
	e.data("last",(last+10));
	var total=e.data("total");
	xhr = new XMLHttpRequest();
    var url = "/php/perfil/publicacion/comments.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            
        	$(".loading-dots").remove();
        	$(".more-comments").hide();
        	e.closest(".publication").find(".publication-comments ul").show();
        	e.closest(".publication").find(".publication-comments ul").prepend(msg);
        }
    	
    }
    xhr.send("p="+e.data("p")+"&l="+last+"&t="+total);
}
function comentarAd(e)
{
	var commentRegex = new RegExp(/^([^'"=])*$/g);
    if(!commentRegex.test($(".textComment").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Comentario no v&aacute;lido. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $(".textComment").addClass('alert-danger');
        e.siblings("textarea").focus().addClass("error");
        $(".textComment").focus();
        return false;
    }
	e.siblings("textarea").focus().removeClass("error");
	var c=e.siblings("textarea").val();
	if(c==""){
		e.siblings("textarea").focus().addClass("error");
		return false;
	}
	var p=e.data("p");
	var c=e.siblings("textarea").val();
	var t=e.siblings(".header-comment").data("t");
	var el=e.siblings(".header-comment").data("e");
	$('<li class="loading-dots"><p ><span>.</span><span>.</span><span>.</span></p></li>').insertBefore(e.closest(".publication-comments").find("ul").find(".comentor"));
	xhr = new XMLHttpRequest();
    var url = "/php/auto/comment.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            $(".loading-dots").remove();
            $(msg).insertBefore(e.closest(".publication-comments").find("ul").find(".comentor"));
            e.siblings("textarea").val("");
        }
    	
    }
    xhr.send("p="+p+"&c="+c+"&t="+t+"&el="+el);
}

function comentar(e)
{
	var commentRegex = new RegExp(/^([^'´´\\"=])*$/g);
    if(!commentRegex.test($(".textComment").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Comentario no v&aacute;lido. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $(".textComment").addClass('alert-danger');
        e.siblings("textarea").focus().addClass("error");
        $(".textComment").focus();
        return false;
    }
	e.siblings("textarea").focus().removeClass("error");
	var p=e.data("p");
	var c=e.siblings("textarea").val();
	if(c==""){
		e.siblings("textarea").focus().addClass("error");
		return false;
	}

	var t=e.siblings(".header-comment").data("t");
	var el=e.siblings(".header-comment").data("e");
	$('<li class="loading-dots"><p ><span>.</span><span>.</span><span>.</span></p></li>').insertBefore(e.closest(".publication").find(".publication-comments ul").find(".comentor"));
	xhr = new XMLHttpRequest();
    var url = "/php/perfil/publicacion/comment.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            $(".loading-dots").remove();
            $(msg).insertBefore(e.closest(".publication").find(".publication-comments ul").find(".comentor"));
            e.siblings("textarea").val("");
        }
    	
    }
    xhr.send("p="+p+"&c="+c+"&t="+t+"&el="+el);

}
function moreComentors(e){
	if(e.hasClass("open"))
	{
		e.removeClass("open");
		e.siblings('.navigation-list').css('visibility','hidden');
	}
	else{
		e.addClass("open");
		e.siblings('.navigation-list').css('visibility','visible');
	}
}
function chooseComentor(e){
	var img=e.find("img").attr("src");
	var name=e.find("span").text();
	e.parent().siblings(".header-comment").attr("src",img);
	e.parent().siblings(".header-comment").data("e",e.data("e"));
	e.parent().siblings(".header-comment").data("t",e.data("t"));
	e.parent().siblings(".commentor").text(name);
	e.parent().removeClass("open");
	e.parent().css('visibility','hidden');
}
function shareThis(e){
	if(e.hasClass("open"))
	{
		e.removeClass("open");
		e.siblings('.navigation-list').css('visibility','hidden');
		e.parents('#ulgarage').next().css('visibility','hidden');
	}
	else{
		e.addClass("open");
		e.siblings('.navigation-list').css('visibility','visible');
		e.parents('#ulgarage').next().css('visibility','visible');
	}
}
function copyShare(e,el){
	var inp =document.createElement('input');
    inp.setAttribute('class', 'temporalinput');
	document.body.appendChild(inp);
	inp.setAttribute("contenteditable","true");
	inp.value =e.dataset.target;
	//alert(inp.value);
	//inp.select();
	inp.focus();
	inp.setSelectionRange(0,99999);
	document.execCommand('copy',false);
	inp.remove();
	el.parent().siblings("p").removeClass("open");
	el.parent().css('visibility','hidden');
	new PNotify({
        title: 'AVI cars:',
        text: "Link copiado al portapapeles",
        type: 'success'
	});
}
function doShare(e,t=4){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    $("#sendShareButton").data("t",t);
    var p=e.data("p");
    $("#sendShareButton").data("p",p);
    var f=0;
    if(e.data("f")!==undefined){
        f=e.data("f");
    }
    $("#sendShareButton").data("f",f);
    xhr = new XMLHttpRequest();
    var url = "/php/share/getEnableShareds.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.responseType = "html";
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            msg=this.response;
            $("#divShareBeforeModal").html(msg);
            $("#modalBeforeShare").modal("show");
        }
        else{
            new PNotify({
                title: 'AVI cars:',
                text: "No puedes compartir este contenido.",
                type: 'error'
            });
        }
        
    }
    xhr.send("p="+encodeURIComponent(p)+"&t="+t+"&f="+f);
    $("#reload-band").addClass('hidden');
    $("#flag-reload").addClass('hidden');
}
function chooseToShare(e){
    var i=e.siblings("input");
    if(i.is(":checked")){
        i.prop("checked",false);
        e.removeClass("choosen");
    }
    else{
        i.prop("checked",true);
        e.addClass("choosen");
    }
}
function sendShare(e){
	var p=e.data("p");
    var f=e.data("f");
    var t=e.data("t");
	xhr = new XMLHttpRequest();
    var url = "/php/share/share.php";
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
	                text: "Compartido con &eacute;xito",
	                type: 'success'
	        	});
	        	setTimeout(function(){
	        		window.location.href="/post/?p="+msg.Success;
	        	},2000)
            	
            }
            else{
            	new PNotify({
	                title: 'AVI cars:',
	                text: "No puedes compartir este contenido.",
	                type: 'error'
	        	});
            }
        }
        else{
        	new PNotify({
                title: 'AVI cars:',
                text: "No puedes compartir este contenido.",
                type: 'error'
        	});
        }
    	
    }
    xhr.send("p="+encodeURIComponent(p)+"&t="+t+"&f="+f+"&"+$(".sendDataToShare").serialize());
}

function successAdvertisement(){
    $("#nuevoAnuncioModal").find("span.alert-danger").remove();
    $next=true;
    var year = new Date().getFullYear();
    var otroAno=$("#otroAnoInputmodalAd").val()*1;
    var marca=$("#marcamodalAd option:selected").text();
    var marcaVal=$("#marcamodalAd").val();
    var submarca=$("#modelomodalAd option:selected").text();
    var submarcaVal=$("#modelomodalAd").val();
    var modelo=$("#anomodalAd option:selected").text();
    var modeloVal=$("#anomodalAd").val();
    var version=$("#versionmodalAd option:selected").text();
    var versionVal=$("#versionmodalAd").val();
    var cpVal=$("#zipcodeModal").val();
    $("#otraMarcaInputmodalAd").next("span.alert-danger").remove();
    $("#otroModeloInputModalAd").next("span.alert-danger").remove();
    $("#otroAnoInputmodalAd").next("span.alert-danger").remove();
    $("#otroVersionInputmodalAd").next("span.alert-danger").remove();
    $("#precioModal").next("span.alert-danger").remove();
    $("#textAdModal").next("span.alert-danger").remove();
    send=true;
    if(marcaVal!=-1 && marcaVal!=0)
    { 
        $("#otraMarcaInputmodalAd").val(marca);
    }
    if(submarcaVal!=-1 && submarcaVal!=0)
    {

        $("#otroModeloInputModalAd").val(submarca);
    }
    if(modeloVal!=-1 && modeloVal!=0)
    {

        $("#otroAnoInputmodalAd").val(modelo);
    }
    if(versionVal!=-1 && versionVal!=0)
    {
        $("#otroVersionInputmodalAd").val(version);
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test($("#precioModal").val()) || $("#precioModal").val()==0 || $("#precioModal").val()=="")
    {
        $next=false; 
        $("#precioModal").after("<span class='alert-danger'>Precio no v&aacute;lido. &Uacute;nicamente n&uacute;meros.</span>");
        $("#check_priceModal").closest(".form-group").addClass("has-error");
        $("#precioModal").focus();
        return false;
    }
    var marcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!marcaRegex.test($("#otraMarcaInputmodalAd").val()) && $("#otraMarcaInputmodalAd").val() != "")
    {
        $next=false;
        $("#otraMarcaInputmodalAd").after("<span class='alert-danger'>Marca no v&aacute;lida.</span>");
        $(".form-group").removeClass('has-error');
        $("#check_marcamodal").addClass('has-error');
        $("#otraMarcaInputmodalAd").focus();
        return false;
    }
    var submarcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!submarcaRegex.test($("#otroModeloInputModalAd").val()) && $("#otroModeloInputModalAd").val() != "")
    {
        $next=false;
        $("#otroModeloInputModalAd").after("<span class='alert-danger'>Submarca no v&aacute;lida.</span>"); 
        $(".form-group").removeClass('has-error');
        $("#check_submarcamodal").addClass('has-error');
        $("#otroModeloInputModalAd").focus();
        return false;
    }
    if($("#otroAnoInputmodalAd").val() != "" && modeloVal==-1)
    {
        if(!Number.isInteger(otroAno)){
        send=false;
        }
        if(send && (otroAno < 1890 || otroAno > year+1 ))
        {
          send=false;
          $next=false;
        }
        if(!send){
            $("#otroAnoInputmodalAd").after("<span class='alert-danger'>A&ntilde;o no v&aacute;lido.</span>"); 
            $(".form-group").removeClass('has-error');
            $("#check_anomodal").addClass('has-error');
            $("#otroAnoInputmodalAd").focus();
            return false;
        }
        else{
            $("#otheryearmodal").removeClass('has-error');
        }
    }
    var versionRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!versionRegex.test($("#otroVersionInputmodalAd").val()) && $("#otroVersionInputmodalAd").val() != "")
    {
        $next=false;
        $("#otroVersionInputmodalAd").after("<span class='alert-danger'>Versi&oacute;n no v&aacute;lida.</span>");
        $(".form-group").removeClass('has-error');
        $("#check_versionmodal").addClass('has-error');
        $("#otroVersionInputmodalAd").focus();
        return false;
    }
    var contentRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if (!contentRegex.test($("#textAdModal").val())||$("#textAdModal").val().length>320) {
        $next=false;
        $("#textAdModal").after("<span class='alert-danger'>Texto no v&aacute;lido.</span>");
        $(".form-group").removeClass('has-error');
        $("#textAdModal").closest(".form-group").addClass("has-error");
        $("#textAdModal").focus();
        return false;
    }
    if(cpVal==""||isNaN(cpVal)||cpVal.length!=5){
        $next=false;
        $("#zipcodeModal").closest(".form-group").addClass('has-error');
        $("#zipcodeModal").focus();
        return false;
    }
    if($next)
    {
        $.ajax({
            type: 'POST',
            url: '/php/signup/zipControl.php',
            data: "code=" + cpVal,
            success: function(resp) {
                if (resp == 0) {
                    $("#zipcodeModal").closest(".form-group").addClass('has-error');
                    $("#zipcodeModal").focus();
                }
                else{
                    var add1 = resp;
                    var add1json = JSON.parse(add1);
                    $("#coloniaModal").val(add1json["city"]);
                    $("#estadoModal").val(add1json["state"]);
                    $.ajax({
                        url : "/php/auto/adCar.php",
                        type : "POST", 
                        data : $("#nuevoAnuncioModalCar").serialize()+"&"+$("#nuevoAnuncioModalAd").serialize()+"&"+$("#nuevoAnuncioModalContact").serialize(),
                        dataType : "json",
                        success : function(msg){
                            if(msg.success){
                                $(".modal").modal("hide");
                                accountNumber=msg.u;
                                $("#successAd").modal("show");
                            }
                            else{
                                $("#modalCrearAnuncio").modal("show");  
                                new PNotify({
                                    title: 'AVI cars:',
                                    text: 'Algo sali&oacute; mal. Por favor revise la informaci&oacute;n de su anuncio.',
                                    type: 'error'
                                });
                            }
                        }
                    });
                }
            }
        });
        
    } 
	  
}
function getAddress(cp){
    var valid=true;
    $.ajax({
        type: 'POST',
        url: '/php/signup/zipControl.php',
        data: "code=" + cp,
        success: function(resp) {
            if (resp == 0) {
                valid=false;
            }
            else{
                var add1 = resp;
                var add1json = JSON.parse(add1);
                $("#coloniaModal").val(add1json["city"]);
                $("#estadoModal").val(add1json["state"]);
            }
        }
    });
    return valid;
}
function goToAdvertisement(){
    if($("#autoServicios").val() == 0 || $("#autoServicios").val() == -1){
        window.location.href="/perfil/autos/detalles/editar/?cuenta="+accountNumber+"&auto="+$("#autoServiciosNuevoAuto").val();
    }
    else{
        window.location.href="/perfil/autos/detalles/editar/?cuenta="+accountNumber+"&auto="+$("#autoServicios").val();
    }
}
function goToTimeline(){
    window.location.href="/timeline";    
}
function editComment(e){
    var list=e.closest("li.comment");
    if(!list.hasClass("edit-comment")){
        list.addClass("edit-comment");
        var comentario=list.data("comment");
        var text=list.find("p").text();
        var html="<div class='editComment'>"
                    +"<span onclick='closeEditComment($(this))'>X</span>"
                    +"<textarea class='form-control textEditComment' data-current='"+text+"'>"
                        +text
                    +"</textarea>"
                    +"<button class='btn btn-avi' onclick='doEditComment($(this))' data-comment='"+comentario+"'>"
                        +"Editar"
                    +"</button>"
                +"</div>"
        list.append(html);
        list.find("p").remove();
    }
}
function editCommentAd(e){
    var list=e.closest("li.comment");
    if(!list.hasClass("edit-comment")){
        list.addClass("edit-comment");
        var comentario=list.data("comment");
        var text=list.find("p").text();
        var html="<div class='editComment'>"
                    +"<span onclick='closeEditComment($(this))'>X</span>"
                    +"<textarea class='form-control' data-current='"+text+"'>"
                        +text
                    +"</textarea>"
                    +"<button class='btn btn-avi' onclick='doEditCommentAd($(this))' data-comment='"+comentario+"'>"
                        +"Editar"
                    +"</button>"
                +"</div>"
        list.append(html);
        list.find("p").remove();
    }
}
function doEditComment(e){
    var commentRegex = new RegExp(/^([^'´´\\"=])*$/g);
    if(!commentRegex.test($(".textEditComment").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Comentario no v&aacute;lido. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $(".textEditComment").addClass('alert-danger');
        e.siblings("textarea").focus().addClass("error");
        $(".textEditComment").focus();
        return false;
    }
    e.siblings("textarea").focus().removeClass("error");
    var comment=e.data("comment");
    var text=e.siblings("textarea").val();
    var xhr=new XMLHttpRequest();
    var response={};
    var list=e.closest("li");
    var url = "/php/perfil/publicacion/editComment.php";
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
            list.removeClass("edit-comment");
            new PNotify({
                title: 'AVI cars:',
                text: 'Editado con &eacute;xito.',
                type: 'success'
            });
            e.parent().remove();
            list.append("<p>"+response.Success+"</p>");
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
    data="c="+encodeURIComponent(comment)+"&t="+encodeURIComponent(text);
    xhr.send(data);
}
function doEditCommentAd(e){
    var comment=e.data("comment");
    var text=e.siblings("textarea").val();
    var xhr=new XMLHttpRequest();
    var response={};
    var list=e.closest("li");
    var url = "/php/auto/editComment.php";
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
            list.removeClass("edit-comment");
            new PNotify({
                title: 'AVI cars:',
                text: 'Editado con &eacute;xito.',
                type: 'success'
            });
            e.parent().remove();
            list.append("<p>"+response.Success+"</p>");
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
    data="c="+encodeURIComponent(comment)+"&t="+encodeURIComponent(text);
    xhr.send(data);
}
function closeEditComment(e){
    var text=e.siblings("textarea").data("current");
    var list=e.closest("li");
    list.removeClass("edit-comment");
    e.parent().remove();
    list.append("<p>"+text+"</p>");
}
var imgModal=document.getElementById("profileImgModal");
var imgModalPublish=document.getElementById("publication-Modal");
window.onclick = function(e){
    if(e.target==imgModal){
        //alert("o");
        $("#profileImgModal").css("display", "none");
        $(".flotante").css("display", "block");
    }
    if(e.target==imgModalPublish){
        
        $("#publication-Modal").css("display", "none");
        $(".flotante").css("display", "block");
    }
}
$("#beforeBlock").click(function(){
    $("#blockuser").data("to",$(this).data("to"));
    $("#modalToblock").modal("show");
})
$("#blockuser").click(function(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var $e=$(this);
    var xhr=new XMLHttpRequest();
    var tourl = "/php/block.php";
    xhr.open("POST", tourl, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.responseType = "json";
    xhr.onreadystatechange = function () { 
        if(this.status==200)
        {
            msg=this.response;
        }
        else{
            msg={Error : "Ocurrio un error, por favor contacte al administrador."};
        }
        if(msg.Error){

            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un error, intente m&aacute;s tarde.',
                type: 'error'
            });
        }
        else{
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            window.location.reload();
        }
    }
    xhr.send("p="+encodeURIComponent($e.data("to")));
})
$("#wizardPestamo .next").click(function(){
    if($(this).hasClass("finish")){
        sendinfoToPrestamo();
        return false;
    }
    $(".form-group").removeClass("has-error");
    var next=true;
    var index=$("#wizardPestamo .wizard-steps").find("li.active a").data("target");
    switch(index){
        case "montoPrestamo":
            var monto=$("#montoPrestamoInput").val();
            var re = /,/g;
            var numero = monto.replace(re,"");
            if(numero=="" || isNaN(numero) || numero<10000 || numero>300000){
                $("#montoPrestamoInput").closest(".form-group").addClass("has-error");
                $("#montoPrestamoInput").siblings("span").removeClass("hidden");
                next=false;
            }
            break;
        case "autoPrestamo":
            marca=$("#marcaPrestamo").val();
            submarca=$("#submarcaPrestamo").val();
            modelo=$("#modeloPrestamo").val();
            version=$("#versionPrestamo").val();
             if(marca==""||marca.length>50){
                $("#marcaPrestamo").closest(".form-group").addClass("has-error");
                next=false;
            }
            if(submarca==""||submarca.length>50){
                $("#submarcaPrestamo").closest(".form-group").addClass("has-error");
                next=false;
            }
            if(modelo==""||isNaN(modelo)||modelo<1900||modelo>2020){
                $("#modeloPrestamo").closest(".form-group").addClass("has-error");
                next=false;
            }
            if(version==""||version.length>100){
                $("#versionPrestamo").closest(".form-group").addClass("has-error");
                next=false;
            }
            break;
    }
    if(next){
        $("#wizardPestamo .wizard-steps").find("li.active").removeClass("active").next().addClass("active");
        $("#wizardPestamo .wizard-body").find("div.active").removeClass("active").next().addClass("active");
        if($("#wizardPestamo .wizard-steps").find("li.active").is(":last-child")){
            $(this).addClass("finish");
            $(this).text("Enviar");
            
        }
        $("#wizardPestamo .previous").prop("disabled",false);    
    }
    
});
$("#wizardPestamo .previous").click(function(){
    $("#wizardPestamo .wizard-steps").find("li.active").removeClass("active").prev().addClass("active");
    $("#wizardPestamo .wizard-body").find("div.active").removeClass("active").prev().addClass("active");
    if($("#wizardPestamo .wizard-steps").find("li.active").is(":first-child")){
        $(this).prop("disabled",true);
    }
    $("#wizardPestamo .next").prop("disabled",false);
    
    $("#wizardPestamo .next").removeClass("finish");
    $("#wizardPestamo .next").text("Siguiente");
});

$("#wizardMovilidad .next").click(function(){
    if($(this).hasClass("finish")){
        sendInfoMovilidad();
        return false;
    }
    $(".form-group").removeClass("has-error");
    var next=true;
    var index=$("#wizardMovilidad .wizard-steps").find("li.active a").data("target");

    if(next){
        $("#wizardMovilidad .wizard-steps").find("li.active").removeClass("active").next().addClass("active");
        $("#wizardMovilidad .wizard-body").find("div.active").removeClass("active").next().addClass("active");
        if($("#wizardMovilidad .wizard-steps").find("li.active").is(":last-child")){
            $(this).addClass("finish");
            $(this).text("Enviar");
        }
        $("#wizardMovilidad .previous").prop("disabled",false);    
    }
    
});
$("#wizardMovilidad .previous").click(function(){
    $("#wizardMovilidad .wizard-steps").find("li.active").removeClass("active").prev().addClass("active");
    $("#wizardMovilidad .wizard-body").find("div.active").removeClass("active").prev().addClass("active");
    if($("#wizardMovilidad .wizard-steps").find("li.active").is(":first-child")){
        $(this).prop("disabled",true);
    }
    $("#wizardMovilidad .next").prop("disabled",false);
    $("#wizardMovilidad .next").removeClass("finish");
    $("#wizardMovilidad .next").text("Siguiente");
});

$("#wizardCompra .next").click(function(){
    if($(this).hasClass("finish")){
        sendInfoCompra();
        return false;
    }
    $(".form-group").removeClass("has-error");
    var next=true;
    var index=$("#wizardCompra .wizard-steps").find("li.active a").data("target");
    switch(index){
        case "autoCompra":
            var marca=$("#marcamodalCompra").val();
            var otraMarca=$("#otraMarcaInputmodalCompra").val();
            var modelo=$("#modelomodalCompra").val();
            var otroModelo=$("#otroModeloInputmodalCompra").val();
            var ano=$("#anomodalCompra").val();
            var otroAno=$("#otroAnoInputmodalCompra").val();
            var version=$("#versionmodalCompra").val();
            var otraVersion=$("#otroVersionInputmodalCompra").val();
            if(marca==0||(marca==-1&&otraMarca=="")){
                next=false;
                $("#marcamodalCompra").closest(".form-group").addClass("has-error");
            }
            if(modelo==0||(modelo==-1&&otroModelo=="")){
                next=false;
                $("#modelomodalCompra").closest(".form-group").addClass("has-error");
            }
            if(ano==0||(ano==-1&&otroAno=="")){
                next=false;
                $("#anomodalCompra").closest(".form-group").addClass("has-error");
            }
            if(version==0||(version==-1&&otraVersion=="")){
                next=false;
                $("#versionmodalCompra").closest(".form-group").addClass("has-error");
            }
            break;
        case "revisionCompra":
            var revision=$("#revisionSelectCompra").val();
            if(revision===""){
                next=false;
                $("#revisionSelectCompra").closest(".form-group").addClass("has-error");
            }
            
            break;
        default:
            break;
    }
    if(next){
        $("#wizardCompra .wizard-steps").find("li.active").removeClass("active").next().addClass("active");
        $("#wizardCompra .wizard-body").find("div.active").removeClass("active").next().addClass("active");
        if($("#wizardCompra .wizard-steps").find("li.active").is(":last-child")){
            $(this).addClass("finish");
            $(this).text("Enviar");
        }
        $("#wizardCompra .previous").prop("disabled",false);    
    }
    
});
$("#wizardCompra .previous").click(function(){
    $("#wizardCompra .wizard-steps").find("li.active").removeClass("active").prev().addClass("active");
    $("#wizardCompra .wizard-body").find("div.active").removeClass("active").prev().addClass("active");
    if($("#wizardCompra .wizard-steps").find("li.active").is(":first-child")){
        $(this).prop("disabled",true);
    }
    $("#wizardCompra .next").prop("disabled",false);
    $("#wizardCompra .next").removeClass("finish");
    $("#wizardCompra .next").text("Siguiente");
});

$("#wizardSeguros .next").click(function(){
    if($(this).hasClass("finish")){
        sendInfoSeguros();
        return false;
    }
    $(".form-group").removeClass("has-error");
    var next=true;
    var index=$("#wizardSeguros .wizard-steps").find("li.active a").data("target");
    switch(index){
        case "autoSeguros":
            var marca=$("#marcamodalIns").val();
            var otraMarca=$("#otraMarcaInputmodalIns").val();
            var modelo=$("#modelomodalIns").val();
            var otroModelo=$("#otroModeloInputmodalIns").val();
            var ano=$("#anomodalIns").val();
            var otroAno=$("#otroAnoInputmodalIns").val();
            var version=$("#versionmodalIns").val();
            var otraVersion=$("#otroVersionInputmodalIns").val();
            if(marca==0||(marca==-1&&otraMarca=="")){
                next=false;
                $("#marcamodalIns").closest(".form-group").addClass("has-error");
            }
            if(modelo==0||(modelo==-1&&otroModelo=="")){
                next=false;
                $("#modelomodalIns").closest(".form-group").addClass("has-error");
            }
            if(ano==0||(ano==-1&&otroAno=="")){
                next=false;
                $("#anomodalIns").closest(".form-group").addClass("has-error");
            }
            if(version==0||(version==-1&&otraVersion=="")){
                next=false;
                $("#versionmodalIns").closest(".form-group").addClass("has-error");
            }
            break;
        default:
            break;
    }
    if(next){
        $("#wizardSeguros .wizard-steps").find("li.active").removeClass("active").next().addClass("active");
        $("#wizardSeguros .wizard-body").find("div.active").removeClass("active").next().addClass("active");
        if($("#wizardSeguros .wizard-steps").find("li.active").is(":last-child")){
            $(this).addClass("finish");
            $(this).text("Enviar");
        }
        $("#wizardSeguros .previous").prop("disabled",false);    
    }
    
});
$("#wizardSeguros .previous").click(function(){
    $("#wizardSeguros .wizard-steps").find("li.active").removeClass("active").prev().addClass("active");
    $("#wizardSeguros .wizard-body").find("div.active").removeClass("active").prev().addClass("active");
    if($("#wizardSeguros .wizard-steps").find("li.active").is(":first-child")){
        $(this).prop("disabled",true);
    }
    $("#wizardSeguros .next").prop("disabled",false);
    $("#wizardSeguros .next").removeClass("finish");
    $("#wizardSeguros .next").text("Siguiente");
});

$("#wizardAdvertisement .next").click(function(){
    if($(this).hasClass("finish")){
        successAdvertisement();
        return false;
    }
    $(".form-group").removeClass("has-error");
    var next=true;
    var index=$("#wizardAdvertisement .wizard-steps").find("li.active a").data("target");
    switch(index){
        case "autoAdvertisement":
            break;
        case "adAdvertisement":
            //var monto=$("#precioModal").val();
            //var re = /,/g;
            //var numero2 = monto.replace(re,"");
            $("#precioModal").siblings("span.alert-danger").addClass("hidden");
            if($("#precioModal").val()=="" || isNaN($("#precioModal").val())){
                $("#precioModal").closest(".form-group").addClass("has-error");
                $("#precioModal").siblings("span.alert-danger").removeClass("hidden");
                next=false;
            }
            var contentRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
            if (!contentRegex.test($("#textAdModal").val())||$("#textAdModal").val().length>320) {
                $next=false;
                $("#textAdModal").after("<span class='alert-danger'>Texto no v&aacute;lido.</span>");
                $(".form-group").removeClass('has-error');
                $("#textAdModal").closest(".form-group").addClass("has-error");
                $("#textAdModal").focus();
                return false;
            }
            break;
    }
    if(next){
        $("#wizardAdvertisement .wizard-steps").find("li.active").removeClass("active").next().addClass("active");
        $("#wizardAdvertisement .wizard-body").find("div.active").removeClass("active").next().addClass("active");
        if($("#wizardAdvertisement .wizard-steps").find("li.active").is(":last-child")){
            $(this).addClass("finish");
            $(this).text("Enviar");
        }
        $("#wizardAdvertisement .previous").prop("disabled",false);    
    }
    
});
$("#wizardAdvertisement .previous").click(function(){
    $("#wizardAdvertisement .wizard-steps").find("li.active").removeClass("active").prev().addClass("active");
    $("#wizardAdvertisement .wizard-body").find("div.active").removeClass("active").prev().addClass("active");
    if($("#wizardAdvertisement .wizard-steps").find("li.active").is(":first-child")){
        $(this).prop("disabled",true);
    }
    $("#wizardAdvertisement .next").prop("disabled",false);
    
    $("#wizardAdvertisement .next").removeClass("finish");
    $("#wizardAdvertisement .next").text("Siguiente");
});

$('#privacidad').on('shown.bs.modal', function () {
  $(this).find("input[type=radio][data-actual=1]").prop("checked",true);
})
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function minPrice(){
    $("#precioModal").siblings("span.alert-danger").addClass("hidden");
    $("#precioModal").siblings("span.alert-info").addClass("hidden");
    $("#precioModal").closest(".form-group").removeClass("has-error");
    if($("#precioModal").val()>0 && $("#precioModal").val()<1000 && $("#moneda option:selected").text()=="MXN")
    {
        var newPrice = $("#precioModal").val() * 100;
        $("#precioModal").closest(".form-group").addClass("has-error");
        $("#precioModal").siblings("span.alert-info").text("Precio muy bajo. ¿No quiso decir "+numberWithCommas(newPrice)+" MXN?");
        $("#precioModal").siblings("span.alert-info").removeClass("hidden");
    }
    else if($("#precioModal").val()>=1000 && $("#precioModal").val()<10000 && $("#moneda option:selected").text()=="MXN")
    {
        var newPrice2 = $("#precioModal").val() * 10;
        $("#precioModal").closest(".form-group").addClass("has-error");
        $("#precioModal").siblings("span.alert-info").text("Precio muy bajo. ¿No quiso decir "+numberWithCommas(newPrice2)+" MXN?");
        $("#precioModal").siblings("span.alert-info").removeClass("hidden");
    }
    else if ($("#precioModal").val()>0 && $("#precioModal").val()<100 && $("#moneda option:selected").text()!="MXN") 
    {
        var newPriceUS = $("#precioModal").val() * 100;
        $("#precioModal").closest(".form-group").addClass("has-error");
        $("#precioModal").siblings("span.alert-info").text("Precio muy bajo. ¿No quiso decir "+numberWithCommas(newPriceUS)+" USD?");
        $("#precioModal").siblings("span.alert-info").removeClass("hidden");
    }
}
function minPriceEdit(){
    $("#precio").siblings("span.alert-danger").addClass("hidden");
    $("#precio").siblings("span.alert-info").addClass("hidden");
    $("#precio").closest(".form-group").removeClass("has-error");
    if($("#precio").val()>0 && $("#precio").val()<1000 && $("#moneda_e option:selected").text()=="MXN")
    {
        var newPrice = $("#precio").val() * 100;
        $("#precio").closest(".form-group").addClass("has-error");
        $("#precio").siblings("span.alert-info").text("Precio muy bajo. ¿No quiso decir "+numberWithCommas(newPrice)+" MXN?");
        $("#precio").siblings("span.alert-info").removeClass("hidden");
    }
    else if($("#precio").val()>=1000 && $("#precio").val()<10000 && $("#moneda option:selected").text()=="MXN")
    {
        var newPrice2 = $("#precio").val() * 10;
        $("#precio").closest(".form-group").addClass("has-error");
        $("#precio").siblings("span.alert-info").text("Precio muy bajo. ¿No quiso decir "+numberWithCommas(newPrice2)+" MXN?");
        $("#precio").siblings("span.alert-info").removeClass("hidden");
    }
    else if ($("#precio").val()>0 && $("#precio").val()<100 && $("#moneda_e option:selected").text()!="MXN") 
    {
        var newPriceUS = $("#precio").val() * 100;
        $("#precio").closest(".form-group").addClass("has-error");
        $("#precio").siblings("span.alert-info").text("Precio muy bajo. ¿No quiso decir "+numberWithCommas(newPriceUS)+" USD?");
        $("#precio").siblings("span.alert-info").removeClass("hidden");
    }
}
function abrir(){

  if(!$("#privacidad").find("input[type=radio][data-actual=1]").is(":checked")){
    $("#Modal_Confirm_Privacidad").modal("show");
  }
  $("#privacidad").modal("hide");
}
function sendInfoMovilidad(){
    var next=true;
    var marca=$("#marcamodalMov").val();
    var otraMarca=$("#otraMarcaInputmodalMov").val();
    var modelo=$("#modelomodalMov").val();
    var otroModelo=$("#otroModeloInputmodalMov").val();
    var ano=$("#anomodalMov").val();
    var otroAno=$("#otroAnoInputmodalMov").val();
    var version=$("#versionmodalMov").val();
    var otraVersion=$("#otroVersionInputmodalMov").val();
    var nombre=$("#nombreModalMov").val();
    var apellido=$("#apellidoModalMov").val();
    var edad=$("#edadModalMov").val();
    var cp=$("#cpModalMov").val();
    var telefono=$("#telefonoModalMov").val();
    var mail=$("#emailModalMov").val();
    if(marca==0||(marca==-1&&otraMarca=="")){
        next=false;
        $("#marcamodalMov").closest(".form-group").addClass("has-error");
    }
    if(modelo==0||(modelo==-1&&otroModelo=="")){
        next=false;
        $("#modelomodalMov").closest(".form-group").addClass("has-error");
    }
    if(ano==0||(ano==-1&&otroAno=="")){
        next=false;
        $("#anomodalMov").closest(".form-group").addClass("has-error");
    }
    if(version==0||(version==-1&&otraVersion=="")){
        next=false;
        $("#versionmodalMov").closest(".form-group").addClass("has-error");
    }
    if(nombre==""){
        next=false;
        $("#nombreModalMov").closest(".form-group").addClass("has-error");
    }
    if(apellido==""){
        next=false;
        $("#apellidoModalMov").closest(".form-group").addClass("has-error");
    }
    if(edad==""||edad<18||edad>99){
        next=false;
        $("#edadModalMov").closest(".form-group").addClass("has-error");
    }
    if(cp==""||cp.length!=5){
        next=false;
        $("#cpModalMov").closest(".form-group").addClass("has-error");
    }
    if(telefono==""||telefono.length!=10){
        next=false;
        $("#telefonoModalMov").closest(".form-group").addClass("has-error");
    }
    if(mail==""){
        next=false;
        $("#emailModalMov").closest(".form-group").addClass("has-error");
    }
    if(next){
        $.ajax({
            url : "/php/auto/movilidad.php?c="+curCar,
            type : "POST",
            async : true,
            data : $("#formMovilidad").serialize(),
            success : function(){
                $(".modal").modal("hide");
                $("#responseTextMonetizar").text("Muchas gracias por solicitar el servicio de Movilidad con nosotros. En breve uno de nuestros asesores se pondrá en contacto contigo.")
                $("#responseImgMonetizar").html('<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>')
                $("#respuestaMonetizar").modal("show");
                
            },
            error : function(){
                $(".modal").modal("hide");
                $("#responseTextMonetizar").text("No tienes permiso para realizar esta acción, contacta al dueño del auto.")
                $("#responseImgMonetizar").html('<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>')
                $("#respuestaMonetizar").modal("show");
            }
        });
        
    }
}
function sendInfoSeguros(){
    var next=true;
    var marca=$("#marcamodalIns").val();
    var otraMarca=$("#otraMarcaInputmodalIns").val();
    var modelo=$("#modelomodalIns").val();
    var otroModelo=$("#otroModeloInputmodalIns").val();
    var ano=$("#anomodalIns").val();
    var otroAno=$("#otroAnoInputmodalIns").val();
    var version=$("#versionmodalIns").val();
    var otraVersion=$("#otroVersionInputmodalIns").val();
    var nombre=$("#nombreModalIns").val();
    var apellido=$("#apellidoModalIns").val();
    var edad=$("#edadModalIns").val();
    var cp=$("#cpModalIns").val();
    var telefono=$("#telefonoModalIns").val();
    var mail=$("#emailModalIns").val();
    if(marca==0||(marca==-1&&otraMarca=="")){
        next=false;
        $("#marcamodalIns").closest(".form-group").addClass("has-error");
    }
    if(modelo==0||(modelo==-1&&otroModelo=="")){
        next=false;
        $("#modelomodalIns").closest(".form-group").addClass("has-error");
    }
    if(ano==0||(ano==-1&&otroAno=="")){
        next=false;
        $("#anomodalIns").closest(".form-group").addClass("has-error");
    }
    if(version==0||(version==-1&&otraVersion=="")){
        next=false;
        $("#versionmodalIns").closest(".form-group").addClass("has-error");
    }
    if(nombre==""){
        next=false;
        $("#nombreModalIns").closest(".form-group").addClass("has-error");
    }
    if(apellido==""){
        next=false;
        $("#apellidoModalIns").closest(".form-group").addClass("has-error");
    }
    if(edad==""||edad<18||edad>99){
        next=false;
        $("#edadModalIns").closest(".form-group").addClass("has-error");
    }
    if(cp==""||cp.length!=5){
        next=false;
        $("#cpModalIns").closest(".form-group").addClass("has-error");
    }
    if(telefono==""||telefono.length!=10){
        next=false;
        $("#telefonoModalIns").closest(".form-group").addClass("has-error");
    }
    if(mail==""){
        next=false;
        $("#emailModalIns").closest(".form-group").addClass("has-error");
    }
    if(next){
        $.ajax({
            url : "/php/auto/seguros.php?c="+curCar,
            type : "POST",
            async : true,
            data : $("#formSeguros").serialize(),
            success : function(){
                $(".modal").modal("hide");
                $("#responseTextMonetizar").text("Muchas gracias por cotizar tu seguro con nosotros. En breve uno de nuestros asesores se pondrá en contacto contigo.")
                $("#responseImgMonetizar").html('<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>')
                $("#respuestaMonetizar").modal("show");
                
            },
            error : function(){
                $(".modal").modal("hide");
                $("#responseTextMonetizar").text("No tienes permiso para realizar esta acción, contacta al dueño del auto.")
                $("#responseImgMonetizar").html('<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>')
                $("#respuestaMonetizar").modal("show");
            }
        });
        
    } 
}
function sendInfoCompra(){
    var next=true;
    var marca=$("#marcamodalCompra").val();
    var otraMarca=$("#otraMarcaInputmodalCompra").val();
    var modelo=$("#modelomodalCompra").val();
    var otroModelo=$("#otroModeloInputmodalCompra").val();
    var ano=$("#anomodalCompra").val();
    var otroAno=$("#otroAnoInputmodalCompra").val();
    var version=$("#versionmodalCompra").val();
    var otraVersion=$("#otroVersionInputmodalCompra").val();
    var nombre=$("#nombreModalCompra").val();
    var apellido=$("#apellidoModalCompra").val();
    var edad=$("#edadModalCompra").val();
    var cp=$("#cpModalCompra").val();
    var telefono=$("#telefonoModalCompra").val();
    var mail=$("#emailModalCompra").val();
    var revision=$("#revisionSelectCompra").val();
    if(marca==0||(marca==-1&&otraMarca=="")){
        next=false;
        $("#marcamodalCompra").closest(".form-group").addClass("has-error");
    }
    if(modelo==0||(modelo==-1&&otroModelo=="")){
        next=false;
        $("#modelomodalCompra").closest(".form-group").addClass("has-error");
    }
    if(ano==0||(ano==-1&&otroAno=="")){
        next=false;
        $("#anomodalCompra").closest(".form-group").addClass("has-error");
    }
    if(version==0||(version==-1&&otraVersion=="")){
        next=false;
        $("#versionmodalCompra").closest(".form-group").addClass("has-error");
    }
    if(revision===""){
        next=false;
        $("#revisionSelectCompra").closest(".form-group").addClass("has-error");
    }
    if(nombre==""){
        next=false;
        $("#nombreModalCompra").closest(".form-group").addClass("has-error");
    }
    if(apellido==""){
        next=false;
        $("#apellidoModalCompra").closest(".form-group").addClass("has-error");
    }
    if(edad==""||edad<18||edad>99){
        next=false;
        $("#edadModalCompra").closest(".form-group").addClass("has-error");
    }
    if(cp==""||cp.length!=5){
        next=false;
        $("#cpModalCompra").closest(".form-group").addClass("has-error");
    }
    if(telefono==""||telefono.length!=10){
        next=false;
        $("#telefonoModalCompra").closest(".form-group").addClass("has-error");
    }
    if(mail==""){
        next=false;
        $("#emailModalCompra").closest(".form-group").addClass("has-error");
    }
    if(next){
        $.ajax({
            url : "/php/auto/compra.php?c="+curCar,
            type : "POST",
            async : true,
            data : $("#formCompra").serialize(),
            success : function(){
                $(".modal").modal("hide");
                $("#responseImgMonetizar").html('<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>')
                $("#responseTextMonetizar").text("Muchas gracias por utilizar la herramienta de b&uacute;squeda de comprador. En breve nos pondremos en contacto contigo.")
                $("#respuestaMonetizar").modal("show");
                
            },
            error : function(){
                $(".modal").modal("hide");
                $("#responseTextMonetizar").text("No tienes permiso para realizar esta acción, contacta al dueño del auto.")
                $("#responseImgMonetizar").html('<a target="_blank" href="http://infotraffic.com.mx"><img src="/img/Home_Movil_logo_headline_sized_infotraffic_.png"></a>')
                $("#respuestaMonetizar").modal("show");
            }
        });
        
    } 
}
function enviarDatosModalPerfil(a,usrUnfollow,type,name,lastname){
    $("#private_data1").val(a.data("elemento"));
    $("#private_data2").val(usrUnfollow);
    $("#private_data3").val(type);
    nombre_completo = name+" "+lastname;
    $("#private_profile").html("<h6>"+nombre_completo+"</h6>");

    $("#public_data1").val(a.data("elemento"));
    $("#public_data2").val(usrUnfollow);
    $("#public_data3").val(type);
    nombre_completo = name+" "+lastname;
    $("#public_profile").html("<h6>"+nombre_completo+"</h6>");

    $("#secret_data1").val(a.data("elemento"));
    $("#secret_data2").val(usrUnfollow);
    $("#secret_data3").val(type);
    nombre_completo = name+" "+lastname;
    $("#secret_profile").html("<h6>"+nombre_completo+"</h6>");
}

function enviarDatosModalPerfilSeguidores(f,usrUnfollowf,typef,namef,lastnamef){
    $("#private_data1_followers").val(f.data("elemento"));
    $("#private_data2_followers").val(usrUnfollowf);
    $("#private_data3_followers").val(typef);
    nombre_completof = namef+" "+lastnamef;
    $("#private_profile_followers").html("<h6>"+nombre_completof+"</h6>");

    $("#public_data1_followers").val(f.data("elemento"));
    $("#public_data2_followers").val(usrUnfollowf);
    $("#public_data3_followers").val(typef);
    nombre_completof = namef+" "+lastnamef;
    $("#public_profile_followers").html("<h6>"+nombre_completof+"</h6>");

    $("#secret_data1_followers").val(f.data("elemento"));
    $("#secret_data2_followers").val(usrUnfollowf);
    $("#secret_data3_followers").val(typef);
    nombre_completof = namef+" "+lastnamef;
    $("#secret_profile_followers").html("<h6>"+nombre_completof+"</h6>");
}

function enviarDatosModalGarage(a,gUnfollow,typeg,nameg){
    $("#private_garage1").val(a.data("element"));
    $("#private_garage2").val(gUnfollow);
    $("#private_garage3").val(typeg);
    nombre_garage = nameg;
    $("#private_garage").html("<h6>"+nombre_garage+"</h6>");

    $("#public_garage1").val(a.data("element"));
    $("#public_garage2").val(gUnfollow);
    $("#public_garage3").val(typeg);
    nombre_garage = nameg;
    $("#public_garage").html("<h6>"+nombre_garage+"</h6>");

    $("#secret_garage1").val(a.data("element"));
    $("#secret_garage2").val(gUnfollow);
    $("#secret_garage3").val(typeg);
    nombre_garage = nameg;
    $("#secret_garage").html("<h6>"+nombre_garage+"</h6>");
}
function enviarDatosModalAuto(b,aUnfollow,typea,namea,owner){
    $("#private_car1").val(b.data("elemen"));
    $("#private_car2").val(aUnfollow);
    $("#private_car3").val(typea);
    $("#owner").val(owner);
    nombre_auto = namea;
    $("#private_car").html("<h6>"+nombre_auto+"</h6>");

    $("#public_car1").val(b.data("elemen"));
    $("#public_car2").val(aUnfollow);
    $("#public_car3").val(typea);
    $("#owner2").val(owner);
    nombre_auto = namea;
    $("#public_car").html("<h6>"+nombre_auto+"</h6>");

    $("#secret_car1").val(b.data("elemen"));
    $("#secret_car2").val(aUnfollow);
    $("#secret_car3").val(typea);
    $("#owner3").val(owner);
    nombre_auto = namea;
    $("#secret_car").html("<h6>"+nombre_auto+"</h6>");
}
    
function addimgtempcarAd()
{
        
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
        
    var data =  new FormData($("#photoCarModalAd")[0]);
    var fileInput=$("#photoCarModalAd input[type=file]");
    var maxSize=20000000;
    if(fileInput.get(0).files.length){
        var fileSize = fileInput.get(0).files[0].size;
    }
    if(fileSize>maxSize){
        
        new PNotify({
            title: 'AVI cars:',
            text: 'El archivo es demasiado grande, solo puedes subir im&aacute;genes de 20 MB o menos.',
            type: 'error'
        });
        $("#reload-band").addClass('hidden');
        $("#flag-reload").addClass('hidden');
        return false;
    }
    setTimeout(function() {
        $.ajax({
            url : "/php/catalogoAutos/addingImg.php",
            type : "POST",
            data : data,
            async:false,
            cache: false,
            contentType: false,
            processData: false,
            dataType : "json",
            success : function(resp){
                if (resp.Error) {
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'Sube un archivo válido: jpg, png o jpeg.',
                        type: 'error'
                    });
                    $("#photoCarModalAd input[type=file]").val("");
                }
                else
                {
                    $htmlDiv = '<div class="inline-logos-ad col-xs-6 newImgEdit" style="background-image: url('+resp.img+')">'+
                                '<div class="font-trash">'+
                                '<a class=" icon-trash icon-trash-car" data-img="'+resp.imgid+'" data-vehicle="'+resp.idcar+'" onclick="showModalTrashAd($(this))" title="Borrar"></a>'+
                                '</div>'+
                                '</div>';
                    $($htmlDiv).insertBefore("#photoCarModalAd");
                    $(document).ready(function() {   
                        setTimeout(function() {
                            $("#reload-band").addClass('hidden');
                            $("#flag-reload").addClass('hidden');
                            $("#photoCarModalAd input[type=file]").val("");
                        },500);
                    });
                }
            }
       });
    },1000);
}
function showModalTrashAd(e)
{
    $("#modalDeleteImgCarAd").find("button:last").data("img", e.data("img"));
    $("#modalDeleteImgCarAd").find("button:last").data("vehicle", e.data("vehicle"));
    $("#modalDeleteImgCarAd").modal("show");
}
function hideimgCarAd(e)
{
    $("#modalDeleteImgCarAd").modal("hide");
}
function deletedImgTmpCarAd(e){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var idCar = e.data("vehicle");
    var idImg = e.data("img");
    if (typeof (idCar)!="undefined") {
        var data = "idImg=" + idImg + "&idCar=" + idCar;
    }
    else
    {
        var data = "idImg=" + idImg;
    }
    $.ajax({
        url : "/php/auto/deleteImgTmp.php",
        type : "POST",
        data : data,
        success : function(resp){
            if (resp=="success")
            {
                $("#editingPhotosCarModalAd").find("a[data-img='"+idImg+"']").parents(".inline-logos-ad").remove();
                $("#modalDeleteImgCarAd").modal("hide");
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
            }
            else
            {
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema en la conexi&oacute;n, por favor recargue la p&aacute;gina.',
                    type: 'error'
                });
            }
        }
    });
}
function deletedAllTmpCarAd(){
    var idCar = currCar;
    if (typeof (idCar)!="undefined") {
        var data = "idCar=" + idCar;
        $.ajax({
            url : "/php/auto/deleteImgTmp.php",
            type : "POST",
            data : data,
            success : function(resp){
                if (resp=="success")
                {
                    $.each($("#editingPhotosCarModalAd").find("img"), function(i, element){
                        if($(this).next().data("img")==idImg)
                        {
                            $(this).parents(".inline-logos-ad").remove();
                        }
                        if(imgsNewCar[i]=idImg)
                        {
                            delete imgsNewCar[i];
                        }
                    });
                    $("#modalDeleteImgCarAd").modal("hide");
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                }
                else
                {
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'Hubo un problema en la conexi&oacute;n, por favor recargue la p&aacute;gina.',
                        type: 'error'
                    });
                }
            }
        });
    }
    else{
        return true;
    }
}


function changeMarcaAd(e){
    var marcamodal=e.val();
    var modelo=0;
    var ano=$("#anomodalAd").val();
    var version=$("#versionmodalAd").val();
    $("#anomodalAd").val(0);
    $("#versionmodalAd").val(0);
    $.ajax({
        url : "/php/catalogoAutos/getModels.php/",
        async : false,
        type : "POST",
        data: "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#modelomodalAd").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/getYears.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#anomodalAd").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/knowVersion.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
        success : function(resp){
            $("#versionmodalAd").html(resp);
        }
    })
    if(marcamodal!=-1)
    {
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalAd").addClass("hidden");
        $("#othervermodalAd").addClass("hidden");
    }
    if(e.val()=="-1")
    {
        $(".otraMarca").removeClass("hidden");
        $("#modelomodalAd").val("-1");
        $("#anomodalAd").val("0");
        $("#versionmodalAd").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalAd").addClass("hidden");
        $("#othervermodalAd").removeClass("hidden");
    }
    if(e.val()=="0")
    {
        $("#anomodalAd").val("0");
        $("#versionmodalAd").val("0");
        $("#modelomodalAd").val("0");
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalAd").addClass("hidden");
        $("#othervermodalAd").addClass("hidden");
    }
    $("#modelomodalAd").find("option").each(function(){

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

function changeSubmarcaAd(e){
    var modelo=e.val();
    if(modelo==-1||modelo==0)
    {
        marcamodal=$("#marcamodalAd").val();
    }
    else
    {
        var marcamodal=e.find("option:selected").data("marca");
    }
    var ano=$("#anomodalAd").val();
    var version=$("#versionmodalAd").val();
    $("#marcamodalAd").val(marcamodal);
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
        $("#otheryearmodalAd").addClass("hidden");
        $("#othervermodalAd").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#anomodalAd").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/knowVersion.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
            success : function(resp){
                $("#versionmodalAd").html(resp);
            }
        })
    }
    if(modelo=="-1")
    {
        $("#anomodalAd").val("0");
        $("#versionmodalAd").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalAd").addClass("hidden");
        $("#othervermodalAd").removeClass("hidden");
    }
    if(modelo=="0")
    {
        $("#anomodalAd").val("0");
        $("#versionmodalAd").val("0");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalAd").addClass("hidden");
        $("#othervermodalAd").addClass("hidden");
    }
}

function changeAnoAd(e){
    var ano=e.val();
    var modelo=$("#modelomodalAd").val();
    var marcamodal=$("#marcamodalAd").val();
    if(ano!=0 && ano!=-1)
        $("#othervermodalAd").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getModels.php",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                if(modelo==="0")
                {
                    $("#modelomodalAd").html(resp);
                }
                else
                {
                    $("#versionmodalAd").html(resp);
                }
            }
        });
    
    if(ano=="-1")
    {
        $("#versionmodalAd").val("-1");
        $("#otheryearmodalAd").removeClass("hidden");
        $("#othervermodalAd").removeClass("hidden");
    }
    if(ano=="0")
    {
        $("#versionmodalAd").val(0);
        $("#otheryearmodalAd").addClass("hidden");
        $("#othervermodalAd").addClass("hidden");
    }
}
function changeVersionAd(e){
    var version=e.val();
    $("#otroVersionInputmodalAd").val($("#versionmodalAd option:selected").text());
    if (version == -1) {
      $("#othervermodalAd").removeClass("hidden");
    }
    else{
      $("#othervermodalAd").addClass("hidden");
    }
}

function changeMarcaMov(e){
    var marcamodal=e.val();
    var modelo=0;
    var ano=$("#anomodalMov").val();
    var version=$("#versionmodalMov").val();
    $("#anomodalMov").val(0);
    $("#versionmodalMov").val(0);
    $.ajax({
        url : "/php/catalogoAutos/getModels.php/",
        async : false,
        type : "POST",
        data: "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#modelomodalMov").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/getYears.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#anomodalMov").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/knowVersion.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
        success : function(resp){
            $("#versionmodalMov").html(resp);
        }
    })
    if(marcamodal!=-1)
    {
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalMov").addClass("hidden");
        $("#othervermodalMov").addClass("hidden");
    }
    if(e.val()=="-1")
    {
        $(".otraMarca").removeClass("hidden");
        $("#modelomodalMov").val("-1");
        $("#anomodalMov").val("0");
        $("#versionmodalMov").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalMov").addClass("hidden");
        $("#othervermodalMov").removeClass("hidden");
    }
    if(e.val()=="0")
    {
        $("#anomodalMov").val("0");
        $("#versionmodalMov").val("0");
        $("#modelomodalMov").val("0");
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalMov").addClass("hidden");
        $("#othervermodalMov").addClass("hidden");
    }
    $("#modelomodalMov").find("option").each(function(){

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

function changeSubmarcaMov(e){
    var modelo=e.val();
    if(modelo==-1||modelo==0)
    {
        marcamodal=$("#marcamodalMov").val();
    }
    else
    {
        var marcamodal=e.find("option:selected").data("marca");
    }
    var ano=$("#anomodalMov").val();
    var version=$("#versionmodalMov").val();
    $("#marcamodalMov").val(marcamodal);
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
        $("#otheryearmodalMov").addClass("hidden");
        $("#othervermodalMov").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#anomodalMov").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/knowVersion.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
            success : function(resp){
                $("#versionmodalMov").html(resp);
            }
        })
    }
    if(modelo=="-1")
    {
        $("#anomodalMov").val("0");
        $("#versionmodalMov").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalMov").addClass("hidden");
        $("#othervermodalMov").removeClass("hidden");
    }
    if(modelo=="0")
    {
        $("#anomodalMov").val("0");
        $("#versionmodalMov").val("0");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalMov").addClass("hidden");
        $("#othervermodalMov").addClass("hidden");
    }
}

function changeAnoMov(e){
    var ano=e.val();
    var modelo=$("#modelomodalMov").val();
    var marcamodal=$("#marcamodalMov").val();
    if(ano!=0 && ano!=-1)
        $("#othervermodalMov").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getModels.php",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                if(modelo==="0")
                {
                    $("#modelomodalMov").html(resp);
                }
                else
                {
                    $("#versionmodalMov").html(resp);
                }
            }
        });
    
    if(ano=="-1")
    {
        $("#versionmodalMov").val("-1");
        $("#otheryearmodalMov").removeClass("hidden");
        $("#othervermodalMov").removeClass("hidden");
    }
    if(ano=="0")
    {
        $("#versionmodalMov").val(0);
        $("#otheryearmodalMov").addClass("hidden");
        $("#othervermodalMov").addClass("hidden");
    }
}
function changeVersionMov(e){
    var version=e.val();
    $("#otroVersionInputmodalMov").val($("#versionmodalMov option:selected").text());
    if (version == -1) {
      $("#othervermodalMov").removeClass("hidden");
    }
    else{
      $("#othervermodalMov").addClass("hidden");
    }
}

function changeMarcaIns(e){
    var marcamodal=e.val();
    var modelo=0;
    var ano=$("#anomodalIns").val();
    var version=$("#versionmodalIns").val();
    $("#anomodalIns").val(0);
    $("#versionmodalIns").val(0);
    $.ajax({
        url : "/php/catalogoAutos/getModels.php/",
        async : false,
        type : "POST",
        data: "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#modelomodalIns").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/getYears.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#anomodalIns").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/knowVersion.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
        success : function(resp){
            $("#versionmodalIns").html(resp);
        }
    })
    if(marcamodal!=-1)
    {
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalIns").addClass("hidden");
        $("#othervermodalIns").addClass("hidden");
    }
    if(e.val()=="-1")
    {
        $(".otraMarca").removeClass("hidden");
        $("#modelomodalIns").val("-1");
        $("#anomodalIns").val("0");
        $("#versionmodalIns").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalIns").addClass("hidden");
        $("#othervermodalIns").removeClass("hidden");
    }
    if(e.val()=="0")
    {
        $("#anomodalIns").val("0");
        $("#versionmodalIns").val("0");
        $("#modelomodalIns").val("0");
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalIns").addClass("hidden");
        $("#othervermodalIns").addClass("hidden");
    }
    $("#modelomodalIns").find("option").each(function(){

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

function changeSubmarcaIns(e){
    var modelo=e.val();
    if(modelo==-1||modelo==0)
    {
        marcamodal=$("#marcamodalIns").val();
    }
    else
    {
        var marcamodal=e.find("option:selected").data("marca");
    }
    var ano=$("#anomodalIns").val();
    var version=$("#versionmodalIns").val();
    $("#marcamodalIns").val(marcamodal);
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
        $("#otheryearmodalIns").addClass("hidden");
        $("#othervermodalIns").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#anomodalIns").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/knowVersion.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
            success : function(resp){
                $("#versionmodalIns").html(resp);
            }
        })
    }
    if(modelo=="-1")
    {
        $("#anomodalIns").val("0");
        $("#versionmodalIns").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalIns").addClass("hidden");
        $("#othervermodalIns").removeClass("hidden");
    }
    if(modelo=="0")
    {
        $("#anomodalIns").val("0");
        $("#versionmodalIns").val("0");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalIns").addClass("hidden");
        $("#othervermodalIns").addClass("hidden");
    }
}

function changeAnoIns(e){
    var ano=e.val();
    var modelo=$("#modelomodalIns").val();
    var marcamodal=$("#marcamodalIns").val();
    if(ano!=0 && ano!=-1)
        $("#othervermodalIns").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getModels.php",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                if(modelo==="0")
                {
                    $("#modelomodalIns").html(resp);
                }
                else
                {
                    $("#versionmodalIns").html(resp);
                }
            }
        });
    
    if(ano=="-1")
    {
        $("#versionmodalIns").val("-1");
        $("#otheryearmodalIns").removeClass("hidden");
        $("#othervermodalIns").removeClass("hidden");
    }
    if(ano=="0")
    {
        $("#versionmodalIns").val(0);
        $("#otheryearmodalIns").addClass("hidden");
        $("#othervermodalIns").addClass("hidden");
    }
}
function changeVersionIns(e){
    var version=e.val();
    $("#otroVersionInputmodalIns").val($("#versionmodalIns option:selected").text());
    if (version == -1) {
      $("#othervermodalIns").removeClass("hidden");
    }
    else{
      $("#othervermodalIns").addClass("hidden");
    }
}

function changeMarcaCompra(e){
    var marcamodal=e.val();
    var modelo=0;
    var ano=$("#anomodalCompra").val();
    var version=$("#versionmodalCompra").val();
    $("#anomodalCompra").val(0);
    $("#versionmodalCompra").val(0);
    $.ajax({
        url : "/php/catalogoAutos/getModels.php/",
        async : false,
        type : "POST",
        data: "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#modelomodalCompra").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/getYears.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
        success : function(resp){
            $("#anomodalCompra").html(resp);
        }
    })
    $.ajax({
        url : "/php/catalogoAutos/knowVersion.php/",
        async : false,
        type : "POST",
        data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
        success : function(resp){
            $("#versionmodalCompra").html(resp);
        }
    })
    if(marcamodal!=-1)
    {
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalCompra").addClass("hidden");
        $("#othervermodalCompra").addClass("hidden");
    }
    if(e.val()=="-1")
    {
        $(".otraMarca").removeClass("hidden");
        $("#modelomodalCompra").val("-1");
        $("#anomodalCompra").val("0");
        $("#versionmodalCompra").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalCompra").addClass("hidden");
        $("#othervermodalCompra").removeClass("hidden");
    }
    if(e.val()=="0")
    {
        $("#anomodalCompra").val("0");
        $("#versionmodalCompra").val("0");
        $("#modelomodalCompra").val("0");
        $(".otraMarca").addClass("hidden");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalCompra").addClass("hidden");
        $("#othervermodalCompra").addClass("hidden");
    }
    $("#modelomodalCompra").find("option").each(function(){

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

function changeSubmarcaCompra(e){
    var modelo=e.val();
    if(modelo==-1||modelo==0)
    {
        marcamodal=$("#marcamodalCompra").val();
    }
    else
    {
        var marcamodal=e.find("option:selected").data("marca");
    }
    var ano=$("#anomodalCompra").val();
    var version=$("#versionmodalCompra").val();
    $("#marcamodalCompra").val(marcamodal);
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
        $("#otheryearmodalCompra").addClass("hidden");
        $("#othervermodalCompra").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#anomodalCompra").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/knowVersion.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
            success : function(resp){
                $("#versionmodalCompra").html(resp);
            }
        })
    }
    if(modelo=="-1")
    {
        $("#anomodalCompra").val("0");
        $("#versionmodalCompra").val("-1");
        $(".otroModelo").removeClass("hidden");
        $("#otheryearmodalCompra").addClass("hidden");
        $("#othervermodalCompra").removeClass("hidden");
    }
    if(modelo=="0")
    {
        $("#anomodalCompra").val("0");
        $("#versionmodalCompra").val("0");
        $(".otroModelo").addClass("hidden");
        $("#otheryearmodalCompra").addClass("hidden");
        $("#othervermodalCompra").addClass("hidden");
    }
}

function changeAnoCompra(e){
    var ano=e.val();
    var modelo=$("#modelomodalCompra").val();
    var marcamodal=$("#marcamodalCompra").val();
    if(ano!=0 && ano!=-1)
        $("#othervermodalCompra").addClass("hidden");
        $.ajax({
            url : "/php/catalogoAutos/getModels.php",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                if(modelo==="0")
                {
                    $("#modelomodalCompra").html(resp);
                }
                else
                {
                    $("#versionmodalCompra").html(resp);
                }
            }
        });
    
    if(ano=="-1")
    {
        $("#versionmodalCompra").val("-1");
        $("#otheryearmodalCompra").removeClass("hidden");
        $("#othervermodalCompra").removeClass("hidden");
    }
    if(ano=="0")
    {
        $("#versionmodalCompra").val(0);
        $("#otheryearmodalCompra").addClass("hidden");
        $("#othervermodalCompra").addClass("hidden");
    }
}
function changeVersionCompra(e){
    var version=e.val();
    $("#otroVersionInputmodalCompra").val($("#versionmodalCompra option:selected").text());
    if (version == -1) {
      $("#othervermodalCompra").removeClass("hidden");
    }
    else{
      $("#othervermodalCompra").addClass("hidden");
    }
}
const number5 = document.querySelector('.number5');
function formatNumber5 (n5) {
    n5 = String(n5).replace(/\D/g, "");
  return n5 === '' ? n5 : Number(n5).toLocaleString('en-US');
}
number5.addEventListener('keyup', (e5) => {
    const element5 = e5.target;
    const value5 = element5.value;
    element5.value = formatNumber5(value5);
});
function fAgrega3()
{
    document.getElementById("montoPrestamoInput").value = document.getElementById("n1").value.replace(/[^0-9]/g,"");
}

$(".searchForm").submit(function(e){
    var search=$(this).find("input").val();
    search=search.trim();
    $(this).find("input").val(search);
    if(search==""){
        e.preventDefault();
        return false;
    }
});
$(".busquedaAvanzada").submit(function(e){
    var minprice=$(this).find(".minprice").val();
    var maxprice=$(this).find(".maxprice").val();
    var send=true;
    if(isNaN(minprice)&&minprice!=""){
        $(this).find(".minprice").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(minprice==""){
        minprice=0;
    }
    if(isNaN(maxprice)&&maxprice!=""){
        $(this).find(".maxprice").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(maxprice==""){
        maxprice=0;
    }
    if(minprice>maxprice){
        $(this).find(".minprice").closest(".form-group").addClass("has-error");
        $(this).find(".maxprice").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(!send){
        e.preventDefault();
        return false;
    }
})
$(".busqueda-width .more-search").click(function(){
    var e=$(this);
    if(!e.hasClass("actioned")){
        e.addClass("actioned");
        var advanced=e.closest("form").siblings("div.advanced-search");
        if(advanced.hasClass("opened")){
            e.removeClass("opened");
            advanced.removeClass("opened");
            setTimeout(function(){
                advanced.css('visibility', 'hidden');

                e.removeClass("actioned");
            },400);
        }
        else{
            advanced.css('visibility', 'visible');
            advanced.addClass("opened");
            e.addClass("opened");
            setTimeout(function(){

                e.removeClass("actioned");
            },400);
        }
    }
})
function getYearsSearch(marca,modelo){
    $.ajax({
        url : "/php/catalogoAutos/getYearsForSearch.php",
        data : "marca="+marca+"&modelo="+modelo,
        async : false,
        type : "POST",
        success: function(msg){
            $(".anoSearch").html(msg);
        }
    })
}
$(".marcaSearch").change(function(){
    var modeloSelect=$(this).closest(".advanced-search").find(".modeloSearch");
    modeloSelect.find("option").addClass("hidden");
    modeloSelect.find("option").attr("disabled",true);
    modeloSelect.find("option[value=0]").attr("disabled",false);
    modeloSelect.find("option[value=0]").removeClass("hidden").prop("selected",true);
    var marca=$(this).val();
    if(marca!=0){
         modeloSelect.find("option[data-marca='"+marca+"']").removeClass("hidden");
         modeloSelect.find("option[data-marca='"+marca+"']").attr("disabled",false);

    }
});
$(".modeloSearch").change(function(){
    var marcaSelect=$(this).closest(".advanced-search").find(".marcaSearch");
    var modelo=$(this).val();
    var marca=$(this).find("option:selected").data("marca");
    if(modelo!=0){
        marcaSelect.find("option[value="+marca+"]").prop("selected",true);
        getYearsSearch(marca,modelo);
    }
    else{
        $(".anoSearch").html('<option value="0">Cualquiera</option>');
    }
});
$(".stateSearch").change(function(){
    var state=$(this).val();
    $.ajax({
        url : "/php/Busqueda/getTownsForSearch.php",
        data : "state="+state,
        async : false,
        type : "POST",
        success: function(msg){
            $(".townSearch").html(msg);
        }
    })
})
function doShareWhatsApp(e){
    var target=e.data("target");
    window.open("https://wa.me/?text="+encodeURIComponent(target),"_blank");
}
function searchToShare(e){
    var text=e.val();
    text=text.toLowerCase();
    if(text==""){
        $(".optionShare").removeClass("hidden");
    }
    else{
        $(".optionShare").each(function(){
            data=$(this).find("span").text();
            data=data.toLowerCase();
            if(data.search(text)<0){
                $(this).addClass("hidden");
            }
            else{
                $(this).removeClass("hidden");
            }
        });
    }
}
function getMentionedUser(e){
        var inputContent=e.val();
        if(inputContent.length>0)
        {
            $.ajax({
                url : "/php/perfil/publicacion/getUsersForTag.php",
                data : "t="+inputContent+"&u="+encodeURIComponent(e.data("u")),
                async : true,
                type : "POST",
                dataType : "html",
                success: function(msg){
                    $("#potencialMencion").show();
                    $("#potencialMencion").html(msg);
                },
                error: function(){
                    $("#potencialMencion").html("");
                    $("#potencialMencion").hide();
                }
            })
        }
        else{
            $("#potencialMencion").html("");
            $("#potencialMencion").hide();
        }
    }
function addUserMention(e){
    var idColaborator=e.data("u");
    var name=e.find("span").text();
    var oldText = $("#publication");
    var newtext = oldText + name;
    $(".textPublish").val(newtext);
    $("#potencialMencion").html("");
    $("#potencialMencion").hide();
}
