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
<ul class="category align-center">
  <?php echo $lC_Box->getContent(); ?>
</ul>
<script>
$(document).ready(function() {
  $(".box-specials-image").find("a").addClass("product_image");
  $(".box-products-price").find("s").attr("style", "color:red;");
  $(".box-products-price").attr("style", "font-size:1.2em;");
  $('.box-specials-buy-now').find('button').addClass('button brown_btn');
});
</script>
<!--modules/boxes/specials.php end-->