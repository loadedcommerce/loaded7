<?php
/*
  $Id: ssl_check.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--INFO SSL CHECK SECTION STARTS-->
  <div class="full_page">
    <!--INFO SSL CHECK CONTENT STARTS-->
    <div class="content">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <div class="contentBorder">
          <div style="width:40%; float:right; margin:0 0 3px 10px;"> 
            <div style="background:#FFFFFF; border:1px solid #DDDDDD; padding: 10px;"> 
              <div><b><?php echo $lC_Language->get('ssl_check_box_heading'); ?></b></div>
              <div><?php echo $lC_Language->get('ssl_check_box_contents'); ?></div>
            </div>
          </div>   
          <p><?php echo $lC_Language->get('text_information_ssl_check'); ?></p>
        </div>
        <div style="clear:both;">&nbsp;</div>
        <!--INFO SSL CHECK ACTIONS STARTS-->
        <div id="infoSslCheckActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
          <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
        <!--INFO SSL CHECK ACTIONS ENDS-->
      </div>
    </div>
    <!--INFO SSL CHECK CONTENT ENDS-->
  </div>
<!--INFO SSL CHECK SECTION ENDS-->