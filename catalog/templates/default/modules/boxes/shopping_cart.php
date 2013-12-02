<?php
/**  
*  $Id: shopping_cart.php v1.0 2013-01-01 datazen $
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
<!--modules/boxes/shopping_cart.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<ul class="category">
  <?php echo $lC_Box->getContent(); ?>
</ul>
<script>
$(document).ready(function() {
  $(".box-shopping-cart-product").find("a").attr("style", "display:inline;");
  $(".box-shopping-cart-subtotal").addClass("align-right strong purple");
});
</script>
<!--modules/boxes/shopping_cart.php end-->