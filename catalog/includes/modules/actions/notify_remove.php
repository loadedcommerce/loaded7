<?php
/**
  @package    catalog::modules::actions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cart_remove.php v1.0 2013-08-08 datazen $
*/
class lC_Actions_notify_remove {
  function execute() {
    global $lC_Database, $lC_Session, $lC_NavigationHistory, $lC_Customer;

    if (!$lC_Customer->isLoggedOn()) {
      $lC_NavigationHistory->setSnapshot();

      lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));

      return false;
    }

    $id = false;

    foreach ($_GET as $key => $value) {
      if ( (preg_match('/^[0-9]+(#?([0-9]+:?[0-9]+)+(;?([0-9]+:?[0-9]+)+)*)*$/', $key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $lC_Session->getName()) ) {
        $id = $key;
      }

      break;
    }

    if (($id !== false) && lC_Product::checkEntry($id)) {
      $lC_Product = new lC_Product($id);

      $Qcheck = $lC_Database->query('select products_id from :table_products_notifications where customers_id = :customers_id and products_id = :products_id limit 1');
      $Qcheck->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
      $Qcheck->bindInt(':customers_id', $lC_Customer->getID());
      $Qcheck->bindInt(':products_id', $lC_Product->getID());
      $Qcheck->execute();

      if ($Qcheck->numberOfRows() > 0) {
        $Qn = $lC_Database->query('delete from :table_products_notifications where customers_id = :customers_id and products_id = :products_id');
        $Qn->bindTable(':table_products_notifications', TABLE_PRODUCTS_NOTIFICATIONS);
        $Qn->bindInt(':customers_id', $lC_Customer->getID());
        $Qn->bindInt(':products_id', $lC_Product->getID());
        $Qn->execute();
      }
    }

    lc_redirect(lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), lc_get_all_get_params(array('action'))));
  }
}
?>
