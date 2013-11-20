<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/specials.php start-->
<div class="well" >
  <ul class="box-specials list-unstyled">
    <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $('.box-specials li').each(function () {
    if ($(this).is(':first-child')) {
    } else {
      $(this).addClass('text-center');
    }
  });
  
  var imageContent = $('.box-specials-image').html();
  $('.box-specials-image').html('<div class="thumbnail">' + imageContent + '</div>');  
  $('.box-specials-price').addClass('red');
  $('.box-specials-buy-now button').addClass('btn btn-warning margin-top');
  $('.box-specials-image-src').addClass('img-responsive');
  
});
</script>
<!--modules/boxes/specials.php end-->