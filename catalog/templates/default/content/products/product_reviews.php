<?php
/**  
*  $Id: product_reviews.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('reviews') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('reviews', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/products/product_reviews.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <?php
        $minHeight = 0;
        if ($lC_Product->hasImage()) $minHeight = (($lC_Image->getHeight('large') / 2) -20);
      ?>
      <div id="productReviewsContent"> 
        <div class="contentBorder">    
          <?php
            if ($lC_Product->hasImage()) {
            ?>
            <div style="float:right; padding:10px; text-align:center; margin:0 0 10px 10px; background-color:#fff;">
              <a href="<?php echo (file_exists(DIR_FS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'popup'))) ? lc_href_link(DIR_WS_CATALOG . $lC_Image->getAddress($lC_Product->getImage(), 'popup')) : lc_href_link(DIR_WS_IMAGES . 'no_image.png'); ?>" title="<?php echo $lC_Product->getTitle(); ?>" class="thickbox review-img">
                <?php echo $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'style="margin-bottom:10px; border:1px solid #e1e1e1;"', 'thumbnail'); ?>
              </a>
              <?php echo '<br /><a href="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action')) . '&action=cart_add') . '" class="noDecoration"><button class="button brown_btn" type="button">' . $lC_Language->get('button_add_to_cart') . '</button></a>'; ?>
            </div>
            <?php
            }
            if ($lC_Product->getData('reviews_average_rating') > 0) {
            ?>
            <p><?php echo $lC_Language->get('average_rating') . ' ' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $lC_Product->getData('reviews_average_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $lC_Product->getData('reviews_average_rating'))); ?><br /><br /></p>
            <?php
            }
            $counter = 0;
            $Qreviews = lC_Reviews::getListing($lC_Product->getID());
            if ($Qreviews->numberOfRows() > 0) {
              while ($Qreviews->next()) {
                $counter++;
                if ($counter > 1) {
                ?>
                <p style="border-bottom:1px solid #eee; padding-top:10px; margin-bottom:10px;" /></p>
                <?php
                }
              ?>
              <p><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '&nbsp;' . sprintf($lC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')) . '; ' . lC_DateTime::getLong($Qreviews->value('date_added')); ?></p>
              <p><?php echo nl2br(wordwrap($Qreviews->valueProtected('reviews_text'), 60, '&shy;')); ?></p>
              <?php
              }
            } else {
              echo '        <p>' . $lC_Language->get('no_reviews_available') . '</p>'; 
            }
          ?>  
        </div>
        <?php
          if ($Qreviews->numberOfRows() > 0) {
          ?> 
          <div class="listingPageLinks">
            <span style="float: right;"><?php echo $Qreviews->getBatchPageLinks('page', 'reviews'); ?></span>
            <span style="float: left; padding-top:5px;"><?php echo $Qreviews->getBatchTotalPages($lC_Language->get('result_set_number_of_reviews')); ?></span>
          </div>
          <?php
          }
        ?>
        <div style="clear:both;">&nbsp;</div>
        <div id="productReviewsActions" class="action_buttonbar">
          <?php
            if ($lC_Reviews->is_enabled === true) {
            ?>
            <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
            <?php
            }
          ?>
          <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_write_review'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
      </div>
    </div>
  </div>
</div>
<!--content/products/product_reviews.php end-->