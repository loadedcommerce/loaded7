<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: featured_products.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/featured_products.php start-->
<div class="well">
  <ul class="box-featured-products list-unstyled">
    <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $('.box-featured-products li').each(function () {
    if ($(this).is(':first-child')) {
    } else {
      $(this).addClass('text-center');
    }
  });
  
  $('.box-featured-products-buy-now button').addClass('btn btn-success margin-top');
  $('.box-featured-products-name').addClass('margin-top');
});
</script>
<!--modules/boxes/featured_products.php end-->