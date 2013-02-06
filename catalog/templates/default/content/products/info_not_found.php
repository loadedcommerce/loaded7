<?php
/**  
*  $Id: info_not_found.php v1.0 2013-01-01 datazen $
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
<!--content/products/info_not_found.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <p><?php echo $lC_Language->get('product_not_found'); ?></p>
    </div>
    <div style="clear:both;">&nbsp;</div>
    <div id="productNotFoundActions" class="action_buttonbar">
      <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_DEFAULT); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_continue'); ?></button></a></span>
    </div>
  </div>
</div>
<!--content/products/info_not_found.php end-->