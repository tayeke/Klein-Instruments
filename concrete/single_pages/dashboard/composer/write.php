<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php 

if (isset($entry)) { ?>

	<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(ucfirst($action) . ' ' . $ct->getCollectionTypeName(), false, false, false)?>
	<form method="post" enctype="multipart/form-data" action="<?php echo $this->action('save')?>" id="ccm-dashboard-composer-form">
	<div class="ccm-pane-body">
	

	<div id="composer-save-status"></div>
	
	<fieldset>
	<legend><?php echo t("Basic Information")?></legend>
	<div class="clearfix">
		<?php echo $form->label('cName', t('Name'))?>
		<div class="input"><?php echo $form->text('cName', $name, array('class' => 'span12'))?></div>		
	</div>

	<div class="clearfix">
		<?php echo $form->label('cDescription', t('Short Description'))?>
		<div class="input"><?php echo $form->textarea('cDescription', $description, array('class' => 'span12', 'rows' => 5))?></div>		
	</div>

	<div class="clearfix">
		<?php echo $form->label('cDatePublic', t('Date Posted'))?>
		<div class="input"><?php  
		if ($this->controller->isPost()) { 	
			$cDatePublic = Loader::helper('form/date_time')->translate('cDatePublic');
		}
		?><?php echo Loader::helper('form/date_time')->datetime('cDatePublic', $cDatePublic)?></div>		
	</div>
	
	</fieldset>
	
	<?php  if ($entry->isComposerDraft()) { ?>
	<fieldset>
	<legend><?php echo t('Publish Location')?></legend>
	<div class="clearfix">
		<label></label>
		<div class="input">
		<span id="ccm-composer-publish-location"><?php 
		if ($entry->getComposerDraftPublishParentID() > 0) { 
			print $this->controller->getComposerDraftPublishText($entry);
		} ?>
		</span>
		
		<?php  
	
	if ($ct->getCollectionTypeComposerPublishMethod() == 'PAGE_TYPE' || $ct->getCollectionTypeComposerPublishMethod() == 'CHOOSE') { ?>
		
		<a href="javascript:void(0)" onclick="ccm_openComposerPublishTargetWindow(false)"><?php echo t('Choose publish location.')?></a>
	
	<?php  } 
	
	?></div></div>
	</fieldset>
	<?php  } ?>
	
	<fieldset>
	<legend><?php echo t('Attributes &amp; Content')?></legend>
	<?php  
	foreach($contentitems as $ci) {
		if ($ci instanceof AttributeKey) { 
			$ak = $ci;
			if (is_object($entry)) {
				$value = $entry->getAttributeValueObject($ak);
			}
			?>
			<div class="clearfix">
				<?php echo $ak->render('label');?>
				<div class="input">
					<?php echo $ak->render('composer', $value, true)?>
				</div>
			</div>
		
		<?php  } else { 
			$b = $ci; 
			$b = $entry->getComposerBlockInstance($b);
			?>
		
		<div class="clearfix">
		<?php 
		if (is_object($b)) {
			$bv = new BlockView();
			$bv->render($b, 'composer');
		} else {
			print t('Block not found. Unable to edit in composer.');
		}
		?>
		
		</div>
		
		<?php 
		} ?>
	<?php  }  ?>
	
	

	</div>
	<div class="ccm-pane-footer">
	<?php 
	$v = $entry->getVersionObject();
	
	?>
	

	<?php  if ($entry->isComposerDraft()) { 
	$pp = new Permissions($entry);
	?>
		<?php echo Loader::helper('concrete/interface')->submit(t('Publish Page'), 'publish', 'right', 'primary')?>
		<?php  if (PERMISSIONS_MODEL != 'simple' && $pp->canAdmin()) { ?>
			<?php echo Loader::helper('concrete/interface')->button_js(t('Permissions'), 'javascript:ccm_composerLaunchPermissions()', 'left', 'primary ccm-composer-hide-on-no-target')?>
		<?php  } ?>
	<?php  } else { ?>
		<?php echo Loader::helper('concrete/interface')->submit(t('Publish Changes'), 'publish', 'right', 'primary')?>
	<?php  } ?>

	<?php echo Loader::helper('concrete/interface')->button_js(t('Preview'), 'javascript:ccm_composerLaunchPreview()', 'right', 'ccm-composer-hide-on-approved')?>
	<?php echo Loader::helper('concrete/interface')->submit(t('Save'), 'save', 'right')?>
	<?php echo Loader::helper('concrete/interface')->submit(t('Discard'), 'discard', 'left', 'error ccm-composer-hide-on-approved')?>
	
	<?php echo $form->hidden('entryID', $entry->getCollectionID())?>
	<?php  if ($entry->isComposerDraft()) { ?>
		<input type="hidden" name="cPublishParentID" value="<?php echo $entry->getComposerDraftPublishParentID()?>" />
	<?php  } ?>
	<?php echo $form->hidden('autosave', 0)?>
	<?php echo Loader::helper('validation/token')->output('composer')?>
	</div>
	</form>
	<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false)?>


	<script type="text/javascript">
	var ccm_composerAutoSaveInterval = false;
	var ccm_composerDoAutoSaveAllowed = true;
	
	ccm_composerDoAutoSave = function(callback) {
		if (!ccm_composerDoAutoSaveAllowed) {
			return false;
		}
		
		$('input[name=autosave]').val('1');
		try {
			tinyMCE.triggerSave(true, true);
		} catch(e) { }
		
		$('#ccm-dashboard-composer-form').ajaxSubmit({
			'dataType': 'json',
			'success': function(r) {
				$('input[name=autosave]').val('0');
				ccm_composerLastSaveTime = new Date();
				$("#composer-save-status").html('<div class="block-message alert-message info"><p><?php echo t("Page saved at ")?>' + r.time + '</p></div>');
				$(".ccm-composer-hide-on-approved").show();
				if (callback) {
					callback();
				}
			}
		});
	}
	
	ccm_composerLaunchPreview = function() {
		jQuery.fn.dialog.showLoader();
		<?php  $t = PageTheme::getSiteTheme(); ?>
		ccm_composerDoAutoSave(function() {
			ccm_previewInternalTheme(<?php echo $entry->getCollectionID()?>, <?php echo $t->getThemeID()?>, '<?php echo addslashes(str_replace(array("\r","\n","\n"),'',$t->getThemeName()))?>');
		});
	}
	
	ccm_composerSelectParentPage = function(cID) {
		$("input[name=cPublishParentID]").val(cID);
		$(".ccm-composer-hide-on-no-target").show();
		$("#ccm-composer-publish-location").load('<?php echo $this->action("select_publish_target")?>', {'entryID': <?php echo $entry->getCollectionID()?>, 'cPublishParentID': cID});
		jQuery.fn.dialog.closeTop();

	}	

	ccm_composerSelectParentPageAndSubmit = function(cID) {
		$("input[name=cPublishParentID]").val(cID);
		$(".ccm-composer-hide-on-no-target").show();
		$("#ccm-composer-publish-location").load('<?php echo $this->action("select_publish_target")?>', {'entryID': <?php echo $entry->getCollectionID()?>, 'cPublishParentID': cID}, function() {
		 	$("input[name=ccm-submit-publish]").click();
		});
	}	
		
	ccm_composerLaunchPermissions = function(cID) {
		var shref = CCM_TOOLS_PATH + '/edit_collection_popup?ctask=edit_permissions_composer&cID=<?php echo $entry->getCollectionID()?>';
		jQuery.fn.dialog.open({
			title: '<?php echo t("Permissions")?>',
			href: shref,
			width: '640',
			modal: false,
			height: '310'
		});
	}
	
	ccm_composerEditBlock = function(cID, bID, arHandle, w, h) {
		if(!w) w=550;
		if(!h) h=380; 
		var editBlockURL = '<?php echo REL_DIR_FILES_TOOLS_REQUIRED ?>/edit_block_popup';
		$.fn.dialog.open({
			title: ccmi18n.editBlock,
			href: editBlockURL+'?cID='+cID+'&bID='+bID+'&arHandle=' + encodeURIComponent(arHandle) + '&btask=edit',
			width: w,
			modal: false,
			height: h
		});		
	}
	
	ccm_openComposerPublishTargetWindow = function(submitOnChoose) {
		var shref = CCM_TOOLS_PATH + '/composer_target?cID=<?php echo $entry->getCollectionID()?>';
		if (submitOnChoose) {
			shref += '&submitOnChoose=1';
		}
		jQuery.fn.dialog.open({
			title: '<?php echo t("Publish Page")?>',
			href: shref,
			width: '550',
			modal: false,
			height: '400'
		});
	}
	
	$(function() {
		<?php  if (is_object($v) && $v->isApproved()) { ?>
			$(".ccm-composer-hide-on-approved").hide();
		<?php  } ?>

		if ($("input[name=cPublishParentID]").val() < 1) {
			$(".ccm-composer-hide-on-no-target").hide();
		}
		
		var ccm_composerAutoSaveIntervalTimeout = 7000;
		var ccm_composerIsPublishClicked = false;
		
		$("#ccm-submit-discard").click(function() {
			return (confirm('<?php echo t("Discard this draft?")?>'));
		});
		
		$("#ccm-submit-publish").click(function() {
			ccm_composerIsPublishClicked = true;
		});
		
		$("#ccm-dashboard-composer-form").submit(function() {
			ccm_composerDoAutoSaveAllowed = false;
		});
		
		<?php  if ($entry->isComposerDraft()) { ?>
			$("#ccm-dashboard-composer-form").submit(function() {
				if ($("input[name=cPublishParentID]").val() > 0) {
					return true;
				}
				if (ccm_composerIsPublishClicked) {
					ccm_composerIsPublishClicked = false;			
	
					<?php  if ($ct->getCollectionTypeComposerPublishMethod() == 'PAGE_TYPE' || $ct->getCollectionTypeComposerPublishMethod() == 'CHOOSE') { ?>
						ccm_openComposerPublishTargetWindow(true);
						return false;
					<?php  } else if ($ct->getCollectionTypeComposerPublishMethod() == 'PARENT') { ?>
						return true;				
					<?php  } else { ?>
						return false;
					<?php  } ?>
				}
			});
		<?php  } ?>
		ccm_composerAutoSaveInterval = setInterval(function() {
			ccm_composerDoAutoSave();
		}, 
		ccm_composerAutoSaveIntervalTimeout);
		
	});
	</script>
	
	
<?php  } else { ?>

	<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Composer'), false, 'span14 offset1')?>
	
	<?php  if (count($ctArray) > 0) { ?>
	<h3><?php echo t('What type of page would you like to write?')?></h3>
	<ul class="icon-select-list">
	<?php  foreach($ctArray as $ct) { ?>
		<li class="icon-select-page"><a href="<?php echo $this->url('/dashboard/composer/write', $ct->getCollectionTypeID())?>"><?php echo $ct->getCollectionTypeName()?></a></li>
	<?php  } ?>
	</ul>
	<?php  } else { ?>
		<p><?php echo t('You have not setup any page types for Composer.')?></p>
	<?php  } ?>

	
	<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper()?>
	
<?php  } ?>

