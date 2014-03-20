<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category_listing.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/index/category_listing.php start-->
<div class="row listing-container">
<div class="listing-title">
  <?php 
    if (PRODUCT_LIST_FILTER == '1') echo lC_Template_output::getManufacturerFilter();
    $Qlisting = lC_Template_output::getProductsListingSql();  
    
    if ( ($Qlisting->numberOfRows() > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
      ?>
      <!-- PAGINATION-->
      <div class="product-listing-module-pagination">
        <div class="pull-left"><?php echo $Qlisting->getBatchTotalPages($lC_Language->get('result_set_number_of_products')); ?></div>
        <div class="btn_previous_next pull-right no-margin-bottom no-margin-top">
          <ul class="pagination no-margin-top no-margin-bottom">
            <?php echo $Qlisting->getBatchPageLinks('page', lc_get_all_get_params(array('page', 'info', 'x', 'y')), false); ?>
          </ul>
        </div>
      </div><div class="clear-both"></div>
      <!-- /PAGINATION--> 
      <?php 
    }
    ?>
    </div>
    
  <div class="content-product-listing-container ">    
    
    <div class="">
      <?php echo lC_Template_output::getCategoryListing(); ?>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {

  var mediaType = _setMediaType();
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'col-sm-6 col-lg-6') {
    thisContentClass = 'col-sm-6 col-lg-6';
  } else {
    thisContentClass = 'col-sm-4 col-lg-4';
  }  
  
  var cnt = 0;
  $(".content-categories-container").each(function(){
    
    var imageContent = $(this).find('div.content-categories-image').html();
    var nameContent = $(this).find('div.content-categories-name').html();
    var nameContentText = $(this).find('div.content-categories-name').text();

    var newNameContentText = (nameContentText.length > 18) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);  
    
    output = '<div class="' + thisContentClass+ '">'+
             '  <div class="thumbnail align-center padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 class="no-margin-top no-margin-bottom">' + nameContent + '</h3>' +
             '    </div>' +
             '  </div>' +
             '</div>';
              
    $(this).html(output);  
    cnt++;
  });
  $('.content-categories-image-src').addClass('img-responsive');
});
</script>
<!--content/index/category_listing.php end-->