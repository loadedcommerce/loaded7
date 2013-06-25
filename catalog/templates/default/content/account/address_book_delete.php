<?php
/**  
*  $Id: address_book_delete.php v1.0 2013-01-01 datazen $
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
$Qentry = lC_AddressBook::getEntry($_GET['address_book']);
?>
<!--content/account/address_book_delete.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <p><b><?php echo $lC_Language->get('address_book_delete_address_title'); ?></b></p>
      <div class="borderPadMe">  
        <div style="float: right; padding: 0px 0px 10px 20px;">
          <?php echo lC_Address::format($_GET['address_book'], '<br />'); ?>
        </div>
        <div style="float: right; padding: 0px 0px 10px 20px; text-align: center;">
          <?php echo '<b>' . $lC_Language->get('selected_address_title') . '</b><br />' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'arrow_south_east.png'); ?>
        </div>
        <?php echo $lC_Language->get('address_book_delete_address_description'); ?>
        <div style="clear: both;"></div>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <div id="addressBookDeleteActions" class="action_buttonbar">
        <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>" class="noDecoration"><div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div></a></span>
        <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $_GET['address_book'] . '&delete=confirm', 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_delete'); ?></button></a></span>
      </div>
      <div style="clear:both;"></div>
    </div>
  </div>
</div>  
<!--content/account/address_book_delete.php end-->