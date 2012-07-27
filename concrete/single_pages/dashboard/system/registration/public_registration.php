<?php  defined('C5_EXECUTE') or die("Access Denied.");?>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Public Registration'), t('Control the options available for Public Registration.'), 'span10 offset3', false);?>
<?php 
$h = Loader::helper('concrete/interface');
?>	
    <form class="form-stacked" method="post" id="registration-type-form" action="<?php  echo $this->url('/dashboard/system/registration/public_registration', 'update_registration_type')?>">  
    
    <div class="ccm-pane-body"> 
    	
    	<div class="clearfix">
            <label id="optionsCheckboxes"><strong><?php  echo t('Allow visitors to signup as site members?')?></strong></label>
            <div class="input">
			  <ul class="inputs-list">
			    <li>
			      <label>
			        <input type="radio" name="registration_type" value="disabled" style="" <?php  echo ( $registration_type == "disabled" || !strlen($registration_type) )?'checked':''?> />
			        <span><?php  echo t('Off')?></span>
			      </label>
			    </li> 
			    <li>
			      <label>
			        <input type="radio" name="registration_type" value="validate_email" style="" <?php  echo ( $registration_type == "validate_email" )?'checked':''?> />
			        <span><?php  echo t(' On - email validation')?></span>
			      </label>
			    </li>
			    <li>
			      <label>
			        <input type="radio" name="registration_type" value="manual_approve" style="" <?php  echo ( $registration_type == "manual_approve" )?'checked':''?> />
			        <span><?php  echo t('On - approve manually')?></span>
			      </label>
			    </li>
			    <li>
			      <label>
			        <input type="radio" name="registration_type" value="enabled" style="" <?php  echo ( $registration_type == "enabled" )?'checked':''?> />
			        <span><?php  echo t('On - signup and go')?></span>
			      </label>
			    </li>  
			  </ul>
			</div>
		</div>  
		
		<div class="clearfix">
            <label id="optionsCheckboxes"><strong><?php  echo t('Options')?></strong></label>
            <div class="input">
			  <ul class="inputs-list">
			    <li>
			      <label>
			        <input type="checkbox" name="enable_registration_captcha" value="1" style="" <?php  echo ( $enable_registration_captcha )?'checked':''?> />
			        <span><?php  echo t('CAPTCHA required')?></span>
			      </label>
			    </li>
			    <li>
			      <label>
			        <input type="checkbox" name="enable_openID" value="1" style="" <?php  echo ( $enable_openID )?'checked':''?> />
			        <span><?php  echo t('Enable OpenID')?></span>
			      </label>
			    </li>
			    <li>
			      <label>
			       <input type="checkbox" name="email_as_username" value="1" style="" <?php  echo ( $email_as_username )?'checked':''?> />
			        <span><?php  echo t('Use emails for login')?></span>
			      </label>
			    </li>  
			  </ul>
			</div>
        </div>  
	</div>
<div class="ccm-pane-footer">
<?php  print $h->submit(t('Save'), 'registration-type-form', 'right', 'primary'); ?>
</div>
</form> 	
    
   
 <script type="text/javascript">
 $(function() {
 	var val = $("input[name=registration_type]:checked").val();
	if (val == 'disabled') {
		$("input[name=enable_registration_captcha]").attr('disabled', true);
	}
	$("input[name=registration_type]").click(function() {
		if ($(this).val() == 'disabled') { 
			$("input[name=enable_registration_captcha]").attr('disabled', true);
			$("input[name=enable_registration_captcha]").attr('checked', false);
		} else {
			$("input[name=enable_registration_captcha]").attr('disabled', false);
		}	
	});
 });
 </script>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>