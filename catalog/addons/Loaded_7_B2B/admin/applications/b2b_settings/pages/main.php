<?php
/**
  @package    admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main.php v1.0 2013-08-08 datazen $
*/
?>
<!-- Main content -->
<style>
.allow-self-register { width:179px !important; }
.bottom-mark .mark-label { color:#4C4C4C; }
.slider { width:96% !important; }
#new_customers_access_level { margin-left:-50px !important; }

.legend { font-weight:bold; font-size: 1.1em; }

.dataColID { text-align: left; }
.dataColGroup { text-align: left; }
.dataColMembers { text-align: left; }
.dataColAction { text-align: right; }

.dataTables_wrapper { background:none; box-shadow: 0 0 0 0 #fff inset, 0 0 0 rgba(255, 255, 255, 0.35) inset; }
.dataTables_paginate { position: absolute; right:0; bottom:6px; }
.paginate_disabled_previous, .paginate_enabled_previous, .paginate_disabled_next, .paginate_enabled_next { background-image: none; border: none; box-shadow: none; color: #0059a0; text-shadow: none; }
.paginate_enabled_previous:hover, .paginate_enabled_next:hover { background: none; color: #0689f1; }
.paginate_disabled_previous, .paginate_disabled_next { color: #999 !important }   
</style>
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <div class="with-padding-no-top">
    <form name="b2b_settings" id="b2b_settings" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=save'); ?>" method="post" enctype="multipart/form-data">

      <div class="side-tabs main-tabs">
        <!-- Tabs -->
        <ul class="tabs">
          <li class="active"><a href="#customers"><?php echo $lC_Language->get('heading_customers'); ?></a></li>
          <li><a href="#products"><?php echo $lC_Language->get('heading_products'); ?></a></li>
        </ul>
        <!-- Content -->
        <div class="tabs-content">
          <div id="customers" class="with-padding">
          
            <fieldset class="fieldset">
              <legend class="legend"><?php echo $lC_Language->get('heading_guest_access'); ?></legend>
              <div class="columns no-margin-bottom">
                <div class="new-row-mobile twelve-columns mid-margin-bottom small-padding-left">
                  <p class="button-height inline-label">
                    <label for="allow_self_register" class="label allow-self-register"><?php echo $lC_Language->get('label_allow_self_registrations'); ?></label>
                    <?php echo lc_draw_checkbox_field('allow_self_register', null, null, 'checked="checked" class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?><span class="small margin-left"><?php echo $lC_Language->get('info_bubble_displays_create_account_form'); ?></span>                
                  </p>

                  <p class="inline-medium-label button-height mid-margin-top">
                    <span class="label"><?php echo $lC_Language->get('label_guest_catalog_access'); ?></span>
                    <input type="text" id="guest-catalog-access-slider" class="guest-catalog-access-slider" data-slider-options='{"size":false,"innerMarks":33,"step":33,"knob":true,"tooltip":"false","tooltipClass":"hidden","bottomMarks":[{"value":0,"label":"None"},{"value":33,"label":"View Catalog"},{"value":66,"label":"See Pricing"},{"value":100,"label":"Add to Cart"}],"insetExtremes":true,"barClasses":"orange-gradient"}'><?php echo lc_show_info_bubble($lC_Language->get('info_bubble_guest_catalog_access')); ?>
                    <input type="hidden" name="guest-catalog-access" id="guest-catalog-access" value="">
                  </p>

                </div>
              </div>
            </fieldset>  

            <div id="levelsContainer">
              <fieldset class="fieldset">
                <legend class="legend no-margin-bottom"><?php echo $lC_Language->get('heading_access_group_levels'); ?></legend>
                <form name="customer_access_levels" method="post">
                <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table" id="dataTable">
                  <thead>
                    <tr>
                      <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_id'); ?></th>
                      <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_group'); ?></th>
                      <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_members'); ?></th>
                      <th scope="col" class="align-right"><?php echo $lC_Language->get('table_heading_action'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4">
                        <p class="inline-medium-label button-height small-margin-top margin-left">
                          <label for="new_customers_access_level" class="label"><?php echo $lC_Language->get('label_new_access_level'); ?></label>
                          <?php echo lc_draw_input_field('new_customers_access_level', null, 'class="input"'); ?>
                          <a href="javascript:void(0);" onclick="addNewCustomersAccessLevel($('#new_customers_access_level').val());" class="button icon-star blue-gradient"><?php echo $lC_Language->get('button_create_new'); ?></a>
                        </p>                    
                      </th>
                    </tr>
                  </tfoot>
                </table>
                </form>
              </fieldset>    
            </div>
          </div>
          
          <div id="products" class="with-padding">
            <p>Coming Soon ...</p>
          </div>        
          
        </div>
      </div> 
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
      <div class="clear-both"></div>
      <div class="six-columns twelve-columns-tablet">
        <div id="buttons-menu-div-listing">
          <div id="buttons-container" style="position: relative;" class="clear-both">
            <div class="align-right">
              <p class="button-height">
                <?php
                  $save = (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? '' : ' onclick="validateForm(\'#b2b_settings\');"');
                  $close = lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule());
                  button_save_close($save, false, $close);
                ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </form>    
     
  </div>
</section>
<?php $lC_Template->loadModal($lC_Template->getModule()); ?>
<script>

$("#guest-catalog-access-slider").change(function() {
  var current = $("#guest-catalog-access-slider").val();
  $('#guest-catalog-access').val(current);
});

function addNewCustomersAccessLevel(level) {
  if (level == '') return;
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=addCustomerAccessLevel&level=LEVEL&addon=Loaded_7_B2B'); ?>';
  $.getJSON(jsonLink.replace('LEVEL', level),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      $('#new_customers_access_level').val("");
      oTable.fnReloadAjax();
    }
  );
}

$(document).ready(function() {  
  $('.guest-catalog-access-slider').slider();
  
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCustomersGroupAccessLevels&addon=Loaded_7_B2B&media=MEDIA'); ?>';   
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "bPaginate": true,
    "bLengthChange": false,
    "bFilter": false,
    "bSort": true,
    "bInfo": false,
    "aoColumns": [{ "sWidth": "5%", "bSortable": true, "sClass": "dataColID hide-on-mobile-portrait" },
                  { "sWidth": "55%", "bSortable": true, "sClass": "dataColGroup" },
                  { "sWidth": "20%", "bSortable": true, "sClass": "dataColMembers hide-on-mobile-portrait" },
                  { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();  
  
});


function validateForm(e) {
  // turn off messages
  jQuery.validator.messages.required = "";
  
  var bValid = $("#b2b_settings").validate({
    invalidHandler: function() {
    },
    rules: {
    },    
    messages: {
    } 
  }).form();

  if (bValid) {
    $(e).submit();
  } 

  return false;
}
</script>
<!-- End main content -->