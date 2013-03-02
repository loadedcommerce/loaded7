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
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

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
  <div class="with-padding-no-top">
    <form name="product" id="product" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('products_id') : '') . '&cID=' . $_GET['cID'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="product_tabs" class="side-tabs" style="position:relative;">
        <ul class="tabs">
          <li class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li><?php echo lc_link_object('#section_images_content', $lC_Language->get('section_images')); ?></li>
          <li><?php echo lc_link_object('#section_pricing_content', $lC_Language->get('section_pricing')); ?></li>
          <li id="tabHeaderSectionDataContent"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
          <li><?php echo lc_link_object('#section_options_content', $lC_Language->get('section_options')); ?></li>
          <li><?php echo lc_link_object('#section_shipping_content', $lC_Language->get('section_shipping')); ?></li>
          <li><?php echo lc_link_object('#section_relationships_content', $lC_Language->get('section_relationships')); ?></li>
        </ul>
        <div class="clearfix tabs-content">
          <!-- content_tab -->
          <div id="section_general_content" class="with-padding">
            <div class="columns">
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <div class="twelve-columns" style="height:38px;">
                  &nbsp;
                </div>
                <span>Image</span>
                <span class="info-spot on-left grey float-right">
                  <span class="icon-info-round"></span>
                  <span class="info-bubble">
                    Put the bubble text here
                  </span>
                </span><br />
                <center><img src="images/pimage.png" style="margin-top:4px;"/></center>
                <span class="float-left">Drag Image to replace</span>
              </div>
              <div class="new-row-mobile eight-columns twelve-columns-mobile">             
                <div class="columns">
                  <div class="twelve-columns mid-margin-bottom align-right">
                    <select class="select">
                      <?php foreach ( $lC_Language->getAll() as $l ) { ?>
                      <option id="<?php echo $l['code']; ?>" value="<?php echo $l['code']; ?>"><?php echo $l['name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="twelve-columns no-margin-bottom">
                    <span class="small-margin-bottom">Name</span>
                    <span class="info-spot on-left grey float-right small-margin-bottom">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom">
                    <?php echo lc_draw_input_field('products_name[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_name[$l['id']]) ? $products_name[$l['id']] : null), 'class="required input full-width"'); ?>
                  </div>
                  <div class="twelve-columns no-margin-bottom mid-margin-top">
                    <span class="small-margin-bottom">Description</span>
                    <span class="info-spot on-left grey float-right small-margin-bottom">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom">
                    <?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 7, 'class="required input full-width autoexpanding"'); ?>
                    <span class="float-right small-margin-top"><a href="#">Enlarge Description <span class="icon-extract icon-grey"></span></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="javascript:toggleEditor(\'products_description[' . $l['id'] . ']\');">' . $lC_Language->get('toggle_html_editor') . '</a>'; ?></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <span>Main Category</span>
                <span class="info-spot on-left grey float-right">
                  <span class="icon-info-round"></span>
                  <span class="info-bubble">
                    Put the bubble text here
                  </span>
                </span>
                <select class="select full-width small-margin-top">
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
                    echo '<option id="categories_' . $value['id'] . '">' . $value['title'] . '</option>' . "\n";
                  }
                ?>
                </select>
              </div>
              <div class="new-row-mobile eight-columns twelve-columns-mobile">
                <span class="full-width">
                  <span class="small-margin-bottom">Keywords</span>
                  <span class="info-spot on-left grey float-right small-margin-bottom">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <div class="full-width clear-right mid-margin-bottom">
                  <?php echo lc_draw_input_field('products_keyword[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_keyword[$l['id']]) ? $products_keyword[$l['id']] : null), 'class="input full-width" id="keyword' . $l['id'] . '"'); ?>
                </div>
              </div>
            </div>            
            
            <!-- leave for now -->
            <div class="field-drop-product button-height black-inputs extreme-margin-bottom">
              <div class="left-column-280px" style="margin-bottom:-18px;">
                <div class="left-column"></div>
                <div class="right-column">
                  <div class="columns mid-margin-top">
                    <div class="new-row-mobile new-row-tablet six-columns twelve-columns-tablet twelve-columns-mobile">
                      <span class="full-width">
                        <span>Base Price</span>
                        <span class="info-spot on-left grey float-right mid-margin-top">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </span>
                      <?php echo lc_draw_input_field('products_price', (isset($lC_ObjectInfo) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null), 'class="input full-width" id="products_price0" onkeyup="updateGross(\'products_price0\')"'); ?>
                    </div>
                    <div class="new-row-mobile new-row-tablet six-columns twelve-columns-tablet twelve-columns-mobile clear-both">
                      <span class="full-width">
                        <span>Status</span>
                        <span class="info-spot on-left grey float-right mid-margin-top">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </span><br />
                      <span class="button-group">
                        <label for="button-radio-1" class="button blue-active">
                          <input type="radio" name="button-radio" id="button-radio-1" value="1" checked>
                          Active
                        </label>
                        <label for="button-radio-2" class="button blue-active">
                          <input type="radio" name="button-radio" id="button-radio-2" value="2">
                          Inactive
                        </label>
                        <label for="button-radio-3" class="button blue-active">
                          <input type="radio" name="button-radio" id="button-radio-3" value="3">
                          Coming Soon
                        </label>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end leave for now -->
            
            
            <div class="columns">
              <div class="four-columns twelve-columns-mobile large-margin-bottom">
                <center><img src="images/prodchart.png" /></center>
              </div>
              <div class="four-columns twelve-columns-mobile">
                <span class="full-width">
                  <span>Model</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span><br /><br />
                <p style="background-color:#cccccc;" class="with-small-padding"><b>K00011</b><p><br />
                <span class="full-width">
                  <span>Product Type</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b>Simple</b><p><br />
                <span class="full-width">
                  <span>Inventory options</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b>Base Product</b><p>                
              </div>
              <div class="four-columns twelve-columns-mobile">
                <span class="full-width">
                  <span>Date Available</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span><br /><br />
                <p style="background-color:#cccccc;" class="with-small-padding"><b></b>4/20/2013<p><br />
                <span class="full-width">
                  <span>Product Class</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b>Common</b><p><br />
                <span class="full-width">
                  <span>Weight</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b>1 lbs</b><p>                
              </div>
            </div>
          </div>
          <!-- images_tab -->
          <div id="section_images_content" class="with-padding">
            <div class="content-panel margin-bottom enabled-panels">
              <div class="panel-navigation silver-gradient">
                <div class="panel-control"></div>
                <div class="panel-load-target scrollable custom-scroll">
                  <div class="navigable">
                    <ul class="files-list mini open-on-panel-content">
                      <li><span class="icon file-jpg"></span><b>Product Images</b></li>
                      <li><span class="icon folder-image"></span><b>Additional Images</b></li>
                    </ul>
                  </div>
                  <div class="custom-vscrollbar" style="display: none; opacity: 0;">
                    <div></div>
                  </div>
                </div>
              </div>
              <div class="panel-content linen" style="height:400px">
                <div class="panel-control align-right"><a class="button icon-pictures" href="#">Replace All</a></div>
              </div>
            </div>
          </div>
          <!-- pricing_tab -->
          <div id="section_pricing_content" class="with-padding">
            <fieldset class="fieldset fields-list">
              <legend class="legend">Pricing Overrides</legend>
              <div class="field-block button-height">
                <label for="input-1" class="label"><b>Base Price</b></label>
                <input type="text" name="input-1" id="input-1" value="" class="input">
              </div>
              <div class="field-block field-block-product button-height">
                <label for="input-1" class="label"><b>Group Pricing</b></label>
                <input onchange="$('#groups_pricing_container').toggle('300');" type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED">
              </div>
              <div id="groups_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
                <label for="input-2" class="label"><b>Label</b></label>
                <input type="text" name="input-2" id="input-2" value="" class="input">
                <small class="input-info">Info below input</small>
              </div>
              <div class="field-block field-block-product button-height">
                <label for="input-1" class="label"><b>Qty Break Pricing</b></label>
                <input onchange="$('#qty_breaks_pricing_container').toggle('300');" type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED">
              </div> 
              <div id="qty_breaks_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
                <label for="input-2" class="label"><b>Label</b></label>
                <input type="text" name="input-2" id="input-2" value="" class="input">
                <small class="input-info">Info below input</small>
              </div>
              <div class="field-block field-block-product button-height">
                <label for="input-1" class="label"><b>Special Pricing</b></label>
                <input onchange="$('#specials_pricing_container').toggle('300');" type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED">
              </div>
              <div id="specials_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
                <label for="input-2" class="label"><b>Label</b></label>
                <input type="text" name="input-2" id="input-2" value="" class="input">
                <small class="input-info">Info below input</small>
              </div>
            </fieldset>
          </div>
          <!-- data_tab -->
          <div id="section_data_content" class="with-padding">
            <fieldset class="fieldset">
              <legend class="legend">Inventory Settings</legend>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Model Input
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Track Inventory Radio Group (Buttons) and Pro Button
                </div>
              </div>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  MSRP Input
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Vendor Supplier Dropdown and Pro Button
                </div>
              </div>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Inventory Control Radio Group (Buttons)
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Blank
                </div>
              </div>
              <div id="inventory_control_container" class="field-drop button-height black-inputs">
                <label for="input-2" class="label"><b>Label</b></label>
                <input type="text" name="input-2" id="input-2" value="" class="input">
                <small class="input-info">Info below input</small>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend">Tax Settings</legend>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Tax Class Dropdown
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Base Price w/Tax Input
                </div>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend">Management Settings</legend>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Manufacturer Dropdown
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Product Class Dropdown & Pro Button
                </div>
              </div>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  URL Slug Input
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Availability Date Start and End Inputs & Pro Button
                </div>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend">Product Details</legend>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Custom Field 1
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Customize & Pro Button
                </div>
              </div>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Custom Field 2
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Blank
                </div>
              </div>
              <div class="columns">
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Custom Field 3
                </div>
                <div style="background-color: #eeeeee;" class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  Blank
                </div>
              </div>
            </fieldset>
          </div>
          <!-- options_tab -->
          <div id="section_options_content" class="with-padding">
            <div class="columns">
              <div style="background-color: #eeeeee;" class="twelve-columns">
                Inventory Contro Radio Group (Buttons) & Pro Button
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Inventory Options Combo Sets</legend>
                  
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Simple Inventory Options</legend>
                  
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Simple Options</legend>
                  
                </fieldset>
              </div>
            </div>
          </div>
          <!-- shipping_tab -->
          <div id="section_shipping_content" class="with-padding">
            Shipping
          </div>
          <!-- relationships_tab -->
          <div id="section_relationships_content" class="with-padding">
            Relationships
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
if (isset($_SESSION['error'])) unset($_SESSION['error']);
if (isset($_SESSION['errmsg'])) unset($_SESSION['errmsg']);
?>