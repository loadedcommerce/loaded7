<?php
/**
  @package    catalog::addons::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;

if (!defined('DIR_FS_ADMIN')) return false;

include_once($lC_Vqmod->modCheck(DIR_FS_ADMIN . 'includes/applications/categories/classes/categories.php'));

class lC_Categories_Admin_Pro extends lC_Categories_Admin {
 /*
  * Save the category record
  *
  * @param integer $id The category id on update, null on insert
  * @param array $data The category information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language;
    
    $category_id = parent::save($id, $data);

    $error = false;

    $lC_Database->startTransaction();
      
    $levels = '';
    if (is_array($data['access_levels'])) {
      foreach ($data['access_levels'] as $key => $val) {
        $levels .= $key . ';';
      }
      $levels = substr($levels, 0, -1);
    }

    $Qcat = $lC_Database->query('update :table_categories set `access_levels` = :access_levels where `categories_id` = :categories_id');
    $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcat->bindInt(':categories_id', $category_id);
    $Qcat->bindValue(':access_levels', $levels);
    $Qcat->setLogging($_SESSION['module'], $category_id);
    $Qcat->execute();

    if ( !$lC_Database->isError() ) {
      $lC_Database->commitTransaction();
      lC_Cache::clear('categories');

      return $category_id; // used for the save_close buttons
    }

    $lC_Database->rollbackTransaction();

    return false;
  }     
}