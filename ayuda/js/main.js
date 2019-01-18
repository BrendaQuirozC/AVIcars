/*
* @Author: Erik Viveros
* @Date:   2018-08-17 10:26:27
* @Last Modified by:   BrendaQuiroz
* @Last Modified time: 2018-10-08 11:11:47
*/
var $scrollToDisplay=100;
var barHeight = 38;
$(".sidebar .more").click(function(){
	if(!$(this).hasClass("open")){
		$(".sidebar ul").find("ul").hide();

		$(".sidebar img.more").attr("src","/img/icons/down.png");
		$(this).siblings("ul").show();
		$(this).addClass("open");
		$(this).attr("src","/img/icons/up.png");
	}
	else
	{
		$(this).siblings("ul").hide();
		$(this).removeClass("open");
		$(this).attr("src","/img/icons/down.png");
	}
});

$(".topnavbar .more").click(function(){
	if(!$(this).hasClass("open")){
		$(".topnavbar ul").find("ul").hide();
		$(".topnavbar img.more").attr("src","/img/icons/down.png");
		$(this).siblings("ul").show();
		$(this).addClass("open");
		$(this).attr("src","/img/icons/up.png");
	}
	else
	{
		$(this).siblings("ul").hide();
		$(this).removeClass("open");
		$(this).attr("src","/img/icons/down.png");
	}
});

$(".submenu .arrow").click(function(){
	if($(".submenu .ul-submenu").hasClass('ul-hidden')){
		$(".submenu .ul-submenu").removeClass('ul-hidden');
		$(this).attr("src","/img/icons/up.png");
	}
	else
	{
		$(".submenu .ul-submenu").addClass('ul-hidden');
		$(this).attr("src","/img/icons/down.png");
	}
});
$(document).scroll(function(){
	var scrollDistance = $(document).scrollTop();
	if(scrollDistance >$scrollToDisplay){
        $(".sidebar").addClass("sidebar-fixed");
    } else {
        $(".sidebar").removeClass("sidebar-fixed");
    }
    if(scrollDistance >barHeight) {
        $(".submenu").addClass("sidebar-fixed");
    } else {
        $(".submenu").removeClass("sidebar-fixed");
    }
})

$(document).ready(function(){ 
	$('#howToRegister').on('click',function(){
	   	if($('#whoSeesMe-info').css('display','block')){
			$('#whoSeesMe-info').css('display','none');
		}
		if($('#howToSell-info').css('display','block')){
	 		$('#howToSell-info').css('display','none');
		}
		if($('#cantAccess-info').css('display','block')){
	 		$('#cantAccess-info').css('display','none');
		}
		if($('#beingBlocked-info').css('display','block')){
	 		$('#beingBlocked-info').css('display','none');
		}
		if($('#certifiedGarages-info').css('display','block')){
	 		$('#certifiedGarages-info').css('display','none');
		}
		$('#howToRegister-info').toggle('linear');
   	});
   	$('#whoSeesMe').on('click',function(){
		if($('#howToRegister-info').css('display','block')){
			$('#howToRegister-info').css('display','none');
		}
		if($('#howToSell-info').css('display','block')){
	 		$('#howToSell-info').css('display','none');
		}
		if($('#cantAccess-info').css('display','block')){
	 		$('#cantAccess-info').css('display','none');
		}
		if($('#beingBlocked-info').css('display','block')){
	 		$('#beingBlocked-info').css('display','none');
		}
		if($('#certifiedGarages-info').css('display','block')){
	 		$('#certifiedGarages-info').css('display','none');
		}
      	$('#whoSeesMe-info').toggle('linear');
    });
	$('#howToSell').on('click',function(){
		if($('#howToRegister-info').css('display','block')){
			$('#howToRegister-info').css('display','none');
		}
		if($('#whoSeesMe-info').css('display','block')){
	 		$('#whoSeesMe-info').css('display','none');
		}
		if($('#cantAccess-info').css('display','block')){
	 		$('#cantAccess-info').css('display','none');
		}
		if($('#beingBlocked-info').css('display','block')){
	 		$('#beingBlocked-info').css('display','none');
		}
		if($('#certifiedGarages-info').css('display','block')){
	 		$('#certifiedGarages-info').css('display','none');
		}
    	$('#howToSell-info').toggle('linear');
	});
   	$('#cantAccess').on('click',function(){
   		if($('#howToRegister-info').css('display','block')){
			$('#howToRegister-info').css('display','none');
		}
		if($('#howToSell-info').css('display','block')){
	 		$('#howToSell-info').css('display','none');
		}
		if($('#whoSeesMe-info').css('display','block')){
	 		$('#whoSeesMe-info').css('display','none');
		}
		if($('#beingBlocked-info').css('display','block')){
	 		$('#beingBlocked-info').css('display','none');
		}
		if($('#certifiedGarages-info').css('display','block')){
	 		$('#certifiedGarages-info').css('display','none');
		}
	    $('#cantAccess-info').toggle('linear');
   	});
   	$('#beingBlocked').on('click',function(){
   		if($('#howToRegister-info').css('display','block')){
			$('#howToRegister-info').css('display','none');
		}
		if($('#howToSell-info').css('display','block')){
	 		$('#howToSell-info').css('display','none');
		}
		if($('#cantAccess-info').css('display','block')){
	 		$('#cantAccess-info').css('display','none');
		}
		if($('#whoSeesMe-info').css('display','block')){
	 		$('#whoSeesMe-info').css('display','none');
		}
		if($('#certifiedGarages-info').css('display','block')){
	 		$('#certifiedGarages-info').css('display','none');
		}
    	$('#beingBlocked-info').toggle('linear');
   	});
   	$('#certifiedGarages').on('click',function(){
   		if($('#howToRegister-info').css('display','block')){
			$('#howToRegister-info').css('display','none');
		}
		if($('#howToSell-info').css('display','block')){
	 		$('#howToSell-info').css('display','none');
		}
		if($('#cantAccess-info').css('display','block')){
	 		$('#cantAccess-info').css('display','none');
		}
		if($('#beingBlocked-info').css('display','block')){
	 		$('#beingBlocked-info').css('display','none');
		}
		if($('#whoSeesMe-info').css('display','block')){
	 		$('#whoSeesMe-info').css('display','none');
		}
    	$('#certifiedGarages-info').toggle('linear');
   	});
});