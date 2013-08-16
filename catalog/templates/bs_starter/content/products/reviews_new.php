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
<div class="row-fluid">
  <div class="span12">
    <h1><?php echo $lC_Language->get('button_write_review');?></h1>
    <div class="row">
      <div class="span4 large-padding-left">
        <div class="thumbnail align-center large-padding-top">
          <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-upcoming-products-image-tag"', 'small')); ?>
          <div class="caption">
            <h3 style="line-height:1.1;">
              <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Product->getTitle()); ?>
            </h3>
            <p class=""><?php echo (strlen($lC_Product->getDescription()) > 60 ) ? substr(lc_clean_html($lC_Product->getDescription()), 0, 57) . '...' : lc_clean_html($lC_Product->getDescription()); ?></p>
            <div class="row-fluid">
              <div class="span6">
                <p class="lead"><?php echo $lC_Product->getPriceFormated(); ?></p>
              </div>
              <div class="span6 no-margin-left">
                <a href="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action', 'new')) . '&action=cart_add'); ?>">
                  <button class="btn btn-success btn-block" type="button">Buy Now</button>
                </a>
              </div>
            </div>
          </div>
        </div>    
      </div>
      <div class="span8">
        <?php 
        if ( $lC_MessageStack->size('reviews') > 0 ) echo '<div class="message-stack-container alert alert-error">' . $lC_MessageStack->get('reviews') . '</div>' . "\n"; 
        //if (isset($_GET['contact']) && $_GET['contact'] == 'success') echo '<div class="message-success-container alert alert-success">' . $lC_Language->get('contact_email_sent_successfully') . '</div>' . "\n"; 
        ?>
        <form name="reviews_new" id="reviews_new" class="row-fluid" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getID() . '&action=process'); ?>" method="post" onsubmit="return checkForm(this);">
          <label><?php echo $lC_Language->get('field_customer_name'); ?></label><input class="span12" type="text" name="customer_name" value="<?php echo (($lC_Customer->isLoggedOn()) ? $lC_Customer->getName() : null); ?>"><br>
          <label><?php echo $lC_Language->get('field_customer_email_address'); ?></label><input class="span12" type="text" name="customer_email_address" value="<?php echo (($lC_Customer->isLoggedOn()) ? $lC_Customer->getEmailAddress() : null); ?>"><br>
          <label><?php echo $lC_Language->get('field_customer_comments'); ?></label><textarea class="span12" name="review" rows="5" cols="25"></textarea><br>    
          <label><?php echo $lC_Language->get('field_review_rating'); ?></label><?php echo $lC_Language->get('review_lowest_rating_title') . ' ' . lc_draw_radio_field('rating', array('1', '2', '3', '4', '5'), '3') . ' ' . $lC_Language->get('review_highest_rating_title'); ?><br>    
        </form>
      </div>
    </div>
  </div>                                                                                                                                                
  <div class="button-set">
    <button class="pull-right btn btn-large btn-success" onclick="$('#reviews_new').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
    <a href="<?php echo  lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getID()); ?>"><button class="pull-left btn btn-large btn-info" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
  </div>    
</div>
<!--content/products/reviews_new.php end-->