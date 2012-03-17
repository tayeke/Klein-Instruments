<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$this->inc('elements/header.php'); // get header file
?>
</head>
<body id="backpage">

<div class="container_12">

	<h1>	<a href="<?php   echo DIR_REL?>/"><?php   
				$block = Block::getByName('My_Site_Name');  
				if( $block && $block->bID ) $block->display();   
				else echo SITE; // display site title
			?></a>
	</h1>

    <div class="main_nav">
            <?php   $this->inc('elements/nav.php'); // get nav.php ?> 
    </div> <!-- close main_nav -->

    <div class="main">
        <?php   
			print $innerContent;
		?>
    </div> <!-- close main -->
    
    <div class="sidebar">  
        <?php   
            $a = new Area('Sidebar');
            $a->display($c); // sidebar editable region
        ?>
    </div> <!-- close sidebar -->
    
<?php   $this->inc('elements/footer.php'); // get footer.php ?>