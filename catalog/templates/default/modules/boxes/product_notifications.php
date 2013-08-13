<?php
/**  
*  $Id: product_notifications.php v1.0 2013-01-01 datazen $
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
<!--modules/boxes/product_notifications.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<ul class="category">
  <?php echo $lC_Box->getContent(); ?>
</ul>
<script>
$('.box-product_notifications-image-container').html('<img class="product_notifications-image" src="templates/default/images/subscribe_btn.png" border="0" style="float:left; margin-right:10px;">');
</script>
<!--modules/boxes/product_notifications.php end-->