<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: checkout_trail.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/checkout_trail.php start-->
<div class="well" >
  <ul class="box-checkout-trail list-unstyled list-indent-large">
    <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<!--modules/boxes/checkout_trail.php end-->