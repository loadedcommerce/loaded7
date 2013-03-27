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
                      "<td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' value='" . $Qpb->valueInt('qty_break') . "' name='price_breaks[qty][]' size='5' id='qty" . $rowCnt . "' /></td><td width='70%'>" . $lC_Language->get('price_breaks_above') . "</td></tr></table></td>" .
                      "<td width='80px'><input value='" . ((isset($lC_ObjectInfo)) ? number_format($Qpb->valueDecimal('price_break'), DECIMAL_PLACES) : null) . "' style='width:70px;' type='text' name='price_breaks[price][]' id='price" . $rowCnt . "' /></td>" .
                      "<td width='30px' align='center'><a id='row" . $rowCnt . "e' href='javascript://' onclick='removePriceBreakEntry(this);'><img border='0' src='images/icons/cross.png'></a></td></tr>";
      $rowCnt++;
    }
  }
}

// get product image
$Qpi = $lC_Database->query('select image from :table_products_images where products_id = :products_id');
$Qpi->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
$Qpi->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
$Qpi->execute();

// get categories array
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

// get specials
$Qspecials = $lC_Database->query('select * from :table_specials where products_id = :products_id');
$Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
$Qspecials->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
$Qspecials->execute();

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
                     "    <td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' name='price_breaks[qty][]' size='5' id='qty" + id + "' /></td><td width='70%'><?php echo $lC_Language->get('price_breaks_above'); ?></td></tr></table></td>" +
                     "    <td width='80px'><input value='<?php echo (isset($lC_ObjectInfo)) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null; ?>' style='width:70px;' type='text' name='price_breaks[price][]' id='price" + id + "' /></td>" +
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
                     "    <td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' name='price_breaks[qty][" + id + "][]' id='price_breaks[qty][" + id + "][]' size='5' id='qty" + id + "' /></td><td width='70%'><?php echo $lC_Language->get('price_breaks_above'); ?></td></tr></table></td>" +
                     "    <td width='80px'><input value='<?php echo (isset($lC_ObjectInfo)) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null; ?>' style='width:70px;' type='text' name='price_breaks[price][" + id + "][]' id='price_breaks[price][" + id + "][]' /></td>" +
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
                <div class="twelve-columns hide-below-768" style="height:38px;">
                  &nbsp;
                </div>
                <div class="twelve-columns">
                  <span>Image</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                  <dl class="accordion same-height small-margin-top">
                    <dt>Preview
                      <div class="button-group absolute-right compact mid-margin-right">
                        <a href="#" class="button icon-cloud-upload disabled">Upload</a>
                        <a href="#" class="button icon-trash with-tooltip disabled" title="Delete"></a>
                      </div>
                    </dt>
                    <dd>
                      <div class="with-padding">
                        <?php if ($Qpi->value('image')) { ?>
                        <div class="prod-image"><img src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>" style="max-width:100%;" /></div>
                        <?php } else { ?>
                        <div class="prod-image"><img src="images/no-prod-image.png" style="max-width: 100%; height: auto;" /><br />No Image</div>
                        <?php } ?>
                      </div>
                    </dd>
                  </dl>
                </div>
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
                    <?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 2, 'class="required input full-width autoexpanding"'); ?>
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
                  foreach ($assignedCategoryTree->getArray() as $value) {
                    $Qci = $lC_Database->query('select categories_id from :table_products_to_categories where products_id = :products_id');
                    $Qci->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
                    $Qci->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
                    $Qci->execute();
                    echo '<option id="categories_' . $value['id'] . '"' . (($Qci->valueInt('categories_id') == $value['id']) ? ' selected="selected"' : '') . '>' . $value['title'] . '</option>' . "\n";
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
            <div class="field-drop-product button-height black-inputs extreme-margin-bottom">
              <div class="columns">
                <div class="new-row-mobile four-columns twelve-columns-mobile"></div>
                <div class="new-row-mobile eight-columns twelve-columns-mobile">
                  <div style="width:100%;">
                    <div style="float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
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
                    <div style="float:left;width:2%;">&nbsp;</div>
                    <div style="float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
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
                        <label for="ps-radio-1" class="button blue-active">
                          <input type="radio" name="product-status-radio-group" id="ps-radio-1" value="active"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == 1) ? ' checked' : ''); ?> />
                          Active
                        </label>
                        <label for="ps-radio-2" class="button blue-active">
                          <input type="radio" name="product-status-radio-group" id="ps-radio-2" value="inactive"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == 0) ? ' checked' : ''); ?> />
                          Inactive
                        </label>
                        <label for="ps-radio-3" class="button blue-active disabled">
                          <input type="radio" name="product-status-radio-group" id="ps-radio-3" value="" />
                          Coming Soon
                        </label>
                      </span>
                    </div>                  
                  </div>                  
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="four-columns twelve-columns-mobile large-margin-bottom">
                <center>Chart Here</center>
              </div>
              <div class="four-columns twelve-columns-mobile">
                <span class="full-width">
                  <span><?php echo $lC_Language->get('field_model'); ?></span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span><br /><br />
                <p style="background-color:#cccccc;" class="with-small-padding"><b><?php echo $lC_ObjectInfo->get('products_model'); ?></b><p><br />
                <span class="full-width">
                  <span>Product Type</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b>&nbsp;</b><p><br />
                <span class="full-width">
                  <span>Inventory options</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b>&nbsp;</b><p>                
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
                <p style="background-color:#cccccc;" class="with-small-padding"><b><?php echo lC_DateTime::getShort($lC_ObjectInfo->get('products_date_added')); ?></b><p><br />
                <span class="full-width">
                  <span>Product Class</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b>&nbsp;</b><p><br />
                <span class="full-width">
                  <span>Weight</span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding"><b><?php echo $lC_ObjectInfo->get('products_weight'); ?></b><p>                
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
                      <li id="images-gallery-trigger" onclick="imagesGalleryTrigger();" class="with-right-arrow grey-arrow">
                        <span class="icon file-jpg"></span><b>Product Image</b>
                      </li>
                      <li id="additional-gallery-trigger" onclick="additionalGalleryTrigger();" class="grey">
                        <span class="icon folder-image"></span><b>Additional Images</b>
                      </li>
                    </ul>
                  </div> 
                </div>
              </div>
              <div class="panel-content linen" style="height:auto">
                <div class="panel-control align-right"><a class="button icon-pictures" href="#">Upload</a></div>
                <div style="height: auto; position: relative;" class="panel-load-target scrollable with-padding custom-scroll">
                  <ul class="gallery" id="images-gallery">
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                  </ul>
                  <ul class="gallery" id="additional-gallery" style="display:none;">
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                    <li>
                      <img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/large/' . $Qpi->value('image'); ?>">
                      <div class="controls">
                        <span class="button-group compact children-tooltip">
                          <a title="Edit" class="button icon-pencil" href="#"></a>
                          <a title="Delete" class="button icon-trash" href="#"></a>
                        </span>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- pricing_tab -->
          <div id="section_pricing_content" class="with-padding">
            <fieldset class="fieldset fields-list">
              <legend class="legend">Pricing Overrides</legend>
              <div class="field-block button-height">
                <label for="" class="label"><b>Base Price</b></label>
                <input type="text" name="" id="" value="<?php echo lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES); ?>" class="input" />
              </div>
              <!-- lc_group_pricing begin -->
              <div class="field-block field-block-product button-height">
                <label for="" class="label"><b>Group Pricing</b></label>
                <input onchange="$('#groups_pricing_pro_badge').toggle('300');$('#groups_pricing_container').toggle('300');" type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED" />
                <span class="info-spot on-left grey margin-left">
                  <span class="icon-info-round"></span>
                  <span class="info-bubble">
                    Put the bubble text here
                  </span>
                </span>
                <span id="groups_pricing_pro_badge" class="info-spot on-left grey" style="display:none;">
                  <small class="tag red-bg">Pro</small>
                  <span class="info-bubble">
                    <b>Go Pro!</b> and enjoy this feature!
                  </span>
                </span>
              </div>
              <div id="groups_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
                <?php //foreach() { ?>
                <div>
                  <label for="" class="label margin-right"><b>Reseller Group 1</b></label>
                  <input type="checkbox" class="switch disabled margin-right" />
                  <span class="nowrap">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <!-- if specials enabled <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;color:#ff0000;" />-->
                  </span>
                  <small class="input-info">Price<!-- if specials enabled /Special--></small>
                </div>
                <div>
                  <label for="" class="label margin-right"><b>Employee Group</b></label>
                  <input type="checkbox" class="switch disabled margin-right">
                  <span class="nowrap">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <!-- if specials enabled <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;color:#ff0000;" />-->
                  </span>
                  <small class="input-info">Price<!-- if specials enabled /Special--></small>
                </div>
                <div>
                  <label for="" class="label margin-right"><b>Gold Reseller</b></label>
                  <input type="checkbox" class="switch disabled margin-right">
                  <span class="nowrap">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <!-- if specials enabled <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;color:#ff0000;" />-->
                  </span>
                  <small class="input-info">Price<!-- if specials enabled /Special--></small>
                </div>
                <?php //} ?>
              </div>
              <!-- lc_group_pricing end -->
              <!-- lc_qty_price_breaks begin -->
              <div class="field-block field-block-product button-height">
                <label for="" class="label"><b>Qty Break Pricing</b></label>
                <input onchange="$('#qty_breaks_number_of_break_points').toggle('300');$('#qty_breaks_pricing_container').toggle('300');" type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED" />
                <span class="info-spot on-left grey margin-left margin-right">
                  <span class="icon-info-round"></span>
                  <span class="info-bubble">
                    Put the bubble text here
                  </span>
                </span>
                <span id="qty_breaks_number_of_break_points" style="display:none;">
                  <span class="number input">
                    <button type="button" class="button number-down" disabled>-</button>
                    <input type="text" value="3" size="3" class="input-unstyled" disabled />
                    <button type="button" class="button number-up" disabled>+</button>
                  </span>
                  <span class="info-spot on-left grey">
                    <small class="tag red-bg">Pro</small>
                    <span class="info-bubble">
                      <b>Go Pro!</b> and enjoy this feature!
                    </span>
                  </span>
                </span>
              </div> 
              <div id="qty_breaks_pricing_container" class="field-drop button-height black-inputs" style="display:none;">
                <label for="" class="label">
                  <span class="info-spot on-left grey margin-right">
                    <small class="tag red-bg" style="border:2px solid grey;">Pro</small>
                    <span class="info-bubble">
                      <b>Go Pro!</b> and enjoy this feature!
                    </span>
                  </span>
                  <b>Retail</b>
                </label>
                <?php //foreach() { ?>
                <div>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="1" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <small class="input-info small-margin-right">Qty</small>
                  </span>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;" />
                    <!--<input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <small class="input-info">Price<!-- if specials enabled /Special--></small>
                    <?php //} ?>
                  </span>
                </div>
                <div>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="10" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <small class="input-info small-margin-right">Qty</small>
                  </span>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;" />
                    <!--<input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <small class="input-info">Price<!-- if specials enabled /Special--></small>
                    <?php //} ?>
                  </span>
                </div>
                <div> 
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="50" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <small class="input-info small-margin-right">Qty</small>
                  </span>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;" />
                    <!--<input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <small class="input-info">Price<!-- if specials enabled /Special--></small>
                    <?php //} ?>
                  </span>
                </div>
                <?php //} ?>
              </div>
              <!-- lc_qty_price_breaks end --> 
              <div class="field-block field-block-product button-height">
                <label for="" class="label"><b>Special Pricing</b></label>
                <input onchange="$('#specials_pricing_container').toggle('300');" type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED"<?php echo (($Qspecials->value('specials_new_products_price') != null) ? ' checked' : ''); ?> />
                <span class="info-spot on-left grey margin-left margin-right">
                  <span class="icon-info-round"></span>
                  <span class="info-bubble">
                    Put the bubble text here
                  </span>
                </span>
              </div>
              <div id="specials_pricing_container" class="field-drop button-height black-inputs"<?php echo (($Qspecials->value('specials_new_products_price') != null) ? ' style="display:block;"' : ' style="display:none;"'); ?>>
                <label for="resize_height" class="label"><b>Special Retail Price</b></label>
                  <div class="columns">
                    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                      <input type="checkbox" class="switch<?php if ($Qspecials->value('status') != 0) echo ' checked'; ?>" />
                      <span class="input">
                        <input name="" id="" value="<?php echo number_format($Qspecials->value('specials_new_products_price'), DECIMAL_PLACES); ?>" placeholder="Price or %" class="input-unstyled align-right" style="color:#ff0000;font-weight:bold;" />
                      </span>
                    </div>
                    <div class="new-row-mobile twelve-columns twelve-columns-mobile">
                      <span class="nowrap margin-right">
                        <span class="input small-margin-top">
                          <input type="text" placeholder="Start" class="input-unstyled datepicker" value="<?php echo lC_DateTime::getShort($Qspecials->value('start_date')); ?>" style="width:97px;" />
                        </span>
                        <span class="icon-calendar icon-size2 small-margin-left"></span>
                      </span>
                      <span class="nowrap">
                        <span class="input small-margin-top">
                          <input type="text" placeholder="End" class="input-unstyled datepicker" value="<?php echo lC_DateTime::getShort($Qspecials->value('expires_date')); ?>" style="width:97px;" />
                        </span>
                        <span class="icon-calendar icon-size2 small-margin-left"></span>
                      </span>
                    </div>
                </div>
              </div>                
            </fieldset>
            <dl class="accordion same-height">
              <dt>Retail Price</dt>
              <dd>
                <?php // if no options set ?>
                  <!-- Please Create your inventory Option -->
                <?php //} else { ?>
                <div class="left-column-200px margin-bottom clear-left with-mid-padding">
                  <div class="left-column">
                    IOption Set - SKU&nbsp;&nbsp;
                    <span class="info-spot on-left grey">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="right-column">
                    Price&nbsp;&nbsp;
                    <span class="info-spot on-left grey">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span><!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Special&nbsp;Price&nbsp;&nbsp;
                    <span class="info-spot on-left grey">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>-->
                  </div>
                </div>
                <!--<div class="left-column-200px margin-bottom clear-left with-mid-padding">
                  <?php //foreach() { ?>
                  <div class="left-column with-small-padding">
                    Red Medium - KSRM0001
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      <!--&nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <?php // } ?>
                  <!--</div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding silver-bg">
                    Red Large - KSRL0023
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      <!--&nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <?php // } ?>
                  <!--</div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding">
                    Red X Large - KSRXL0011
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      <!--&nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <?php // } ?>
                  <!--</div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding silver-bg">
                    Green Medium - KSGM0054
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      <!--&nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <?php // } ?>
                  <!--</div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding">
                    Green Large - KSGL0055
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      <!--&nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <?php // } ?>
                  <!--</div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding silver-bg">
                    Green X Large - KSGXL0167
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      <!--&nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <?php // } ?>
                  <!--</div>
                  <div style="height:5px;"></div>
                  <?php //} //} ?>
                </div>-->
              </dd>
            </dl>
          </div>
          <!-- data_tab -->
          <div id="section_data_content" class="with-padding">
            <fieldset class="fieldset">
              <legend class="legend">Inventory Settings</legend>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span><?php echo $lC_Language->get('field_model'); ?></span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <input type="text" class="required input full-width" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_model') : null); ?>" id="" name="" />
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <!-- lc_track_inventory_override begin -->
                  <div class="twelve-columns no-margin-bottom">
                    <span>Track Inventory Override</span>
                    <span class="info-spot on-left grey small-margin-left">
                      <small class="tag red-bg">Pro</small>
                      <span class="info-bubble">
                        <b>Go Pro!</b> and enjoy this feature!
                      </span>
                    </span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <span class="button-group">
                      <label for="ti-radio-1" class="button disabled">
                        <input type="radio" name="track-inventory-radio-group" id="ti-radio-1" value="1" />
                        Default
                      </label>
                      <label for="ti-radio-2" class="button disabled">
                        <input type="radio" name="track-inventory-radio-group" id="ti-radio-2" value="2" />
                        On
                      </label>
                      <label for="ti-radio-3" class="button disabled">
                        <input type="radio" name="track-inventory-radio-group" id="ti-radio-3" value="3" />
                        Off
                      </label>
                    </span>
                  </div>
                  <!-- lc_track_inventory_override end -->
                </div>
              </div>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span>MSRP</span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <input type="text" class="required input full-width" value="coing soon..." id="" name="" disabled />
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <!-- lc_vendor_supplier begin -->
                  <div class="twelve-columns no-margin-bottom">
                    <span>Vendor/Supplier</span>
                    <span class="info-spot on-left grey small-margin-left">
                      <small class="tag red-bg">Pro</small>
                      <span class="info-bubble">
                        <b>Go Pro!</b> and enjoy this feature!
                      </span>
                    </span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <select class="select full-width small-margin-top" disabled>
                      <option id="1" value="1">Vendor #1</option>
                    </select>
                  </div>
                  <!-- lc_vendor_supplier end -->
                </div>
              </div>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <!-- lc_inventory_control begin -->
                  <div class="twelve-columns no-margin-bottom">
                    <span>Inventory Control</span>
                    <span class="info-spot on-left grey small-margin-left">
                      <small class="tag red-bg">Pro</small>
                      <span class="info-bubble">
                        <b>Go Pro!</b> and enjoy this feature!
                      </span>
                    </span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <span class="button-group">
                      <label for="ic-radio-1" class="button green-active active">
                        <input type="radio" name="inventory-control-radio-group" id="ic-radio-1" value="1" onclick="$('#inventory_control_simple').show('300');$('#inventory_control_multi').hide('300');" />
                        Simple
                      </label>
                      <label for="ic-radio-2" class="button green-active">
                        <input type="radio" name="inventory-control-radio-group" id="ic-radio-2" value="2" onclick="$('#inventory_control_simple').hide('300');$('#inventory_control_multi').show('300');" />
                        Multi SKU
                      </label>
                      <label for="ic-radio-3" class="button disabled green-active">
                        <input type="radio" name="inventory-control-radio-group" id="ic-radio-3" value="3" />
                        Recurring
                      </label>
                    </span>
                  </div>
                  <!-- lc_inventory_control end -->
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">&nbsp;</div>
              </div>
              <div id="inventory_control_container" class="field-drop button-height black-inputs no-margin-bottom">
                <!-- lc_inventory_control_simple begin -->
                <div id="inventory_control_simple">
                  <div>
                    <label for="" class="label"><b>Qty On Hand</b></label>
                    <input type="text" name="products_quantity" id="products_quantity" value="<?php echo $lC_ObjectInfo->get('products_quantity'); ?>" class="input small-margin-right" style="width:60px;" />
                    <input type="text" name="products_sku_ean13" id="products_sku_ean13" value="" class="input" disabled />
                    <b>SKU/EAN13</b>
                  </div>
                  <div class="small-margin-top">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled /> <b>Cost</b>
                    <span class="info-spot on-left grey small-margin-left">
                      <small class="tag red-bg" style="border:2px solid grey;">Pro</small>
                      <span class="info-bubble">
                        <b>Go Pro!</b> and enjoy this feature!
                      </span>
                    </span> 
                  </div>
                </div>
                <!-- lc_inventory_control_simple end -->                                       
                <div id="inventory_control_multi" style="display:none;">
                  <span class="icon-warning icon icon-size2 icon-orange small-margin-right"></span> Edit QOH and SKU on the Options Tab.
                </div>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend">Tax Settings</legend>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span>Tax Class</span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <?php echo lc_draw_pull_down_menu('products_tax_class_id', $tax_class_array, (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('products_tax_class_id') : null), 'class="select full-width small-margin-top" id="tax_class0"'); ?>
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span>Base Price with Tax</span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <?php echo lc_draw_input_field('products_price_gross', (isset($lC_ObjectInfo) ? lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES) : null), 'class="required input full-width" id="products_price0_gross" READONLY'); ?>
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend">Management Settings</legend>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span>Manufacturer</span> 
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <select class="select full-width small-margin-top">
                      <option id="1" value="1">Boutique</option>
                      <option id="2" value="2">Citezens of Humanity</option>
                      <option id="3" value="3">Crew Clothing</option>
                      <option id="4" value="4">Mudd & Water</option>
                      <option id="5" value="5">Summer</option>
                    </select>
                  </div>
                </div> 
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <!-- lc_products_class begin -->
                  <div class="twelve-columns no-margin-bottom">
                    <span>Product Class</span>
                    <span class="info-spot on-left grey small-margin-left">
                      <small class="tag red-bg">Pro</small>
                      <span class="info-bubble">
                        <b>Go Pro!</b> and enjoy this feature!
                      </span>
                    </span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <select class="select full-width small-margin-top" disabled>
                      <option id="1" value="1">Common</option>
                      <option id="2" value="2">2nd Class</option>
                      <option id="3" value="3">3rd Class</option>
                      <option id="4" value="4">4th Class</option>
                      <option id="5" value="5">5th Class</option>
                    </select>
                  </div>
                  <!-- lc_products_class end -->
                </div>
              </div>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span>URL Slug</span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <input type="text" class="required input full-width" value="coming soon..." id="" name="" disabled />
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span>Availability</span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom">
                    <span class="nowrap margin-right">
                      <span class="input small-margin-top">
                        <input type="text" placeholder="Start" class="input-unstyled datepicker" value="<?php echo lC_DateTime::getShort($lC_ObjectInfo->get('products_date_added')); ?>" style="width:97px;" />
                      </span>
                      <span class="icon-calendar icon-size2 small-margin-left"></span>
                    </span>
                    <!-- lc_products_availability begin -->
                    <span class="nowrap">
                      <span class="input small-margin-top">
                        <input type="text" placeholder="End" class="input-unstyled datepicker" value="" style="width:97px;" disabled />
                      </span>
                      <span class="icon-calendar icon-size2 small-margin-left grey"></span>
                      <span class="info-spot on-left grey small-margin-left">
                        <small class="tag red-bg">Pro</small>
                        <span class="info-bubble">
                          <b>Go Pro!</b> and enjoy this feature!
                        </span>
                      </span>
                    </span>
                    <!-- lc_products_availability end -->
                  </div>
                </div>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend">Product Details</legend>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <?php //foreach() { ?>
                  <div class="margin-bottom">
                    <label for="" class="label">Custom Field 1</label>
                    <input type="text" name="" id="" value="coming soon..." class="input" disabled />
                  </div>
                  <?php //} ?>
                  <div class="margin-bottom">
                    <label for="" class="label">Custom Field 2</label>
                    <input type="text" name="" id="" value="coming soon..." class="input" disabled />
                  </div>
                  <div> 
                    <label for="" class="label">Custom Field 3</label>
                    <input type="text" name="" id="" value="coming soon..." class="input" disabled />
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <!-- lc_products_custom begin -->
                  <p class="button-height">
                    <a class="button icon-star small-margin-right disabled" href="javascript:void(0)">Customize</a>
                    <span class="info-spot on-left grey">
                      <small class="tag red-bg">Pro</small>
                      <span class="info-bubble">
                        <b>Go Pro!</b> and enjoy this feature!
                      </span>
                    </span>
                  </p>
                  <!-- lc_products_custom end -->
                </div>
              </div>
            </fieldset>
          </div>
          <!-- options_tab -->
          <div id="section_options_content" class="with-padding">
            <div class="columns">
              <div class="twelve-columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span class="large-margin-right">Inventory Control</span>
                    <span class="large-margin-right">&nbsp;</span>
                    <span class="margin-right">&nbsp;</span>
                    <span class="info-spot on-left grey large-margin-left">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span><br />
                    <span class="button-group small-margin-top">
                      <!-- lc_options_inventory_control begin -->
                      <label for="ioc-radio-1" class="button red-active">
                        <input type="radio" name="inventory-control-radio-group" id="ioc-radio-1" value="1" />
                        Simple
                      </label>
                      <label for="ioc-radio-2" class="button red-active">
                        <input type="radio" name="inventory-control-radio-group" id="ioc-radio-2" value="2" checked />
                        Multi SKU
                      </label>
                      <label for="ioc-radio-3" class="button red-active disabled">
                        <input type="radio" name="inventory-control-radio-group" id="ioc-radio-3" value="3" />
                        Recurring
                      </label>
                      <span class="info-spot on-left grey margin-left">
                        <small class="tag red-bg">Pro</small>
                        <span class="info-bubble">
                          <b>Go Pro!</b> and enjoy this feature!
                        </span>
                      </span>
                      <!-- lc_options_inventory_control end -->
                    </span>
                  </div>
                </div>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Inventory Options Combo Sets</legend>
                  <div class="columns">
                    <div class="twelve-columns">
                      Data Table is Going to be here :)                   
                    </div>
                  </div>
                  <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)">New Inventory Option Combo Set</a></div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Simple Inventory Options</legend>
                  <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)">New Inventory Simple Option</a></div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Simple Options</legend>
                  <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)">New Simple Option</a></div>
                </fieldset>
              </div>
            </div>
          </div>
          <!-- shipping_tab -->
          <div id="section_shipping_content" class="with-padding">
            <div class="columns">
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Product Characteristics</legend>
                  <div class="columns">
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span>Weight</span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="<?php echo $lC_ObjectInfo->get('products_weight'); ?>" id="" name="" />
                      </div>
                      <div class="twelve-columns no-margin-bottom margin-top">
                        Non Shippable Item
                        <input type="checkbox" id="virtual" name="virtual" disabled />
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span>Additional Handling Fee</span>
                        <span class="info-spot on-left grey">
                          <small class="tag red-bg">Pro</small>
                          <span class="info-bubble">
                            <b>Go Pro!</b> and enjoy this feature!
                          </span>
                        </span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="coming soon..." id="" name="" disabled />
                      </div>
                    </div>                
                  </div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Order Fee Modifiers</legend>
                  <div class="columns">
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span>Shipping Fee Override</span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="coming soon..." id="" name="" disabled /><small>Enter 0 for free shipping</small>
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span>Additional Handling Fee</span>
                        <span class="info-spot on-left grey">
                          <small class="tag red-bg">Pro</small>
                          <span class="info-bubble">
                            <b>Go Pro!</b> and enjoy this feature!
                          </span>
                        </span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="coming soon..." id="" name="" disabled />
                      </div>
                    </div>                
                  </div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend">Supplier Characteristics</legend>
                  <div class="columns">
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span>Days to Ship</span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="coming soon..." id="" name="" disabled />
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span>Warehouse</span>
                        <span class="info-spot on-left grey">
                          <small class="tag red-bg">Pro</small>
                          <span class="info-bubble">
                            <b>Go Pro!</b> and enjoy this feature!
                          </span>
                        </span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="coming soon..." id="" name="" disabled />
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span>Date Expected for Stock</span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <span class="nowrap margin-right">
                          <span class="input small-margin-top full-width">
                            <input type="text" placeholder="" class="input-unstyled datepicker full-width" value="coming soon..." disabled />
                            <span class="icon-calendar icon-size2 small-margin-left float-right" style="margin-top:-29px;"></span>
                          </span>
                        </span>
                      </div>
                    </div>                
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
          <!-- relationships_tab -->
          <div id="section_relationships_content" class="with-padding">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tbody>
                  <?php
                    foreach ($assignedCategoryTree->getArray() as $value) {
                      echo '          <tr>' . "\n" .
                           '            <td width="30px" class="cat_rel_td">' . lc_draw_checkbox_field('categories[]', $value['id'], in_array($value['id'], $product_categories_array), 'class="input" id="categories_' . $value['id'] . '"') . '</td>' . "\n" .
                           '            <td class="cat_rel_td"><a href="#" onclick="document.product.categories_' . $value['id'] . '.checked=!document.product.categories_' . $value['id'] . '.checked;">' . $value['title'] . '</a></td>' . "\n" .
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
              <select class="select expandable-list"> 
                <option id="" value="">Related</option>
                <!--<option id="create_order" value="create_order">Create Order</option>-->
                <option id="duplicate_product" value="duplicate_product">Duplicate</option>
                <option id="catalog_view" value="catalog_view">View In Catalog</option>
                <option id="view_customers" value="view_customers">View Customers</option>
                <option id="notify_customers" value="notify_customers">Notify Customers</option>
              </select>&nbsp;
              <select class="select expandable-list" style="width:96px;"> 
                <option id="" value="">Actions</option>
                <option id="save" value="save">Save</option>
                <option id="apply_changes" value="apply_changes">Apply</option>
              </select>&nbsp;
              <!--<a class="button<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 3) ? ' disabled' : NULL); ?>" href="<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '#' : 'javascript://" onclick="$(\'#product\').submit();'); ?>">
                <span class="button-icon green-gradient glossy">
                  <span class="icon-download"></span>
                </span><?php echo $lC_Language->get('button_save'); ?>
              </a>&nbsp;-->
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