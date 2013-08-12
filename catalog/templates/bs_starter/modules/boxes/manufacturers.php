<?php
/**
  @package    catalog::manufacturers::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: manufacturers.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/manufacturers.php start-->
<div class="well" >
  <ul id="box-manufacturers" class="nav nav-list">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
      <form id="box-manufacturer-form" name="manufacturers" class="no-margin-bottom" action="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false); ?>" method="get">
        <?php echo $lC_Box->getContent(); ?>
      </form>
  </ul>
</div>
<script>
$(document).ready(function() {
  $("#box-manufacturers li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('align-center margin-left-li');
  });  
  $('#box-manufacturers-select').attr('onchange', 'this.form.submit();').attr('style', 'width:100%');
});
</script>
<!--modules/boxes/manufacturers.php end-->

