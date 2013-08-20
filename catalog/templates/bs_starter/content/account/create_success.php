<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: create_success.php v1.0 2013-08-08 datazen $
*/
if ($lC_NavigationHistory->hasSnapshot()) {
  $origin_href = $lC_NavigationHistory->getSnapshotURL();
  $href_parts = explode("?", $origin_href);
  $lC_NavigationHistory->resetSnapshot();
} else {
  $origin_href = lc_href_link(FILENAME_DEFAULT);
}
?>
<!--content/account/create_success.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>        
    <div class="well">
      <p><?php echo sprintf($lC_Language->get('success_account_created'), lc_href_link(FILENAME_INFO, 'contact')); ?></p>
    </div>
    <div class="btn-set small-margin-top clearfix">
      <a href="<?php echo lc_href_link((($href_parts[1] == 'shipping=') ? FILENAME_CHECKOUT : FILENAME_DEFAULT), (($href_parts[1] == 'shipping=') ? $href_parts[1] : '')); ?>"><button class="pull-right btn btn-lg btn-primary" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a>
    </div>     
  </div>
</div>
<!--content/account/create_success.php end-->