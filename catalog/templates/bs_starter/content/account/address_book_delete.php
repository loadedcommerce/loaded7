<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: address_book_delete.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/account/address_book_delete.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
      if ( $lC_MessageStack->size('create') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">' . $lC_MessageStack->get('create') . '</div>' . "\n"; 
    ?>
    <div class="row">
      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top"><?php echo $lC_Language->get('selected_address_title'); ?></h3>
        <div class="well">
          <address class="small-margin-bottom no-margin-top"><?php echo lC_Address::format(preg_replace('/[^0-9]/', '', $_GET['address_book']), '<br />'); ?> </address>
        </div>
      </div>

      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top"><?php echo $lC_Language->get('address_book_delete_address_title'); ?></h3>
        <div class="well">
          <p><?php echo $lC_Language->get('address_book_delete_address_description'); ?></p>
        </div>
      </div>
    </div>
    <div class="btn-set small-margin-top clearfix">
      <a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book=' . preg_replace('/[^0-9]/', '', $_GET['address_book']) . '&delete=confirm', 'SSL'); ?>"><button class="pull-right btn btn-lg btn-primary" type="button"><?php echo $lC_Language->get('button_delete'); ?></button></a>
      <a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'address_book', 'SSL'); ?>"><button class="pull-left btn btn-lg btn-primary" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a>
    </div>     
  </div>
</div>  
<!--content/account/address_book_delete.php end-->