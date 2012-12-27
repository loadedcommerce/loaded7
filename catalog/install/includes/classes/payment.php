<?php
/*
  $Id: payment.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Payment_Admin {
  var $_group = 'payment';

  function install() {
    global $lC_Database, $lC_Language;

    $Qinstall = $lC_Database->query('insert into :table_templates_boxes (title, code, author_name, author_www, modules_group) values (:title, :code, :author_name, :author_www, :modules_group)');
    $Qinstall->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qinstall->bindValue(':title', $this->_title);
    $Qinstall->bindValue(':code', $this->_code);
    $Qinstall->bindValue(':author_name', $this->_author_name);
    $Qinstall->bindValue(':author_www', $this->_author_www);
    $Qinstall->bindValue(':modules_group', $this->_group);
    $Qinstall->execute();

//      foreach ($lC_Language->getAll() as $key => $value) {
      if (file_exists(dirname(__FILE__) . '/../languages/en_US/modules/' . $this->_group . '/' . $this->_code . '.xml')) {
        foreach ($lC_Language->extractDefinitions('en_US/modules/' . $this->_group . '/' . $this->_code . '.xml') as $def) {
          $Qcheck = $lC_Database->query('select id from :table_languages_definitions where definition_key = :definition_key and content_group = :content_group and languages_id = :languages_id limit 1');
          $Qcheck->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
          $Qcheck->bindValue(':definition_key', $def['key']);
          $Qcheck->bindValue(':content_group', $def['group']);
          $Qcheck->bindInt(':languages_id', 1 /*$value['id']*/);
          $Qcheck->execute();

          if ($Qcheck->numberOfRows() === 1) {
            $Qdef = $lC_Database->query('update :table_languages_definitions set definition_value = :definition_value where definition_key = :definition_key and content_group = :content_group and languages_id = :languages_id');
          } else {
            $Qdef = $lC_Database->query('insert into :table_languages_definitions (languages_id, content_group, definition_key, definition_value) values (:languages_id, :content_group, :definition_key, :definition_value)');
          }
          $Qdef->bindTable(':table_languages_definitions', TABLE_LANGUAGES_DEFINITIONS);
          $Qdef->bindInt(':languages_id', 1 /*$value['id']*/);
          $Qdef->bindValue(':content_group', $def['group']);
          $Qdef->bindValue(':definition_key', $def['key']);
          $Qdef->bindValue(':definition_value', $def['value']);
          $Qdef->execute();
        }
      }
//      }
  }
}
?>