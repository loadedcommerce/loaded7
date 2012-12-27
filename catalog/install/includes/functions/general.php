<?php
/*
  $Id: general.php v1.0 2012-12-08 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2012 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2012 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
function lc_realpath($directory) {
  return str_replace('\\', '/', realpath($directory));
}
?>