<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: new.php v1.0 2013-08-08 datazen $
*/                          
?>
<!--content/index/product_listing.php start-->
<div class="row">
  <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
  
  <div id="content-product-listing-category-description-container">
    <?php 
      if (lC_Template_output::getCategoryDescription() != '') {
        echo '<div id="content-product-listing-category-description">' . lC_Template_output::getCategoryDescription() . '</div>'; 
      }
    ?>
  </div>
  
  <div class="content-product-listing-container">
    <?php 
    $lC_Products = new lC_Products();
    $lC_Products->setSortBy('date_added', '-');
    $Qlisting = $lC_Products->execute(MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY);    
    
    if (PRODUCT_LIST_FILTER == '1') echo lC_Template_output::getManufacturerFilter();
  
    if ( ($Qlisting->numberOfRows() > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
      ?>
      <!-- PAGINATION-->
      <div class="product-listing-module-pagination margin-bottom">
        <div class="pull-left large-margin-bottom"><?php echo $Qlisting->getBatchTotalPages($lC_Language->get('result_set_number_of_products')); ?></div>
        <div class="pull-right large-margin-bottom no-margin-top">
          <ul class="pagination">
            <?php echo $Qlisting->getBatchPageLinks('page', lc_get_all_get_params(array('page', 'info', 'x', 'y')), false); ?>
          </ul>
        </div>
      </div><div class="clear-both"></div>
      <!-- /PAGINATION-->   
      <?php
    }
    ?>

    <div class="product-listing-module-container">
      <?php 
      if ($Qlisting->numberOfRows() > 0) {
        if (file_exists(DIR_FS_TEMPLATE . 'modules/product_listing.php')) {
          require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'modules/product_listing.php'));
        } else {
          require($lC_Vqmod->modCheck('includes/modules/product_listing.php'));
        }      
      } else {
        echo '<div class="margin-bottom margin-top">' . $lC_Language->get('no_products_found') . '</div>' . "\n";
      } 
      ?>
    </div><div class="clear-both"></div>

    <?php
    if ( ($Qlisting->numberOfRows() > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
      ?>
      <!-- PAGINATION-->
      <div class="product-listing-module-pagination">
        <div class="pull-left"><?php echo $Qlisting->getBatchTotalPages($lC_Language->get('result_set_number_of_products')); ?></div>
        <div class="pull-right no-margin-bottom no-margin-top">
          <ul class="pagination">
            <?php echo $Qlisting->getBatchPageLinks('page', lc_get_all_get_params(array('page', 'info', 'x', 'y')), false); ?>
          </ul>
        </div>
      </div><div class="clear-both"></div>
      <!-- /PAGINATION--> 
      <?php 
    }
    ?>
  </div>
</div>
<script>
$(document).ready(function() {
  var buttonContentText;
  var mediaType = _setMediaType();
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'col-sm-6 col-lg-6') {
    thisContentClass = 'col-sm-6 col-lg-6';
  } else {
    thisContentClass = 'col-sm-4 col-lg-4';
  }  
  
  $(".product-listing-module-items").each(function(){
    
    var imageContent = $(this).find('div.product-listing-module-image').html();
    var nameContent = $(this).find('div.product-listing-module-name').html();
    var nameContentText = $(this).find('div.product-listing-module-name').text();
    var descContent = $(this).find('div.product-listing-module-description').html();
    var descContentText = $(this).find('div.product-listing-module-description').text();
    var priceContent = $(this).find('div.product-listing-module-price').html();
    var buttonContent = $(this).find('.product-listing-module-buy-now').html();
    buttonContentText = $(this).find('.product-listing-module-buy-now-button').text();

    var newNameContentText = (nameContentText.length > 18) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);  
    
    var newDescContentText = (descContentText.length > 65) ? descContentText.substr(0, 62) + '...' : descContentText;
    descContent = descContent.replace(descContentText, newDescContentText);      
    
    output = '<div class="' + thisContentClass+ ' with-padding">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 style="line-height:1.1;">' + nameContent + '</h3>' +
             '      <p class="">' + descContent + '</p>' +
             '      <div class="row">' +
             '        <div class="col-sm-6 col-lg-6">' +
             '          <p class="lead small-margin-bottom">' + priceContent + '</p>' +
             '        </div>' +
             '        <div class="col-sm-6 col-lg-6 no-margin-left product-listing-module-buy-now a">' + buttonContent + '</div>' +
             '      </div>' +
             '    </div>' +
             '  </div>' +
             '</div>';
              
    $(this).html(output);  
  });
  $('.product-listing-module-buy-now-button').addClass('btn btn-success btn-block');
  if (mediaType == 'small-tablet-landscape' || mediaType == 'tablet-portrait') {
     var textArr = buttonContentText.split(' ');
    $('.product-listing-module-buy-now-button').text(textArr[0]);  
    $('.product-listing-module-container p.lead').attr('style', 'font-size:1.1em;');  
    $('.product-listing-module-items').find('img').addClass('img-responsive');
  }
  $('.thumbnail').equalHeights();
});
</script>
<!--content/index/product_listing.php end-->