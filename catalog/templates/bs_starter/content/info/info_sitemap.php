<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: info_sitemap.php v1.0 2013-08-08 datazen $
*/
$lC_CategoryTree->reset();
$lC_CategoryTree->setShowCategoryProductCount(false);
$lC_CategoryTree->setParentGroupStringTop('<ul>', '</ul>');
$lC_CategoryTree->setParentGroupString('<ul>', '</ul>');
$lC_CategoryTree->setChildStringWithChildren('<li>', '');
$lC_CategoryTree->setUseAria(true);
?>
<!--content/info/info_sitemap.php start-->
<div class="row">
  <h1 class="no-margin-top margin-bottom"><?php echo $lC_Template->getPageTitle(); ?></h1>
  <div class="col-sm-6 col-lg-6">
    <div class="well">
      <?php echo $lC_CategoryTree->getTree(); ?>
    </div>
  </div>
  <div class="col-sm-6 col-lg-6">
    <div class="well">
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
  </div>    
  <div class="btn-set">
    <a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="pull-right btn btn-lg btn-success" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a>
    <a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="pull-left btn btn-lg btn-info" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
  </div>    
</div>
<!--content/info/info_sitemap.php end-->