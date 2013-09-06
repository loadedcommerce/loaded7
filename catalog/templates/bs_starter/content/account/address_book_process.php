<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: address_book_process.php v1.0 2013-08-08 datazen $
*/
if (isset($_GET['edit'])) {
  $Qentry = lC_AddressBook::getEntry($_GET['address_book']);
} else {
  if (lC_AddressBook::numberOfEntries() >= MAX_ADDRESS_BOOK_ENTRIES) {
    $lC_MessageStack->add('address_book', $lC_Language->get('error_address_book_full'));
  }
}  
?>
<!--content/account/address_book_process.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
      if ( $lC_MessageStack->size('address_book') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">' . $lC_MessageStack->get('address_book') . '</div>' . "\n"; 
      if ( ($lC_Customer->hasDefaultAddress() === false) || (isset($_GET['new']) && (lC_AddressBook::numberOfEntries() < MAX_ADDRESS_BOOK_ENTRIES)) || (isset($Qentry) && ($Qentry->numberOfRows() === 1)) ) {
        ?>
        <div class="row">
          <form role="form" class="form-inline" name="address_book" id="address_book" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $_GET['address_book'] . '&' . (isset($_GET['edit']) ? 'edit' : 'new') . '=save', 'SSL'); ?>" method="post" onsubmit="return check_form(address_book);">
            <?php
              if (file_exists(DIR_FS_TEMPLATE . 'modules/address_book_details.php')) {
                require($lC_Vqmod->modCheck(DIR_FS_TEMPLATE . 'modules/address_book_details.php'));
              } else {
                require($lC_Vqmod->modCheck('includes/modules/address_book_details.php'));
              }
            ?>
          </form>
        </div>
        <div class="btn-set small-margin-top clearfix">
          <button class="pull-right btn btn-lg btn-primary" onclick="$('#address_book').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
          <form action="<?php echo ($lC_NavigationHistory->hasSnapshot()) ? $lC_NavigationHistory->getSnapshotURL() : ($lC_Customer->hasDefaultAddress() === false) ? lc_href_link(FILENAME_ACCOUNT, null, 'SSL') : lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-left btn btn-lg btn-default" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
        </div> 
        <?php
      } else {
        ?>
        <div class="btn-set small-margin-top clearfix">
          <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-left btn btn-lg btn-default" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
        </div> 
        <?php
      }
      ?>
  </div>
</div>  
<!--content/account/address_book_process.php end-->