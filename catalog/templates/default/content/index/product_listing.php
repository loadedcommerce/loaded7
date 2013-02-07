<?php
/**  
*  $Id: product_listing.php v1.0 2013-01-01 datazen $
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
<!--content/index/product_listing.php start-->
<style>
button.price_add {
  margin-top: 20px;
}
</style>
<div class="full_page">
  <div class="content">
    <!-- h1><?php echo $lC_Template->getPageTitle(); ?></h1 --> 
    <div class="products_list products_slider">
      <?php 
      if (PRODUCT_LIST_FILTER > 0) echo lC_Default::getManufacturerFilter();
      
      $Qlisting = lC_Default::getProductsListingSql();
      
      if (file_exists(DIR_FS_TEMPLATE . 'modules/product_listing.php')) {
        require(DIR_FS_TEMPLATE . 'modules/product_listing.php');
      } else {
        require('includes/modules/product_listing.php');
      }
    ?>
    </div>
  </div>
</div>
<!--content/index/product_listing.php end-->