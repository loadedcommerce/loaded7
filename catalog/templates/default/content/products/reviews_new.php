<?php
/*
  $Id: review_new.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
if ($lC_MessageStack->size('reviews') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('reviews', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--NEW REVIEW SECTION STARTS-->
  <div class="full_page">
    <!--NEW REVIEW CONTENT STARTS-->
    <div class="content">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <p><b><?php echo $lC_Language->get('new_review_title'); ?></b></p><br />
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
            <li><?php echo lc_draw_label($lC_Language->get('field_customer_comments'), null, 'customer_comments'); ?></li>
            <li><?php echo lc_draw_textarea_field('review', null, null, 15, 'style="width: 99%;"'); ?></li>
            <li class="newReviewsFormLine reviewsRadio"><?php echo $lC_Language->get('field_review_rating') . '&nbsp;&nbsp;' . $lC_Language->get('review_lowest_rating_title') . ' ' . lc_draw_radio_field('rating', array('1', '2', '3', '4', '5')) . ' ' . $lC_Language->get('review_highest_rating_title'); ?></li>
          </ol>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <div id="newReviewActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews&' . $lC_Product->getID()); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
          <span class="buttonRight"><a onclick="$('#reviews_new').submit();"><button class="button brown_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
        </form>
      </div>
    </div>
    <!--NEW REVIEW CONTENT ENDS-->
  </div>
<!--NEW REVIEW SECTION STARTS-->