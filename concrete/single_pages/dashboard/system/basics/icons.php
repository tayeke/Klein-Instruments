<?php  defined('C5_EXECUTE') or die("Access Denied.");?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Bookmark Icons'), false, 'span12 offset2')?>


	<form method="post" id="favicon-form" action="<?php echo $this->action('update_favicon')?>" enctype="multipart/form-data" >


	<?php echo $this->controller->token->output('update_favicon')?>
	<input id="remove-existing-favicon" name="remove_favicon" type="hidden" value="0" />
	<fieldset>
		<legend><?php echo t('Favicon')?></legend>

	<?php 
	$favIconFID=intval(Config::get('FAVICON_FID'));
	if($favIconFID){
		$f = File::getByID($favIconFID);
		?>
		<div class="clearfix">
		<label><?php echo t('Selected Icon')?></label>
		<div class="input">
			<img src="<?php echo $f->getRelativePath() ?>" />
		</div>
		</div>
		<div class="clearfix">
		<label></label>
		<div class="input">
			<a href="javascript:void(0)" class="btn danger" onclick="removeFavIcon()"><?php echo t('Remove')?></a>
		</div>
		</div>
		
		<script>
		function removeFavIcon(){
			document.getElementById('remove-existing-favicon').value=1;
			$('#favicon-form').get(0).submit();
		}
		</script>
	<?php  }else{ ?>
	

	
		<div class="clearfix">
			<label for="favicon_upload"><?php echo t('Upload File')?></label>
			<div class="input">
				<input id="favicon_upload" type="file" class="input-file" name="favicon_file"/>
				<div class="help-block"><?php echo t('Your image should be 16x16 pixels, and should be an gif or a png with a .ico file extension.')?></div>

			</div>
		</div>

		<div class="clearfix">
			<label></label>
			<div class="input">
				<?php 
				print $interface->submit(t('Upload'), 'favicon-form', 'left');
				?>
			
			</div>
		</div>

	<?php  } ?>
	</fieldset>

	</form>
	
		
	
	<br/><br/>

	<form method="post" id="iphone-form" action="<?php echo $this->action('update_iphone_thumbnail')?>" enctype="multipart/form-data" >
	<?php echo $this->controller->token->output('update_iphone_thumbnail')?>
	<input id="remove-existing-iphone-thumbnail" name="remove_icon" type="hidden" value="0" />

	<fieldset>
		<legend><?php echo t('iPhone Thumbnail')?></legend>
	
	
	<?php 
	$favIconFID=intval(Config::get('IPHONE_HOME_SCREEN_THUMBNAIL_FID'));
	if($favIconFID){
		$f = File::getByID($favIconFID);
		?>
		<div class="clearfix">
		<label><?php echo t('Selected Icon')?></label>
		<div class="input">
			<img src="<?php echo $f->getRelativePath() ?>" />
		</div>
		</div>
		<div class="clearfix">
		<label></label>
		<div class="input">
			<a href="javascript:void(0)" class="btn danger" onclick="removeIphoneThumbnail()"><?php echo t('Remove')?></a>
		</div>
		</div>
		
		<script>
		function removeIphoneThumbnail(){
			document.getElementById('remove-existing-iphone-thumbnail').value=1;
			$('#iphone-form').get(0).submit();
		}
		</script>
		
	<?php  } else { ?>

		<div class="clearfix">
			<label for="favicon_upload"><?php echo t('Upload File')?></label>
			<div class="input">
				<input id="favicon_upload" type="file" class="input-file" name="favicon_file"/>
				<div class="help-block"><?php echo t('iPhone home screen icons should be 57x57 and be in the .png format.')?></div>

			</div>
		</div>

		<div class="clearfix">
			<label></label>
			<div class="input">
				<?php 
				print $interface->submit(t('Upload'), 'favicon-form', 'left');
				?>
			
			</div>
		</div>
	<?php  } ?>
		
	</fieldset>
	
	</form>




<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper();?>