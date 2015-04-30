<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/specials.php start-->
<div class="content-specials-div col-sm-12 col-lg-12">
  <div class="row margin-bottom">
    <h3 class="no-margin-top"><?php echo $lC_Box->getTitle(); ?></h3>
    <?php echo $lC_Box->getContent(); ?>
  </div>
</div>
<script>
$(document).ready(function() {
  var mediaType = _setMediaType();
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'col-sm-12 col-md-6 col-lg-6') {
    thisContentClass = 'col-sm-12 col-md-6 col-lg-6';
  } else {
    thisContentClass = 'col-sm-12 col-md-4 col-lg-4';
  }   
  
  $(".content-specials-container").each(function(){
    var imageContent = $(this).find('div.content-specials-image').html();
    var nameContent = $(this).find('div.content-specials-name').html();
    var nameContentText = $(this).find('div.content-specials-name').text();
    var descContent = $(this).find('div.content-specials-desc').html();
    var descContentText = $(this).find('div.content-specials-desc').text();
    var priceContent = $(this).find('div.content-specials-price').html();
    var buttonContent = $(this).find('div.content-specials-button').html();
    var buttonContentText = $(this).find('div.content-specials-button').text();

    var newNameContentText = (nameContentText.length > 18) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);  
    
    var newDescContentText = (descContentText.length > 65) ? descContentText.substr(0, 62) + '...' : descContentText;
    descContent = descContent.replace(descContentText, newDescContentText);      
    
    
    output = '<div class="' + thisContentClass+ ' with-padding-no-top-bottom">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 style="line-height:1.1;">' + nameContent + '</h3>' +
             '      <p class="">' + descContent + '</p>' +
             '      <div class="row pricing-row">' +
             '        <div class="col-sm-6 col-lg-6">' +
             '          <p class="lead small-margin-bottom">' + priceContent + '</p>' +
             '        </div>' +
             '        <div class="col-sm-6 col-lg-6 no-margin-left buy-btn-div">' + buttonContent + '</div>' +
             '      </div>' +
             '    </div>' +
             '  </div>' +
             '</div>';
                 
    $(this).html(output);  
  });
  $('.content-specials-add-button').addClass('btn btn-success btn-block');
  if (mediaType == 'small-tablet-landscape' || mediaType == 'tablet-portrait') {
     var textArr = buttonContentText.split(' ');
    $('.content-specials-add-button').text(textArr[0]);  
    $('.content-specials-container p.lead').attr('style', 'font-size:1.1em;');  
  }
  $('.content-specials-image-src').addClass('img-responsive');
});
</script>
<!--modules/content/specials.php end-->