<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: whats_new.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/whats_new.php start-->
<aside role="complementary">
<div class="well" >
  <ul class="box-whats-new list-unstyled">
    <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $('.box-whats-new li').each(function () {
    if ($(this).is(':first-child')) {
    } else {
      $(this).addClass('text-center');
    }
  });
  
  var imageContent = $('.box-whats-new-image').html();
  $('.box-whats-new-image').html('<div class="thumbnail">' + imageContent + '</div>');  
  $('.box-whats-new-price').addClass('red');
  $('.box-whats-new-buy-now button').addClass('btn btn-warning margin-top');
  $('.box-whats-new-image-src').addClass('img-responsive');
});
</script>
</aside>
<!--modules/boxes/whats_new.php end-->
