<?php
/**  
*  $Id: search.php v1.0 2013-01-01 datazen $
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
<!--modules/boxes/search.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<ul class="category">
  <form name="search" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" method="get">
    <?php echo $lC_Box->getContent(); ?>
  </form>
</ul>
<script>
$(document).ready(function() {
  var icon = '<?php echo lc_icon('send.png', null, null, 'style="vertical-align:middle; margin-left:5px;"'); ?>';
  $('.box-search-submit').html(icon);
  $('.box-keywords').attr('style', 'width:73%;');
  $(".box-search-input").find('a').attr('style', 'display:inline; cursor:pointer;');
});
</script>
<!--modules/boxes/search.php end-->