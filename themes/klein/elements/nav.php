<?php   defined('C5_EXECUTE') or die(_("Access Denied.")); ?>       
	<div class="nav_container">
		<?php   
			$a = new Area('Header Nav');
			$a->display($c); // main auto nav
		?>
	</div> <!-- close nav_container -->