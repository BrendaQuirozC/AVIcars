function shareAccount(garage)
{
    $.ajax({
        type: 'POST',
        url: '/php/perfil/shareAccount.php',
        data: "garage="+garage,
        success: function(resp)
        {
           	$("#modalShare").html(resp);
           	$('#modalShare').modal('show');
        }
    });
}

function usrToShare(e) 
{
	$.ajax({
        type: 'POST',
        url: '/php/perfil/search.php',
        data: "usuario="+e.val(),
        success: function(resp)
        {
            $('#listUsuariosToColaborate').css('visibility','visible');
    	    $( "#listUsuariosToColaborate").html(resp);
        }
    });
}
