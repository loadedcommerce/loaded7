<?php
/**
  @package    catalog::install::functions
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: general.php v1.0 2013-08-08 datazen $
*/
function lc_realpath($directory) {
  return str_replace('\\', '/', realpath($directory));
}
?>