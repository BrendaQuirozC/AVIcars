    function esBiciesto(y)
    {
        if(y%4==0)
        {
            return true;
        }
        return false;
    }
    function diasMes(m,b)
    {
        var r=0;
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
            html+="<option value='"+i+"' "+selected+">"+ans+"</option>";
        }
        $("#diaNac").html(html);
    }
    function hideBanner(e)
    {
        e.parents(".login-please").css("height", "35px");
        e.attr("onclick", "showBanner($(this))");
        e.text("+");
        e.attr("title","ver más");
    }
    function showBanner(e)
    {
        e.parents(".login-please").css("height", "121px");
        e.attr("onclick", "hideBanner($(this))");
        e.text("×");
        e.attr("title","ver menos");
    }
    function showRegister(){
        $(".modal").modal("hide");
        $("#logeate").modal("show");
    }
    function showLogin(){
        $(".modal").modal("hide");
        $("#inicieSesion").modal("show");
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
    function doShareWhatsApp(e){
        var target=e.data("target");
        window.open("https://wa.me/?text="+encodeURIComponent(target),"_blank");
    }
    $( function() {
        $( "#signUpBirthdate" ).datepicker().datepicker('setDate','-16y');
    });
    $( ".selector" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $(document).ready(function(){
        //calculardias();
        $(".dateInput").change(function(){
            calculardias();
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
    $(".login-now").click(function(){
       
        if($("#logeate").hasClass('hidden'))
        {
            $("#registrate").addClass('hidden');
            $("#logeate").removeClass('hidden');
            $(".login-container").find("h2").text("Registro");
            $(this).text("¡Inicia sesión!");
            $(this).siblings("h5").text("¿Ya tienes cuenta?");
        }
        else
        {
            $("#logeate").addClass('hidden');
            $("#registrate").removeClass('hidden');
            $(".login-container").find("h2").text("Inicia sesión");
            $(this).siblings("h5").text("¿No tienes cuenta?");
            $(this).text("¡Regístrate!");
            
        }

    });

    window.fbAsyncInit = function() {
        FB.init({
          appId      : '1651717514914692',
          cookie     : true,
          xfbml      : true,
          version    : 'v2.12'
        });  
    FB.AppEvents.logPageView();   
    };
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.12&appId=1651717514914692&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    var lastScrollTop = 0;
    var $header=$("html").find("div.container-fluid").children('div');
    if($header.hasClass("header head-form"))
    {
        var $scrollToDisplay=417;
    }
    else{
        var $scrollToDisplay=340;
    }
    $(document).scroll(function() {
        var scrollDistance = $(document).scrollTop();
        var navbarHeight = $('.search-nav').outerHeight();
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