<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: create.php v1.0 2013-08-08 datazen $
*/
?>
<!--content/info/create.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
      if ( $lC_MessageStack->size('create') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">' . $lC_MessageStack->get('create') . '</div>' . "\n"; 
    ?>
    <div class="row">
      <form role="form" class="form-inline" name="create" id="create" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create=save', 'SSL'); ?>" method="post" onsubmit="return check_form(create);">
        <div class="col-sm-6 col-lg-6 large-padding-left margin-top">
          <div class="well no-padding-top">
            <h3><?php echo $lC_Language->get('personal_details_title'); ?></h3>
              <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo lc_draw_input_field('firstname', null, 'placeholder="' . $lC_Language->get('field_customer_first_name') . '" class="form-control"'); ?></div>
              <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo lc_draw_input_field('lastname', null, 'placeholder="' . $lC_Language->get('field_customer_last_name') . '" class="form-control"'); ?></div>
              <?php
              if (ACCOUNT_DATE_OF_BIRTH == '1') {
                echo '<div class="form-group full-width"><label class="sr-only"></label>' . lc_draw_input_field('dob', null,'class="datepicker form-control full-width padding-left" data-date-format="mm/dd/yyyy" placeholder="' . $lC_Language->get('field_customer_date_of_birth') . '"') . '</div>' . "\n"; 
              }
              if (ACCOUNT_GENDER > -1) {
                ?>
                <div class="form-group full-width margin-top">
                  <label class="sr-only"></label>
                  <select class="form-control" name="gender" id="gender">
                    <option value="m"><?php echo $lC_Language->get('gender_male'); ?></option>
                    <option value="f"><?php echo $lC_Language->get('gender_female'); ?></option>
                  </select>
                </div>
                <?php
              }
              if (ACCOUNT_NEWSLETTER == '1') {  
                ?>
                <br />
                <div class="checkbox margin-top">
                  <label class="normal"><?php echo $lC_Language->get('field_customer_newsletter'); ?><input id="newsletter" class="small-margin-left" type="checkbox" checked="checked" value="1" name="newsletter"></label>
                </div>                
                <?php
              }
              ?>      
          </div>
        </div>
        <div class="col-sm-6 col-lg-6 margin-top clearfix">
          <div class="well no-padding-top">
            <h3><?php echo $lC_Language->get('login_details_title'); ?></h3>
            <div class="form-group full-width margin-bottom"><?php echo lc_draw_input_field('email_address', ($_POST['email']) ? $_POST['email'] : '', 'placeholder="' . $lC_Language->get('field_customer_email_address') . '" class="form-control"'); ?></div>
            <div class="form-group full-width margin-bottom"><?php echo lc_draw_password_field('password', 'class="form-control" placeholder="' . $lC_Language->get('field_customer_password') . '"'); ?></div>
            <div class="form-group full-width margin-bottom"><?php echo lc_draw_password_field('confirmation', 'placeholder="' .  $lC_Language->get('field_customer_password_confirmation') . '" class="form-control"'); ?></div>
            <?php
            if (DISPLAY_PRIVACY_CONDITIONS == '1') {
              echo '<div class="form-group no-wrap">' . lc_draw_checkbox_field('privacy_conditions', null, null, 'class="form-control no-margin-top small-margin-right" style="width:5%;"') . '<label style="font-weight:200;">' . sprintf($lC_Language->get('create_account_terms_confirm'), lc_href_link(FILENAME_INFO, 'privacy', 'AUTO')) . '</label></div>';
            }
            ?>
          </div>
        </div>
      </form>
    </div>
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" onclick="$('#create').submit();" type="button"><?php echo $lC_Language->get('button_signup'); ?></button>
      <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'AUTO'); ?>" method="post"><button class="pull-left btn btn-lg btn-default" onclick="$(this).closest('form').submit();" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div> 
    <hr>
  </div>
</div>    
<script>
$(document).ready(function() {
  $('.datepicker').datepicker();
});
</script>                       
<!--content/info/create.php end-->