<?php
/*
  $Id: info_shipping.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--INFO SHIPPING SECTION STARTS-->
  <div id="infoShipping" class="full_page">
    <!--INFO SHIPPING DETAILS STARTS-->
    <div class="content">
      <!--INFO SHIPPING CONTENT STARTS-->
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <div><?php echo $lC_Language->get('text_information_shipping'); ?></div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <!--INFO SHIPPING CONTENT ENDS-->
      <!--INFO SHIPPING ACTIONS STARTS-->
      <div id="infoShippingActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
      <!--INFO SHIPPING ACTIONS ENDS-->
    </div>
  </div>
  <!--INFO SHIPPING DETAILS ENDS-->
<!--INFO SHIPPING SECTION ENDS-->