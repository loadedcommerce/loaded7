<?php
/**
  @package    catalog::templates::classes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('includes/classes/address_book.php'));

class lC_Bs_starter_rpc {
 /*
  * Return the zones dropdown array
  *
  * @access public
  * @return json
  */
  public static function getZonesDropdown() {
    $result = array();
    $result = lC_AddressBook::getZonesDropdownHtml($_GET['country'], $_GET['zone']);
    if (is_array($result)) $result['rpcStatus'] = '1';
    
    echo json_encode($result);
  }
}
?>