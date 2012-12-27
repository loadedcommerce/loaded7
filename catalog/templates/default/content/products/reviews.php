<?php
  /*
  $Id: reviews.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */
  $Qreviews = lC_Reviews::getListing();
?>
<!--PRODUCT REVIEWS SECTION STARTS-->
<div class="full_page">
  <!--PRODUCT REVIEWS CONTENT STARTS-->
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <?php
        while ($Qreviews->next()) {
        ?>
        <div class="review-box clear">
          <?php
            if (!lc_empty($Qreviews->value('image'))) {
              echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=' . $Qreviews->valueInt('reviews_id') . '&' . $Qreviews->value('products_keyword')), $lC_Image->show($Qreviews->value('image'), $Qreviews->value('products_name'), 'class="review-img" align="left"'));
            }
          ?>
          <h6><?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'reviews=' . $Qreviews->valueInt('reviews_id') . '&' . $Qreviews->value('products_keyword')), $Qreviews->value('products_name')); ?> (<?php echo sprintf($lC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')); ?>)</h6>
          <div><?php echo '<i>' . sprintf($lC_Language->get('review_rating'), lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))), sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '</i>';?> 
          <br/><?php echo sprintf($lC_Language->get('review_date_added'), lC_DateTime::getLong($Qreviews->value('date_added'))); ?></div>
          <div><?php echo wordwrap($Qreviews->valueProtected('reviews_text'), 60, '&shy;') . ((strlen($Qreviews->valueProtected('reviews_text')) >= 100) ? '..' : '') . '<br /><br />'; ?></div>
          <div style="clear:both;"></div>
        </div>
        <div style="clear:both;"></div>
        <?php
        }
      ?>
    </div>
    <div class="listingPageLinks" style="border-top: 1px solid #eee; padding-top:10px;">
      <span style="float: right;"><?php echo $Qreviews->getBatchPageLinks('page', 'reviews'); ?></span>
      <?php echo $Qreviews->getBatchTotalPages($lC_Language->get('result_set_number_of_reviews')); ?>
    </div>
  </div>
</div>
<div style="clear:both;"></div>