<?php
/**
  @package    catalog::manufacturers::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturers.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/manufacturers.php start-->
<div class="well" >
  <form role="form" class="form-inline no-margin-bottom" id="manufacturers" name="manufacturers" action="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false); ?>" method="get">
    <ul class="box-templates list-unstyled">
      <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
        <?php echo $lC_Box->getContent(); ?>
    </ul>
  </form>
</div>
<script>
$(document).ready(function() { 
  $('.box-manufacturers-select').addClass('form-input-width');
});
$('.box-manufacturers-selection').addClass('form-group full-width');
$('.box-manufacturers-select').addClass('form-control');
</script>
<!--modules/boxes/manufacturers.php end-->