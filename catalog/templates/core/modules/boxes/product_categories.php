<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: product_categories.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/product_categories.php start-->
<div class="well">
  <div class="box-header small-margin-bottom small-margin-left"><?php echo $lC_Box->getTitle(); ?></div>
  <?php echo $lC_Box->getContent(); ?>
</div>
<script>
$('.box-product-categories-ul-top').addClass('list-unstyled list-indent-large');
$('.box-product-categories-ul').addClass('list-unstyled list-indent-large');
</script>
<!--modules/boxes/product_categories.php end-->