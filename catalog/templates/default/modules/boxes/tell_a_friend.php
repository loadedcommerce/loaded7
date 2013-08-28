<?php
/**  
*  $Id: tell_a_friend.php v1.0 2013-01-01 datazen $
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
<!--modules/boxes/tell_a_friend.php start-->
<h1 class="clear-both large-margin-top"><?php echo $lC_Box->getTitle(); ?></h1>
<ul class="category">
  <?php echo $lC_Box->getContent(); ?>
</ul>
<script>
$(document).ready(function() {
  var icon = '<?php echo lc_icon('send.png', null, null, 'style="vertical-align:middle; margin-left:5px;"'); ?>';
  $('.box-tell-a-friend-submit').html(icon).attr('style', 'cursor:pointer;');
  $('.box-tell-a-friend-text').addClass('margin-top');
});
</script>
<!--modules/boxes/tell_a_friend.php end-->