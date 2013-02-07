<?php
/*
  $Id: administrators.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $modulesArr, $accessArr;
$aSearch = (isset($_GET['aID']) && $_GET['aID'] != null ? '&aSearch=' . $_GET['aID'] : null);  
if (!empty($_GET['set']) && $_GET['set'] == 'members') { // members
  ?>
  <script>
    $(document).ready(function() {
      // set responsive elements
      var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';      
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA' . $aSearch); ?>';
      oTable = $('#dataTable').dataTable({
        "bProcessing": true,
        "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
        "sPaginationType": paginationType,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "aoColumns": [{ "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile dataColLast" },
                      { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-mobile dataColFirst" },
                      { "sWidth": "20%", "bSortable": true, "sClass": "dataColUser" },
                      { "sWidth": "20%", "bSortable": true, "sClass": "hide-on-tablet dataColGroup" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
      $('#dataTable').responsiveTable();
           
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#main-title > h1').attr('style', 'font-size:1.8em;');
        $('#main-title').attr('style', 'padding: 0 0 0 20px;');
        $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
        $('#dataTable_length').hide();
        $('#floating-button-container').hide();
        $('#actionText').hide();
        $('.on-mobile').show();
      } else {
        // instantiate floating menu
        $('#floating-menu-div-listing').fixFloat();
      }     
    });
  </script>
  <?php
} else if (!empty($_GET['set']) && $_GET['set'] == 'groups') {  // groups
  ?>
  <script>
    $(document).ready(function() {
      // set responsive elements
      var paginationType = ($.template.mediaQuery.name === 'mobile-portrait') ? 'two_button' : 'full_numbers';
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAllGroups&media=MEDIA'); ?>';
      oTable = $('#dataTable').dataTable({
        "bProcessing": true,
        "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
        "sPaginationType": paginationType,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
   //     "sDom": 'T<"clear">lfrtip',
        "oTableTools": {
            "sSwfPath": "../ext/jquery/DataTables/media/swf/copy_csv_xls_pdf.swf"
        },
        "aoColumns": [{ "sWidth": "40%", "bSortable": true,  "sClass": "dataColGroup" },
                      { "sWidth": "30%", "bSortable": false, "sClass": "hide-on-mobile-portrait dataColModules" },
                      { "sWidth": "10%", "bSortable": true,  "sClass": "hide-on-mobile-portrait dataColMembers" },
                      { "sWidth": "20%", "bSortable": false, "sClass": "dataColAction" }]
      });
      $('#dataTable').responsiveTable();
      
      if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
        $('#main-title > h1').attr('style', 'font-size:1.8em;');
        $('#main-title').attr('style', 'padding: 0 0 0 20px;');
        $('#dataTable_info').attr('style', 'bottom: 42px; color:#4c4c4c;');
        $('#dataTable_length').hide();
        $('#floating-button-container').hide();
        $('#actionText').hide();
        $('.on-mobile').show();
      } else {
        // instantiate floating menu
        $('#floating-menu-div-listing').fixFloat();
      }   

    //  $('#dataTable tbody tr').each( function () {
    //    $('<tr class="row-drop"><td colspan="4">Hello world!</td></tr>').insertAfter('tr');
    //  });
    });
  </script>
  <?php
} else {  // access
  ?>
  <script>
    // access page script
    $(document).ready(function() {
      // instantiate sliders
      $('.access-levels-slider').slider();
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();

      /*
      // set the floating button block height
      if ($.template.mediaQuery.isSmallerThan('desktop')) {
        $("#floating-menu-div-listing").attr('style', 'top:80px !important;');
      } else {
        $("#floating-menu-div-listing").attr('style', 'top:170px !important;');
        $("#main-title").addClass('large-margin-bottom');
      }
      */

      // validation
      var gID = '<?php echo $_GET['gid']; ?>';
      if (gID != 0) {
        $("#aEdit").validate();
      } else {
        // set focus to first input field
        $("input:text:visible:first").focus();
        $("#aNew").validate();
      }

      // if edit, populate the field values
      if (gID != 0) {
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getGroupData&gid=GID'); ?>'
        $.getJSON(jsonLink.replace('GID', parseInt(gID)),
          function (data) {
            if (data.rpcStatus == -10) { // no session
              var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
              $(location).attr('href',url);
            }
            if (data.rpcStatus != 1) {
              $modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
              return false;
            }
            // populate the input values
            $("#group_name").val(data.name);
            // set the slider values
            setSliders(data.access_modules);
          }
        );
      }
    });

    // show full input width only on desktop
    var element = $('#only-desktop');
    $(document).on('init-queries', function() {
      if ($.template.mediaQuery.isSmallerThan('desktop')) {
        $('#group_name').removeClass('full-width');
      }
    }).on('enter-query-desktop', function() {
      $('#group_name').addClass('full-width');
    }).on('quit-query-desktop', function() {
      $('#group_name').removeClass('full-width');
    });

    // expand / collapse all details blocks
    /*$("#quick-set-container").click(function() {
      var isOpen = $('#quick-set-container').is('.open');
      if (isOpen) {
        $('.details').addClass('open');
        $('.details-summary').attr('aria-expanded', 'true');
        $('.div-details').show();

      } else {
        $('.details').removeClass('open');
        $('.details-summary').attr('aria-expanded', 'false');
        $('.div-details').hide();
      }
    });*/

    // main quick set slider
    $("#generalSelect").change(function() {
      var current = $("#generalSelect").val();
      var modules = <?php echo json_encode($modulesArr); ?>;
      $.each(modules, function(key, value) {
        id = value.toLowerCase();
        $('#' + id).val(current).change();
      });
      var access = <?php echo json_encode($accessArr); ?>;
      $.each(access, function(akey, avalue) {
        id = avalue.toLowerCase();
        $('#' + id).val(current).change();
      });
    });

    // quick-set section sliders
    function updateSectionSliders(section) {
      var newVal = $('#' + section).val();
      var access = <?php echo json_encode($accessArr); ?>;
      $.each(access, function(key, value) {
        id = value.toLowerCase();
        parts = id.split('-');
        if (parts[0] == section) {
          $('#' + id).val(newVal).change();
        }
      });
    }

    // set the slider values
    function setSliders(access_modules) {
      var valueCheck = 0;
      var sectionCheck = '0';
      var isSame = true;
      $.each(access_modules, function(key, value) {
        var sliderValue = (value * 25);
        if (key == '*') { // top level admin
          var current = 100;
          var modules = <?php echo json_encode($modulesArr); ?>;
          $.each(modules, function(key, value) {
            id = value.toLowerCase();
            $('#' + id).val(current).change();
          });
          var access = <?php echo json_encode($accessArr); ?>;
          $.each(access, function(akey, avalue) {
            id = avalue.toLowerCase();
            $('#' + id).val(current).change();
          });
          $('#generalSelect').val(current).change();
        } else {
          var parts = key.split('-');
          var section = parts[0];
          if (sectionCheck == '0') {
            sectionCheck = section;
            valueCheck = sliderValue;
          }
          if (sectionCheck != section) {
            // section changed
            if (isSame == true) {
              $('#' + sectionCheck).val(valueCheck).change();
            } else {
              $('#lbl-' + sectionCheck).html('<span class="details-title-text">' + sectionCheck.charAt(0).toUpperCase() + sectionCheck.slice(1) + '</span><div style="width:72%; float:right"><div><span class="icon-gear icon-size2 red"></span><span class="margin-left intro anthracite"><?php echo $lC_Language->get('text_custom_levels_selected'); ?></span></div></div>');
              $('#p-quick_set').hide();
              isSame = true;
            }
            valueCheck = sliderValue;
            sectionCheck = section;
          } else {
            // section is same
            if (sliderValue != valueCheck) isSame = false;
          }
          $('#' + key).val(sliderValue).change();
        }
      });
      if (isSame == true) {
        $('#' + sectionCheck).val(valueCheck).change();
      }
      return true;
    }
  </script>
  <?php
}
?>