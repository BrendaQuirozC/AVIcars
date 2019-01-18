<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-07-23 12:58:44
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-07 11:55:20
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
$auto=new Auto;
$marcas=$auto->getMarcas();
$submarcas=$auto->getSubMarcas();
$modelos=$auto->getModels();
$versiones=$auto->knowVersion();
?>
<div id="check_newCarBrand" class="form-group selectdiv selecposition top-bottom-space">
	<select class="form-control form-style" id="marcamodal" name="marca">
		<option class="visible" value="0">MARCA</option>
	<?php 
	foreach ($marcas as $m => $marca) 
	{ 
		if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO'){
	?>
		<option data-brand="<?= $marca?>" class="visible" value="<?= $m?>"><?= $marca?></option>
	<?php 
		}
	} 
	?>
	<option class="visible" value="-1">Otra Marca</option>
	</select>
	<div class="otraMarca hidden">
		<label class='control-label'>Especifica la marca</label>
		<input type='text' class='form-control' id='otraMarcaInputmodal' name='otraMarcaInput' placeholder="Otra Marca" value=''/>
	</div>
</div>

<div id="check_newCarSubbrand" class="form-group selectdiv selecposition top-bottom-space">
	<select class="form-control form-style submarca" id="modelomodal" name="submarca" >
		<option value="0">MODELO</option>
	<?php 
	foreach ($submarcas as $sm => $submarca) 
	{ 
	?>
		<option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
	<?php 
	} 
	?>
		<option class="visible" value="-1">Otro Modelo</option>
	</select>
	<div class='otroModelo hidden'>
		<label class='control-label otroModelo'>Especifica el modelo</label>
		<input type='text' class='form-control' id='otroModeloInputmodal' name='otroModeloInput' placeholder="Otro Modelo" value=''/>
	</div>
</div>

<div class="form-group selectdiv selecposition top-bottom-space">
	<select class="form-control form-style" id="anomodal" name="modelo">
		<option value="0">A&Ntilde;O</option>
	<?php 
	foreach ($modelos as $md => $modelo) 
	{ 
	?>
		<option data-modelo="<?= $modelo["modelo"]?>" value="<?= $modelo["id"]?>"> <?= $modelo["modelo"]?> </option>
	<?php 
	} ?>
		<option class="visible" value="-1">Otro A&ntilde;o</option>							
	</select>
	<div id='otheryearmodal' class='otroAno hidden'>
		<label class='control-label'>Especifica el a&ntilde;o</label>
		<input type='text' class='form-control' id='otroAnoInputmodal' name='otroAnoInputmodal' placeholder="Otro A&ntilde;o"/>
	</div>
</div>

<div id="check_vers" class="form-group selectdiv selecposition">
	<select class="form-control form-style" id="versionmodal" name="subnombres" onchange="changeVersionAd($(this))">
		<option value="0">VERSI&Oacute;N</option>
	<?php 
	foreach ($versiones as $vr => $version) 
	{ 
	?>
		<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>" > <?= $version["version"]?> <?= $version["subnombre"]?> </option>
	<?php 
	} ?>
		<option class="visible" value="-1">Otra Versi&oacute;n</option>		
	</select>
	<div id='othervermodal' class='otraVersion hidden'>
		<label class='control-label col-xs-12'>Especifica</label>
		<input type='text' maxlength="50" class='form-control' id='otroVersionInputmodal' name='otroVersionInputmodal' value='' placeholder="Otra Versi&oacute;n"/>
	</div>
</div>
<div id="modalDeleteImgCar" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Eliminar Foto</h4>
			</div>
			<div class="modal-body">
				<p>¿Est&aacute; seguro que desea eliminar la imagen?</p>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" onclick="hideimgCar($(this))">cerrar</button> |
				<button type="button" id="eliminar" class="btn modal-btns" onclick="deletedImgTmpCar($(this))">aceptar</button>
			</div>
		</div>
	</div>
</div>
<script>
	var imgsNewCar={};
	var count=0;
	function addimgtempcar()
	{
		$("#reload-band").removeClass('hidden');
		$("#flag-reload").removeClass('hidden');
		var data =  new FormData($("#photoCarModal")[0]);
		var fileInput=$("#photoCarModal input[type=file]");
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
		        url : "/php/auto/addingTmpimg.php",
		        type : "POST",
		        data : data,
		        async:false,
		        cache: false,
		        contentType: false,
		        processData: false,
		        dataType : "json",
		        success : function(resp){
		            if (resp.Success)
		            {
		            	imgsNewCar[count]=resp.img;
		            	count++;
		                $htmlDiv = '<div class="newcarphotos inline-logos col-xs-6 newImgEdit" style="background-image: url('+resp.Success+')">'+
		                	'<div class="font-trash">'+
		                    	'<a class="borrar icon-trash icon-trash-car" data-img="'+resp.img+'" onclick="showModalTrash($(this))" title="Borrar"></a>'+
		                    '</div>'+
		                    '</div>';
		                $($htmlDiv).insertBefore("#photoCarModal");
		                $(document).ready(function() {   
	    					setTimeout(function() {
								$("#reload-band").addClass('hidden');
	    						$("#flag-reload").addClass('hidden');
	    						$("#photoCarModal input[type=file]").val("");
	    					},500);
						});
		            }
		            else if(resp.Error == 1)
		            {
		            	$("#reload-band").addClass('hidden');
	            		$("#flag-reload").addClass('hidden');
		                new PNotify({
		                    title: 'AVI cars:',
		                    text: 'Hubo un problema en la conexi&oacute;n, por favor recargue la p&aacute;gina.',
		                    type: 'error'
		                });
		                $("#photoCarModal input[type=file]").val("");
		            }
		            else if(resp.Error == 2)
		            {
		            	$("#reload-band").addClass('hidden');
	            		$("#flag-reload").addClass('hidden');
		                new PNotify({
		                    title: 'AVI cars:',
		                    text: 'Sube un archivo v&aacute;lido: jpg, png o jpeg.',
		                    type: 'error'
		                });
		                $("#photoCarModal input[type=file]").val("");
		            }
		        }
		    });
		},1000);
	}
	function showModalTrash(e)
	{
	    $("#modalDeleteImgCar").find("button:last").data("img", e.data("img"));
	    $("#modalDeleteImgCar").modal("show");
	}
	function hideimgCar(e)
	{
		$("#modalDeleteImgCar").modal("hide");
	}
	function deletedImgTmpCar(e){
		$("#reload-band").removeClass('hidden');
    	$("#flag-reload").removeClass('hidden');
	    var idImg = e.data("img");
	    var eliminar = $("#eliminar").val();
	    data = "eliminar="+ eliminar+ "&idImg=" + idImg;
	    $.ajax({
	        url : "/php/auto/deleteImgTmp.php",
	        type : "POST",
	        data : data,
	        success : function(resp){
	            if (resp=="success")
	            {
	                $("#editingPhotosCarModal").find("a[data-img='"+idImg+"']").parents(".inline-logos").remove();
	                $("#modalDeleteImgCar").modal("hide");
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
	$("#garageCarModal").change(function(){
		$("#garageinsertcar").val($(this).val());
	});
	$("#marcamodal").change(function(){
        var marcamodal=$(this).val();
        var modelo=0;
        var ano=$("#anomodal").val();
        var version=$("#versionmodal").val();
        $("#versionmodal").val(0);
        if(marcamodal>0)
        	$("#otraMarcaInputmodal").val($("#marcamodal option:selected").text());
        else
        	$("#otraMarcaInputmodal").val("");
    	$.ajax({
            url : "/php/catalogoAutos/getModels.php/",
            async : false,
            type : "POST",
            data: "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#modelomodal").html(resp);
                //alert(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#anomodal").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/knowVersion.php/",
            async : false,
            type : "POST",
            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano+"&version="+version,
            success : function(resp){
                $("#versionmodal").html(resp);
            }
        })
        
        if(marcamodal!=-1)
        {
        	$(".otraMarca").addClass("hidden");
        	$(".otroModelo").addClass("hidden");
            $("#otheryearmodal").addClass("hidden");
            $("#othervermodal").addClass("hidden");
            $("#otraMarcaInputmodal").val($("#marcamodal option:selected").text());
        }
        if(marcamodal=="-1")
        {            
        	$("#modelomodal").val("-1");
        	$("#anomodal").val("0");
        	$("#versionmodal").val("-1");
            $(".otraMarca").removeClass("hidden");
            $(".otroModelo").removeClass("hidden");
            $("#othervermodal").removeClass("hidden");
        }
        if(marcamodal=="0")
        {
        	$("#anomodal").val("0");
            $("#versionmodal").val("0");
            $("#modelomodal").val("0");
            $(".otraMarca").addClass("hidden");
        	$(".otroModelo").addClass("hidden");
            $("#otheryearmodal").addClass("hidden");
            $("#othervermodal").addClass("hidden");
        }
        $("#modelomodal").find("option").each(function(){

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
                    //alert("nomms");
                }

            }
        });
    });

    $("#modelomodal").change(function(){
        var modelo=$(this).val();
        
        if(modelo==-1||modelo==0)
        {
        	$("#otroModeloInputmodal").val("");
            marcamodal=$("#marcamodal").val();
        }
        else
        {
        	$("#otroModeloInputmodal").val($("#modelomodal option:selected").text());
            var marcamodal=$(this).find("option:selected").data("marca");
        }
        var ano=$("#anomodal").val();
        var version=$("#versionmodal").val();
        $("#marcamodal").val(marcamodal);
        $(this).find("option").each(function(){
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
        	$("#otheryearmodal").addClass("hidden");
        	$("#othervermodal").addClass("hidden");
        	$.ajax({
	            url : "/php/catalogoAutos/getYears.php/",
	            async : false,
	            type : "POST",
	            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
	            success : function(resp){
	                $("#anomodal").html(resp);
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
            $("#anomodal").val("0");
            $("#versionmodal").val("-1");
            $(".otroModelo").removeClass("hidden");
            $("#othervermodal").removeClass("hidden");
        }
        if(modelo=="0")
        {
        	$("#anomodal").val("0");
            $("#versionmodal").val("0");
        	$(".otroModelo").addClass("hidden");
            $("#otheryearmodal").addClass("hidden");
            $("#othervermodal").addClass("hidden");
        }
    });

    $("#anomodal").change(function(){
        var ano=$(this).val();
        if(ano>0)
        	$("#otroAnoInputmodal").val($("#anomodal option:selected").text());
        else
        	$("#otroAnoInputmodal").val("");
        var modelo=$("#modelomodal").val();
        var marcamodal=$("#marcamodal").val();
        
        if(ano!=0 && ano!=-1)
        {
        	$("#otheryearmodal").addClass("hidden");
        	$("#othervermodal").addClass("hidden");
        	$.ajax({
	            url : "/php/catalogoAutos/getModels.php",
	            async : false,
	            type : "POST",
	            data : "marca="+marcamodal+"&modelo="+modelo+"&ano="+ano,
	            success : function(resp){
	                if(modelo==="0")
		                {
		                    $("#modelomodal").html(resp);
		                }
		                else
		                {
		                    $("#versionmodal").html(resp);
		                }
	            }
	        });
        }
        
        if(ano=="-1")
        {
            $("#modelomodal").val(modelo);
            $("#otheryearmodal").removeClass("hidden");
            $("#othervermodal").removeClass("hidden");

        }
        if(ano=="0")
        {
            $("#versionmodal").val(0);
	        $("#otheryearmodal").addClass("hidden");
            $("#othervermodal").addClass("hidden");

        }
    });
    $("#versionmodal").change(function(){
        var version=$(this).val();
        if (version == -1) {
          $("#othervermodal").removeClass("hidden");
        }
        else{
          $("#othervermodal").addClass("hidden");
        }
    });
</script>