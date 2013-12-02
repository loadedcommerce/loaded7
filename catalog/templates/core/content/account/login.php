<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: login.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/login.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php
      if (isset($_GET['success']) && $_GET['success'] != NULL) echo '<div class="message-success-container alert alert-success"><img class="margin-right" src="images/icons/success.gif">' . preg_replace('/[^a-zA-Z0-9]\'\.\,/', '', $_GET['success']) . '</div>' . "\n"; 
      if ( $lC_MessageStack->size('login') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">' . $lC_MessageStack->get('login') . '</div>' . "\n"; 
    ?>
    <div class="row">
      <div class="col-sm-6 col-lg-6 large-padding-left margin-top">
        <div class="well no-padding-top">
          <h3><?php echo $lC_Language->get('login_returning_customer_heading'); ?></h3>
          <form role="form" id="login" name="login" class="no-margin-bottom" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'login=process', 'SSL'); ?>" method="post">
            <div class="form-group">
              <label class="sr-only"></label><?php echo lc_draw_input_field('email_address', (($_POST['email']) ? $_POST['email'] : ($_SESSION['lC_Customer_data']['email_address']) ? $_SESSION['lC_Customer_data']['email_address'] : ''), 'class="form-control" placeholder="' . $lC_Language->get('field_customer_email_address') . '"'); ?>
            </div>
            <div class="form-group">
              <label class="sr-only"></label><?php echo lc_draw_password_field('password', 'class="form-control" placeholder="' . $lC_Language->get('field_customer_password') . '"'); ?>
              <p class="help-block small-margin-left"><?php echo sprintf($lC_Language->get('login_returning_customer_password_forgotten'), lc_href_link(FILENAME_ACCOUNT, 'password_forgotten', 'SSL')); ?></p>
            </div>
          </form>   
          <div class="button-set clearfix">
            <button class="pull-right btn btn-lg btn-primary" onclick="$('#login').submit();" type="button"><?php echo $lC_Language->get('button_sign_in'); ?></button>
          </div> 
        </div>
      </div>
      <div class="col-sm-6 col-lg-6 margin-top">
        <div class="well no-padding-top">
          <h3><?php echo $lC_Language->get('login_new_customer_heading'); ?></h3>
          <p><?php echo $lC_Language->get('login_new_customer_text'); ?></p>    
          <div class="buttons-set clearfix large-margin-top">
            <form class="form-inline" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create', 'SSL'); ?>" method="post"><button class="pull-right btn btn-lg btn-primary" type="button" onclick="$(this).closest('form').submit();"><?php echo $lC_Language->get('button_create_account'); ?></button></form>
          </div>        
        </div>
      </div>
    </div>
  </div>
</div>                          
<!--content/info/login.php end-->