<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mainpage_banner.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/content/mainpage_banner.php start-->     
<div class="col-sm-12 col-lg-12">
  <div class="row content-mainpage-banner-div margin-bottom clear-both">
    <?php echo $lC_Box->getContent(); ?>
  </div>
</div>
<!--modules/content/mainpage_banner.php end-->
<script>
$(document).ready(function() {     
  $('.content-mainpage-banner-div img').addClass('img-responsive');
});
</script>