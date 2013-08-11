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
  <ul id="box-currencies" class="nav nav-list">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
      <form id="box-manufacturer-form" name="currencies" class="no-margin-bottom" action="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false); ?>" method="get">
        <?php echo $lC_Box->getContent(); ?>
      </form>
  </ul>
</div>
<script>
$(document).ready(function() {
  $("#box-currencies li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('align-center margin-left-li');
  });  
  $('#box-currencies-select').attr('onchange', 'this.form.submit();');
});
</script>
<!--modules/boxes/currencies.php end-->

