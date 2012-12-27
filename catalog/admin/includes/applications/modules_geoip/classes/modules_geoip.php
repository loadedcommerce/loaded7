<?php
/*
  $Id: images.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Modules_geoip_Admin class manages geoip modules
*/
class lC_Modules_geoip_Admin {
 /*
  * Returns the geoip modules datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Language, $lC_Template;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/geoip');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $files = $lC_DirectoryListing->getFiles();

    $installed = explode(';', MODULE_SERVICES_INSTALLED);

    $result = array('aaData' => array());
    foreach ( $files as $file ) {
      include('includes/modules/geoip/' . $file['name']);
      $class = substr($file['name'], 0, strrpos($file['name'], '.'));
      if (class_exists('lC_GeoIP_' . $class)) {
        $lC_Language->loadIniFile('modules/geoip/' . $class . '.php');
        $module = 'lC_GeoIP_' . $class;
        $module = new $module();
        $name = '<td>' . $module->getTitle() . '</td>';
        $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">';
        if ( $module->isInstalled() ) {
          if ( sizeof($module->getKeys()) > 0 ) {
            $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? '#' : 'javascript://" onclick="editModule(\'' . $class . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>';
            $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? '#' : 'javascript://" onclick="showInfo(\'' . $class . '\', \'' . urlencode($module->getTitle()) . '\')') . '" class="button icon-question-round icon-blue with-tooltip' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('button_info') . '"></a>';
          } else {
            $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? '#' : 'javascript://" onclick="showInfo(\'' . $class . '\', \'' . urlencode($module->getTitle()) . '\')') . '" class="button icon-question-round icon-blue' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('button_info')) . '</a>';
          }
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? '#' : 'javascript://" onclick="uninstallModule(\'' . $class . '\', \'' . urlencode($module->getTitle()) . '\')') . '" class="button icon-minus-round icon-red with-tooltip' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_uninstall') . '"></a>';
        } else {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? '#' : 'javascript://" onclick="showInfo(\'' . $class . '\', \'' . urlencode($module->getTitle()) . '\')') . '" class="button icon-question-round icon-blue' . ((int)($_SESSION['admin']['access']['modules'] < 2) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('button_info')) . '</a>';
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? '#' : 'javascript://" onclick="installModule(\'' . $class . '\', \'' . urlencode($module->getTitle()) . '\')') . '" class="button icon-plus-round icon-green with-tooltip' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_install') . '"></a>';
        }
        $action .= '</span></td>';
      }
      $result['aaData'][] = array("$name", "$action");
    }

    return $result;
  }
 /*
  * Return the geoip module information
  *
  * @param integer $id The geoip module id
  * @access public
  * @return array
  */
  public static function getInfo($id) {
    global $lC_Database, $lC_Language;

    $result = array();

    include('includes/modules/geoip/' . $id . '.php');
    $lC_Language->loadIniFile('modules/geoip/' . $id . '.php');
    $module = 'lC_GeoIP_' . $id;
    $module = new $module();

    $result['title'] = $module->getTitle();
    $result['description'] = $module->getDescription();
    $result['author'] =  $module->getAuthorName() . ' (' . $module->getAuthorAddress() . ')</td>';

    return $result;
  }
 /*
  * Return the geoip module data
  *
  * @param integer $id The geoip module id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;

    $result = array();

    include('includes/modules/geoip/' . $_GET['module'] . '.php');
    $lC_Language->loadIniFile('modules/geoip/' . $_GET['module'] . '.php');
    $module = 'lC_GeoIP_' . $_GET['module'];
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
  * Save the geoip module information
  *
  * @param array $data An array containing the geoip module information
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
  * Install the geoip module
  *
  * @param string $key A string containing the geoip module name
  * @access public
  * @return boolean
  */
  public static function installModule($key) {
    global $lC_Language;

    if ( file_exists('includes/modules/geoip/' . $key . '.php') ) {
      $lC_Language->loadIniFile('modules/geoip/' . $key . '.php');

      include('includes/modules/geoip/' . $key . '.php');

      $module = 'lC_GeoIP_' . $key;
      $module = new $module();

      $module->install();

      lC_Cache::clear('modules-geoip');
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }
 /*
  * Uninstall the geoip module
  *
  * @param string $key A string containing the geoip module name
  * @access public
  * @return boolean
  */
  public static function uninstall($key) {
    global $lC_Language;

    if ( file_exists('includes/modules/geoip/' . $key . '.php') ) {
      $lC_Language->loadIniFile('modules/geoip/' . $key . '.php');

      include('includes/modules/geoip/' . $key . '.php');

      $module = 'lC_GeoIP_' . $key;
      $module = new $module();

      $module->remove();

      lC_Cache::clear('modules-geoip');
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }
}
?>