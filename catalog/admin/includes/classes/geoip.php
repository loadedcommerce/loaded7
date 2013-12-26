<?php
/**
  @package    admin::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: geoip.php v1.0 2013-08-08 datazen $
*/
class lC_GeoIP_Admin {
  var $_group = 'geoip',
      $_status = false,
      $_active = false;

  // class methods
  public function load() {
    global $lC_Language, $lC_Vqmod;

    if (defined('MODULE_DEFAULT_GEOIP') && !lc_empty(MODULE_DEFAULT_GEOIP) && file_exists('includes/modules/geoip/' . MODULE_DEFAULT_GEOIP . '.php')) {
      $lC_Language->loadIniFile('modules/geoip/' . MODULE_DEFAULT_GEOIP . '.php');
      include($lC_Vqmod->modCheck('includes/modules/geoip/' . MODULE_DEFAULT_GEOIP . '.php'));
      $module = 'lC_GeoIP_' . MODULE_DEFAULT_GEOIP;
      return new $module();
    } else {
      return new lC_GeoIP_Admin();
    }
  }

  public function activate() {
  }

  public function deactivate() {
  }

  public function getCountryISOCode2() {
  }

  public function getCountryName() {
  }

  public function getData() {
    return array();
  }

  public function isValid() {
    return false;
  }

  public function isActive() {
    return $this->_active;
  }

  public function getCode() {
    return $this->_code;
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getDescription() {
    return $this->_description;
  }

  public function getAuthorName() {
    return $this->_author_name;
  }

  public function getAuthorAddress() {
    return $this->_author_www;
  }

  public function hasKeys() {
    static $has_keys;

    if (isset($has_keys) === false) {
      $has_keys = (sizeof($this->getKeys()) > 0) ? true : false;
    }

    return $has_keys;
  }

  public function getKeys() {
    return array();
  }

  public function isInstalled() {
    return $this->_status;
  }

  public function install() {
    global $lC_Database, $lC_Language, $lC_Vqmod;

    if (defined('MODULE_DEFAULT_GEOIP')) {
      include($lC_Vqmod->modCheck('includes/modules/geoip/' . MODULE_DEFAULT_GEOIP . '.php'));
      $module = 'lC_GeoIP_' . MODULE_DEFAULT_GEOIP;
      $module = new module();
      $module->remove();
    }

    $Qcfg = $lC_Database->query('insert into :table_configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, date_added) values (:configuration_title, :configuration_key, :configuration_value, :configuration_description, :configuration_group_id, :date_added)');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_title', 'Default GeoIP Module');
    $Qcfg->bindValue(':configuration_key', 'MODULE_DEFAULT_GEOIP');
    $Qcfg->bindValue(':configuration_value', $this->_code);
    $Qcfg->bindvalue(':configuration_description', 'Default GeoIP module.');
    $Qcfg->bindInt(':configuration_group_id', 6);
    $Qcfg->bindRaw(':date_added', 'now()');
    $Qcfg->execute();

    $Qinstall = $lC_Database->query('insert into :table_templates_boxes (title, code, author_name, author_www, modules_group) values (:title, :code, :author_name, :author_www, :modules_group)');
    $Qinstall->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qinstall->bindValue(':title', $this->_title);
    $Qinstall->bindValue(':code', $this->_code);
    $Qinstall->bindValue(':author_name', $this->_author_name);
    $Qinstall->bindValue(':author_www', $this->_author_www);
    $Qinstall->bindValue(':modules_group', $this->_group);
    $Qinstall->execute();

/*
    foreach ($lC_Language->getAll() as $key => $value) {
      if (file_exists('../includes/languages/' . $key . '/modules/' . $this->_group . '/' . $this->_code . '.xml')) {
        foreach ($lC_Language->extractDefinitions($key . '/modules/' . $this->_group . '/' . $this->_code . '.xml') as $def) {
          $Qcheck = $lC_Database->query('select id from :table_languages_definitions where definition_key = :definition_key and content_group = :content_group and languages_id = :languages_id limit 1');
          $Qcheck->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
          $Qcheck->bindValue(':definition_key', $def['key']);
          $Qcheck->bindValue(':content_group', $def['group']);
          $Qcheck->bindInt(':languages_id', $value['id']);
          $Qcheck->execute();

          if ($Qcheck->numberOfRows() === 1) {
            $Qdef = $lC_Database->query('update :table_languages_definitions set definition_value = :definition_value where definition_key = :definition_key and content_group = :content_group and languages_id = :languages_id');
          } else {
            $Qdef = $lC_Database->query('insert into :table_languages_definitions (languages_id, content_group, definition_key, definition_value) values (:languages_id, :content_group, :definition_key, :definition_value)');
          }
          $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
          $Qdef->bindInt(':languages_id', $value['id']);
          $Qdef->bindValue(':content_group', $def['group']);
          $Qdef->bindValue(':definition_key', $def['key']);
          $Qdef->bindValue(':definition_value', $def['value']);
          $Qdef->execute();
        }
      }
    }

    lC_Cache::clear('languages');
*/

    lC_Cache::clear('configuration');
  }

  public function remove() {
    global $lC_Database, $lC_Language;

    $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key = :configuration_key');
    $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qdel->bindValue(':configuration_key', 'MODULE_DEFAULT_GEOIP');
    $Qdel->execute();

    $Qdel = $lC_Database->query('delete from :table_templates_boxes where code = :code and modules_group = :modules_group');
    $Qdel->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qdel->bindValue(':code', $this->_code);
    $Qdel->bindValue(':modules_group', $this->_group);
    $Qdel->execute();

    if ($this->hasKeys()) {
      $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key in (":configuration_key")');
      $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qdel->bindRaw(':configuration_key', implode('", "', $this->getKeys()));
      $Qdel->execute();
    }

/*
    if (file_exists('../includes/languages/' . $lC_Language->getCode() . '/modules/' . $this->_group . '/' . $this->_code . '.xml')) {
      foreach ($lC_Language->extractDefinitions($lC_Language->getCode() . '/modules/' . $this->_group . '/' . $this->_code . '.xml') as $def) {
        $Qdel = $lC_Database->query('delete from :table_languages_definitions where definition_key = :definition_key and content_group = :content_group');
        $Qdel->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
        $Qdel->bindValue(':definition_key', $def['key']);
        $Qdel->bindValue(':content_group', $def['group']);
        $Qdel->execute();
      }

      lC_Cache::clear('languages');
    }
*/

    lC_Cache::clear('configuration');
  }
}
?>