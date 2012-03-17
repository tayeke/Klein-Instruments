<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('elements/header.php'); // get header file
 ?>
	<div class="row">
   		<div class="span12">
        <?php  
            $a = new Area('Main');
			$start = '<div class="clear">';
      		$end = '</div>';
      		$a->setBlockWrapperStart($start);
      		$a->setBlockWrapperEnd($end);
            $a->display($c); // main editable region
        ?>
	    </div> <!-- close main -->
	</div>
<?php   $this->inc('elements/footer.php'); // get footer.php ?>