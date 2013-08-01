<?php
/**  
*  $Id: address_book.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('address_book') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('address_book', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<!--content/account/address_book.php start-->
<div class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <p><b><?php echo $lC_Language->get('primary_address_title'); ?></b></p>
      <div class="borderPadMe">
        <div class="short-code-column one-third margin-bottom"> <?php echo lC_Address::format($lC_Customer->getDefaultAddressID(), '<br />'); ?> </div>
        <div class="short-code-column two-third column-last no-margin-bottom "> <?php echo $lC_Language->get('primary_address_description'); ?> </div>
        <div style="clear: both;"></div>
      </div>
      <div style="clear: both;">&nbsp;</div>
      <p><b><?php echo $lC_Language->get('address_book_title'); ?></b></p>
      <div class="borderPadMe">
        <div>
          <?php
            $Qaddresses = lC_AddressBook::getListing();
            while ($Qaddresses->next()) {
            ?>
            <div class="short-code-column two-third margin-bottom"> <b><?php echo $Qaddresses->valueProtected('firstname') . ' ' . $Qaddresses->valueProtected('lastname'); ?></b>
              <?php
                if ($Qaddresses->valueInt('address_book_id') == $lC_Customer->getDefaultAddressID()) {
                  echo '&nbsp;<small><i>' . $lC_Language->get('primary_address_marker') . '</i></small>';
                }
              ?>
              <br />
            <?php echo lC_Address::format($Qaddresses->toArray(), '<br />'); ?> </div>
            <div class="short-code-column one-third column-last no-margin-bottom ">
              <?php 
                echo ' ' . 
                '  <a href="' . lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $Qaddresses->valueInt('address_book_id') . '&edit', 'SSL')  . '" style="text-decoration:none;">' . 
                '    <button class="button brown_btn" type="button">' . $lC_Language->get('button_edit') . '</button>' . 
                '  </a>' .
                '  <a href="' . lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $Qaddresses->valueInt('address_book_id') . '&delete', 'SSL')  . '" style="text-decoration:none;">' . 
                '    <button class="button brown_btn" type="button">' . $lC_Language->get('button_delete') . '</button>' . 
                '  </a>' . 
                ' ';
              ?>
            </div>
            <div style="clear: both;"></div>
            <div class="five-sixth margin-bottom"></div>
            <div style="clear: both;"></div>
            <?php
            }
          ?>
        </div>
      </div>
      <div style="clear: both;">&nbsp;</div>
      <div id ="addressBookActions" class="action_buttonbar"> <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="noDecoration">
            <div class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></div>
          </a></span>
        <?php
          if ($Qaddresses->numberOfRows() < MAX_ADDRESS_BOOK_ENTRIES) {
          ?>
          <span class="buttonRight"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book&new', 'SSL'); ?>" class="noDecoration">
              <button class="button purple_btn" type="submit"><?php echo $lC_Language->get('button_add_address'); ?></button>
            </a></span>
          <?php
          } else {
            echo sprintf($lC_Language->get('address_book_maximum_entries'), MAX_ADDRESS_BOOK_ENTRIES);
          }
        ?>
      </div>
      <div style="clear:both;"></div>
    </div>
  </div>
</div>
<!--content/account/address_book.php end-->