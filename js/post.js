/*
* @Author: Erik Viveros
* @Date:   2018-08-14 11:53:16
* @Last Modified by:   BrendaQuiroz
* @Last Modified time: 2019-01-14 13:21:33
*/
function loadNextPosts(l){
	xhr = new XMLHttpRequest();
    var url = "/php/perfil/publicacion/posts.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            $("#posts").append("<h4>M&aacute;s contenido del autor</h4>"+msg);
            search=true;
            
        }
        else{
        	search=false;
        }
    	
    }
    xhr.send("l="+l+"&"+s+"="+encodeURIComponent(u));   
}
$(".photo-container div").click(function(){
	var e=$(this);
	var imgs=e.closest(".photo-container").data("photos");
	var index=e.data("index");
	$(".publicationModal").find(".carousel-inner").html("");
	$.ajax({
		url : "/php/perfil/publicacion/getAllImages.php",
		data : "imgs="+encodeURI(imgs),
		async: true,
		dataType: "json",
		type : "POST",
		success : function(msg){
			$.each(msg, function(i,el){
				var active=(index==i) ? "active" : "";
				var html='<div class="item '+active+'">'
	    					+'<img class="center-block" src="'+el+'" alt="'+i+'">'
						+'</div>'
				$(".publicationModal").find(".carousel-inner").append(html)
			})
			$(".publicationModal").show();
		}
	})
})
$(document).ready(function(){
	loadNextPosts(lastPost);
});