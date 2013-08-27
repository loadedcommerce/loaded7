<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: help.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/search/help.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
    <div class="info-shipping-text"><?php echo $lC_Language->get('search_help'); ?></div>
    <div class="button-set">
      <a href="javascript:window.close();"><button class="pull-right btn btn-sm btn-primary" type="button"><?php echo $lC_Language->get('close_window'); ?></button></a>
    </div>
  </div>
</div>
<!--content/search/help.php end-->