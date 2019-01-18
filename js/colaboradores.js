/*
* @Author: erikfer94
* @Date:   2018-10-10 14:18:45
* @Last Modified by:   erikfer94
* @Last Modified time: 2018-10-18 18:17:26
*/
function getUsers(e){
	var inputContent=e.val();
	var already=getPendings();
	if(inputContent.length>0)
	{
		$.ajax({
			url : "/php/Garage/getUsersForColaborate.php",
			data : "t="+inputContent+"&g="+encodeURIComponent(e.data("g"))+"&p="+encodeURIComponent(JSON.stringify(already)),
			async : true,
			type : "POST",
			dataType : "html",
			success: function(msg){
				$("#potenciales").show();
				$("#potenciales").html(msg)
			},
			error: function(){
				$("#potenciales").html("");
				$("#potenciales").hide();
			}
		})
	}
	else{
		$("#potenciales").html("");
		$("#potenciales").hide();
	}
}
function addColaborator(e){
	var idColaborator=e.data("u");
	var name=e.find("span").text();
	$(".toColaborate ul").append("<li data-u='"+idColaborator+"' onclick='removePotential($(this));'>"+name+"<span>x</span></li>");
	$("#potenciales").html("");
	$("#potenciales").hide();
	$("#inputMembers").val("");
	$("#addPendings").attr("disabled",false);
}
function removePotential(e){
	e.closest("li").remove();
	if(toAdd()==0){
		$("#addPendings").attr("disabled",true);
	}
}
function toAdd(){
	
	var i=0;
	$(".toColaborate ul").find("li").each(function(){
		i++;
	});
	return i;
}
function getPendings(){
	ids={};
	$(".toColaborate ul").find("li").each(function(i,el){
		ids[i]=$(this).data("u");
	});
	return ids;
}
$(document).ready(function(){
	$("#addPendings").click(function(){
		$("#reload-band").removeClass('hidden');
		$("#flag-reload").removeClass('hidden');
		var pendings=getPendings();
		var g=$(this).data("g");
		var l=$("#selectLevel").val();
		if(toAdd()>0){
			$(this).attr("disabled",true);
			$.ajax({
				url : "/php/Garage/addColaborators.php",
				data : "l="+l+"&g="+encodeURIComponent(g)+"&p="+encodeURIComponent(JSON.stringify(pendings)),
				async : true,
				type : "POST",
				dataType : "html",
				success: function(msg){
					$("#reload-band").addClass('hidden');
					$("#flag-reload").addClass('hidden');
					new PNotify({
			            title: 'AVI cars:',
			            text: 'Se han a&ntilde;adido con &eacute;xito los colaboradores.',
			            type: 'success'
			        });

					setTimeout(function(){
						window.location.reload();
					},1500);
					
				},
				error: function(){
					$("#reload-band").addClass('hidden');
					$("#flag-reload").addClass('hidden');
					new PNotify({
			            title: 'AVI cars:',
			            text: 'Lo sentimos, algo salio mal.',
			            type: 'error'
			        });
					$(this).attr("disabled",false);
				}
			})
			
		}
	});
	$(".deleteMember").click(function(){
		var colaborador=$(this).closest(".member").data("u");
		var garage=$(this).closest(".members").data("g");
		$("#deleteCOlaboradorButton").data("g",garage);
		$("#deleteCOlaboradorButton").data("u",colaborador);
	})
	$("#deleteCOlaboradorButton").click(function(){
		$("#reload-band").removeClass('hidden');
		$("#flag-reload").removeClass('hidden');
		var g=$(this).data("g");
		var u=$(this).data("u");
		$.ajax({
			url : "/php/Garage/deleteColaborator.php",
			data : "g="+encodeURIComponent(g)+"&u="+encodeURIComponent(u),
			async : true,
			type : "POST",
			dataType : "json",
			success: function(msg){
				$("#reload-band").addClass('hidden');
				$("#flag-reload").addClass('hidden');
				if(msg.Success){
					new PNotify({
			            title: 'AVI cars:',
			            text: 'Se ha borrado con &eacute;xito al colaborador.',
			            type: 'success'
			        });

					$(".member[data-u='"+u+"']").remove();
				}
				else{
					new PNotify({
			            title: 'AVI cars:',
			            text: 'Lo sentimos, algo salio mal.',
			            type: 'error'
			        });
					$(this).attr("disabled",false);
				}
			},
			error: function(){
				$("#reload-band").addClass('hidden');
				$("#flag-reload").addClass('hidden');
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Lo sentimos, algo salio mal.',
		            type: 'error'
		        });
				$(this).attr("disabled",false);
			}
		})
	});
	$(".level").change(function(){
		$("#reload-band").removeClass('hidden');
		$("#flag-reload").removeClass('hidden');
		var g=$(this).closest(".members").data("g");
		var u=$(this).closest(".member").data("u");
		var l=$(this).val();
		$.ajax({
			url : "/php/Garage/changeRoleColaborator.php",
			data : "l="+l+"&g="+encodeURIComponent(g)+"&u="+encodeURIComponent(u),
			async : true,
			type : "POST",
			dataType : "json",
			success: function(msg){
				$("#reload-band").addClass('hidden');
				$("#flag-reload").addClass('hidden');
				if(msg.Success){
					new PNotify({
			            title: 'AVI cars:',
			            text: 'Se ha editado con &eacute;xito al colaborador.',
			            type: 'success'
			        });
				}
				else{
					new PNotify({
			            title: 'AVI cars:',
			            text: 'Lo sentimos, algo salio mal.',
			            type: 'error'
			        });
					$(this).attr("disabled",false);
				}
			},
			error: function(){
				$("#reload-band").addClass('hidden');
				$("#flag-reload").addClass('hidden');
				new PNotify({
		            title: 'AVI cars:',
		            text: 'Lo sentimos, algo salio mal.',
		            type: 'error'
		        });
				$(this).attr("disabled",false);
			}
		})
	})
})
