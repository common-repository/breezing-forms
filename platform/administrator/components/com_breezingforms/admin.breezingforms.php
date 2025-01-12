<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.8
* @package BreezingForms
* @copyright (C) 2008-2012 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$option = JRequest::getCmd('option');
$task = JRequest::getCmd('task');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.version');
$version = new JVersion();

$sourcePath = JPATH_SITE . DS . 'components' . DS . 'com_breezingforms' . DS . 'exports'.DS;
if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
    while (false !== ($file = @readdir($handle))) {
        if($file!="." && $file!=".."&& $file!="index.html") {
            @JFile::delete($sourcePath.$file);
        }
    }
    @closedir($handle);
}

$sourcePath = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_breezingforms' . DS . 'packages'.DS;
if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
    while (false !== ($file = @readdir($handle))) {
        if($file!="." && $file!=".." && $file!="index.html" && $file!="stdlib.english.xml") {
            @JFile::delete($sourcePath.$file);
        }
    }
    @closedir($handle);
}

// 1.7.5 to 1.8 cleanup

if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_breezingforms'.DS.'install.secimage.php')){
    JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_breezingforms'.DS.'install.secimage.php');
}

if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_breezingforms'.DS.'uninstall.secimage.php')){
    JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_breezingforms'.DS.'uninstall.secimage.php');
}

if(!JFolder::exists(WP_CONTENT_DIR.DS.'breezingforms')){
    JFolder::create(WP_CONTENT_DIR.DS.'breezingforms');
}

if(!JFile::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'index.html')){
    JFile::copy(
            JPATH_SITE.DS.'components'.DS.'com_breezingforms'.DS.'index.html', 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'index.html'
    );
}

#### MAIL TEMPLATES

if(!JFolder::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'mailtpl')){
    JFolder::copy(
            JPATH_ADMINISTRATOR.DS.'components'.DS.'com_breezingforms'.DS.'mailtpl'.DS, 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'mailtpl'.DS
    );
}

#### PDF TEMPLATES

if(!JFolder::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'pdftpl')){
    JFolder::copy(
            JPATH_ADMINISTRATOR.DS.'components'.DS.'com_breezingforms'.DS.'pdftpl'.DS, 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'pdftpl'.DS
    );
}

#### DOWNLOAD TEMPLATES

if(!JFolder::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'downloadtpl')){
    JFolder::copy(
            JPATH_SITE.DS.'components'.DS.'com_breezingforms'.DS.'downloadtpl'.DS, 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'downloadtpl'.DS
    );
}

#### UPLOADS

if(!JFolder::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'uploads')){
    JFolder::create(WP_CONTENT_DIR.DS.'breezingforms'.DS.'uploads');
    JFile::copy(
            JPATH_SITE.DS.'components'.DS.'com_breezingforms'.DS.'uploads'.DS.'index.html', 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'uploads'.DS.'index.html'
    );
}

#### THEMES

if(!JFolder::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes')){
    $wpisnew = '###';
    JFile::write(WP_CONTENT_DIR.DS.'breezingforms'.DS.'WPISNEW',$wpisnew);
    JFolder::copy(
            JPATH_SITE.DS.'components'.DS.'com_breezingforms'.DS.'themes'.DS.'quickmode'.DS, 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'quickmode'.DS
    );
    JFolder::move(
           WP_CONTENT_DIR.DS.'breezingforms'.DS.'quickmode'.DS,
           WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS
    );
}

if(JFile::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'WPISNEW')){
    define('BFWPISNEW',true);
}else{
    define('BFWPISNEW',false);
}

if(!JFolder::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'images')){
    JFolder::copy(
            JPATH_SITE.DS.'components'.DS.'com_breezingforms'.DS.'themes'.DS.'quickmode'.DS.'images'.DS, 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'images'.DS
    );
}

if(!JFile::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'jq.mobile.min.css')){
    JFile::copy(
            JPATH_SITE.DS.'components'.DS.'com_breezingforms'.DS.'themes'.DS.'quickmode'.DS.'jq.mobile.min.css', 
            WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'jq.mobile.min.css'
    );
}

#### DELETE SYSTEM THEMES FILES FROM MEDIA FOLDER (the ones in the original themes path are being used)

if(JFile::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.css')){
    JFile::delete(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.css');
}

if(JFile::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.ie7.css')){
    JFile::delete(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.ie7.css');
}

if(JFile::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.ie6.css')){
    JFile::delete(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.ie6.css');
}

if(JFile::exists(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.ie.css')){
    JFile::delete(WP_CONTENT_DIR.DS.'breezingforms'.DS.'themes'.DS.'system.ie.css');
}

/**
 * 
 * SAME CHECKS FOR CAPTCHA AS IN FRONTEND, SINCE THEY DONT SHARE THE SAME SESSION
 * 
 */

if(JRequest::getBool('bfReCaptcha')){

	@ob_end_clean();
        require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Decoder.php');
	require_once(JPATH_SITE.'/administrator/components/com_breezingforms/libraries/Zend/Json/Encoder.php');
        $db = JFactory::getDBO();
        $db->setQuery( "Select * From #__facileforms_forms Where id = " . $db->Quote( JRequest::getInt('form',-1) ) );
	$list = $db->loadObjectList();
	if(count($list) == 0){
		exit;
	}
	$form = $list[0];
	$areas = Zend_Json::decode($form->template_areas);
        foreach($areas As $area){
		foreach($area['elements'] As $element){

                    if($element['bfType'] == 'ReCaptcha'){
                        if(!function_exists('recaptcha_check_answer')){
                            require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/recaptcha/recaptchalib.php');
                        }
                        
                        $publickey = $element['pubkey']; // you got this from the signup page
                        $privatekey = $element['privkey'];

                        $resp = recaptcha_check_answer ($privatekey,
                                                        $_SERVER["REMOTE_ADDR"],
                                                        isset( $_POST["recaptcha_challenge_field"] ) ? $_POST["recaptcha_challenge_field"] : '' ,
                                                        isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : '' );

                        JFactory::getSession()->set('bfrecapsuccess',false);
                        if ($resp->is_valid) {
                            echo 'success';
                            JFactory::getSession()->set('bfrecapsuccess',true);
                        }
                        else
                        {
                            die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
                               "(reCAPTCHA said: " . $resp->error . ")");
                        }
                        exit;
                    }
                }
        }
	
	exit;

} else if(JRequest::getBool('checkCaptcha')){
	
	ob_end_clean();
        
	require_once(JPATH_SITE . '/components/com_breezingforms/images/captcha/securimage.php');
	$securimage = new Securimage();
	if(!$securimage->check(str_replace('?','',JRequest::getVar('value', '')))){
		echo 'capResult=>false';
	} else {
		echo 'capResult=>true';
	}
	exit;
	
}

$mainframe = JFactory::getApplication();

$cache = JFactory::getCache('com_content');
$cache->clean();

// since joomla 1.6.2, load some behaviour to get the core.js files loaded
if (version_compare($version->getShortVersion(), '1.6', '>=')) {
    JHtml::_('behavior.framework');
}

JHtml::_('behavior.tooltip');

// purge ajax save
$sourcePath = WP_CONTENT_DIR . DS . 'breezingforms' . DS . 'ajax_cache'.DS;
if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
    while (false !== ($file = @readdir($handle))) {
        if($file!="." && $file!="..") {
            $parts = explode('_', $file);
            if(count($parts)==3 && $parts[0] == 'ajaxsave') {
                if (@JFile::exists($sourcePath.$file) && @is_readable($sourcePath.$file)) {
                    $fileCreationTime = @filectime($sourcePath.$file);
                    $fileAge = time() - $fileCreationTime;
                    if($fileAge >= 3600) {
                        @JFile::delete($sourcePath.$file);
                    }
                }
            }
        }
    }
    @closedir($handle);
}

/**
 * DB UPGRADE BEGIN
 */
$tables = JFactory::getDBO()->getTableFields( JFactory::getDBO()->getTableList() );
if(isset($tables[JFactory::getDBO()->getPrefix().'facileforms_forms'])){
    /**
     * New as of 1.7.3
     */
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_alt_mailfrom'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_alt_mailfrom` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_mailfrom` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_alt_fromname'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_alt_fromname` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_fromname` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_custom_mail_subject'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_custom_mail_subject` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `custom_mail_subject` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_emailntf'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_emailntf` tinyint( 1 ) NOT NULL DEFAULT 1 AFTER `emailntf` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_emaillog'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_emaillog` tinyint( 1 ) NOT NULL DEFAULT 1 AFTER `emaillog` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_emailxml'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_emailxml` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `emailxml` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['email_type'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `email_type` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `mb_emailxml` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_email_type'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_email_type` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `email_type` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['email_custom_template'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `email_custom_template` TEXT AFTER `mb_email_type` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_email_custom_template'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_email_custom_template` TEXT AFTER `email_custom_template` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['email_custom_html'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `email_custom_html` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `mb_email_custom_template` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mb_email_custom_html'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mb_email_custom_html` tinyint( 1 ) NOT NULL DEFAULT 0 AFTER `email_custom_html` ");
        JFactory::getDBO()->query();
    }
    /////
    // New as of 1.7.2
    /////
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['alt_mailfrom'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `alt_mailfrom` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `id` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['alt_fromname'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `alt_fromname` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_mailfrom` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_email_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_email_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `alt_fromname` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_checkbox_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_checkbox_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_email_field` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_api_key'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_api_key` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_checkbox_field` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_list_id'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_list_id` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_api_key` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_double_optin'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_double_optin` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `mailchimp_list_id` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_mergevars'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_mergevars` TEXT AFTER `mailchimp_double_optin` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_text_html_mobile_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_text_html_mobile_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_mergevars` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_errors'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_errors` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_text_html_mobile_field` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_update_existing'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_update_existing` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_send_errors` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_replace_interests'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_replace_interests` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_update_existing` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_welcome'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_welcome` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_replace_interests` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_default_type'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_default_type` VARCHAR( 255 ) NOT NULL DEFAULT 'text' AFTER `mailchimp_send_welcome` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_delete_member'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_delete_member` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `mailchimp_default_type` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_goodbye'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_goodbye` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `mailchimp_delete_member` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_send_notify'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_send_notify` TINYINT( 1 ) NOT NULL DEFAULT 1 AFTER `mailchimp_send_goodbye` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['mailchimp_unsubscribe_field'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `mailchimp_unsubscribe_field` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_send_notify` ");
        JFactory::getDBO()->query();
    }
    // salesforce
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['salesforce_token'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `salesforce_token` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `mailchimp_unsubscribe_field` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['salesforce_username'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `salesforce_username` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `salesforce_token` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['salesforce_password'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `salesforce_password` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `salesforce_username` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['salesforce_type'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `salesforce_type` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `salesforce_password` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['salesforce_fields'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `salesforce_fields` TEXT AFTER `salesforce_type` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['salesforce_enabled'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `salesforce_enabled` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `salesforce_fields` ");
        JFactory::getDBO()->query();
    }
    // dropbox
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['dropbox_email'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `dropbox_email` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `salesforce_fields` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['dropbox_password'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `dropbox_password` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `dropbox_email` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['dropbox_folder'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `dropbox_folder` TEXT AFTER `dropbox_password` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['dropbox_submission_enabled'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `dropbox_submission_enabled` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `dropbox_folder` ");
        JFactory::getDBO()->query();
    }
    if(!isset( $tables[JFactory::getDBO()->getPrefix().'facileforms_forms']['dropbox_submission_types'] )){
        JFactory::getDBO()->setQuery("ALTER TABLE `#__facileforms_forms` ADD `dropbox_submission_types` VARCHAR( 255 ) NOT NULL DEFAULT 'pdf' AFTER `dropbox_submission_enabled` ");
        JFactory::getDBO()->query();
    }
}
/**
 * DB UPGRADE END
 */

require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFTabs.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFText.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/classes/BFTableElements.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/functions/helpers.php');
require_once(JPATH_SITE . '/administrator/components/com_breezingforms/libraries/crosstec/constants.php');

jimport('joomla.version');
$version = new JVersion();

if(version_compare($version->getShortVersion(), '1.6', '>=')){

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGERECS'),
                        'index.php?option=com_breezingforms&act=managerecs', JRequest::getVar('act','') == 'managerecs' || JRequest::getVar('act','') == 'recordmanagement');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGEFORMS'),
                        'index.php?option=com_breezingforms&act=manageforms', JRequest::getVar('act','') == 'manageforms' || JRequest::getVar('act','') == 'easymode' || JRequest::getVar('act','') == 'quickmode');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGESCRIPTS'),
                        'index.php?option=com_breezingforms&act=managescripts', JRequest::getVar('act','') == 'managescripts');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGEPIECES'),
                        'index.php?option=com_breezingforms&act=managepieces', JRequest::getVar('act','') == 'managepieces');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_INTEGRATOR'),
                        'index.php?option=com_breezingforms&act=integrate', JRequest::getVar('act','') == 'integrate');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_MANAGEMENUS'),
                        'index.php?option=com_breezingforms&act=managemenus', JRequest::getVar('act','') == 'managemenus');

JSubMenuHelper::addEntry(
                        BFText::_('COM_BREEZINGFORMS_CONFIG'),
                        'index.php?option=com_breezingforms&act=configuration', JRequest::getVar('act','') == 'configuration');

}

// wordpress always forces addslashes, let's get rid of them
$_POST    = bf_stripslashes_deep($_POST);
$_GET     = bf_stripslashes_deep($_GET);
$_REQUEST = bf_stripslashes_deep($_REQUEST);

$db = JFactory::getDBO();

/*
 * Temporary section end
 */

if( !isset($_REQUEST['action']) ){
    if( !isset($_REQUEST['act']) || ( isset($_REQUEST['act']) && $_REQUEST['act'] != 'quickmode_editor' ) ) {
    
    $active_managerecs = JRequest::getVar('act','') == '' || JRequest::getVar('act','') == 'managerecs' || JRequest::getVar('act','') == 'recordmanagement';
    $active_forms = JRequest::getVar('act','') == 'editpage' || JRequest::getVar('act','') == 'manageforms' || JRequest::getVar('act','') == 'easymode' || JRequest::getVar('act','') == 'quickmode';
    $active_scripts = JRequest::getVar('act','') == 'managescripts';
    $active_pieces = JRequest::getVar('act','') == 'managepieces';
    $active_config = JRequest::getVar('act','') == 'configuration';
    
    $active_icon32 = 'icon-options-general';
    $active_text = '';
    $active_slug = -1;
    if($active_managerecs){
        $active_text = ' : Records';
        $active_icon32 = 'icon-post';
        $active_slug = 0;
    }else 
    if($active_forms){
        $active_text = ' : Forms';
        $active_icon32 = 'icon-themes';
        $active_slug = 1;
    }else 
    if($active_scripts){
        $active_text = ' : Scripts';
        $active_icon32 = 'icon-tools';
        $active_slug = 2;
    }else 
    if($active_pieces){
        $active_text = ' : Pieces';
        $active_icon32 = 'icon-plugins';
        $active_slug = 3;
    }else 
    if($active_config){
        $active_text = ' : Configuration';
        $active_icon32 = 'icon-options-general';
        $active_slug = 4;
    }
    
    if( $active_slug > -1 ){
        echo '<script type="text/javascript">
            jQuery(document).ready(
                function(){
                    var next = jQuery("li .wp-first-item").next();
                    for(var i = 0; i<5;i++){
                        if( i == '.$active_slug.' ){
                            next.css("font-weight","bold");
                            break;
                        }
                        next = next.next();
                    }
                }
            );
        </script>';
    }
    
    echo '
        
        <style>
        input[type=submit] {
            display: inline-block;
            text-decoration: none;
            font-size: 12px;
            line-height: 23px;
            height: 24px;
            margin: 0;
            padding: 0 10px 1px;
            cursor: pointer;
            border-width: 1px;
            border-style: solid;
            -webkit-border-radius: 3px;
            -webkit-appearance: none;
            border-radius: 3px;
            white-space: nowrap;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            border-color: #bbb !important;
            color: #464646 !important;
            background-color: #fff !important;
        }
        #bf-toolbar {
        }

        ul#bf-toolbar-links {
            height: 28px;
            position: relative;
            border: 1px solid #DFDFDF;
            background-color: #EFEFEF;
            margin: 0;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#ffffff", endColorstr="#EFEFEF"); /* for IE */
            background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#EFEFEF)); /* for webkit browsers */
            background: -moz-linear-gradient(top,  #fff,  #EFEFEF); /* for firefox 3.6+ */
        }

        ul#bf-toolbar-links li {
            list-style: none;
            display: inline;
        }

        ul#bf-toolbar-links li a{
            text-decoration: none;
            text-shadow: 0 1px 1px #FFF;
            line-height: 16px;
            white-space: nowrap;
            float: left;
            margin-top: 7px;
        }

        ul#bf-toolbar-links li div.bf-toolbar-link-bg{
            background-image: url(images/menu.png);
            background-repeat: no-repeat;
            background-color: transparent;
            width: 28px;
            height: 28px;
            float: left;
        }

        ul#bf-toolbar-links li div#bf-toolbar-link-records{
            background-position: -269px -33px;
        }
        
        ul#bf-toolbar-links li div#bf-toolbar-link-forms{
            background-position: 1px -33px;
        }
        
        ul#bf-toolbar-links li div#bf-toolbar-link-scripts{
            background-position: -209px -33px;
        }
        
        ul#bf-toolbar-links li div#bf-toolbar-link-pieces{
            background-position: -179px -33px;
        }
        
        ul#bf-toolbar-links li div#bf-toolbar-link-config{
            background-position: -239px -33px;
        }
        
        ul#bf-toolbar-links li a.bf-toolbar-active{
            font-weight: bold;
        }
        
        ul#bf-toolbar-links li div#bf-toolbar-link-docs{
            background-position: -149px -33px;
        }
    </style>
    <div class="wrap">
    <div id="'.$active_icon32.'" class="icon32"></div>
    <h2>BreezingForms'.$active_text.'</h2>
    <p></p>
    <div id="bf-toolbar">
        <ul id="bf-toolbar-links">
        <li><div class="bf-toolbar-link-bg" id="bf-toolbar-link-records"></div><a class="'.($active_managerecs ? 'bf-toolbar-active' : '').'" href="admin.php?page=breezingforms&act=recordmanagement">'.BFText::_('COM_BREEZINGFORMS_WP_RECORDS').'</a></li>
        <li><div class="bf-toolbar-link-bg" id="bf-toolbar-link-forms"></div><a class="'.($active_forms ? 'bf-toolbar-active' : '').'" href="admin.php?page=breezingforms&act=manageforms">'.BFText::_('COM_BREEZINGFORMS_WP_FORMS').'</a></li>
        <li><div class="bf-toolbar-link-bg" id="bf-toolbar-link-scripts"></div><a class="'.($active_scripts ? 'bf-toolbar-active' : '').'" href="admin.php?page=breezingforms&act=managescripts">'.BFText::_('COM_BREEZINGFORMS_WP_SCRIPTS').'</a></li>
        <li><div class="bf-toolbar-link-bg" id="bf-toolbar-link-pieces"></div><a class="'.($active_pieces ? 'bf-toolbar-active' : '').'" href="admin.php?page=breezingforms&act=managepieces">'.BFText::_('COM_BREEZINGFORMS_WP_PIECES').'</a></li>
        <li><div class="bf-toolbar-link-bg" id="bf-toolbar-link-config"></div><a class="'.($active_config ? 'bf-toolbar-active' : '').'" href="admin.php?page=breezingforms&act=configuration">'.BFText::_('COM_BREEZINGFORMS_WP_CONFIGURATION').'</a></li>
        <li><div class="bf-toolbar-link-bg" id="bf-toolbar-link-docs"></div><a href="https://crosstec.org/en/forums/index.html">Docs &amp; Support</a></li>
        <li><div class="bf-toolbar-link-bg" id="bf-toolbar-link-stars"></div><a href="https://crosstec.org/en/downloads/wordpress-forms.html">Get the Pro version!</a></li>
        </ul>
    </div>
    <div style="clear:both;"></div>
    <p></p>';
    }
}

global $errors, $errmode;
global $ff_mospath, $ff_admpath, $ff_compath, $ff_request;
global $ff_mossite, $ff_admsite, $ff_admicon, $ff_comsite;
global $ff_config, $ff_compatible, $ff_install;

$my = JFactory::getUser();

if (!isset($ff_compath)) { // joomla!
	
	jimport('joomla.version');
        $version = new JVersion();

        if(version_compare($version->getShortVersion(), '1.6', '<')){
            if ($my->usertype != 'Super Administrator' && $my->usertype != 'Administrator') {
                    JFactory::getApplication()->redirect( 'index.php', BFText::_('COM_BREEZINGFORMS_NOT_AUTHORIZED') );
            } // if
        }

	// get paths
	$comppath = '/components/com_breezingforms';
	$ff_admpath = dirname(__FILE__);
	$ff_mospath = str_replace('\\','/',dirname(dirname(dirname($ff_admpath))));
	$ff_admpath = str_replace('\\','/',$ff_admpath);
	$ff_compath = $ff_mospath.$comppath;

	require_once($ff_admpath.'/toolbar.facileforms.php');
        
} // if

$errors = array();
$errmode = 'die';   // die or log

// compatibility check
if (!$ff_compatible) {
	echo '<h1>'.BFText::_('COM_BREEZINGFORMS_INCOMPATIBLE').'</h1>';
	exit;
} // if

// load ff parameters
$ff_request = array();
reset($_REQUEST);
while (list($prop, $val) = each($_REQUEST))
	if (is_scalar($val) && substr($prop,0,9)=='ff_param_')
		$ff_request[$prop] = $val;

if ($ff_install) {
	$act = 'installation';
	$task = 'step2';
} // if

$ids = JRequest::getVar( 'ids', array());

switch($act) {
	case 'installation':
		require_once($ff_admpath.'/admin/install.php');
		break;
	case 'configuration':
		require_once($ff_admpath.'/admin/config.php');
		break;
	case 'managemenus':
		require_once($ff_admpath.'/admin/menu.php');
		break;
	case 'manageforms':
		require_once($ff_admpath.'/admin/form.php');
		break;
	case 'editpage':
		require_once($ff_admpath.'/admin/element.php');
		break;
	case 'managescripts':
		require_once($ff_admpath.'/admin/script.php');
		break;
	case 'managepieces':
		require_once($ff_admpath.'/admin/piece.php');
		break;
	case 'run':
		require_once($ff_admpath.'/admin/run.php');
		break;
	case 'easymode':
		require_once($ff_admpath.'/admin/easymode.php');
		break;
	case 'quickmode':
		require_once($ff_admpath.'/admin/quickmode.php');
		break;
	case 'quickmode_editor':
		require_once($ff_admpath.'/admin/quickmode-editor.php');
		break;
	case 'integrate':
		require_once($ff_admpath.'/admin/integrator.php');
		break;
	case 'recordmanagement':
		require_once($ff_admpath.'/admin/recordmanagement.php');
		break;
	default:
		require_once($ff_admpath.'/admin/recordmanagement.php');
		break;
} // switch

echo "</div>"; // wrap end

// some general purpose functions for admin

function isInputElement($type)
{
	switch ($type) {
		case 'Static Text/HTML':
		case 'Rectangle':
		case 'Image':
		case 'Tooltip':
		case 'Query List':
		case 'Regular Button':
		case 'Graphic Button':
		case 'Icon':
			return false;
		default:
			break;
	} // switch
	return true;
} // isInputElement

function isVisibleElement($type)
{
	switch ($type) {
		case 'Hidden Input':
			return false;
		default:
			break;
	} // switch
	return true;
} // isVisibleElement

function _ff_query($sql, $insert = 0)
{
	global $database, $errors;
	$database = JFactory::getDBO();
	$id = null;
	$database->setQuery($sql);
	$database->query();
	if ($database->getErrorNum()) {
		if (isset($errmode) && $errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	if ($insert) $id = $database->insertid();
	return $id;
} // _ff_query

function _ff_select($sql)
{
	global $database, $errors;
	$database = JFactory::getDBO();
	$database->setQuery($sql);
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		if (isset($errmode) && $errmode=='log')
			$errors[] = $database->getErrorMsg();
		else
			die($database->stderr());
	} // if
	
	return $rows;
} // _ff_select

function _ff_selectValue($sql)
{
	global $database, $errors;
	$database = JFactory::getDBO();
	$database->setQuery($sql);
	$value = $database->loadResult();
	if ($database->getErrorNum()) {
		
			die($database->stderr());
	} // if
	return $value;
} // _ff_selectValue

function protectedComponentIds()
{
    jimport('joomla.version');
    $version = new JVersion();
/*
    if(version_compare($version->getShortVersion(), '1.6', '>=')){

        $rows = _ff_select(
		"select id, parent_id As parent from #__menu ".
		"where ".
		" link in (".
			"'index.php?option=com_breezingforms&act=managerecs',".
			"'index.php?option=com_breezingforms&act=managemenus',".
			"'index.php?option=com_breezingforms&act=manageforms',".
			"'index.php?option=com_breezingforms&act=managescripts',".
			"'index.php?option=com_breezingforms&act=managepieces',".
			"'index.php?option=com_breezingforms&act=share',".
			"'index.php?option=com_breezingforms&act=integrate',".
			"'index.php?option=com_breezingforms&act=configuration'".
		") ".
		"order by id"
	);

    }else{

	$rows = _ff_select(
		"select id, parent from #__components ".
		"where `option`='com_breezingforms' ".
		"and admin_menu_link in (".
			"'option=com_breezingforms&act=managerecs',".
			"'option=com_breezingforms&act=managemenus',".
			"'option=com_breezingforms&act=manageforms',".
			"'option=com_breezingforms&act=managescripts',".
			"'option=com_breezingforms&act=managepieces',".
			"'option=com_breezingforms&act=share',".
			"'option=com_breezingforms&act=integrate',".
			"'option=com_breezingforms&act=configuration'".
		") ".
		"order by id"
	);

    }
    */
    $parent = 0;
    $ids = array();
    if (count($rows))
        foreach ($rows as $row) {
            if ($parent == 0) {
                $parent = 1;
                if(isset($row->parent)){
                    $ids[] = $row->parent;
                }
            } // if
            $ids[] = $row->id;
        } // foreach
 return implode($ids, ',');
} // protectedComponentIds

function addComponentMenu($row, $parent, $copy = false)
{
	$db = JFactory::getDBO();
	$admin_menu_link = '';
	if ($row->name!='') {
		$admin_menu_link =
			'option=com_breezingforms'.
			'&act=run'.
			'&ff_name='.$row->name;
		if ($row->page!=1) $admin_menu_link .= '&ff_page='.$row->page;
		if ($row->frame==1) $admin_menu_link .= '&ff_frame=1';
		if ($row->border==1) $admin_menu_link .= '&ff_border=1';
		if ($row->params!='') $admin_menu_link .= $row->params;
	} // if
	if ($parent==0) $ordering = 0; else $ordering = $row->ordering;

        jimport('joomla.version');
        $version = new JVersion();
/*
        if(version_compare($version->getShortVersion(), '1.6', '>=')){

            $parent = $parent == 0 ? 1 : $parent;

            $db->setQuery("Select component_id From #__menu Where link = 'index.php?option=com_breezingforms' And parent_id = 1");
            $result = $db->loadResult();
            if($result){
                
                return _ff_query(
                    "insert into #__menu (".
                            "`title`, alias, menutype, parent_id, ".
                            "link,".
                            "ordering, level, component_id, client_id, img, lft, rgt".
                    ") ".
                    "values (".$db->Quote( ($copy ? 'Copy of ' : '') . $row->title . ($copy ? ' ('.md5(session_id().microtime().mt_rand(0,  mt_getrandmax())).')' : '')).", ".$db->Quote( ($copy ? 'Copy of ' : '') . $row->title . ($copy ? ' ('.md5(session_id().microtime().mt_rand(0,  mt_getrandmax())).')' : '')).", 'menu', $parent, ".
                            "'index.php?$admin_menu_link',".
                            "'$ordering', 1, ".intval($result).", 1, 'components/com_breezingforms/images/$row->img',( Select mlftrgt From (Select max(mlft.rgt)+1 As mlftrgt From #__menu As mlft) As tbone ),( Select mrgtrgt From (Select max(mrgt.rgt)+2 As mrgtrgt From #__menu As mrgt) As filet )".
                    ")",
                    true
                );
            }else{
                die("BreezingForms main menu item not found!");
            }
        }
        // if older JVersion
	return _ff_query(
		"insert into #__components (".
			"id, name, link, menuid, parent, ".
			"admin_menu_link, admin_menu_alt, `option`, ".
			"ordering, admin_menu_img, iscore, params".
		") ".
		"values (".
			"'', ".$db->Quote($row->title).", '', 0, $parent, ".
			"'$admin_menu_link', ".$db->Quote($row->title).", 'com_breezingforms', ".
			"'$ordering', '$row->img', 1, ''".
		")",
		true
	);
 * */
 
} // addComponentMenu

function updateComponentMenus($copy = false)
{
	// remove unprotected menu items
    /*
	$protids = protectedComponentIds();
	if(trim($protids)!=''){

            jimport('joomla.version');
            $version = new JVersion();

            if(version_compare($version->getShortVersion(), '1.6', '>=')){
                _ff_query(
			"delete from #__menu ".
			"where `link` Like 'index.php?option=com_breezingforms%' ".
			"and id not in ($protids) And `menutype` <> 'mainmenu'"
		);
            }else{
		_ff_query(
			"delete from #__components ".
			"where `option`='com_breezingforms' ".
			"and id not in ($protids)"
		);
            }
	} 
	*/
	// add published menu items
	$rows = _ff_select(
		"select ".
			"m.id as id, ".
			"m.parent as parent, ".
			"m.ordering as ordering, ".
			"m.title as title, ".
			"m.img as img, ".
			"m.name as name, ".
			"m.page as page, ".
			"m.frame as frame, ".
			"m.border as border, ".
			"m.params as params, ".
			"m.published as published ".
		"from #__facileforms_compmenus as m ".
			"left join #__facileforms_compmenus as p on m.parent=p.id ".
		"where m.published=1 ".
			"and (m.parent=0 or p.published=1) ".
		"order by ".
			"if(m.parent,p.ordering,m.ordering), ".
			"if(m.parent,m.ordering,-1)"
	);
        /*
	$parent = 0;
	if (count($rows)) foreach ($rows as $row) {

                jimport('joomla.version');
                $version = new JVersion();

                if(version_compare($version->getShortVersion(), '1.6', '>=')){

                    JFactory::getDBO()->setQuery("Select id From #__menu Where `alias` = " . JFactory::getDBO()->Quote($row->title));

                    if(JFactory::getDBO()->loadResult()){
                        return BFText::_('COM_BREEZINGFORMS_MENU_ITEM_EXISTS');
                    }

                    if ($row->parent==0 || $row->parent==1){
                            $parent = addComponentMenu($row, 1, $copy);
                    }else{
                            addComponentMenu($row, $parent, $copy);
                    }
                }else{
                    if ($row->parent==0){
                            $parent = addComponentMenu($row, 0);
                    }else{
                            addComponentMenu($row, $parent);
                    }
                }
	} // foreach
        */
        return '';
} // updateComponentMenus

function dropPackage($id)
{
	// drop package settings
	_ff_query("delete from #__facileforms_packages where id = ".JFactory::getDBO()->Quote($id)."");

	// drop backend menus
	$rows = _ff_select("select id from #__facileforms_compmenus where package = ".JFactory::getDBO()->Quote($id)."");
	if (count($rows)) foreach ($rows as $row)
		_ff_query("delete from #__facileforms_compmenus where id=$row->id or parent=$row->id");
	updateComponentMenus();

	// drop forms
	$rows = _ff_select("select id from #__facileforms_forms where package = ".JFactory::getDBO()->Quote($id)."");
	if (count($rows)) foreach ($rows as $row) {
		_ff_query("delete from #__facileforms_elements where form = $row->id");
		_ff_query("delete from #__facileforms_forms where id = $row->id");
	} // if

	// drop scripts
	_ff_query("delete from #__facileforms_scripts where package =  ".JFactory::getDBO()->Quote($id)."");

	// drop pieces
	_ff_query("delete from #__facileforms_pieces where package =  ".JFactory::getDBO()->Quote($id)."");
} // dropPackage

function savePackage($id, $name, $title, $version, $created, $author, $email, $url, $description, $copyright)
{
	$db = JFactory::getDBO();
	$cnt = _ff_selectValue("select count(*) from #__facileforms_packages where id=".JFactory::getDBO()->Quote($id)."");
	if (!$cnt) {
		
		_ff_query(
			"insert into #__facileforms_packages ".
					"(id, name, title, version, created, author, ".
					 "email, url, description, copyright) ".
			"values (".$db->Quote($id).", ".$db->Quote($name).", ".$db->Quote($title).", ".$db->Quote($version).", ".$db->Quote($created).", ".$db->Quote($author).",
					".$db->Quote($email).", ".$db->Quote($url).", ".$db->Quote($description).", ".$db->Quote($copyright).")"
		);
	} else {
		_ff_query(
			"update #__facileforms_packages ".
				"set name=".$db->Quote($name).", title=".$db->Quote($title).", version=".$db->Quote($version).", created=".$db->Quote($created).", author=".$db->Quote($author).", ".
				"email=".$db->Quote($email).", url=".$db->Quote($url).", description=".$db->Quote($description).", copyright=".$db->Quote($copyright). " 
			where id =  ".$db->Quote($id)
		);
	} // if
} // savePackage

function relinkScripts(&$oldscripts)
{
	if (is_array($oldscripts) && count($oldscripts))
		foreach ($oldscripts as $row) {
			$newid = _ff_selectValue("select max(id) from #__facileforms_scripts where name = ".JFactory::getDBO()->Quote($row->name)."");
			if ($newid) {
				_ff_query("update #__facileforms_forms set script1id=$newid where script1id=$row->id");
				_ff_query("update #__facileforms_forms set script2id=$newid where script2id=$row->id");
				_ff_query("update #__facileforms_elements set script1id=$newid where script1id=$row->id");
				_ff_query("update #__facileforms_elements set script2id=$newid where script2id=$row->id");
				_ff_query("update #__facileforms_elements set script3id=$newid where script3id=$row->id");
			} // if
		} // foreach
} // relinkScripts

function relinkPieces(&$oldpieces)
{
	if (is_array($oldpieces) && count($oldpieces))
		foreach ($oldpieces as $row) {
			$newid = _ff_selectValue("select max(id) from #__facileforms_pieces where name = ".JFactory::getDBO()->Quote($row->name)."");
			if ($newid) {
				_ff_query("update #__facileforms_forms set piece1id=$newid where piece1id=$row->id");
				_ff_query("update #__facileforms_forms set piece2id=$newid where piece2id=$row->id");
				_ff_query("update #__facileforms_forms set piece3id=$newid where piece3id=$row->id");
				_ff_query("update #__facileforms_forms set piece4id=$newid where piece4id=$row->id");
			} // if
		} // foreach
} // relinkPieces
?>