<?php
  /*
  $Id: address_book_process.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
  */
  if (isset($_GET['edit'])) {
    $Qentry = lC_AddressBook::getEntry($_GET['address_book']);
  } else {
    if (lC_AddressBook::numberOfEntries() >= MAX_ADDRESS_BOOK_ENTRIES) {
      $lC_MessageStack->add('address_book', $lC_Language->get('error_address_book_full'));
    }
  }
  if ($lC_MessageStack->size('address_book') > 0) {
    echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('address_book', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
  }
?>
<!--ADDRESS BOOK PROCESS SECTION STARTS-->
<div id="addressBookProcess" class="full_page">
  <div class="content">
    <div class="short-code-column">
      <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
      <?php
      if ( ($lC_Customer->hasDefaultAddress() === false) || (isset($_GET['new']) && (lC_AddressBook::numberOfEntries() < MAX_ADDRESS_BOOK_ENTRIES)) || (isset($Qentry) && ($Qentry->numberOfRows() === 1)) ) {
        ?>
        <form name="address_book" id="address_book" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $_GET['address_book'] . '&' . (isset($_GET['edit']) ? 'edit' : 'new') . '=save', 'SSL'); ?>" method="post">
          <?php
          if (file_exists(DIR_FS_TEMPLATE . 'modules/address_book_details.php')) {
            require(DIR_FS_TEMPLATE . 'modules/address_book_details.php');
          } else {
            require('includes/modules/address_book_details.php');
          }
          ?>
          
          <div style="clear:both;">&nbsp;</div>
          <!--ADDRESS BOOK PROCESS ACTIONS STARTS-->       
          <div id="addressBookProcessActions" class="action_buttonbar">
            <?php
              if ($lC_NavigationHistory->hasSnapshot()) {
                $back_link = $lC_NavigationHistory->getSnapshotURL();
              } elseif ($lC_Customer->hasDefaultAddress() === false) {
                $back_link = lc_href_link(FILENAME_ACCOUNT, null, 'SSL');
              } else {
                $back_link = lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL');
              }
            ?>
            <span class="buttonLeft"><a href="<?php echo $back_link; ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
            <span class="buttonRight"><button class="button purple_btn" type="submit"><?php echo $lC_Language->get('button_continue'); ?></button></span>
            <div style="clear:both;"></div> 
          </div>
          <!--ADDRESS BOOK PROCESS ACTIONS ENDS-->
        </form>
        <?php
      } else {
        ?>
        <!--ADDRESS BOOK PROCESS ACTIONS STARTS-->
        <div id="addressBookProcessActions" class="action_buttonbar">
          <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
        </div>        
        <div style="clear:both;"></div>
        <!--ADDRESS BOOK PROCESS ACTIONS ENDS--> 
        <?php
      }
      ?>
    </div>
  </div>
  <!--ADDRESS BOOK PROCESS CONTENT ENDS-->
  </div>
<!--ADDRESS BOOK PROCESS SECTION ENDS-->
<script>
$('#address_book').submit(function() {
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
  jQuery.validator.messages.required = "";
  var bValid = $("#address_book").validate({
    rules: {
      firstname: { minlength: fnameMin, required: true },
      lastname: { minlength: lnameMin, required: true },
    },
    invalidHandler: function(e, validator) {
      var errors = validator.numberOfInvalids();
      if (errors) {
        $("#errDiv").show().delay(5000).fadeOut('slow');
      } else {
        $("#errDiv").hide();
      }
      return false;
    }
  }).form();

  if (bValid) {      
    $('#address_book').submit();
  }
  return false;
});
</script>