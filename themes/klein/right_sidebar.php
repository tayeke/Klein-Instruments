<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('elements/header.php'); // get header file
?>
	<div class="left">
	<div class="row">
   		<div class="span8">
        <?php  
            $a = new Area('Main');
            $a->display($c); // main editable region
        ?>
        </div>
    </div> <!-- close main -->
    </div>
    <div class="right">
	<div class="row">
   		<div class="span4">
        <?php  
            $a = new Area('Sidebar');
            $a->display($c); // main editable region
        ?>
        </div>
    </div> <!-- close sidebar -->
    </div>
<?php   $this->inc('elements/footer.php'); // get footer.php ?>