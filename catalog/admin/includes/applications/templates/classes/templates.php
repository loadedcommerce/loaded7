<?php
/*
  $Id: templates.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Templates_Admin class manages templates
*/
class lC_Templates_Admin {
 /*
  * Returns the templates datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Language;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/templates');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $files = $lC_DirectoryListing->getFiles();

    $cnt = 0;
    $result = array('aaData' => array());
    foreach ( $files as $file ) {
      include('includes/templates/' . $file['name']);
      $code = substr($file['name'], 0, strrpos($file['name'], '.'));
      $class = 'lC_Template_' . $code;
      if ( class_exists($class) ) {
        $module = new $class();
        $module_title = $module->getTitle();
        if ( $module->getCode() == DEFAULT_TEMPLATE ) {
          $module_title .= ' (' . $lC_Language->get('default_entry') . ')';
        }
        $name = '<td>' . $module_title . '</td>';
        $action = '<td class="align-right vertical-center"><span class="button-group compact">';
        if ( $module->isInstalled() && $module->isActive() ) {
          if ( $module->hasKeys() || ( $module->getCode() != DEFAULT_TEMPLATE ) ) {
            $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="editTemplate(\'' . str_ireplace('.php', '', $file['name']) . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>';
          }
          if ($module->getCode() != DEFAULT_TEMPLATE) {
            $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="uninstallTemplate(\'' . str_ireplace('.php', '', $file['name']) . '\', \'' . $module->getTitle() . '\')') . '" class="button icon-minus-round icon-red with-tooltip' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_uninstall') . '"></a>';
          }
        } else {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="installTemplate(\'' . str_ireplace('.php', '', $file['name']) . '\', \'' . $module->getTitle() . '\')') . '" class="button icon-plus-round icon-green with-tooltip' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_install') . '"></a>';
        }
        $action .= '<a href="' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? '#' : 'javascript://" onclick="showInfo(\'' . str_ireplace('.php', '', $file['name']) . '\', \'' . $module->getTitle() . '\')') . '" class="button icon-question-round icon-blue with-tooltip' . ((int)($_SESSION['admin']['access']['templates'] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_info') . '"></a></td>';

        $result['aaData'][] = array("$name", "$action");
        $cnt++;
      }
    }

    return $result;
  }
 /*
  * Return the template information
  *
  * @param string $id The template name
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Language;

    include('includes/templates/' . $id . '.php');
    $module = 'lC_Template_' . $id;
    $module = new $module();

    $cnt = 0;
    $keys = '';
    $result = array();

    foreach ( $module->getKeys() as $key => $value ) {
      $keys .= '<b>' . $value['title'] . '</b><br />' . $value['description'] . '<br />';
      if ( !empty($value['set_function']) ) {
        $keys .= lc_call_user_func($value['set_function'], $value['value'], $key);
      } else {
        $keys .= lc_draw_input_field('configuration[' . $key . ']', $value['value']);
      }
      $keys .= '<br /><br />';
      $cnt++;
    }
    $keys = substr($keys, 0, strrpos($keys, '<br /><br />'));

    $result['keys'] = substr($keys, 0, strrpos($keys, '<br /><br />'));
    $result['totalKeys'] = $cnt;
    $result['title'] = $module->getTitle();
    $result['code'] = $module->getCode();
    $result['author'] = $module->getAuthorName() . ' (' . $module->getAuthorAddress() . ')';
    $result['markup'] = $module->getMarkup();
    $result['css_based'] = ( $module->isCSSBased() ? 'Yes' : 'No' );
    $result['medium'] = $module->getMedium();

    return $result;
  }
 /*
  * Save the template information
  *
  * @param string $module_name The template module configuration value used on update, null on insert
  * @param array $data An array containing the template module configuration data
  * @access public
  * @return boolean
  */
  public static function save($module_name, $data) {
    global $lC_Database;

    $default = (isset($_GET['default']) && $_GET['default'] == 'on') ? true : false;

    $error = false;

    $lC_Database->startTransaction();

    if ( !empty($data['configuration']) ) {
      if ( $default === true ) {
        $data['configuration']['DEFAULT_TEMPLATE'] = $module_name;
      }

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
    } elseif ( $default === true ) {
      $Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
      $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qupdate->bindValue(':configuration_value', $module_name);
      $Qupdate->bindValue(':configuration_key', 'DEFAULT_TEMPLATE');
      $Qupdate->setLogging($_SESSION['module']);
      $Qupdate->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
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
    if ( file_exists('includes/templates/' . $module_name . '.php') ) {
      include('includes/templates/' . $module_name . '.php');

      $class = 'lC_Template_' . $module_name;

      $module = new $class();
      $module->install();

      lC_Cache::clear('configuration');
      lC_Cache::clear('templates');

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
    if ( file_exists('includes/templates/' . $module_name . '.php') ) {
      include('includes/templates/' . $module_name . '.php');

      $class = 'lC_Template_' . $module_name;

      $module = new $class();
      $module->remove();

      lC_Cache::clear('configuration');
      lC_Cache::clear('templates');

      return true;
    }

    return false;
  }
}
?>