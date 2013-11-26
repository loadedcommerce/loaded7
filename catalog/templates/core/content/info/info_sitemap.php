<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
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
  <div class="col-sm-12 col-lg-12">
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
          <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO), $lC_Language->get('sitemap_information_pages')); ?>
            <ul>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'cookie'), $lC_Language->get('breadcrumb_cookie_usage')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'ssl_check'), $lC_Language->get('breadcrumb_ssl_check')); ?></li>
              <li><?php echo lc_link_object(lc_href_link(FILENAME_INFO, 'contact'), $lC_Language->get('box_information_contact')); ?></li>
            </ul>
          </li>
        </ul>    
      </div>
    </div>    
    <div class="btn-set clearfix">
      <form action="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'AUTO'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-right btn btn-lg btn-primary" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></form>
      <form action="<?php echo lc_href_link(FILENAME_INFO, null, 'AUTO'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-left btn btn-lg btn-default" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div>    
  </div>    
</div>
<!--content/info/info_sitemap.php end-->