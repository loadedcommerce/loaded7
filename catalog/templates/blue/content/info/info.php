<?php
/**  
*  $Id: info.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
?>
<!--content/info/info.php start-->
<div id="infoListing" class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <ul>
        <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'shipping'), $lC_Language->get('box_information_shipping')); ?></li>
        <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'privacy'), $lC_Language->get('box_information_privacy')); ?></li>
        <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'conditions'), $lC_Language->get('box_information_conditions')); ?></li>
        <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'cookie'), $lC_Language->get('info_cookie_usage_heading')); ?></li>
        <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'ssl_check'), $lC_Language->get('breadcrumb_ssl_check')); ?></li>
        <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'contact'), $lC_Language->get('box_information_contact')); ?></li>
        <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'sitemap'), $lC_Language->get('box_information_sitemap')); ?></li>
      </ul>
    </div>
    <div style="clear:both;">&nbsp;</div>
    <div id="infoConditionsActions" class="action_buttonbar">
      <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'new'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_go_shopping'); ?></button></a></span>
    </div>
    <div style="clear:both;"></div>
  </div>
</div>
<!--content/info/info.php end-->