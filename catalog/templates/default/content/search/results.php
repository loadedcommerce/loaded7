<?php
/**  
*  $Id: results.php v1.0 2013-01-01 datazen $
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
<!--content/search/results.php start-->
<div id="searchResults" class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1> 
      <?php 
      if (file_exists(DIR_FS_TEMPLATE . 'modules/product_listing.php')) {
        require(DIR_FS_TEMPLATE . 'modules/product_listing.php');
      } else {
        require('includes/modules/product_listing.php'); 
      }
      ?>
      <div id="searchResultsActions">
        <span class="buttonLeft"><a href="javascript: history.go(-1);" style="text-decoration:none;"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
    </div>
  </div>
</div>
<!--content/search/results.php end-->