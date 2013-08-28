<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: info.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/info.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <ul class="nav nav-pills nav-stacked large-margin-bottom">
      <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'shipping'), $lC_Language->get('box_information_shipping')); ?></li>
      <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'privacy'), $lC_Language->get('box_information_privacy')); ?></li>
      <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'conditions'), $lC_Language->get('box_information_conditions')); ?></li>
      <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'cookie'), $lC_Language->get('info_cookie_usage_heading')); ?></li>
      <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'ssl_check'), $lC_Language->get('breadcrumb_ssl_check')); ?></li>
      <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'contact'), $lC_Language->get('box_information_contact')); ?></li>
      <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'sitemap'), $lC_Language->get('box_information_sitemap')); ?></li>
    </ul>
    <div class="button-set clearfix">
      <form action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new', 'AUTO'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-right btn btn-lg btn-primary" type="submit"><?php echo $lC_Language->get('button_go_shopping'); ?></button></form>
    </div>
  </div>
</div>
<!--content/info/info.php end-->