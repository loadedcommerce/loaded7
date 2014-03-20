<?php
/**
  @package    catalog::search::boxes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: search.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/search.php start-->
<div class="well" >
  <form role="form" name="box-search-form" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" class="form-inline no-margin-bottom" method="get">
    <ul class="box-search list-unstyled list-indent-large">
      <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
        <?php echo $lC_Box->getContent(); ?>
    </ul>
  </form>
</div>
<script>
$(document).ready(function() {
  var mediaType = _setMediaType();
  $('.box-search-submit').html('<i class="btn btn-sm btn-primary cusrsor:pointer">Go</i>');
  $(".box-search li:last-child").addClass('help-block small-padding-left');
  $('.box-search-input').addClass('form-group');
  if (mediaType == 'tablet-portrait' || mediaType == 'small-tablet-landscape') {
    $('.box-keywords').attr('style', 'width:60%; display:inline;').addClass('form-control');
  } else {
    $('.box-keywords').attr('style', 'width:73%; display:inline;').addClass('form-control');
  }
});
</script>
<!--modules/boxes/search.php end-->