<?php
/**  
*  $Id: info_shipping.php v1.0 2013-01-01 datazen $
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
<!--content/info/info_shipping.php start-->
<div id="infoShipping" class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <div><?php echo $lC_Language->get('text_information_shipping'); ?></div>
    </div>
    <div style="clear:both;">&nbsp;</div>
    <div id="infoShippingActions" class="action_buttonbar">
      <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
      <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
    </div>
    <div style="clear:both;"></div>
  </div>
</div>
<!--content/info/info_shipping.php end-->