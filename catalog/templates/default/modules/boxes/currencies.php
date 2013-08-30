<?php
/**  
*  $Id: currencies.php v1.0 2013-01-01 datazen $
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
<!--modules/boxes/currencies.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<ul class="category large-margin-bottom">
  <form name="currencies" class="margin-bottom" action="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false); ?>" method="get">
    <?php echo $lC_Box->getContent(); ?>
  </form>
</ul>
<script>
$(document).ready(function() {
  $('.box-currencies-select').attr('onchange', '$(this).closest("form").submit();').attr('style', 'width:100%');
});
</script>
<!--modules/boxes/currencies.php end-->