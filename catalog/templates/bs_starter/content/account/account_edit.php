<?php
/**
  @package    catalog::templates::content
  @author     Loaded Commerce, LLC
  @copyright  Copyright 2003-2013 Loaded Commerce Development Team
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on DevKit http://www.bootstraptor.com under GPL license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: account_edit.php v1.0 2013-08-08 datazen $
*/
$Qaccount = lC_Account::getEntry();
?>
<!--content/info/account_edit.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
      if ( $lC_MessageStack->size('account_edit') > 0 ) echo '<div class="message-stack-container alert alert-error small-margin-bottom margin-left-neg">' . $lC_MessageStack->get('account_edit') . '</div>' . "\n"; 
    ?>
  </div>
  <div class="col-sm-12 col-lg-12">
    <form role="form" name="account_edit" id="account_edit" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'edit=save', 'SSL'); ?>" method="post" onsubmit="return check_form(account_edit);">
      <div class="row">
        <div class="col-sm-6 col-lg-6">
          <h3 class="small-margin-top"><?php echo $lC_Language->get('personal_details_title'); ?></h3>
          <div class="well clearfix">
            <div class="form-group">
              <label class="sr-only"></label><?php echo lc_draw_input_field('firstname', $Qaccount->value('customers_firstname') , 'class="form-control" placeholder="' . $lC_Language->get('field_customer_first_name') . '"'); ?>
            </div>
            <div class="form-group">
              <label class="sr-only"></label><?php echo lc_draw_input_field('lastname', $Qaccount->value('customers_lastname'), 'class="form-control" placeholder="' . $lC_Language->get('field_customer_last_name') . '"'); ?>
            </div>
            <?php
            if (ACCOUNT_DATE_OF_BIRTH == '1') {
              echo '<div class="form-group"><label class="sr-only"></label>' . lc_draw_input_field('dob', $Qaccount->value('customers_dob_month') . '/' . $Qaccount->value('customers_dob_date') . '/' . $Qaccount->value('customers_dob_year'),'class="datepicker form-control padding-left" data-date-format="mm/dd/yyyy" placeholder="' . $lC_Language->get('field_customer_date_of_birth') . '"') . '</div>' . "\n"; 
            } 
            if (ACCOUNT_GENDER > -1) {
              ?>
              <div class="form-group no-wrap margin-top">
                <label class="sr-only"></label>
                <select class="form-control" name="gender" id="gender">
                  <option value="m"><?php echo $lC_Language->get('gender_male'); ?></option>
                  <option value="f"><?php echo $lC_Language->get('gender_female'); ?></option>
                </select>
              </div>
              <?php              
            }
            ?>
          </div>
        </div>
        <div class="col-sm-6 col-lg-6">
          <h3 class="small-margin-top"><?php echo $lC_Language->get('login_details_title'); ?></h3>
          <div class="well clearfix">
            <div class="form-group no-margin-bottom">
              <label class="sr-only"></label><?php echo lc_draw_input_field('email_address', $Qaccount->value('customers_email_address'), 'class="form-control" placeholder="' . $lC_Language->get('field_customer_email_address') . '"'); ?>
            </div>
          </div>
        </div>
      </div>
    </form>         
    <div class="button-set clearfix large-margin-bottom">
      <button class="pull-right btn btn-lg btn-success" onclick="$('#account_edit').submit();" type="button"><?php echo $lC_Language->get('button_update'); ?></button>
      <form action="<?php echo lc_href_link(FILENAME_ACCOUNT, null, 'SSL'); ?>" method="post"><button onclick="$(this).closest('form').submit();" class="pull-left btn btn-lg btn-default" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div>     
  </div>
</div>
<script>
$(document).ready(function() {
  $('.datepicker').datepicker();
});
</script>
<!--content/account/account_edit.php end-->