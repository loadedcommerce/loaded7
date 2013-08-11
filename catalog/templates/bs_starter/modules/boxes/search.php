<?php
/**
  @package    catalog::search::boxes
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: search.php v1.0 2013-08-08 datazen $
*/
?>
<!--modules/boxes/search.php start-->
<div class="well" >
    <ul id="box-search" class="nav nav-list">
      <li class="nav-header"><?php echo $lC_Box->getTitle(); ?></li>
        <form name="box-search-form" action="<?php echo lc_href_link(FILENAME_SEARCH, null, 'NONSSL', false); ?>" class="no-margin-bottom" method="get">
          <?php echo $lC_Box->getContent(); ?>
        </form>
    </ul>
</div>
<script>
$(document).ready(function() {
  $("#box-search li").each(function(){
    if ($(this).attr('class') != 'nav-header') $(this).addClass('margin-left-li');
  });  
  $('#box-search-submit').html('<i class="btn btn-small btn-info cusrsor:pointer margin-bottom">Go</i>');
  $('#box-keywords').attr('style', 'width:73%;');
  $("#box-search li:last-child").addClass('margin-top-li').attr('style', 'padding-left:4px;');
});
</script>
<!--modules/boxes/search.php end-->

