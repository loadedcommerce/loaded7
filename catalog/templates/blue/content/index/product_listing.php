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
    <h1 class="categories_h1"><?php echo $lC_Template->getPageTitle(); ?></h1> 
      <div id="categories_description">
      <?php 
        if (lC_Default::getCategoryDescription() != '') {
          echo '<div id="categories_description_inner">' . lC_Default::getCategoryDescription() . '</div>'; 
        }
      ?>
      </div>
      <div class="products_list products_slider">
      <?php 
      if (PRODUCT_LIST_FILTER == '1') echo lC_Default::getManufacturerFilter();
      $Qlisting = lC_Default::getProductsListingSql();
      if (file_exists(DIR_FS_TEMPLATE . 'modules/product_listing.php')) {
        require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'modules/product_listing.php'));
      } else {
        require($lC_Vqmod->modCheck('includes/modules/product_listing.php'));
      }
    ?>
    </div>
  </div>
</div>
<!--content/index/product_listing.php end-->