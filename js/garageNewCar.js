$(document).ready(function() 
{
    const priceNF = document.querySelector('.priceNF');
    if(priceNF)
    {
        function formatNumberPrice (formatPrice) 
        {
            formatPrice = String(formatPrice).replace(/\D/g, "");
            return formatPrice === '' ? formatPrice : Number(formatPrice).toLocaleString('en-US');
        }
        priceNF.addEventListener('keyup', (e3) => {
            const element3 = e3.target;
            const value3 = element3.value;
            element3.value = formatNumberPrice(value3);
        });
    }
    const kmNF = document.querySelector('.kmNF');
    function formatNumberKm (formatKm) 
    {
        formatKm = String(formatKm).replace(/\D/g, "");
        return formatKm === '' ? formatKm : Number(formatKm).toLocaleString('en-US');
    }
    kmNF.addEventListener('keyup', (e3) => {
        const element3 = e3.target;
        const value3 = element3.value;
        element3.value = formatNumberKm(value3);
    });

    $("#carrusel").carrusel();
    setTimeout(function(){
        if($("#zipcode").val()!="")
        {
            $.ajax({
                type: 'POST',
                url: '/php/signup/zipControl.php',
                data: "code=" + $("#zipcode").val(),
                success: function(resp) {
                    if(resp!=0)
                    {
                        var add1 = resp;
                        var add1json = JSON.parse(add1);
                        $("#estado").val(add1json["state"]);
                    }
                    else{
                        $("#estado").val("");
                    }
                }
            });
        }
    },800);
    function askVersion(marca,modelo,ano)
    {
        if(marca!=0&&marca!=-1&&modelo!=0&&modelo!=-1&&ano!=0&&ano!=-1)
        {
            $("#versionManual").addClass("hidden");
            $("#cotizacionAseguradoras").removeClass("hidden");
            $.ajax({
                url : "/php/catalogoAutos/getVersiones.php/",
                async : false,
                type : "POST",
                data : $("#formSeguros").serialize(),
                dataType : "json",
                success : function(resp){
                    var k=0;
                    $.each(resp,function(i,val){
                        if(k==0)
                        {
                            $("#versionNameCurr").text(val);
                            $("#endCuestionario").data("version",i);
                        }
                        k++;
                        var dataHtml="<tr>"
                                        +"<td onclick='selectVersion($(this))' class='version"+i+"'>"
                                            +"<input type='checkbox' value='"+i+"' class='hidden' name='version'>"
                                            +val
                                        +"</td>"
                                    +"</tr>";
                        $("#versionesCotizacion").find("tbody").append(dataHtml);
                    })
                }
            })
        }
        else{
            $("#cotizacionAseguradoras").addClass("hidden");
            $("#versionManual").removeClass("hidden");

        }
    }

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
    
    //$("#marca").val(0);
    //$("#modelo").val(0);
    //$("#ano").val(0);
    //$("version").val(0);
    $("#marca").change(function(){
        var marca=$(this).val();
        var modelo=0;
        var ano=$("#ano").val();
        $("#version").val("-1");
        $("#modelo").val(0);
        $.ajax({
            url : "/php/catalogoAutos/reloadModels.php/",
            async : false,
            type : "POST",
            success : function(resp){
                $("#modelo").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marca+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#ano").html(resp);
            }
        })
        $.ajax({
            url : "/php/catalogoAutos/getVersiones.php/",
            async : false,
            type : "POST",
            data : "marca="+marca+"&modelo="+modelo,
            success : function(resp){
                $("#version").html(resp);
            }
        })

        if($(this).val()=="-1")
        {            
            $("#modelo").val("-1");
            $("#ano").val("0");
        }
        $("#modelo").find("option").each(function(){

            if($(this).data("marca")==marca||$(this).attr("value")==0)
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
    });

    $("#modelo").change(function(){
        var modelo=$(this).val();
        if(modelo==-1||modelo==0)
        {
            marca=$("#marca").val();
        }
        else
        {
            var marca=$(this).find("option:selected").data("marca");
        }

        var ano=$("#ano").val();
        var version=$("#version").val();
        $("#marca").val(marca);
        $(this).find("option").each(function(){
            if($(this).data("marca")==marca||$(this).attr("value")==0)
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
        $.ajax({
            url : "/php/catalogoAutos/getYears.php/",
            async : false,
            type : "POST",
            data : "marca="+marca+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                $("#ano").html(resp);
            }
        })

        $.ajax({
            url : "/php/catalogoAutos/knowVersion.php/",
            async : false,
            type : "POST",
            data : "marca="+marca+"&modelo="+modelo+"&ano="+ano+"&version="+version,
            success : function(resp){
                $("#version").html(resp);
            }
        })
        if(modelo=="-1")
        {
            $("#ano").val("0");
        }
    });

    $("#ano").change(function(){
        var ano=$(this).val();
        var modelo=$("#modelo").val();
        var marca=$("#marca").val();
        $.ajax({
            url : "/php/catalogoAutos/getModels.php",
            async : false,
            type : "POST",
            data : "marca="+marca+"&modelo="+modelo+"&ano="+ano,
            success : function(resp){
                if(modelo==="0")
                {
                    $("#modelo").html(resp);
                }
                else
                {
                    $("#version").html(resp);
                }
            }
        });
        if(ano=="-1")
        {
            $("#modelo").val(modelo);
        }
        if(ano=="0")
        {
            if(modelo==-1)
            {
                $("#modelo").val(0);
            }
            else
            {
                $("#modelo").val(modelo);
            }

        }
    });
    $("#version").change(function(){
        var version=$(this).val();
        if (version == -1) {
          $("#otherver").removeClass("hidden");
        }
        else{
          $("#otherver").addClass("hidden");
        }
    });
    $("#km").on({
            "focus": function (event) {
                $(event.target).select();
            },
            "keyup": function (event) {
                $(event.target).val(function (index, value ) {
                    return value.replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                });
            }
            });
    $("#price").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value ) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });
    if ($('input[name=vender]:checked').val() == 0) {
        $('#check_price').css('color','#888');
    }
    $("#enventa").click(function(){
        $('#precio').removeAttr('disabled');
        $('#moneda_c').removeAttr('disabled');
        $('#check_price').css('color','#333');
    });
    $("#noventa").click(function(){
        $('#precio').attr('disabled', 'disabled'); 
        $('#moneda_c').attr('disabled', 'disabled'); 
        $('#check_price').css('color','#888');
    });
    var $carCrop;
     $carCrop = $('#img-to-upload-car').croppie({
        viewport: { width: 285,
            height: 142,
            type: "square"
        },
        boundary: { width: 285, height: 220},
        enableOrientation: true
    });
    $('#loadImageCoverCar').on('shown.bs.modal', function(){
        $carCrop.croppie('bind', {
            url: $carImg.img,
            orientataion: 4
        });
    });
    $('#loadImageCoverCar').on('hidden.bs.modal', function(){
        $.ajax({
            url : "/perfil/edit/perfil/deletePhoto.php",
            data : "url="+$carImg.img,
            async : false,
            method : "POST",
            success : function(){

            }
        })
    });
    $("#saveCarPicture").on('click', function (ev) {
        $carCrop.croppie('result', {
            type: 'base64',
            size: {width: 855, height: 426},
            circle: false,
        }).then(function (resp) {
            $.ajax({
                url : "/perfil/edit/perfil/saveCroppedPhoto.php?t=3&actual="+encodeURIComponent($carImg.img),
                data : "ruta="+encodeURIComponent(resp)+"&id="+ $carImg.idcar,
                type : "POST",
                async : false,
                dataType: "json",
                success : function(msg){
                    if($carImg.type==2){
                        savingImg(msg.link, msg.car);    
                    }
                }
            })
        });
    }); 
	Dropzone.autoDiscover = false;
    $("#upload2").dropzone({
        maxFilesize: 5, // MB
        addRemoveLinks: true,
        dictRemoveFile: "Eliminar",
        url: "/perfil/nuevo-auto/upload.php",
        maxfilesexceeded: function(file) 
          {
            alert('You have uploaded more than 1 Image. Only the first file will be uploaded!');
          },
        //acceptedFiles: "image/*",
        success: function (file, response) {
            objetoresp = JSON.parse(response);
            if(objetoresp.status == "success")
            {
                file.previewElement.classList.add("dz-success");
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
        },
        removedfile: function(file) {
            var tamanoObj = file.previewElement.classList.length;
            var i = 0;
            var borrar=true;
            while(i<tamanoObj)
            {
                if(file.previewElement.classList[i]=="dz-error")
                {
                    borrar = false;
                    break;
                }
                i++;    
            }
            if(borrar)
            {
                var name = file.name;    
                $.ajax({
                    type: 'POST',
                    url: '/perfil/nuevo-auto/upload.php',
                    data: {name: name,request: 2},
                    sucess: function(data){
                        objetoresp = JSON.parse(data);
                        //console.log('success: ' + objetoresp);
                    }
                });
            }
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        }
    });
});

function sendDataCar()
{
    var $count=0;
    var year = new Date().getFullYear();
    var otroAno=$("#otroAnoInput").val()*1;
    var marca=$("#marca option:selected").text();
    var marcaVal=$("#marca").val();
    var submarca=$("#modelo option:selected").text();
    var submarcaVal=$("#modelo").val();
    var modelo=$("#ano option:selected").text();
    var modeloVal=$("#ano").val();
    var version=$("#version option:selected").text();
    var versionVal=$("#version").val();
    send=true;
    if(marcaVal!=-1 && marcaVal!=0)
    {
        $("#otraMarcaInput").val(marca);
    }
    if(submarcaVal!=-1 && submarcaVal!=0)
    {
        $("#otroModeloInput").val(submarca);
    }
    if(modeloVal!=-1 && modeloVal!=0)
    {
        $("#otroAnoInput").val(modelo);
    }
    if(versionVal!=-1 && versionVal!=0)
    {
        $("#otroVersionInput").val(version);
    }
    var marcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!marcaRegex.test($("#otraMarcaInput").val()) && $("#otraMarcaInput").val() != "")
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, marca no v&aacute;lida.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_marca").addClass('has-error');
        $("#otraMarcaInput").focus();
        return false;
    }
    var submarcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!submarcaRegex.test($("#otroModeloInput").val()) && $("#otroModeloInput").val() != "")
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, submarca no v&aacute;lida.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_submarca").addClass('has-error');
        $("#otroModeloInput").focus();
        return false;
    }
    if($("#otroAnoInput").val() != "" && modeloVal==-1)
    {
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
            text: 'Lo sentimos, a&ntilde;o no v&aacute;lido.',
            type: 'error'
        });
            $(".form-group").removeClass('has-error');
            $("#otheryear").addClass('has-error');
            $("#otroAnoInput").focus();
            return false;
        }
        else{
            $("#otheryear").removeClass('has-error');
        }
    }
    var versionRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!versionRegex.test($("#otroVersionInput").val()) && $("#otroVersionInput").val() != "")
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, versi&oacute;n no v&aacute;lida.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_version").addClass('has-error');
        $("#otroVersionInput").focus();
        return false;
    }
    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if (!nameRegex.test($("#alias").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'El alias es un campo obligatorio. Se aceptan los signos _ ,$ , &, @, *, . entre letras.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_alias").addClass('has-error');
        $("#alias").focus();
        return false;
    }
    var kmRegex = new RegExp(/^[0-9]*$/g);
    if($("#kilometraje").val() != ""  && !kmRegex.test($("#kilometraje").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Kilometraje no v&aacute;lido. &Uacute;nicamente n&uacute;meros.',
                    type: 'error'

                });
        $("#check_kilometraje").closest(".form-group").addClass("has-error");
        $("#kilometraje").focus();
        return false;
    }
    var duenoRegex = new RegExp(/^[0-9]*$/g);
    if($("#duenos").val() != "" && !duenoRegex.test($("#duenos").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'N&uacute;mero de due&ntilde;os no v&aacute;lido. &Uacute;nicamente n&uacute;meros.',
                    type: 'error'

                });
        $("#check_duenos").closest(".form-group").addClass("has-error");
        $("#duenos").focus();
        return false;
    }
    var potenciaRegex = new RegExp(/^[0-9]*$/g);
    if($("#potencia").val() != "" && !potenciaRegex.test($("#potencia").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Potencia no v&aacute;lida. &Uacute;nicamente n&uacute;meros.',
                    type: 'error'

                });
        $("#check_potencia").closest(".form-group").addClass("has-error");
        $("#potencia").focus();
        return false;
    }
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    if($("#garantiaFabrica").val() != "" && !garantRegex.test($("#garantiaFabrica").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en garant&iacute;a de f&aacute;brica. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_garantiaFabrica").closest(".form-group").addClass("has-error");
        $("#garantiaFabrica").focus();
        return false;
    }
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    if($("#garantiaVendedor").val() != "" && !garantRegex.test($("#garantiaVendedor").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en garant&iacute;a de vendedor. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_garantiaVendedor").closest(".form-group").addClass("has-error");
        $("#garantiaVendedor").focus();
        return false;
    }
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    if($("#garantiaUsuario").val() != "" && !garantRegex.test($("#garantiaUsuario").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en garant&iacute;a de usuario. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_garantiaUsuario").closest(".form-group").addClass("has-error");
        $("#garantiaUsuario").focus();
        return false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(typeof $("#precio").val()!= "undefined" && !numberRegex.test($("#precio").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Precio no v&aacute;lido. &Uacute;nicamente n&uacute;meros sin espacios.',
                    type: 'error'

                });
        $("#check_price").closest(".form-group").addClass("has-error");
        $("#precio").focus();
        return false;
    }
    var textRegex = new RegExp(/^([^'"=])*$/g);
    if(typeof $("#anunciotext").val()!= "undefined" && $("#anunciotext").val() != "" && !textRegex.test($("#anunciotext").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en texto del anuncio. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_anunciotext").closest(".form-group").addClass("has-error");
        $("#anunciotext").focus();
        return false;
    }
    var phoneRegex = new RegExp(/^[0-9]*$/g);
    if (typeof $("#phone").val()!= "undefined" && $("#phone").val() != "" && !phoneRegex.test($("#phone").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, tel&eacute;fono no v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros sin espacios.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#phone").addClass('alert-danger');
        $("#phone").focus();
        return false;
    }
    var phoneRegex = new RegExp(/^[0-9]*$/g);
    if (typeof $("#phone2").val()!= "undefined" && $("#phone2").val() != "" && !phoneRegex.test($("#phone2").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, tel&eacute;fono 2 no v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros sin espacios.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#phone2").addClass('alert-danger');
        $("#phone2").focus();
        return false;
    }
    var phoneRegex = new RegExp(/^[0-9]*$/g);
    if (typeof $("#phone3").val()!= "undefined" && $("#phone3").val() != "" && !phoneRegex.test($("#phone3").val())) {
        $resp=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, tel&eacute;fono 3 no v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros sin espacios.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#phone3").addClass('alert-danger');
        $("#phone3").focus();
        return false;
    }
    if($("#phone").val()=="")
    {
        $count+=1;
    }
    if($("#email").val()=="" && $count)
    {
        $resp=false;
        $("#check_email").addClass("has-error");
        $("#email").after("<span class='alert-danger'>Debes ingresar al menos un tipo de contacto</span>");
        $("#email").focus();
        new PNotify({
            title: 'AVI cars:',
            text: 'Por favor ingresa al menos un tipo de contacto (e-mail o tel&eacute;fono).',
            type: 'error'
        });
        return false;
    }
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    if(typeof $("#email").val()!= "undefined" && $("#email").val() != "" && !emailRegex.test($("#email").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Correo elctr&oacute;nico no válido.',
            type: 'error'
        });
        $("#check_email").addClass("has-error");
        $("#email").focus();
        return false;
    }
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    if(typeof $("#email2").val()!= "undefined" && $("#email2").val() != "" && !emailRegex.test($("#email2").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Correo elctr&oacute;nico 2 no válido.',
            type: 'error'
        });
        $("#check_email2").addClass("has-error");
        $("#email2").focus();
        return false;
    }
    var streetRegex = new RegExp(/^([^'"=])*$/g);
    if(typeof $("#calle").val()!= "undefined" && $("#calle").val() != "" && !streetRegex.test($("#calle").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en calle y n&uacute;mero. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_calle").closest(".form-group").addClass("has-error");
        $("#calle").focus();
        return false;
    }
    var streetRegex = new RegExp(/^([^'"=])*$/g);
    if(typeof $("#colonia").val()!= "undefined" && $("#colonia").val() != "" && !streetRegex.test($("#colonia").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en Colonia/Delegaci&oacute;n. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_colonia").closest(".form-group").addClass("has-error");
        $("#colonia").focus();
        return false;
    }
    var zipRegex = new RegExp(/^[0-9]*$/g);
    if (typeof $("#zipcode").val()!= "undefined" && $("#zipcode").val() != "" && !zipRegex.test($("#zipcode").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, el c&oacute;digo postal no es v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_zipcode").addClass('has-error');
        $("#zipcode").focus();
        return false;
    }
    var textRegex = new RegExp(/^([^'"=])*$/g);
    if(typeof $("#locationreference").val()!= "undefined" && $("#locationreference").val() != "" && !textRegex.test($("#locationreference").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en referencias de la ubicaci&oacute;n. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_locationreference").closest(".form-group").addClass("has-error");
        $("#locationreference").focus();
        return false;
    }
    var pieceRegex = new RegExp(/^([^'"=])*$/g);
    dosave=true;
    $(".pieceInput").each(function()
    {     
        if($(this).val() != "" && !pieceRegex.test($(this).val()))
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Nombre no v&aacute;lido en piezas faltantes.',
                type: 'error'
            });
            $(".form-group").removeClass('has-error');
            $(this).closest(".form-group").addClass("has-error");
            $(this).focus();
            dosave= false;
        }
    });
    if(!dosave){
        return false;
    }
    dofminor=true;
    var minorRegex = new RegExp(/^([^'"=])*$/g);
    $(".missingInput").each(function()
    {  
        if($(this).val() != "" && !minorRegex.test($(this).val()))
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Nombre no v&aacute;lido en fallas menores.',
                type: 'error'
            });
            $(".form-group").removeClass('has-error');
            $(this).closest(".form-group").addClass("has-error");
            $(this).focus();
            dofminor= false;
        }
    });
    if(!dofminor){
        return false;
    }
    dofmayor=true;
    var mayorRegex = new RegExp(/^([^'"=])*$/g);
    $(".missingGreaterInput").each(function()
    {  
        if($(this).val() != "" && !mayorRegex.test($(this).val()))
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Nombre no v&aacute;lido en fallas mayores.',
                type: 'error'
            });
            $(".form-group").removeClass('has-error');
            $(this).closest(".form-group").addClass("has-error");
            $(this).focus();
            dofmayor= false;
        }
    });
    if(!dofmayor){
        return false;
    }
    $.ajax({
        url : "/php/catalogoAutos/editAuto.php",
        type : "POST",
        data : $("#fromEdit").serialize(),
        success : function(){
            window.location.href='/perfil/autos/detalles/?cuenta='+$("#cuenta").val()+'&auto='+$("#auto").val();
        }
    });
}

function sendAd()
{
    var $count=0;
    $(".form-control").removeClass("alert-danger");
    $("#fromEdit").find("span.alert-danger").remove();
    $resp=true;
    var year = new Date().getFullYear();
    var otroAno=$("#otroAnoInput").val()*1;
    var marca=$("#marca option:selected").text();
    var marcaVal=$("#marca").val();
    var submarca=$("#modelo option:selected").text();
    var submarcaVal=$("#modelo").val();
    var modelo=$("#ano option:selected").text();
    var modeloVal=$("#ano").val();
    var version=$("#version option:selected").text();
    var versionVal=$("#version").val();
    send=true;
    if(marcaVal!=-1 && marcaVal!=0)
    { 
        $("#otraMarcaInput").val(marca);
    }
    if(submarcaVal!=-1 && submarcaVal!=0)
    {

        $("#otroModeloInput").val(submarca);
    }
    if(modeloVal!=-1 && modeloVal!=0)
    {

        $("#otroAnoInput").val(modelo);
    }
    if(versionVal!=-1 && versionVal!=0)
    {
        $("#otroVersionInput").val(version);
    }
    var marcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!marcaRegex.test($("#otraMarcaInput").val()) && $("#otraMarcaInput").val() != "")
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, marca no v&aacute;lida.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#chack_marca").addClass('has-error');
        $("#otraMarcaInput").focus();
        return false;
    }
    var submarcaRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!submarcaRegex.test($("#otroModeloInput").val()) && $("#otroModeloInput").val() != "")
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, submarca no v&aacute;lida.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_submarca").addClass('has-error');
        $("#otroModeloInput").focus();
        return false;
    }
    if($("#otroAnoInput").val() != "" && modeloVal==-1)
    {
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
            text: 'Lo sentimos, a&ntilde;o no v&aacute;lido.',
            type: 'error'
        });
            $(".form-group").removeClass('has-error');
            $("#otheryear").addClass('has-error');
            $("#otroAnoInput").focus();
            return false;
        }
        else{
            $("#otheryear").removeClass('has-error');
        }
    }
    var versionRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!versionRegex.test($("#otroVersionInput").val()) && $("#otroVersionInput").val() != "")
    {
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, versi&oacute;n no v&aacute;lida.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_version").addClass('has-error');
        $("#otroVersionInput").focus();
        return false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test($("#precio").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Precio no v&aacute;lido. &Uacute;nicamente n&uacute;meros sin espacios.',
                    type: 'error'

                });
        $("#check_price").closest(".form-group").addClass("has-error");
        $("#precio").focus();
        return false;
    }
    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if (!nameRegex.test($("#alias").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'El alias es un campo obligatorio. No puede empezar con n&uacute;mero/signo. Se aceptan los signos _ ,$ , &, @, *, . entre letras.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#alias").addClass('alert-danger');
        $("#alias").focus();
        return false;
    }
    var textRegex = new RegExp(/^([^'"=])*$/g);
    if($("#anunciotext").val() != "" && !textRegex.test($("#anunciotext").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en texto del anuncio. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_anunciotext").closest(".form-group").addClass("has-error");
        $("#anunciotext").focus();
        return false;
    }
    var streetRegex = new RegExp(/^([^'"=])*$/g);
    if($("#calle").val() != "" && !streetRegex.test($("#calle").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en Calle y n&uacute;mero. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_calle").closest(".form-group").addClass("has-error");
        $("#calle").focus();
        return false;
    }
    var streetRegex = new RegExp(/^([^'"=])*$/g);
    if($("#colonia").val() != "" && !streetRegex.test($("#colonia").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en Colonia/Delegaci&oacute;n. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_colonia").closest(".form-group").addClass("has-error");
        $("#colonia").focus();
        return false;
    }
    var zipRegex = new RegExp(/^[0-9]*$/g);
    if ($("#zipcode").val() != "" && !zipRegex.test($("#zipcode").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, el c&oacute;digo postal no es v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#check_zipcode").addClass('has-error');
        $("#zipcode").focus();
        return false;
    }
    var textRegex = new RegExp(/^([^'"=])*$/g);
    if($("#locationreference").val() != "" && !textRegex.test($("#locationreference").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en referencias de la ubicaci&oacute;n. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_locationreference").closest(".form-group").addClass("has-error");
        $("#locationreference").focus();
        return false;
    }
    var phoneRegex = new RegExp(/^[0-9]*$/g);
    if ($("#phone").val() != "" && !phoneRegex.test($("#phone").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, tel&eacute;fono no v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros sin espacios.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#phone").addClass('alert-danger');
        $("#phone").focus();
        return false;
    }
    var phoneRegex = new RegExp(/^[0-9]*$/g);
    if ($("#phone2").val() != "" && !phoneRegex.test($("#phone2").val())) {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, tel&eacute;fono 2 no v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros sin espacios.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#phone2").addClass('alert-danger');
        $("#phone2").focus();
        return false;
    }
    var phoneRegex = new RegExp(/^[0-9]*$/g);
    if ($("#phone3").val() != "" && !phoneRegex.test($("#phone3").val())) {
        $resp=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Lo sentimos, tel&eacute;fono 3 no v&aacute;lido. S&oacute;lo se aceptan n&uacute;meros sin espacios.',
            type: 'error'
        });
        $(".form-group").removeClass('has-error');
        $("#phone3").addClass('alert-danger');
        $("#phone3").focus();
        return false;
    }
    if($("#phone").val()=="")
    {
        $count+=1;
    }
    if($("#email").val()=="" && $count)
    {
        $resp=false;
        $("#check_email").addClass("has-error");
        $("#email").after("<span class='alert-danger'>Debes ingresar al menos un tipo de contacto</span>");
        $("#email").focus();
        new PNotify({
            title: 'AVI cars:',
            text: 'Por favor ingresa al menos un tipo de contacto (e-mail o tel&eacute;fono).',
            type: 'error'
        });
        return false;
    }
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    if($("#email").val() != "" && !emailRegex.test($("#email").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Correo elctr&oacute;nico no válido.',
            type: 'error'
        });
        $("#check_email").addClass("has-error");
        $("#email").focus();
        return false;
    }
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    if($("#email2").val() != "" && !emailRegex.test($("#email2").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Correo elctr&oacute;nico 2 no válido.',
            type: 'error'
        });
        $("#check_email2").addClass("has-error");
        $("#email2").focus();
        return false;
    }
    var kmRegex = new RegExp(/^[0-9]*$/g);
    if($("#kilometraje").val() != ""  && !kmRegex.test($("#kilometraje").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Kilometraje no v&aacute;lido. &Uacute;nicamente n&uacute;meros.',
                    type: 'error'

                });
        $("#check_kilometraje").closest(".form-group").addClass("has-error");
        $("#kilometraje").focus();
        return false;
    }
    var duenoRegex = new RegExp(/^[0-9]*$/g);
    if($("#duenos").val() != "" && !duenoRegex.test($("#duenos").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'N&uacute;mero de due&ntilde;os no v&aacute;lido. &Uacute;nicamente n&uacute;meros.',
                    type: 'error'

                });
        $("#check_duenos").closest(".form-group").addClass("has-error");
        $("#duenos").focus();
        return false;
    }
    var potenciaRegex = new RegExp(/^[0-9]*$/g);
    if($("#potencia").val() != "" && !potenciaRegex.test($("#potencia").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Potencia no v&aacute;lida. &Uacute;nicamente n&uacute;meros.',
                    type: 'error'

                });
        $("#check_potencia").closest(".form-group").addClass("has-error");
        $("#potencia").focus();
        return false;
    }
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    if($("#garantiaFabrica").val() != "" && !garantRegex.test($("#garantiaFabrica").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en garant&iacute;a de f&aacute;brica. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_garantiaFabrica").closest(".form-group").addClass("has-error");
        $("#garantiaFabrica").focus();
        return false;
    }
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    if($("#garantiaVendedor").val() != "" && !garantRegex.test($("#garantiaVendedor").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en garant&iacute;a de vendedor. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_garantiaVendedor").closest(".form-group").addClass("has-error");
        $("#garantiaVendedor").focus();
        return false;
    }
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    if($("#garantiaUsuario").val() != "" && !garantRegex.test($("#garantiaUsuario").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en garant&iacute;a de usuario. No se acepta el uso de comillas o signo = .',
            type: 'error'
        });
        $("#check_garantiaUsuario").closest(".form-group").addClass("has-error");
        $("#garantiaUsuario").focus();
        return false;
    }

    var garantRegex = new RegExp(/^([^'"=])*$/g);
    dosave=true;
    $(".pieceInput").each(function()
    {     
        if($(this).val() != "" && !garantRegex.test($(this).val()))
        {
           
            new PNotify({
                title: 'AVI cars:',
                text: 'Nombre no v&aacute;lido en piezas faltantes.',
                type: 'error'
            });
            $(".form-group").removeClass('has-error');
            $(this).closest(".input-group").addClass("has-error");
            $(this).focus();
            dosave= false;
        }
    });
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    $(".missingInput").each(function()
    {  
        if($(this).val() != "" && !garantRegex.test($(this).val()))
        {
            $next=false;
            new PNotify({
                title: 'AVI cars:',
                text: 'Nombre no v&aacute;lido en fallas menores.',
                type: 'error'
            });
            $(".form-group").removeClass('has-error');
            $(this).closest(".form-group").addClass("has-error");
            $(this).focus();
            dosave= false;
        }
    });
    var garantRegex = new RegExp(/^([^'"=])*$/g);
    $(".missingGreaterInput").each(function()
    {  
        if($(this).val() != "" && !garantRegex.test($(this).val()))
        {
            $next=false;
            new PNotify({
                title: 'AVI cars:',
                text: 'Nombre no v&aacute;lido en fallas mayores.',
                type: 'error'
            });
            $(".form-group").removeClass('has-error');
            $(this).closest(".form-group").addClass("has-error");
            $(this).focus();
            dosave= false;
        }
    });
    if(!dosave){
        return false;
    }
    if($resp)
    {
         $.ajax({
            url : "/php/auto/adCar.php",
            type : "POST", 
            data : $("#fromEdit").serialize(),
            dataType : "json",
            success : function(msg){
                if(msg.success)
                {
                    window.location.href='/perfil/autos/detalles/?cuenta='+$("#cuenta").val()+'&auto='+$("#auto").val();
                }
                else{
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'Oooops! Algo salio mal.',
                        type: 'error'
                    });
                }
            }
        });
    }
}

function showModal(e)
{
    $("#modalDelete").find("button:last").data("img", e.data("img"));
    $("#modalDelete").find("button:last").data("car", e.data("car"));
    $("#modalDelete").modal("show");
}

function hideimgCarModal(e)
{
    $("#modalDelete").modal("hide");
}

function deletedImg(e){
    var idCar = e.data("car");
    var idImg = e.data("img");
    var eliminar = $("#eliminar").val();
    data = "eliminar="+ eliminar + "&idCar=" + idCar + "&idImg=" + idImg;
    $.ajax({
        url : "/php/catalogoAutos/deleteImg.php",
        type : "POST",
        data : data,
        success : function(resp){
            if (resp=="success")
            {
                $("#editingPhotosDiv").find("a[data-img='"+idImg+"']").parents(".inline-logos").remove();
                $("#modalDelete").modal("hide");
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema en la conexi&oacute;n, por favor recargue la p&aacute;gina.',
                    type: 'error'
                });
            }
        }
    });
}
var $carImg;
function addingImg(e){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var idCar = $("#car").val();
    var data =  new FormData($("#photoCar")[0]);
    var fileInput=$("#photoCar input[type=file]");
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
            success : function(response){
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                if(response.Error)
                {
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'El tipo de imagen no es v&aacute;lido.',
                        type: 'error'
                    });
                    $("#photoCar input[type=file]").val("");
                }
                else
                {
                    $htmlDiv = '<div class="inline-logos col-xs-3 newImgEdit" style="background-image: url('+response.img+')">'+
                        '<div class="font-trash">'+
                            '<a class=" icon-trash icon-trash-car" data-img="'+response.imgid+'" data-car="'+response.idcar+'" onclick="showModal($(this))" title="Borrar"></a>'+
                        '</div>'+
                        '</div>';
                    $($htmlDiv).insertBefore("#photoCar");
                    $(document).ready(function() {   
                        setTimeout(function() {
                            $("#reload-band").addClass('hidden');
                            $("#flag-reload").addClass('hidden');
                            $("#photoCar input[type=file]").val("");
                        },500);
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
                $("#photoCar input[type=file]").val("");
            }
        });
    },1000);
}

function openSlide()
{   
    $(".flotante").css("display", "none");
    $("#vehicleImgModal").css("display", "block");
}
function showSlides(n)
{
    var i;
    var slides = document.getElementsByClassName("mySlide");
    if (n >= slides.length) 
    {
        var n = 0;
    }
    if(n==(slides.length-1))
    {
        $(".nextSlide").attr("onclick", "disabled");
    }
    else
    {
         $(".nextSlide").attr("onclick", "plusSlide(1)");
    }
    if (n < 0) 
    {
        var n = slides.length-1;
    }
    if (n == 0) 
    {
        $(".prevSlide").attr("onclick", "disabled");
    }
    else
    {
        $(".prevSlide").attr("onclick", "plusSlide(-1)");
    }
    for (i = 0; i < slides.length; i++) 
    {
         slides[i].style.display = "none";
    }
    
    slides[n].style.display = "block";
}
function plusSlide(n)
{
    showSlides(slideIndex += n);
}
function currSlide(n)
{
    showSlides(slideIndex = n);
}
function closeModalImgCar()
{
    $(".flotante").css("display", "block");
    $("#vehicleImgModal").css("display", "none");
    $(".modal-backdrop").modal("hide");  
    //$("#modVechicle").attr("src", ''); 
}
function addPriceFormat()
{
    var resp = $("#formatPrice").val().replace(/[^0-9]/g,"");
    $("#precio").val(resp);
}
function addKmFormat()
{
    var resp = $("#formatKm").val().replace(/[^0-9]/g,"");
    $("#kilometraje").val(resp);
}

function coverCar(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var cover =  new FormData($("#formCoverCar")[0]);
    var fileInput=$("#formCoverCar input[type=file]");
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
    setTimeout(function(){
        $.ajax({
            type: 'POST',
            url: '/php/auto/changeCoverCar.php',
            data: cover,
            dataType: "json",
            async:false,
            cache: false,
            contentType: false,
            processData: false,
            success: function(ans) {
                
                if(ans.Error)
                {
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'El tipo de imagen de portada no es v&aacute;lido.',
                        type: 'error'
                    });
                    $("#formCoverCar input[type=file]").val("");
                } 
                else
                {
                    
                    $carImg=ans;
                    $("#loadImageCoverCar").modal("show");
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                    $("#formCoverCar input[type=file]").val("");
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
                $("#formCoverCar input[type=file]").val("");
            }

        });
    },1000);
}
function savingImg(url,idcar){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    ruta = url;
    id = idcar;
     $.ajax({
        type: 'POST',
        url: '/php/catalogoAutos/savingImg.php',
        data: "ruta=" + ruta + "&idcar=" + id,
        dataType: "json",
        success: function(resp) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(resp.error)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                    type: 'error'
                });
            }
            else
            {   
                $("#loadImageCoverCar").modal("hide");
                $("#coverPhotoEdit").attr("style", "background-image: url('"+resp.ruta+"')");
            }
        },
        error: function()
        {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}