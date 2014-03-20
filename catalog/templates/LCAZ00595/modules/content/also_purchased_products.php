<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: also_purchased_products.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/also_purchased_products.php start-->
<div class="content-also-purchased-products-div col-sm-12 col-lg-12">
  <div class="row">
    <h3 class="no-margin-top"><?php echo $lC_Box->getTitle(); ?></h3>
    <?php echo $lC_Box->getContent(); ?>
  </div>
</div>
<script>
$(document).ready(function() {  
  var buttonContentText;
  var mediaType = _setMediaType();   
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'col-sm-6 col-lg-6') {
    thisContentClass = 'col-sm-4 col-lg-4';
  } else {
    thisContentClass = 'col-sm-3 col-lg-3';
  }
  
  $(".content-also-purchased-products-container").each(function(){
    var imageContent = $(this).find('div.content-also-purchased-products-image').html();
    var nameContent = $(this).find('div.content-also-purchased-products-name').html();
    var nameContentText = $(this).find('div.content-also-purchased-products-name').text();
    var priceContent = $(this).find('div.content-also-purchased-products-price').html();
    var dateContent = $(this).find('div.content-also-purchased-products-date').html();
    var buttonContent = $(this).find('div.content-also-purchased-products-button').html();
    buttonContentText = $(this).find('div.content-also-purchased-products-button').text();
    var textAddToCart = '<?php echo $lC_Language->get('button_add_to_cart'); ?>';
    
    buttonContent =buttonContent.replace(buttonContentText, textAddToCart);
    var newNameContentText = (nameContentText.length > 16) ? nameContentText.substr(0, 13) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);
    
    output = '<div class="' + thisContentClass + ' with-padding-no-top-bottom">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 class="content-also-purchased-products-text-name small-margin-top no-margin-bottom">' + nameContent + '</h3>' +
             '      <div class="row">' +
             '        <div class="col-sm-6 col-lg-6 no-padding-right">' +
             '          <p class="lead no-margin-bottom">' + priceContent + '</p>' +
             '        </div>' +
             '        <div class="col-sm-6 col-lg-6 margin-top">' + buttonContent + '</div>' +
             '      </div>' +
             '    </div>' +
             '  </div>' +
             '</div>';            
              
    $(this).html(output);  
  });
  if (mediaType == 'small-tablet-landscape' || mediaType == 'tablet-portrait' || mediaType == 'tablet-landscape') {
    var textArr = buttonContentText.split(' ');
    $('.content-also-purchased-products-add-button').text(textArr[0]);  
    $('.content-also-purchased-products-container p.lead').attr('style', 'font-size:1.1em;');  
  }  
  $('.content-also-purchased-products-add-button').addClass('btn btn-success btn-block');
  $('.content-also-purchased-products-image-src').addClass('img-responsive');
});
</script>
<!--modules/content/also_purchased_products.php end-->