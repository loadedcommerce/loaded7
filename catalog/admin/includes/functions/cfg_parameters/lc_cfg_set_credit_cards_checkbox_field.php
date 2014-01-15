<?php
/**
  @package    admin::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: lc_cfg_set_credit_cards_checkbox_field.php v1.0 2013-08-08 datazen $
*/
function lc_cfg_set_credit_cards_checkbox_field($default, $key = null) {
  global $lC_Database;

  $name = (empty($key)) ? 'configuration_value' : 'configuration[' . $key . '][]';

  $cc_array = array();

  $Qcc = $lC_Database->query('select id, credit_card_name from :table_credit_cards where credit_card_status = :credit_card_status order by sort_order, credit_card_name');
  $Qcc->bindTable(':table_credit_cards', TABLE_CREDIT_CARDS);
  $Qcc->bindInt(':credit_card_status', 1);
  $Qcc->execute();

  while ($Qcc->next()) {
    $cc_array[] = array('id' => $Qcc->valueInt('id'),
                        'text' => $Qcc->value('credit_card_name'));
  }

  return lc_draw_checkbox_field($name, $cc_array, explode(',', $default), 'class="checkbox"', '<br />');
}
?>