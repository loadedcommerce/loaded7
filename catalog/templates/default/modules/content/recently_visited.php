<?php
/**  
*  $Id: recently_visited.php v1.0 2013-01-01 datazen $
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
<!--modules/content/recently_visited.php start-->
<div class="products_list products_slider">
  <h2 class="sub_title no-margin-bottom"><?php echo $lC_Box->getTitle(); ?></h2>
  <ul id="content-recently-visited-ul" class="purple-line-bottom">
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $(".content-recently-visited-container").each(function(){
    var imageContent = $(this).find('div.content-recently-visited-image').html();
    var nameContent = $(this).find('div.content-recently-visited-name').html();;
    var nameContentText = $(this).find('div.content-recently-visited-name').text();;
    var priceContent = $(this).find('div.content-recently-visited-price').html();
    var fromContent = $(this).find('div.content-recently-visited-from').html();
    
    var newNameContentText = (nameContentText.length > 20) ? nameContentText.substr(0, 17) + '...' : nameContentText;
    nameContent = nameContent.replace(nameContentText, newNameContentText);
    
    output = '<li>' + nameContent +
             '  <span class="content-recently-visited-image">' + imageContent + '</span>';

    if (priceContent != '') {             
      output += '  <span class="content-recently-visited-text"><div class="content-recently-visited-text-price">' + priceContent + '</div><div class="content-recently-visited-text-from">' + fromContent + '</div></span>';
    } else {  
      output += '  <span class="content-recently-visited-text"><div class="content-recently-visited-text-from">' + fromContent + '</div></span>';
    }
    
    output += '</li>';
              
    $(this).html(output);  
  });
});
</script>
<!--modules/content/recently_visited.php end-->