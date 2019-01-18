function seguirPerfil(usuarioAseguir, type) {
 	$("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
	$.ajax({
		type: 'POST',
		url: '/php/Follow/seguir.php',
		data: "seguir=" + encodeURIComponent(usuarioAseguir)+ "&type="+type,
		success: function(ans) {
            $("#follow").html(ans);
            if(ans=="Siguiendo")
            {
                $("#follow").next("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
            }
            if(ans=="Siguiendo" || ans=="Solicitud enviada")
            {
       			if($("#follow").parent("a").attr("onclick")=="seguirPerfil('"+usuarioAseguir+"',"+type+")")
                {
                    $("#follow").parent("a").attr("onclick", "unfollow($(this),'"+usuarioAseguir+"',"+type+")");
                }
            }
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
            if(ans=="Solicitud enviada")
            {
                $(".psolicitud").parent("a").attr("id", "solicita");
                $(".gsolicitud").parent("a").attr("id", "gsolicita");
                $(".asolicitud").parent("a").attr("id", "asolicita");
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
function unfollow(e,usrUnfollow, type)
{
	$("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var data = null;
    if (typeof (e.data("owner"))!="undefined") {
        var data = "unfollow=" + encodeURIComponent(usrUnfollow)+ "&type="+type + "&dueno=" + encodeURIComponent(e.data("owner"));
    }
    else
    {
        var data = "unfollow=" + encodeURIComponent(usrUnfollow)+ "&type="+type;    
    }
	$.ajax({
		type: 'POST',
		url: '/php/Follow/unfollow.php',
		data: data ,
		success: function(ans) {
            if(ans=="Seguir")
            {
            	$("#follow").html(ans);
        		$("#follow").next("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png");
                $("#follow").parent("a").attr("id", "");
        		if($("#follow").parent("a").attr("onclick")=="unfollow($(this),'"+usrUnfollow+"',"+type+")")
                {
                    $("#follow").parent("a").attr("onclick", "seguirPerfil('"+usrUnfollow+"',"+type+")");
                }
            }
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

function unfollowCar(e,usrUnfollow, type, owner)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    var data = null;
    if (owner!="undefined") {
        var data = "unfollow=" + encodeURIComponent(usrUnfollow)+ "&type="+type + "&dueno=" + encodeURIComponent(owner);
    }
    else
    {
        var data = "unfollow=" + encodeURIComponent(usrUnfollow)+ "&type="+type;    
    }
    $.ajax({
        type: 'POST',
        url: '/php/Follow/unfollow.php',
        data: data ,
        success: function(ans) {
            if(ans=="Seguir")
            {
                $(".unfollow.unfollow-car"+e).closest(".viewingFollowingsCar").remove();
                $("#follow").html(ans);
                $("#follow").next("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png");
                $("#follow").parent("a").attr("id", "");
                if($("#follow").parent("a").attr("onclick")=="unfollow($(this),'"+usrUnfollow+"',"+type+")")
                {
                    $("#follow").parent("a").attr("onclick", "seguirPerfil('"+usrUnfollow+"',"+type+")");
                }
            }
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

function unfollowing(e,usrUnfollow,type,name,lastname)
{
	$.ajax({
		type: 'POST',
		url: '/php/Follow/unfollow.php',
		data: "unfollow=" + encodeURIComponent(usrUnfollow)+ "&type="+type,
		success: function(ans) {
            if(ans=="Seguir")
            {
            	$(".unfollow.unfollow-profile"+e).closest(".viewingFollowings").remove();
            }
		},
		error: function()
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
	});
}

function unfollowingGarage(a,gUnfollow,typeg)
{
    $.ajax({
        type: 'POST',
        url: '/php/Follow/unfollow.php',
        data: "unfollow=" + encodeURIComponent(gUnfollow)+ "&type="+typeg,
        success: function(ans) {
            if(ans=="Seguir")
            {
                $(".unfollow.unfollow-garage"+a).closest(".viewingFollowingsGarage").remove();
            }
        },
        error: function()
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}

function unfollowingFollower(e,usrUnfollow, type)
{
	$.ajax({
		type: 'POST',
		url: '/php/Follow/unfollow.php',
		data: "unfollow=" + encodeURIComponent(usrUnfollow)+ "&type="+type,
		success: function(ans) {
            if(ans=="Seguir")
            {
                //$(".unfollow.unfollow-profile"+e).closest(".viewingFollowingsProfile").remove();
            	e.find("span").text("Seguir");
                e.attr("onclick", "followingFollower($(this),'"+usrUnfollow+"',"+type+")");
             	e.find("img").attr("src","/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png");

            }
		},
		error: function()
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
	});
}

function likeFromFollower(id,type)
{
	$.ajax({
        type: 'POST',
        url: '/php/likes/likes.php',
        data: {"liking":id, "tipo":type},
        dataType: 'json',
        success: function(resp){
        	//console.log(resp);
        },
        error: function(){
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}

function followingFollower(e,usrUnfollow, type)
{
	$.ajax({
		type: 'POST',
		url: '/php/Follow/seguir.php',
		data: "seguir=" + encodeURIComponent(usrUnfollow)+ "&type="+type,
		success: function(ans) {
            if(ans=="Siguiendo" || ans=="Solicitud enviada")
            {
            	e.find("span").text(ans);
                e.attr("onclick", "unfollowingFollower($(this),'"+usrUnfollow+"',"+type+")");
             	e.find("img").attr("src","/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
             	if(ans=="Siguiendo")
             	{
             		likeFromFollower(usrUnfollow, type);
             	}
            }
		},
		error: function()
        {
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
	});
}

function like(e,id,type)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    $.ajax({
        type: 'POST',
        url: '/php/likes/likes.php',
        data: {"liking":id, "tipo":type},
        dataType: 'json',
        success: function(resp)
        {
            if(resp.success)
            {
                if(((type==2 || type==3) && e.find("span").hasClass("likes-garages")) || type==4 || type==5)
                {
                    var countLikes=e.find("span");
                    countLikes.text(parseInt(countLikes.text())+1);
                }
                if(!e.find("span").is("#follow"))
                {
                    e.attr("onclick", "unlike($(this),'"+id+"',"+type+")");
                    e.find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
                    $("p.h-adSale").text("Ya no me interesa");   
                    $("p.h-adSale").addClass("hidden");  
                    $("#wheelAd").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
                    $("#wheelAd").attr("style", "width: 31px;");
                    $("button.h-adSale").text("Ya no me interesa el anuncio");    
                    if(e.data("likes")==".countLikes")
                    {
                        e.parents("div.publication-menu").siblings("div.publication-header").find("ul li:nth-child(1)").attr("onclick", "unlike($(this),'"+id+"',"+type+")");
                        var spanLike=e.data("likes");
                        spanCountLike = e.parents("div.publication-menu").siblings("div.publication-header").find("span"+spanLike);
                        spanCountLike.text(parseInt(spanCountLike.text())+1);
                        spanCountLike.next("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
                    }
                    else if(e.data("likes")==".bodyLike")
                    {
                        var buttonLikeli = e.parents("div.publication-header").siblings("div.publication-menu").find(e.data("likes"));
                        buttonLikeli.attr("onclick", "unlike($(this),'"+id+"',"+type+")");
                        buttonLikeli.find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
                    }
                }
                else
                {
                    e.attr("onclick", "unfollow($(this),'"+id+"',"+type+");unlike($(this),'"+id+"',"+type+")");
                    $(".likesChange").attr("onclick", "unlike($(this),'"+id+"',"+type+")");
                    $(".likesChange").find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png");
                }
            }
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
        },
        error: function(){
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
function unlike(e,id,type)
{
    $("#reload-band").removeClass('hidden');
    $("#flag-reload").removeClass('hidden');
    $.ajax({
        type: 'POST',
        url: '/php/likes/unlike.php',
        data: {"liking":id, "tipo":type},
        dataType: 'json',
        success: function(resp)
        {
            if(resp.success)
            {
                if(((type==2 || type==3) && e.find("span").hasClass("likes-garages")) || type==4 || type==5)
                {
                    var countLikes=e.find("span");
                    countLikes.text(parseInt(countLikes.text())-1);
                }  
                if(!e.find("span").is("#follow"))
                {
                    e.attr("onclick", "like($(this),'"+id+"',"+type+")");
                    e.find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png"); 
                    $("p.h-adSale").text("Me interesa");
                    $("p.h-adSale").removeClass("hidden"); 
                    $("#wheelAd").attr("src", "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off_small.png");
                    $("#wheelAd").attr("style", "width: 25px;");
                    $("button.h-adSale").text("Me interesa el anuncio");
                    if(e.data("likes")==".countLikes")
                    {
                        var spanLike=e.data("likes");
                        e.parents("div.publication-menu").siblings("div.publication-header").find("ul li:nth-child(1)").attr("onclick", "like($(this),'"+id+"',"+type+")");
                        spanCountLike = e.parents("div.publication-menu").siblings("div.publication-header").find("span"+spanLike);
                        spanCountLike.text(parseInt(spanCountLike.text())-1);
                        spanCountLike.next("img").attr("src", "/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png");
                    }
                    else if(e.data("likes")==".bodyLike")
                    {
                        var buttonLikeli = e.parents("div.publication-header").siblings("div.publication-menu").find(e.data("likes"));
                        buttonLikeli.attr("onclick", "like($(this),'"+id+"',"+type+")");
                        buttonLikeli.find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png");
                    }
                } 
                else
                {
                    e.attr("onclick", "seguirPerfil('"+id+"',"+type+");like($(this),'"+id+"',"+type+")");
                    $(".likesChange").attr("onclick", "like($(this),'"+id+"',"+type+")");
                    $(".likesChange").find("img").attr("src", "/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png");
                }   
            }
            $("#reload-band").addClass('hidden');
            $("#flag-reload").addClass('hidden');
        },
        error: function(){
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
function unlikingFromSiguiendo(e,id,type)
{
    $.ajax({
        type: 'POST',
        url: '/php/likes/unlike.php',
        data: {"liking":id, "tipo":type},
        dataType: 'json',
        success: function(resp)
        {
            if(resp.success)
            {
            	e.closest(".viewingFollowingsCar").remove();
                e.closest(".viewingFollowings").remove();
            }
                
        },
        error: function(){
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}

function confirmFollower(e, acceptedUser, type){
    if (typeof (e.data("garage"))!="undefined") {
        var data = "follower=" + encodeURIComponent(acceptedUser)+ "&type="+type + "&garage=" + e.data("garage");
    }
    else if (typeof (e.data("auto"))!="undefined") {
        var data = "follower=" + encodeURIComponent(acceptedUser)+ "&type="+type + "&auto=" + e.data("auto");
    }
    else
    {
        var data = "follower=" + encodeURIComponent(acceptedUser)+ "&type="+type;    
    }
	$.ajax({
        type: 'POST',
        url: '/php/Follow/confirmFollower.php',
        data: data,
        success: function(resp)
        {
            if(resp!="error")
            {
                e.siblings("#notifTexto").text(resp);
                e.next("a").remove();
                e.next("a").removeClass("hidden");
                e.remove();
            } 
        },
        error: function(){
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}

function rejectFollower(e,rejectedUser,type){
    if (typeof (e.data("garage"))!="undefined") {
        var data = "follower=" + encodeURIComponent(rejectedUser)+ "&type="+type + "&garage=" + e.data("garage");
    }
    else if (typeof (e.data("auto"))!="undefined") {
        var data = "follower=" + encodeURIComponent(rejectedUser)+ "&type="+type + "&auto=" + e.data("auto");
    }
    else
    {
        var data = "follower=" + encodeURIComponent(rejectedUser)+ "&type="+type;    
    }
	$.ajax({
        type: 'POST',
        url: '/php/Follow/rejectFollower.php',
        data: data,
        success: function(resp)
        {
        	if(resp=="success")
            {
            	e.parents("div.followRequest").addClass('reject-transition');
                setTimeout(function(){e.parents("div.followRequest").remove()},300); 
                $("div.solicitudes").find(".num").text((parseInt($("div.solicitudes").find(".num").text())-1));
                e.parents("div.notifRequest").addClass('reject-transition');
                setTimeout(function(){e.parents("div.notifRequest").remove()},300); 
            } 
        },
        error: function(){
            new PNotify({
                title: 'AVI cars:',
                text: 'Hubo un problema, por favor intente m&aacute;s tarde.',
                type: 'error'
            });
        }
    });
}

$(document).ready(function(){
	if(typeof(EventSource) !== "undefined") {
	    var source = new EventSource("/php/perfil/notification.php");
	    source.onmessage = function(event) {
	    	if(event.data>0){
                $(".counter").text(event.data);
                $(".counter").show();

            }
            else{
                $(".counter").text(0);
                $(".counter").hide();
            }
            if(event.data<0){
                window.location.href="/";
            }
	    };
	}
});
