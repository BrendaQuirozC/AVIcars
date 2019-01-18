function agregarCarro($idusuario,$nc)
{
    window.location.href ="/perfil/nuevo-auto/?cuenta="+$idusuario+"&"+"garage="+$nc;
}

function verGarages($idusuario,$nc)
{
    window.location.href ="/perfil/garage/subgarage/?cuenta="+$idusuario+"&"+"garage="+$nc;
}

function showGarage(e)
{
    window.location.href = "/perfil/garage/timeline/?cuenta="+e.data("usuario")+"&garage="+e.data("garage");
}

function opentable(e){

    var locationHost= "http://" + window.location.host + "/";

        var dataid = $("#"+e).find("span").click();
        setTimeout(function () {
            window.location.href = "/#"+e;
        }, 450)

}

function addCarToUsr(idver)
{
    $.ajax({
        type: "POST",
        url: "/perfil/nuevo-auto/confirm.php",
        data: "version=" + idver ,
        async : false,
        success: function (resp) {
            $("#boxCatalog").html(resp);
        }
    }); 
}

function showPrice()
{
    if(!$("#sell").prop("checked"))
    {
        //$("#precio").removeClass("hidden");
        $('#precio').slideToggle("fast");
        $("#sell").prop("checked", true);
    }
    else
    {
        //$("#precio").addClass("hidden");
        $('#precio').slideToggle("fast");
        $("#sell").prop("checked", false);
    }
}
function saveCar()
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    $(".form-group").removeClass("has-error");
    var vin = $("#vinNum").val();
    var color = $("#color").val();
    var estado = $("#estado").val();
    var version=($("#version").val());
    var marca=($("#marca")).val();
    var submarca=($("#modelo")).val();
    var ano=($("#ano")).val();
    var alias=($("#alias")).val();
    var garage=($("#garage")).val();
    var cuenta=($("#cuenta")).val();

    var $resp = true;
    if(alias=="")
    {
        $("#alias").closest(".form-group").addClass("has-error");
        $resp = false;
    }
    if($("#sell").prop("checked")&&version!="0")
    {
        var km = $("#km").val();
        var precio = $("#price").val();
        var sell = 1;
        if(precio == "")
        {
            $("#price").closest(".form-group").addClass("has-error");
            $resp = false;
        }
        if(km == "")
        {
            $("#km").closest(".form-group").addClass("has-error");
            $resp = false;
        } 
    }
    else
    {
        var km = null;
        var precio = null;
        var sell = 0;
    }
    if(!$resp)
    {
        return false;
    }
    var data = "version=" + version + "&vin="+ vin + "&color=" + color + "&estado=" + estado + "&alias=" + $("#alias").val() + "&sell="+ sell + "&garage="+ garage+ "&cuenta="+ cuenta;
    $.ajax({
        type: 'POST',
        url: '/perfil/nuevo-auto/publicarGarage.php',
        data: data,
        dataType: "json",
        success: function(ans) {
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans.Respuesta)
            {
                window.location.href ="/perfil/autos/detalles/?cuenta="+ans.cuenta+"&auto="+ans.auto;
            }
            else{
                new PNotify({
                    title: 'AVI cars:',
                    text: "Contrase&ntilde;a incorrecta",
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

function getVersionId($id){
    var data= $id.data("version");
    //alert(data);
}
