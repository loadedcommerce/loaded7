<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: also_purchased_products.php v1.0 2013-08-08 datazen $
*/
global $lC_Language;
?>
<!--modules/content/also_purchased_products.php start-->
<style>
.content-also-purchased-products-image-tag { padding-top:20px; }
.content-also-purchased-products-text-expected { font-weight:200; font-size:.9em; }
.content-also-purchased-products-text-price { font-size:1.3em; font-weight:400; }
.content-also-purchased-products-text-name { line-height:1.1; font-size:1.3em; }
</style>
<div class="row-fluid articles-grid">
  <h2 class="sub_title"><?php echo $lC_Box->getTitle(); ?></h2>
  <?php echo $lC_Box->getContent(); ?>
</div>
<script>
$(document).ready(function() {     
  var mainContentClass = $('#main-content-container').attr('class');
  if(mainContentClass == 'span6') {
    thisContentClass = 'span4';
  } else {
    thisContentClass = 'span3';
  }
  
  $(".content-also-purchased-products-container").each(function(){
    var imageContent = $(this).find('div.content-also-purchased-products-image').html();
    var nameContent = $(this).find('div.content-also-purchased-products-name').html();
    var nameContentText = $(this).find('div.content-also-purchased-products-name').text();
    var descContent = $(this).find('div.content-also-purchased-products-desc').html();
    var descContentText = $(this).find('div.content-also-purchased-products-desc').text();    
    var priceContent = $(this).find('div.content-also-purchased-products-price').html();
    var dateContent = $(this).find('div.content-also-purchased-products-date').html();
    var buttonContent = $(this).find('div.content-also-purchased-products-button').html();
    var buttonContentText = $(this).find('div.content-also-purchased-products-button').text();
    var newNameContentText = (nameContentText.length > 16) ? nameContentText.substr(0, 13) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);
    
    output = '<div class="' + thisContentClass + ' ">'+
             '  <div class="thumbnail align-center large-padding-top">'+ imageContent +
             '    <div class="caption">' +
             '      <h3 class="content-also-purchased-products-text-name">' + nameContent + '</h3>' +
             '      <div class="row-fluid">' +
             '        <div class="span6">' +
             '          <p class="lead">' + priceContent + '</p>' +
             '        </div>' +
             '        <div class="span6 no-margin-left">' + buttonContent + '</div>' +
             '      </div>' +
             '    </div>' +
             '  </div>' +
             '</div>';
              
    $(this).html(output);  
  });
  $('.content-also-purchased-products-add-button').addClass('btn btn-success btn-block');
});
</script>
<!--modules/content/also_purchased_products.php end-->