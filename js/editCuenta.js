/*
* @Author: Erik Viveros
* @Date:   2018-08-14 12:48:20
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-10-02 16:53:28
*/
function esBiciesto(y)
{
    //console.log(y)
    if(y%4==0)
    {
        return true;
    }
    return false;
}
function diasMes(m,b)
{
    var r=0;
    //console.log(m);
    switch(m)
    {
        case "1":
            r=31;
            break;
        case "2":
            r=28
            if(b)
            {
                r=29;
            }
            break;
        case "3":
            r=31;
            break;
        case "4":
            r=30;
            break;
        case "5":
            r=31;
            break;
        case "6":
            r=30;
            break;
        case "7":
            r=31;   
            break;
        case "8":
            r=31;
            break;
        case "9":
            r=30;
            break;
        case "10":
            r=31;
            break;
        case "11":
            r=30;
            break;
        case "12":
            r=31;
            break;
        default:
            r=31;
            break;
    }
    return r
}
function calculardias()
{
    var dia=$("#diaNac").val();
    var mes=$("#mesNac").val();
    var ano=$("#anoNac").val();
    var b=esBiciesto(ano);
    var dias=diasMes(mes,b);
    var html="";
    for(i=1;i<=dias;i++)
    {
        d=""+i+"";
        if(d.length==1)
        {
            ans="0"+d;
        }
        else
        {
            ans=d;
        }
        var selected="";
        if(dia==d)
        {
            selected="selected";
        }
        //onsole.log(dia+" "+d);
        html+="<option value='"+i+"' "+selected+">"+ans+"</option>";
    }
    $("#diaNac").html(html);
}

$(document).ready(function(){
    if($("#signUpZipcode").val()!=="")
    {
        $.ajax({
            type: 'POST',
            url: '/php/signup/zipControl.php',
            data: "code=" + $("#signUpZipcode").val(),
            success: function(resp) {
                if (resp != 0) {
                    var add1 = resp;
                    var add1json = JSON.parse(add1);
                    $("#delegacion").val(add1json["city"]);
                    $("#estado").val(add1json["state"]);
                    $("#pais").val(add1json["country"]);
                }
                else {
                    $("#delegacion").val("");
                    $("#estado").val("");
                    $("#pais").val("");
                }
            }
        });
    }
    //calculardias();
    $(".dateInput").change(function(){
        calculardias();
    })
    $("#delete-account").click(function(){
        xhr = new XMLHttpRequest();
        var response={};
        var url = "deleteAccount.php";
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
                response={Error : true};
            }
            if(response.Success){
                window.location.href=response.Success;
            }
            else{
                new PNotify({
                    title: 'AVI cars:',
                    text: "Oooops! Parece que no te vas a ir. intenta mas tarde nuevamente.",
                    type: 'error'
                });
            }
        }
        xhr.send("c="+$(this).data("c"));
    })
})
/*function perfilShow(idToShow)
{
    var actualObject = $("#"+idToShow);
    actualObject.siblings("div").addClass("hidden");
    actualObject.removeClass("hidden");
}*/
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