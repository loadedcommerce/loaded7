<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_notifications.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/product_notifications.php start-->
<div class="well" >
  <ul id="box-product-notifications" class="nav nav-list">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $("#box-product-notifications li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('margin-left-li');
  });
  $('#box-product_notifications-image-container').html('<img class="product_notifications-image" src="templates/bs_starter/images/icons/32/info.png" border="0">');
  $("#box-product-notifications li:last-child").addClass('small-margin-top align-center');
});
</script>
<!--modules/boxes/product_notifications.php end-->