<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: credit_cards.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Credit_cards extends lC_Access {
  var $_module = 'credit_cards',
      $_group = 'configuration',
      $_icon = 'cards.png',
      $_title,
      $_sort_order = 700;

  public function lC_Access_Credit_cards() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_credit_cards_title');
  }
}
?>