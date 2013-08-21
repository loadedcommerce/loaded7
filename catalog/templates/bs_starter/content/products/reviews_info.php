<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews_info.php v1.0 2013-08-08 datazen $
*/
$Qreviews = lC_Reviews::getEntry($_GET[$lC_Template->getModule()]);
?>
<!--content/products/reviews_info.php start--> 
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
            <div class="content-reviews-info-stars"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '&nbsp;' . sprintf($lC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')) . '; ' . lC_DateTime::getLong($Qreviews->value('date_added')); ?></div>
            <div class="content-reviews-info-text"><?php echo nl2br(wordwrap($Qreviews->valueProtected('reviews_text'), 60, '&shy;')); ?></div>
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
      <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>"><button class="pull-left btn btn-lg btn-default large-margin-bottom" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
    </div>    
  </div>    
</div>
<!--content/products/reviews_info.php end-->