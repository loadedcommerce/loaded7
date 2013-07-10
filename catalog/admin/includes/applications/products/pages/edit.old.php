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

if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
  $lC_ObjectInfo = new lC_ObjectInfo(lC_Products_Admin::get($_GET[$lC_Template->getModule()]));
  $attributes = $lC_ObjectInfo->get('attributes');
  $Qpd = $lC_Database->query('select products_name, products_description, products_keyword, products_tags, products_url, language_id from :table_products_description where products_id = :products_id');
  $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
  $Qpd->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
  $Qpd->execute();
  $products_name = array();
  $products_description = array();
  $products_keyword = array();
  $products_tags = array();
  $products_url = array();
  while ($Qpd->next()) {
    $products_name[$Qpd->valueInt('language_id')] = $Qpd->value('products_name');
    $products_description[$Qpd->valueInt('language_id')] = $Qpd->value('products_description');
    $products_keyword[$Qpd->valueInt('language_id')] = $Qpd->value('products_keyword');
    $products_tags[$Qpd->valueInt('language_id')] = $Qpd->value('products_tags');
    $products_url[$Qpd->valueInt('language_id')] = $Qpd->value('products_url');
  }
}
// get tax class
$Qtc = $lC_Database->query('select tax_class_id, tax_class_title from :table_tax_class order by tax_class_title');
$Qtc->bindTable(':table_tax_class', TABLE_TAX_CLASS);
$Qtc->execute();
$tax_class_array = array(array('id' => '0',
                               'text' => $lC_Language->get('none')));
while ($Qtc->next()) {
  $tax_class_array[] = array('id' => $Qtc->valueInt('tax_class_id'),
                             'text' => $Qtc->value('tax_class_title'));
}
$tax_options_string = '';
foreach($tax_class_array as $value) {
  $tax_options_string .= '<option value=\'' . $value['id'] . '\'>' . $value['text'] . '</option>';
}
// get weight class
$Qwc = $lC_Database->query('select weight_class_id, weight_class_title from :table_weight_class where language_id = :language_id order by weight_class_title');
$Qwc->bindTable(':table_weight_class', TABLE_WEIGHT_CLASS);
$Qwc->bindInt(':language_id', $lC_Language->getID());
$Qwc->execute();
$weight_class_array = array();
while ($Qwc->next()) {
  $weight_class_array[] = array('id' => $Qwc->valueInt('weight_class_id'),
                                'text' => $Qwc->value('weight_class_title'));
}
// get customer groups
$customer_groups_array = array_merge(array(array('id' => '9999', 'text' => 'All Groups')), lc_get_customer_groups_array());
$groups_options_string = '';
foreach($customer_groups_array as $key => $value) {
  $groups_options_string .= '<option value=\'' . $value['id'] . '\'>' . $value['text'] . '</option>';
}
// get price breaks
$rowCnt = 0;
if ( isset($lC_ObjectInfo) ) {
  $Qpb = $lC_Database->query('select group_id, tax_class_id, qty_break, price_break from :table_products_pricing where products_id = :products_id order by group_id, qty_break');
  $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
  $Qpb->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
  $Qpb->execute();

  $editPBEntry = '';
  $rowCnt = 1;
  while ($Qpb->next()) {
    if (isset($Qpb->result)) {
      $editPBEntry .= "<tr id='row"  . $rowCnt . "'><td width='100px'><select style='width:98%' name='price_breaks[group_id][]'>" . getCustomerGroupOptionsString($Qpb->valueInt('group_id'), true) . "</select></td>" .
                      "<td width='100px'><select style='width:98%' name='price_breaks[tax_class_id][]'>" . getTaxClassOptionsString($Qpb->valueInt('tax_class_id'), true) . "</select></td>" .
                      "<td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' value='" . $Qpb->valueInt('qty_break') . "' name='price_breaks[qty][]' size='5' id='qty" . $rowCnt . "'></td><td width='70%'>" . $lC_Language->get('price_breaks_above') . "</td></tr></table></td>" .
                      "<td width='80px'><input value='" . ((isset($lC_ObjectInfo)) ? number_format($Qpb->valueDecimal('price_break'), DECIMAL_PLACES) : null) . "' style='width:70px;' type='text' name='price_breaks[price][]' id='price" . $rowCnt . "'></td>" .
                      "<td width='30px' align='center'><a id='row" . $rowCnt . "e' href='javascript://' onclick='removePriceBreakEntry(this);'><img border='0' src='images/icons/cross.png'></a></td></tr>";
      $rowCnt++;
    }
  }
}

function getCustomerGroupOptionsString($id = null, $esc = false) {
  global $customer_groups_array;
  $options_string = '';
  foreach($customer_groups_array as $value) {
    $options_string_esc .= '<option value=\'' . $value['id'] . '\' ' . (($id == $value['id']) ? 'selected=\'selected\'' : '') . '>' . $value['text'] . '</option>';
    $options_string .= '<option value="' . $value['id'] . '" ' . (($id == $value['id']) ? 'selected="selected"' : '') . '">' . $value['text'] . '</option>';
  }
  if ($esc) {
    return $options_string_esc;
  } else {
    return $options_string;
  }
}

function getTaxClassOptionsString($id = null, $esc = false) {
  global $tax_class_array;
  $options_string = '';
  foreach($tax_class_array as $value) {
    $options_string_esc .= '<option value=\'' . $value['id'] . '\' ' . (($id == $value['id']) ? 'selected=\'selected\'' : '') . '>' . $value['text'] . '</option>';
    $options_string .= '<option value="' . $value['id'] . '" ' . (($id == $value['id']) ? 'selected="selected"' : '') . '">' . $value['text'] . '</option>';
  }
  if ($esc) {
    return $options_string_esc;
  } else {
    return $options_string;
  }
}
?>
<script type="text/javascript" src="../ext/tiny_mce/tiny_mce.js"></script>
<script>
tinyMCE.init({
  mode : "none",
  theme : "advanced",
  language : "<?php echo substr($lC_Language->getCode(), 0, 2); ?>",
  height : "400",
  theme_advanced_toolbar_align : "left",
  theme_advanced_toolbar_location : "top",
  theme_advanced_statusbar_location : "bottom",
  cleanup : false,
  plugins : "style,layer,table,advimage,advlink,preview,contextmenu,paste,fullscreen,visualchars",
  theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,fontselect,fontsizeselect,bullist,numlist,separator,outdent,indent",
  theme_advanced_buttons2 : "undo,redo,separator,link,unlink,anchor,image,code,separator,preview,separator,forecolor,backcolor,tablecontrols,separator,hr,removeformat,visualaid",
  theme_advanced_buttons3 : "sub,sup,separator,charmap,fullscreen,separator,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,visualchars"
});

function toggleEditor(id) {
  if ( !tinyMCE.get(id) ) {
    tinyMCE.execCommand('mceAddControl', false, id);
  } else {
    tinyMCE.execCommand('mceRemoveControl', false, id);
  }
}
</script>
<?php
  if (isset($_SESSION['error'])) unset($_SESSION['error']);
  if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']);
  $lC_Template->loadModal($lC_Template->getModule());
?>
<!-- End main content -->
<script><!--
  var tax_rates = new Array();
  <?php
  foreach ($tax_class_array as $tc_entry) {
    if ( $tc_entry['id'] > 0 ) {
      echo '  tax_rates["' . $tc_entry['id'] . '"] = ' . $lC_Tax->getTaxRate($tc_entry['id']) . ';' . "\n";
    }
  }
  ?>

  function doRound(x, places) {
    return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
  }

  function getTaxRate(fieldcounter) {
    var selected_value = document.getElementById('tax_class' + fieldcounter).selectedIndex;
    var parameterVal = document.getElementById('tax_class' + fieldcounter).options[selected_value].value;

    if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
      return tax_rates[parameterVal];
    } else {
      return 0;
    }
  }

  function updatePrice(field) {
    var modifiedPrice = 0.00;
    var basePrice = Number(<?php echo (isset($lC_ObjectInfo)) ? $lC_ObjectInfo->get('products_price') : 0; ?>);
    var adjValue = Number(document.getElementById('variants_price_adj' + field).value);

    modifiedPrice = doRound(basePrice + adjValue, <?php echo DECIMAL_PLACES; ?>);
    document.getElementById('variants_price_mod' + field).value = modifiedPrice.toFixed(<?php echo DECIMAL_PLACES; ?>);
  }

  function updateGross(field) {
    var fieldcounter = field.substring(14);
    var taxRate = getTaxRate(fieldcounter);
    var grossValue = document.getElementById(field).value;

    if (taxRate > 0) {
      grossValue = grossValue * ((taxRate / 100) + 1);
    }
    grossValue = doRound(grossValue, <?php echo DECIMAL_PLACES; ?>);
    document.getElementById(field + '_gross').value = grossValue.toFixed(<?php echo DECIMAL_PLACES; ?>);
  }

  function updateNet(field) {
    var fieldcounter = field.substring(14);
    var taxRate = getTaxRate(fieldcounter);
    var netValue = document.getElementById(field + "_gross").value;

    if (taxRate > 0) {
      netValue = netValue / ((taxRate / 100) + 1);
    }
    document.getElementById(field).value = doRound(netValue, <?php echo DECIMAL_PLACES; ?>);
  }

  var variants_groups = new Array();
  var variants_values = new Array();

  <?php
  $Qvgroups = $lC_Database->query('select id, title, module from :table_products_variants_groups where languages_id = :languages_id order by sort_order, title');
  $Qvgroups->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
  $Qvgroups->bindInt(':languages_id', $lC_Language->getID());
  $Qvgroups->execute();

  while ( $Qvgroups->next() ) {
    echo 'variants_groups[' . $Qvgroups->valueInt('id') . '] = new Array();' .
         'variants_groups[' . $Qvgroups->valueInt('id') . '][\'title\'] = \'' . $Qvgroups->valueProtected('title') . '\';' .
         'variants_groups[' . $Qvgroups->valueInt('id') . '][\'multiple\'] = ' . (lC_Variants::allowsMultipleValues($Qvgroups->value('module')) ? 'true' : 'false') . ';';
  }

  $Qvvalues = $lC_Database->query('select id, title from :table_products_variants_values where languages_id = :languages_id order by sort_order, title');
  $Qvvalues->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
  $Qvvalues->bindInt(':languages_id', $lC_Language->getID());
  $Qvvalues->execute();

  while ($Qvvalues->next()) {
    echo 'variants_values[' . $Qvvalues->valueInt('id') . '] = \'' . $Qvvalues->valueProtected('title') . '\';';
  }
  ?>

  var variants = new Array();
  var variants_default_combo = null;
  var variant_selected = null;
  var variants_counter = 1;

  function moreFields() {
    if (variant_selected == null) {
      addVariant();
    }
    if (variants[variant_selected][document.product.variantGroups.options[document.product.variantGroups.options.selectedIndex].parentNode.id] == undefined) {
      variants[variant_selected][document.product.variantGroups.options[document.product.variantGroups.options.selectedIndex].parentNode.id] = new Array();
    }
    if (variants_groups[document.product.variantGroups.options[document.product.variantGroups.options.selectedIndex].parentNode.id]['multiple'] == false) {
      variants[variant_selected][document.product.variantGroups.options[document.product.variantGroups.options.selectedIndex].parentNode.id] = new Array();
    }
    variants[variant_selected][document.product.variantGroups.options[document.product.variantGroups.options.selectedIndex].parentNode.id][document.product.variantGroups.options[document.product.variantGroups.options.selectedIndex].value] = document.product.variantGroups.options[document.product.variantGroups.options.selectedIndex].value;

    var spanFields = document.getElementById('variant' + variant_selected).getElementsByTagName('span');
    var variant_string = '';
    var variant_combo_string = '';

    for (i=0; i<variants[variant_selected].length; i++) {
      if (variants[variant_selected][i] != undefined) {
        for (y=0; y<variants[variant_selected][i].length; y++) {
          if (variants[variant_selected][i][y] != undefined) {
            variant_string += variants_groups[i]['title'] + ': ' + variants_values[variants[variant_selected][i][y]] + ', ';
            variant_combo_string += i + '_' + variants[variant_selected][i][y] + ';';
          }
        }
      }
    }

    if (variant_string != '') {
      variant_string = variant_string.substring(0, variant_string.length-2);
      variant_combo_string = variant_combo_string.substring(0, variant_combo_string.length-1);
    }
    spanFields[0].innerHTML = '<?php echo lc_icon_admin('attach.png') . '&nbsp;'; ?>' + variant_string;
    document.getElementById('variants_combo_' + variant_selected).value = variant_combo_string;
  }

  function addVariant() {
    if ( variants_values.length < 1 ) {
      return false;
    }

    var newFields = document.getElementById('readroot').cloneNode(true);
    newFields.id = 'variant' + variants_counter;
    var vp_holder = 'variants_price' + variants_counter;
    var aFields = newFields.getElementsByTagName('a');
    var inputFields = newFields.getElementsByTagName('input');
    var selectFields = newFields.getElementsByTagName('select');
    var images = newFields.getElementsByTagName('img');

    for (y=0; y<aFields.length; y++) {
      if (aFields[y].name == 'trash') {
        aFields[y].href = "javascript:removeVariant('variant" + variants_counter + "');";
      } else if (aFields[y].name == 'default') {
        aFields[y].href = "javascript:setDefaultVariant('" + variants_counter + "');";
      }
    }

    for (y=0; y<inputFields.length; y++) {
      if (inputFields[y].name == 'new_variants_price') {
        inputFields[y].id = inputFields[y].name.substr(4) + variants_counter;
        inputFields[y].onkeyup = function() { updateGross(vp_holder) };
      } else if (inputFields[y].name == 'new_variants_price_gross') {
        inputFields[y].id = 'variants_price' + variants_counter + '_gross';
        inputFields[y].onkeyup = function() { updateNet(vp_holder) };
      } else {
        inputFields[y].id = inputFields[y].name.substr(4) + '_' + variants_counter;
      }

      inputFields[y].name = inputFields[y].name.substr(4) + '[' + variants_counter + ']';
      inputFields[y].disabled = false;
    }

    for (y=0; y<selectFields.length; y++) {
      if (selectFields[y].name == 'new_variants_tax_class_id') {
        selectFields[y].id = 'tax_class' + variants_counter;
        selectFields[y].onchange = function() { updateGross(vp_holder) };
      } else {
        selectFields[y].id = selectFields[y].name.substr(4) + '_' + variants_counter;
      }

      selectFields[y].name = selectFields[y].name.substr(4) + '[' + variants_counter + ']';
      selectFields[y].disabled = false;
    }

    for (y=0; y<images.length; y++) {
      if (images[y].name == 'vdcnew') {
        images[y].id = 'vdc' + variants_counter;
      }
    }

    document.getElementById('writeroot').insertBefore(newFields, document.getElementById('writeroot').firstChild);
    newFields.className = 'variantActive';

    if (variant_selected != null) {
      document.getElementById('variant' + variant_selected).className = 'attributeAdd';
    }
    if (variants_default_combo == null) {
      setDefaultVariant(variants_counter);
    }

    newFields.style.display = 'block';
    variant_selected = variants_counter;
    variants[variant_selected] = new Array();

    $("#qty-pricing-grid-variant").attr('id', 'qty-pricing-grid-variant' + variants_counter);
    $("#newpbid").attr('id', 'newpbid' + variants_counter);
    $("#variantTableContent").attr('id', 'variantTableContent' + variants_counter);

    for (i=0; i < variants_counter; i++) {
      if (i != variant_selected) {
        $("#variantTableContent" + i).hide();
      }
    }

    variants_counter++;
  }

  var being_removed = false;

  function removeVariant(id) {
    being_removed = true;
    var to_remove = id.substr(7);

    document.getElementById('writeroot').removeChild(document.getElementById(id));
    if (to_remove == variant_selected) {
      variant_selected = null;
    }
  }

  function activateVariant(element) {
    if (being_removed == true) {
      being_removed = false;
      return true;
    }

    var to_activate = element.id.substr(7);

    if (to_activate != variant_selected) {
      if (variant_selected != null) {
        document.getElementById('variant' + variant_selected).className = 'attributeAdd';
      }
      element.className = 'variantActive';
      variant_selected = to_activate;
      toggleVariantTableContent();
    }

  }

  function toggleVariantTableContent() {
    for (i=0; i < variants_counter; i++) {
      if (i != variant_selected) {
        $("#variantTableContent" + i).hide();
      } else {
        $("#variantTableContent" + i).show();
      }
    }
  }

  function setDefaultVariant(id) {
    if ( id != variants_default_combo ) {
      document.getElementById('variants_default_combo').value = id;
      document.getElementById('vdc' + id).src = "<?php echo lc_icon_admin_raw('default.png'); ?>";
      if (variants_default_combo != null) {
        document.getElementById('vdc' + variants_default_combo).src = "<?php echo lc_icon_admin_raw('default_grey.png'); ?>";
      }
      variants_default_combo = id;
    }
  }
  <?php
  if ( isset($lC_ObjectInfo) ) {
    ?>
    
    function removeImage(id) {
      $.modal.confirm('Confirm Delete?', function() {
        var image = id.split('_');
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=deleteProductImage'); ?>' + '&image=' + image[1],
          function (data) {
            getImages();
          }
        );        
      }, function() {
      });
    }
    
    function setDefaultImage(id) {
      var image = id.split('_');

      $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=setDefaultImage'); ?>' + '&image=' + image[1],
        function (data) {
          getImagesOriginals();
        }
      );
    }

    function showImages(data) {
      for ( i=0; i<data.entries.length; i++ ) {
        var entry = data.entries[i];

        var style = 'width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px; padding: 10px; float: left; text-align: center;';

        if ( entry[1] == '1' ) { // original (products_images_groups_id)
          var onmouseover = 'this.style.backgroundColor=\'#EFEBDE\'; this.style.backgroundImage=\'url(<?php echo lc_href_link_admin('templates/' . $lC_Template->getCode() . '/img/icons/16/drag.png'); ?>)\'; this.style.backgroundRepeat=\'no-repeat\'; this.style.backgroundPosition=\'0 0\';';

          if ( entry[6] == '1' ) { // default_flag
            style += ' background-color: #E5EFE5;';

            var onmouseout = 'this.style.backgroundColor=\'#E5EFE5\'; this.style.backgroundImage=\'none\';';
          } else {
            var onmouseout = 'this.style.backgroundColor=\'#FFFFFF\'; this.style.backgroundImage=\'none\';';
          }
        } else {
          var onmouseover = 'this.style.backgroundColor=\'#EFEBDE\';';
          var onmouseout = 'this.style.backgroundColor=\'#FFFFFF\';';
        }

        var newdiv = '<span id="image_' + entry[0] + '" style="' + style + '" onmouseover="' + onmouseover + '" onmouseout="' + onmouseout + '">';
        newdiv += '<a href="' + entry[4] + '" target="_blank"><img src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/mini/'; ?>' + entry[2] + '" border="0" height="<?php echo $lC_Image->getHeight('mini'); ?>" alt="' + entry[2] + '" title="' + entry[2] + '" style="max-width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px;" /></a><br />' + entry[3] + '<br />' + entry[5] + ' bytes<br />';

        if ( entry[1] == '1' ) {
          if ( entry[6] == '1' ) {
            newdiv += '<?php echo lc_icon_admin('default.png'); ?>&nbsp;';
          } else {
            newdiv += '<a href="#" onclick="setDefaultImage(\'image_' + entry[0] + '\');"><?php echo lc_icon_admin('default_grey.png'); ?></a>&nbsp;';
          }

          newdiv += '<a href="#" onclick="removeImage(\'image_' + entry[0] + '\');"><?php echo lc_icon_admin('trash.png'); ?></a>';
        }

        newdiv += '</span>';

        if ( entry[1] == '1' ) {
          $('#imagesOriginal').append(newdiv);
        } else {
          $('#imagesOther').append(newdiv);
        }
      }

      $('#imagesOriginal').sortable( {
        update: function(event, ui) {
          $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=reorderImages'); ?>' + '&' + $(this).sortable('serialize'),
            function (data) {
              getImagesOthers();
            }
          );
        }
      } );

      if ( $('#showProgressOriginal').css('display') != 'none') {
        $('#showProgressOriginal').css('display', 'none');
      }

      if ( $('#showProgressOther').css('display') != 'none') {
        $('#showProgressOther').css('display', 'none');
      }
    }

    function getImages() {
      getImagesOriginals(false);
      getImagesOthers(false);

      $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=getImages'); ?>',
        function (data) {
          showImages(data);
        }
      );
    }

    function getImagesOriginals(makeCall) {
      $('#imagesOriginal').html('<div id="showProgressOriginal" style="float: left; padding-left: 10px;"><?php echo lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('images_loading_from_server'); ?></div>');

      if ( makeCall != false ) {
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=getImages&filter=originals'); ?>',
          function (data) {
            showImages(data);
          }
        );
      }
    }

    function getImagesOthers(makeCall) {
      $('#imagesOther').html('<div id="showProgressOther" style="float: left; padding-left: 10px;"><?php echo lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('images_loading_from_server'); ?></div>');

      if ( makeCall != false ) {
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=getImages&filter=others'); ?>',
          function (data) {
            showImages(data);
          }
        );
      }
    }

    function assignLocalImages() {
      $('#showProgressAssigningLocalImages').css('display', 'inline');

      var selectedFiles = '';

      $('#localImagesSelection :selected').each(function(i, selected) {
        selectedFiles += 'files[]=' + $(selected).text() + '&';
      });

      $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=assignLocalImages'); ?>' + '&' + selectedFiles,
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

  function switchImageFilesView(layer) {
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
      layer1link.style.backgroundColor='';
      layer2link.style.backgroundColor='#E5EFE5';
    } else {
      getLocalImages();
    }
  }
  // qty pricing
  function updatePriceBreakFields() {
    $("#qty-pricing-grid > tbody").append("<?php  echo $editPBEntry; ?>");
  }

  function addPriceBreakFields() {
    var id = $("#pbid").val();
    var newPBEntry = "<tr id='row" + id + "'><td width='100px'><select style='width:98%' name='price_breaks[group_id][]'><?php echo $groups_options_string; ?></select></td>" +
                     "    <td width='100px'><select style='width:98%' name='price_breaks[tax_class_id][]'><?php echo $tax_options_string; ?></select></td>" +
                     "    <td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' name='price_breaks[qty][]' size='5' id='qty" + id + "'></td><td width='70%'><?php echo $lC_Language->get('price_breaks_above'); ?></td></tr></table></td>" +
                     "    <td width='80px'><input value='<?php echo (isset($lC_ObjectInfo)) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null; ?>' style='width:70px;' type='text' name='price_breaks[price][]' id='price" + id + "'></td>" +
                     "    <td width='30px' align='center'><a href='javascript://' onClick='$(\"#row" + id + "\").remove(); return false;'><img border='0' src='images/icons/cross.png'></a></td></tr>";
    $("#qty-pricing-grid > tbody").append(newPBEntry);
    id++;
    $("#pbid").val(id);
  }

  function addPriceBreakFieldsNew() {
    var id = variants_counter -1;
    if (id < 1) id = 1;
    var selected = variant_selected;
    if (selected != id) id = selected;

    var row = $("#newpbid" + id).val();
    var newPBEntry = "<tr id='row-" + id + '-' + row + "'><td width='100px'><select style='width:98%' name='price_breaks[group_id][" + id + "][]' id='price_breaks[group_id][" + id + "][]'><?php echo $groups_options_string; ?></select></td>" +
                     "    <td width='100px'><select style='width:98%' name='price_breaks[tax_class_id][" + id + "][]' id='price_breaks[tax_class_id][" + id + "][]'><?php echo $tax_options_string; ?></select></td>" +
                     "    <td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' name='price_breaks[qty][" + id + "][]' id='price_breaks[qty][" + id + "][]' size='5' id='qty" + id + "'></td><td width='70%'><?php echo $lC_Language->get('price_breaks_above'); ?></td></tr></table></td>" +
                     "    <td width='80px'><input value='<?php echo (isset($lC_ObjectInfo)) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null; ?>' style='width:70px;' type='text' name='price_breaks[price][" + id + "][]' id='price_breaks[price][" + id + "][]'></td>" +
                     "    <td width='30px' align='center'><a href='javascript://' onClick='$(\"#row-" + id + "-" + row + "\").remove(); return false;'><img border='0' src='images/icons/cross.png'></a></td></tr>";
    $("#qty-pricing-grid-variant" + id + " > tbody").append(newPBEntry);
    row++;
    $("#newpbid" + id).val(row);
  }

  function removePriceBreakEntry(element) {
    var currentId = $(element).attr('id').replace('e','');
    $("#" + currentId).remove();
  }
//--></script>

<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo (isset($lC_ObjectInfo) && isset($products_name[$lC_Language->getID()])) ? $products_name[$lC_Language->getID()] : $lC_Language->get('heading_title_new_product'); ?></h1>
    <?php
    if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
      echo $lC_MessageStack->get($lC_Template->getModule());
    }
    ?>
  </hgroup>
  <style>
    .attributeAdd {
      border: 1px solid #7f9db9;
      background-color: #F0F1F1;
      margin: 2px;
    }

    .variantActive {
      border: 1px solid #7f9db9;
      background-color: #E8FFC6;
      margin: 2px;
    }

    .variantLine {
      color: #D3D3D3;
      background-color: #D3D3D3;
      height: 1px;
    }
    
    form.dataForm fieldset legend { padding: 3px 5px; border-bottom: 1px solid black; font-weight: bold; width: 99%; }
    LABEL { font-weight:bold; }
    TD { padding: 5px 0 0 5px; }
  </style>
  <div class="with-padding-no-top">
    <form name="product" id="product" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('products_id') : '') . '&cID=' . $_GET['cID'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div class="standard-tabs same-height">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li id="tabHeaderSectionDataContent"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
          <li><?php echo lc_link_object('#section_images_content', $lC_Language->get('section_images')); ?></li>
          <li><?php echo lc_link_object('#section_categories_content', $lC_Language->get('section_categories')); ?></li>
        </ul>
        <div class="clearfix tabs-content">
          <div id="section_general_content" class="with-padding">
            <div id="languageTabs" class="standard-tabs same-height">
              <ul class="tabs">
                <?php
                foreach ( $lC_Language->getAll() as $l ) {
                  echo '<li>' . lc_link_object('#languageTabs_' . $l['code'], $lC_Language->showImage($l['code']) . '&nbsp;' . $l['name']) . '</li>';
                }
                ?>
              </ul>
              <div class="clearfix tabs-content with-padding">
                <?php
                foreach ( $lC_Language->getAll() as $l ) {
                  ?>
                  <div id="languageTabs_<?php echo $l['code']; ?>">
                    <fieldset>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_name[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_name'); ?></label><?php echo lc_draw_input_field('products_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_name[$l['id']]) ? $products_name[$l['id']] : null), 'class="required input float-right" style="width:95%;"'); ?></p>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_description[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_description'); ?></label><?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 4, 'class="required input float-right" style="width:95%;"'); ?><div style="width:99%; text-align: right;"><?php echo '<a href="javascript:toggleEditor(\'products_description[' . $l['id'] . ']\');">' . $lC_Language->get('toggle_html_editor') . '</a>'; ?></div></p>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_keyword[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_keyword'); ?></label><?php echo lc_draw_input_field('products_keyword[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_keyword[$l['id']]) ? $products_keyword[$l['id']] : null), 'class="input float-right" style="width:95%" id="keyword' . $l['id'] . '"'); ?></p>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_tags[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_tags'); ?></label><?php echo lc_draw_input_field('products_tags[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_tags[$l['id']]) ? $products_tags[$l['id']] : null), 'class="input float-right" style="width:95%" maxlength="255"'); ?></p>
                      <p class="button-height inline-small-label"><label class="label" for="<?php echo 'products_url[' . $l['id'] . ']'; ?>"><?php echo $lC_Language->get('field_url'); ?></label><?php echo lc_draw_input_field('products_url[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_url[$l['id']]) ? $products_url[$l['id']] : null), 'class="input float-right" style="width:95%;"'); ?></p>
                    </fieldset>
                  </div><div class="clear-both"></div>
                  <?php
                }
                ?>
              </div>
            </div>
            <fieldset>
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr><td>&nbsp;</td></tr>
                <?php
                $Qattributes = $lC_Database->query('select id, code from :table_templates_boxes where modules_group = :modules_group order by code');
                $Qattributes->bindTable(':table_templates_boxes');
                $Qattributes->bindValue(':modules_group', 'product_attributes');
                $Qattributes->execute();
                while ( $Qattributes->next() ) {
                  $module = basename($Qattributes->value('code'));
                  if ( !class_exists('lC_ProductAttributes_' . $module) ) {
                    if ( file_exists(DIR_FS_ADMIN . 'includes/modules/product_attributes/' . $module . '.php') ) {
                      include(DIR_FS_ADMIN . 'includes/modules/product_attributes/' . $module . '.php');
                    }
                  }
                  if ( class_exists('lC_ProductAttributes_' . $module) ) {
                    $module = 'lC_ProductAttributes_' . $module;
                    $module = new $module();
                    ?>
                    <tr class="with-padding">
                      <td width="150px" style="font-weight:bold;"><?php echo $module->getTitle() . ':'; ?></td>
                      <td><?php echo $module->setFunction((isset($attributes[$Qattributes->valueInt('id')]) ? $attributes[$Qattributes->valueInt('id')] : null)); ?></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <?php
                  }
                }
                ?>
              </table>
            </fieldset>
            <script type="text/javascript">
            $(document).ready(function() {
              var pid = '<?php echo $_GET[$lC_Template->getModule()]; ?>';
              var jsonVKUrl = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=validateKeyword&pid=PID'); ?>';
              $("#product").validate({

                invalidHandler: function() {
                  $("#checkAllTabs").html('<?php echo $lC_Language->get('ms_error_check_all_lang_tabs'); ?>').fadeIn('fast').delay(2000).fadeOut('slow');
                },
                rules: {
                  <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                    ?>
                    "products_keyword[<?php echo $l['id']; ?>]": {
                      required: true,
                      remote: jsonVKUrl.replace('PID', pid),

                    },
                    <?php
                  }
                  ?>
                },
                messages: {
                  <?php
                  foreach ( $lC_Language->getAll() as $l ) {
                    ?>
                    "products_keyword[<?php echo $l['id']; ?>]": "<?php echo $lC_Language->get('ms_error_product_keyword_exists'); ?>",
                    <?php
                  }
                  ?>
                }
               });
               <?php
               if ( isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ) {
                 ?>
                 $("#has_variants").attr('checked', true);
                 <?php
               }
               ?>
               //$( "button, input:submit, a", ".ui-dialog-buttonset" ).button();
             });
            </script>
          </div>
          <div id="section_data_content" class="with-padding">

            <p class="button-height">
              <label for="has_variants" class="label" style="display:inline !important;" for="has_variants">Has Variants?</label>
              <?php echo lc_draw_checkbox_field('has_variants', null, null, 'onchange="toggleDataDiv();" class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?>
            </p>
            
            <div id="noVariants" style="display:none;">
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="50%" height="100%" valign="top">
                      <fieldset style="height: 100%;">
                        <p class="button-height">
                          <label for="tax_class0" class="label"><?php echo $lC_Language->get('field_tax_class'); ?></label>
                          <?php echo lc_draw_pull_down_menu('products_tax_class_id', $tax_class_array, (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('products_tax_class_id') : null), 'class="input with-small-padding" style="width:77%; float:right; margin-right:20px;" id="tax_class0" onchange="updateGross(\'products_price0\');"'); ?>
                        </p>
                        <p class="button-height">
                          <label for="products_price0" class="label"><?php echo $lC_Language->get('field_price_net'); ?></label>
                          <?php echo lc_draw_input_field('products_price', (isset($lC_ObjectInfo) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null), 'class="input" style="width:73%; float:right; margin-right:20px;" id="products_price0" onkeyup="updateGross(\'products_price0\')"'); ?>
                        </p>
                        <p class="button-height">
                          <label for="products_price0_gross" class="label"><?php echo $lC_Language->get('field_price_gross'); ?></label>
                          <?php echo lc_draw_input_field('products_price_gross', (isset($lC_ObjectInfo) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null), 'class="input" style="width:73%; float:right; margin-right:20px;" id="products_price0_gross" onkeyup="updateNet(\'products_price0\')" READONLY'); ?>
                        </p>

                        <div>
                          <label for="qty_price_breaks" class="label" style="padding:3px 0;"><?php echo $lC_Language->get('field_qty_price_breaks'); ?></label>
                          <table style="margin: 10px 20px 0 0; border:1px solid #7f9db9;" id="qty-pricing-grid" summary="Qty Price Breaks"  width="96%" border="1" cellpadding="2" cellspacing="0">
                            <thead style="height:24px;" class="ui-widget-header">
                              <tr style="padding-top:5px">
                                <th align="left" width="90px"><?php echo $lC_Language->get('table_heading_qpb_customer_group'); ?></th>
                                <th align="left" width="90px"><?php echo $lC_Language->get('table_heading_qpb_tax_class'); ?></th>
                                <th align="left" width="100px">&nbsp;<?php echo $lC_Language->get('table_heading_qpb_qty'); ?></th>
                                <th align="left" width="70px"><?php echo $lC_Language->get('table_heading_qpb_net_price'); ?></th>
                                <th align="center" width="20px">&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot><input type="hidden" id="pbid" value="1"></tfoot>
                          </table>
                          <div style="margin:5px 0;">
                            <div style="float:right; margin-right:20px;">
                              <button onclick="addPriceBreakFields(); return false;" class="button blue-gradient glossy" /><?php echo $lC_Language->get('button_add_price_break'); ?></button>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </td>
                    <td width="50%" height="100%" valign="top">
                      <fieldset>
                        <p class="button-height">
                          <label style="display:inline !important;" for="products_status"><?php echo $lC_Language->get('field_active'); ?></label>
                          <span style="float:right; margin-right:62%;"><?php echo lc_draw_checkbox_field('products_status', null, ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == 1) ? true : false), 'class="switch medium" data-text-on="' . strtoupper($lC_Language->get('button_yes')) . '" data-text-off="' . strtoupper($lC_Language->get('button_no')) . '"'); ?></span>
                        </p>
                        <p class="button-height">
                          <label for="products_model" class="label"><?php echo $lC_Language->get('field_model'); ?></label>
                          <?php echo lc_draw_input_field('products_model', (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_model') : null), 'class="input" style="width:73%; float:right;"'); ?>
                        </p>
                        <p class="button-height">
                          <label for="products_quantity" class="label"><?php echo $lC_Language->get('field_quantity'); ?></label>
                          <?php echo lc_draw_input_field('products_quantity', (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_quantity') : null), 'class="input" style="width:73%; float:right;"'); ?>
                        </p>
                        <p class="button-height">
                          <label for="products_weight" class="label"><?php echo $lC_Language->get('field_weight'); ?></label>
                          <?php echo lc_draw_input_field('products_weight', (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_weight') : null), 'class="input" style="width:73%; float:right;"'); ?>
                        </p>
                        <p class="button-height">
                          <label for="products_weight_class" class="label"><?php echo $lC_Language->get('field_weight_class'); ?></label>
                          <?php echo lc_draw_pull_down_menu('products_weight_class', $weight_class_array, (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_weight_class') : SHIPPING_WEIGHT_UNIT), 'class="input with-small-padding" style="width:77%; float:right;"'); ?>
                        </p>
                      </fieldset>
                    </td>
                  </tr>
                </table>
            </div>
            <div id="hasVariants" style="display:none;">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td style="padding-top:10px;" width="20%" valign="top">
                    <select name="variantGroups" ondblclick="moreFields();" size="20" style="width: 100%;">
                      <?php
                      $Qvgroups = $lC_Database->query('select id, title, module from :table_products_variants_groups where languages_id = :languages_id order by sort_order, title');
                      $Qvgroups->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
                      $Qvgroups->bindInt(':languages_id', $lC_Language->getID());
                      $Qvgroups->execute();
                      $has_multiple_value_groups = false;
                      while ($Qvgroups->next()) {
                        $vgroup_title = $Qvgroups->value('title');
                        if ( lC_Variants::allowsMultipleValues($Qvgroups->value('module')) ) {
                          if ( $has_multiple_value_groups === false ) {
                            $has_multiple_value_groups = true;
                          }                             
                          $vgroup_title .= ' (*)';
                        }
                        echo '          <optgroup label="' . $vgroup_title . '" id="' . $Qvgroups->valueInt('id') . '">' . "\n";
                        $Qvvalues = $lC_Database->query('select id, title from :table_products_variants_values where products_variants_groups_id = :products_variants_groups_id and languages_id = :languages_id order by sort_order, title');
                        $Qvvalues->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
                        $Qvvalues->bindInt(':products_variants_groups_id', $Qvgroups->valueInt('id'));
                        $Qvvalues->bindInt(':languages_id', $lC_Language->getID());
                        $Qvvalues->execute();
                        while ($Qvvalues->next()) {
                          echo '            <option value="' . $Qvvalues->valueInt('id') . '">' . $Qvvalues->value('title') . '</option>' . "\n";
                        }
                        echo '          </optgroup>' . "\n";
                      }
                      ?>
                    </select>
                    <?php
                    if ( $has_multiple_value_groups === true ) {
                      echo '<div style="text-align: center; font-style: italic;">(*) Multiple values can be assiged to the same product variant</div>';
                    }
                    ?>
                  </td>
                  <td valign="top" align="right" width="5%" style="padding-top:110px;">
                    <span class="button-group compact with-padding">
                       <span onclick="addVariant(); return false;" style="width:17px; cursor:pointer;" class="button icon-plus icon-size2 icon-blue glossy with-tooltip" title="<?php echo $lC_Language->get('button_add_variant');?>"></span><br /><br />
                       <span onclick="moreFields(); return false;" style="cursor:pointer;" class="button icon-forward icon-size2 icon-blue glossy with-tooltip" title="<?php echo $lC_Language->get('button_add_variant_option');?>"></span>
                    </span>
                  </td>
                  <td width="75%" valign="top">
                    <fieldset>
                      <legend><?php echo $lC_Language->get('subsection_assigned_variants'); ?></legend>
                      <span id="writeroot">
                        <?php
                        $variants_default_combo = null;

                        if ( isset($lC_ObjectInfo) ) {
                          $Qvariants = $lC_Database->query('select * from :table_products where parent_id = :parent_id');
                          $Qvariants->bindTable(':table_products', TABLE_PRODUCTS);
                          $Qvariants->bindInt(':parent_id', $lC_ObjectInfo->getInt('products_id'));
                          $Qvariants->execute();

                          $counter = 1;
                          while ( $Qvariants->next() ) {
                            $Qcombos = $lC_Database->query('select pv.default_combo, pvg.id as group_id, pvg.title as group_title, pvv.id as value_id, pvv.title as value_title from :table_products_variants pv, :table_products_variants_groups pvg, :table_products_variants_values pvv where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id order by pvg.sort_order, pvg.title');
                            $Qcombos->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
                            $Qcombos->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
                            $Qcombos->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
                            $Qcombos->bindInt(':products_id', $Qvariants->valueInt('products_id'));
                            $Qcombos->bindInt(':languages_id', $lC_Language->getID());
                            $Qcombos->bindInt(':languages_id', $lC_Language->getID());
                            $Qcombos->execute();

                            // QPB
                            $Qpb = $lC_Database->query('select group_id, tax_class_id, qty_break, price_break from :table_products_pricing where products_id = :products_id order by group_id, qty_break');
                            $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
                            $Qpb->bindInt(':products_id', $Qvariants->valueInt('products_id'));
                            $Qpb->execute();

                            $divPBEntry = '';
                            $rowCnt = 1;
                            while ($Qpb->next()) {
                              $rowID = 'row' . $counter . '_' . $rowCnt;
                              $divPBEntry .= '<tr id="' . $rowID . '"><td width="100px"><select style="width:98%" name="price_breaks[group_id][' . $counter . '][]" id="price_breaks[group_id][' . $counter . '][]">' . getCustomerGroupOptionsString($Qpb->valueInt('group_id')) . '</select></td>' .
                                             '<td width="100px"><select style="width:98%" name="price_breaks[tax_class_id][' . $counter . '][]" id="price_breaks[tax_class_id][' . $counter . '][]">' . getTaxClassOptionsString($Qpb->valueInt('tax_class_id')) . '</select></td>' .
                                             '<td width="110px"><table width="110px" border="0" cellpadding="0" cellspacing="0"><tr><td><input type="text" value="' . $Qpb->valueInt('qty_break') . '" name="price_breaks[qty][' . $counter . '][]" id="price_breaks[qty][][]" size="5" id="qty' . $counter . '"></td><td width="70%">' . $lC_Language->get('price_breaks_above') . '</td></tr></table></td>' .
                                             '<td width="80px"><input value="' . number_format(lc_round($Qpb->valueDecimal('price_break'), DECIMAL_PLACES), 2) . '" style="width:70px;" type="text" name="price_breaks[price][' . $counter . '][]" id="price_breaks[price][' . $counter . '][]"></td>' .
                                             '<td width="30px" align="center"><a id="' . $rowID . 'e" href="javascript://" onclick="removePriceBreakEntry(this); return false;"><img border="0" src="images/icons/cross.png"></a></td></tr>';
                              $rowCnt++;
                            }

                            $variants_string = '';
                            $variants_combo_string = '';
                            ?>
                            <script type="text/javascript">
                              variants[<?php echo $counter; ?>] = new Array();
                            </script>
                            <?php
                            while ( $Qcombos->next() ) {
                              if ( ($variants_default_combo === null) && ($Qcombos->valueInt('default_combo') === 1) ) {
                                $variants_default_combo = $counter;
                              }
                              $variants_string .= $Qcombos->value('group_title') . ': ' . $Qcombos->value('value_title') . ', ';
                              $variants_combo_string .= $Qcombos->valueInt('group_id') . '_' . $Qcombos->valueInt('value_id') . ';';
                              ?>
                              <script type="text/javascript">
                                if (variants[<?php echo $counter; ?>][<?php echo $Qcombos->valueInt('group_id'); ?>] == undefined) {
                                  variants[<?php echo $counter; ?>][<?php echo $Qcombos->valueInt('group_id'); ?>] = new Array();
                                }
                                variants[<?php echo $counter; ?>][<?php echo $Qcombos->valueInt('group_id'); ?>][<?php echo $Qcombos->valueInt('value_id'); ?>] = <?php echo $Qcombos->valueInt('value_id'); ?>;
                              </script>
                              <?php
                            }
                            $variants_string = substr($variants_string, 0, -2);
                            $variants_combo_string = substr($variants_combo_string, 0, -1);
                            ?>
                            <div id="variant<?php echo $counter; ?>" class="attributeAdd" onclick="activateVariant(this);">
                              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                  <td colspan="2"><div style="float: right;"><?php echo '<a href="javascript:setDefaultVariant(\'' . $counter . '\');">' . lc_icon_admin((($variants_default_combo === $counter) ? 'default.png' : 'default_grey.png'), null, null, 'id="vdc' . $counter . '"') . '</a>'; ?>&nbsp;<a href="javascript:removeVariant('variant<?php echo $counter; ?>');"><?php echo lc_icon_admin('trash.png'); ?></a></div><span style="font-weight: bold;"><?php echo lc_icon_admin('attach.png') . '&nbsp;' . $variants_string; ?></span></td>
                                </tr>
                                <tr id="variantTableContent<?php echo $counter; ?>">
                                  <td width="60%" height="100%" valign="top">
                                    <fieldset style="height: 100%;">
                                      <legend><?php echo $lC_Language->get('subsection_price'); ?></legend>

                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_tax_class'); ?></td>
                                          <td><?php echo lc_draw_pull_down_menu('variants_tax_class_id[' . $counter . ']', $tax_class_array, $Qvariants->valueInt('products_tax_class_id'), 'id="tax_class' . $counter . '" onchange="updateGross(\'variants_price' . $counter . '\');"'); ?></td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_price_net'); ?></td>
                                          <td><?php echo lc_draw_input_field('variants_price[' . $counter . ']', $Qvariants->value('products_price'), 'id="variants_price' . $counter . '" onkeyup="updateGross(\'variants_price' . $counter . '\')"'); ?></td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_price_gross'); ?></td>
                                          <td><?php echo lc_draw_input_field('variants_price_gross[' . $counter . ']', $Qvariants->value('products_price'), 'id="variants_price' . $counter . '_gross" onkeyup="updateNet(\'variants_price' . $counter . '\')"'); ?></td>
                                        </tr>
                                        <tr>
                                          <td colspan="2">
                                            <div><label for="qty_price_breaks" style="padding:3px 0;"><?php echo $lC_Language->get('field_qty_price_breaks'); ?></label>
                                              <table style="border:1px solid #7f9db9;" id="qty-pricing-grid-variant<?php echo $counter; ?>" summary="Qty Price Breaks"  width="100%" border="0" cellpadding="2" cellspacing="0">
                                                <thead style="height:24px;" class="ui-widget-header">
                                                  <tr style="padding-top:5px">
                                                    <th align="left" width="100px"><?php echo $lC_Language->get('table_heading_qpb_customer_group'); ?></th>
                                                    <th align="left" width="100px"><?php echo $lC_Language->get('table_heading_qpb_tax_class'); ?></th>
                                                    <th align="left" width="110px">&nbsp;<?php echo $lC_Language->get('table_heading_qpb_qty'); ?></th>
                                                    <th align="left" width="80px"><?php echo $lC_Language->get('table_heading_qpb_net_price'); ?></th>
                                                    <th align="center" width="30px">&nbsp;</th>
                                                  </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot><input type="hidden" id="newpbid<?php echo $counter; ?>" value="<?php echo $counter; ?>"></tfoot>
                                              </table>
                                              <div style="margin:5px 0;" class="ui-dialog-buttonset">
                                                <div style="float:right;"><button onclick="addPriceBreakFieldsNew(); return false;" class="operationButton" /><?php echo $lC_Language->get('button_add_price_break'); ?></button></div>
                                              </div>
                                            </div>
                                          </td>
                                        </tr>
                                      </table>
                                      <script type="text/javascript"><!--
                                        updateGross('variants_price<?php echo $counter; ?>');
                                        var counter = '<?php echo $counter; ?>';
                                        var divPBEntry = '<?php echo $divPBEntry; ?>';
                                        $("#qty-pricing-grid-variant" + counter + " > tbody").append(divPBEntry);
                                      //--></script>
                                    </fieldset>
                                  </td>
                                  <td width="40%" height="100%" valign="top">
                                    <fieldset style="height: 100%;">
                                      <legend><?php echo $lC_Language->get('subsection_data'); ?></legend>
                                      <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_status'); ?></td>
                                          <td><?php echo lc_draw_checkbox_field('variants_status[' . $counter . ']', null, null, (($Qvariants->value('products_status') == 1) ? 'checked="checked"' : '') ); ?></td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_model'); ?></td>
                                          <td><?php echo lc_draw_input_field('variants_model[' . $counter . ']', $Qvariants->value('products_model')); ?></td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_quantity'); ?></td>
                                          <td><?php echo lc_draw_input_field('variants_quantity[' . $counter . ']', $Qvariants->value('products_quantity')) . lc_draw_hidden_field('variants_combo[' . $counter . ']', $variants_combo_string, 'id="variants_combo_' . $counter . '"') . lc_draw_hidden_field('variants_combo_db[' . $counter . ']', $Qvariants->valueInt('products_id')); ?></td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_weight'); ?></td>
                                          <td><?php echo lc_draw_input_field('variants_weight[' . $counter . ']', $Qvariants->value('products_weight'), 'size="6"'); ?></td>
                                        </tr>
                                        <tr>
                                          <td><?php echo $lC_Language->get('field_weight_class'); ?></td>
                                          <td><?php echo lc_draw_pull_down_menu('variants_weight_class[' . $counter . ']', $weight_class_array, $Qvariants->value('products_weight_class')); ?></td>
                                        </tr>
                                      </table>
                                    </fieldset>
                                  </td>
                                </tr>
                              </table>
                            </div>
                            <?php
                            $counter++;
                          }
                          if ( $counter > 0 ) {
                            ?>
                            <script type="text/javascript">
                              variants_counter = <?php echo $counter; ?>;
                            </script>
                            <?php
                          }
                        }
                        ?>
                      </span>
                      <?php
                      echo lc_draw_hidden_field('variants_default_combo', $variants_default_combo, 'id="variants_default_combo"');
                      if ( is_numeric($variants_default_combo) ) {
                        ?>
                        <script type="text/javascript">
                          variants_default_combo = <?php echo $variants_default_combo; ?>;
                        </script>
                        <?php
                      }
                      ?>
                      <div id="readroot" style="display: none" class="attributeAdd" onclick="activateVariant(this);">
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td colspan="2"><div style="float: right;"><a href="#" name="default"><?php echo lc_icon_admin('default_grey.png', null, null, 'name="vdcnew"'); ?></a>&nbsp;<a href="#" name="trash"><?php echo lc_icon_admin('trash.png'); ?></a></div><span style="font-weight: bold;"><?php echo lc_icon_admin('attach.png') . '&nbsp;'; ?></span></td>
                          </tr>
                          <tr id="variantTableContent">
                            <td width="60%" height="100%" valign="top">
                              <fieldset style="height: 100%;">
                                <legend><?php echo $lC_Language->get('subsection_price'); ?></legend>
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_tax_class'); ?></td>
                                    <td><?php echo lc_draw_pull_down_menu('new_variants_tax_class_id', $tax_class_array, null, 'disabled="disabled"'); ?></td>
                                  </tr>
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_price_net'); ?></td>
                                    <td><?php echo lc_draw_input_field('new_variants_price', null, 'disabled="disabled"'); ?></td>
                                  </tr>
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_price_gross'); ?></td>
                                    <td><?php echo lc_draw_input_field('new_variants_price_gross', null, 'disabled="disabled"'); ?></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                      <div><label for="qty_price_breaks" style="padding:3px 0;"><?php echo $lC_Language->get('field_qty_price_breaks'); ?></label>
                                        <table style="border:1px solid #7f9db9;" id="qty-pricing-grid-variant" summary="Qty Price Breaks"  width="100%" border="0" cellpadding="2" cellspacing="0">
                                          <thead style="height:24px;" class="ui-widget-header">
                                            <tr style="padding-top:5px">
                                              <th align="left" width="100px"><?php echo $lC_Language->get('table_heading_qpb_customer_group'); ?></th>
                                              <th align="left" width="100px"><?php echo $lC_Language->get('table_heading_qpb_tax_class'); ?></th>
                                              <th align="left" width="110px">&nbsp;<?php echo $lC_Language->get('table_heading_qpb_qty'); ?></th>
                                              <th align="left" width="80px"><?php echo $lC_Language->get('table_heading_qpb_net_price'); ?></th>
                                              <th align="center" width="30px">&nbsp;</th>
                                            </tr>
                                          </thead>
                                          <tbody></tbody>
                                          <tfoot><input type="hidden" id="newpbid" value="1"></tfoot>
                                        </table>
                                        <div style="margin:5px 0;" class="ui-dialog-buttonset">
                                          <div style="float:right;"><button onclick="addPriceBreakFieldsNew(); return false;" class="operationButton" /><?php echo $lC_Language->get('button_add_price_break'); ?></button></div>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </fieldset>
                            </td>
                            <td width="40%" height="100%" valign="top">
                              <fieldset style="height: 100%;">
                                <legend><?php echo $lC_Language->get('subsection_data'); ?></legend>
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_status'); ?></td>
                                    <td><?php echo lc_draw_checkbox_field('new_variants_status', null, null, 'disabled="disabled"'); ?></td>
                                  </tr>
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_model'); ?></td>
                                    <td><?php echo lc_draw_input_field('new_variants_model', null, 'disabled="disabled"'); ?></td>
                                  </tr>
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_quantity'); ?></td>
                                    <td><?php echo lc_draw_input_field('new_variants_quantity', null, 'disabled="disabled"') . lc_draw_hidden_field('new_variants_combo', null, 'disabled="disabled"'); ?></td>
                                  </tr>
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_weight'); ?></td>
                                    <td><?php echo lc_draw_input_field('new_variants_weight', null, 'size="6" disabled="disabled"'); ?></td>
                                  </tr>
                                  <tr>
                                    <td><?php echo $lC_Language->get('field_weight_class'); ?></td>
                                    <td><?php echo lc_draw_pull_down_menu('new_variants_weight_class', $weight_class_array, SHIPPING_WEIGHT_UNIT, 'disabled="disabled"'); ?></td>
                                  </tr>
                                </table>
                              </fieldset>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </fieldset>
                  </td>
                </tr>
              </table>
            </div>
            <script language="javascript">
              $(document).ready(function() {
                toggleDataDiv();
                toggleVariantTableContent();

                var error = '<?php echo $_SESSION['error']; ?>';
                if (error) {
                  var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
                  alert(errmsg);
                }
              });

              function toggleDataDiv() {
                if ($("#has_variants").attr("checked")) {
                  $("#noVariants").hide();
                  $("#hasVariants").show();
                } else {
                  $("#hasVariants").hide();
                  $("#noVariants").show();
                }
              }
            </script>
          </div>
          <div id="section_images_content" class="with-padding">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="100%" height="100%" valign="top">
                  <fieldset style="height: 100%;">
                    <legend><?php echo $lC_Language->get('subsection_new_image'); ?></legend>
                    <div style="float: right;">
                      <a href="#" id="remoteFilesLink" onclick="switchImageFilesView('remote');" style="background-color: #E5EFE5;"><?php echo $lC_Language->get('image_remote_upload'); ?></a> | <a href="#" id="localFilesLink" onclick="switchImageFilesView('local');"><?php echo $lC_Language->get('image_local_files'); ?></a>
                    </div>
                    <div id="remoteFiles">
                      <span id="fileUploadField"></span>
                      <?php
                      if ( isset($lC_ObjectInfo) ) {
                        ?>
                        <div id="fileUploaderContainer" class="small-margin-top">
                          <noscript>
                            <p><?php echo $lC_Language->get('ms_error_javascript_not_enabled_for_upload'); ?></p>
                          </noscript>
                        </div>
                        <?php
                      } else {
                        echo lc_draw_file_field('products_image', null, 'class="file"');
                      }
                      ?>
                    </div>
                    <?php
                    if ( isset($lC_ObjectInfo) ) {
                      ?>
                      <script type="text/javascript"><!--
                        function createUploader(){
                          var uploader = new qq.FileUploader({
                              element: document.getElementById('fileUploaderContainer'),
                              action: '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=fileUpload'); ?>',
                              onComplete: function(id, fileName, responseJSON){
                                getImages();
                              },
                          });
                        }
                        $(document).ready(function() {
                          createUploader();
                        });
                      //--></script>
                      <?php
                    }
                    ?>
                    <div id="localFiles" style="display: none;">
                      <p><?php echo $lC_Language->get('introduction_select_local_images'); ?></p>
                      <select id="localImagesSelection" name="localimages[]" size="5" multiple="multiple" style="width: 100%;"></select>
                      <div id="showProgressGetLocalImages" style="display: none; float: right; padding-right: 10px;"><?php echo lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_retrieving_local_files'); ?></div>
                      <p><?php echo realpath('../images/products/_upload'); ?></p>
                      <?php
                      if ( isset($lC_ObjectInfo) ) {
                        echo '<input type="button" value="Assign To Product" class="operationButton" onclick="assignLocalImages();" /><div id="showProgressAssigningLocalImages" style="display: none; padding-left: 10px;">' . lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_multiple_upload_progress') . '</div>';
                      }
                      ?>
                    </div>
                  </fieldset>
                  <script type="text/javascript"><!--
                  getLocalImages();
                  //--></script>
                  <?php
                  if ( isset($lC_ObjectInfo) ) {
                    ?>
                    <fieldset style="height: 100%;">
                      <legend><?php echo $lC_Language->get('subsection_original_images'); ?></legend>
                      <div id="imagesOriginal" style="overflow: auto;" class="small-margin-top"></div>
                    </fieldset>
                    <fieldset style="height: 100%;">
                      <legend><?php echo $lC_Language->get('subsection_images'); ?></legend>
                      <div id="imagesOther" style="overflow: auto;"></div>
                    </fieldset>
                    <script type="text/javascript"><!--
                      getImages();
                    //--></script>
                    <?php
                  }
                  ?>
                </td>
              </tr>
            </table>
          
          </div>
          <div id="section_categories_content" class="with-padding">
            <div id="section_categories_content">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tbody>
                      <?php
                      $product_categories_array = array();
                      if ( isset($lC_ObjectInfo) ) {
                        $Qcategories = $lC_Database->query('select categories_id from :table_products_to_categories where products_id = :products_id');
                        $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
                        $Qcategories->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
                        $Qcategories->execute();
                        while ($Qcategories->next()) {
                          $product_categories_array[] = $Qcategories->valueInt('categories_id');
                        }
                      }
                      $assignedCategoryTree = new lC_CategoryTree();
                      $assignedCategoryTree->setBreadcrumbUsage(false);
                      $assignedCategoryTree->setSpacerString('&nbsp;', 5);
                      foreach ($assignedCategoryTree->getArray() as $value) {
                        echo '          <tr>' . "\n" .
                             '            <td width="30px">' . lc_draw_checkbox_field('categories[]', $value['id'], in_array($value['id'], $product_categories_array), 'class="input" id="categories_' . $value['id'] . '"') . '</td>' . "\n" .
                             '            <td><a href="#" onclick="document.product.categories_' . $value['id'] . '.checked=!document.product.categories_' . $value['id'] . '.checked;">' . $value['title'] . '</a></td>' . "\n" .
                             '          </tr>' . "\n";
                      }
                      ?>
                    </tbody>
                  </table></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    <div class="clear-both"></div>
    <div id="floating-button-container" class="six-columns twelve-columns-tablet">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon red-gradient glossy">
                  <span class="icon-cross"></span>
                </span><?php echo $lC_Language->get('button_cancel'); ?>
              </a>&nbsp;
              <a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="$(\'#product\').submit();'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span><?php echo $lC_Language->get('button_save'); ?>
              </a>&nbsp;
            </p>
          </div>
          <div id="floating-button-container-title" class="hidden">
            <p class="white big-text small-margin-top"><?php echo $lC_Template->getPageTitle(); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<?php
/*

<script type="text/javascript">
$(document).ready(function(){
  $("#mainTabs").tabs( { selected: 0 } );
  $("#languageTabs").tabs( { selected: 0 } );
  $("#languageTabsMeta").tabs( { selected: 0 } );

 <?php if ( isset($lC_ObjectInfo)) { ?>updatePriceBreakFields();<?php } ?>

});
</script>
<form name="product" id="product" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('products_id') : '') . '&cID=' . $_GET['cID'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
<div id="mainTabs">
  <ul>
    <li><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
    <li id="tabHeaderSectionDataContent"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
    <li><?php echo lc_link_object('#section_images_content', $lC_Language->get('section_images')); ?></li>
    <li><?php echo lc_link_object('#section_categories_content', $lC_Language->get('section_categories')); ?></li>
  </ul>

  <div id="section_general_content">


  </div>



</div>
*/


if (isset($_SESSION['error'])) unset($_SESSION['error']);
if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']);

?>