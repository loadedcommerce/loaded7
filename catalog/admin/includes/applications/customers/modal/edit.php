<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
$pContent = '';
if ( ACCOUNT_GENDER > -1 ) {
  $pContent .= '<p>' . $lC_Language->get('introduction_new_customer') . '</p>' .
               '<p class="button-height inline-label">' .
               '  <label for="gender" class="label" style="width:30%;">' . $lC_Language->get('field_gender') . '</label>' .
               '  <span class="button-group">' .
               '    <label for="editGender_1" class="button green-active">' .
               '    <input type="radio" name="gender" id="editGender_1" value="m" checked>' . $lC_Language->get('gender_male') . '</label>' .
               '    <label for="editGender_2" class="button green-active">' .
               '    <input type="radio" name="gender" id="editGender_2" value="f">' . $lC_Language->get('gender_female') . '</label>' .
               '  </span>' .
               '</p>';
}
$pContent .=  '<p class="button-height inline-label">' .
              '  <label for="firstname" class="label" style="width:30%;">' . $lC_Language->get('field_first_name') . '</label>' .
                 lc_draw_input_field('firstname', null, 'class="input" style="width:93%;" id="editFirstname"') .
              '</p>' .
              '<p class="button-height inline-label">' .
              '  <label for="lastname" class="label" style="width:30%;">' . $lC_Language->get('field_last_name') . '</label>' .
                 lc_draw_input_field('lastname', null, 'class="input" style="width:93%;" id="editLastname"') .
              '</p>';
if ( ACCOUNT_DATE_OF_BIRTH == '1' ) {
  $pContent .=  '<p class="button-height inline-label">' .
                '  <label for="dob" class="label" style="width:30%;">' . $lC_Language->get('field_date_of_birth') . '</label>' .
                '  <span class="input">' .
                '    <span class="icon-calendar"></span>' .
                     lc_draw_input_field('dob', null, 'class="input-unstyled datepicker" style="width:90%;" id="editDob"') .
                '  </span>' .
                '</p>';
}
$pContent .=  '<p class="button-height inline-label">' .
              '  <label for="email_address" class="label" style="width:30%;">' . $lC_Language->get('field_email_address') . '</label>' .
                 lc_draw_input_field('email_address', null, 'class="input" style="width:93%;" id="editEmailAddress"') .
              '</p>';
if ( ACCOUNT_NEWSLETTER == '1' ) {
  $pContent .= '<p class="button-height inline-label">' .
               '  <label for="newsletter" class="label" style="width:30%;">' . $lC_Language->get('field_newsletter_subscription') . '</label>' .
                  lc_draw_checkbox_field('newsletter', '1', true, 'id="editNewsletter" class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"') .
               '</p>';
}
$pContent .= '<p class="button-height inline-label">' .
             '  <label for="password" class="label" style="width:30%;">' . $lC_Language->get('field_password') . '</label>' .
                lc_draw_password_field('password', 'class="input" style="width:93%;" id="editPassword"') .
             '</p>' .
             '<p class="button-height inline-label">' .
             '  <label for="confirmation" class="label" style="width:30%;">' . $lC_Language->get('field_password_confirmation') . '</label>' .
                lc_draw_password_field('confirmation', 'class="input" style="width:93%;" id="editConfirmation"') .
             '</p>' .
             '<p class="button-height inline-label">' .
             '  <label for="group" class="label" style="width:30%;">' . $lC_Language->get('field_customer_group') . '</label>' .
                lc_draw_pull_down_menu('group', null, null, 'class="select" style="width:73%;" id="editGroup"') .
             '</p>' .
             '<p class="button-height inline-label">' .
               '  <label for="status" class="label" style="width:30%;">' . $lC_Language->get('field_status') . '</label>' .
                  lc_draw_checkbox_field('status', '1', true, 'id="editStatus" class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"') .
             '</p>' .
             '<p class="button-height float-right">' .
             '  <a class="button margin-bottom" href="javascript://" onclick="saveCustomer(); return false;">' .
             '    <span class="button-icon green-gradient">' .
             '      <span class="icon-download"></span>' .
             '    </span>' . $lC_Language->get('button_save') .
             '  </a>' .
             '</p>';

$aContent  = '<span id="addAddress" style="display:none;">';
if ( ACCOUNT_GENDER > -1 ) {
$aContent .= '  <p class="button-height inline-label">' .
             '    <label for="gender" class="label" style="width:30%;">' . $lC_Language->get('field_gender') . '</label>' .
             '    <span class="button-group">' .
             '      <label for="ab_gender_1" class="button green-active">' .
             '      <input type="radio" name="ab_gender" id="ab_gender_1" value="m" checked>' . $lC_Language->get('gender_male') . '</label>' .
             '      <label for="ab_gender_2" class="button green-active">' .
             '      <input type="radio" name="ab_gender" id="ab_gender_2" value="f">' . $lC_Language->get('gender_female') . '</label>' .
             '    </span>' .
             '  </p>';
}
$aContent .= '<p class="button-height inline-label">' .
              '  <label for="ab_firstname" class="label" style="width:30%;">' . $lC_Language->get('field_first_name') . '</label>' .
                 lc_draw_input_field('ab_firstname', null, 'class="input" style="width:93%;"') .
              '</p>' .
              '<p class="button-height inline-label">' .
              '  <label for="ab_lastname" class="label" style="width:30%;">' . $lC_Language->get('field_last_name') . '</label>' .
                 lc_draw_input_field('ab_lastname', null, 'class="input" style="width:93%;"') .
              '</p>';
if ( ACCOUNT_COMPANY > -1 ) {
  $aContent .=  '<p class="button-height inline-label">' .
                '  <label for="ab_company" class="label" style="width:30%;">' . $lC_Language->get('field_company') . '</label>' .
                lc_draw_input_field('ab_company', null, 'class="input" style="width:93%;"') .
                '</p>';
}    
$aContent .= '<p class="button-height inline-label">' .
              '  <label for="ab_street_address" class="label" style="width:30%;">' . $lC_Language->get('field_street_address') . '</label>' .
                 lc_draw_input_field('ab_street_address', null, 'class="input" style="width:93%;"') .
              '</p>';
if ( ACCOUNT_SUBURB > -1 ) {
  $aContent .=  '<p class="button-height inline-label">' .
                '  <label for="ab_suburb" class="label" style="width:30%;">' . $lC_Language->get('field_suburb') . '</label>' .
                lc_draw_input_field('ab_suburb', null, 'class="input" style="width:93%;"') .
                '</p>';
}          
$aContent .=  '<p class="button-height inline-label">' .
              '  <label for="ab_postcode" class="label" style="width:30%;">' . $lC_Language->get('field_post_code') . '</label>' .
              lc_draw_input_field('ab_postcode', null, 'class="input" style="width:93%;"') .
              '</p>' .
              '<p class="button-height inline-label">' .
              '  <label for="ab_city" class="label" style="width:30%;">' . $lC_Language->get('field_city') . '</label>' .
              lc_draw_input_field('ab_city', null, 'class="input" style="width:93%;"') .
              '</p>';
if ( ACCOUNT_STATE > -1 ) {
  $aContent .=  '<p class="button-height inline-label">' .
                '  <label for="ab_state" class="label" style="width:30%;">' . $lC_Language->get('field_state') . '</label>' .
                '<span id="abState"></span>' . 
                '</p>';
}     
$aContent .=  '<p class="button-height inline-label">' .
              '  <label for="ab_country_id" class="label" style="width:30%;">' . $lC_Language->get('field_country') . '</label>' .
              lc_draw_pull_down_menu('ab_country_id', null, STORE_COUNTRY, 'class="select" style="width:73%;" onchange="updateZones();"') .
              '</p>';         
if ( ACCOUNT_TELEPHONE > -1 ) {
  $aContent .=  '<p class="button-height inline-label">' .
                '  <label for="ab_telephone" class="label" style="width:30%;">' . $lC_Language->get('field_telephone_number') . '</label>' .
                lc_draw_input_field('ab_telephone', null, 'class="input" style="width:93%;"') .
                '</p>';
}
if ( ACCOUNT_FAX > -1 ) {
  $aContent .=  '<p class="button-height inline-label">' .
                '  <label for="ab_fax" class="label" style="width:30%;">' . $lC_Language->get('field_fax_number') . '</label>' .
                lc_draw_input_field('ab_fax', null, 'class="input" style="width:93%;"') .
                '</p>';
}              
$aContent .= '<p class="button-height inline-label" id="setPrimary"></p>';
$aContent .= '  <p class="button-height float-right">' .
             '    <a class="button margin-bottom" href="javascript://" onclick="toggleAddressForm(true); return false;">' .
             '      <span class="button-icon red-gradient glossy">' .
             '        <span class="icon-cross"></span>' .
             '      </span>' . $lC_Language->get('button_cancel') .
             '    </a>' .
             '    <a class="button margin-bottom" href="javascript://" onclick="saveAddress(); return false;">' .
             '      <span class="button-icon green-gradient glossy">' .
             '        <span class="icon-download"></span>' .
             '      </span>' . $lC_Language->get('button_save') .
             '    </a>' .
             '  </p>' . 
             '</span>';


?>
<style>
#editCustomer { padding-bottom:20px; }
.list > li > span { color: #666666; }
</style>
<script>
function editCustomer(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  $.modal({
      content: '<div class="standard-tabs same-height" id="editCustomerContainer">'+
               '  <ul class="tabs">'+
               '    <li class="active"><?php echo lc_link_object('#section_personal', $lC_Language->get('section_personal'), 'onclick="toggleAddAddressButton(false); return false;"'); ?></li>'+
               '    <li><?php echo lc_link_object('#section_address_book', $lC_Language->get('section_address_book'), 'onclick="toggleAddAddressButton(true); return false;"'); ?></li>'+
               '    <li id="li-toggle" style="display:none;"><a href="javascript://" onclick="toggleAddressForm(); return false;"><span class="icon-plus-round icon-green"><?php echo $lC_Language->get('operation_new_address_book_entry'); ?></span></a></li>'+
               '  </ul>'+
               '  <div class="clearfix tabs-content">'+
               '    <div id="section_personal" class="with-padding">'+
               '      <form name="personalForm" id="personalForm" autocomplete="off" action="" method="post">'+
               '        <?php echo $pContent; ?>'+
               '      </form>'+
               '    </div>'+
               '    <div id="section_address_book" class="with-padding">'+
               '      <form name="addressBookForm" id="addressBookForm" action="" method="post">'+
               '        <div id="addresBookPersonal">'+
               '          <div id="addressListContainer"></div>'+
               '        </div>'+
               '        <?php echo $aContent; ?>'+
               '      </form>'+
               '    </div>'+
               '    <span id="abParentId" style="display:none;"></span>'+
               '    <span id="abId" style="display:none;"></span>'+
               '  </div>'+
               '</div>',
      title: '<?php echo $lC_Language->get('modal_heading_edit_customer'); ?>',
      width: 600,
      scrolling: false,
      actions: {
        'Close' : {
          color: 'red',
          click: function(win) { win.closeModal(); }
        }
      },
      buttons: {
        '<?php echo $lC_Language->get('button_close'); ?>': {
          classes:  'glossy',
          click:    function(win) { win.closeModal(); }
        }
      },
      buttonsLowPadding: true
  });
  mask();
  getFormData(id);
  $('.datepicker').glDatePicker({ startDate: new Date("January 1, 1960"), zIndex: 100 });
}

function getFormData(id) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCustomerFormData&cid=CID'); ?>';
  $.getJSON(jsonLink.replace('CID', id),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        unmask();
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      // populate personal form
      $("#editGroup").empty();
      $.each(data.groupsArray, function(val, text) {
        var selected = (data.customerData.customers_group_id == val) ? 'selected="selected"' : '';
        if(data.customerData.customers_group_id == val) {
          $("#editGroup").closest("span + *").prevAll("span.select-value:first").text(text);
        }
        $("#editGroup").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      if (data.customerData.customers_gender == 'm') {
        $("#editGender_1").attr('checked', true).change();
      } else if (data.customerData.customers_gender == 'f') {
        $("#editGender_2").attr('checked', true).change();
      }
      $("#editFirstname").val(data.customerData.customers_firstname);
      $("#editLastname").val(data.customerData.customers_lastname);
      $("#editDob").val(data.customerData.customers_dob_short);
      $("#editEmailAddress").val(data.customerData.customers_email_address);
      if (data.customerData.customers_newsletter == 1) {
        $("#editNewsletter").attr('checked', true);
        $("#editNewsletter").parent().addClass("checked");
      } else {
        $("#editNewsletter").attr('checked', false);
        $("#editNewsletter").parent().removeClass("checked");
      }
      if (data.customerData.customers_status == 1) {
        $("#editStatus").attr('checked', true);
        $("#editStatus").parent().addClass("checked");
      } else {
        $("#editStatus").attr('checked', false);
        $("#editStatus").parent().removeClass("checked");
      }

      // populate address book listing
      $("#addressListContainer").html(data.addressBook);

      // populate new address form
      $("#abParentId").html(id);
      $("#ab_gender_1").attr('checked', true);
      $("#abState").html(data.abState);
      $("#ab_country_id").empty();
      $.each(data.countriesArray, function(val, text) {
        var storeCountry = '<?php echo STORE_COUNTRY; ?>';
        var selected = (storeCountry == val) ? 'selected="selected"' : '';
        if(storeCountry == val) {
          $("#ab_country_id").closest("span + *").prevAll("span.select-value:first").text(text);
        }
        $("#ab_country_id").append(
          $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      unmask();
    }
  );
}

function toggleAddressForm(reset) {
  if (reset == undefined) {
    if ($("#addAddress:visible").length > 0) {
      $("#addresBookPersonal").show();
      $("#addAddress").hide();
    } else {
      $("#addresBookPersonal").hide();
      $("#addAddress").show();
      $('#li-toggle').hide();
    }
  } else {    
    $("#addressBookForm")[0].reset();
    $("#addresBookPersonal").show();
    $("#addAddress").hide();
    $('#li-toggle').show();
  }
}

function toggleAddAddressButton(x) {
  if (x === true) {
    var isVisible = $('#addAddress').is(':visible');
    if (isVisible) {
      $('#li-toggle').hide();
    } else {
      $('#li-toggle').show();
    }
  } else {
    $('#li-toggle').hide();
  }
}

function saveCustomer() {
  var cid = parseInt($("#abParentId").html());
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
  var emailMin = '<?php echo ACCOUNT_EMAIL_ADDRESS; ?>';
  var pwMin = '<?php echo ACCOUNT_PASSWORD; ?>';
  var bValid = $("#personalForm").validate({
    rules: {
      firstname: { minlength: fnameMin, required: true },
      lastname: { minlength: lnameMin, required: true },
      email_address: { minlength: emailMin, email: true, required: true },
      dob: { date: true },
      password: { minlength: pwMin },
      confirmation: { minlength: pwMin },
    },
    invalidHandler: function() {
      unmask();
    }
  }).form();
  if (bValid) {
    var nvp = $('#personalForm').serialize();
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=updateCustomer&cid=CID&BATCH'); ?>'
    $.getJSON(jsonLink.replace('CID', cid).replace('BATCH', nvp),
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
        } else {
          modalMessage('<?php echo $lC_Language->get('text_changes_saved'); ?>');
          var modPage = '<?php echo $lC_Template->getModule(); ?>';
          if (modPage == 'customers') {
            oTable.fnReloadAjax();
          }
          // get new form data
          getFormData(cid);
        }
      }
    );
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

function saveAddress() {
  $("#formProcessing").fadeIn('fast');
  var abid = parseInt($("#abId").html());
  var fnameMin = '<?php echo ACCOUNT_FIRST_NAME; ?>';
  var lnameMin = '<?php echo ACCOUNT_LAST_NAME; ?>';
  var emailMin = '<?php echo ACCOUNT_EMAIL_ADDRESS; ?>';
  var pwMin = '<?php echo ACCOUNT_PASSWORD; ?>';
  var companyMin = '<?php echo ACCOUNT_COMPANY; ?>';
  var addressMin = '<?php echo ACCOUNT_STREET_ADDRESS; ?>';
  var suburbMin = '<?php echo ACCOUNT_SUBURB; ?>';
  var postcodeMin = '<?php echo ACCOUNT_POST_CODE; ?>';
  var cityMin = '<?php echo ACCOUNT_CITY; ?>';
  var telephoneMin = '<?php echo ACCOUNT_TELEPHONE; ?>';
  var faxMin = '<?php echo ACCOUNT_FAX; ?>';
  var bValid = $("#addressBookForm").validate({
    rules: {
      ab_gender: { required: true },
      ab_firstname: { minlength: fnameMin, required: true },
      ab_lastname: { minlength: lnameMin, required: true },
      ab_company: { minlength: companyMin },
      ab_street_address: { minlength: addressMin, required: true },
      ab_suburb: { minlength: suburbMin },
      ab_postcode: { minlength: postcodeMin, required: true },
      ab_city: { minlength: cityMin, required: true },
      ab_state: { required: true },
      ab_telephone: { minlength: telephoneMin, required: true },
      ab_fax: { minlength: faxMin },
    },
    invalidHandler: function() {
      $("#formProcessing").fadeOut('fast');
    }
  }).form();
  if (bValid) {
    var cid = parseInt($("#abParentId").html());
    var formData = $("#addressBookForm").serialize();
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=saveAddressEntry&customer_id=CID&abid=ABID&FORMDATA'); ?>'
    $.getJSON(jsonLink.replace('CID', cid).replace('ABID', abid).replace('FORMDATA', formData),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          $("#formProcessing").fadeOut('slow');
          if (data.rpcStatus == -1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          } else if (data.rpcStatus == -2) {
            $.modal.alert('<?php echo $lC_Language->get('ms_warning_state_select_from_list'); ?>');
          } else if (data.rpcStatus == -3) {
            $.modal.alert('<?php echo sprintf($lC_Language->get('ms_error_state'), ACCOUNT_STATE); ?>');
          }
        } else {
          // get new form data
          getFormData(cid);
          // show the address listing
          toggleAddressForm();
          // added to clear form after successful save
          $("#addressBookForm")[0].reset();
          modalMessage('<?php echo $lC_Language->get('text_new_address_saved'); ?>');
        }
      }
    );
  }
}

function editAddress(id, primary) {
  $("#addressBookForm")[0].reset();
  $("#abId").html(id);
  // get the address book data
  var cid = parseInt($("#abParentId").html());
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAddressEntry&cid=CID&abid=ABID'); ?>'
  $.getJSON(jsonLink.replace('CID', cid).replace('ABID', id),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $("#formProcessing").fadeOut('slow');
        if (data.rpcStatus == -1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }
      } else {
        // populate the ab form
        $("#ab_country_id").val( data.country_id ).attr('selected', true);
        updateZones(data.zoneData.zone_name);
        if (data.gender == 'm') {
          $("#ab_gender_1").attr('checked', true);
          $("#ab_gender_2").attr('checked', false);
        } else if (data.gender == 'f') {
          $("#ab_gender_1").attr('checked', false);
          $("#ab_gender_2").attr('checked', true);
        }
        $("#ab_firstname").val(data.firstname);
        $("#ab_lastname").val(data.lastname);
        $("#ab_company").val(data.company);
        $("#ab_street_address").val(data.street_address);
        $("#ab_suburb").val(data.suburb);
        $("#ab_city").val(data.city);
        $("#ab_postcode").val(data.postcode);
        $("#ab_telephone").val(data.telephone_number);
        $("#ab_fax").val(data.fax_number);
        if (primary == 'false') {
          $("#setPrimary").html('<label for="default" class="label"><?php echo $lC_Language->get('field_set_as_primary'); ?></label>&nbsp;&nbsp;<?php echo '&nbsp;' . lc_draw_checkbox_field('ab_primary', null, null, 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"');?>');
        } else {
          $("#setPrimary").empty();
        }
        // toggle the form on
        toggleAddressForm();
      }
    }
  );
}

function deleteAddress(id) {
  $.modal.confirm('<?php echo $lC_Language->get('introduction_delete_address_book_entry'); ?>', function() {
    var cid = parseInt($("#abParentId").html());
    var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteAddressEntry&cid=CID&abid=ABID'); ?>'
    $.getJSON(jsonLink.replace('CID', cid).replace('ABID', id),
      function (data) {
        if (data.rpcStatus == -10) { // no session
          var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
          $(location).attr('href',url);
        }
        if (data.rpcStatus != 1) {
          if (data.rpcStatus == -1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
          } else if (data.rpcStatus == -2) {
            $.modal.alert('<?php echo $lC_Language->get('delete_warning_primary_address_book_entry'); ?>');
          }
        } else {
          // get new form data
          getFormData(cid);
        }
      }
    );
  }, function() { 
  });
}

function updateZones(selected) {
  $("#formProcessing").fadeIn('fast');
  var countryID = $("#ab_country_id").val();
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getStateZones&country_id=CID'); ?>'
  $.getJSON(jsonLink.replace('CID', countryID),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $("#formProcessing").fadeOut('slow');
        if (data.rpcStatus == -1) {
          $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        }
      } else {
        $("#abState").html(data.abZonesDropdown);
        if (selected != undefined) {          
          $("#ab_state").closest("span + *").prevAll("span.select-value:first").text(selected);
          $("#ab_state").val( selected ).attr('selected', true);
        }
        $("#formProcessing").fadeOut('slow');
      }
    }
  );
}
</script>