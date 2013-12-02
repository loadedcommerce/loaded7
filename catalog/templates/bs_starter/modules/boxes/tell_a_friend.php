<?php
/**
  @package    catalog::templates::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: tell_a_friend.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/tell_a_friend.php start-->
<div class="well" >
  <form role="form" class="box-tell-a-friend-form no-margin-bottom form-inline" name="tell_a_friend" action="<?php echo lc_href_link(FILENAME_PRODUCTS, 'tell_a_friend&' . lc_sanitize_string(basename(key(array_slice($_GET, 0, 1) )))); ?>" method="post">   
    <ul class="box-search list-unstyled list-indent-large">
      <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
        <?php echo $lC_Box->getContent(); ?>
    </ul>
  </form>
</div>
<script>
$(document).ready(function() {
  var mediaType = _setMediaType();
  $('.box-tell-a-friend-submit').html('<i class="btn btn-sm btn-primary cusrsor:pointer">Go</i>');
  $(".box-search li:last-child").addClass('help-block small-padding-left');
  $('.box-tell-a-friend-input').addClass('form-group');
  if (mediaType == 'tablet-portrait' || mediaType == 'small-tablet-landscape') {
    $('.box-to-email-address').attr('style', 'width:60%; display:inline;').addClass('form-control');
  } else {
    $('.box-to-email-address').attr('style', 'width:73%; display:inline;').addClass('form-control');
  }
});
</script>
<!--modules/boxes/tell_a_friend.php end-->