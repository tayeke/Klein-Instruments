<?php 
defined('C5_EXECUTE') or die("Access Denied.");
if ($_REQUEST['q']) {
	$r = Loader::helper("file")->getContents(MENU_HELP_SERVICE_URL . '?q=' . $_REQUEST['q']);
	if ($r) {
		print $r;
	} else {
		print Loader::helper('json')->encode(array());
	}	
	exit;
}