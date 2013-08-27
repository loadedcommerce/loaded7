<?php
/**
  @package    catalog::core
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
class lC_Index_rpc {
 /*
  * Set the media type to session
  *
  * @access public
  * @return json
  */
  public static function setMediaType() {
    $_SESSION['mediaType'] = $_GET['type'];
    $_SESSION['mediaSize'] = $_GET['size'];

    $result['rpcStatus'] = '1';
    
    echo json_encode($result);
  } 
}
?>