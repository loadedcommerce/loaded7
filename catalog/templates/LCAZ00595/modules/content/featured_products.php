<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/featured_products.php start-->
<div class="content-featured-products-div col-sm-12 col-lg-12">
  <div class="row">
    <h3 class="no-margin-top"><?php echo $lC_Box->getTitle(); ?></h3>
    <?php echo $lC_Box->getContent(); ?>
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
  
  $(".content-featured-products-container").each(function(){
    var imageContent = $(this).find('div.content-featured-products-image').html();
    var nameContent = $(this).find('div.content-featured-products-name').html();;
    var nameContentText = $(this).find('div.content-featured-products-name').text();
    var descContent = $(this).find('div.content-featured-products-desc').html();
    var priceContent = $(this).find('div.content-featured-products-price').html();
    var dateContent = $(this).find('div.content-featured-products-date').html();
    var buttonContent = $(this).find('div.content-featured-products-button').html();
    
    var newNameContentText = (nameContentText.length > 16) ? nameContentText.substr(0, 13) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);
    
    output = '<div class="' + thisContentClass + ' with-padding-no-top-bottom">' +
             '  <div class="thumbnail align-center large-padding-top">' + imageContent +
             '    <div class="caption">' +
             '      <h3 style="line-height:1.1;">' + nameContent + '</h3>' +
             '      <p class="">' + descContent + '</p>' +
             '      <div class="row">';
    if (mediaType == 'desktop') {
      output += '        <div class="col-sm-6 col-lg-6">' +
                '          <p class="lead small-margin-bottom">' + priceContent + '</p>' +
                '        </div>';
    }
    output += '        <div class="col-sm-6 col-lg-6 no-margin-left">' + buttonContent + '</div>' +
              '      </div>' +
              '    </div>' +
              '  </div>' +
              '</div>';
              
    $(this).html(output);  
  });
  $('.content-featured-products-image-src').addClass('img-responsive');
  $('.content-featured-products-add-button').addClass('btn btn-success btn-block');
  if (mediaType == 'small-tablet-landscape' || mediaType == 'tablet-portrait') {
     var textArr = buttonContentText.split(' ');
    $('.content-featured-products-add-button').text(textArr[0]);  
    $('.content-featured-products-container p.lead').attr('style', 'font-size:1.1em;');  
  }
});
</script>
<!--modules/content/featured_products.php end-->