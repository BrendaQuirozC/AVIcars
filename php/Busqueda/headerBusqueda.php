<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-01 16:02:41
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-12 16:58:49
 */
?>

<div class="header <?= !isset($_SESSION["iduser"]) ? 'searchMargin' : '' ?>">
	
	<nav class="navbar navbar-default">
		<div class="container-fluid navbar-profile search-header">
		    <ul class="nav navbar-nav navbar-padding" id="header-list ">
		    	<li data-target="perfiles" class="tabop">
		    		<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_personas.png">
		    		<span>Personas</span>
		    	</li>
		    	<li data-target="garages" class="tabop ">
		    		<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_garage.png" style="width: 37px;">
		    		<span>Garages</span>
		    	</li>
		    	<li data-target="autos" class="tabop active">
					<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_autos.png">
					<span>Autos</span>
				</li>
				<?php
				if($sess)
				{ ?>	
				<li data-target="posts" class="tabop ">
					<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_publicaciones.png" style="width: 29px;">
					<span>Posts</span>
				</li>
				<?php } ?>
		    </ul>
		</div>
	</nav>
</div>