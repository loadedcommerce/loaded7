<?php
/*
  $Id: reviews_not_found.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--REVIEWS NOT FOUND SECTION STARTS-->
  <div class="full_page">
    <!--REVIEWS NOT FOUND CONTENT STARTS-->
    <div class="content">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1> 
        <div>  
          <p><?php echo $lC_Language->get('no_reviews_available'); ?></p>
        </div>
        <div id="reviewsNotFoundActions">
          <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
      </div>
    </div>
    <!--REVIEWS NOT FOUND CONTENT ENDS-->
  </div>
<!--REVIEWS NOT FOUND SECTION ENDS-->
