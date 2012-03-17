<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<div class="row"> 
   <div class="user_footer span12">
    
		<?php   
            $a = new Area('Footer Content');
            $a->display($c); // footer editable region
        ?> 
    
                    &copy; <?php   echo date('Y')?> <a href="<?php   echo DIR_REL?>/"><?php   echo SITE?></a>.
                    <?php   echo t('All rights reserved.')?>
                    <?php   
                    $u = new User();
                    if ($u->isRegistered()) { ?>
                        <?php    
                        if (Config::get("ENABLE_USER_PROFILES")) {
                            $userName = '<a href="' . $this->url('/profile') . '">' . $u->getUserName() . '</a>';
                        } else {
                            $userName = $u->getUserName();
                        }
                        ?>
                        <span class="sign-in"><?php   echo t('Currently logged in as <b>%s</b>.', $userName)?> <a href="<?php   echo $this->url('/login', 'logout')?>"><?php   echo t('Sign Out')?></a></span>
                    <?php    } else { ?>
                        <span class="sign-in"><a href="<?php   echo $this->url('/login')?>"><?php   echo t('Sign In to Edit this Site')?></a></span>
                    <?php    } ?>
    </div> <!-- close user_footer -->
</div> <!-- close Container-->


</body>

<!--css3-mediaqueries-js - http://code.google.com/p/css3-mediaqueries-js/ - Enables media queries in some unsupported browsers-->
	<script type="text/javascript" src="<?php   echo $this->getThemePath(); ?>/js/css3-mediaqueries.js"></script>
	<script type="text/javascript" src="<?php   echo $this->getThemePath(); ?>/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php   echo $this->getThemePath(); ?>/js/scripts.js"></script>
	
</html>