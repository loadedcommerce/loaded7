<?php
/**  
*  $Id: new_products.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
?>
<!--modules/content/new_products.php start-->
<div class="products_list products_slider">
  <h2 class="sub_title"><?php echo $lC_Box->getTitle(); ?></h2>
  <ul class="content-new-products-carousel jcarousel-skin-tango">
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<!--modules/content/new_products.php end-->
<script>
$(document).ready(function() {
  var buttonContentText;
  
  $(".content-new-products-container").each(function(){
    var imageContent = $(this).find('div.content-new-products-image').html();
    var nameContent = $(this).find('div.content-new-products-name').html();
    var nameContentText = $(this).find('div.content-new-products-name').text();
    var descContent = $(this).find('div.content-new-products-desc').html();
    var descContentText = $(this).find('div.content-new-products-desc').text();
    var priceContent = $(this).find('div.content-new-products-price').html();
    var buttonContent = $(this).find('div.content-new-products-button').html();
    buttonContentText = $(this).find('div.content-new-products-button').text();
    var textFeatureNotAvailable = '<?php echo $lC_Language->get('feature_not_available'); ?>';
    var textAddToWishlist = '<?php echo $lC_Language->get('add_to_wishlist'); ?>';
    var textAddToCart = '<?php echo $lC_Language->get('button_add_to_cart'); ?>';

    var newNameContentText = (nameContentText.length > 18) ? nameContentText.substr(0, 15) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText); 
    
    var newDescContentText = (descContentText.length > 65) ? descContentText.substr(0, 62) + '...' : descContentText;
    descContent = descContent.replace(descContentText, newDescContentText);      

    var priceHtml = '<span class="pr_price">' + priceContent + '</span><span class="pr_add">' + textAddToCart + '</span>';
    
    output = '<li>'+ imageContent + 
             '  <div class="product_info">' +
             '    <h3>' + nameContent + '</h3>' +
             '    <small>' + descContent + '</small>' +
             '  </div>' +
             '  <div class="price_info" style="margin-top:10px;"> <a href="javascript://" onclick="alert(\'' + textFeatureNotAvailable + '\'); return false;">' + textAddToWishlist + '</a>' + buttonContent +
             '  </div>' +
             '</li>';
             
    $(this).html(output);  
    $('.content-new-products-add-button').addClass('price_add').html(priceHtml);
  });
  $('.content-new-products-container').find('img').addClass('product_image');
  //$('.content-new-products-carousel').jcarousel();
});
</script>
