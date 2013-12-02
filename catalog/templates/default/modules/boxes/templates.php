<?php
/**  
*  $Id: templates.php v1.0 2013-01-01 datazen $
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
<!--modules/boxes/templates.php start-->
<h1><?php echo $lC_Box->getTitle(); ?></h1>
<ul class="category large-margin-bottom">
  <form name="templates" action="<?php echo lc_href_link(basename($_SERVER['SCRIPT_FILENAME']), null, 'AUTO', false); ?>" class="no-margin-bottom" method="get">
    <?php echo $lC_Box->getContent(); ?>
  </form>
</ul>
<!--modules/boxes/templates.php end-->