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
<div class="row-fluid">
  <div class="span12">
    <h1><?php echo $lC_Template->getPageTitle();?></h1>
    <div class="row">
      <div class="span4 large-padding-left">
        <div class="thumbnail align-center large-padding-top">
          <?php echo lc_link_object(lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()), $lC_Image->show($lC_Product->getImage(), $lC_Product->getTitle(), 'class="content-reviews-info-image-src"', 'small')); ?>
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
        ?>
        <div class="content-reviews-info-stars"><?php echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '&nbsp;' . sprintf($lC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')) . '; ' . lC_DateTime::getLong($Qreviews->value('date_added')); ?></div>
        <div class="content-reviews-info-text"><?php echo nl2br(wordwrap($Qreviews->valueProtected('reviews_text'), 60, '&shy;')); ?></div>
      </div>
    </div>
  </div>                                                                                                                                                
  <div class="button-set">
    <?php
    if ($lC_Reviews->is_enabled === true) {
      ?>  
      <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>"><button class="pull-right btn btn-lg btn-success" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a>
      <?php 
    } 
    ?>
    <a href="<?php echo lc_href_link(FILENAME_PRODUCTS, $lC_Product->getKeyword()); ?>"><button class="pull-left btn btn-lg btn-info" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
  </div>    
</div>
<!--content/products/reviews_info.php end-->