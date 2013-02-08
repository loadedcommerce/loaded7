<?php
/*
  $Id: category_listing.php v1.0 2013-01-01 datazen $ 

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--content/index/category_listing.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr><?php echo lC_Default::getCategoryListing(); ?></tr>
        </table>
    </div>
    <div style="clear:both;">&nbsp;</div>
  </div>
</div>
<!--content/index/category_listing.php end-->