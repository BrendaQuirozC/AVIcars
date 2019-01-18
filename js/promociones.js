/*
* @Author: Erik Viveros
* @Date:   2018-08-14 11:54:38
* @Last Modified by:   Erik Viveros
* @Last Modified time: 2018-08-14 11:54:53
*/
function sendData(){
	$("#carbrand_c").val($("#marca option:selected").text()) 
	$("#carsubbrand_c").val($("#modelo option:selected").text()) 
	$("#carmodel_c").val($("#ano option:selected").text()) 
	$("#carversion_c").val($("#version option:selected").text()) 
	$("#description").val("Color: "+$("#color option:selected").text()+", Condicion: "+$("#estado option:selected").text()+", VIN: "+$("#vinNum").val()+", Alias: "+$("#alias").val()) 
	$.ajax({
		url : "http://crm.sercomglob.com.mx//index.php?entryPoint=WebToLeadCapture",
		type : "POST",
		data : $("#formCampana").serialize(),
		success : function(){
			
		}
	});
	$("#mensaje").modal("show");
}
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
	
})