<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category_listing.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/index/category_listing.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1 class="categories_h1"><?php echo $lC_Template->getPageTitle(); ?></h1>
      <?php 
        if (lC_Bs_starter::getCategoryDescription() != '') {
          echo lC_Bs_starter::getCategoryDescription(); 
        }
      ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2" class="categories_listing">
        <tr><?php echo lC_Bs_starter::getCategoryListing(); ?></tr>
      </table>
    </div>
    <div style="clear:both;">&nbsp;</div>
  </div>
</div>
<!--content/index/category_listing.php end-->