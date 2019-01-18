/*
* @Author: Erik Viveros
* @Date:   2018-08-14 11:55:48
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-09-14 16:14:51
*/
var p=10;
var g=0;
var c=0;
var a=0;
var ps=0;
function getFollowers(e){
	var target=e.data("target");
	var count=0;
	switch(target){
		case "personas":
			count=p;
			p+=10;
			break;
		case "garages":
			count=g;
			g+=10;
			break;
		case "autos":
			count=c;
			c+=10;
			break;
		case "anuncios":
			count=a;
			a+=10;
			break;
		case "publicacion":
			count=ps;
			ps+=10;
			break;
		default:
			count=p;
			p+=10
			break;
	}
	var content=$(".siguiendo[data-content="+target+"]");
	$.ajax({
		type: "POST",
		url: "/siguiendo/followings.php",
		data: target+"="+true+"&c="+count,
		success: function(msg)
		{
			content.find(".seemore").remove();
			content.append(msg);

		}
	});
}
$(document).ready(function(){
	$(".followingOp").click(function(){
		$(".siguiendo").removeClass("active")
		$(".followingOp").removeClass("active");
		$(this).addClass("active");
		var target=$(this).data("target");
		$(".siguiendo[data-content="+target+"]").addClass("active");
		getFollowers($(this));
	})	
});