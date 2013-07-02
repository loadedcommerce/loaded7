<?php
/*
  $Id: lc_cfg_set_credit_cards_checkbox_field.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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