<?php
/**  
*  $Id: info_sitemap.php v1.0 2013-01-01 datazen $
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
$lC_CategoryTree->reset();
$lC_CategoryTree->setShowCategoryProductCount(false);
$lC_CategoryTree->setParentGroupStringTop('<ul>', '</ul>');
$lC_CategoryTree->setParentGroupString('<ul>', '</ul>');
$lC_CategoryTree->setChildStringWithChildren('<li>', '');
$lC_CategoryTree->setUseAria(true);
?>
<!--content/info/info_sitemap.php start-->
<div id="infoSitemap" class="full_page">
  <h1><?php echo $lC_Template->getPageTitle(); ?></h1>        
  <div class="content">
    <div>
      <div class="short-code-column one-half" id="sitemapLeft" style="margin-right:0;">
        <?php echo $lC_CategoryTree->getTree(); ?>
      </div>
      <div class="short-code-column one-half column-last" id="sitemapRight" style="padding-left:20px;">
        <ul>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, null, 'SSL'), $lC_Language->get('sitemap_account')); ?>
            <ul>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'edit', 'SSL'), $lC_Language->get('sitemap_account_edit')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'), $lC_Language->get('sitemap_address_book')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'orders', 'SSL'), $lC_Language->get('sitemap_account_history')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_ACCOUNT, 'newsletters', 'SSL'), $lC_Language->get('sitemap_account_notifications')); ?></li>
            </ul>
          </li>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_CHECKOUT, null, 'SSL'), $lC_Language->get('sitemap_shopping_cart')); ?></li>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_CHECKOUT, 'shipping', 'SSL'), $lC_Language->get('sitemap_checkout_shipping')); ?></li>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_SEARCH), $lC_Language->get('sitemap_advanced_search')); ?></li>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'new'), $lC_Language->get('sitemap_products_new')); ?></li>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'specials'), $lC_Language->get('sitemap_specials')); ?></li>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews'), $lC_Language->get('sitemap_reviews')); ?></li>
          <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO), $lC_Language->get('box_information_heading')); ?>
            <ul>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'shipping'), $lC_Language->get('box_information_shipping')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'privacy'), $lC_Language->get('box_information_privacy')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'conditions'), $lC_Language->get('box_information_conditions')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'contact'), $lC_Language->get('box_information_contact')); ?></li>
            </ul>
          </li>
        </ul>
      </div>
      <div style="clear:both;"></div>
    </div>
    <div style="clear:both;">&nbsp;</div>
    <div id="infoContactActions" class="action_buttonbar">
      <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
      <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
    </div>
    <div style="clear:both;"></div>
  </div>
</div>
<!--content/info/info_sitemap.php end-->