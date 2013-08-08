<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_listing.php v1.0 2013-08-08 datazen $
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
        if (lC_Bs_starter::getCategoryDescription() != '') {
          echo '<div id="categories_description_inner">' . lC_Bs_starter::getCategoryDescription() . '</div>'; 
        }
      ?>
      </div>
      <div class="products_list products_slider">
      <?php 
      if (PRODUCT_LIST_FILTER == '1') echo lC_Bs_starter::getManufacturerFilter();
      $Qlisting = lC_Bs_starter::getProductsListingSql();
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