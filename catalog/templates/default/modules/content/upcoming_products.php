<?php
/**  
*  $Id: upcoming_products.php v1.0 2013-01-01 datazen $
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
<!--modules/content/upcoming_products.php start-->
<div class="products_list products_slider">
  <h2 class="sub_title no-margin-bottom"><?php echo $lC_Box->getTitle(); ?></h2>
  <ul id="content-upcoming-products-ul" class="purple-line-bottom">
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $(".content-upcoming-products-container").each(function(){
    var imageContent = $(this).find('div.content-upcoming-products-image').html();
    var nameContent = $(this).find('div.content-upcoming-products-name').html();;
    var nameContentText = $(this).find('div.content-upcoming-products-name').text();;
    var priceContent = $(this).find('div.content-upcoming-products-price').html();
    var dateContent = $(this).find('div.content-upcoming-products-date').html();
    var textExpected = '<?php echo $lC_Language->get('upcoming_products_text_expected'); ?>';
    
    var newNameContentText = (nameContentText.length > 20) ? nameContentText.substr(0, 17) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);
    
    output = '<li>' + nameContent +
             '  <span class="content-upcoming-products-image">' + imageContent + '</span>' +
             '  <span class="content-upcoming-products-text"><div class="content-upcoming-products-text-price">' + priceContent + '</div><div class="content-upcoming-products-text-expected">' + textExpected + ': ' + dateContent + '</div></span>' +
             '</li>';
              
    $(this).html(output);  
  });
});
</script>
<!--modules/content/upcoming_products.php end-->