<?php
/**
  @package    catalog::modules::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: currencies.php v1.0 2013-08-08 datazen $
*/
class lC_Boxes_currencies extends lC_Modules {
  var $_title,
      $_code = 'currencies',
      $_author_name = 'Loaded Commerce',
      $_author_www = 'http://www.loadedcommerce.com',
      $_group = 'boxes';

  public function lC_Boxes_currencies() {
    global $lC_Language;
    
    if (function_exists($lC_Language->injectDefinitions)) $lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');

    $this->_title = $lC_Language->get('box_currencies_heading');
  }

  public function initialize() {
    global $lC_Session, $lC_Currencies;

    $data = array();

    foreach ($lC_Currencies->currencies as $key => $value) {
      $data[] = array('id' => $key, 'text' => $value['title']);
    }

    if (sizeof($data) > 1) {
      $hidden_get_variables = '';

      foreach ($_GET as $key => $value) {
        if ( ($key != 'currency') && ($key != $lC_Session->getName()) && ($key != 'x') && ($key != 'y') ) {
          $hidden_get_variables .= lc_draw_hidden_field($key, $value);
        }
      }

      $this->_content = '<li class="box-currencies-selection">' . lc_draw_pull_down_menu('currency', $data, $_SESSION['currency'], 'class="box-currencies-select" onchange="$(this).closest(\'form\').submit();"') . $hidden_get_variables . lc_draw_hidden_session_id_field() . '</li>' . "\n";
    }
  }
}
?>
