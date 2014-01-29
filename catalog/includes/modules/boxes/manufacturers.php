<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturers.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_manufacturers extends lC_Modules {
  var $_title,
      $_code = 'manufacturers',
      $_author_name = 'LoadedCommerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_manufacturers() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_manufacturers_heading');
  }

  public function initialize() {
    global $lC_Database, $lC_Language;

    $Qmanufacturers = $lC_Database->query('select manufacturers_id as id, manufacturers_name as text from :table_manufacturers order by manufacturers_name');
    $Qmanufacturers->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qmanufacturers->setCache('manufacturers');
    $Qmanufacturers->execute();
    
    $manufacturers = '';
    $manufacturers_array = array(array('id' => '', 'text' => $lC_Language->get('pull_down_default')));

    while ($Qmanufacturers->next()) {
      $manufacturers_array[] = $Qmanufacturers->toArray();
    }
    $Qmanufacturers->freeResult();    
    
    foreach ($manufacturers_array as $man) {
      $manufacturers .= '<option value="' . $man['id'] . '">' . $man['text'] . '</option>';
    }
    $this->_content = '<li class="box-manufacturers-selection">' . 
                      '  <select name="manufacturers" class="box-manufacturers-select" onchange="$(this).closest(\'form\').submit();" size="' . BOX_MANUFACTURERS_LIST_SIZE . '">' . 
                           $manufacturers . 
                      '  </select>' . lc_draw_hidden_session_id_field() . "\n";
                      '</li>' . "\n";
  }

  public function install() {
    global $lC_Database;

    parent::install();

    $lC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Manufacturers List Size', 'BOX_MANUFACTURERS_LIST_SIZE', '1', 'The size of the manufacturers pull down menu listing.', '6', '0', now())");
  }

  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('BOX_MANUFACTURERS_LIST_SIZE');
    }

    return $this->_keys;
  }
}
?>
