<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: administrators.php v1.0 2013-08-08 datazen $
*/
class lC_Administrators_Admin {
 /**
  * Returns the administrators datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $lC_Language->loadIniFile('administrators.php');
    
    $media = $_GET['media'];
    
    /* Filtering */
    $aWhere = "";
    if ($_GET['aSearch'] != "") {
      $aWhere = "where id = '" . (int)$_GET['aSearch'] . "'";
    } 

    $Qadmins = $lC_Database->query('select * from :table_administrators ' . $aWhere . ' order by user_name');
    $Qadmins->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qadmins->execute();

    $result = array('entries' => array());
    $result = array('aaData' => array());
    $cnt = 0;
    while ( $Qadmins->next() ) {
      $group = self::getGroupName($Qadmins->valueInt('access_group_id')) ;
      $color = ($odd == $cnt%2) ? 'purple-bg' : 'green-bg';
      if ($Qadmins->valueInt('access_group_id') == 1) $color = 'red-bg';

      $last = '<td>' . $Qadmins->valueProtected('last_name') . '</td>';
      $first = '<td>' . $Qadmins->valueProtected('first_name') . '</td>';
      $user = '<td>' . $Qadmins->valueProtected('user_name') . '</td>';
      $group = '<td><small class="tag ' . $color . '">' . self::getGroupName($Qadmins->valueInt('access_group_id')) . '</small></td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editAdmin(\'' . $Qadmins->valueInt('id') . '\')') . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteAdmin(\'' . $Qadmins->valueInt('id') . '\', \'' . urlencode($Qadmins->valueProtected('user_name')) . '\')') . '" class="button icon-trash with-tooltip ' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a></span></td>';

      $result['aaData'][] = array("$last", "$first", "$user", "$group", "$action");
      $result['entries'][] = $Qadmins->toArray();
      $cnt++;
    }
    $Qadmins->freeResult();

    return $result;
  }

  public static function getAccessGroupName($_id) {

  }
 /**
  * Returns the administrator information
  *
  * @param integer $id The administrator id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $Qadmin = $lC_Database->query('select * from :table_administrators where id = :id');
    $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qadmin->bindInt(':id', $id);
    $Qadmin->execute();

    $modules = array('access_modules' => array());

    $Qaccess = $lC_Database->query('select module from :table_administrators_access where administrators_id = :administrators_id');
    $Qaccess->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
    $Qaccess->bindInt(':administrators_id', $id);
    $Qaccess->execute();

    while ( $Qaccess->next() ) {
      $modules['access_modules'][] = $Qaccess->value('module');
    }
    
    $languages_array = array(); 
    $languageAarray = array(); 
    foreach (lC_Languages_Admin::getIdNameArray() as $key => $value) {
      $languages_array[$value['languages_id']] = $value['name'];
    }
    $languageAarray['languagesArray'] = $languages_array;

    $data = array_merge($Qadmin->toArray(), $modules,$languageAarray);

    unset($modules);
    $Qaccess->freeResult();
    $Qadmin->freeResult();

    return $data;
  }
 /**
  * Saves the administrator information
  *
  * @param integer  $id   The administrator id used on update, null on insert
  * @param array    $data An array containing the administrator information
  * @access public
  * @return array
  */
  public static function save($id = null, $data) {
    global $lC_Database;

    $error = false;
    $result = array();

    $Qcheck = $lC_Database->query('select id, language_id from :table_administrators where user_name = :user_name');

    if ( isset($id) && $id != null ) {
      $Qcheck->appendQuery('and id != :id');
      $Qcheck->bindInt(':id', $id);
    }

    $Qcheck->appendQuery('limit 1');
    $Qcheck->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qcheck->bindValue(':user_name', $data['user_name']);
    $Qcheck->execute();
    
    if ($Qcheck->numberOfRows() < 1) {
      $lC_Database->startTransaction();

      if ( isset($id) && $id != null ) {
        $Qadmin = $lC_Database->query('update :table_administrators set user_name = :user_name, first_name = :first_name, last_name = :last_name, image = :image, access_group_id = :access_group_id, language_id = :language_id, verify_key = :verify_key');
        if ( isset($data['user_password']) && !empty($data['user_password']) ) {
          $Qadmin->appendQuery(', user_password = :user_password');
          $Qadmin->bindValue(':user_password', lc_encrypt_string(trim($data['user_password'])));
        }
        $Qadmin->appendQuery('where id = :id');
        $Qadmin->bindInt(':id', $id);
      } else {
        $Qadmin = $lC_Database->query('insert into :table_administrators (user_name, user_password, first_name, last_name, image, access_group_id, language_id, verify_key) values (:user_name, :user_password, :first_name, :last_name, :image, :access_group_id,:language_id, :verify_key)');
        $Qadmin->bindValue(':user_password', lc_encrypt_string(trim($data['user_password'])));
      }
      
      $Qadmin->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qadmin->bindValue(':user_name', $data['user_name']);
      $Qadmin->bindValue(':first_name', $data['first_name']);
      $Qadmin->bindValue(':last_name', $data['last_name']);
      $Qadmin->bindValue(':image', $data['avatar']);
      $Qadmin->bindInt(':access_group_id', $data['access_group_id']);
      $Qadmin->bindInt(':language_id', $data['language_id']);
      $Qadmin->bindValue(':verify_key', '');
      $Qadmin->setLogging($_SESSION['module'], $id);
      $Qadmin->execute();

      if ( !$lC_Database->isError() ) {
        if ( !is_numeric($id) ) {
          $id = $lC_Database->nextID();
          $new = 1;
        }
      } else {
        $error = true;
      }

      if ( $error === false  ) {
        $lC_Database->commitTransaction();
        if (!$new) {
          // check for language changes and set session accordingly
          if ($data['language_id'] != $Qcheck->value('language_id')) {
            $_SESSION['admin']['language_id'] = $data['language_id'];
          }
          $_SESSION['admin']['username'] = $data['user_name'];
          $_SESSION['admin']['firstname'] = $data['first_name'];
          $_SESSION['admin']['lastname'] = $data['last_name'];
        }
      } else {
        $lC_Database->rollbackTransaction();
        $result['rpcStatus'] = -1;
      }
    } else {
      $result['rpcStatus'] = -2;
    }

    return $result;
  }
 /**
  * Deletes the administrator record
  *
  * @param integer $id The administrator id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $lC_Database->startTransaction();

    $Qdel = $lC_Database->query('delete from :table_administrators_access where administrators_id = :administrators_id');
    $Qdel->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
    $Qdel->bindInt(':administrators_id', $id);
    $Qdel->setLogging($_SESSION['module'], $id);
    $Qdel->execute();

    if ( !$lC_Database->isError() ) {
      $Qdel = $lC_Database->query('delete from :table_administrators where id = :id');
      $Qdel->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
      $Qdel->bindInt(':id', $id);
      $Qdel->setLogging($_SESSION['module'], $id);
      $Qdel->execute();

      if ( !$lC_Database->isError() ) {
        $lC_Database->commitTransaction();

        return true;
      }
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /**
  * Returns the administrators groups datatable data
  *
  * @param boollean $entriesOnly  True = Send back entries data only, False = send back both entries data and data tables data
  * @access public
  * @return array
  */
  public static function getAllGroups($entriesOnly = FALSE) {
    global $lC_Database, $lC_Language, $_module;

    $lC_Language->loadIniFile('administrators.php');
    
    $media = $_GET['media'];    

    $QadminGroups = $lC_Database->query('select id, name from :table_administrators_groups order by id');
    $QadminGroups->bindTable(':table_administrators_groups', TABLE_ADMINISTRATORS_GROUPS);
    $QadminGroups->execute();

    $result = array('entries' => array());
    $result = array('aaData' => array());
    while ( $QadminGroups->next() ) {
      $name = '<td>' . $QadminGroups->valueProtected('name') . '</td>';
      $modules = '<td class="hide-on-tablet">' . self::getAccessBlocks($QadminGroups->valueInt('id')) . '</td>';
      $members = '<td>' . self::getTotalMembers($QadminGroups->valueInt('id')) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'administrators&set=access&gid=' . $QadminGroups->valueInt('id'))) . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteGroup(\'' . $QadminGroups->valueInt('id') . '\', \'' . urlencode($QadminGroups->valueProtected('name')) . '\')') . '" class="button icon-trash with-tooltip ' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a></span></td>';
      // modification of default top level admin is prohibited
      if ($QadminGroups->valueInt('id') == '1' || $QadminGroups->value('name') == $lC_Language->get('text_top_administrator')) $action = '';
      $result['aaData'][] = array("$name", "$modules", "$members", "$action");
      $result['entries'][] = $QadminGroups->toArray();
    }

    $QadminGroups->freeResult();

    if ($entriesOnly) return $result['entries'];
    return $result;
  }
 /**
  * Returns the total members for an administrator group
  *
  * @param integer $id The administrator groups id
  * @access public
  * @return boolean
  */
  public static function getTotalMembers($_id) {
    global $lC_Database, $lC_Language;

    $QadminGroups = $lC_Database->query('select count(*) as total from :table_administrators WHERE access_group_id = :id');
    $QadminGroups->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $QadminGroups->bindInt(':id', $_id);
    $QadminGroups->execute();

    $total =  ($QadminGroups->valueInt('total') > 0) ? $QadminGroups->valueInt('total') : 0;

    $QadminGroups->freeResult();

    return $total;
  }
 /**
  * Returns the administrators group name
  *
  * @param integer $id The administrator groups id
  * @access public
  * @return boolean
  */
  public static function getGroupName($_id) {
    global $lC_Database;

    $Qgroup = $lC_Database->query('select name from :table_administrators_groups where id = :id');
    $Qgroup->bindTable(':table_administrators_groups', TABLE_ADMINISTRATORS_GROUPS);
    $Qgroup->bindInt(':id', $_id);
    $Qgroup->execute();

    $data = $Qgroup->toArray();

    $Qgroup->freeResult();

    return $data['name'];
  }
 /**
  * Returns the administrator group data
  *
  * @param integer $id The administrator id
  * @access public
  * @return array
  */
  public static function getGroupData($id) {
    global $lC_Database;

    $Qgroup = $lC_Database->query('select * from :table_administrators_groups where id = :id');
    $Qgroup->bindTable(':table_administrators_groups', TABLE_ADMINISTRATORS_GROUPS);
    $Qgroup->bindInt(':id', $id);
    $Qgroup->execute();

    $modules = array('access_modules' => array());

    $Qaccess = $lC_Database->query('select module, level from :table_administrators_access where administrators_groups_id = :administrators_groups_id');
    $Qaccess->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
    $Qaccess->bindInt(':administrators_groups_id', $id);
    $Qaccess->execute();

    while ( $Qaccess->next() ) {
      $modules['access_modules'][$Qaccess->value('module')] = $Qaccess->valueInt('level');
    }

    $data = array_merge($Qgroup->toArray(), $modules);

    unset($modules);
    $Qaccess->freeResult();
    $Qgroup->freeResult();

    return $data;
  }
 /**
  * Saves the administrator group information
  *
  * @param  integer $id   The administrator id used on update, null on insert
  * @param  array   $data An array containing the administrator information
  * @access public
  * @return array
  */
  public static function saveGroup($id = null, $data) {
    global $lC_Database;

    $error = false;
    $result = array();

    $lC_Database->startTransaction();

    if ( isset($id) && $id != null ) {
      $Qadmin = $lC_Database->query('update :table_administrators_groups set name = :name, last_modified = :last_modified where id = :id');
      $Qadmin->bindInt(':id', $id);
      $Qadmin->bindRaw(':last_modified', 'now()');
    } else {
      $Qadmin = $lC_Database->query('insert into :table_administrators_groups (name, date_added) values (:name, :date_added)');
      $Qadmin->bindRaw(':date_added', 'now()');
    }

    $Qadmin->bindTable(':table_administrators_groups', TABLE_ADMINISTRATORS_GROUPS);
    $Qadmin->bindValue(':name', $data['group_name']);
    $Qadmin->setLogging($_SESSION['module'], $id);
    $Qadmin->execute();

    if ( !$lC_Database->isError() ) {
      if ( !is_numeric($id) ) {
        $id = $lC_Database->nextID();
      }
    } else {
      $error = true;
    }

    if ( $error === false ) {
      if ( !empty($data) ) {

        $accessModulesArr = array();
        foreach ($data as $key => $value) {
          if ($key == 'process' || $key == 'group_name') continue;
          if (!(strpos($key, '-'))) continue;
          $accessModulesArr[$key] = $value;
        }

        // sanity check - since this process is additive, remove any stray access modules for this id first before inserting.
        $Qdel = $lC_Database->query('delete from :table_administrators_access where administrators_groups_id = :administrators_groups_id');
        $Qdel->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
        $Qdel->bindInt(':administrators_groups_id', $id);
        $Qdel->setLogging($_SESSION['module'], $id);
        $Qdel->execute();

        foreach ($accessModulesArr as $module => $level) {
          $Qcheck = $lC_Database->query('select administrators_groups_id from :table_administrators_access where administrators_groups_id = :administrators_groups_id and module = :module limit 1');
          $Qcheck->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
          $Qcheck->bindInt(':administrators_groups_id', $id);
          $Qcheck->bindValue(':module', $module);
          $Qcheck->execute();

          if ( $Qcheck->numberOfRows() < 1 ) {
            $Qinsert = $lC_Database->query('insert into :table_administrators_access (administrators_groups_id, module, level) values (:administrators_groups_id, :module, :level)');
            $Qinsert->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
            $Qinsert->bindInt(':administrators_groups_id', $id);
            $Qinsert->bindValue(':module', $module);
            $Qinsert->bindValue(':level', floor($level/25));
            $Qinsert->setLogging($_SESSION['module'], $id);
            $Qinsert->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
          $Qcheck->freeResult();
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();
      $result['rpcStatus'] = 1; // succeaa
    } else {
      $lC_Database->rollbackTransaction();
      $result['rpcStatus'] = -1; // error saving
    }

    return $result;
  }
 /**
  * Deletes the administrators groups record
  *
  * @param integer $id The administrators groups id to delete
  * @access public
  * @return boolean
  */
  public static function deleteGroup($id) {
    global $lC_Database;

    $lC_Database->startTransaction();

    $Qdel = $lC_Database->query('delete from :table_administrators_access where administrators_groups_id = :administrators_groups_id');
    $Qdel->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
    $Qdel->bindInt(':administrators_groups_id', $id);
    $Qdel->setLogging($_SESSION['module'], $id);
    $Qdel->execute();

    if ( !$lC_Database->isError() ) {
      $Qdel = $lC_Database->query('delete from :table_administrators_groups where id = :id');
      $Qdel->bindTable(':table_administrators_groups', TABLE_ADMINISTRATORS_GROUPS);
      $Qdel->bindInt(':id', $id);
      $Qdel->setLogging($_SESSION['module'], $id);
      $Qdel->execute();

      if ( !$lC_Database->isError() ) {
        $lC_Database->commitTransaction();

        return true;
      }
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /**
  * Returns the access modules tag blocks
  *
  * @param string $_group_id The administrators groups id
  * @access public
  * @return string
  */
  public static function getAccessBlocks($_group_id) {
    global $lC_Database, $lC_Language;

    if ($_group_id == '1') return '<small class="tag red-gradient">' . $lC_Language->get('text_top_administrator') . '</small>';

    $Qcheck = $lC_Database->query('select level from :table_administrators_access where administrators_groups_id = :id');
    $Qcheck->bindTable(':table_administrators_access', TABLE_ADMINISTRATORS_ACCESS);
    $Qcheck->bindInt(':id', $_group_id);
    $Qcheck->execute();

    $levelArr = array();
    while ( $Qcheck->next() ) {
       $levelArr[] = $Qcheck->valueInt('level');
    }
    $checkArr = array_unique($levelArr);

    $tagBlock = '';
    if (count($checkArr) == 1) {
      // all values same
      $tagBlock .= '<small class="tag green-gradient" style="padding:0px 13px;">' . $lC_Language->get('icon_all') . '</small>&nbsp;';
      $maxValue = $checkArr[0];
    } else {
       // values not same
      $tagBlock .= '<small class="tag blue-gradient"">' . $lC_Language->get('icon_limited') . '</small>&nbsp;';
      $maxValue = @max($checkArr);
    }
    switch ($maxValue) {
      case 1 :
        $tagBlock .= '<small class="tag grey-gradient" style="padding:0px 9px;">' . $lC_Language->get('icon_view') . '</small>';
        break;
      case 2 :
        $tagBlock .= '<small class="tag purple-gradient" style="padding:0px 3px;">' . $lC_Language->get('icon_insert') . '</small>';
        break;
      case 3 :
        $tagBlock .= '<small class="tag green-gradient">' . $lC_Language->get('icon_update') . '</small>';
        break;
      case 4 :
        $tagBlock .= '<small class="tag red-gradient">' . $lC_Language->get('icon_delete') . '</small>';
        break;
      default :
        $tagBlock .= '<small class="tag silver-bg">' . $lC_Language->get('icon_none') . '</small>';
    }

    return $tagBlock;
  }
 /**
  * Get the administrator access modules
  *
  * @access public
  * @return array
  */
  public static function getAccessModules() {
    global $lC_Language;

    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/access');
    $lC_DirectoryListing->setIncludeDirectories(false);

    $modules = array();

    foreach ( $lC_DirectoryListing->getFiles() as $file ) {  
      $module = substr($file['name'], 0, strrpos($file['name'], '.'));
      if ( !class_exists('lC_Access_' . ucfirst($module)) ) {
        $lC_Language->loadIniFile('modules/access/' . $file['name']);
        include($lC_DirectoryListing->getDirectory() . '/' . $file['name']);
      }
      $tmp_module = '';
      if ($module == 'product_variants' || $module == 'product_settings') {
        $tmp_module = $module;
      }
      $module = 'lC_Access_' . ucfirst($module);
      $module = new $module(); 
      $module_group = lC_Access::getGroupTitle( $module->getGroup() ); 
      $module_group = str_replace(" ", "_", $module_group);
      $modules[$module_group][] = array('id' => (($tmp_module != '') ? $tmp_module : $module->getModule()),
                                                                          'text' => $module->getTitle());
    }

    ksort($modules);

    return $modules;
  }
 /**
  * upload the profile image
  *
  * @access public
  * @return array
  */
  public static function profileImageUpload($id) {
    global $lC_Database, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/ajax_upload.php'));

    // list of valid extensions, ex. array("jpeg", "jpg", "gif")
    $allowedExtensions = array('gif', 'jpg', 'jpeg', 'png');
    // max file size in bytes
    $sizeLimit = 10 * 1024 * 1024;

    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
    
    $profile_image = $uploader->handleUpload('images/avatar/');
    
    /*$Qcheck = $lC_Database->query('select user_name from :table_administrators where id = ' . (int)$id);
    $Qcheck->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
    $Qcheck->execute();
    
    if ($Qcheck->numberOfRows() > 0) {  
      if (isset($profile_image['filename']) && $profile_image['filename'] != null) {
        $lC_Database->startTransaction();
        $Qimage = $lC_Database->query('update :table_administrators set image = "' . $profile_image['pathinfo']['basename'] . '" where id = ' . (int)$id);
        $Qimage->bindTable(':table_administrators', TABLE_ADMINISTRATORS);
        $Qimage->bindValue(':image', $profile_image['pathinfo']['basename']);
        $Qimage->execute();
      }
    } */
    
    $result = array('result' => 1,
                    'success' => true,
                    'rpcStatus' => RPC_STATUS_SUCCESS,
                    'filename' => $profile_image['pathinfo']['basename']);

    return $result;
  }
}
?>