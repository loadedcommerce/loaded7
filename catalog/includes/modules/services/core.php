<?php
/**
  $Id: core.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Services_core {

  function start() {
    global $lC_Customer, $lC_Tax, $lC_Weight, $lC_ShoppingCart, $lC_NavigationHistory, $lC_Image, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/classes/template.php'));
    include($lC_Vqmod->modCheck('includes/classes/modules.php'));
    include($lC_Vqmod->modCheck('includes/classes/category.php'));
    include($lC_Vqmod->modCheck('includes/classes/variants.php'));
    include($lC_Vqmod->modCheck('includes/classes/product.php'));
    include($lC_Vqmod->modCheck('includes/classes/datetime.php'));
    include($lC_Vqmod->modCheck('includes/classes/xml.php'));
    include($lC_Vqmod->modCheck('includes/classes/mail.php'));
    include($lC_Vqmod->modCheck('includes/classes/address.php'));

    include($lC_Vqmod->modCheck('includes/classes/customer.php'));
    $lC_Customer = new lC_Customer();

    include($lC_Vqmod->modCheck('includes/classes/tax.php'));
    $lC_Tax = new lC_Tax();

    include($lC_Vqmod->modCheck('includes/classes/weight.php'));
    $lC_Weight = new lC_Weight();

    include($lC_Vqmod->modCheck('includes/classes/shopping_cart.php'));
    $lC_ShoppingCart = new lC_ShoppingCart();
    $lC_ShoppingCart->refresh();

    include($lC_Vqmod->modCheck('includes/classes/navigation_history.php'));
    $lC_NavigationHistory = new lC_NavigationHistory(true);

    include($lC_Vqmod->modCheck('includes/classes/image.php'));
    $lC_Image = new lC_Image();

    return true;
  }

  function stop() {
    return true;
  }
}
?>