<?php
/**  
*  $Id: ssl_check.php v1.0 2013-01-01 datazen $
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
<!--content/info/ssl_check.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <div class="contentBorder">
        <div class="single-bg" style="width:40%; margin-right:15px; padding:15px;">
          <b><?php echo $lC_Language->get('ssl_check_box_heading'); ?></b><br />
          <?php echo $lC_Language->get('ssl_check_box_contents'); ?>
        </div>
        <div>
          <?php echo $lC_Language->get('text_information_ssl_check'); ?>
        </div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="infoSslCheckActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_INFO); ?>"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
    </div>
  </div>
</div>
<!--content/info/ssl_check.php end-->