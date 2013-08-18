<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_reviews.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/products/product_reviews.php start--> 
<div class="row">
  <div class="col-sm-12 col-lg-12 clearfix">
    <h1 class="no-margin-top"><?php echo $lC_Language->get('reviews_heading'); ?></h1>
    <div class="row">
      <div class="col-sm-4 col-lg-4 large-padding-left">
        <div class="thumbnail align-center large-padding-top">
          <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-product-reviews-image-src"', 'small')); ?>
          <div class="caption">
            <h3 style="line-height:1.1;">
              <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()); ?>
            </h3>
            <div class=""><?php echo (strlen($lC_Product->getDescription()) > 60 ) ? substr(lc_clean_html($lC_Product->getDescription()), 0, 57) . '...' : lc_clean_html($lC_Product->getDescription()); ?></div>
            <div class="row margin-top">
              <div class="col-sm-6 col-lg-6">
                <p class="lead"><?php echo $lC_Product->getPriceFormated(); ?></p>
              </div>
              <div class="col-sm-6 col-lg-6 no-margin-left">
                <a href="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add'); ?>">
                  <button class="btn btn-success btn-block large" type="button"><?php echo $lC_Language->get('button_buy_now'); ?></button>
                </a>
              </div>
            </div>
          </div>
        </div>    
      </div>
      <div class="col-sm-8 col-lg-8">
        <?php 
        if ( $lC_MessageStack->size('reviews') > 0 ) echo '<div class="message-stack-container alert alert-error">' . $lC_MessageStack->get('reviews') . '</div>' . "\n"; 
        ?>

        <div class="content-product-reviews-container">  
          <h3 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h3>  
          <?php
          if ($lC_Product->getData('reviews_average_rating') > 0) {
            ?>
            <div class="content-product-reviews-rating"><?php echo $lC_Language->get('average_rating') . ' ' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $lC_Product->getData('reviews_average_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $lC_Product->getData('reviews_average_rating'))); ?><br /><br /></p>
            <?php echo lC_Reviews::getListingOutput($lC_Product->getID()); ?>
            <?php 
          }
          ?>  
        </div>
      </div>
    </div>
  </div>   
  <div class="col-sm-12 col-lg-12 clearfix">
    <div class="button-set">
      <?php
      if ($lC_Reviews->is_enabled === true) {
        ?>  
        <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()); ?>"><button class="pull-right btn btn-lg btn-primary large-margin-bottom" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a>
        <?php 
      } 
      ?>
      <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>"><button class="pull-left btn btn-lg btn-primary large-margin-bottom" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
    </div>    
  </div>    
</div>
<!--content/products/product_reviews.php end-->