<?php
/*
  $Id: manufacturers.php v1.0 2013-01-01 datazen $ 

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Boxes_manufacturers extends lC_Modules {
    var $_title,
        $_code = 'manufacturers',
        $_author_name = 'LoadedCommerce',
        $_author_www = 'http://www.loadedcommerce.com',
        $_group = 'boxes';

    function lC_Boxes_manufacturers() {
      global $lC_Language;
      
      if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

      $this->_title = $lC_Language->get('box_manufacturers_heading');
    }

    function initialize() {
      global $lC_Database, $lC_Language;

      $Qmanufacturers = $lC_Database->query('select manufacturers_id as id, manufacturers_name as text from :table_manufacturers order by manufacturers_name');
      $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
      $Qmanufacturers->setCache('manufacturers');
      $Qmanufacturers->execute();

      $manufacturers_array = array(array('id' => '', 'text' => $lC_Language->get('pull_down_default')));

      while ($Qmanufacturers->next()) {
        $manufacturers_array[] = $Qmanufacturers->toArray();
      }

      $Qmanufacturers->freeResult();

      $this->_content = '<ul class="category departments"><form id="brands" name="manufacturers" action="' . lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false) . '" method="get">' .
                        lc_draw_pull_down_menu('manufacturers', $manufacturers_array, null, 'onchange="this.form.submit();" size="' . BOX_MANUFACTURERS_LIST_SIZE . '" style="width: 100%"') . lc_draw_hidden_session_id_field() .
                        '</form></ul><br><br>';
    }

    function install() {
      global $lC_Database;

      parent::install();

      $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Manufacturers List Size', 'BOX_MANUFACTURERS_LIST_SIZE', '1', 'The size of the manufacturers pull down menu listing.', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('BOX_MANUFACTURERS_LIST_SIZE');
      }

      return $this->_keys;
    }
  }
?>
