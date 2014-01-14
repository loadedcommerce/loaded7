<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: new.php v1.0 2013-08-08 datazen $
*/
$content = '<div id="newCustomer">' .
           '  <div id="newCustomerForm">' .
           '    <form name="customers" id="customers" autocomplete="off" action="" method="post">';
if ( ACCOUNT_GENDER > -1 ) {
  $content .= '<p>' . $lC_Language->get('introduction_new_customer') . '</p>' .
              '<p class="button-height inline-label">' .
              '  <label for="gender" class="label" style="width:30%;">' . $lC_Language->get('field_gender') . '</label>' .
              '  <span class="button-group">' .
              '    <label for="gender-1" class="button green-active">' .
              '    <input type="radio" name="gender" id="gender-1" value="m" checked>' . $lC_Language->get('gender_male') . '</label>' .
              '    <label for="gender-2" class="button green-active">' .
              '    <input type="radio" name="gender" id="gender-2" value="f">' . $lC_Language->get('gender_female') . '</label>' .
              '  </span>' .
              '</p>';
}
$content .=  '<p class="button-height inline-label">' .
             '  <label for="firstname" class="label" style="width:30%;">' . $lC_Language->get('field_first_name') . '</label>' .
                lc_draw_input_field('firstname', null, 'class="input" style="width:90%;"') .
             '</p>' .
             '<p class="button-height inline-label">' .
             '  <label for="lastname" class="label" style="width:30%;">' . $lC_Language->get('field_last_name') . '</label>' .
                lc_draw_input_field('lastname', null, 'class="input" style="width:90%;"') .
             '</p>';
if ( ACCOUNT_DATE_OF_BIRTH == '1' ) {
  $content .=  '<p class="button-height inline-label">' .
               '  <label for="dob" class="label" style="width:30%;">' . $lC_Language->get('field_date_of_birth') . '</label>' .
               '  <span class="input">' .
               '    <span class="icon-calendar"></span>' .
                    lc_draw_input_field('dob', null, 'class="input-unstyled datepicker" style="width:90%;"') .
               '  </span>' .
               '</p>';
}
$content .=  '<p class="button-height inline-label">' .
             '  <label for="email_address" class="label" style="width:30%;">' . $lC_Language->get('field_email_address') . '</label>' .
                lc_draw_input_field('email_address', null, 'class="input" style="width:90%;"') .
             '</p>';
if ( ACCOUNT_NEWSLETTER == '1' ) {
  $content .= '<p class="button-height inline-label">' .
              '  <label for="newsletter" class="label" style="width:30%;">' . $lC_Language->get('field_newsletter_subscription') . '</label>' .
                 lc_draw_checkbox_field('newsletter', '1', true, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"') .
              '</p>';
}
$content .= '<p class="button-height inline-label">' .
            '  <label for="password" class="label" style="width:30%;">' . $lC_Language->get('field_password') . '</label>' .
               lc_draw_password_field('password', 'class="input" style="width:90%;"') .
            '</p>' .
            '<p class="button-height inline-label">' .
            '  <label for="confirmation" class="label" style="width:30%;">' . $lC_Language->get('field_password_confirmation') . '</label>' .
               lc_draw_password_field('confirmation', 'class="input" style="width:90%;"') .
            '</p>' .
            '<p class="button-height inline-label">' .
            '  <label for="group" class="label" style="width:30%;">' . $lC_Language->get('field_customer_group') . '</label>' .
               lc_draw_pull_down_menu('group', null, null, 'class="input with-small-padding" style="width:73%;"') .
            '</p>' .
            '<p class="button-height inline-label">' .
              '  <label for="status" class="label" style="width:30%;">' . $lC_Language->get('field_status') . '</label>' .
                 lc_draw_checkbox_field('status', '1', true, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"') .
            '</p>';
$content .= '</form></div></div>';
?>
<style>
#newCustomer { padding-bottom:20px; }
</style>
<script>
function newCustomer() {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 2) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCustomerFormData'); ?>';
  $.getJSON(jsonLink,
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $.modal({
          content: '<?php echo $content; ?>',
          title: '<?php echo $lC_Language->get('modal_heading_new_customer'); ?>',
          width: 600,
          actions: {
            'Close' : {
              color: 'red',
              click: function(win) { win.closeModal(); }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_cancel'); ?>': {
              classes:  'glossy',
              click:    function(win) { win.closeModal(); }
            },
            '<?php echo $lC_Language->get('button_save_and_close'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
                var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
                var emailMin = '<?php echo ACCOUNT_EMAIL_ADDRESS; ?>';
                var pwMin = '<?php echo ACCOUNT_PASSWORD; ?>';
                var bValid = $("#customers").validate({
                  rules: {
                    firstname: { minlength: fnameMin, required: true },
                    lastname: { minlength: lnameMin, required: true },
                    email_address: { minlength: emailMin, email: true, required: true },
                    dob: { date: true },
                    password: { minlength: pwMin, required: true },
                    confirmation: { minlength: pwMin, required: true },
                  },
                  invalidHandler: function() {
                  }
                }).form();
                var passwd = $('#password').val();
                var confirm = $('#confirmation').val();
                if (passwd != confirm) {
                  $.modal.alert('<?php echo $lC_Language->get('ms_error_password_confirmation_invalid'); ?>');
                  return false;
                }
                if (bValid) {
                  var nvp = $('#customers').serialize();
                  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveCustomer&BATCH'); ?>'
                  $.getJSON(jsonLink.replace('BATCH', nvp),
                    function (data) {
                      if (data.rpcStatus == -10) { // no session
                        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
                        $(location).attr('href',url);
                      }
                      if (data.rpcStatus != 1) {
                        if (data.rpcStatus == -1) {
                          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
                        } else if (data.rpcStatus == -2) {
                          $.modal.alert('<?php echo $lC_Language->get('ms_error_email_address_exists'); ?>');
                        } else if (data.rpcStatus == -3) {
                          $.modal.alert('<?php echo $lC_Language->get('ms_error_password_confirmation_invalid'); ?>');
                        }
                        return false;
                      }
                      oTable.fnReloadAjax();
                    }
                  );
                  win.closeModal();
                }
              }
            },
            '<?php echo $lC_Language->get('button_continue'); ?>': {
              classes:  'green-gradient glossy with-tooltip',
              click:    function(win) { addNewCustomer(); }
            }
          },
          buttonsLowPadding: true
      });
      var cont = document.getElementsByClassName('with-tooltip');
      $(cont).attr("title", "<?php echo $lC_Language->get('button_continue_title_tag'); ?>");
      $("#group").html("");
      i=0;
      $.each(data.groupsArray, function(val, text) {
        if(i == 0) {
          $("#group").closest("span + *").prevAll("span.select-value:first").text(text);
          i++
        }
        $("#group").append(
          $("<option></option>").val(val).html(text)
        );
      });
      $('.datepicker').glDatePicker({ startDate: new Date("January 1, 1960"), zIndex: 100 });
    }
  );
}

function addNewCustomer() {
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
  var emailMin = '<?php echo ACCOUNT_EMAIL_ADDRESS; ?>';
  var pwMin = '<?php echo ACCOUNT_PASSWORD; ?>';
  var bValid = $("#customers").validate({
    rules: {
      firstname: { minlength: fnameMin, required: true },
      lastname: { minlength: lnameMin, required: true },
      email_address: { minlength: emailMin, email: true, required: true },
      dob: { date: true },
      password: { minlength: pwMin, required: true },
      confirmation: { minlength: pwMin, required: true },
    },
    invalidHandler: function() {
    }
  }).form();
  var passwd = $('#password').val();
  var confirm = $('#confirmation').val();
  if (passwd != confirm) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_password_confirmation_invalid'); ?>');
    return false;
  }
  if (bValid) {
    var nvp = $('#customers').serialize();
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveCustomer&BATCH'); ?>'
    $.getJSON(jsonLink.replace('BATCH', nvp),    
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }         
    
        if (data.rpcStatus != 1) {
          if (data.rpcStatus == -1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          } else if (data.rpcStatus == -2) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_email_address_exists'); ?>');
          } else if (data.rpcStatus == -3) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_password_confirmation_invalid'); ?>');
          }
          return false;
        }
        modalMessage('<?php echo $lC_Language->get('text_changes_saved'); ?>');          
        oTable.fnReloadAjax();
        var add_addr = 1;
        editCustomer(data.new_customer_id, add_addr=1);  
        cm = $('#newCustomer').getModalWindow();
        setTimeout("$(cm).closeModal()", 2300);
      }
    );
    $.modal.all.closeModal();
  }
}
function modalMessage(text) {
  mm = $.modal({
          contentBg: false,
          contentAlign: 'center',
          content: text,
          resizable: false,
          actions: {},
          buttons: {}
        });
  $(mm);
  setTimeout ("$(mm).closeModal()", 800);
}
</script>
