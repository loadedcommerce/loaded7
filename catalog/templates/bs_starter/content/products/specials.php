<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/products/specials.php start-->  
<div class="row-fluid">
  <div class="span12">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1> 
    <?php echo $lC_Specials->getListingOutput(); ?>
  </div>
</div>
<script>
$(document).ready(function() {
  var buttonContentText;
  var mediaType = _setMediaType();
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'span6') {
    thisContentClass = 'span6';
  } else {
    thisContentClass = 'span4';
  }  
  
  $(".content-specials-listing-container").each(function(){
    
    var imageContent = $(this).find('div.content-specials-listing-image').html();
    var nameContent = $(this).find('div.content-specials-listing-name').html();
    var nameContentText = $(this).find('div.content-specials-listing-name').text();
    var descContent = $(this).find('div.content-specials-listing-description').html();
    var descContentText = $(this).find('div.content-specials-listing-description').text();
    var priceContent = $(this).find('div.content-specials-listing-price').html();
    var buttonContent = $(this).find('.content-specials-listing-buy-now').html();
    buttonContentText = $(this).find('.content-specials-listing-buy-now-button').text();

    var newNameContentText = (nameContentText.length > 18) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);  
    
    var newDescContentText = (descContentText.length > 65) ? descContentText.substr(0, 62) + '...' : descContentText;
    descContent = descContent.replace(descContentText, newDescContentText);      
    
    output = '<div class="' + thisContentClass+ ' with-padding-no-top-bottom">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 style="line-height:1.1;">' + nameContent + '</h3>' +
             '      <p class="">' + descContent + '</p>' +
             '      <div class="row-fluid">' +
             '        <div class="span6">' +
             '          <p class="lead">' + priceContent + '</p>' +
             '        </div>' +
             '        <div class="span6 no-margin-left content-specials-listing-buy-now a">' + buttonContent + '</div>' +
             '      </div>' +
             '    </div>' +
             '  </div>' +
             '</div>';
              
    $(this).html(output);  
  });
  $('.content-specials-listing-buy-now-button').addClass('btn btn-success btn-block');
  if (mediaType == 'small-tablet-landscape' || mediaType == 'tablet-portrait') {
     var textArr = buttonContentText.split(' ');
    $('.content-specials-listing-buy-now-button').text(textArr[0]);  
    $('.content-specials-listing-container p.lead').attr('style', 'font-size:1.1em;');  
  }
});
</script>