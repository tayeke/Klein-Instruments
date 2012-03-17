<?php    defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php   echo LANGUAGE?>" lang="<?php   echo LANGUAGE?>">
<head>
<?php   Loader::element('header_required'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php   echo $this->getThemePath(); ?>/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" media="screen" type="text/css" href="<?php   echo $this->getStyleSheet('styles.css')?>" />
<link rel="stylesheet" media="screen" type="text/css" href="<?php   echo $this->getStyleSheet('typography.css')?>" />

<!-- 1140px Grid styles for IE -->
<!--[if lte IE 9]><link rel="stylesheet" href="<?php   echo $this->getThemePath(); ?>/css/ie.css" type="text/css" media="screen" /><![endif]-->

<?php    Loader::element('footer_required'); ?>

</head>
<body id="backpage">

<div class="container">
	<div id="heading" class="row">
		<h1><a href="<?php   echo DIR_REL?>/">
				<img src="<?php echo $this->getThemePath(); ?>/img/logo.png" alt="Klein Instruments" width="500" />
			</a>
			<span><i>- Color Measurement Tools</i></span>
		</h1>
		<span class="phone-number">503-746-5354</span>
		<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
		<script type="IN/FollowCompany" data-id="2387788" data-counter="right"></script>	
	    <div class="main_nav">
	    	<?php
			     $navBT = BlockType::getByHandle('autonav');
			     $navBT->controller->displayPages = 'custom';
			     $navBT->controller->displayPagesCID = '1';
			     $navBT->controller->orderBy = 'display_asc';
			     $navBT->controller->displaySubPages = 'all';
			     $navBT->controller->displaySubPageLevels = 'all';
			     $navBT->render('view');
			?>
			<a class="nav-right" href="/ordering">Ordering</a>
	    </div> <!-- close main_nav -->
	</div>