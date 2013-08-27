<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: category_not_found.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/index/category_not_found.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Language->get('text_not_found'); ?></h1>
    <div class="strong"><?php echo $lC_Language->get('text_category_not_found'); ?></div>
    <div class="button-set">
      <form action="<?php echo lc_href_link(FILENAME_DEFAULT); ?>"><button class="pull-right btn btn-lg btn-primary" onclick="$(this).closest('form').submit();" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></form>
    </div>  
  </div>  
</div>
<!--content/index/category_not_found.php end-->