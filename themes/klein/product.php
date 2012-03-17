<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('elements/header.php'); // get header file
?>
	<div class="left">
	<div class="row">
		<div class="span8">
        <?php  
            $a = new Area('Product Title');
            $a->display($c); 
        ?>
    	</div> 
    </div>
	<div class="row">
		<div class="span8">
        <?php  
            $a = new Area('Description');
            $a->display($c);
        ?>
    	</div> 
    </div>
	<div class="row">
		<div class="span8">
        <?php  
            $a = new Area('Product Image');
            $a->display($c); 
        ?>
    	</div> 
    </div>
	<div class="row">
		<div class="span8">
        <?php  
            $a = new Area('Product Info');
            $a->display($c); 
        ?>
    	</div> 
    </div>
    </div>
    <div class="right">
    <div class="row">
		<div class="span4">
        <?php  
            $a = new Area('Product Features');
            $a->display($c); 
        ?>
    	</div> 
    </div>
    </div>
<?php   $this->inc('elements/footer.php'); // get footer.php ?>