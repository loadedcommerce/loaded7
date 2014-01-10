<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: images.php v1.0 2013-08-08 datazen $
*/
class lC_Images_Admin { 
 /*
  * Returns the image manager datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Language, $lC_Vqmod;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/image');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('php');

    $cnt = 0;
    $result = array('aaData' => array());
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      include($lC_Vqmod->modCheck('includes/modules/image/' . $file['name']));
      $class = 'lC_Image_Admin_' . substr($file['name'], 0, strrpos($file['name'], '.')); 
      if ( class_exists($class) ) {  
        $module = new $class();  
        $name = '<td>' . $module->getTitle() . '</td>';
        $action = '<td class="align-right vertical-center"><span class="button-group">
                   <a href="' . ((int)($_SESSION['admin']['access']['images'] < 3) ? '#' : 'javascript://" onclick="doAction(\'' . str_ireplace('.php', '', $file['name']) . '\', \'' . urlencode(str_ireplace('.php', '', $file['name'])) . '\')') . '" class="button icon-gear' . ((int)($_SESSION['admin']['access']['images'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_run')) . '</a>
                 </span></td>';
        $result['aaData'][] = array("$name", "$action"); 
        $cnt++;
      }                            
    }
    $result['total'] = $cnt; 

    return $result;
  }
 /*
  * Returns the number if images/groups
  *
  * @access public
  * @return array
  */
  public static function check() {
    global $lC_Language, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/modules/image/check.php'));
    $class = 'lC_Image_Admin_check';
    $lC_Images = new $class();

    $lC_Images->activate();

    $cnt = 0;
    $result = array('aaData' => array());
    foreach ( $lC_Images->getData() as $data ) {
      $group = '<span>' . $data[0] . '</span>'; 
      $total = '<span>' . $data[1] . '</span>'; 

      $result['aaData'][] = array("$group", "$total"); 
      $cnt++;
    }
    $result['total'] = $cnt;

    return $result;
  }
 /*
  * Return the images information
  *
  * @access public
  * @return array
  */
  public static function getInfo() {
    global $lC_Language, $lC_Template, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/modules/image/resize.php'));
    $class = 'lC_Image_Admin_resize';
    $lC_Images = new $class();

    $result = array();
    $html = '<table border="0" width="100%" cellspacing="0" cellpadding="2">';
    foreach ( $lC_Images->getParameters() as $params ) {
      $html .= '<tr><td width="40%"><b>' . $params['key'] . '</b></td><td width="60%">' . $params['field'] . '</td></tr><tr><td>&nbsp;</td></tr>';
    }
    $html .= '</table>';
    
    $result['html'] = $html;

    return $result;
  }
 /*
  * Batch resize images
  *
  * @access public
  * @return array
  */
  public static function resizeBatch() {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/image.php'));
    $lC_Image_Admin = new lC_Image_Admin();

    $overwrite = (isset($_GET['overwrite']) && $_GET['overwrite'] == '1') ? true : false;
    $result = array();

    if (!isset($_GET['groups']) || !is_array($_GET['groups'])) {
      return false;
    }

    $Qoriginals = $lC_Database->query('select image from :table_products_images');
    $Qoriginals->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qoriginals->execute();

    $counter = array();
    while ($Qoriginals->next()) {  
      foreach ($_GET['groups'] as $groupID) {
        if ( ($groupID != '1') && in_array($groupID, $_GET['groups'])) {
          if (!isset($counter[$groupID])) {
            $counter[$groupID] = 0;
          }
          if ( ($overwrite === true) || !file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $group['code'] . '/' . $Qoriginals->value('image')) ) {
            $lC_Image_Admin->resize($Qoriginals->value('image'), $groupID);
            $counter[$groupID]++;
          }
        }
      }
    }
    $cnt = 0;
    $html = '';
    foreach ($counter as $key => $value) {
      // get group title
      $Qgroup = $lC_Database->query('select title from :table_products_images_groups where id = :id');
      $Qgroup->bindTable(':table_products_images_groups', TABLE_PRODUCTS_IMAGES_GROUPS);
      $Qgroup->bindInt(':id', $key);
      $Qgroup->execute();

      $class = ($cnt % 2) ? 'even' : 'odd';

      $html .= '<tr class="' . $class . '">';
      $html .= '<td class="dataColResultsGroup"><span>' . $Qgroup->value('title') . '</span></td>'; 
      $html .= '<td class="dataColResultsTotal"><span>' . $value . '</span></td>'; 
      $html .= '</tr>';

      $cnt++;
    }
    $result['html'] = $html;

    $Qgroup->freeResult; 

    //remove the resize flag if it exists
    if (file_exists(DIR_FS_WORK . 'resize.tmp')) {
      @unlink(DIR_FS_WORK . 'resize.tmp');
    }

    return $result;
  }
}
?>
