/*
* @Author: erikfer94
* @Date:   2018-09-13 17:36:23
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-09-13 17:45:28
*/
var not=0
function getNotification(){
	$.ajax({
		url : "/notificaciones/getNotifications.php",
		data : "ord="+not,
		type : "POST",
		success : function(msg){
			not++;
			$(".siguiendo").find(".seemore").remove();
			$(".siguiendo").append(msg);
		}
	})
}
$(document).ready(function(){
	getNotification()
})