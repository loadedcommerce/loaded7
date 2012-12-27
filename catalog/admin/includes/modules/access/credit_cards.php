<?php
/*
  $Id: credit_cards.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Access_Credit_cards extends lC_Access {
    var $_module = 'credit_cards',
        $_group = 'configuration',
        $_icon = 'cards.png',
        $_title,
        $_sort_order = 400;

    function lC_Access_Credit_cards() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_credit_cards_title');
    }
  }
?>