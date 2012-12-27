<?php
/*
  $Id: results.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--SEARCH RESULTS SECTION STARTS-->
  <div id="searchResults" class="full_page">
    <!--SEARCH RESULTS CONTENT STARTS-->
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
        <!--SEARCH RESULTS ACTIONS STARTS-->
        <div id="searchResultsActions">
          <span class="buttonLeft"><a href="javascript: history.go(-1);" style="text-decoration:none;"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        </div>
        <!--SEARCH RESULTS ACTIONS ENDS-->
        <div style="clear:both;"></div>
      </div>
    </div>
    <!--SEARCH RESULTS CONTENT ENDS-->
  </div>
<!--SEARCH RESULTS SECTION ENDS-->