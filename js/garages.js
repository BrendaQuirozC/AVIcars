/*
* @Author: erikfer94
* @Date:   2018-10-09 11:11:31
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-10-19 17:29:56
*/
var l=0;
var search=true;
function getGarages(){
	$(".seemore").remove();
	xhr = new XMLHttpRequest();
    var url = "/php/perfil/getGarages.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            $("#garagesContent").append(msg);
            l+=10;
        }
        else{
        	search=false;
        }
    	
    }
    xhr.send("c="+encodeURIComponent(c)+"&t="+l);
    
}
$(document).ready(function(){
	getGarages();
	
});