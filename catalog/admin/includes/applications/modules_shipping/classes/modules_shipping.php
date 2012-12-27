<?php
/*
  $Id: modules_shipping.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Modules_shipping_Admin class manages shipping modules
*/
class lC_Modules_shipping_Admin {
 /*
  * Returns the shipping modules datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Language, $lC_Template;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/shipping');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $files = $lC_DirectoryListing->getFiles();

    $installed_modules = array();
    $result = array('aaData' => array());
    foreach ( $files as $file ) {
      include('includes/modules/shipping/' . $file['name']);
      $class = substr($file['name'], 0, strrpos($file['name'], '.'));
      if ( class_exists('lC_Shipping_' . $class) ) {
        $lC_Language->injectDefinitions('modules/shipping/' . $class . '.xml');
        $module = 'lC_Shipping_' . $class;
        $module = new $module();
        $name = '<td>' . $module->_title . '</td>';
        $sort = '<td>' . $module->_sort_order . '</td>';
        if ( $module->isInstalled() ) {
          $action = '<td class="align-right vertical-center"><span class="button-group compact">
                       <a href="' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? '#' : 'javascript://" onclick="editModule(\'' . $module->_code . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                       <a href="' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? '#' : 'javascript://" onclick="uninstallModule(\'' . $module->_code . '\', \'' . urlencode($module->_title) . '\')') . '" class="button icon-minus-round icon-red with-tooltip' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_uninstall') . '"></a>
                     </span></td>';
        } else {
          $action = '<td class="align-right vertical-center"><span class="button-group compact">
                       <a href="' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? '#' : 'javascript://" onclick="installModule(\'' . $module->_code . '\')') . '" class="button icon-plus-round icon-green' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_install')) . '</a>
                     </span></td>';
        }
        $result['aaData'][] = array("$name", "$sort", "$action");
      }
    }

    return $result;
  }
 /*
  * Return the shipping module information
  *
  * @param integer $id The addon module id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;

    $result = array();

    include('includes/modules/shipping/' . $id . '.php');
    $lC_Language->injectDefinitions('modules/shipping/' . $id . '.xml');
    $module = 'lC_Shipping_' . $id;
    $module = new $module();

    $cnt = 0;
    $keys = '';
    foreach ( $module->getKeys() as $key ) {
      $Qkey = $lC_Database->query('select configuration_title, configuration_value, configuration_description, use_function, set_function from :table_configuration where configuration_key = :configuration_key');
      $Qkey->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qkey->bindValue(':configuration_key', $key);
      $Qkey->execute();
      $keys .= '<label for="' . $Qkey->value('configuration_title') . '" class="label"><strong>' . $Qkey->value('configuration_title') . '</strong>&nbsp;<span class="icon-info-round icon-blue with-tooltip with-small-padding" style="cursor:pointer;" title="' . $Qkey->value('configuration_description') . '" data-tooltip-options=\'{"classes":["anthracite-gradient"]}\'></span>';
      if ( !lc_empty($Qkey->value('set_function')) ) {
        $keys .= lc_call_user_func($Qkey->value('set_function'), $Qkey->value('configuration_value'), $key);
      } else {
        $keys .= lc_draw_input_field('configuration[' . $key . ']', $Qkey->value('configuration_value'), 'class="input full-width"');
      }
      $keys .= '</label><br /><br />';
      $cnt++;
    }
    $result['keys'] = substr($keys, 0, strrpos($keys, '<br /><br />'));
    $result['totalKeys'] = $cnt;

    return $result;
  }
 /*
  * Save the shipping module information
  *
  * @param array $data An array containing the shipping module information
  * @access public
  * @return boolean
  */
  public static function save($data) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    foreach ( $data['configuration'] as $key => $value ) {
      $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
      $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qupdate->bindValue(':configuration_value', is_array($data['configuration'][$key]) ? implode(',', $data['configuration'][$key]) : $value);
      $Qupdate->bindValue(':configuration_key', $key);
      $Qupdate->setLogging($_SESSION['module']);
      $Qupdate->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('configuration');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Install the shipping module
  *
  * @param string $key A string containing the shipping module name
  * @access public
  * @return boolean
  */
  public static function installModule($key) {
    global $lC_Database, $lC_Language;

    if ( file_exists('includes/modules/shipping/' . $key . '.php') ) {
      $lC_Language->injectDefinitions('modules/shipping/' . $key . '.xml');

      include('includes/modules/shipping/' . $key . '.php');

      $module = 'lC_Shipping_' . $key;
      $module = new $module();

      $module->install();

      lC_Cache::clear('modules-shipping');
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }
 /*
  * Uninstall the shipping module
  *
  * @param string $key A string containing the shipping module name
  * @access public
  * @return boolean
  */
  public static function uninstall($key) {
    global $lC_Database, $lC_Language;

    if ( file_exists('includes/modules/shipping/' . $key . '.php') ) {
      $lC_Language->injectDefinitions('modules/shipping/' . $key . '.xml');

      include('includes/modules/shipping/' . $key . '.php');

      $module = 'lC_Shipping_' . $key;
      $module = new $module();

      $module->remove();

      lC_Cache::clear('modules-shipping');
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }
}
?>