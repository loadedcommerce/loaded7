<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: info_shipping.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/info_shipping.php start-->
<div class="row-fluid">
  <div class="span12">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
    <div class="info-shipping-text large-margin-top large-margin-bottom"><?php echo $lC_Language->get('text_information_shipping'); ?></div>
    <div class="button-set"></div>
      <a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="pull-right btn btn-large btn-success" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a>
      <a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="pull-left btn btn-large btn-info" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
    </div>
  </div>
</div>
<!--content/info/info_shipping.php end-->