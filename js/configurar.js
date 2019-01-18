/*
* @Author: Erik Viveros
* @Date:   2018-08-14 12:47:41
* @Last Modified by:   Erik Viveros
* @Last Modified time: 2018-08-14 12:47:47
*/
if($("#garageZipcode").val()!=="")
{
    $.ajax({
        type: 'POST',
        url: '/php/signup/zipControl.php',
        data: "code=" + $("#garageZipcode").val(),
        success: function(resp) {
            if (resp != 0) {
                var add1 = resp;
                var add1json = JSON.parse(add1);
                $("#delegacion").val(add1json["city"]);
                $("#estado").val(add1json["state"]);
            }
            else {
                $("#delegacion").val("");
                $("#estado").val("");
            }
        }
    });
}