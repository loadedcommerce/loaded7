<?php
/*
  $Id: reviews.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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