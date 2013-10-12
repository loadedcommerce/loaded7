<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews_new.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/products/reviews_new.php start--> 
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Language->get('button_write_review');?></h1>
    <div class="row">
      <div class="col-sm-4 col-lg-4 large-padding-left large-padding-right">
        <div class="thumbnail align-center large-padding-top">
          <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-reviews-new-image-src"', 'small')); ?>
          <div class="caption">
            <h3 style="line-height:1.1;">
              <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()); ?>
            </h3>
            <p class=""><?php echo (strlen($lC_Product->getDescription()) > 60 ) ? substr(lc_clean_html($lC_Product->getDescription()), 0, 57) . '...' : lc_clean_html($lC_Product->getDescription()); ?></p>
            <div class="row">
              <div class="col-sm-6 col-lg-6">
                <p class="lead"><?php echo $lC_Product->getPriceFormated(); ?></p>
              </div>
              <div class="col-sm-6 col-lg-6 no-margin-left">
                <button class="btn btn-success btn-block" onclick="window.location.href='<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add'); ?>'" type="button"><?php echo $lC_Language->get('button_buy_now'); ?></button>
              </div>
            </div>
          </div>
        </div>    
      </div>
      <div class="col-sm-8 col-lg-8">
        <?php 
        if ( $lC_MessageStack->size('reviews') > 0 ) echo '<div class="message-stack-container alert alert-error">' . $lC_MessageStack->get('reviews') . '</div>' . "\n"; 
        //if (isset($_GET['contact']) && $_GET['contact'] == 'success') echo '<div class="message-success-container alert alert-success">' . $lC_Language->get('contact_email_sent_successfully') . '</div>' . "\n"; 
        ?>
        <form role="form" name="reviews_new" id="reviews_new" class="row" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getID() . '&action=process'); ?>" method="post" onsubmit="return checkForm(this);">
          <div class="form-group"><label><?php echo $lC_Language->get('field_customer_name'); ?></label><input class="form-control" type="text" name="customer_name" value="<?php echo (($lC_Customer->isLoggedOn()) ? $lC_Customer->getName() : null); ?>"></div>
          <div class="form-group"><label><?php echo $lC_Language->get('field_customer_email_address'); ?></label><input class="form-control" type="text" name="customer_email_address" value="<?php echo (($lC_Customer->isLoggedOn()) ? $lC_Customer->getEmailAddress() : null); ?>"></div>
          <div class="form-group"><label><?php echo $lC_Language->get('field_customer_comments'); ?></label><textarea class="form-control" name="review" rows="5" cols="25"></textarea></div>
          <div class="form-group no-margin-bottom"><label class="margin-right"><?php echo $lC_Language->get('field_review_rating'); ?></label><span class="content-reviews-rating-title-bad with-padding-no-top-bottom strong"><?php echo $lC_Language->get('review_lowest_rating_title') . '</span><span class="content-reviews-rating-stars">' . lc_draw_radio_field('rating', array('1', '2', '3', '4', '5'), '3', null, null) . '</span><span class="content-reviews-rating-title-good with-padding-no-top-bottom strong">' . $lC_Language->get('review_highest_rating_title'); ?></span></div>    
        </form>
      </div>
    </div>
  </div>                                                                                                                                                
  <div class="button-set clearfix large-margin-bottom">
    <button class="pull-right btn btn-lg btn-primary large-margin-right" onclick="$('#reviews_new').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
    <button class="pull-left btn btn-lg btn-default large-margin-left" onclick="window.location.href='<?php echo  lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getID()); ?>'" type="button"><?php echo $lC_Language->get('button_back'); ?></button>
  </div>    
</div>
<!--content/products/reviews_new.php end-->