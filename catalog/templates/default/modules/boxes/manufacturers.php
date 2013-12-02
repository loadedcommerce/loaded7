<?php
/**  
*  $Id: manufacturers.php v1.0 2013-01-01 datazen $
*
*  LoadedCommerce, Innovative eCommerce Solutions
*  http://www.loadedcommerce.com
*
*  Copyright (c) 2013 Loaded Commerce, LLC
*
*  @author     Loaded Commerce Team
*  @copyright  (c) 2013 Loaded Commerce Team
*  @license    http://loadedcommerce.com/license.html
*/
?>
<!--modules/boxes/manufacturers.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<ul class="category large-margin-bottom">
  <form name="manufacturers" class="margin-bottom" action="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'NONSSL', false); ?>" method="get">
    <?php echo $lC_Box->getContent(); ?>
  </form>
</ul>
<script>
$(document).ready(function() {
  $('.box-manufacturers-select').attr('style', 'width:100%');
});
</script>
<!--modules/boxes/manufacturers.php end-->