<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: address_book.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/account/address_book.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
    if ( $lC_MessageStack->size('address_book') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">' . $lC_MessageStack->get('address_book') . '</div>' . "\n"; 
    ?>
    <div class="row">
      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top"><?php echo $lC_Language->get('primary_address_title'); ?></h3>
        <div class="well">
          <address class="small-margin-bottom small-margin-top"><?php echo lC_Address::format($lC_Customer->getDefaultAddressID(), '<br />'); ?> </address>
        </div>
      </div>
      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top">&nbsp;</h3>
        <div class="well">
          <p><?php echo $lC_Language->get('primary_address_description'); ?></p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-lg-12">
        <h3 class="no-margin-top"><?php echo $lC_Language->get('address_book_title'); ?></h3>
        <?php
        $Qaddresses = lC_AddressBook::getListing();
        while ($Qaddresses->next()) {
          echo '<div class="well relative">' . "\n";
          echo '  <address class="no-margin-bottom">' . "\n";
          echo $Qaddresses->valueProtected('firstname') . ' ' . $Qaddresses->valueProtected('lastname'); 
          if ($Qaddresses->valueInt('address_book_id') == $lC_Customer->getDefaultAddressID()) {
            echo '  <small class="margin-left"><i>' . $lC_Language->get('primary_address_marker') . '</i></small>';
          }
          echo '  <br />' . "\n";
          echo lC_Address::format($Qaddresses->toArray(), '<br />'); 
          echo '  </address>' . "\n";
          ?>
          <div class="btn-group clearfix absolute-top-right-large-padding">
            <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $Qaddresses->valueInt('address_book_id') . '&edit', 'SSL'); ?>" class="display-inline" method="post"><button onclick="$(this).closest('form').submit();" type="button" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_edit'); ?></button></form>
            <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book=' . $Qaddresses->valueInt('address_book_id') . '&delete', 'SSL'); ?>" class="display-inline" method="post"><button onclick="$(this).closest('form').submit();" type="button" class="btn btn-default btn-xs"><?php echo $lC_Language->get('button_delete'); ?></button></form>
          </div>
          <?php
          echo '</div>' . "\n";
        }
        ?>            
      </div>
    </div>    
    <div class="row">
      <div class="col-sm-12 col-lg-12">
        <div class="btn-set small-margin-top clearfix">
          <?php
          if ($Qaddresses->numberOfRows() < MAX_ADDRESS_BOOK_ENTRIES) {
            echo '<form action="' . lc_href_link(FILENAME_ACCOUNT, 'address_book&new', 'SSL') . '" method="post"><button onclick="$(this).closest(\'form\').submit();" class="pull-right btn btn-lg btn-primary" type="submit">' . $lC_Language->get('button_add_address') . '</button></form>' . "\n";
          } else {
            echo '<div class="text-right">' . sprintf($lC_Language->get('address_book_maximum_entries'), MAX_ADDRESS_BOOK_ENTRIES) . '</div>' . "\n";
          }        
          ?>
          <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" method="post"><button class="pull-left btn btn-lg btn-default" onclick="$(this).closest('form').submit();" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
        </div>
      </div>
    </div>
  </div>
</div> 
<!--content/account/address_book.php end-->