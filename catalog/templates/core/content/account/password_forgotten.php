<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: password_forgotten.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/account/password_forgotten.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php    
    if ( $lC_MessageStack->size('password_forgotten') > 0 ) echo '<div class="message-stack-container alert alert-danger">' . $lC_MessageStack->get('password_forgotten') . '</div>' . "\n";  
    ?>
    <div class="well">
      <p class="no-margin-bottom"><?php echo $lC_Language->get('password_forgotten'); ?></p>
      <form role="form" name="password_forgotten" id="password_forgotten" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'password_forgotten=process', 'SSL'); ?>" method="post" onsubmit="return check_form(password_forgotten);">
        <div class="form-group">
          <label></label><?php echo lc_draw_input_field('email_address', null, 'placeholder="' . $lC_Language->get('field_customer_email_address') . '" class="form-control"'); ?>
        </div>
      </form>
    </div>
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" onclick="$('#password_forgotten').submit();" type="button"><?php echo $lC_Language->get('button_continue'); ?></button>
      <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" method="post"><button class="pull-left btn btn-lg btn-default" onclick="$(this).closest('form').submit();" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div> 
  </div>
</div>
<!--content/account/password_forgotten.php end-->