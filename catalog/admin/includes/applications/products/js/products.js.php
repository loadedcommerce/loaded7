<?php
/*
  $Id: products.js.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
global $lC_Template, $lC_Language, $lC_Image, $pInfo;
if (!empty($_GET['action']) && ($_GET['action'] == 'save')) { // edit a product
  ?>
  <script>
    $(document).ready(function() {
      $("#languageTabs").parent().css('margin-bottom', '0'); 
      // instantiate floating menu
      $('#floating-menu-div-listing').fixFloat();
      // instantiate the datepicker
      $(".datepicker").glDatePicker({ zIndex: 100 }); 

      // CONTENT TAB
      <?php if ($pInfo) { ?>
      createUploader2();
      <?php } ?>
      //$('#fileUploaderImageContainer .qq-upload-button').hide();
      //$('#fileUploaderImageContainer .qq-upload-list').hide();
      <?php               
      foreach ( $lC_Language->getAll() as $l ) {  
        echo "CKEDITOR.replace('ckEditorProductDescription_" . $l['id'] . "', { height: 200, width: '99%', extraPlugins: 'stylesheetparser',contentsCss: '../templates/default/css/base.css',stylesSet: [] });";
      }
      ?>  
      //$('#products_name_1').focus();
      $(this).scrollTop(0); 
           
      // IMAGES TAB
      createUploader();
      <?php if ($pInfo) { ?>
      getImages();
      <?php } ?>
      //getLocalImages();  
      $('#images-gallery-trigger').addClass('with-right-arrow grey-arrow');
            
       // OPTIONS TAB            
      _setSimpleOptionsSortOrder();
      
      $('.sorted_table').sortable({  
        containerSelector: 'tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>',
        tolerance: '1',
        onDragStart: function (item, group, _super) {      
          item.css({
            height: item.height(),
            width: item.width()
          });
          item.addClass("dragged");
          $('body').addClass('dragging');
        },
        onDrop: function  (item, container, _super) { 
          item.removeClass("dragged");
          item.attr("style", "");
          $("body").removeClass("dragging");

          _setSimpleOptionsSortOrder();
        }    
      });
             
      // PRICING TAB
      _refreshSimpleOptionsPricingSymbols();
      _updatePricingDivChevrons(); 
      
                
    });
    <?php if ($pInfo) { ?>
    /**
     * CONTENT TAB
     * 
    /* create the uploader instance on content tab */
    function createUploader2(){
      var uploader = new qq.FileUploader({
        element: document.getElementById('fileUploaderImageContainer'),
        action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=fileUpload&default=1'); ?>',
        onComplete: function(id, fileName, responseJSON){
          getImages();
        },
      });
    }
    <?php } ?>

    function toggleEditor(id) {
      var selection = $("#ckEditorProductDescription_" + id);
      if ($(selection).is(":visible")) {
        $('#ckEditorProductDescription_' + id).hide();
        $('#cke_ckEditorProductDescription_' + id).show();
      } else {
        $('#ckEditorProductDescription_' + id).attr('style', 'width:99%');
        $('#cke_ckEditorProductDescription_' + id).hide();
      }
    }
    
    /**
    * IMAGES TAB
    *    
   /* create the uploader instance for images tab */
    function createUploader(){
      var uploader = new qq.FileUploader({
        element: document.getElementById('fileUploaderContainer'),
        action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=fileUpload&default=DEFAULT'); ?>',
        onComplete: function(id, fileName, responseJSON){
          getImages();
        },
      });
    }

    // added for the product images content panel switching
    function showContent(tab) { 
      $('.qq-upload-list').empty();
      if (tab == 'default') {
        $(".panel-content").css('left', 0);
        $('#defaultImagesContainer').show();
        $('#additionalImagesContainer').hide();
        $('#images-gallery-trigger').addClass('with-right-arrow grey-arrow');
        $('#images-gallery-trigger > a').addClass('selected-menu');
        $('#additional-gallery-trigger').removeClass('with-right-arrow grey-arrow');
        $('#additional-gallery-trigger > a').removeClass('selected-menu');    
      } else {
        $(".panel-content").css('left', 0);
        $('#defaultImagesContainer').hide();
        $('#additionalImagesContainer').show();
        $('#images-gallery-trigger').removeClass('with-right-arrow grey-arrow');
        $('#images-gallery-trigger > a').removeClass('selected-menu');
        $('#additional-gallery-trigger').addClass('with-right-arrow grey-arrow');  
        $('#additional-gallery-trigger > a').addClass('selected-menu');  
      }
    }
    
    // added to check if image file exists
    function checkImageExists(file){
      return $.ajax({
        url: file,
        type: 'HEAD'
      });
    }
          
    function showImages(data) {  
      var defaultImage = 'test'; 
      
      for ( i=0; i < data.entries.length; i++ ) {
        var entry = data.entries[i];
        var style = 'width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px; margin: 10px; padding: 10px; float: left; text-align: center; border-radius: 5px;';  
        if ( entry[1] == '1' ) { // original (products_images_groups_id)
          style += ' background-color: #535252;';
          var onmouseover = 'this.style.backgroundColor=\'#656565\';';
          var onmouseout = 'this.style.backgroundColor=\'#535252\';';      
        } else {
          var onmouseover = 'this.style.backgroundColor=\'#656565\';';
          var onmouseout = 'this.style.backgroundColor=\'\';';
        }

        if ( entry[6] == '1' ) { // default_flag         
          var newdiv = '<span id="image_' + entry[0] + '" style="' + style + '" onmouseover="' + onmouseover + '" onmouseout="' + onmouseout + '">';
          newdiv += '<img class="framed" src="<?php echo '../images/products/mini/'; ?>' + entry[2] + '" border="0" height="<?php echo $lC_Image->getHeight('mini'); ?>" alt="' + entry[2] + '" title="' + entry[5] + ' bytes" style="max-width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px;" /><br />' + entry[3];
          
          defaultImage = entry[2];
          
          if ( entry[1] == '1' ) {    
            newdiv += '<div class="show-on-parent-hover" style="position:relative;"><span class="button-group compact children-tooltip" style="position:absolute; top:-42px; left:11px;"><a href="javascript://" class="button icon-play orange-gradient" title="<?php echo $lC_Language->get('icon_preview'); ?>" onclick="showImage(\'' + entry[4] + '\', \'' + entry[7] + '\', \'' + entry[8] + '\');"></a><a href="#" class="button icon-cross red-gradient" onclick="removeImage(\'image_' + entry[0] + '\');" title="<?php echo $lC_Language->get('icon_delete'); ?>"></a></span></div>';
          } else {
            newdiv += '<div class="show-on-parent-hover" style="position:relative;"><span class="button-group compact children-tooltip" style="position:absolute; top:-42px; left:23px;"><a href="javascript://" class="button icon-play orange-gradient" title="<?php echo $lC_Language->get('icon_preview'); ?>" onclick="showImage(\'' + entry[4] + '\', \'' + entry[7] + '\', \'' + entry[8] + '\');"></a></div>';
          }
          newdiv += '</span>';

          $('#defaultImages').append(newdiv);
          
        } else {
          if ( entry[1] == '1' ) {  // original images
            var onmouseover = 'this.style.backgroundColor=\'#656565\'; this.style.backgroundImage=\'url(<?php echo lc_href_link_admin('templates/' . $lC_Template->getCode() . '/img/icons/16/drag.png'); ?>)\'; this.style.backgroundRepeat=\'no-repeat\'; this.style.zIndex=\'300000 !important\'; this.style.backgroundPosition=\'8px 2px\';';
            var newdiv2 = '<span id="image_' + entry[0] + '" style="' + style + '" onmouseover="' + onmouseover + '" onmouseout="' + onmouseout + '">';
            newdiv2 += '<img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/mini/'; ?>' + entry[2] + '" border="0" height="<?php echo $lC_Image->getHeight('mini'); ?>" alt="' + entry[2] + '" title="' + entry[5] + ' bytes" style="max-width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px;" /><br />' + entry[3];
            newdiv2 += '<div class="show-on-parent-hover" style="position:relative; width:125%;"><span class="button-group compact children-tooltip" style="position:absolute; top:-40px; left:0;"><a href="javascript://" class="button icon-play orange-gradient" title="<?php echo $lC_Language->get('icon_preview'); ?>" onclick="showImage(\'' + entry[4] + '\', \'' + entry[7] + '\', \'' + entry[8] + '\');"></a><a href="#" class="button icon-marker blue-gradient" onclick="setDefaultImage(\'image_' + entry[0] + '\');" title="<?php echo $lC_Language->get('icon_make_default'); ?>"></a><a href="#" class="button icon-cross red-gradient" onclick="removeImage(\'image_' + entry[0] + '\');" title="<?php echo $lC_Language->get('icon_delete'); ?>"></a></span></div>';
            
            newdiv2 += '</span>';  
            $('#additionalOriginal').append(newdiv2);      
          } else {
            var newdiv2 = '<span id="image_' + entry[0] + '" style="' + style + (( entry[1] == "1" ) ? " clear:both;" : "") + '" onmouseover="' + onmouseover + '" onmouseout="' + onmouseout + '">';
            newdiv2 += '<a href="' + entry[4] + '" target="_blank"><img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/mini/'; ?>' + entry[2] + '" border="0" height="<?php echo $lC_Image->getHeight('mini'); ?>" alt="' + entry[2] + '" title="' + entry[5] + ' bytes" style="max-width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px;" /></a><br />' + entry[3];
            newdiv2 += '<div class="show-on-parent-hover" style="position:relative;"><span class="button-group compact children-tooltip" style="position:absolute; top:-42px; left:23px;"><a href="javascript://" class="button icon-play orange-gradient" title="<?php echo $lC_Language->get('icon_preview'); ?>" onclick="showImage(\'' + entry[4] + '\', \'' + entry[7] + '\', \'' + entry[8] + '\');"></a></div>';
            

            newdiv2 += '</span>';  
            $('#additionalOther').append(newdiv2);
            
          }
        }      
      }
      
      checkImageExists('../images/products/large/' + defaultImage).done(function() {
        $('#imagePreviewContainer').html('<img src="<?php echo '../images/products/large/'; ?>' + defaultImage + '" style="max-width:100%;" />');
      }).fail(function() {
        $('#imagePreviewContainer').html('<img src="<?php echo '../images/no-image.png'; ?>" style="max-width:100%;" />');
      });       
      
      $('#additionalOriginal').sortable({
        update: function(event, ui) {
          $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=reorderImages'); ?>' + '&' + $(this).sortable('serialize'),
            function (data) {
              getImagesOriginals();
              getImagesOthers();
            }
          );
        }
      });  

      setTimeout('_clearProgressIndicators()', 1000);
    }
    
    function _clearProgressIndicators() {
      if ( $('#showProgressOriginal').css('display') != 'none') {
        $('#showProgressOriginal').css('display', 'none');
      }

      if ( $('#showProgressOther').css('display') != 'none') {
        $('#showProgressOther').css('display', 'none');
      }
    }

    function getImages() {
      $('#defaultImages').empty();
      getImagesOriginals(false);
      getImagesOthers(false);

      $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=getImages'); ?>',
        function (data) {   
          showImages(data);
        }
      );
      $(".qq-upload-drop-area").hide();
    }

    function getImagesOriginals(makeCall) {
      $('#additionalOriginal').empty();
      $('#imagePreviewContainer').empty();
      $('#defaultImages').html('<div id="showProgressOriginal" style="float: left; padding-left: 10px;"><span class="loader on-dark small-margin-right"></span><?php echo $lC_Language->get('image_loading_from_server'); ?></div>');
      $('#imagePreviewContainer').html('<p id="showProgressOriginal" align="center" class="large-margin-top"><span class="loader huge refreshing"></span></p>');

      if ( makeCall != false ) {
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=getImages&filter=originals'); ?>',
          function (data) {
            showImages(data);
          }
        );
      }
    }

    function getImagesOthers(makeCall) {
      $('#additionalOther').empty();
      $('#defaultOther').html('<div id="showProgressOther" style="float: left; padding-left: 10px;"><span class="loader on-dark small-margin-right"></span><?php echo $lC_Language->get('image_loading_from_server'); ?></div>');

      if ( makeCall != false ) {
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=getImages&filter=others'); ?>',
          function (data) {
            showImages(data);
          }
        );
      }
    }
                               
    function removeImage(id) {
      $.modal.confirm('<?php echo $lC_Language->get('text_confirm_delete'); ?>', function() {
        var image = id.split('_');
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=deleteProductImage'); ?>' + '&image=' + image[1],
          function (data) {
            getImages();
          }
        );        
      }, function() {
      });
    }
                 
    function setDefaultImage(id) {  
      $.modal.confirm('<?php echo $lC_Language->get('text_confirm_set_default'); ?>', function() {
        var image = id.split('_');
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=setDefaultImage'); ?>' + '&image=' + image[1],
          function (data) {
            getImages();  
            showContent('default');
          }
        ); 
      }, function() {
      })  
    }

    function getLocalImages() {
      $('#showProgressGetLocalImages').css('display', 'inline');

      $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getLocalImages'); ?>',
        function (data) {
          var i = 0;
          var selectList = document.getElementById('localImagesSelection');

          for ( i=selectList.options.length; i>=0; i-- ) {
            selectList.options[i] = null;
          }

          for ( i=0; i<data.entries.length; i++ ) {
            selectList.options[i] = new Option(data.entries[i]);
            selectList.options[i].selected = false;
          }

          $('#showProgressGetLocalImages').css('display', 'none');
        }
      );
    }

    <?php
    if ( isset($pInfo) ) {
      ?>
      function assignLocalImages() {
        $('#showProgressAssigningLocalImages').css('display', 'inline');

        var selectedFiles = '';

        $('#localImagesSelection :selected').each(function(i, selected) {
          selectedFiles += 'files[]=' + $(selected).text() + '&';
        });

        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . (isset($pInfo) ? $pInfo->getInt('products_id') : null) . '&action=assignLocalImages'); ?>' + '&' + selectedFiles,
          function (data) {
            $('#showProgressAssigningLocalImages').css('display', 'none');
            getLocalImages();
            getImages();
          }
        );
      }
      <?php
    }
    ?>

    function switchImageFilesView(layer) {
      /*
      if (layer == 'local') {
        var layer1 = document.getElementById('remoteFiles');
        var layer1link = document.getElementById('remoteFilesLink');
        var layer2 = document.getElementById('localFiles');
        var layer2link = document.getElementById('localFilesLink');
      } else {
        var layer1 = document.getElementById('localFiles');
        var layer1link = document.getElementById('localFilesLink');
        var layer2 = document.getElementById('remoteFiles');
        var layer2link = document.getElementById('remoteFilesLink');
      }

      if ( (layer != 'local') || ((layer == 'local') && (layer1.style.display != 'none')) ) {
        layer1.style.display='none';
        layer2.style.display='inline';
        layer1link.style.fontWeight='normal';
        layer2link.style.fontWeight='bolder';
      } else {
        getLocalImages();
      }
      */
    }
    
    function backToNav() {
      var imagesPanelWidth = $(".panel-content").width();
      //alert(imagesPanelWidth);
      $(".panel-navigation").css('left', '0').css('right', '0');
      $(".panel-content").css('left', imagesPanelWidth);
    } 
    
    /**
    * OPTIONS TAB
    *    
    */ 
    function _setSimpleOptionsSortOrder() {
      var order = 0;
      $('#simpleOptionsTable tr').each(function () {
        var sort = $(this).find('input[class=sort]');
        var td = $(this).find('td[class="sort hide-below-480"]');
        if ($(sort.val()) != undefined) {
          $(sort).val(order.toString());
          $(td).text(order.toString());
          order = parseInt(order) + 10;
        }
      });
    }

    $('input[name=inventory_control_radio_group]').click(function() {
      _updateInvControlType($(this).val());
    });
    $('input[name=inventory_option_control_radio_group]').click(function() {
      _updateInvControlType($(this).val());
    });

    function _updateInvControlType(type) {
      // remomve the active classes
      $('.oicb').removeClass('active');  
      if (type == '1') {
      //  $('#inventory_control_simple').show('300');
      //  $('#inventory_control_multi').hide('300');
        $('label[for=\'ic_radio_1\']').addClass('active');
        $('label[for=\'ioc_radio_1\']').addClass('active'); 
    //    $('#multiSkuContainer').hide();   
     //   $('#simpleOptionsContainer').show();   
      } else if (type == '2') {   
      //  $('#inventory_control_simple').hide('300');
      //  $('#inventory_control_multi').show('300');
        $('label[for=\'ic_radio_1\']').addClass('active');
        $('label[for=\'ioc_radio_1\']').addClass('active'); 
     //   $('#multiSkuContainer').show();   
     //   $('#simpleOptionsContainer').hide();        
      }
    }

    function toggleSimpleOpitonsStatus(e, id) {
      var status = $('#simple_options_group_status_' + id).val();
      if (status == '1') {
        $('#simple_options_group_status_' + id).val('-1');
        $(e).html('<span class="icon-cross icon-size2 icon-red"></span>');
      } else {
        $('#simple_options_group_status_' + id).val('1');
        $(e).html('<span class="icon-tick icon-size2 icon-green"></span>');    
      }
    }

    function removeSimpleOptionsRow(id) {
      $.modal.confirm('<?php echo $lC_Language->get('text_remove_row'); ?>', function() {
          $('#tre-' + id).remove();
          $('.trp-' + id).remove();
        }, function() {
          return false;
      });  
    }

    function toggleSimpleOptionsRow(item) {
      var expand = $(item + '_span').hasClass('icon-squared-plus');
      if (expand) {
        $(item).slideDown();
        $(item + '_span').removeClass('icon-squared-plus').addClass('icon-squared-minus');
      } else {
        $(item).slideUp();
        $(item + '_span').removeClass('icon-squared-minus').addClass('icon-squared-plus');
      }
    }

    function toggleAllSimpleOptionsRows() {
      var expand = $('#toggle-all').hasClass('icon-squared-plus');
      if (expand) {
        $('.dropall').slideDown();
        $('#toggle-all').removeClass('icon-squared-plus').addClass('icon-squared-minus');
        $('.toggle-icon').removeClass('icon-squared-plus').addClass('icon-squared-minus');
      } else {
        $('.dropall').slideUp();
        $('#toggle-all').removeClass('icon-squared-minus').addClass('icon-squared-plus');
        $('.toggle-icon').removeClass('icon-squared-minus').addClass('icon-squared-plus');
      }
    }    
    
    /**
    /* PRICING TAB
    /**  

    // refresh simple option price modifier plus/minus symbols */
    function _refreshSimpleOptionsPricingSymbols() {
      $('#simpleOptionsPricingTable input').each(function(index, element) {
        var id = $(this).attr("id").replace('simple_options_entry_price_modifier_', '');
        showSimpleOptionsPricingSymbol(element, id); 
      });
    }

    // show the plus/minus simple option price modifier symbols
    function showSimpleOptionsPricingSymbol(e, id) {
      var val = $(e).val();
      if (parseFloat(val) >= 0.0000) {
        $('#div_' + id).removeClass('icon-red').removeClass('icon-minus-round').addClass('icon-green').addClass('icon-plus-round');   
      } else {
        $('#div_' + id).removeClass('icon-green').removeClass('icon-plus-round').addClass('icon-red').addClass('icon-minus-round');   
      }
    }

    // update the discount tag displays
    function updatePricingDiscountDisplay() {
      var base = parseFloat($('#products_base_price').val());
      $(".sprice").each( function() {
        var sprice = parseFloat($(this).val());
        var discount = (((base - sprice) / base) * 100).toFixed(<?php echo DECIMAL_PLACES; ?>);
        if (sprice > base) {
          $(this).closest('div.columns').find('.tag').removeClass('blue-bg').addClass('red-bg').html('+' + discount.toString() + '%').blinkf();
        } else {
          $(this).closest('div.columns').find('.tag').removeClass('red-bg').addClass('blue-bg').html('-' + discount.toString() + '%').unblinkf();
        }
      });   
    }

    // update the open/close chevrons on pricing tab 
    function _updatePricingDivChevrons() {
      var gpVisible = $('#groups_pricing_container').is(":visible");
      var qpbVisible = $('#qty_breaks_pricing_container').is(":visible");
      var spVisible = $('#specials_pricing_container').is(":visible");
      var iconOpen = 'icon-chevron-thin-down';
      var iconClose = 'icon-chevron-thin-up';
      if (gpVisible) {
        $('#groups_pricing_container_span').removeClass(iconOpen).addClass(iconClose);
      } else {    
        $('#groups_pricing_container_span').removeClass(iconClose).addClass(iconOpen);
      }
      if (qpbVisible) {
        $('#qty_breaks_pricing_container_span').removeClass(iconOpen).addClass(iconClose);
      } else {    
        $('#qty_breaks_pricing_container_span').removeClass(iconClose).addClass(iconOpen);
      }
      if (spVisible) {
        $('#specials_pricing_container_span').removeClass(iconOpen).addClass(iconClose);
      } else {    
        $('#specials_pricing_container_span').removeClass(iconClose).addClass(iconOpen);
      }    
    }

    // toggle section switches //
    function togglePricingSection(e, section) {
      var divIsOpen = $('#' + section).is(":visible");
      var switchIsEnabled = $(e).parent('.switch').hasClass('checked');
      if (divIsOpen) {
        $('#' + section).slideUp('300');
      } else {
        if (switchIsEnabled && divIsOpen) {
          $('#' + section).slideUp('300');
        } else {
          $('#' + section).slideDown('300');
        }
      }
      
      setTimeout(function() {  
        _updatePricingDivChevrons();
      }, 500);
    }        
    
  </script>
  <?php
} else { // default product listing
  ?>
  <script>
    $(document).ready(function() {
      updateProductFilter();
    });

    function doSelectFunction(e) {
      if (e.value == 'delete') {
        batchDelete();
      } else if (e.value == 'copy') {
        batchCopy();
      }
    }

    function updateProductFilter() {
      var cid = $("#cid").val();
      var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
      var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA&cid=CID'); ?>';
      var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getCategoriesArray&cid=CID'); ?>';
      $.getJSON(jsonLink.replace('CID', cid),
        function (data) {
          if (data.rpcStatus == -10) { // no session
            var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
            $(location).attr('href',url);
          }
          if (data.rpcStatus != 1) {
            $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
            return false;
          }
          $("#cid").empty();
          $.each(data.categoriesArray, function(val, text) {
            var selected = (cid == val) ? 'selected="selected"' : '';
            $("#cid").append(
              $("<option " + selected + "></option>").val(val).html(text)
            );
          });
          oTable = $('#dataTable').dataTable({
            "bProcessing": true,
            "sAjaxSource": dataTableDataURL.replace('CID', cid).replace('MEDIA', $.template.mediaQuery.name),
            "sPaginationType": paginationType,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "bDestroy": true,
            "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck hide-on-mobile" },
                          { "sWidth": "50%", "bSortable": true, "sClass": "dataColProducts" },
                          { "sWidth": "15%", "bSortable": true, "sClass": "dataColPrice hide-on-mobile-portrait" },
                          { "sWidth": "10%", "bSortable": true, "sClass": "dataColQty hide-on-tablet" },
                          { "sWidth": "25%", "bSortable": false, "sClass": "dataColAction" }]
          }); 
          $('#dataTable').responsiveTable();
               
          if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
            $('#main-title > h1').attr('style', 'font-size:1.8em;');
            $('#main-title').attr('style', 'padding: 0 0 0 20px;');
            $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
            $('#dataTable_length').hide();
            $('#floating-button-container').hide();
            $('#actionText').hide();
            $('.on-mobile').show();
            $('.selectContainer').hide();
          } else {
            // instantiate floating menu
            $('#floating-menu-div-listing').fixFloat();
          }          
        }
      );
    }
  </script>
  <?php
}
?>