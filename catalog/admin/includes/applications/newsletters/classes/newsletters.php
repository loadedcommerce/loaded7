<?php
/*
  $Id: newsletters.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Newsletters_Admin class manages newsletters
*/
class lC_Newsletters_Admin {
 /*
  * Returns the newsletters datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $lC_Currencies, $lC_Vqmod, $_module;

    $media = $_GET['media'];
    
    $Qnewsletters = $lC_Database->query('select newsletters_id, title, length(content) as content_length, module, date_added, date_sent, status, locked from :table_newsletters order by date_added desc');
    $Qnewsletters->bindTable(':table_newsletters', TABLE_NEWSLETTERS);
    $Qnewsletters->setBatchLimit($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS);
    $Qnewsletters->execute();

    $result = array('aaData' => array());
    while ( $Qnewsletters->next() ) {
      $newsletter_module_class = 'lC_Newsletter_' . $Qnewsletters->value('module');
      if ( !class_exists($newsletter_module_class) ) {
        $lC_Language->loadIniFile('modules/newsletters/' . $Qnewsletters->value('module') . '.php');
        include($lC_Vqmod->modCheck('includes/modules/newsletters/' . $Qnewsletters->value('module') . '.php'));
        $$newsletter_module_class = new $newsletter_module_class();
      }
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qnewsletters->valueInt('newsletters_id') . '" id="' . $Qnewsletters->valueInt('newsletters_id') . '"></td>';
      $newsletter = '<td><a class="with-tooltip" href="javascript://" onclick="showPreview(\'' . $Qnewsletters->valueInt('newsletters_id') . '\')" title="' .  $lC_Language->get('icon_preview') . '"><span class="icon-search"></span>&nbsp;' . $Qnewsletters->value('title') . '</a></td>';  
      $size = '<td>' . number_format($Qnewsletters->valueInt('content_length')) . '</td>';  
      $module = '<td>' . $$newsletter_module_class->getTitle() . '</td>';  
      $sent = '<td>' . (($Qnewsletters->valueInt('status') === 1) ? '<span class="icon-tick icon-green icon-size2"></span>' : '<span class="icon-cross icon-red icon-size2"></span>') . '</td>';  

      $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">';
      if ( $Qnewsletters->valueInt('status') === 1 ) {
        $action .= '<a href="' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? '#' : 'javascript://" onclick="showLog(\'' . $Qnewsletters->valueInt('newsletters_id') . '\')') . '" class="button icon-read' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_log')) . '</a>';
      } else {
        $action .= '<a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editNewsletter(\'' . $Qnewsletters->valueInt('newsletters_id') . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                    <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? '#' : 'javascript://" onclick="' . lC_Newsletters_Admin::_u2c('send_' . $Qnewsletters->value('module')) . '(\'' . $Qnewsletters->valueInt('newsletters_id') . '\')') . '" class="button icon-mail with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 2) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_email_send') . '"></a>';
      }    
      $action .= '<a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteNewsletter(\'' . $Qnewsletters->valueInt('newsletters_id') . '\', \'' . urlencode($Qnewsletters->value('title')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>';
          
      $action .= '</span></td>';
      
      $result['aaData'][] = array("$check", "$newsletter", "$size", "$module", "$sent", "$action");
      $result['entries'][] = $Qnewsletters->toArray();
    }

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The newsletter id
  * @access public
  * @return array
  */
  public static function formData($id = null) {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/newsletters');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $modules_array = array();
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $module = substr($file['name'], 0, strrpos($file['name'], '.'));
      $lC_Language->loadIniFile('modules/newsletters/' . $file['name']);
      include($lC_Vqmod->modCheck('includes/modules/newsletters/' . $file['name']));
      $newsletter_module_class = 'lC_Newsletter_' . $module;
      $lC_NewsletterModule = new $newsletter_module_class();
      $modules_array[$module] = $lC_NewsletterModule->getTitle();
    }
    $result['modulesArray'] = $modules_array;

    if ($id != null) {
      $Qnewsletter = $lC_Database->query('select * from :table_newsletters where newsletters_id = :newsletters_id');
      $Qnewsletter->bindTable(':table_newsletters', TABLE_NEWSLETTERS);
      $Qnewsletter->bindInt(':newsletters_id', $id);
      $Qnewsletter->execute();

      $data = $Qnewsletter->toArray();

      $Qnewsletter->freeResult();

      $result = array_merge((array)$result, (array)$data);
    }

    return $result;
  }
 /*
  * Return the data used on the send dialog form
  *
  * @param integer $id The newsletter id
  * @param boolean $email True = send newsletter 
  * @access public
  * @return array
  */
  public static function sendData($id, $email = false) {
   global $lC_Language, $lC_Template, $lC_Vqmod;  

   $result = array();
   $lC_ObjectInfo = new lC_ObjectInfo(lC_Newsletters_Admin::getData($id));
   $lC_Language->loadIniFile('modules/newsletters/' . $lC_ObjectInfo->get('module') . '.php');
   include($lC_Vqmod->modCheck('includes/modules/newsletters/' . $lC_ObjectInfo->get('module') . '.php'));
   $module_name = 'lC_Newsletter_' . $lC_ObjectInfo->get('module');
   $module = new $module_name($lC_ObjectInfo->get('title'), $lC_ObjectInfo->get('content'), $lC_ObjectInfo->get('newsletters_id'));

   $result['newsletterModule'] = $lC_ObjectInfo->get('module');
   $result['hasAudienceSelection'] = $module->hasAudienceSelection();
   $result['showAudienceSelectionForm'] = $module->showAudienceSelectionForm();
   $result['showConfirmation'] = $module->showConfirmation();
   $result['hasAudienceSize'] = $module->hasAudienceSize();

   if ($email == true || $email == '1') {
     flush();
     $module->sendEmail();
   }

   return $result;

  }
 /*
  * Return newsletter information
  *
  * @param integer $id The newsletter id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $Qnewsletter = $lC_Database->query('select * from :table_newsletters where newsletters_id = :newsletters_id');
    $Qnewsletter->bindTable(':table_newsletters', TABLE_NEWSLETTERS);
    $Qnewsletter->bindInt(':newsletters_id', $id);
    $Qnewsletter->execute();

    $data = $Qnewsletter->toArray();

    $Qnewsletter->freeResult();

    return $data;
  }
 /*
  * Return the data used on the preview dialog form
  *
  * @param integer $id The newsletter id
  * @access public
  * @return array
  */
  public static function preview($id) {
    $result = array();
    $lC_ObjectInfo = new lC_ObjectInfo(lC_Newsletters_Admin::getData($id));
    $result['title'] = $lC_ObjectInfo->get('title'); 
    $result['content'] = nl2br(lc_output_string_protected($lC_ObjectInfo->get('content')));

    return $result;
  }
 /*
  * Return the data used on the log dialog form
  *
  * @param integer $id The newsletter id
  * @access public
  * @return array
  */
  public static function logInfo($id) {
    global $lC_Database;

    $Qlog = $lC_Database->query('select email_address, date_sent from :table_newsletters_log where newsletters_id = :newsletters_id order by date_sent desc');
    $Qlog->bindTable(':table_newsletters_log', TABLE_NEWSLETTERS_LOG);
    $Qlog->bindInt(':newsletters_id', $id);
    $Qlog->execute();

    $result = array('aaData' => array()); 
    while ( $Qlog->next() ) {
      $email = '<td>' . $Qlog->valueProtected('email_address') . '</td>';
      $sent = '<td>' . (!lc_empty($Qlog->value('date_sent')) ? '<span class="icon-tick icon-green icon-size2"></span>' : '<span class="icon-cross icon-red icon-size2"></span>') . '</td>';  
      $date = '<td>' . $Qlog->value('date_sent') . '</td>';
      
      $result['aaData'][] = array("$email", "$sent", "$date");
    }

    return $result;
  }
 /*
  * Save the newsletter information
  *
  * @param integer $id The newsletter id used on update, null on insert
  * @param array $data An array containing the newsletter information
  * @access public
  * @return array
  */
  public static function save($id = null, $data) {
    global $lC_Database;

    if ( is_numeric($id) ) {
      $Qemail = $lC_Database->query('update :table_newsletters set title = :title, content = :content, module = :module where newsletters_id = :newsletters_id');
      $Qemail->bindInt(':newsletters_id', $id);
    } else {
      $Qemail = $lC_Database->query('insert into :table_newsletters (title, content, module, date_added, status) values (:title, :content, :module, now(), 0)');
    }

    $Qemail->bindTable(':table_newsletters', TABLE_NEWSLETTERS);
    $Qemail->bindValue(':title', $data['title']);
    $Qemail->bindValue(':content', $data['content']);
    $Qemail->bindValue(':module', $data['module']);
    $Qemail->setLogging($_SESSION['module'], $id);
    $Qemail->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the newsletter record
  *
  * @param integer $id The newsletter id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    $Qdelete = $lC_Database->query('delete from :table_newsletters_log where newsletters_id = :newsletters_id');
    $Qdelete->bindTable(':table_newsletters_log', TABLE_NEWSLETTERS_LOG);
    $Qdelete->bindInt(':newsletters_id', $id);
    $Qdelete->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    }

    if ( $error === false ) {
      $Qdelete = $lC_Database->query('delete from :table_newsletters where newsletters_id = :newsletters_id');
      $Qdelete->bindTable(':table_newsletters', TABLE_NEWSLETTERS);
      $Qdelete->bindInt(':newsletters_id', $id);
      $Qdelete->setLogging($_SESSION['module'], $id);
      $Qdelete->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch delete newsletter records
  *
  * @param array $batch The newsletter id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Newsletters_Admin::delete($id);
    }
    return true;
  }
 /*
  * Convert underscore string to camel case
  *
  * @param string $string The underscore string to convert
  * @access private
  * @return string
  */
  private static function _u2c($string) {
    $new = array();
    $arr = explode('_', $string);
    foreach ($arr as $value) {
      $new[] = ucwords($value);
    }
    $result = str_replace('_', '', implode('_', $new));
    $return = strtolower(substr($result, 0, 1)) . substr($result, 1);

    return $return;
  }
}
?>
