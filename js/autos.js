/*
* @Author: erikfer94
* @Date:   2018-10-09 11:11:31
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-10-09 15:52:36
*/
var l=0;
var search=true;
function getAutos(){
	$(".seemore").remove();
	xhr = new XMLHttpRequest();
    var url = "/php/perfil/getAutos.php";
    xhr.open("POST", url, false);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () { 
    	if(this.status==200)
        {
            msg=this.response;
            $("#autosContent").append(msg);
            l+=10;
        }
        else{
        	search=false;
        }
    	
    }
    if(acc==0)
    	xhr.send("c="+encodeURIComponent(c)+"&t="+l);
    else
    	xhr.send("c="+encodeURIComponent(c)+"&g="+encodeURIComponent(acc)+"&t="+l);
    
}
$(document).ready(function(){
	getAutos();
	
});