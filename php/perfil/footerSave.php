<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-24 11:25:21
 * @Last Modified by:   Brenda Quiroz
 * @Last Modified time: 2018-07-06 09:49:02
 */
?>
	<div class="modal fade"  id='privacidad' role='dialog'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Actualizar privacidad</h4>
				</div>
				<div class="modal-body">
					<label for="privacy">Privacidad</label>
		            <div class="  text-center"> 
		           		<ul style="list-style: none; padding: 15px">
		           			<li>
		           				<label for="privacy1" class="select-slider pointer">
	           						<div style="width: 20%; float: left;  margin: 20px 0px;">
	           							<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_icon_Perfil-Pública_infotraffic.png" class="settings" style="width: 32px" alt="Privacidad"> 
	           						</div>
		           					<div style="width: 80%; float: right; text-align: left;">
		           						<p>P&uacute;blico&ensp;
		           							<input id="privacy1" type="radio" class="pointer" name="updatePrivacy" value="2" <?= ($privacidad==2) ? "checked" : "" ?>>
		           						</p> 
		           						<p style="font-weight: normal;">Todos te pueden encontrar. Todos te pueden seguir.</p>
		           					</div>
		           				</label>
	           					
		           				
		           			</li>
		           			<li>
		           				<label for="privacy2" class="pointer">
		           					<div style="width: 20%; float: left;  margin: 20px 0px;">
		           						<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png" class="settings" alt="Privacidad">
		           					</div>
		           					<div style="width: 80%; float: right; text-align: left;">
		           						<p>Privado&ensp;
		           							<input id="privacy2" type="radio" class="pointer" name="updatePrivacy" value="1" <?= ($privacidad==1) ? "checked" : "" ?>>
		           						</p> 
		           						<p style="font-weight: normal;">Todos te pueden encontrar. T&uacute; decides si te pueden seguir.</p>
		           					</div>
		           				</label>
		           				
		           				
		           			</li>
		           			<li>
		           				<label for="privacy4" class="pointer">
		           					<div style="width: 20%; float: left;  margin: 20px 0px;">
		           						<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_Ojo-INvisible_infotraffic.png" class="settings" style="width: 38px;" alt="Privacidad">
		           					</div>
		           					<div style="width: 80%; float: right; text-align: left;">
		           						<p>Secreto&ensp;
		           							<input id="privacy4" type="radio" class="pointer" name="updatePrivacy" value="3" <?= ($privacidad==3) ? "checked" : "" ?>> 
		           						</p> 
		           						<p style="font-weight: normal;">Nadie te puede encontrar. Nadie te puede seguir.</p>
		           					</div>
		           				</label>
		           				
		           			</li>
		           		</ul>
		            </div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" data-privacy='<?= $privacyToChange?>' onclick="updatePrivacy($(this));">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="multiple-action-buttons">
		<button class="flotante send" onclick="<?= (isset($function)) ? $function : "" ?>" id="send-form"></button>	
	</div>
	<script type="text/javascript">
		var didScroll;
		var lastScrollTop = 0;
		var delta = 5;
		var imgs={};
		var cont=0;
		var navbarHeight = $('.search-nav').outerHeight();

		$(window).scroll(function(event){
		    didScroll = true;
		});

		setInterval(function() {
		    if (didScroll) {
		        hasScrolled();
		        didScroll = false;
		    }
		}, 250);
		function hasScrolled() {
		    var st = $(this).scrollTop();
		    
		    // Make sure they scroll more than delta
		    if(Math.abs(lastScrollTop - st) <= delta)
		        return;
		    
		    // If they scrolled down and are past the navbar, add class .nav-up.
		    // This is necessary so you never see what is "behind" the navbar.
		    if (st > lastScrollTop && st > navbarHeight){
		        // Scroll Down
		        $('.search-nav').removeClass('nav-down').addClass('nav-up');
		        $(".secondary-nav").css("top","0px");
		        $(".alert-nav").css("top","54px");
		    } else {
		        // Scroll Up
		        if(st + $(window).height() < $(document).height()) {
		            $('.search-nav').removeClass('nav-up').addClass('nav-down');

		        	$(".secondary-nav").css("top","54px");
		        	$(".alert-nav").css("top","108px");
		        }
		    }
		    
		    lastScrollTop = st;
		}
	</script>
	<script type="text/javascript">
		
		function logout(){
			try 
			{
				var auth2 = gapi.auth2.getAuthInstance();
			    auth2.signOut().then(function () {
			    	window.location.href="/php/userLogout.php";
			    });
		    }
		    catch(err) 
		    {
			    console.log(err.message);
			}
			finally
			{
				window.location.href="/php/userLogout.php";
			}
		}
		setTimeout(function(){
			gapi.load('auth2', function() {
		        gapi.auth2.init();
		    });
			
		},2000);
	</script>
</div>
</body>
</html>
