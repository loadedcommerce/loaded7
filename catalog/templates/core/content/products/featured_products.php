<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured.php v1.0 2013-08-08 datazen $
*/                          
?>
<!--content/index/product_listing.php start-->
<div class="row">
  <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
  <?php echo $lC_Featured_products->getListingOutput(); ?>
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
  
  $(".content-featured-products-listing-container").each(function(){
    
    var imageContent = $(this).find('div.content-featured-products-listing-image').html();
    var nameContent = $(this).find('div.content-featured-products-listing-name').html();
    var nameContentText = $(this).find('div.content-featured-products-listing-name').text();
    var descContent = $(this).find('div.content-featured-products-listing-description').html();
    var descContentText = $(this).find('div.content-featured-products-listing-description').text();
    var priceContent = $(this).find('div.content-featured-products-listing-price').html();
    var buttonContent = $(this).find('.content-featured-products-listing-buy-now').html();
    buttonContentText = $(this).find('.content-featured-products-listing-buy-now-button').text();

    var newNameContentText = (nameContentText.length > 18) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);  
    
    var newDescContentText = (descContentText.length > 65) ? descContentText.substr(0, 62) + '...' : descContentText;
    descContent = descContent.replace(descContentText, newDescContentText);      
    
    output = '<div class="' + thisContentClass+ ' with-padding">'+
             '  <div class="align-center large-padding-top">'+ imageContent +
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
  $('.content-featured-products-listing-buy-now-button').addClass('btn btn-success btn-block');
  if (mediaType == 'small-tablet-landscape' || mediaType == 'tablet-portrait') {
     var textArr = buttonContentText.split(' ');
    $('.content-featured-products-listing-buy-now-button').text(textArr[0]);  
    $('.content-featured-products-listing-container p.lead').attr('style', 'font-size:1.1em;');
  }
});
</script>
<!--content/index/product_listing.php end-->