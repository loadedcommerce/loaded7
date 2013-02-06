<?php
/**  
*  $Id: cookie.php v1.0 2013-01-01 datazen $
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
<!--content/info/cookie.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <div class="contentBorder">
        <div style="width:40%; float:right; margin:0 0 3px 10px;"> 
          <div style="background:#FFFFFF; border:1px solid #DDDDDD; padding: 10px;"> 
            <div><b><?php echo $lC_Language->get('cookie_usage_box_heading'); ?></b></div>
            <div><?php echo $lC_Language->get('cookie_usage_box_contents'); ?></div>
          </div>
        </div>   
        <p><?php echo $lC_Language->get('text_information_cookie_usage'); ?></p>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="infoConditionsActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
    </div>
  </div>
</div>
<!--content/info/cookie.php end-->