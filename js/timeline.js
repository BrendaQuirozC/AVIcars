/*
* @Author: Erik Viveros
* @Date:   2018-08-14 11:56:30
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-12-21 13:05:11
*/
function seguirSugerencia(e,usuarioAseguir, type) {
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    $.ajax({
        type: 'POST',
        url: '/php/Follow/seguir.php',
        data: "seguir=" + encodeURIComponent(usuarioAseguir)+ "&type="+type,
        success: function(ans) {
            e.find("span").text(ans);
            if(ans=="Siguiendo")
            {
                e.find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
            }
            if(ans=="Siguiendo" || ans=="Solicitud enviada")
            {
                e.attr("onclick", "unfollowSugerencia($(this),'"+usuarioAseguir+"',"+type+")");
            }
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="Solicitud enviada")
            {
                likeSugerencia(e,usuarioAseguir,type);
                new PNotify({
                    title: 'AVI cars:',
                    text: '¡Solicitud enviada!',
                    type: 'success'
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

function unfollowSugerencia(e,usrUnfollow, type)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var data = "unfollow=" + encodeURIComponent(usrUnfollow)+ "&type="+type;    
    $.ajax({
        type: 'POST',
        url: '/php/Follow/unfollow.php',
        data: data ,
        success: function(ans) {
            if(ans=="Seguir")
            {
                e.find("span").text(ans);
                e.find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png");
                e.attr("onclick", "seguirSugerencia($(this),'"+usrUnfollow+"',"+type+")");

            }
            unlikeSugerencia(e,usrUnfollow,type);
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
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

function likeSugerencia(e,id,type)
{
    $.ajax({
        type: 'POST',
        url: '/php/likes/likes.php',
        data: {"liking":id, "tipo":type},
        dataType: 'json',
        success: function(resp)
        {

        },
        error: function(){
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }

    })
}
function unlikeSugerencia(e,id,type)
{
    $.ajax({
        type: 'POST',
        url: '/php/likes/unlike.php',
        data: {"liking":id, "tipo":type},
        dataType: 'json',
        success: function(resp)
        {
            
        },
        error: function(){
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }

    })
}
function loadNextPosts(l,isCar=false){
	xhr = new XMLHttpRequest();
    var url = "/php/perfil/publicacion/posts.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            $("#posts").append(msg);
            search=true;
            
        }
        else{
            if(this.status==403){
                if((l-10) % 30 != 0){
                    var html='<div class="login-content jointo">'
                        +'<p>Se parte de</p>'
                        +'<img class="logo" src="/img/logo_horizontal.png">'
                        +'<img style="width: 90%;" src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/BNNR_AVI_'+Math.floor(Math.random() * 5)+'.png">'
                        +'<p>&iexcl;Crea tu perfil!</p>'
                        +'<div class="login-buttons otherlogin">'
                            +'<ul>'
                                +'<li>'
                                    +'<img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fb_infotraffic.png" id="facebookBtnLogin">'
                                    +'<div id="fbLink"  class="fb-login-button facebook-btn" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" login_text="Facebook" scope="public_profile,email" onlogin="checkLoginState();" href="javascript:void(0);"></div>'
                                +'</li>'
                                +'<li>'
                                    +'<img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fotraffic.png" onclick="window.open(window.location.href=\'/\',\'_blank\')">'
                                +'</li>'
                                +'<li>'
                                    +'<img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_google_infotraffic.png">'
                                    +'<div class=" btn g-signin2" id="loginG" data-onsuccess="onSignIn"></div>'
                                +'</li>'
                                +'<li>'
                                    +'<img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_twitter_infotraffic.png" id="twitterBtnLogin" onclick=\'window.open("/php/login/loginTwitter.php?u="+encodeURIComponent(location.pathname+location.search),"_self");\'>'
                                +'</li>'
                            +'</ul>'
                        +'</div>'
                        +'<b class="subtitle">Al ingresar usted acepta los <a href="/Terminos_y_condiciones_AVIcars.pdf" target="_blank">t&eacute;rminos y condiciones</a>.</b>'
                    +'</div>';
                    $("#posts").append(html);
                }
            }
            
        	search=false;
            if(s=="a"){
                xhr2 = new XMLHttpRequest();
                var urltwo ="/php/perfil/publicacion/getFromGarage.php";
                xhr2.open("POST", urltwo, false);
                xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr2.onreadystatechange = function () { 
                    if(this.status==200)
                    {
                        msg=this.response;
                        if(msg!=0){
                            s="g";
                            lastPost=0;
                            u=msg;
                            loadNextPosts(lastPost);
                            lastPost+=10;
                        }
                        
                    }
                }
                xhr2.send("a="+encodeURIComponent(u));
                
            }
        }
    	
    }
    if(isCar)
        xhr.send("l="+l+"&"+s+"="+encodeURIComponent(u)+"&iscar=1");
    else
        xhr.send("l="+l+"&"+s+"="+encodeURIComponent(u));
    
}
$(window).scroll(function(event){
	if($(window).scrollTop() + $(window).height()> $(document).height() - 60) {
		//console.log(search);
		if(search){
			search=false;
			loadNextPosts(lastPost);
      		lastPost+=10;	
		}
      	
   }

});
$(document).ready(function(){
    var pathloc=window.location.pathname;
    var isCar=false;
    if(pathloc.toLowerCase()=="/perfil/autos/detalles/"){
        isCar = true;
    } 
	loadNextPosts(lastPost,isCar);
	lastPost+=10;
});

function toRightSuggest(e){
    var contentDiv=e.siblings(".suggestions");
    var size=contentDiv[0].scrollWidth-contentDiv.width();
    var currPosition=contentDiv.scrollLeft();
    var newPosition=currPosition+200;
    //contentDiv.scrollLeft(newPosition);
    contentDiv.animate({
        scrollLeft: newPosition
    },200);
    e.siblings(".move.toLeft").show();

    if(newPosition>=size){
        e.hide();
    }
}
function toLeftSuggest(e){
    var contentDiv=e.siblings(".suggestions");
    var currPosition=contentDiv.scrollLeft();
    var newPosition=currPosition-200;
    //contentDiv.scrollLeft(newPosition);
    contentDiv.animate({
        scrollLeft: newPosition
    },200);
    e.siblings(".move.toRight").show();
    if(newPosition<=0){
        e.hide();
    }
}