<?php
/*
  $Id: logoff.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--ACCOUNT LOGOFF SECTION STARTS-->
  <div class="full_page">
    <!--ACCOUNT LOGOFF CONTENT STARTS-->
    <div class="content">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <div>
          <div>
            <p><?php echo $lC_Language->get('sign_out_text'); ?></p>
          </div>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <!--ACCOUNT LOGOFF ACTIONS STARTS-->  
        <div id="logoffActions" class="action_buttonbar">
          <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div> 
        <div style="clear:both;"></div>
        <!--ACCOUNT LOGOFF ACTIONS ENDS-->     
      </div>
    </div>
    <!--ACCOUNT LOGOFF CONTENT ENDS-->
  </div>
<!--ACCOUNT LOGOFF SECTION ENDS-->