<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: images.php v1.0 2013-08-08 datazen $
*/
class lC_Access_Images extends lC_Access {
  var $_module = 'images',
      $_group = 'content',
      $_icon = 'image.png',
      $_title,
      $_sort_order = 200;

  public function lC_Access_Images() {
    global $lC_Language;

    $this->_title = $lC_Language->get('access_images_title');
  }
}
?>