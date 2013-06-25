<?php
/**  
*  $Id: reviews_info.php v1.0 2013-01-01 datazen $
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
$Qreviews = lC_Reviews::getEntry($_GET[$lC_Template->getModule()]);
if ($lC_MessageStack->size('reviews') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('reviews', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/products/reviews_info.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <?php
      $height = ($lC_Product->hasImage()) ? $lC_Image->getHeight('thumbnails')+30 : 110;
      if ($lC_Product->hasImage()) {
        $height = $lC_Image->getHeight('thumbnails')+20;
      ?>
      <div style="float:right; padding:10px; text-align:center; margin:0 0 10px 10px; background-color:#fff;">
        <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, 'images&' . $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="review-img"', 'thumbnail'), 'target="_blank" onclick="window.open(\'' . lc_href_link(FILENAME_PRODUCTS, 'images&' . $lC_Product->getKeyword()) . '\', \'popUp\', \'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=' . (($lC_Product->numberOfImages() > 1) ? $lC_Image->getWidth('large') + ($lC_Image->getWidth('thumbnails') * 2) + 70 : $lC_Image->getWidth('large') + 20) . ',height=' . ($lC_Image->getHeight('large') + 20) . '\'); return false;"'); ?>
        <?php echo '<br /><a href="' . lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), $lC_Product->getKeyword() . '&' . lc_get_all_get_params(array('action')) . '&action=cart_add') . '" class="buyNowButtonSmall"><button class="button brown_btn" type="button">' . $lC_Language->get('button_buy_now') . '</button></a>'; ?>
      </div>
      <?php
      }
      ?>
      <p><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '&nbsp;' . sprintf($lC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')) . '; ' . lC_DateTime::getLong($Qreviews->value('date_added')); ?></p>
      <p><?php echo nl2br(wordwrap($Qreviews->valueProtected('reviews_text'), 60, '&shy;')); ?></p>
      <div style="clear:both;">&nbsp;</div>
      <div class="action_buttonbar">
      <?php
        if ($lC_Reviews->is_enabled === true) {
        ?>
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
        <?php
        }
      ?>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_PRODUCTS, 'reviews=new&' . $lC_Product->getKeyword()); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_write_review'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>  
    </div>
    <div style="clear:both;"></div>
  </div>
</div>
<!--content/products/reviews_info.php end-->