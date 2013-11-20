<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews.php v1.0 2013-08-08 datazen $
*/
class lC_Services_reviews {
  function start() {
    global $lC_Reviews, $lC_Vqmod;
    
    include($lC_Vqmod->modCheck('includes/classes/reviews.php'));

    $lC_Reviews = new lC_Reviews();
    return true;
  }

  function stop() {
    return true;
  }
}
?>