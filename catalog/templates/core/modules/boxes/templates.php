<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: templates.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/templates.php start-->
<div class="well" >
  <form role="form" name="templates" action="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false); ?>" class="box-templates-form no-margin-bottom form-inline" method="get">
    <ul class="box-templates list-unstyled">
      <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
        <?php echo $lC_Box->getContent(); ?>
    </ul>
  </form>  
</div>
<script>
$(document).ready(function() { 
  $('.box-templates-select').addClass('form-input-width');
});
$('.box-templates-selection').addClass('form-group full-width');
$('.box-templates-select').addClass('form-control');
</script>
<!--modules/boxes/templates.php end-->