<?php
/*
  $Id: info_privacy.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--INFO PRIVACY SECTION STARTS-->
  <div id="infoPrivacy" class="full_page">
    <!--INFO PRIVACY DETAILS STARTS-->
    <div class="content">
      <!--INFO PRIVACY CONTENT STARTS-->
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
        <div><?php echo $lC_Language->get('text_information_privacy'); ?></div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <!--INFO PRIVACY CONTENT ENDS-->
      <!--INFO PRIVACY ACTIONS STARTS-->
      <div id="infoPrivacyActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
      <!--INFO PRIVACY ACTIONS ENDS-->
    </div>
  </div>
  <!--INFO PRIVACY DETAILS ENDS-->
<!--INFO PRIVACY SECTION ENDS-->