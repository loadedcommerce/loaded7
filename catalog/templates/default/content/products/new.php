<?php
/*
  $Id: new.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
  .products_list div.price_info button { margin-top:20px; }
</style>
<!--NEW PRODUCTS SECTION STARTS-->
  <div class="full_page">
    <!--NEW PRODUCTS CONTENT STARTS-->
    <div class="content">
      <div class="short-code-column">
        <!-- h1><?php echo $lC_Template->getPageTitle(); ?></h1 --> 
        <!--NEW PRODUCTS LISTING STARTS-->
        <?php
          $lC_Products = new lC_Products();
          $lC_Products->setSortBy('date_added', '-');
          $Qlisting = $lC_Products->execute();
          if ($Qlisting->numberOfRows() > 0) {
            if (file_exists(DIR_FS_TEMPLATE . 'modules/product_listing.php')) {
              require(DIR_FS_TEMPLATE . 'modules/product_listing.php');
            } else {
              require('includes/modules/product_listing.php');
            }      
          } else {
        ?>
        <p><?php echo $lC_Language->get('no_new_products'); ?></p>
        <?php
          }
        ?>
        <!--NEW PRODUCTS LISTING ENDS-->
        <!--NEW PRODUCTS CONTENT STARTS-->
        <div id="productsNewActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="javascript: history.go(-1);" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
      <!--NEW PRODUCTS CONTENT ENDS-->
      </div>
    </div>
    <!--NEW PRODUCTS CONTENT ENDS-->
  </div>
<!--NEW PRODUCTS CONTENT ENDS-->
