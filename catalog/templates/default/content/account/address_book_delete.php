<?php
/*
  $Id: address_book_delete.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$Qentry = lC_AddressBook::getEntry($_GET['address_book']);
?>
<!--ADDRESS BOOK DELETE SECTION STARTS-->
  <div class="full_page">
    <!--ADDRESS BOOK DELETE CONTENT STARTS-->
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
        <!--ADDRESS BOOK DELETE ACTIONS STARTS-->    
        <div id="addressBookDeleteActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
          <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $_GET['address_book'] . '&delete=confirm', 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_delete'); ?></button></a></span>
        </div>
        <div style="clear:both;"></div>
        <!--ADDRESS BOOK DELETE ACTIONS ENDS-->
      </div>
    </div>
    <!--ADDRESS BOOK DELETE CONTENT ENDS-->
  </div>  
<!--ADDRESS BOOK DELETE SECTION ENDS-->