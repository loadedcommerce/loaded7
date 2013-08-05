<?php
/**  
*  $Id: specials.php v1.0 2013-01-01 datazen $
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
<!--modules/boxes/specials.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<div id="specialsBox">
  <div class="products_list products_slider">
    <ul>
      <li><?php echo $lC_Box->getContent(); ?></li>
    </ul>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#sBoxImg").find("a").addClass("product_image");
    var sBoxText = $("#sBoxName").find("a").html();
    var sBoxURL = $("#sBoxName").find("a").attr("href");
    var sBoxOldPrice = $("#sBoxPrice").find("s").html();                                                                                                                                                                                                              
    var sBoxNewPrice = $("#sBoxPrice").find(".productSpecialPrice").html();
    $("#sBoxName").html('<div class="product_info"><b><a href="' + sBoxURL + '">' + sBoxText + '</a></b></div>');
    $("#sBoxPrice").html('<a href="' + sBoxURL + '&action=cart_add" class="noDecoration"><div class="price_info"><button class="price_add" type="button"><span class="pr_price" style="white-space:nowrap;"><s>' + sBoxOldPrice + '</s> <span class="productSpecialPrice">' + sBoxNewPrice + '</span></span><span class="pr_add"><?php echo $lC_Language->get('button_buy_now'); ?></span></button></div></a>');
  });
</script>
<!--modules/boxes/specials.php end-->