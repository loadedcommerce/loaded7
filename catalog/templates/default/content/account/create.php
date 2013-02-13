<?php
/**  
*  $Id: create.php v1.0 2013-01-01 datazen $
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
if ($lC_MessageStack->size('create') > 0) {
  echo '<br /><div class="short-code msg error"><span>' . $lC_MessageStack->get('create', DIR_WS_TEMAPLTE_IMAGES . 'shortcodes/', '.png') . '</span></div>';
}
?>
<style>
#personal_details li { margin: 10px 0; }
#login_details li { margin: 10px 0; }
</style>
<!--content/account/create.php start-->
<div id="accountCreate" class="full_page">
  <h1><?php echo $lC_Template->getPageTitle(); ?></h1>        
  <form name="create" id="create" action="<?php echo lc_href_link(FILENAME_ACCOUNT, 'create=save', 'SSL'); ?>" method="post">
  <div id="errDiv" class="short-code msg error" style="margin-bottom:10px; display:none;">
    <span><?php echo $lC_Language->get('form_validation_error'); ?></span>
  </div>   
  <div class="single-bg">
    <div class="short-code-column one-half no-margin-bottom lg-input-height">   
      <h3>Personal Details</h3>
      <ul id="personal_details">
        <li><?php echo lc_draw_label('', 'firstname', null, false) . ' ' . lc_draw_input_field('firstname', null, 'placeholder="' . $lC_Language->get('field_customer_first_name') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_first_name') . '\'" holder="' . $lC_Language->get('field_customer_first_name') . '" class="txt" style="width:99%;"'); ?></li>
        <li><?php echo lc_draw_label('', 'lastname', null, false) . ' ' . lc_draw_input_field('lastname', null, 'placeholder="' . $lC_Language->get('field_customer_last_name') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_last_name') . '\'" holder="' . $lC_Language->get('field_customer_last_name') . '" class="txt" style="width:99%;"'); ?></li>
        <?php
        if (ACCOUNT_DATE_OF_BIRTH == '1') {
          //echo '<li>' . lc_draw_label($lC_Language->get('field_customer_date_of_birth'), 'dob_days', null, true) . lc_draw_date_pull_down_menu('dob', null, false, null, null, @date('Y')-1901, -5) . '</li>';
          echo '<li>' . lc_draw_label('', 'dob_days', null, false) . ' ' . lc_draw_input_field('dob', null, 'placeholder="' . $lC_Language->get('field_customer_date_of_birth') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_date_of_birth') . '\'" holder="' . $lC_Language->get('field_customer_date_of_birth') . '" class="txt required date" style="width:86%;"') . '</li>'; 
        }
        if (ACCOUNT_GENDER > -1) {
          $gender_array = array(array('id' => 'm', 'text' => $lC_Language->get('gender_male')),
                                array('id' => 'f', 'text' => $lC_Language->get('gender_female')));   
          echo '<li style="font-size:.9em; margin-left:3px;">' . lc_draw_label('', 'gender', null, false) . ' ' . lc_draw_radio_field('gender', $gender_array, 'm', 'style="height:12px;"') . '</li>'; 
        }
        if (ACCOUNT_NEWSLETTER == '1') {
          echo '<li style="font-size:.9em; margin-left:5px;">' . lc_draw_label($lC_Language->get('field_customer_newsletter'), 'newsletter') . '&nbsp;&nbsp;' . lc_draw_checkbox_field('newsletter', '1', '1', ($_POST['email']) ? 'checked="checked" style="height:12px;"' : 'style="height:12px;"') . '</li>';
        }
        ?>      
      </ul>     
    </div>
    <div class="short-code-column one-half column-last no-margin-bottom lg-input-height">
      <h3>Login Details</h3>
      <ul id="login_details">    
        <li><?php echo lc_draw_label('', 'email_address', '', false) . ' ' . lc_draw_input_field('email_address', ($_POST['email']) ? $_POST['email'] : '', 'placeholder="' . $lC_Language->get('field_customer_email_address') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_email_address') . '\'" holder="' . $lC_Language->get('field_customer_email_address') . '" class="txt" style="width:100%;"'); ?></li>
        <li><?php echo lc_draw_label('', 'password', null, false) . ' ' . lc_draw_password_field('password', 'onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_password') . '\'" class="txt" style="width:100%;" placeholder="' . $lC_Language->get('field_customer_password') . '" holder="' . $lC_Language->get('field_customer_password') . '"'); ?></li>
        <li><?php echo lc_draw_label('', 'confirmation', null, false) . ' ' . lc_draw_password_field('confirmation', 'placeholder="' .  $lC_Language->get('field_customer_password_confirmation') . '" onfocus="this.placeholder = \'\'" onblur="this.placeholder = \'' . $lC_Language->get('field_customer_password_confirmation') . '\'" holder="' . $lC_Language->get('field_customer_password_confirmation') . '" class="txt" style="width:100%;"'); ?></li>
        <?php
          if (DISPLAY_PRIVACY_CONDITIONS == '1') {
            echo '<div style="margin-top:20px; font-size:.9em;"><ol style="list-style:none;"><li> ' . lc_draw_checkbox_field('privacy_conditions', array(array('id' => 1, 'text' => sprintf($lC_Language->get('create_account_terms_confirm'), lc_href_link(FILENAME_INFO, 'privacy', 'AUTO')))), null, 'style="height:12px; margin-right:2px;"') . '</li></ol></div>';
          }
        ?>        
      </ul>
    </div>
  </div>
  <div class="action_buttonbar clear">
    <span class="buttonLeft"><a href="<?php echo lc_href_link(FILENAME_ACCOUNT, 'login'); ?>" class="noDecoration"><button class="button brown_btn" type="button"><?php echo $lC_Language->get('button_back'); ?></button></a></span>
    <span class="buttonRight"><button class="button purple_btn" type="submit"><?php echo $lC_Language->get('button_signup'); ?></button></span>
  </div>    
  </form>
</div>
<script>
$('#create').submit(function() {
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
  var emailMin = '<?php echo ACCOUNT_EMAIL_ADDRESS; ?>';
  var pwMin = '<?php echo ACCOUNT_PASSWORD; ?>';  
  jQuery.validator.messages.required = "";
  var bValid = $("#create").validate({
    rules: {
      firstname: { minlength: fnameMin, required: true },
      lastname: { minlength: lnameMin, required: true },
      email_address: { minlength: emailMin, email: true, required: true },
      password: { minlength: pwMin, required: true },
      confirmation: { minlength: pwMin, required: true },
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
  var passwd = $('#password').val();
  var confirm = $('#confirmation').val();
  if (passwd != confirm) {
    alert('<?php echo $lC_Language->get('field_customer_password_mismatch_with_confirmation'); ?>');
    return false;
  }
  if (bValid) {  
    var displayConditions  = '<?php echo DISPLAY_PRIVACY_CONDITIONS; ?>';
    var isChecked = $('#privacy_conditions').is(':checked');
    if (displayConditions == 1 && isChecked == false) {
      alert('<?php echo $lC_Language->get('create_account_terms_description'); ?>');
      return false;
    }     
    $('#create').submit();
  }
  return false;
});
</script>
<!--content/account/create.php end-->