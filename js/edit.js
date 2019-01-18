function editar() {
    var dataForm = new FormData($("#formEditProfile")[0]);
    //var data = $("form").serialize()+"&imagenes=" +imagenes;
    $.ajax({
        type: 'POST',
        url: '/perfil/edit/perfil/editProfile.php',
        data: dataForm,
        async:false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(ans) {
            if(ans=="0")
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'La imagen de perfil no es v&aacute;lida o excede el tama&ntilde;o.',
                    type: 'error'
                });
            }
            else if(ans=="1")
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'La imagen de portada no es v&aacute;ida o excede el tama&ntilde;o.',
                    type: 'error'
                });
            }
            else if(ans=="2")
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: '¡Perfil editado!',
                    type: 'success'
                });

                setTimeout(function(){location.reload()},1600);
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
    });
}

function personalizarGarage()
{
    var dataForm = new FormData($("#formEditGarage")[0]);
    var nameRegex = new RegExp(/^([a-zA-Z0-9ñÑáéíóúÁÉÍÓÚÄËÏÖÜäëïöüàèìòùÀÈÌÒÙ]|[$&%@*\-_ (){}\[\]!?¿¡.,;:\n!¿#/\\+^=])*$/g);
    if(!nameRegex.test($("#garageName").val()) || $("#garageName").val()==="" || $("#garageName").val().length <= 1)
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Nombre de garage no v&aacute;lido. ',
            type: 'error'

        });
        $(".form-group").removeClass('has-error');
        $("#garageName").closest(".form-group").addClass("has-error");
        $("#garageName").focus();
        return false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test($("#garagePhone").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Tel&eacute;fono no v&aacute;lido. &Uacute;nicamente n&uacute;meros sin espacios.',
            type: 'error'

        });
        $("#garagePhone").closest(".form-group").addClass("has-error");
        $("#garagePhone").focus();
        return false;
    }
    var numberRegex = new RegExp(/^[0-9]*$/g);
    if(!numberRegex.test($("#garageCellPhone").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Tel&eacute;fono no v&aacute;lido. &Uacute;nicamente n&uacute;meros sin espacios.',
            type: 'error'

        });
        $(".form-group").removeClass('has-error');
        $("#garageCellPhone").closest(".form-group").addClass("has-error");
        $("#garageCellPhone").focus();
        return false;
    }
    var streetRegex = new RegExp(/^([^'"=])*$/g);
    if(!streetRegex.test($("#garageStreet").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en direcci&oacute;n. No se permita el uso de comillas o signo = .',
            type: 'error'

        });
        $(".form-group").removeClass('has-error');
        $("#garageStreet").closest(".form-group").addClass("has-error");
        $("#garageStreet").focus();
        return false;
    }
    var cpRegex = new RegExp(/^[0-9]*$/g);
    if(!cpRegex.test($("#garageZipcode").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en c&oacute;digo postal. &Uacute;nicamente n&uacute;meros sin espacios.',
            type: 'error'

        });
        $(".form-group").removeClass('has-error');
        $("#garageZipcode").closest(".form-group").addClass("has-error");
        $("#garageZipcode").focus();
        return false;
    }
    var bioRegex = new RegExp(/^([^'"=])*$/g);
    if(!bioRegex.test($("#descripcion").val()))
    {
        $next=false;
        new PNotify({
            title: 'AVI cars:',
            text: 'Error en descripci&oacute;n. No se acepta el uso de comillas o signo = .',
            type: 'error'

        });
        $(".form-group").removeClass('has-error');
        $("#descripcion").closest(".form-group").addClass("has-error");
        $("#descripcion").focus();
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/perfil/garage/personalizarGarage.php',
        data: dataForm,
        async:false,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans.Error)
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un error, por favor recargue la p&aacute;gina',
                    type: 'error'
                });
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: '¡Garage editado!',
                    type: 'success'
                });
                setTimeout(function(){window.location.href="/perfil/garage/timeline/?cuenta="+ans.user+"&garage="+ans.Success},1600);
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

function cover(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var cover =  new FormData($("#formCover")[0]);
    var fileInput=$("#formCover input[type=file]");
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
            url: '/perfil/edit/perfil/changePhoto.php',
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
                    $("#formCover input[type=file]").val("");
                } 
                else
                {
                    
                    $coverImg=ans;
                    $("#loadImageCover").modal("show");
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                    $("#formCover input[type=file]").val("");
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
                $("#formCover input[type=file]").val("");
            }

        });
    },1000);
    
}

function saveCover(img)
{
    
    ruta = img;
    $.ajax({
        type: 'POST',
        url: '/perfil/edit/perfil/savePhoto.php',
        data: "ruta=" + ruta+"&tipo="+ 4,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                new PNotify({
                        title: 'AVI cars:',
                        text: 'Portada editada',
                        type: 'success'
                    });
                    setTimeout(function(){location.reload()},1600);
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}

function cancelCover(img)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    ruta = $("#hiddenRoute").val();
    $.ajax({
        type: 'POST',
        url: '/perfil/edit/perfil/removePhoto.php',
        data: "ruta=" + ruta+"&tipo="+ 4,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                $(".header-profile").css("background-image", 'url('+img+')');
                $('button').remove(".guardar-cover");
                $('button').remove(".cerrar-cover");
                $(".editing").show();
                $(".editing-avatar").show();
                //setTimeout(function(){location.reload()},1600);
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}
var $uploadCrop;
var $rawImg;
var $coverCrop;
var $coverImg;

function avatarAng(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var avatar =  new FormData($("#formAvatar")[0]);
    var fileInput=$("#formAvatar input[type=file]");
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
            url: '/perfil/edit/perfil/changePhoto.php',
            data: avatar,
            dataType: "json",
            async:false,
            cache: false,
            contentType: false,
            processData: false,
            success: function(ans) {
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                if(ans.Error)
                {
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'El tipo de imagen de avatar no es v&aacute;lido.',
                        type: 'error'
                    });
                    $("#formAvatar input[type=file]").val("");
                } 
                else
                {
                    $rawImg=ans;
                    $("#loadImage").modal("show");
                    $("#formAvatar input[type=file]").val("");
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
                $("#formAvatar input[type=file]").val("");
            }
        });
    }),1000;
    
}

function saveAvatar(url)
{
    ruta = url;
    $.ajax({
        type: 'POST',
        url: '/perfil/edit/perfil/savePhoto.php',
        data: "ruta=" + ruta+"&tipo="+ 3,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                new PNotify({
                        title: 'AVI cars:',
                        text: 'Imagen de perfil editada.',
                        type: 'success'
                    });
                    setTimeout(function(){location.reload()},1600);
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}

function cancelAvatar(img)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    ruta = $("#hiddenRouteAvatar").val();
    $.ajax({
        type: 'POST',
        url: '/perfil/edit/perfil/removePhoto.php',
        data: "ruta=" + ruta+"&tipo="+ 3,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                $(".avatar-profile").attr("src",img);
                $('button').remove(".guardar-avatar");
                $('button').remove(".cerrar-avatar");
                $(".editing").show();
                $(".editing-avatar").show();
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}

function coverGarage(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var cover =  new FormData($("#garageCover")[0]);
    var fileInput=$("#garageCover input[type=file]");
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
            url: '/perfil/garage/garage-autos/configurar/changePhotoGarage.php',
            data: cover,
            async:false,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(ans) {

                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                if(ans.Error)
                {
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'El tipo de imagen de avatar para el garage no es v&aacute;lido.',
                        type: 'error'
                    });
                    $("#garageCover input[type=file]").val("");
                } 
                else
                {
                    $coverImg=ans;
                    $("#loadImageCover").modal("show");
                    $("#garageCover input[type=file]").val("");
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
                $("#garageCover input[type=file]").val("");
            }

        });
    },1000)
    
}

function saveCoverGarage(link)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    ruta = link
    garage = $("#garage").val();
    $.ajax({
        type: 'POST',
        url: '/perfil/garage/garage-autos/configurar/savePhotoGarage.php',
        data: "ruta=" + ruta+"&tipo="+ 6 + "&garage=" + garage,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                new PNotify({
                        title: 'AVI cars:',
                        text: 'Portada editada',
                        type: 'success'
                    });
                    setTimeout(function(){location.reload()},1600);
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}

function cancelCoverGarage(img)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    ruta = $("#hiddenRoute").val();
    garage = $("#garage").val();
    $.ajax({
        type: 'POST',
        url: '/perfil/garage/garage-autos/configurar/removePhotoGarage.php',
        data: "ruta=" + ruta+"&tipo="+ 6 + "&garage=" + garage,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                $(".header-garage").css("background-image", 'url('+img+')');
                $('button').remove(".guardar-cover");
                $('button').remove(".cerrar-cover");
                $(".editing").show();
                $(".editing-garage-avatar").show();
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}

function avatarGarage(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var avatar =  new FormData($("#garageAvatar")[0]);
    var fileInput=$("#garageAvatar input[type=file]");
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
            url: '/perfil/garage/garage-autos/configurar/changePhotoGarage.php',
            data: avatar,
            async:false,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function(ans) {
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                if(ans.Error)
                {
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'El tipo de imagen de avatar para el garage no es v&aacute;lido.',
                        type: 'error'
                    });
                    $("#garageAvatar input[type=file]").val("");
                } 
                else
                {
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                    if(ans=="error")
                    {
                        new PNotify({
                            title: 'AVI cars:',
                            text: 'El tipo de imagen de avatar no es v&aacute;lido.',
                            type: 'error'
                        });
                    } 
                    else
                    {
                        $rawImg=ans;
                        $("#loadImage").modal("show");
                    }
                    $("#garageAvatar input[type=file]").val("");
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
                $("#garageAvatar input[type=file]").val("");
            }
        });
    },1000)
    
}

function saveAvatarGarage(url)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    ruta = $("#hiddenRouteAvatar").val();
    garage = $("#garage").val();
    ruta=url;
    $.ajax({
        type: 'POST',
        url: '/perfil/garage/garage-autos/configurar/savePhotoGarage.php',
        data: "ruta=" + ruta+"&tipo="+ 5 + "&garage=" + garage,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                new PNotify({
                        title: 'AVI cars:',
                        text: 'Imagen de perfil editada.',
                        type: 'success'
                    });
                    setTimeout(function(){location.reload()},1600);
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}

function cancelAvatarGarage(img)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    ruta = $("#hiddenRouteAvatar").val();
    garage = $("#garage").val();
    $.ajax({
        type: 'POST',
        url: '/perfil/garage/garage-autos/configurar/removePhotoGarage.php',
        data: "ruta=" + ruta+"&tipo="+ 5 + "&garage=" + garage,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="success")
            {
                $(".imgGarage").attr("src",img);
                $('button').remove(".guardar-avatar-garage");
                $('button').remove(".cerrar-avatar-garage");
                $(".editing-garage-avatar").show();
                $(".editing").show();
                $(".avatar-garage").show();
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
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
    });
}
function avatarGarageModal(){
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var avatar =  new FormData($("#garageAvatar")[0]);
    $.ajax({
        type: 'POST',
        url: '/perfil/garage/garage-autos/configurar/changePhotoGarage.php',
        data: avatar,
        async:false,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="error")
            {
                new PNotify({
                    title: 'AVI cars:',
                    text: 'El tipo de imagen de avatar no es v&aacute;lido.',
                    type: 'error'
                });
            } 
            else
            {
                $("#reload-band").addClass('hidden');
                $("#flag-reload").addClass('hidden');
                if(ans=="error")
                {
                    new PNotify({
                        title: 'AVI cars:',
                        text: 'El tipo de imagen de avatar no es v&aacute;lido.',
                        type: 'error'
                    });
                } 
                else
                {
                    //$rawImg=ans;
                    //$("#loadImage").modal("show");
                    $(".newGarageImg").css("background-image", "url('"+ans.img+"')");
                    $(".editGarageAvatarModal").css("bottom", "111px");
                    $("#left-rotate").removeClass("hidden");
                    $("#right-rotate").removeClass("hidden");
                }
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
$(document).ready(function() {
    
    $uploadCrop = $('#img-to-upload').croppie({
        viewport: {
            width: 250,
            height: 250,
            type : "circle"
        },
        boundary: { width: 300, height: 300 },
        enableOrientation: true
    });
    $('#loadImage').on('shown.bs.modal', function(){
        $uploadCrop.croppie('bind', {
            url: $rawImg.img,
            orientataion: 4
        });
    });
    $('#loadImage').on('hidden.bs.modal', function(){
        $.ajax({
            url : "/perfil/edit/perfil/deletePhoto.php",
            data : "url="+$rawImg.img,
            async : false,
            method : "POST",
            success : function(){

            }
        })
    });
    $('#docrop').on('click', function (ev) {
        $("#reload-band").removeClass('hidden');
        $("#flag-reload").removeClass('hidden');
        $uploadCrop.croppie('result', {
            type: 'base64',
            size: {width: 800, height: 800},
            circle: false,
        }).then(function (resp) {
            $.ajax({
                url : "/perfil/edit/perfil/saveCroppedPhoto.php?t=1&actual="+encodeURIComponent($rawImg.img),
                data : "ruta="+encodeURIComponent(resp),
                type : "POST",
                async : false,
                dataType: "json",
                success : function(msg){
                    if($rawImg.type==1){
                        saveAvatar(msg.link);    
                    }
                    else{
                        saveAvatarGarage(msg.link);
                    }
                },
                error : function(){
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                }
            })
        });
    });
    $('.crop-rotate').on('click', function(ev,p=$(this)) {
        $uploadCrop.croppie('rotate',parseInt(p.data('deg')));
    });

    $coverCrop = $('#img-to-upload-cover').croppie({
        viewport: {
            width: 270,
            height: 65,
            type: "square"
        },
        boundary: { width: 270, height: 270 },
        enableOrientation: true
    });
    $('#loadImageCover').on('shown.bs.modal', function(){
        $coverCrop.croppie('bind', {
            url: $coverImg.img,
            orientataion: 4
        });
    });
    $('#loadImageCover').on('hidden.bs.modal', function(){
        $.ajax({
            url : "/perfil/edit/perfil/deletePhoto.php",
            data : "url="+$coverImg.img,
            async : false,
            method : "POST",
            success : function(){

            }
        })
    });
    $('.crop-rotate-cover').on('click', function(ev,p=$(this)) {
        $coverCrop.croppie('rotate',parseInt(p.data('deg')));
    });
    $("#saveCoverProfile").on('click', function (ev) {
        $("#reload-band").removeClass('hidden');
        $("#flag-reload").removeClass('hidden');
        $coverCrop.croppie('result', {
            type: 'base64',
            size: {width: 1022, height: 246},
            circle: false,
        }).then(function (resp) {
            $.ajax({
                url : "/perfil/edit/perfil/saveCroppedPhoto.php?t=2&actual="+encodeURIComponent($coverImg.img),
                data : "ruta="+encodeURIComponent(resp),
                type : "POST",
                async : false,
                dataType : "json",
                success : function(msg){
                    if($coverImg.type==1){
                        saveCover(msg.link);    
                    }
                    else{
                        saveCoverGarage(msg.link);
                    }
                },
                error : function(){
                    $("#reload-band").addClass('hidden');
                    $("#flag-reload").addClass('hidden');
                }
            })
        });
    }); 
    $(".img-up").click(function(e){
        var el = e.target;
        triggered=el.id;
        $("#modAvatar").attr("src", "");
        
       
        if(el.tagName=="IMG")
        {
            $(".flotante").css("display", "none");
            $("#profileImgModal").css("display", "block");
            $("#modAvatar").attr("src", $("#"+triggered).attr("src"));
        }
        else
        {
            if(triggered!=""&&triggered!="saveCoverProfile"&&triggered!="cancelCoverProfile"){
                $(".flotante").css("display", "none");
                $("#profileImgModal").css("display", "block");
                $("#modAvatar").attr("src", $("#"+triggered).data("link"));
            }
        }
    });
});

function closemodalImgAvatar()
{
    $(".flotante").css("display", "block");
    $("#profileImgModal").css("display", "none");
    $(".modal-backdrop").modal("hide");  
    $("#modAvatar").attr("src", ''); 
}