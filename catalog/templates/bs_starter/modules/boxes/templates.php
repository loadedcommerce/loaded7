<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: templates.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/templates.php start-->
<div class="well" >
  <form name="templates" action="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false); ?>" class="no-margin-bottom" method="get">
    <ul id="box-templates" class="nav nav-list">
      <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
      <?php echo $lC_Box->getContent(); ?>
    </ul>
  </form>
</div>
<script>
$(document).ready(function() {
  $("#box-templates li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('align-center margin-left-li');
  });  
  $('#template').attr('onchange', 'this,form.submit();');
});
</script>
<!--modules/boxes/templates.php end-->

