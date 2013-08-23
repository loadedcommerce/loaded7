<?php
/**
  @package    catalog::currencies::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: currencies.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/currencies.php start-->
<div class="well" >
  <form role="form" class="form-inline no-margin-bottom" id="currencies" name="currencies" action="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false); ?>" method="get">
    <ul class="box-currencies list-unstyled">
      <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
        <?php echo $lC_Box->getContent(); ?>
    </ul>
  </form>
</div>
<script>
$(document).ready(function() { 
  $('.box-currencies-select').addClass('form-input-width');
});
$('.box-currencies-selection').addClass('form-group full-width');
$('.box-currencies-select').addClass('form-control');
</script>
<!--modules/boxes/currencies.php end-->