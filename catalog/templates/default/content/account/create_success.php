<?php
/**  
*  $Id: create_success.php v1.0 2013-01-01 datazen $
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
if ($lC_NavigationHistory->hasSnapshot()) {
  $origin_href = $lC_NavigationHistory->getSnapshotURL();
  $lC_NavigationHistory->resetSnapshot();
} else {
  $origin_href = lc_href_link(FILENAME_DEFAULT);
}
?>
<!--content/account/create_success.php start-->
<div id="createAccountSuccess" class="full_page">
  <h1><?php echo $lC_Template->getPageTitle(); ?></h1>        
  <div class="short-code-column">
    <p><?php echo sprintf($lC_Language->get('success_account_created'), lc_href_link(FILENAME_INFO, 'contact')); ?></p>
    <div style="clear:both;">&nbsp;</div> 
    <div class="action_buttonbar">
      <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
    </div>
  </div>
</div>
<div style="clear:both;"></div> 
<!--content/account/create_success.php end-->