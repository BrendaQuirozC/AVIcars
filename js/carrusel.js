var idCarrusel=0;
$.carrusel={
	steps : 1,
	height : 100,
	last : 1,
	sig : function(){
		var lastIndex=this.last;
		var firstIndex=lastIndex+1;
		lastIndex+=this.steps;
		firstIndex=lastIndex+this.steps;
		if($(this).find(".img-carrusel-"+lastIndex).length)
		{
			this.find(".img-carrusel").removeClass("inline-block").addClass("hidden");
			for(i=lastIndex;i<firstIndex;i++)
			{
				this.find(".img-carrusel-"+i).addClass("inline-block").removeClass("hidden");
			}
			this.last=lastIndex;
		}
	},
	prev : function(){
		var lastIndex=this.last;
		if(lastIndex>1)
		{
			var firstIndex=lastIndex;
			lastIndex-=this.steps;
			this.find(".img-carrusel").removeClass("inline-block").addClass("hidden");
			for(i=lastIndex;i<firstIndex;i++)
			{
				this.find(".img-carrusel-"+i).addClass("inline-block").removeClass("hidden");
			}
			this.last=lastIndex;
		}
	}
}
$.carrusel.reference = function (needle) {	
	return $(needle);
};
$.carrusel.init=function(el){
	el.addClass("carrusel");
	el.attr("tabindex",idCarrusel);
	idCarrusel++;
	if(!el.data("steps"))
	{
		el.data("steps",1);
	}
	if(!el.data("last"))
	{
		el.data("last",1);
	}
	if(!el.data("contentsize"))
	{
		el.data("contentsize",78);
	}
	var steps=el.data("steps");
	var last=el.data("last");
	var contentsize=el.data("contentsize")
	var porcentaje=Math.floor(contentsize/steps);
	var navsize=Math.floor((100-contentsize)/2);
	el.find(".carrusel-nav").attr("style","min-width:"+navsize+"% !important;")
	el.find(".img-carrusel").attr("style","width:"+porcentaje+"% !important;")
	for(var i=last; i<(last+steps);i++)
	{
		//console.log(i);
		el.find(".img-carrusel-"+i).addClass("inline-block");
	}
}
$.fn.carrusel=function(){
	//console.log("here");
	var instance = $.carrusel.reference(this);
	$.carrusel.init(instance);	
	//console.log(instance);
	return instance;
}
$(document).ready(function(){
	//carrussel(".carrusel");	
	$(".carrusel-right").click(function(){
		var carrusel=$(this).closest(".carrusel")
		var lastIndex=carrusel.data("last");
		var steps=carrusel.data("steps");

		var firstIndex=lastIndex+1;
		var width=$(document).width();
		var move=true;
		lastIndex+=steps;
		firstIndex=lastIndex+steps;
		if(carrusel.find(".img-carrusel-"+lastIndex).length)
		{
			//alert(lastIndex+"  "+firstIndex);

			carrusel.find(".img-carrusel").removeClass("inline-block").addClass("hidden");
			for(i=lastIndex;i<firstIndex;i++)
			{
				carrusel.find(".img-carrusel-"+i).addClass("inline-block").removeClass("hidden");
			}
			carrusel.data("last",lastIndex);
		}
	})
	$(".carrusel-left").click(function(){
		var carrusel=$(this).closest(".carrusel")
		var lastIndex=carrusel.data("last");
		var steps=carrusel.data("steps");
		if(lastIndex>1)
		{
			var firstIndex=lastIndex;
			var width=$(document).width();
			//$(".img-carrusel").animate({left: "-50px"}, 500);  
			carrusel.find(".img-carrusel").removeClass("inline-block").addClass("hidden"); 
			lastIndex-=steps;
			//console.log(lastIndex+"  "+firstIndex);
			for(i=lastIndex;i<firstIndex;i++)
			{
				carrusel.find(".img-carrusel-"+i).addClass("inline-block").removeClass("hidden");
			}
			carrusel.data("last",lastIndex);
		}
	})
	
	/*$(".carrusel-right").click(function(){
		var lastIndex=$(this).closest(".carrusel").data("last");

		var firstIndex=lastIndex+1;
		var width=$(document).width();
		var move=true;
		if(width>991||(width<768&&width>548))
		{
			lastIndex+=3;
			firstIndex=lastIndex+3;

		}
		else
		{
			lastIndex++;
			firstIndex++;
		}
		if($(".img-carrusel-"+lastIndex).length)
		{
			//alert(lastIndex+"  "+firstIndex);

			$(".img-carrusel").removeClass("inline-block").addClass("hidden");
			for(i=lastIndex;i<firstIndex;i++)
			{
				$(".img-carrusel-"+i).addClass("inline-block").removeClass("hidden");
			}
			$(this).closest(".carrusel").data("last",lastIndex);
		}
	})
	$(".carrusel-left").click(function(){
		var lastIndex=$(this).closest(".carrusel").data("last");
		if(lastIndex>1)
		{
			var firstIndex=lastIndex;
			var width=$(document).width();
			//$(".img-carrusel").animate({left: "-50px"}, 500);  
			$(".img-carrusel").removeClass("inline-block").addClass("hidden"); 
			if(width>991||(width<768&&width>548))
			{
				lastIndex-=3;
			}
			else
			{
				lastIndex--;
			}
			//console.log(lastIndex+"  "+firstIndex);
			for(i=lastIndex;i<firstIndex;i++)
			{
				$(".img-carrusel-"+i).addClass("inline-block").removeClass("hidden");
			}
			$(this).closest(".carrusel").data("last",lastIndex);
		}
	})
	$(".img-carrusel").click(function(){
		var marca=$(this).data("marca");
		$(".img-carrusel").removeClass("selected");
		$(this).addClass("selected");
		$("#marca").closest(".form-group").addClass("has-warning");
		$("#marca").val(marca).trigger("change");
		setTimeout(function(){
			$("#marca").closest(".form-group").removeClass("has-warning");
			$("#modelo").focus();
		},500);
	})*/
})