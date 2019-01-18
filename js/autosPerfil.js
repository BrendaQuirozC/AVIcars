/*
* @Author: Erik Viveros
* @Date:   2018-08-14 12:39:04
* @Last Modified by:   Erik Viveros
* @Last Modified time: 2018-08-14 12:39:13
*/
function addOnlyCar(e) 
{
	$.ajax({
		type: "POST",
        url: "findGarages.php",
        success: function (resp) {
        	e.siblings("select").remove();
            e.after(resp);
        }
	});
}
function autodetalles()
{
	location.replace("../perfil/?cuenta="+resp.Success);
}