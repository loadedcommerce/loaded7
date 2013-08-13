<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: specials.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/specials.php start-->
<div class="well" >
  <ul class="box-specials nav nav-list">
    <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<script>
$(document).ready(function() {
  $(".box-specials li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('align-center margin-left-li');
  });  
  var imageContent = $('.box-specials-image').html();
  $('.box-specials-image').html('<div class="thumbnail">' + imageContent + '</div>');  
  $('.box-specials-price').addClass('red');
  $('.box-specials-buy-now button').addClass('btn btn-warning margin-top');
});
</script>
<!--modules/boxes/specials.php end-->