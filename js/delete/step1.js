/*
* @Author: Erik Viveros
* @Date:   2018-08-14 12:50:16
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-09-17 11:00:58
*/
$(".delete").click(function(){
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
    xhr.send("r="+$(this).data("r")+"&t="+tkn);
})