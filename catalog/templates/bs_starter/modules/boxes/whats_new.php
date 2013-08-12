<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: whats_new.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/whats_new.php start-->
<div class="well" >
  <ul id="box-whats-new" class="nav nav-list">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $("#box-whats-new li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('align-center margin-left-li');
  });  
  var imageContent = $('.box-whats-new-image').html();
  $('.box-whats-new-image').html('<div class="thumbnail">' + imageContent + '</div>');
  $('.box-whats-new-buy-now button').addClass('btn btn-warning margin-top');
});
</script>
<!--modules/boxes/whats_new.php end-->