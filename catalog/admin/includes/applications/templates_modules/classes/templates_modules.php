<?php
/*
  $Id: templates_modules.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Templates_modules_Admin class manages templates modules
*/
class lC_Templates_modules_Admin {
 /*
  * Returns the templates modules datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Language;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('../includes/modules/' . $_GET['set']);
    $lC_DirectoryListing->setIncludeDirectories(false);

    include('../includes/classes/modules.php');

    $lC_Language->load('modules-' . $_GET['set']);

    $result = array('aaData' => array());
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      include('../includes/modules/' . $_GET['set'] . '/' . $file['name']);
      $code = substr($file['name'], 0, strrpos($file['name'], '.'));
      $class = 'lC_' . ucfirst($_GET['set']) . '_' . $code;
      if ( class_exists($class) ) {
        $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $code . '.xml');
        $module = new $class();
        $name = '<td>' . $module->getTitle() . '</td>';
        $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">';
        if ( $module->isInstalled() ) {
          if ( $module->hasKeys() ) {
            $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="editModule(\'' . str_ireplace('.php', '', $file['name']) . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>';
          }
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="uninstallModule(\'' . str_ireplace('.php', '', $file['name']) . '\', \'' . $module->getTitle() . '\')') . '" class="button icon-minus-round icon-red with-tooltip' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_uninstall') . '"></a>';
        } else {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="installModule(\'' . str_ireplace('.php', '', $file['name']) . '\', \'' . $module->getTitle() . '\')') . '" class="button icon-plus-round icon-green with-tooltip' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_install') . '"></a>';
        }
        $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="showInfo(\'' . str_ireplace('.php', '', $file['name']) . '\', \'' . $module->getTitle() . '\')') . '" class="button icon-question-round icon-blue with-tooltip' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_info') . '"></a>';
        $action .= '</span></td>';

        $result['aaData'][] = array("$name", "$action");
        $cnt++;
      }
    }

    return $result;
  }
 /*
  * Return the template module information
  *
  * @param string $id The template module name
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;

    include('../includes/classes/modules.php');
    $lC_Language->load('modules-' . $_GET['set']);

    include('../includes/modules/' . $_GET['set'] . '/' . $id . '.php');
    $module = 'lC_' . ucfirst($_GET['set']) . '_' . $id;
    $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $id . '.xml');
    $module = new $module();

    $cnt = 0;
    $keys = '';
    $result = array();
    foreach ( $module->getKeys() as $key ) {
      $Qkey = $lC_Database->query('select configuration_title, configuration_key, configuration_value, configuration_description, use_function, set_function from :table_configuration where configuration_key = :configuration_key');
      $Qkey->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qkey->bindValue(':configuration_key', $key);
      $Qkey->execute();
      $keys .= '<b>' . $Qkey->value('configuration_title') . '</b><br />' . $Qkey->value('configuration_description') . '<br />';
      if ( !lc_empty($Qkey->value('set_function')) ) {
        $keys .= lc_call_user_func($Qkey->value('set_function'), $Qkey->value('configuration_value'), $key);
      } else {
        $keys .= lc_draw_input_field('configuration[' . $key . ']', $Qkey->value('configuration_value'));
      }
      $keys .= '<br /><br />';
      $cnt++;
    }
    $result['keys'] = substr($keys, 0, strrpos($keys, '<br /><br />'));
    $result['totalKeys'] = $cnt;
    $result['title'] = $module->getTitle();
    $result['author'] = $module->getAuthorName();

    return $result;
  }
 /*
  * Save the template module information
  *
  * @param array $data An array containing the template module information
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
      $Qupdate->bindValue(':configuration_value', $value);
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
  * Install the template module
  *
  * @param string $module_name The template module name
  * @access public
  * @return boolean
  */
  public static function install($module_name) {
    global $lC_Language;

    if ( file_exists('../includes/modules/' . $_GET['set'] . '/' . $module_name . '.php') ) {
      include_once('../includes/classes/modules.php');
      include('../includes/modules/' . $_GET['set'] . '/' . $module_name . '.php');

      $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $module_name . '.xml');

      $class = 'lC_' . ucfirst($_GET['set']) . '_' . $module_name;

      $module = new $class();
      $module->install();

      lC_Cache::clear('configuration');
      lC_Cache::clear('modules_' . $_GET['set']);
      lC_Cache::clear('templates_' . $_GET['set'] . '_layout');

      return true;
    }

    return false;
  }
 /*
  * Uninstall the template module
  *
  * @param string $module_name The template module name
  * @access public
  * @return boolean
  */
  public static function uninstall($module_name) {
    global $lC_Language;
    if ( file_exists('../includes/modules/' . $_GET['set'] . '/' . $module_name . '.php') ) {
      include_once('../includes/classes/modules.php');
      include('../includes/modules/' . $_GET['set'] . '/' . $module_name . '.php');

      $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $module_name . '.xml');

      $class = 'lC_' . ucfirst($_GET['set']) . '_' . $module_name;

      $module = new $class();
      $module->remove();

      lC_Cache::clear('configuration');
      lC_Cache::clear('modules_' . $_GET['set']);
      lC_Cache::clear('templates_' . $_GET['set'] . '_layout');

      return true;
    }

    return false;
  }
}
?>