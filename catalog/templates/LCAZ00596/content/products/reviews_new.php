<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
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
 <form name="reviews_new" id="reviews_new" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getID() . '&action=process'); ?>" method="post" onsubmit="return checkForm(this);">
      <div id="newReviewsForm">
        <ol>
          <?php
            if (!$lC_Customer->isLoggedOn()) {
          ?>
          <li class="newReviewsFormLine"><span style="width:25%; float:left;"><?php echo lc_draw_label($lC_Language->get('field_customer_name'), null, 'customer_name') . '</span><span style="width:75%; float:right;">' . lc_draw_input_field('customer_name', null, 'style="width:99%;"'); ?></span></li>
          <li class="newReviewsFormLine"><span style="width:25%; float:left;"><?php echo lc_draw_label($lC_Language->get('field_customer_email_address'), null, 'customer_email_address') . '</span><span style="width:75%; float:right;">' . lc_draw_input_field('customer_email_address', null, 'style="width:99%;"'); ?></span></li>
          <?php
            } else {
          ?>
          <li class="newReviewsFormLine">
            <span style="width:25%; float:left;"><?php echo lc_draw_label($lC_Language->get('field_customer_name'), null, 'customer_name'); ?></span>
            <span style="width:75%;"><?php echo $_SESSION['lC_Customer_data']['first_name'] . ' ' . $_SESSION['lC_Customer_data']['last_name']; ?></span>
          </li>
          <li class="newReviewsFormLine">
            <span style="width:25%; float:left;"><?php echo lc_draw_label($lC_Language->get('field_customer_email_address'), null, 'customer_name'); ?></span>
            <span style="width:75%;"><?php echo $_SESSION['lC_Customer_data']['email_address']; ?></span>
          </li>
          <?php
            }
          ?>
          <li class="newReviewsFormLine"><?php echo lc_draw_label($lC_Language->get('field_customer_comments'), null, 'customer_comments'); ?></li>
          <li><?php echo lc_draw_textarea_field('review', null, null, 15, 'style="width: 99%;"'); ?></li>
          <li class="newReviewsFormLine reviewsRadio"><?php echo $lC_Language->get('field_review_rating') . '&nbsp;&nbsp;' . $lC_Language->get('review_lowest_rating_title') . ' ' . lc_draw_radio_field('rating', array('1', '2', '3', '4', '5')) . ' ' . $lC_Language->get('review_highest_rating_title'); ?></li>
        </ol>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="newReviewActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getID()); ?>" class="noDecoration"><div class="pull-right btn btn-lg btn-primary large-margin-left"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
        <span class="buttonRight"><a onclick="$('#reviews_new').submit();"><button class="pull-right btn btn-lg btn-primary large-margin-right" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
        </form>
      </div>
    </div>
  </div>                                                                                                                                                
  <div class="button-set clearfix large-margin-bottom">
    <button class="pull-right btn btn-lg btn-primary large-margin-right" onclick="$('#reviews_new').submit();" type="button"><?php echo $lC_Language->get('button_write_review'); ?></button>
    <button class="pull-left btn btn-lg btn-default large-margin-left" onclick="window.location.href='<?php echo  lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getID()); ?>'" type="button"><?php echo $lC_Language->get('button_back'); ?></button>
  </div>    
</div>
<!--content/products/reviews_new.php end-->