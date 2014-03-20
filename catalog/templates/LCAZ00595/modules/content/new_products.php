<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: new_products.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/new_products.php start-->
<div class="col-sm-12 col-lg-12">
  <div class="row margin-bottom">
    <div class="title"><h3 class="no-margin-top mainpagetop"><?php echo $lC_Box->getTitle(); ?></h3></div>
    <?php echo $lC_Box->getContent(); ?>
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
    thisContentClass = 'col-sm-4 col-lg-3';
  }   
  thisContentClass = 'col-sm-3 col-lg-3';
  $(".content-new-products-container").each(function(){
    var imageContent = $(this).find('div.content-new-products-image').html();
    var nameContent = $(this).find('div.content-new-products-name').html();
    var nameContentText = $(this).find('div.content-new-products-name').text();
    var descContent = $(this).find('div.content-new-products-desc').html();
    var descContentText = $(this).find('div.content-new-products-desc').text();
    var priceContent = $(this).find('div.content-new-products-price').html();
    var buttonContent = $(this).find('div.content-new-products-button').html();
    
    buttonContentText = $(this).find('div.content-new-products-button').text();
    var textAddToCart = '<?php echo $lC_Language->get('button_add_to_cart'); ?>';
    
    buttonContent =buttonContent.replace(buttonContentText, textAddToCart);
    
     var textAddToWishlist = '<?php echo $lC_Language->get('add_to_wishlist'); ?>';
    var newNameContentText = (nameContentText.length > 40) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent =nameContent.replace(nameContentText, newNameContentText);  
    nameDetails = nameContent.replace(nameContentText, 'Details');
    
    var newDescContentText = (descContentText.length > 65) ? descContentText.substr(0, 62) + '...' : descContentText;
    descContent = descContent.replace(descContentText, newDescContentText);      
    var av_rating='<?php //echo lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $lC_Product->getData('reviews_average_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $lC_Product->getData('reviews_average_rating'))); ?>';
    
     output = '<div class="' + thisContentClass+ ' with-padding">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 style="line-height:1.1;">' + nameContent + '</h3>' +
             '      <br/>' +
             '      <div class="row">' +
             '        <div class="col-sm-7 col-lg-7">' +
             '          <p class="lead">' + priceContent + '</p>' + 
             '        </div>' +
             '        <div class="addToCart pull-right col-sm-5 col-lg-5" >'+        
             '          <div id="reviewsContainer">'+             
             '          </div>' +
             '        </div>' +
             '      </div>'+
             '      </div>'+
             '    <div class="az_wishlistpart thumbButtons col-xs-12 col-sm-12 col-lg-12"> ' + 
             '      <div class=" col-xs-7 col-sm-7 col-lg-7"><div class="cart col-xs-8 col-sm-8 col-lg-8">' + buttonContent + '</div></div> '+
             '      <div class="col-xs-5 col-sm-5 col-lg-5"><div class="info col-xs-8 col-sm-8 col-lg-8">' + nameDetails + '</div></div> '+                         
             '    </div> '+
             '  </div>' +
             '</div>';   

    $(this).html(output);  
  });
  $('.content-new-products-add-button').addClass('btn btn-success btn-block');
  if (mediaType == 'small-tablet-landscape' || mediaType == 'tablet-portrait') {
     var textArr = buttonContentText.split(' ');
    $('.content-new-products-add-button').text(textArr[0]);  
    $('.content-new-products-container p.lead').attr('style', 'font-size:1.1em;');  
  }
  $('.content-new-products-image-src').addClass('img-responsive');
});
</script>

<!-- static banner -->
<div class="col-xs-12 col-sm-12 col-lg-12 staticbanner">
  <div class="row content-static-banner-container margin-bottom clear-both">
	<div class="col-xs-6 col-sm-6 col-lg-6"><?php echo $static_banners[0]['image']; ?></div>
	<div class="col-xs-6 col-sm-6 col-lg-6"><?php echo $static_banners[1]['image']; ?></div>
  </div>
</div>

<!--modules/content/new_products.php end-->