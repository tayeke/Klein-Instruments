<?php 
defined('C5_EXECUTE') or die("Access Denied.");

$attribs = UserAttributeKey::getList(true);
$u = new User();
$uh = Loader::helper('concrete/user');
$txt = Loader::helper('text');
$vals = Loader::helper('validation/strings');
$valt = Loader::helper('validation/token');
$valc = Loader::helper('concrete/validation');
$dtt = Loader::helper('form/date_time');
$dh = Loader::helper('date');
$form = Loader::helper('form');
$ih = Loader::helper('concrete/interface');
$av = Loader::helper('concrete/avatar'); 

function printAttributeRow($ak, $uo) {
	
	$vo = $uo->getAttributeValueObject($ak);
	$value = '';
	if (is_object($vo)) {
		$value = $vo->getValue('displaySanitized', 'display');
	}
	
	if ($value == '') {
		$text = '<div class="ccm-attribute-field-none">' . t('None') . '</div>';
	} else {
		$text = $value;
	}
	if ($ak->isAttributeKeyEditable()) { 
	$type = $ak->getAttributeType();
	
	$html = '
	<tr class="ccm-attribute-editable-field">
		<td width="250" style="vertical-align:middle;"><a style="font-weight:bold; line-height:18px;" href="javascript:void(0)">' . $ak->getAttributeKeyDisplayHandle() . '</a></td>
		<td class="ccm-attribute-editable-field-central" style="vertical-align:middle;"><div class="ccm-attribute-editable-field-text">' . $text . '</div>
		<form method="post" style="margin-bottom:0;" action="' . View::url('/dashboard/users/search', 'edit_attribute') . '">
		<input type="hidden" name="uakID" value="' . $ak->getAttributeKeyID() . '" />
		<input type="hidden" name="uID" value="' . $uo->getUserID() . '" />
		<input type="hidden" name="task" value="update_extended_attribute" />
		<div class="ccm-attribute-editable-field-form ccm-attribute-editable-field-type-' . strtolower($type->getAttributeTypeHandle()) . '">
		' . $ak->render('form', $vo, true) . '
		</div>
		</form>
		</td>
		<td class="ccm-attribute-editable-field-save" style="vertical-align:middle; text-align:center;" width="30"><a href="javascript:void(0)"><img src="' . ASSETS_URL_IMAGES . '/icons/edit_small.png" width="16" height="16" class="ccm-attribute-editable-field-save-button" /></a>
		<a href="javascript:void(0)"><img src="' . ASSETS_URL_IMAGES . '/icons/close.png" width="16" height="16" class="ccm-attribute-editable-field-clear-button" /></a>
		<img src="' . ASSETS_URL_IMAGES . '/throbber_white_16.gif" width="16" height="16" class="ccm-attribute-editable-field-loading" />
		</td>
	</tr>';
	
	} else {

	$html = '
	<tr>
		<th>' . $ak->getAttributeKeyDisplayHandle() . '</th>
		<td width="100%" colspan="2">' . $text . '</td>
	</tr>';	
	}
	print $html;
}


if (intval($_GET['uID'])) {
	
	$uo = UserInfo::getByID(intval($_GET['uID']));
	if (is_object($uo)) {
		$uID = intval($_REQUEST['uID']);
		
		if (isset($_GET['task'])) {
			if ($uo->getUserID() == USER_SUPER_ID && (!$u->isSuperUser())) {
				throw new Exception(t('Only the super user may edit this account.'));
			}
		}
		
		if ($_GET['task'] == 'activate') {
			if( !$valt->validate("user_activate") ){
				throw new Exception(t('Invalid token.  Unable to activate user.'));
			}else{		
				$uo->activate();
				$uo = UserInfo::getByID(intval($_GET['uID']));
				$this->controller->redirect('/dashboard/users/search?uID=' . intval($_GET['uID']) . '&activated=1');
			}
		}

		if ($_GET['task'] == 'validate_email') {
			$uo->markValidated();
			$uo = UserInfo::getByID(intval($_GET['uID']));
			$this->controller->redirect('/dashboard/users/search?uID=' . intval($_GET['uID']) . '&validated=1');
		}
		
		
		if ($_GET['task'] == 'remove-avatar') {
			$av->removeAvatar($uo->getUserID());
			$this->controller->redirect('/dashboard/users/search?uID=' . intval($_GET['uID']) . '&task=edit');

		}
		
		if ($_GET['task'] == 'deactivate') {
			if( !$valt->validate("user_deactivate") ){
				throw new Exception(t('Invalid token.  Unable to deactivate user.'));
			}else{
				$uo->deactivate();
				$uo = UserInfo::getByID(intval($_GET['uID']));
				$this->controller->redirect('/dashboard/users/search?uID=' . intval($_GET['uID']) . '&deactivated=1');
			}
		}	
	}
}


if (is_object($uo)) { 
	$gl = new GroupList($uo, true);
	
	if ($_GET['task'] == 'edit' || $_POST['edit'] && !$editComplete) { ?>
    
	<?php 
    $gArray = $gl->getGroupList();
	$uName = ($_POST) ? $_POST['uName'] : $uo->getUserName();
	$uEmail = ($_POST) ? $_POST['uEmail'] : $uo->getUserEmail();
	?>
		
	<script>	
	function editAttrVal(attId,cancel){
		if(!cancel){
			$('#attUnknownWrap'+attId).css('display','none');
			$('#attEditWrap'+attId).css('display','block');
			$('#attValChanged'+attId).val(attId);	
		}else{
			$('#attUnknownWrap'+attId).css('display','block');
			$('#attEditWrap'+attId).css('display','none');
			$('#attValChanged'+attId).val(0);	
		}
	}
	</script>
		
		
	<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Edit User'), t('Edit User account.'), false, false);?>
	
    
	<div class="ccm-pane-body">
    <form method="post" enctype="multipart/form-data" id="ccm-user-form" action="<?php echo $this->url('/dashboard/users/search?uID=' . intval($_GET['uID']) )?>">
	<?php echo $valt->output('update_account_' . intval($_GET['uID']) )?>
	<input type="hidden" name="_disableLogin" value="1">

		<table border="0" cellspacing="0" cellpadding="0" width="100%">
            <thead>
                <tr>
                    <th colspan="3"><?php echo t('User Information')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="35%"><?php echo t('Username')?> <span class="required">*</span></td>
                    <td width="35%"><?php echo t('Email Address')?> <span class="required">*</span></td>
                    <td width="30%"><?php echo t('User Avatar')?></td>
                </tr>	
                <tr>
                    <td><input type="text" name="uName" autocomplete="off" value="<?php echo $uName?>" style="width: 95%"></td>
                    <td><input type="text" name="uEmail" autocomplete="off" value="<?php echo $uEmail?>" style="width: 95%"></td>
                    <td>
                    <?php  if ($uo->hasAvatar()) { ?>
                    <input class="btn error" type="button" onclick="location.href='<?php echo $this->url('/dashboard/users/search?uID=' . intval($uID) . '&task=remove-avatar')?>'" value="<?php echo t('Remove Avatar')?>" />
                    <?php  } else { ?>
                    <input type="file" name="uAvatar" style="width: 95%" /><input type="hidden" name="uHasAvatar" value="<?php echo $uo->hasAvatar()?>" />
                    <?php  } ?>
                    </td>
                </tr>
            </tbody>
		</table>
        
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
        	<thead>
            	<tr>
                	<th colspan="2">
						<?php echo t('Change Password')?>
                        <span style="margin-left: 4px; color: #aaa"><?php echo t('(Leave these fields blank to keep the same password)')?></span>
					</th>
				</tr>
			</thead>
            <tbody>
            	<tr>
					<td><?php echo t('Password')?></td>
					<td><?php echo t('Password (Confirm)')?></td>
				</tr>
                <tr>
                    <td><input type="password" name="uPassword" autocomplete="off" value="" style="width: 95%"></td>
                    <td><input type="password" name="uPasswordConfirm" autocomplete="off" value="" style="width: 95%"></td>
                </tr>
                
				<?php 
                $languages = Localization::getAvailableInterfaceLanguages();
                if (count($languages) > 0) { ?>
	
                <tr>
                    <td colspan="2"><?php echo t('Default Language')?></td>
                </tr>	
				<tr>
                    <td colspan="2">
                    <?php 
                        array_unshift($languages, 'en_US');
                        $locales = array();
                        Loader::library('3rdparty/Zend/Locale');
                        Loader::library('3rdparty/Zend/Locale/Data');
                        $locales[''] = t('** Default');
                        Zend_Locale_Data::setCache(Cache::getLibrary());
                        foreach($languages as $lang) {
                            $loc = new Zend_Locale($lang);
                            $locales[$lang] = Zend_Locale::getTranslation($loc->getLanguage(), 'language', $lang);
                        }
                        $ux = $uo->getUserObject();
                        print $form->select('uDefaultLanguage', $locales, $ux->getUserDefaultLanguage());
                    ?>
                    </td>
				</tr>	
				<?php  } // END Languages options ?>

				<?php  if(ENABLE_USER_TIMEZONES) { ?>
                <tr>
                    <td colspan="2"><?php echo t('Time Zone')?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php  
                        echo $form->select('uTimezone', 
                                $dh->getTimezones(), 
                                ($uo->getUserTimezone()?$uo->getUserTimezone():date_default_timezone_get())
						); ?>
                    </td>
                </tr>
        		<?php  } // END Timezone options ?>
                
			</tbody>
		</table>
    
		<table border="0" cellspacing="0" cellpadding="0" class="zebra-striped">
        	<thead>
            	<tr>
                	<th>
						<span style="line-height:32px;"><?php echo t('Groups')?></span>
                    	<a class="btn small ccm-button-v2-right" id="groupSelector" href="<?php echo REL_DIR_FILES_TOOLS_REQUIRED?>/user_group_selector.php?mode=groups" dialog-title="<?php echo t('Add Groups')?>" dialog-modal="false"><?php echo t('Add Group')?></a>
                    </th>
                </tr>
			</thead>
			<tbody>
            	<tr class="inputs-list">
					<td>
                    
					<?php  foreach ($gArray as $g) { ?>
                    
                            <label>
                                <input type="checkbox" name="gID[]" value="<?php echo $g->getGroupID()?>" <?php  
                                    if (is_array($_POST['gID'])) {
                                        if (in_array($g->getGroupID(), $_POST['gID'])) {
                                            echo(' checked ');
                                        }
                                    } else {
                                        if ($g->inGroup()) {
                                            echo(' checked ');
                                        }
                                    }
                                ?> />
                                <span><?php echo $g->getGroupName()?></span>
                            </label>
                        
                    <?php  } ?> 
                	
                    <div id="ccm-additional-groups"></div>
                    
                	</td>
				</tr>
			</tbody>
		</table>
        
        <input type="hidden" name="edit" value="1" />

    <div class="well">
    	<?php  print $ih->button(t('Back'), $this->url('/dashboard/users/search?uID=' . intval($_GET['uID'])), 'left')?>
		<?php  print $ih->submit(t('Update User'), 'update', 'right', 'primary')?>
    </div>

		</form>
		
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="zebra-striped inputs-list">
        	<thead>
            	<tr>
                	<th colspan="3">
						<?php echo t('Other Information')?>
                    	<span style="margin-left: 4px; color: #aaa">(<?php echo t('Click field name to edit')?>)</span>
                    </th>
				</tr>
			</thead>
			<tbody>
            
				<?php             
                $attribs = UserAttributeKey::getEditableList();
                foreach($attribs as $ak) { 
                    printAttributeRow($ak, $uo);
                }
				?>
                
        	</tbody>
		</table>
		
	</div>
    
    <!-- END User Edit Page -->
    
	<?php  } else { ?>

	<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('View User'), t('View User accounts.'), false, false);?>
	<div class="ccm-pane-options">
		<?php  if ($uo->getUserID() != USER_SUPER_ID || $u->isSuperUser()) { ?>

			<?php  print $ih->button(t('Edit User'), $this->url('/dashboard/users/search?uID=' . intval($uID) ) . '&task=edit', 'left');?>

			<?php  if (USER_VALIDATE_EMAIL == true) { ?>
				<?php  if ($uo->isValidated() < 1) { ?>
				<?php  print $ih->button(t('Mark Email as Valid'), $this->url('/dashboard/users/search?uID=' . intval($uID) . '&task=validate_email'), 'left');?>
				<?php  } ?>
			<?php  } ?>
			
			<?php  if ($uo->getUserID() != USER_SUPER_ID) { ?>
				<?php  if ($uo->isActive()) { ?>
					<?php  print $ih->button(t('Deactivate User'), $this->url('/dashboard/users/search?uID=' . intval($uID) . '&task=deactivate&ccm_token='.$valt->generate('user_deactivate')), 'left');?>
				<?php  } else { ?>
					<?php  print $ih->button(t('Activate User'), $this->url('/dashboard/users/search?uID=' . intval($uID) . '&task=activate&ccm_token='.$valt->generate('user_activate')), 'left');?>
				<?php  } ?>
			<?php  } ?>
		
		<?php  } ?>
		
		<?php 
		$tp = new TaskPermission();
		if ($uo->getUserID() != $u->getUserID()) {
			if ($tp->canSudo()) { 
			
				$loginAsUserConfirm = t('This will end your current session and sign you in as %s', $uo->getUserName());
				
				print $ih->button_js(t('Sign In as User'), 'loginAsUser()', 'left');?>

				<script type="text/javascript">
				loginAsUser = function() {
					if (confirm('<?php echo $loginAsUserConfirm?>')) { 
						location.href = "<?php echo $this->url('/dashboard/users/search', 'sign_in_as_user', $uo->getUserID(), $valt->generate('sudo'))?>";				
					}
				}
				</script>

			<?php  } /*else { ?>
				<?php  print $ih->button_js(t('Sign In as User'), 'alert(\'' . t('You do not have permission to sign in as other users.') . '\')', 'left', 'ccm-button-inactive');?>
			<?php  }*/ ?>
		<?php  } ?>
		
		<?php 
		$cu = new User();
		$tp = new TaskPermission();
		if ($tp->canDeleteUser()) {
		$delConfirmJS = t('Are you sure you want to permanently remove this user?');
			if ($uo->getUserID() == USER_SUPER_ID) { ?>
				<?php echo t('You may not remove the super user account.')?>
			<?php  } else if (!$tp->canDeleteUser()) { ?>
				<?php echo t('You do not have permission to perform this action.');		
			} else if ($uo->getUserID() == $cu->getUserID()) {
				echo t('You cannot delete your own user account.');
			}else{ ?>   
				
				<script type="text/javascript">
				deleteUser = function() {
					if (confirm('<?php echo $delConfirmJS?>')) { 
						location.href = "<?php echo $this->url('/dashboard/users/search', 'delete', $uo->getUserID(), $valt->generate('delete_account'))?>";				
					}
				}
				</script>
	
				<?php  print $ih->button_js(t('Delete User Account'), "deleteUser()", 'left', 'error');?>
	
			<?php  } ?>
		<?php  } ?>
		</div>
		<div class="ccm-pane-body ccm-pane-body-footer" id="ccm-dashboard-user-body">
		
		<?php echo $av->outputUserAvatar($uo)?>
		<h3><?php echo t('Basic Details')?></h3>
		<p><strong><?php echo $uo->getUserName()?></strong>
		<p><a href="mailto:<?php echo $uo->getUserEmail()?>"><?php echo $uo->getUserEmail()?></a></p>
		<p><?php echo t('Account created on %s', $uo->getUserDateAdded('user'))?></p>
		<?php echo (ENABLE_USER_TIMEZONES && strlen($uo->getUserTimezone())?"<p>".t('Timezone').": ".$uo->getUserTimezone() . '</p>':"")?>
		<?php  if (USER_VALIDATE_EMAIL) { ?>
			<p>
			<?php echo t('Full Record')?>: <strong><?php echo  ($uo->isFullRecord()) ? "Yes" : "No" ?></strong>
			&nbsp;&nbsp;
			<?php echo t('Email Validated')?>: <strong><?php 
				switch($uo->isValidated()) {
					case '-1':
						print t('Unknown');
						break;
					case '0':
						print t('No');
						break;
					case '1':
						print t('Yes');
						break;
				}?>
				</strong></p>
				
		<?php  } ?>

		<br/>
		<?php 
		$attribs = UserAttributeKey::getList(true);
		if (count($attribs) > 0) { ?>
		<h3><?php echo t('User Attributes')?></h3>

		<?php  
		for ($i = 0; $i < count($attribs); $i++) { 			
			$uk = $attribs[$i]; 
			
			?>
			
		<div class="row">
		<div class="span5" style=""><p><strong><?php echo $uk->getAttributeKeyDisplayHandle()?></strong></p></div>
		<div class="span5"><p>
			<?php echo $uo->getAttribute($uk->getAttributeKeyHandle(), 'displaySanitized', 'display')?>
		</p></div>
		</div>

		<?php  } ?>
		
		<?php  }  ?>
		
		<br/>
		<h3><?php echo t('Groups')?></h3>

		<?php  $gArray = $gl->getGroupList(); ?>
		<?php  $enteredArray = array(); ?>
		<?php  $groups = 0; ?>
		<?php  foreach ($gArray as $g) { ?>
			<?php  if ($g->inGroup()) { 
				$groups++; ?>

				<div class="row">
				<div class="span5" style=""><p><strong><?php echo $g->getGroupName()?></strong></p></div>
				<div class="span5"><p>
					<?php 
					$dateTime = $g->getGroupDateTimeEntered();
					if ($dateTime != '0000-00-00 00:00:00') {
						echo($dateTime . '<br>');
					} else {
						echo('<br>');
					}?>
					</p></div>
				</div>
			<?php  } ?>
		<?php  } 
		
		if ($groups == 0) { 
			print t('None');
		}
		?>
		
	</div>
	<?php  } ?>


<script type="text/javascript">


ccm_activateEditableProperties = function() {
	$("tr.ccm-attribute-editable-field").each(function() {
		var trow = $(this);
		$(this).find('a').click(function() {
			trow.find('.ccm-attribute-editable-field-text').hide();
			trow.find('.ccm-attribute-editable-field-clear-button').hide();
			trow.find('.ccm-attribute-editable-field-form').show();
			trow.find('.ccm-attribute-editable-field-save-button').show();
		});
		
		trow.find('form').submit(function() {
			ccm_submitEditableProperty(trow);
			return false;
		});
		
		trow.find('.ccm-attribute-editable-field-save-button').parent().click(function() {
			ccm_submitEditableProperty(trow);
		});

		trow.find('.ccm-attribute-editable-field-clear-button').parent().unbind();
		trow.find('.ccm-attribute-editable-field-clear-button').parent().click(function() {
			trow.find('form input[name=task]').val('clear_extended_attribute');
			ccm_submitEditableProperty(trow);
			return false;
		});

	});
}

ccm_submitEditableProperty = function(trow) {
	trow.find('.ccm-attribute-editable-field-save-button').hide();
	trow.find('.ccm-attribute-editable-field-clear-button').hide();
	trow.find('.ccm-attribute-editable-field-loading').show();
	try {
		tinyMCE.triggerSave(true, true);
	} catch(e) { }
	
	trow.find('form').ajaxSubmit(function(resp) {
		// resp is new HTML to display in the div
		trow.find('.ccm-attribute-editable-field-loading').hide();
		trow.find('.ccm-attribute-editable-field-save-button').show();
		trow.find('.ccm-attribute-editable-field-text').html(resp);
		trow.find('.ccm-attribute-editable-field-form').hide();
		trow.find('.ccm-attribute-editable-field-save-button').hide();
		trow.find('.ccm-attribute-editable-field-text').show();
		trow.find('.ccm-attribute-editable-field-clear-button').show();
		trow.find('td').show('highlight', {
			color: '#FFF9BB'
		});

	});
}

$(function() {
	ccm_activateEditableProperties();
	$("#groupSelector").dialog();
	ccm_triggerSelectGroup = function(gID, gName) {
		var html = '<label><input type="checkbox" name="gID[]" value="' + gID + '" checked /> <span>' + gName + '</span>';
		$("#ccm-additional-groups").append(html);
	}

});
</script>


<?php 

} else { ?>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Search Users'), t('Search the users of your site and perform bulk actions on them.'), false, false);?>

<?php 
$tp = new TaskPermission();
if ($tp->canAccessUserSearch()) { ?>

<div class="ccm-pane-options" id="ccm-<?php echo $searchInstance?>-pane-options">
<?php  Loader::element('users/search_form_advanced', array('columns' => $columns, 'searchInstance' => $searchInstance, 'searchRequest' => $searchRequest, 'searchType' => 'DASHBOARD')); ?>
</div>

<?php  Loader::element('users/search_results', array('columns' => $columns, 'searchInstance' => $searchInstance, 'searchType' => 'DASHBOARD', 'users' => $users, 'userList' => $userList, 'pagination' => $pagination)); ?>

<?php  } else { ?>
<div class="ccm-pane-body">
	<p><?php echo t('You do not have access to user search. This setting may be changed in the access section of the dashboard settings page.')?></p>
</div>	

<?php  } ?>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>

<?php  } ?>