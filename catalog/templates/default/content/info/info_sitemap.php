<?php
/*
  $Id: iiiiinfo_sitemap.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$lC_CategoryTree->reset();
$lC_CategoryTree->setShowCategoryProductCount(false);
?>
<!--INFO SITEMAP SECTION STARTS-->
  <div id="infoSitemap" class="full_page">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>        
    <div class="content">
      <!--INFO SITEMAP DETAILS STARTS-->
      <div class="short-code-column">
        <!--INFO SITEMAP CATEGORIES STARTS-->
        <div id="sitemapLeft">
          <?php echo $lC_CategoryTree->getTree(); ?>
        </div>
        <!--INFO SITEMAP CATEGORIES ENDS-->
        <!--INFO SITEMAP PAGES STARTS-->
        <div id="sitemapRight">
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
        <!--INFO SITEMAP PAGES ENDS-->
        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <!--INFO SITEMAP DETAILS ENDS-->
      <!--INFO SITEMAP ACTIONS STARTS-->
      <div id="infoContactActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
      <!--INFO SITEMAP ACTIONS ENDS-->
    </div>
  </div>
<!--INFO SITEMAP SECTION ENDS-->