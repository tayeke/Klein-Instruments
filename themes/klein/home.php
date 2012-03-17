<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('elements/header.php'); // get header file
?>
	<div class="row">
		<div class="span8">
        <?php  
            $a = new Area('Gallery');
            $a->display($c); // main editable region
        ?>
    	</div> 
    	<div class="span4 latest-news">
        <?php  
            $a = new Area('Latest Developments');
            $a->display($c); // main editable region
        ?>
       </div>
    </div>
	<div class="row">
		<div class="span4">
        <?php  
            $a = new Area('Site Description');
            $a->display($c); // main editable region
        ?>
    	</div> 
   		<div class="span8 featured-products">
        <?php  
            $a = new Area('Main');
            $a->display($c); // main editable region
        ?>
       </div>
    </div> <!-- close main -->
<?php   $this->inc('elements/footer.php'); // get footer.php ?>