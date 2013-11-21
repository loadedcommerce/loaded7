<?php
/**
  @package    catalog::admin::templates
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: core.php v1.0 2013-08-08 datazen $
*/
class lC_Template_core {
  var $_id,
      $_title = 'Loaded Commerce Bootstrap 3.0 Core Template',
      $_code = 'core',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_markup_version = 'HTML 5.0',
      $_css_based = '1', /* 0=No; 1=Yes */
      $_medium = 'Responsive UI',
      $_groups = array('boxes' => array('left', 'right'),
                       'content' => array('before', 'after')),
      $_keys;

  public function getID() {
    global $lC_Database;

    if (isset($this->_id) === false) {
      $Qtemplate = $lC_Database->query('select id from :table_templates where code = :code');
      $Qtemplate->bindTable(':table_templates', TABLE_TEMPLATES);
      $Qtemplate->bindvalue(':code', $this->_code);
      $Qtemplate->execute();

      $this->_id = $Qtemplate->valueInt('id');
    }

    return $this->_id;
  }

  public function getTitle() {
    return $this->_title;
  }

  public function getCode() {
    return $this->_code;
  }

  public function getAuthorName() {
    return $this->_author_name;
  }

  public function getAuthorAddress() {
    return $this->_author_www;
  }

  public function getMarkup() {
    return $this->_markup_version;
  }

  public function isCSSBased() {
    return ($this->_css_based == '1');
  }

  public function getMedium() {
    return $this->_medium;
  }

  public function getGroups($group) {
    return $this->_groups[$group];
  }

  public function install() {
    global $lC_Database;

    $Qinstall = $lC_Database->query('insert into :table_templates (title, code, author_name, author_www, markup_version, css_based, medium) values (:title, :code, :author_name, :author_www, :markup_version, :css_based, :medium)');
    $Qinstall->bindTable(':table_templates', TABLE_TEMPLATES);
    $Qinstall->bindValue(':title', $this->_title);
    $Qinstall->bindValue(':code', $this->_code);
    $Qinstall->bindValue(':author_name', $this->_author_name);
    $Qinstall->bindValue(':author_www', $this->_author_www);
    $Qinstall->bindValue(':markup_version', $this->_markup_version);
    $Qinstall->bindValue(':css_based', $this->_css_based);
    $Qinstall->bindValue(':medium', $this->_medium);
    $Qinstall->execute();

    $id = $lC_Database->nextID();

    $data = array('categories' => array('*', 'left', '100'),
                  'manufacturers' => array('*', 'left', '200'));

    $Qboxes = $lC_Database->query('select id, code from :table_templates_boxes');
    $Qboxes->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qboxes->execute();

    while ($Qboxes->next()) {
      if (isset($data[$Qboxes->value('code')])) {
        $Qrelation = $lC_Database->query('insert into :table_templates_boxes_to_pages (templates_boxes_id, templates_id, content_page, boxes_group, sort_order, page_specific) values (:templates_boxes_id, :templates_id, :content_page, :boxes_group, :sort_order, :page_specific)');
        $Qrelation->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
        $Qrelation->bindInt(':templates_boxes_id', $Qboxes->valueInt('id'));
        $Qrelation->bindInt(':templates_id', $id);
        $Qrelation->bindValue(':content_page', $data[$Qboxes->value('code')][0]);
        $Qrelation->bindValue(':boxes_group', $data[$Qboxes->value('code')][1]);
        $Qrelation->bindInt(':sort_order', $data[$Qboxes->value('code')][2]);
        $Qrelation->bindInt(':page_specific', 0);
        $Qrelation->execute();
      }
    }
  }

  public function remove() {
    global $lC_Database;

    $Qdel = $lC_Database->query('delete from :table_templates_boxes_to_pages where templates_id = :templates_id');
    $Qdel->bindTable(':table_templates_boxes_to_pages', TABLE_TEMPLATES_BOXES_TO_PAGES);
    $Qdel->bindValue(':templates_id', $this->getID());
    $Qdel->execute();

    $Qdel = $lC_Database->query('delete from :table_templates where id = :id');
    $Qdel->bindTable(':table_templates', TABLE_TEMPLATES);
    $Qdel->bindValue(':id', $this->getID());
    $Qdel->execute();

    if ($this->hasKeys()) {
      $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key in (":configuration_key")');
      $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qdel->bindRaw(':configuration_key', implode('", "', $this->getKeys()));
      $Qdel->execute();
    }
  }

  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array();
    }

    return $this->_keys;
  }

  public function hasKeys() {
    static $has_keys;

    if (isset($has_keys) === false) {
      $has_keys = (sizeof($this->getKeys()) > 0) ? true : false;
    }

    return $has_keys;
  }

  public function isInstalled() {
    global $lC_Database;

    static $is_installed;

    if (isset($is_installed) === false) {
      $Qcheck = $lC_Database->query('select id from :table_templates where code = :code');
      $Qcheck->bindTable(':table_templates', TABLE_TEMPLATES);
      $Qcheck->bindValue(':code', $this->_code);
      $Qcheck->execute();

      $is_installed = ($Qcheck->numberOfRows()) ? true : false;
    }

    return $is_installed;
  }

  public function isActive() {
    return true;
  }
}
?>