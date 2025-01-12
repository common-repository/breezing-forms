<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.8
* @package BreezingForms
* @copyright (C) 2008-2012 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

JToolBarHelper::title('<img src="'. JURI::root() . 'administrator/components/com_breezingforms/libraries/jquery/themes/easymode/i/logo-breezingforms.png'.'" align="top"/>');

jimport('joomla.version');
$version = new JVersion();



class HTML_facileFormsForm
{
	public static function edit($option, $tabpane, $pkg, &$row, &$lists, $caller)
	{
		global $ff_mossite, $ff_admsite, $ff_config;
		$action = $row->id ? BFText::_('COM_BREEZINGFORMS_FORMS_EDIT') : BFText::_('COM_BREEZINGFORMS_FORMS_ADD');
?>
                
		<script type="text/javascript" src="<?php echo WP_PLUGIN_URL;?>/<?php echo BF_FOLDER;?>/platform/administrator/components/com_breezingforms/admin/areautils.js"></script>
		<script type="text/javascript">
		<!--
                window.addEvent('domready', function(){ $$('dl.tabs').each(function(tabs){ new JTabs(tabs, {}); }); });
        
		function checkNumber(value, msg1, msg2)
		{
			var nonDigits = /\D/;
			var error = '';
			if (value == '')
				error += msg1;
			else
			if (nonDigits.test(value))
				error += msg2;
			return error;
		} // checkNumber

		function checkIdentifier(value, msg1, msg2)
		{
			var invalidChars = /\W/;
			var error = '';
			if (value == '')
				error += msg1;
			else
				if (invalidChars.test(value))
					error += msg2;
			return error;
		} // checkIdentifier

		var bf_submitbutton = function(pressbutton)
		{
                        var form = document.adminForm;
			var error = '';
			if (pressbutton != 'cancel') {
				if (form.title.value == '')
					error += "<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TITLEEMPTY'); ?>\n";
				error += checkIdentifier(
					form.name.value,
					"<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NAMEEMPTY'); ?>\n",
					"<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NAMEIDENT'); ?>\n"
				);
				error += checkNumber(
					form.width.value,
					"<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_WIDTHEMPTY'); ?>\n",
					"<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_WIDTHNUMBER'); ?>\n"
				);
				error += checkNumber(
					form.height.value,
					"<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_HEIGHTEMPTY'); ?>\n",
					"<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_HEIGHTNUMBER'); ?>\n"
				);
			} // if
			if (error != '')
				alert(error);
			else{
				if( typeof parent != "undefined" && pressbutton == 'cancel'){
					if(typeof parent.SqueezeBox != "undefined"){
						parent.SqueezeBox.close();
					} else {
						Joomla.submitform( pressbutton );
					}
				} else {
					Joomla.submitform( pressbutton );
				}	
			}
		}; // submitbutton

                if(typeof Joomla != 'undefined'){
                    Joomla.submitbutton = bf_submitbutton;
                }

                submitbutton = bf_submitbutton;

		function createInitCode()
		{
			form = document.adminForm;
			name = form.name.value;
			if (name=='') {
				alert("<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ENTNAMEFIRST'); ?>");
				return;
			} // if
			if (!confirm("<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CREATEINITNOW'); ?>\n<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_EXISTINGAPPENDED'); ?>")) return;
			code =
				"function ff_"+name+"_init()\n"+
				"{\n"+
				"} // ff_"+name+"_init\n";
			oldcode = form.script1code.value;
			if (oldcode != '')
				form.script1code.value =
					code+
					"\n// -------------- <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_OLDCODEBELOW'); ?> --------------\n\n"+
					oldcode;
			else
				form.script1code.value = code;
			codeAreaChange(form.script1code);
		} // createInitCode

		function createSubmittedCode()
		{
			form = document.adminForm;
			name = form.name.value;
			if (name=='') {
				alert("<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ENTNAMEFIRST'); ?>");
				return;
			} // if
			if (!confirm("<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CREATESUBMITTEDNOW'); ?>\n<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_EXISTINGAPPENDED'); ?>")) return;
			code =
				"function ff_"+name+"_submitted(status, message)\n"+
				"{\n"+
				"    switch (status) {\n"+
				"        case FF_STATUS_OK:\n"+
				"           // do whatever desired on success\n"+
				"           break;\n"+
				"        case FF_STATUS_UNPUBLISHED:\n"+
				"        case FF_STATUS_SAVERECORD_FAILED:\n"+
				"        case FF_STATUS_SAVESUBRECORD_FAILED:\n"+
				"        case FF_STATUS_UPLOAD_FAILED:\n"+
				"        case FF_STATUS_ATTACHMENT_FAILED:\n"+
				"        case FF_STATUS_SENDMAIL_FAILED:\n"+
				"        default:\n"+
				"           alert(message);\n"+
				"    } // switch\n"+
				"} // ff_"+name+"_submitted\n";
			oldcode = form.script2code.value;
			if (oldcode != '')
				form.script2code.value =
					code+
					"\n// -------------- <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_OLDCODEBELOW'); ?> --------------\n\n"+
					oldcode;
			else
				form.script2code.value = code;
			codeAreaChange(form.script2code);
		} // createSubmittedCode

		function dispheight(value)
		{
			switch (value) {
				case '0':
					document.getElementById('heightmargin').style.display = 'none';
					break;
				default:
					document.getElementById('heightmargin').style.display = '';
			} // switch
		} // dispheight

		function dispprevwidth()
		{
			var form = document.adminForm;
			if (form.widthmode.value=='0' || form.prevmode.value=='0')
				document.getElementById('prevwidthvalue').style.display = 'none';
			else
				document.getElementById('prevwidthvalue').style.display = '';
		} // dispprevwidth

		function dispinit(value)
		{
			if(document.getElementById) {
				switch (value) {
					case '1':
						document.getElementById('initlib').style.display = '';
						document.getElementById('initcode').style.display = 'none';
						break;
					case '2':
						document.getElementById('initlib').style.display = 'none';
						document.getElementById('initcode').style.display = '';
						break;
					default:
						document.getElementById('initlib').style.display = 'none';
						document.getElementById('initcode').style.display = 'none';
				} // switch
			} // if
		} // dispinit

		function dispsubmitted(value)
		{
			if(document.getElementById) {
				switch (value) {
					case '1':
						document.getElementById('submittedlib').style.display = '';
						document.getElementById('submittedcode').style.display = 'none';
						break;
					case '2':
						document.getElementById('submittedlib').style.display = 'none';
						document.getElementById('submittedcode').style.display = '';
						break;
					default:
						document.getElementById('submittedlib').style.display = 'none';
						document.getElementById('submittedcode').style.display = 'none';
				} // switch
			} // if
		} // dispsubmitted

		function dispemail(value)
		{
			if(document.getElementById) {
				switch (value) {
					case '0':
						document.getElementById('emaillogging').style.display = 'none';
						document.getElementById('emailattachment').style.display = 'none';
						document.getElementById('emailaddress').style.display = 'none';
						break;
					case '1':
						document.getElementById('emaillogging').style.display = '';
						document.getElementById('emailattachment').style.display = '';
						document.getElementById('emailaddress').style.display = 'none';
						break;
					default:
						document.getElementById('emaillogging').style.display = '';
						document.getElementById('emailattachment').style.display = '';
						document.getElementById('emailaddress').style.display = '';
				} // switch
			} // if
		} // dispemail

		function dispp1(value)
		{
			if(document.getElementById) {
				switch (value) {
					case '1':
						document.getElementById('p1lib').style.display = '';
						document.getElementById('p1code').style.display = 'none';
						break;
					case '2':
						document.getElementById('p1lib').style.display = 'none';
						document.getElementById('p1code').style.display = '';
						break;
					default:
						document.getElementById('p1lib').style.display = 'none';
						document.getElementById('p1code').style.display = 'none';
				} // switch
			} // if
		} // dispp1

		function dispp2(value)
		{
			if(document.getElementById) {
				switch (value) {
					case '1':
						document.getElementById('p2lib').style.display = '';
						document.getElementById('p2code').style.display = 'none';
						break;
					case '2':
						document.getElementById('p2lib').style.display = 'none';
						document.getElementById('p2code').style.display = '';
						break;
					default:
						document.getElementById('p2lib').style.display = 'none';
						document.getElementById('p2code').style.display = 'none';
				} // switch
			} // if
		} // dispp2

		function dispp3(value)
		{
			if(document.getElementById) {
				switch (value) {
					case '1':
						document.getElementById('p3lib').style.display = '';
						document.getElementById('p3code').style.display = 'none';
						break;
					case '2':
						document.getElementById('p3lib').style.display = 'none';
						document.getElementById('p3code').style.display = '';
						break;
					default:
						document.getElementById('p3lib').style.display = 'none';
						document.getElementById('p3code').style.display = 'none';
				} // switch
			} // if
		} // dispp3

		function dispp4(value)
		{
			if(document.getElementById) {
				switch (value) {
					case '1':
						document.getElementById('p4lib').style.display = '';
						document.getElementById('p4code').style.display = 'none';
						break;
					case '2':
						document.getElementById('p4lib').style.display = 'none';
						document.getElementById('p4code').style.display = '';
						break;
					default:
						document.getElementById('p4lib').style.display = 'none';
						document.getElementById('p4code').style.display = 'none';
				} // switch
			} // if
		} // dispp4

		onload = function()
		{
<?php
			if ($row->script1cond!=0) echo "\t\t\tdispinit('".$row->script1cond."');\n";
			if ($row->script2cond!=0) echo "\t\t\tdispsubmitted('".$row->script2cond."');\n";
			if ($row->piece1cond !=0) echo "\t\t\tdispp1('".$row->piece1cond."');\n";
			if ($row->piece2cond !=0) echo "\t\t\tdispp2('".$row->piece2cond."');\n";
			if ($row->piece3cond !=0) echo "\t\t\tdispp3('".$row->piece3cond."');\n";
			if ($row->piece4cond !=0) echo "\t\t\tdispp4('".$row->piece4cond."');\n";
			switch ($tabpane) {
				case 1:
				case 2:
				case 3:
					echo "\t\t\ttabPane1.setSelectedIndex($tabpane);\n";
					break;
				default:
					echo "\t\t\tdocument.adminForm.title.focus();\n";
			} // switch
?>
			codeAreaAdd('script1code', 'script1lines');
			codeAreaAdd('script2code', 'script2lines');
			codeAreaAdd('piece1code',  'piece1lines');
			codeAreaAdd('piece2code',  'piece2lines');
			codeAreaAdd('piece3code',  'piece3lines');
			codeAreaAdd('piece4code',  'piece4lines');
		} // onload
		//-->
		</script>
		<div style="float:right;">
                <button class="button-primary" onclick="bf_submitbutton('save');"><?php echo htmlentities(BFText::_('COM_BREEZINGFORMS_TOOLBAR_SAVE'), ENT_QUOTES, 'UTF-8'); ?></button>
		<button class="button-secondary" onclick="location.href='admin.php?page=breezingforms&format=html&act=quickmode&formName=<?php echo $row->name; ?>&form=<?php echo $row->id; ?>'"><?php echo htmlentities(BFText::_('COM_BREEZINGFORMS_TOOLBAR_CANCEL'), ENT_QUOTES, 'UTF-8'); ?></button>
                </div>
                <form action="admin.php?page=breezingforms&format=html" method="post" name="adminForm" id="adminForm" class="adminForm">
		<div style="clear:both;"></div>
                <table border="0" style="width:100%;">
			<tr>
				<td></td>
				<td>
<?php
		$tabs = new BFTabs(0);
		$tabs->startPane('editPane');
		$tabs->startTab(BFText::_('COM_BREEZINGFORMS_FORMS_SETTINGS'),'tab_settings');
?>
			<table class="adminform">
			<tr>
				<td width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TITLE'); ?>:</td>
				<td>
					<input type="text" size="50" maxlength="50" name="title" value="<?php echo $row->title; ?>" class="inputbox"/>

				</td>
                                <td></td>
			</tr>

			<tr>
				<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PACKAGE'); ?>:</td>
				<td>
					<input type="text" size="30" maxlength="30" id="package" name="package" value="<?php echo $row->package; ?>" class="inputbox"/>
				</td>
                                <td></td>
			</tr>

			<tr>
				<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NAME'); ?>:</td>
				<td>
					<input type="text" size="30" maxlength="30" name="name" value="<?php echo $row->name; ?>" class="inputbox"/>

				</td>
                                <td></td>
			</tr>
			<tr>
				<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ORDERING'); ?>:</td>
				<td><?php echo $lists['ordering']; ?></td>
			</tr>
			<tr>
				<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PUBLISHED'); ?>:</td>
				<td><?php echo JHTML::_('select.booleanlist',  "published", "", $row->published); ?></td>
				<td>
                                    <input type="hidden" name="class1" value="<?php echo $row->class1; ?>"/>
                                    <input type="hidden" name="class2" value="<?php echo $row->class2; ?>"/>
                                    <input type="hidden" name="runmode" value="1"/>
                                    <input type="hidden" name="widthmode" value="<?php echo $row->widthmode; ?>"/>
                                    <input type="hidden" name="width" value="<?php echo $row->width; ?>"/>
                                    <input type="hidden" name="heightmode" value="<?php echo $row->heightmode; ?>"/>
                                    <input type="hidden" name="height" value="<?php echo $row->height; ?>"/>
                                </td>
			</tr>
<?php
if($row->template_code == ''){
?>
			<tr>
				<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PREVMODE'); ?>:</td>
				<td>
					<select name="prevmode" size="1" onchange="dispprevwidth();" class="inputbox">
						<option value="0"<?php if ($row->prevmode==0) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONE'); ?></option>
						<option value="1"<?php if ($row->prevmode==1) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_BELOW'); ?></option>
						<option value="2"<?php if ($row->prevmode==2) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_OVERLAYED'); ?></option>
					</select>
					<span id="prevwidthvalue"<?php if ($row->widthmode==0 || $row->prevmode==0) echo ' style="display:none;"'; ?>>
						&nbsp;&nbsp;&nbsp;&nbsp;<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_WIDTH'); ?>: <input size="6" maxlength="6" name="prevwidth" value="<?php echo $row->prevwidth; ?>" class="inputbox" /> px
					</span>
				</td>
				<td></td>
			</tr>
<?php
}
?>
			<tr>
				<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_LOGTODB'); ?>:</td>
				<td>
					<select name="dblog" size="1" class="inputbox">
						<option value="0"<?php if ($row->dblog==0) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NO'); ?></option>
						<option value="1"<?php if ($row->dblog==1) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONEMPTY'); ?></option>
						<option value="2"<?php if ($row->dblog==2) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ALLVALS'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>
			

                        <tr>
				<td colspan="2">
					<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_DESCRIPTION'); ?>:
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
					<a href="#" onClick="textAreaResize('description',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
					<br/>
					<textarea wrap="off" name="description" style="width:700px;" rows="<?php echo $ff_config->areasmall; ?>" class="inputbox"><?php echo htmlspecialchars($row->description, ENT_QUOTES); ?></textarea>
				</td>
				<td></td>
			</tr>

			</table>

<?php

		$tabs->endTab();
		$tabs->startTab(BFText::_('COM_BREEZINGFORMS_ADMIN_EMAILS'),'tab_admin_emails');
?>
                <table class="adminform">
                <tr>
				
				<td  width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_EMAILNOTIFY'); ?>:</td>
				<td>
					<select name="emailntf" size="1" onchange="dispemail(this.value);" class="inputbox">
						<option value="0"<?php if ($row->emailntf==0) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NO'); ?></option>
						<option value="1"<?php if ($row->emailntf==1) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_DEFADDR'); ?></option>
						<option value="2"<?php if ($row->emailntf==2) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CUSTADDR'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr id="emailaddress"<?php if ($row->emailntf!=2) echo ' style="display:none;"'; ?>>
							<td width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_EMAIL'); ?>:</td>
							<td><input size="50" type="text" name="emailadr" value="<?php echo $row->emailadr; ?>" class="inputbox"/></td>
						</tr>
						<tr id="emaillogging"<?php if ($row->emailntf==0) echo ' style="display:none;"'; ?>>
							<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_REPORT'); ?>:</td>
							<td>
								<select name="emaillog" size="1" class="inputbox">
									<option value="0"<?php if ($row->emaillog==0) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_HDRONLY'); ?></option>
									<option value="1"<?php if ($row->emaillog==1) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONEMPTY'); ?></option>
									<option value="2"<?php if ($row->emaillog==2) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ALLVALS'); ?></option>
								</select>
							</td>
						</tr>
						<tr id="emailattachment"<?php if ($row->emailntf==0) echo ' style="display:none;"'; ?>>
							<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ATTACHMENT'); ?>: </td>
							<td>
								<select name="emailxml" size="1" class="inputbox">
									<option value="0"<?php if ($row->emailxml==0) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NO'); ?></option>
									<option value="1"<?php if ($row->emailxml==1) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_XML'); ?></option>
									<!--<option value="2"<?php if ($row->emailxml==2) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_XML_ALLVALS'); ?></option>-->
									<option value="3"<?php if ($row->emailxml==3) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_CSV'); ?></option>
									<option value="4"<?php if ($row->emailxml==4) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_PDF'); ?></option>
								</select>
							</td>
						</tr>
					</table>
				</td>
				<td></td>
			</tr>
                        <tr>
				
				<td><?php echo BFText::_('COM_BREEZINGFORMS_ALT_MAILFROM'); ?>:</td>
				<td>
					<input type="text" name="alt_mailfrom"  value="<?php echo $row->alt_mailfrom; ?>" size="50"  class="inputbox"/>
				</td>
				<td></td>
			</tr>
                        <tr>
				
				<td><?php echo BFText::_('COM_BREEZINGFORMS_ALT_FROMNAME'); ?>:</td>
				<td>
					<input type="text" name="alt_fromname"  value="<?php echo $row->alt_fromname; ?>" size="50"  class="inputbox"/>
				</td>
				<td></td>
			</tr>
			<tr>
				
				<td><?php echo BFText::_('COM_BREEZINGFORMS_CUSTOM_MAIL_SUBJECT'); ?>:</td>
				<td>
					<input type="text" name="custom_mail_subject"  value="<?php echo $row->custom_mail_subject; ?>" size="50"  class="inputbox"/>
				</td>
				<td></td>
			</tr>

                        <tr>
                            <td valign="top"><?php echo BFText::_('COM_BREEZINGFORMS_EDIT_EMAILS'); ?>:
                            <br/>
                            <br/>
                            <div style="height: 250px; overflow: auto;<?php echo $row->email_type == 0 ? ' display: none;' : '' ?>" id="email_custom_template_picker">
                            <?php echo bf_getFieldSelectorList($row->id,'email_custom_template');?>
                            </div>
                            </td>
                            <td valign="top">
					<input onclick="document.getElementById('email_custom_html').style.display='none';document.getElementById('email_custom_template').style.display='none';document.getElementById('email_custom_template_picker').style.display='none';" type="radio" name="email_type" value="0"<?php echo $row->email_type == 0 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_EMAIL_TYPE_DEFAULT');?>
                                        <input onclick="document.getElementById('email_custom_html').style.display='';document.getElementById('email_custom_template').style.display='';document.getElementById('email_custom_template_picker').style.display='';" type="radio" name="email_type" value="1"<?php echo $row->email_type == 1 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_EMAIL_TYPE_CUSTOM');?>
                                        <div id="email_custom_html" style="<?php echo $row->email_type == 0 ? ' display: none;' : '' ?>">
                                            <br/>
                                            <?php echo BFText::_('COM_BREEZINGFORMS_EMAIL_CUSTOM_HTML');?>:
                                            <input type="radio" name="email_custom_html" value="0"<?php echo $row->email_custom_html == 0 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?>
                                            <input type="radio" name="email_custom_html" value="1"<?php echo $row->email_custom_html == 1 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?>
                                        </div>
                                        <br/>
                                        <textarea style="width:100%; height: 500px;<?php echo $row->email_type == 0 ? ' display: none;' : '' ?>" name="email_custom_template" id="email_custom_template"><?php echo htmlentities($row->email_custom_template, ENT_QUOTES, 'UTF-8');?></textarea>
                            </td>
                            <td></td>
			</tr>

                </table>


<?php

		$tabs->endTab();
		$tabs->startTab(BFText::_('COM_BREEZINGFORMS_MAILBACK_EMAILS'),'tab_mailback_emails');
?>
                <table class="adminform">
			<tr>
                            <td valign="top" width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_EMAILNOTIFY'); ?>:</td>
				
				<td valign="top">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr id="bf_emaillogging"<?php if ($row->mb_emailntf==0) echo ' style="display:none;"'; ?>>
							<td width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_REPORT'); ?>:</td>
							<td>
								<select name="mb_emaillog" size="1" class="inputbox">
									<option value="0"<?php if ($row->mb_emaillog==0) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_HDRONLY'); ?></option>
									<option value="1"<?php if ($row->mb_emaillog==1) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONEMPTY'); ?></option>
									<option value="2"<?php if ($row->mb_emaillog==2) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ALLVALS'); ?></option>
								</select>
							</td>
						</tr>
						<tr id="bf_emailattachment"<?php if ($row->mb_emailntf==0) echo ' style="display:none;"'; ?>>
							<td><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ATTACHMENT'); ?>: </td>
							<td>
								<select name="mb_emailxml" size="1" class="inputbox">
									<option value="0"<?php if ($row->mb_emailxml==0) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NO'); ?></option>
									<option value="1"<?php if ($row->mb_emailxml==1) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_XML'); ?></option>
									<!--<option value="2"<?php if ($row->mb_emailxml==2) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_XML_ALLVALS'); ?></option>-->
									<option value="3"<?php if ($row->mb_emailxml==3) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_CSV'); ?></option>
									<option value="4"<?php if ($row->mb_emailxml==4) echo ' selected="selected"'; ?>><?php echo BFText::_('COM_BREEZINGFORMS_PDF'); ?></option>
								</select>
							</td>
						</tr>
					</table>
				</td>
				<td></td>
			</tr>
                        <tr>
				
				<td><?php echo BFText::_('COM_BREEZINGFORMS_ALT_MAILFROM'); ?>:</td>
				<td>
					<input type="text" name="mb_alt_mailfrom"  value="<?php echo $row->mb_alt_mailfrom; ?>" size="50"  class="inputbox"/>
				</td>
				<td></td>
			</tr>
                        <tr>
				
				<td><?php echo BFText::_('COM_BREEZINGFORMS_ALT_FROMNAME'); ?>:</td>
				<td>
					<input type="text" name="mb_alt_fromname"  value="<?php echo $row->mb_alt_fromname; ?>" size="50"  class="inputbox"/>
				</td>
				<td></td>
			</tr>
			<tr>
				
				<td><?php echo BFText::_('COM_BREEZINGFORMS_CUSTOM_MAIL_SUBJECT'); ?>:</td>
				<td>
					<input type="text" name="mb_custom_mail_subject"  value="<?php echo $row->mb_custom_mail_subject; ?>" size="50"  class="inputbox"/>
				</td>
				<td></td>
			</tr>

                        <tr>
                            <td valign="top"><?php echo BFText::_('COM_BREEZINGFORMS_EDIT_EMAILS'); ?>:
                            <br/>
                            <br/>
                            <div style="height: 250px; overflow: auto;<?php echo $row->mb_email_type == 0 ? ' display: none;' : '' ?>" id="mb_email_custom_template_picker">
                            <?php echo bf_getFieldSelectorList($row->id,'mb_email_custom_template');?>
                            </div>
                            </td>
                            <td valign="top">
					<input onclick="document.getElementById('mb_email_custom_html').style.display='none';document.getElementById('mb_email_custom_template').style.display='none';document.getElementById('mb_email_custom_template_picker').style.display='none';" type="radio" name="mb_email_type" value="0"<?php echo $row->mb_email_type == 0 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_EMAIL_TYPE_DEFAULT');?>
                                        <input onclick="document.getElementById('mb_email_custom_html').style.display='';document.getElementById('mb_email_custom_template').style.display='';document.getElementById('mb_email_custom_template_picker').style.display='';" type="radio" name="mb_email_type" value="1"<?php echo $row->mb_email_type == 1 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_EMAIL_TYPE_CUSTOM');?>
                                        <div id="mb_email_custom_html" style="<?php echo $row->mb_email_type == 0 ? ' display: none;' : '' ?>">
                                            <br/>
                                            <?php echo BFText::_('COM_BREEZINGFORMS_EMAIL_CUSTOM_HTML');?>:
                                            <input type="radio" name="mb_email_custom_html" value="0"<?php echo $row->mb_email_custom_html == 0 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?>
                                            <input type="radio" name="mb_email_custom_html" value="1"<?php echo $row->mb_email_custom_html == 1 ? ' checked="checked"' : ''?>/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?>
                                        </div>
                                        <br/>
                                        <textarea style="width:100%; height: 500px;<?php echo $row->mb_email_type == 0 ? ' display: none;' : '' ?>" name="mb_email_custom_template" id="mb_email_custom_template"><?php echo htmlentities($row->mb_email_custom_template, ENT_QUOTES, 'UTF-8');?></textarea>
                            </td>
                            <td></td>
			</tr>

                </table>


<?php
		if($row->template_code != '' ){
?>
<input type="hidden" name="prevmode" value="2"/>
<input type="hidden" name="nonclassic" value="1"/>
<input type="hidden" name="quickmode" value="<?php echo $row->template_code_processed == 'QuickMode' ? '1' : '0'?>"/>
<?php			
		}

                $tabs->endTab();
		$tabs->startTab('MailChimp®','tab_mailchimp');
?>

                <table class="adminform" border="0" width="100%">

                                            <tr>
                                                <td width="30%" valign="top"><?php echo BFText::_('COM_BREEZINGFORMS_API_KEY'); ?></td>
                                                <td><input type="text" name="mailchimp_api_key"  value="<?php echo $row->mailchimp_api_key; ?>" size="50"  class="inputbox"/></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_LIST_ID'); ?></td>
                                                <td><input type="text" name="mailchimp_list_id"  value="<?php echo $row->mailchimp_list_id; ?>" size="50"  class="inputbox"/></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_EMAIL_FIELD'); ?></td>
                                                <td><input type="text" name="mailchimp_email_field"  value="<?php echo $row->mailchimp_email_field; ?>" size="50"  class="inputbox"/></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_CHECKBOX_FIELD'); ?></td>
                                                <td><input type="text" name="mailchimp_checkbox_field"  value="<?php echo $row->mailchimp_checkbox_field; ?>" size="50"  class="inputbox"/></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_UNSUBSCRIBE_FIELD'); ?></td>
                                                <td><input type="text" name="mailchimp_unsubscribe_field"  value="<?php echo $row->mailchimp_unsubscribe_field; ?>" size="50"  class="inputbox"/></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_TEXT_HTML_MOBILE_FIELD'); ?></td>
                                                <td><input type="text" name="mailchimp_text_html_mobile_field"  value="<?php echo $row->mailchimp_text_html_mobile_field; ?>" size="50"  class="inputbox"/></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_MERGE_VARS'); ?></td>
                                                <td><input type="text" name="mailchimp_mergevars"  value="<?php echo $row->mailchimp_mergevars; ?>" style="width:100%;"  class="inputbox"/></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_DEFAULT_TYPE'); ?></td>
                                                <td>
                                                    <select name="mailchimp_default_type" class="inputbox">
                                                        <option value="text"<?php echo $row->mailchimp_default_type == 'text' ? ' selected="selected"' : '';?>>Text</option>
                                                        <option value="html"<?php echo $row->mailchimp_default_type == 'html' ? ' selected="selected"' : '';?>>HTML</option>
                                                        <option value="mobile"<?php echo $row->mailchimp_default_type == 'mobile' ? ' selected="selected"' : '';?>>Mobile</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_DOUBLE_OPTIN'); ?></td>
                                                <td><input type="radio" name="mailchimp_double_optin" class="inputbox"<?php echo $row->mailchimp_double_optin ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_double_optin"  class="inputbox"<?php echo !$row->mailchimp_double_optin ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_UPDATE_EXISTING'); ?></td>
                                                <td><input type="radio" name="mailchimp_update_existing" class="inputbox"<?php echo $row->mailchimp_update_existing ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_update_existing"  class="inputbox"<?php echo !$row->mailchimp_update_existing ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_REPLACE_INTERESTS'); ?></td>
                                                <td><input type="radio" name="mailchimp_replace_interests" class="inputbox"<?php echo $row->mailchimp_replace_interests ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_replace_interests"  class="inputbox"<?php echo !$row->mailchimp_replace_interests ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_SEND_WELCOME'); ?></td>
                                                <td><input type="radio" name="mailchimp_send_welcome" class="inputbox"<?php echo $row->mailchimp_send_welcome ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_send_welcome"  class="inputbox"<?php echo !$row->mailchimp_send_welcome ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_UNSUBSCRIBE_DELETE_MEMBER'); ?></td>
                                                <td><input type="radio" name="mailchimp_delete_member" class="inputbox"<?php echo $row->mailchimp_delete_member ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_delete_member"  class="inputbox"<?php echo !$row->mailchimp_delete_member ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_UNSUBSCRIBE_SEND_GOODBYE'); ?></td>
                                                <td><input type="radio" name="mailchimp_send_goodbye" class="inputbox"<?php echo $row->mailchimp_send_goodbye ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_send_goodbye"  class="inputbox"<?php echo !$row->mailchimp_send_goodbye ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_UNSUBSCRIBE_SEND_NOTIFY'); ?></td>
                                                <td><input type="radio" name="mailchimp_send_notify" class="inputbox"<?php echo $row->mailchimp_send_notify ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_send_notify"  class="inputbox"<?php echo !$row->mailchimp_send_notify ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo BFText::_('COM_BREEZINGFORMS_SEND_ERRORS'); ?></td>
                                                <td><input type="radio" name="mailchimp_send_errors" class="inputbox"<?php echo $row->mailchimp_send_errors ? ' checked="checked"' : '';?> value="1"/> <?php echo BFText::_('COM_BREEZINGFORMS_YES');?> <input type="radio" name="mailchimp_send_errors"  class="inputbox"<?php echo !$row->mailchimp_send_errors ? ' checked="checked"' : '';?> value="0"/> <?php echo BFText::_('COM_BREEZINGFORMS_NO');?></td>
                                            </tr>

                                        </table>
<?php
                $tabs->endTab();
		$tabs->startTab('Salesforce®','tab_salesforce');
?>
                 <table class="adminform" border="0" width="100%">

                    <?php
                    if($row->salesforce_error){
                    ?>
                    <tr>
                        <td width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_SF_ERROR'); ?></td>
                        <td style="color: red;"><?php echo $row->salesforce_error; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                     
                    <tr>
                        <td width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_SF_ENABLED'); ?></td>
                        <td>
                            <input type="checkbox" onclick="document.getElementById('salesforce_flag').value=1;" name="salesforce_enabled"  value="1" size="50"  class="inputbox"<?php echo $row->salesforce_enabled == 1 ? ' checked="checked"' : ''; ?>/>
                            <input type="hidden" name="salesforce_flag" id="salesforce_flag" value="0"/>
                        </td>
                    </tr>
                     
                    <tr>
                        <td><?php echo BFText::_('COM_BREEZINGFORMS_SF_TOKEN'); ?></td>
                        <td><input type="text" name="salesforce_token"  value="<?php echo $row->salesforce_token; ?>" size="50"  class="inputbox"/></td>
                    </tr>
                    
                    <tr>
                        <td><?php echo BFText::_('COM_BREEZINGFORMS_SF_USERNAME'); ?></td>
                        <td><input type="text" name="salesforce_username"  value="<?php echo $row->salesforce_username; ?>" size="50"  class="inputbox"/></td>
                    </tr>
                    
                    <tr>
                        <td><?php echo BFText::_('COM_BREEZINGFORMS_SF_PASSWORD'); ?></td>
                        <td><input type="password" name="salesforce_password"  value="<?php echo $row->salesforce_password; ?>" size="50"  class="inputbox"/></td>
                    </tr>
                    
                    <?php
                    if( count($row->salesforce_types) != 0 ){
                    ?>
                    <tr>
                        <td><?php echo BFText::_('COM_BREEZINGFORMS_SF_TYPE'); ?></td>
                        <td>
                            <select name="salesforce_type">
                                <?php
                                foreach($row->salesforce_types As $stype){
                                ?>
                                <option value="<?php echo $stype->name;?>"<?php echo $stype->name == $row->salesforce_type ? ' selected="selected"' : '' ?>><?php echo $stype->label;?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><b><?php echo BFText::_('COM_BREEZINGFORMS_SF_FIELDS'); ?></b></td>
                        <td></td>
                    </tr>
                        <?php
                        foreach($row->breezingforms_fields As $bfField){
                        ?>
                        <tr>
                            <td><?php echo $bfField->title; ?> (<?php echo $bfField->name?>)</td>
                            <td>
                                <select name="salesforce_fields[]">
                                    <option value=""> - <?php echo BFText::_('COM_BREEZINGFORMS_SF_UNUSED'); ?> - </option>
                                    <?php
                                    foreach($row->salesforce_type_fields As $stypefields){
                                    ?>
                                    <option value="<?php echo $bfField->name?>::<?php echo $stypefields->name;?>"<?php echo in_array($bfField->name.'::'.$stypefields->name, $row->salesforce_fields) ? ' selected="selected"' : '' ?>><?php echo $stypefields->label;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                    
                 </table>
<?php
                $tabs->endTab();
		$tabs->startTab('Dropbox®','tab_dropbox');
?>
                <table class="adminform" border="0" width="100%">

                   <tr>
                        <td width="15%"><?php echo BFText::_('COM_BREEZINGFORMS_SF_USERNAME'); ?></td>
                        <td><input type="text" name="dropbox_email"  value="<?php echo $row->dropbox_email; ?>" size="50"  class="inputbox"/></td>
                    </tr>
                    
                    <tr>
                        <td><?php echo BFText::_('COM_BREEZINGFORMS_SF_PASSWORD'); ?></td>
                        <td><input type="password" name="dropbox_password"  value="<?php echo $row->dropbox_password; ?>" size="50"  class="inputbox"/></td>
                    </tr>
                    
                    <tr>
                        <td>Folder (leave empty for form name)</td>
                        <td><input type="text" name="dropbox_folder" value="<?php echo $row->dropbox_folder; ?>" size="50"  class="inputbox"/></td>
                    </tr>
                    
                    <tr>
                        <td>Upload Submission</td>
                        <td><input type="checkbox" name="dropbox_submission_enabled"  value="1" size="50"  class="inputbox"<?php echo $row->dropbox_submission_enabled == 1 ? ' checked="checked"' : ''; ?>/></td>
                    </tr>
                    
                    <tr>
                        <td>Submission Types</td>
                        <td>
                            <input type="checkbox" name="dropbox_submission_types[]"  value="pdf" size="50"  class="inputbox"<?php echo in_array('pdf', $row->dropbox_submission_types) ? ' checked="checked"' : ''; ?>/> <label>PDF</label>
                            <input type="checkbox" name="dropbox_submission_types[]"  value="csv" size="50"  class="inputbox"<?php echo in_array('csv', $row->dropbox_submission_types) ? ' checked="checked"' : ''; ?>/> <label>CSV</label>
                            <input type="checkbox" name="dropbox_submission_types[]"  value="xml" size="50"  class="inputbox"<?php echo in_array('xml', $row->dropbox_submission_types) ? ' checked="checked"' : ''; ?>/> <label>XML</label>
                        </td>
                    </tr>
                    
                </table>
<?php
                $tabs->endTab();
                /*
		$tabs->startTab(BFText::_('COM_BREEZINGFORMS_POST_TEMPLATE'),'tab_posttemplate');
?>
                <table border="0" width="100%">

                   <tr>
                        <td><?php echo BFText::_('COM_BREEZINGFORMS_POST_TEMPLATE'); ?></td>
                        <td><?php
                        global $wp_version;
                        if(version_compare($wp_version, '3.3','<') && version_compare($wp_version, '3.0','>=')){
                            echo @the_editor('', 'post_template');
                        }else if(version_compare($wp_version, '3.3','>=')){
                            echo wp_editor('', 'post_template');
                        }else{
                            echo '<textarea style="width: 100%; height: 300px;" name="post_template" id="post_template"></textarea>'."\n";
                        }
                        ?></td>
                    </tr>
                    
                </table>
<?php
		$tabs->endTab();*/
		$tabs->startTab(BFText::_('COM_BREEZINGFORMS_FORMS_SCRIPTS'),'tab_scripts');
		$subsize = $initsize = $ff_config->areasmall;
		if ($row->script1cond==2)
			$initsize = $ff_config->areamedium;
		else
			if ($row->script2cond==2)
				$subsize = $ff_config->areamedium;
?>
			<table class="adminform">
			<tr>
				<td colspan="2">
					<fieldset><legend><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_INITSCRIPT'); ?></legend>
						<table cellpadding="4" cellspacing="1" border="0">
							<tr>
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TYPE'); ?>:</td>
								<td nowrap>
									<input type="radio" id="script1cond1" name="script1cond" value="0" onclick="dispinit(this.value)"<?php if ($row->script1cond==0) echo ' checked="checked"'; ?> /><label for="script1cond1"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONE'); ?></label>
									<input type="radio" id="script1cond2" name="script1cond" value="1" onclick="dispinit(this.value)"<?php if ($row->script1cond==1) echo ' checked="checked"'; ?> /><label for="script1cond2"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_LIBRARY'); ?></label>
									<input type="radio" id="script1cond3" name="script1cond" value="2" onclick="dispinit(this.value)"<?php if ($row->script1cond==2) echo ' checked="checked"'; ?> /><label for="script1cond3"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CUSTOM'); ?></label>
								</td>
								<td></td>
							</tr>
							<tr id="initlib" style="display:none;">
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_SCRIPT'); ?>:</td>
								<td nowrap>
									<select name="script1id" class="inputbox" size="1">
<?php
										$scripts = $lists['init'];
										for ($i = 0; $i < count($scripts); $i++) {
											$script = $scripts[$i];
											$selected = '';
											if ($script->id == $row->script1id) $selected = ' selected';
											echo '<option value="'.$script->id.'"'.$selected.'>'.$script->text.'</option>';
										} // for
?>
									</select>
								</td>
								<td></td>
							</tr>
							<tr id="initcode" style="display:none;">
								<td nowrap valign="top" colspan="2">
									<a href="#" onClick="codeAreaResize('script1code',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
									<a href="#" onClick="codeAreaResize('script1code',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
									<a href="#" onClick="codeAreaResize('script1code',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
									<a href="#" onClick="createInitCode();"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CREATEFRAME'); ?></a>
<?php
									echo bf_ToolTip(BFText::_('COM_BREEZINGFORMS_FORMS_TIPINITCODE'));
?>
									<br />
									<textarea onFocus="codeAreaFocus(this);" readonly="readonly" wrap="off" name="script1lines" style="width:60px !important;" rows="<?php echo $initsize; ?>" class="inputbox"></textarea>
									<textarea onFocus="codeAreaFocus(this);" onKeyUp="codeAreaChange(this,event);" wrap="off" name="script1code" style="width:610px !important;" rows="<?php echo $initsize; ?>" class="inputbox"><?php echo htmlspecialchars($row->script1code, ENT_QUOTES); ?></textarea>
								</td>
								<td></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<fieldset><legend><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_SUBMITTEDSCRIPT'); ?></legend>
						<table cellpadding="4" cellspacing="1" border="0">
							<tr>
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TYPE'); ?>:</td>
								<td nowrap>
									<input type="radio" id="script2cond1" name="script2cond" value="0" onclick="dispsubmitted(this.value)"<?php if ($row->script2cond==0) echo ' checked="checked"'; ?> /><label for="script2cond1"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONE'); ?></label>
									<input type="radio" id="script2cond2" name="script2cond" value="1" onclick="dispsubmitted(this.value)"<?php if ($row->script2cond==1) echo ' checked="checked"'; ?> /><label for="script2cond2"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_LIBRARY'); ?></label>
									<input type="radio" id="script2cond3" name="script2cond" value="2" onclick="dispsubmitted(this.value)"<?php if ($row->script2cond==2) echo ' checked="checked"'; ?> /><label for="script2cond3"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CUSTOM'); ?></label>
								</td>
								<td></td>
							</tr>
							<tr id="submittedlib" style="display:none;">
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_SCRIPT'); ?>:</td>
								<td nowrap>
									<select name="script2id" class="inputbox" size="1">
<?php
										$scripts = $lists['submitted'];
										for ($i = 0; $i < count($scripts); $i++) {
											$script = $scripts[$i];
											$selected = '';
											if ($script->id == $row->script2id) $selected = ' selected';
											echo '<option value="'.$script->id.'"'.$selected.'>'.$script->text.'</option>';
										} // for
?>
									</select>
								</td>
								<td></td>
							</tr>
							<tr id="submittedcode" style="display:none;">
								<td nowrap valign="top" colspan="2">
									<a href="#" onClick="codeAreaResize('script2code',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
									<a href="#" onClick="codeAreaResize('script2code',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
									<a href="#" onClick="codeAreaResize('script2code',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
									<a href="#" onClick="createSubmittedCode();"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CREATEFRAME'); ?></a>
<?php
									echo bf_ToolTip(BFText::_('COM_BREEZINGFORMS_FORMS_TIPSUBMITTEDCODE'));
?>
									<br />
									<textarea onFocus="codeAreaFocus(this);" readonly="readonly" wrap="off" name="script2lines" style="width:60px !important;" rows="<?php echo $subsize; ?>" class="inputbox"></textarea>
									<textarea onFocus="codeAreaFocus(this);" onKeyUp="codeAreaChange(this,event);" wrap="off" name="script2code" style="width:610px !important;" rows="<?php echo $subsize; ?>" class="inputbox"><?php echo htmlspecialchars($row->script2code, ENT_QUOTES); ?></textarea>
								</td>
								<td></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			</table>
<?php
		$tabs->endTab();
		$tabs->startTab(BFText::_('COM_BREEZINGFORMS_FORMS_FORMPIECES'),'tab_formpieces');
		$p1size = $p2size = $ff_config->areasmall;
		if ($row->piece1cond==2)
			$p1size = $ff_config->areamedium;
		else
			if ($row->piece2cond==2)
				$p2size = $ff_config->areamedium;
?>
			<table class="adminform">
			<tr>
				<td colspan="2">
					<fieldset><legend><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_BEFOREFORM'); ?></legend>
						<table cellpadding="4" cellspacing="1" border="0">
							<tr>
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TYPE'); ?>:</td>
								<td nowrap>
									<input type="radio" id="piece1cond0" name="piece1cond" value="0" onclick="dispp1(this.value)"<?php if ($row->piece1cond==0) echo ' checked="checked"'; ?> /><label for="piece1cond0"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONE'); ?></label>
									<input type="radio" id="piece1cond1" name="piece1cond" value="1" onclick="dispp1(this.value)"<?php if ($row->piece1cond==1) echo ' checked="checked"'; ?> /><label for="piece1cond1"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_LIBRARY'); ?></label>
									<input type="radio" id="piece1cond2" name="piece1cond" value="2" onclick="dispp1(this.value)"<?php if ($row->piece1cond==2) echo ' checked="checked"'; ?> /><label for="piece1cond2"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CUSTOM'); ?></label>
								</td>
								<td></td>
							</tr>
							<tr id="p1lib" style="display:none;">
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PIECE'); ?>:</td>
								<td nowrap>
									<select name="piece1id" class="inputbox" size="1">
<?php
										$pieces = $lists['piece1'];
										for ($i = 0; $i < count($pieces); $i++) {
											$piece = $pieces[$i];
											$selected = '';
											if ($piece->id == $row->piece1id) $selected = ' selected';
											echo '<option value="'.$piece->id.'"'.$selected.'>'.$piece->text.'</option>';
										} // for
?>
									</select>
								</td>
								<td></td>
							</tr>
							<tr id="p1code" style="display:none;">
								<td nowrap valign="top" colspan="2">
									<a href="#" onClick="codeAreaResize('piece1code',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece1code',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece1code',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
									<br/>
									<textarea onFocus="codeAreaFocus(this);" readonly="readonly" wrap="off" name="piece1lines" style="width:60px !important;" rows="<?php echo $p1size; ?>" class="inputbox"></textarea>
									<textarea onFocus="codeAreaFocus(this);" onKeyUp="codeAreaChange(this,event);" wrap="off" name="piece1code" style="width:610px !important;" rows="<?php echo $p1size; ?>" class="inputbox"><?php echo htmlspecialchars($row->piece1code, ENT_QUOTES); ?></textarea>
								</td>
								<td></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<fieldset><legend><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_AFTERFORM'); ?></legend>
						<table cellpadding="4" cellspacing="1" border="0">
							<tr>
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TYPE'); ?>:</td>
								<td nowrap>
									<input type="radio" id="piece2cond0" name="piece2cond" value="0" onclick="dispp2(this.value)"<?php if ($row->piece2cond==0) echo ' checked="checked"'; ?> /><label for="piece2cond0"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONE'); ?></label>
									<input type="radio" id="piece2cond1" name="piece2cond" value="1" onclick="dispp2(this.value)"<?php if ($row->piece2cond==1) echo ' checked="checked"'; ?> /><label for="piece2cond1"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_LIBRARY'); ?></label>
									<input type="radio" id="piece2cond2" name="piece2cond" value="2" onclick="dispp2(this.value)"<?php if ($row->piece2cond==2) echo ' checked="checked"'; ?> /><label for="piece2cond2"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CUSTOM'); ?></label>
								</td>
								<td></td>
							</tr>
							<tr id="p2lib" style="display:none;">
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PIECE'); ?>:</td>
								<td nowrap>
									<select name="piece2id" class="inputbox" size="1">
<?php
										$pieces = $lists['piece2'];
										for ($i = 0; $i < count($pieces); $i++) {
											$piece = $pieces[$i];
											$selected = '';
											if ($piece->id == $row->piece2id) $selected = ' selected';
											echo '<option value="'.$piece->id.'"'.$selected.'>'.$piece->text.'</option>';
										} // for
?>
									</select>
								</td>
								<td></td>
							</tr>
							<tr id="p2code" style="display:none;">
								<td nowrap valign="top" colspan="2">
									<a href="#" onClick="codeAreaResize('piece2code',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece2code',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece2code',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
									<br/>
									<textarea onFocus="codeAreaFocus(this);" readonly="readonly" wrap="off" name="piece2lines" style="width:60px !important;" rows="<?php echo $p2size; ?>" class="inputbox"></textarea>
									<textarea onFocus="codeAreaFocus(this);" onKeyUp="codeAreaChange(this,event);" wrap="off" name="piece2code" style="width:610px !important;" rows="<?php echo $p2size; ?>" class="inputbox"><?php echo htmlspecialchars($row->piece2code, ENT_QUOTES); ?></textarea>
								</td>
								<td></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			</table>
<?php
		$tabs->endTab();
		$tabs->startTab(BFText::_('COM_BREEZINGFORMS_FORMS_SUBMPIECES'),'tab_submpieces');
		$p3size = $p4size = $ff_config->areasmall;
		if ($row->piece3cond==2)
			$p3size = $ff_config->areamedium;
		else
			if ($row->piece4cond==2)
				$p4size = $ff_config->areamedium;
?>
			<table class="adminform">
			<tr>
				<td colspan="2">
					<fieldset><legend><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_BEGINSUBMIT'); ?></legend>
						<table cellpadding="4" cellspacing="1" border="0">
							<tr>
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TYPE'); ?>:</td>
								<td nowrap>
									<input type="radio" id="piece3cond0" name="piece3cond" value="0" onclick="dispp3(this.value)"<?php if ($row->piece3cond==0) echo ' checked="checked"'; ?> /><label for="piece3cond0"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONE'); ?></label>
									<input type="radio" id="piece3cond1" name="piece3cond" value="1" onclick="dispp3(this.value)"<?php if ($row->piece3cond==1) echo ' checked="checked"'; ?> /><label for="piece3cond1"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_LIBRARY'); ?></label>
									<input type="radio" id="piece3cond2" name="piece3cond" value="2" onclick="dispp3(this.value)"<?php if ($row->piece3cond==2) echo ' checked="checked"'; ?> /><label for="piece3cond2"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CUSTOM'); ?></label>
								</td>
								<td></td>
							</tr>
							<tr id="p3lib" style="display:none;">
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PIECE'); ?>:</td>
								<td nowrap>
									<select name="piece3id" class="inputbox" size="1">
<?php
										$pieces = $lists['piece3'];
										for ($i = 0; $i < count($pieces); $i++) {
											$piece = $pieces[$i];
											$selected = '';
											if ($piece->id == $row->piece3id) $selected = ' selected';
											echo '<option value="'.$piece->id.'"'.$selected.'>'.$piece->text.'</option>';
										} // for
?>
									</select>
								</td>
								<td></td>
							</tr>
							<tr id="p3code" style="display:none;">
								<td nowrap valign="top" colspan="2">
									<a href="#" onClick="codeAreaResize('piece3code',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece3code',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece3code',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
									<br/>
									<textarea onFocus="codeAreaFocus(this);" readonly="readonly" wrap="off" name="piece3lines" style="width:60px !important;" rows="<?php echo $p3size; ?>" class="inputbox"></textarea>
									<textarea onFocus="codeAreaFocus(this);" onKeyUp="codeAreaChange(this,event);" wrap="off" name="piece3code" style="width:610px !important;" rows="<?php echo $p3size; ?>" class="inputbox"><?php echo htmlspecialchars($row->piece3code, ENT_QUOTES); ?></textarea>
								</td>
								<td></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">
					<fieldset><legend><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ENDSUBMIT'); ?></legend>
						<table cellpadding="4" cellspacing="1" border="0">
							<tr>
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TYPE'); ?>:</td>
								<td nowrap>
									<input type="radio" id="piece4cond0" name="piece4cond" value="0" onclick="dispp4(this.value)"<?php if ($row->piece4cond==0) echo ' checked="checked"'; ?> /><label for="piece4cond0"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NONE'); ?></label>
									<input type="radio" id="piece4cond1" name="piece4cond" value="1" onclick="dispp4(this.value)"<?php if ($row->piece4cond==1) echo ' checked="checked"'; ?> /><label for="piece4cond1"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_LIBRARY'); ?></label>
									<input type="radio" id="piece4cond2" name="piece4cond" value="2" onclick="dispp4(this.value)"<?php if ($row->piece4cond==2) echo ' checked="checked"'; ?> /><label for="piece4cond2"> <?php echo BFText::_('COM_BREEZINGFORMS_FORMS_CUSTOM'); ?></label>
								</td>
								<td></td>
							</tr>
							<tr id="p4lib" style="display:none;">
								<td nowrap><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PIECE'); ?>:</td>
								<td nowrap>
									<select name="piece4id" class="inputbox" size="1">
<?php
										$pieces = $lists['piece4'];
										for ($i = 0; $i < count($pieces); $i++) {
											$piece = $pieces[$i];
											$selected = '';
											if ($piece->id == $row->piece4id) $selected = ' selected';
											echo '<option value="'.$piece->id.'"'.$selected.'>'.$piece->text.'</option>';
										} // for
?>
									</select>
								</td>
								<td></td>
							</tr>
							<tr id="p4code" style="display:none;">
								<td nowrap valign="top" colspan="2">
									<a href="#" onClick="codeAreaResize('piece4code',<?php echo $ff_config->areasmall; ?>);">[<?php echo $ff_config->areasmall; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece4code',<?php echo $ff_config->areamedium; ?>);">[<?php echo $ff_config->areamedium; ?>]</a>
									<a href="#" onClick="codeAreaResize('piece4code',<?php echo $ff_config->arealarge; ?>);">[<?php echo $ff_config->arealarge; ?>]</a>
									<br/>
									<textarea onFocus="codeAreaFocus(this);" readonly="readonly" wrap="off" name="piece4lines" style="width:60px !important;" rows="<?php echo $p4size; ?>" class="inputbox"></textarea>
									<textarea onFocus="codeAreaFocus(this);" onKeyUp="codeAreaChange(this,event);" wrap="off" name="piece4code" style="width:610px !important;" rows="<?php echo $p4size; ?>" class="inputbox"><?php echo htmlspecialchars($row->piece4code, ENT_QUOTES); ?></textarea>
								</td>
								<td></td>
							</tr>
						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
			</table>
<?php
		$tabs->endTab();
		$tabs->endPane();
?>
				</td>
				<td></td>
			</tr>
		</table>
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="pkg" value="<?php echo $pkg; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="manageforms" />
		<input type="hidden" name="pages" value="<?php echo $row->pages; ?>" />
		<input type="hidden" name="caller_url" value="<?php echo htmlspecialchars($caller, ENT_QUOTES); ?>" />
		</form>
                <p></p>
                <div style="float:right;">
                <button class="button-primary" onclick="bf_submitbutton('save');"><?php echo htmlentities(BFText::_('COM_BREEZINGFORMS_TOOLBAR_SAVE'), ENT_QUOTES, 'UTF-8'); ?></button>
		<button class="button-secondary" onclick="location.href='admin.php?page=breezingforms&format=html&act=quickmode&formName=<?php echo $row->name; ?>&form=<?php echo $row->id; ?>'"><?php echo htmlentities(BFText::_('COM_BREEZINGFORMS_TOOLBAR_CANCEL'), ENT_QUOTES, 'UTF-8'); ?></button>
                </div>
                <div style="clear:both;"></div>
<?php
	} // edit

	public static function listitems( $option, &$rows, &$pkglist )
	{
		global $ff_config, $ff_version;
?>
		<script type="text/javascript">
			<!--
			var bf_submitbutton = function(pressbutton)
			{
				var form = document.adminForm;
                                
				switch (pressbutton) {
					case 'copy':
					case 'publish':
					case 'unpublish':
					case 'remove':
						if (form.boxchecked.value==0) {
							alert("<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_SELFORMSFIRST'); ?>");
							return;
						} // if
						break;
					default:
						break;
				} // switch
				if (pressbutton == 'remove')
					if (!confirm("<?php echo BFText::_('COM_BREEZINGFORMS_FORMS_ASKDEL'); ?>")) return;
				if (pressbutton == '' && form.pkgsel.value == '')
					form.pkg.value = '- blank -';
				if (pressbutton == 'easymode')
					form.act.value = 'easymode'
				if (pressbutton == 'quickmode')
					form.act.value = 'quickmode'
				else
					form.pkg.value = form.pkgsel.value;
				submitform(pressbutton);
			} // submitbutton

                        if(typeof Joomla != 'undefined'){
                            Joomla.submitbutton = bf_submitbutton;
                        }

                        submitbutton = bf_submitbutton;

			function listItemTask( id, task )
			{
				var f = document.adminForm;
				cb = eval( 'f.' + id );
				if (cb) {
					for (i = 0; true; i++) {
						cbx = eval('f.cb'+i);
						if (!cbx) break;
						cbx.checked = false;
					} // for
					cb.checked = true;
					f.boxchecked.value = 1;
					submitbutton(task);
				}
				return false;
			} // listItemTask
			//-->
		</script>
		<form action="admin.php?page=breezingforms&format=html" method="post" name="adminForm">
		Package:
					<select id="pkgsel" name="pkgsel" class="inputbox" size="1" onchange="submitbutton('');">
<?php
					if (count($pkglist)) foreach ($pkglist as $pkg) {
						$selected = '';
						if ($pkg[0]) $selected = ' selected';
						echo '<option value="'.$pkg[1].'"'.$selected.'>'.($pkg[1] == '' ? ' - '.BFText::_('COM_BREEZINGFORMS_SELECT') . ' - ' : $pkg[1]).'&nbsp;</option>';
					} // foreach
?>
					</select>
                <div style="float:right">
                        <button onclick="bf_submitbutton('quickmode')" class="button-primary">
                        <?php echo BFText::_('COM_BREEZINGFORMS_TOOLBAR_NEW');?>
                        </button>

                        <button onclick="bf_submitbutton('copy')" class="button">
                        <?php echo BFText::_('COM_BREEZINGFORMS_TOOLBAR_COPY');?>
                        </button>

                        <button onclick="bf_submitbutton('publish')" class="button">
                        <?php echo BFText::_('COM_BREEZINGFORMS_TOOLBAR_PUBLISH');?>
                        </button>
                            
                        <button onclick="bf_submitbutton('unpublish')" class="button">
                        <?php echo BFText::_('COM_BREEZINGFORMS_TOOLBAR_UNPUBLISH');?>
                        </button>
                            
                        <button onclick="bf_submitbutton('remove')" class="button">
                        <?php echo BFText::_('COM_BREEZINGFORMS_TOOLBAR_DELETE');?>
                        </button>
                </div>
                <div style="clear:both;"></div>
                <p></p>
		<table class="widefat">
			<thead>
                        <tr>
				<th nowrap align="left"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
				<th nowrap align="left"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_TITLE'); ?></th>
				<th nowrap align="left"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_NAME'); ?></th>
				<th nowrap align="left">Shortcode</th>
				<th nowrap align="left">ID</th>
				<th nowrap align="left"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_PUBLISHED'); ?></th>
				<th nowrap align="left" colspan="2"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_REORDER'); ?></th>
				<th align="left"><?php echo BFText::_('COM_BREEZINGFORMS_FORMS_DESCRIPTION'); ?></th>
			</tr>
                        </thead>
                        <tbody>
<?php                   
			$k = 0;
			for($i=0; $i < count( $rows ); $i++) {
				$row = $rows[$i];
				$desc = $row->description;
				if (strlen($desc) > $ff_config->limitdesc) $desc = substr($desc,0,$ff_config->limitdesc).'...';
?>
				<tr class="row<?php echo $k; ?>">
					<td nowrap valign="top" align="left"><input style="margin-left: 8px;" type="checkbox" id="cb<?php echo $i; ?>" name="ids[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
					
					<?php 
					if($row->template_code_processed != '' && $row->template_code_processed != 'QuickMode'){
					?>
					<td valign="top" align="left"><a href="admin.php?page=breezingforms&amp;format=html&amp;act=easymode&amp;formName=<?php echo $row->name?>&amp;form=<?php echo $row->id; ?>"><?php echo $row->title; ?></a></td>
					<td valign="top" align="left"><a href="admin.php?page=breezingforms&amp;format=html&amp;act=easymode&amp;formName=<?php echo $row->name?>&amp;form=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></td>
					<?php } else if($row->template_code_processed == 'QuickMode') { ?>
					<td valign="top" align="left"><a href="admin.php?page=breezingforms&amp;format=html&amp;act=quickmode&amp;formName=<?php echo $row->name?>&amp;form=<?php echo $row->id; ?>"><?php echo $row->title; ?></a></td>
					<td valign="top" align="left"><a href="admin.php?page=breezingforms&amp;format=html&amp;act=quickmode&amp;formName=<?php echo $row->name?>&amp;form=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></td>
					<?php } else { ?>
					<td valign="top" align="left"><a href="#editpage1" onclick="return listItemTask('cb<?php echo $i; ?>','editpage1')"><?php echo $row->title; ?></a></td>
					<td valign="top" align="left"><a href="#editform" onclick="return listItemTask('cb<?php echo $i; ?>','edit')"><?php echo $row->name; ?></a></td>
					<?php } ?>
					
					<td nowrap valign="top" align="left"><input type="text" style="width:100%;" value="[breezingforms name=&quot;<?php echo $row->name?>&quot;]" class="readonly" readonly="readonly"/></td>
					<td nowrap valign="top" align="left"><?php echo $row->id; ?></td>
					<td nowrap valign="top" align="left"><?php
					if ($row->published == "1") {
						?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','unpublish')"><img src="<?php echo WP_PLUGIN_URL;?>/<?php echo BF_FOLDER;?>/platform/administrator/components/com_breezingforms/images/icons/publish_g.png" alt="+" border="0" /></a><?php
					} else {
						?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','publish')"><img src="<?php echo WP_PLUGIN_URL;?>/<?php echo BF_FOLDER;?>/platform/administrator/components/com_breezingforms/images/icons/publish_x.png" alt="-" border="0" /></a><?php
					} // if
					?></td>
					<td nowrap valign="top" align="left"><?php
						if ($i > 0) {
							?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','orderup')"><img src="<?php echo WP_PLUGIN_URL;?>/<?php echo BF_FOLDER;?>/platform/administrator/components/com_breezingforms/images/icons/uparrow.png" alt="^" border="0" /></a><?php
						} // if
					?></td>
					<td nowrap valign="top" align="left"><?php
						if ($i < count($rows)-1) {
							?><a href="#" onClick="return listItemTask('cb<?php echo $i; ?>','orderdown')"><img src="<?php echo WP_PLUGIN_URL;?>/<?php echo BF_FOLDER;?>/platform/administrator/components/com_breezingforms/images/icons/downarrow.png" alt="v" border="0" /></a><?php
						} // if
					?></td>
					<td valign="top" align="left"><?php echo htmlspecialchars($desc, ENT_QUOTES); ?></td>
				</tr>
<?php
				$k = 1 - $k;
			} // for
?>
                        </tbody>
		</table>
                <input type="hidden" name="page" value="breezingforms" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="manageforms" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="form" value="" />
		<input type="hidden" name="page" value="" />
		<input type="hidden" name="pkg" value="" />
		</form>
<?php
	} // listitems

} // class HTML_facileFormsForm

?>