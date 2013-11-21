<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: statistics.php v1.0 2013-08-08 datazen $
*/
class lC_Statistics_Admin { 
 /*
  * Returns the statistics modules datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Language, $lC_Vqmod;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/statistics');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('php'); 

    $cnt = 0;
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      include($lC_Vqmod->modCheck('includes/modules/statistics/' . $file['name']));
      $class = 'lC_Statistics_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', substr($file['name'], 0, strrpos($file['name'], '.')))));
      if ( class_exists($class) ) {
        $module = new $class();
        $name = '<td>' . lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, 'statistics&module=' . substr($file['name'], 0, strrpos($file['name'], '.'))), $module->getIcon() . '&nbsp;' . $module->getTitle()) . '</td>';
        $action = '<td class="align-right vertical-center"><span class="button-group compact">
                     <a href="' . ((int)($_SESSION['admin']['access']['banner_manager'] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, 'statistics&module=' . substr($file['name'], 0, strrpos($file['name'], '.')))) . '" class="button icon-gear' . ((int)($_SESSION['admin']['access']['banner_manager'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_run')) . '</a>
                   </span></td>';
        $result['aaData'][] = array("$name", "$action"); 
        $cnt++;
      }
    }
    $result['total'] = $cnt; 

    return $result;
  }
 /*
  * Returns the statistics module datatable data for listings
  *
  * @param string $module The statistics module name
  * @access public
  * @return array
  */
  public static function getData($module) { 
    global $lC_Language, $lC_Vqmod;

    include_once($lC_Vqmod->modCheck('includes/modules/statistics/' . $module . '.php'));
    $class = 'lC_Statistics_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', $module)));
    $lC_Statistics = new $class();
    $lC_Statistics->activate();

    $cnt = 0;
    $col = array();
    $result = array('aaData' => array());
    foreach ( $lC_Statistics->getData() as $data ) {
      if ( !isset($columns) ) $columns = sizeof($data);
      for ( $i = 0; $i < $columns; $i++ ) {
         $col[$i] = '<span>' . $data[$i] . '</span>';
      }
      $cnt++;
      if ($columns == 2) {
        $result['aaData'][] = array("$col[0]", "$col[1]"); 
      } else if ($columns == 3) {
        $result['aaData'][] = array("$col[0]", "$col[1]", "$col[2]");
      } else if ($columns == 4) {
        $result['aaData'][] = array("$col[0]", "$col[1]", "$col[2]", "$col[3]");
      } else if ($columns == 5) {
        $result['aaData'][] = array("$col[0]", "$col[1]", "$col[2]", "$col[3]", "$col[4]");
      }
    }
    $result['total'] = $cnt; 

    return $result;
  }
}
?>