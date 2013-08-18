<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: categories.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/categories.php start-->
<div class="well" >
  <div class="box-header small-margin-bottom small-margin-left"><?php echo $lC_Box->getTitle(); ?></div>
  <?php echo $lC_Box->getContent(); ?>
</div>
<script>
$('.box-categories-ul-top').addClass('list-unstyled list-indent-large');
$('.box-categories-ul').addClass('list-unstyled list-indent-large');
</script>
<!--modules/boxes/categories.php end-->