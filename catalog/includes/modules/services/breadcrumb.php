<?php
/**
  @package    catalog::modules::services
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: breadcrumb.php v1.0 2013-08-08 datazen $
*/
class lC_Services_breadcrumb {

  public function start() {
    global $lC_Breadcrumb, $lC_Language, $lC_Vqmod;

    include($lC_Vqmod->modCheck('includes/classes/breadcrumb.php'));
    $lC_Breadcrumb = new lC_Breadcrumb();

    $template = (isset($_SESSION['template']['code'])) ? $_SESSION['template']['code'] : 'core';
    $lC_Breadcrumb->add('<span id="breadcrumb-top">' . $lC_Language->get('text_home') . '</span>', lc_href_link(FILENAME_DEFAULT));

    return true;
  }

  public function stop() {
    return true;
  }
}
?>