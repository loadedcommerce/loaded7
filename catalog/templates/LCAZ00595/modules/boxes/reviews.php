<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/reviews.php start-->
<div class="well" >
  <ul class="box-reviews list-unstyled">
    <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $('.box-reviews li').each(function () {
    if ($(this).is(':first-child')) {
    } else {
      $(this).addClass('text-center');
      if ($(this).is(':last-child')) $(this).addClass('margin-top');
    }
  });
  
  var imageContent = $('.box-reviews-image').html();
  $('.box-reviews-image').html('<div class="thumbnail">' + imageContent + '</div>');  
  $('.box-reviews-image-src').addClass('img-responsive');
});
</script>
<!--modules/boxes/reviews.php end-->