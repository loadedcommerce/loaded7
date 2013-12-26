<?php
/**
  $Id: payment.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Vqmod;

include($lC_Vqmod->modCheck('../includes/classes/payment.php'));

class lC_Payment_Admin extends lC_Payment {
  var $_group = 'payment';

  // class methods
  function hasKeys() {
    static $has_keys;

    if (isset($has_keys) === false) {
      $has_keys = (sizeof($this->getKeys()) > 0) ? true : false;
    }

    return $has_keys;
  }

  function getPostTransactionActions($history) {
    return false;
  }

  function install() {
    global $lC_Database, $lC_Language;

      $Qinstall = $lC_Database->query('insert into :table_templates_boxes (title, code, author_name, author_www, modules_group) values (:title, :code, :author_name, :author_www, :modules_group)');
      $Qinstall->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
      $Qinstall->bindValue(':title', strip_tags($this->_title));
      $Qinstall->bindValue(':code', $this->_code);
      $Qinstall->bindValue(':author_name', $this->_author_name);
      $Qinstall->bindValue(':author_www', $this->_author_www);
      $Qinstall->bindValue(':modules_group', $this->_group);
      $Qinstall->execute();
      
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
  }

  function remove() {
    global $lC_Database, $lC_Language;

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
  }
}
?>