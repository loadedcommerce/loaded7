<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account_password.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/account/account_password.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
      if ( $lC_MessageStack->size('account_password') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom">' . $lC_MessageStack->get('account_password') . '</div>' . "\n"; 
    ?>
    <div class="well">
      <form role="form" name="account_password" id="account_password" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password=save', 'SSL'); ?>" method="post" onsubmit="return check_form(account_password);">
        <div class=""><label class="sr-only"></label><?php echo lc_draw_password_field('password_current', 'placeholder="' . $lC_Language->get('field_customer_password_current') . '" class="form-control"'); ?></div>
        <div class="margin-top"><label class="sr-only"></label><?php echo lc_draw_password_field('password_new', 'placeholder="' . $lC_Language->get('field_customer_password_new') . '" class="form-control"'); ?></div>
        <div class="margin-top"><label class="sr-only"></label><?php echo lc_draw_password_field('password_confirmation', 'placeholder="' . $lC_Language->get('field_customer_password_confirmation') . '" class="form-control"'); ?></div>
      </form>
    </div>
    
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" onclick="$('#account_password').submit();" type="button"><?php echo $lC_Language->get('button_update'); ?></button>
      <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" method="post"><button class="pull-left btn btn-lg btn-default" onclick="$(this).closest('form').submit();" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div>     
    
  </div>
</div> 
<!--content/account/account_password.php end-->