<?php
/*
  $Id: services.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Services_Admin class manages zM services
*/
class lC_Services_Admin {
 /*
  * Returns the services datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Language, $lC_Template, $lC_Vqmod;

    $media = $_GET['media'];
    
    $lC_DirectoryListing = new lC_DirectoryListing('includes/modules/services');
    $lC_DirectoryListing->setIncludeDirectories(false);
    $files = $lC_DirectoryListing->getFiles();

    $installed = explode(';', MODULE_SERVICES_INSTALLED);

    $result = array('aaData' => array());
    foreach ($files as $file) {
      include($lC_Vqmod->modCheck('includes/modules/services/' . $file['name']));
      $class = substr($file['name'], 0, strrpos($file['name'], '.'));
      $module = 'lC_Services_' . $class . '_Admin';
      $module = new $module();
      $name = '<td>' . $module->title . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact" style="white-space:nowrap;">';
      if ( in_array($class, $installed) && !lc_empty($module->keys()) ) {
        $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? '#' : 'javascript://" onclick="editModule(\'' . $class . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>';
        if ( !in_array($class, $installed) ) {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? '#' : 'javascript://" onclick="installModule(\'' . $class . '\')') . '" class="button icon-plus-round icon-green with-tooltip' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? ' disabled' : NULL) . '" title="' .  $lC_Language->get('icon_install') . '"></a>';
        } elseif ( $module->uninstallable ) {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? '#' : 'javascript://" onclick="uninstallModule(\'' . $class . '\', \'' . urlencode($module->title) . '\')') . '" class="button icon-minus-round icon-red with-tooltip' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_uninstall') . '"></a>';
        }
      } else {
        if ( !in_array($class, $installed) ) {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? '#' : 'javascript://" onclick="installModule(\'' . $class . '\')') . '" class="button icon-plus-round icon-green' . ((int)($_SESSION['admin']['access']['modules'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_install')) . '</a>';
        } elseif ( $module->uninstallable ) {
          $action .= '<a href="' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? '#' : 'javascript://" onclick="uninstallModule(\'' . $class . '\', \'' . urlencode($module->title) . '\')') . '" class="button icon-minus-round icon-red' . ((int)($_SESSION['admin']['access']['modules'] < 4) ? ' disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_uninstall')) . '</a>';
        }
      }
      $action .= '</span></td>';
      $result['aaData'][] = array("$name", "$action");
      $cnt++;
    }

    return $result;
  }
 /*
  * Return the service module information
  *
  * @param integer $id The service module id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/modules/services/' . $_GET['module'] . '.php'));
    $module = 'lC_Services_' . $_GET['module'] . '_Admin';
    $module = new $module();

    $cnt = 0;
    $keys = '';
    $result = array();
    foreach ( $module->keys() as $key ) {
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
    $result['title'] = $module->title;

    return $result;
  }
 /*
  * Save the service module information
  *
  * @param array $data An array containing the service module information
  * @access public
  * @return boolean
  */
  public static function save($data) {
    global $lC_Database;

    $error = false;

    $lC_Database->startTransaction();

    foreach ( $data['configuration'] as $key => $value ) {
      $Qsu = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
      $Qsu->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qsu->bindValue(':configuration_value', $value);
      $Qsu->bindvalue(':configuration_key', $key);
      $Qsu->setLogging($_SESSION['module']);
      $Qsu->execute();

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
  * Install the service module
  *
  * @param string $module_key A string containing the service module name
  * @access public
  * @return boolean
  */
  public static function install($module_key) {
    global $lC_Database, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/modules/services/' . $module_key . '.php'));

    $class = 'lC_Services_' . $module_key . '_Admin';
    $installed = explode(';', MODULE_SERVICES_INSTALLED);

    $module = new $class();
    $module->install();

    if ( isset($module->depends) ) {
      if ( is_string($module->depends) && ( ( $key = array_search($module->depends, $installed) ) !== false ) ) {
        if ( isset($installed[$key+1]) ) {
          array_splice($installed, $key+1, 0, $module_key);
        } else {
          $installed[] = $module_key;
        }
      } elseif ( is_array($module->depends) ) {
        foreach ( $module->depends as $depends_module ) {
          if ( ( $key = array_search($depends_module, $installed) ) !== false ) {
            if ( !isset($array_position) || ( $key > $array_position ) ) {
              $array_position = $key;
            }
          }
        }

        if ( isset($array_position) ) {
          array_splice($installed, $array_position+1, 0, $module_key);
        } else {
          $installed[] = $module_key;
        }
      }
    } elseif ( isset($module->precedes) ) {
      if ( is_string($module->precedes) ) {
        if ( ( $key = array_search($module->precedes, $installed) ) !== false ) {
          array_splice($installed, $key, 0, $module_key);
        } else {
          $installed[] = $module_key;
        }
      } elseif ( is_array($module->precedes) ) {
        foreach ( $module->precedes as $precedes_module ) {
          if ( ( $key = array_search($precedes_module, $installed) ) !== false ) {
            if ( !isset($array_position) || ( $key < $array_position ) ) {
              $array_position = $key;
            }
          }
        }

        if ( isset($array_position) ) {
          array_splice($installed, $array_position, 0, $module_key);
        } else {
          $installed[] = $module_key;
        }
      }
    } else {
      $installed[] = $module_key;
    }

    $Qsu = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
    $Qsu->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qsu->bindValue(':configuration_value', implode(';', $installed));
    $Qsu->bindValue(':configuration_key', 'MODULE_SERVICES_INSTALLED');
    $Qsu->execute();

    if ( !$lC_Database->isError() ) {
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }
 /*
  * Uninstall the service module
  *
  * @param string $module_key A string containing the service module name
  * @access public
  * @return boolean
  */
  public static function uninstall($module_key) {
    global $lC_Database, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/modules/services/' . $module_key . '.php'));

    $class = 'lC_Services_' . $module_key . '_Admin';
    $installed = explode(';', MODULE_SERVICES_INSTALLED);

    $module = new $class();
    $module->remove();

    unset($installed[array_search($module_key, $installed)]);

    $Qsu = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
    $Qsu->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qsu->bindValue(':configuration_value', implode(';', $installed));
    $Qsu->bindValue(':configuration_key', 'MODULE_SERVICES_INSTALLED');
    $Qsu->execute();

    if ( !$lC_Database->isError() ) {
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }
}
?>