<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: cookie.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/permissions.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top margin-bottom"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <div class="large-margin-right"><?php echo $lC_Language->get('info_permissions_text'); ?></div>
    <form action="<?php echo lc_href_link(FILENAME_DEFAULT, null, 'AUTO'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-right btn btn-lg btn-primary" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></form>
  </div>    
</div>
<!--content/info/permissions.php end-->