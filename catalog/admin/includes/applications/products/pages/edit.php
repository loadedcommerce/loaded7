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
                               'text' => $lC_Language->get('text_none')));
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
                      "<td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' value='" . $Qpb->valueInt('qty_break') . "' name='price_breaks[qty][]' size='5' id='qty" . $rowCnt . "' /></td><td width='70%'>" . $lC_Language->get('text_price_breaks_above') . "</td></tr></table></td>" .
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
function toggleEditor(id) {
  var editorHidden = $(".clEditorProductDescription").is(":visible");
  if (editorHidden) {
    //alert('show');
    $(".clEditorProductDescription").cleditor({width:"99%", height:"255"});
  } else {
    //alert('hide');
    var editor = $(".clEditorProductDescription").cleditor()[0];
    editor.$area.insertBefore(editor.$main); // Move the textarea out of the main div
    editor.$area.removeData("cleditor"); // Remove the cleditor pointer from the textarea
    editor.$main.remove(); // Remove the main div and all children from the DOM
    $(".clEditorProductDescription").show();
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

        var style = 'width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px; margin: 10px; padding: 10px; float: left; text-align: center; border-radius: 5px;';

        if ( entry[1] == '1' ) { // original (products_images_groups_id)
          var onmouseover = 'this.style.backgroundColor=\'#656565\'; this.style.backgroundImage=\'url(<?php echo lc_href_link_admin('templates/' . $lC_Template->getCode() . '/img/icons/16/drag.png'); ?>)\'; this.style.backgroundRepeat=\'no-repeat\'; this.style.zIndex=\'300000 !important\'; this.style.backgroundPosition=\'8px 2px\';';
          
          if ( entry[6] == '1' ) { // default_flag
            //style += ' background-color: #E5EFE5;';
            style += ' ';

            //var onmouseover += 'this.style.backgroundColor=\'#656565\';';
            //var onmouseout = 'this.style.backgroundColor=\'#E5EFE5\'; this.style.backgroundImage=\'none\';';
            var onmouseout = 'this.style.backgroundColor=\'\'; this.style.backgroundImage=\'none\';';
          } else {
            var onmouseover = 'this.style.backgroundColor=\'#656565\';';
            //var onmouseout = 'this.style.backgroundColor=\'#FFFFFF\'; this.style.backgroundImage=\'none\';';
            var onmouseout = 'this.style.backgroundColor=\'\'; this.style.backgroundImage=\'none\';';
          }
        } else {
          var onmouseover = 'this.style.backgroundColor=\'#656565\';';
          //var onmouseover = '';
          var onmouseout = 'this.style.backgroundColor=\'\';';
          //var onmouseout = '';
        }

        var newdiv = '<span id="image_' + entry[0] + '" style="' + style + '" onmouseover="' + onmouseover + '" onmouseout="' + onmouseout + '">';
        newdiv += '<a href="' + entry[4] + '" target="_blank"><img class="framed" src="<?php echo DIR_WS_HTTP_CATALOG . 'images/products/mini/'; ?>' + entry[2] + '" border="0" height="<?php echo $lC_Image->getHeight('mini'); ?>" alt="' + entry[2] + '" title="' + entry[5] + ' bytes" style="max-width: <?php echo $lC_Image->getWidth('mini') + 20; ?>px;" /></a><br />' + entry[3];

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
      $('#imagesOriginal').html('<div id="showProgressOriginal" style="float: left; padding-left: 10px;"><?php echo lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_loading_from_server'); ?></div>');

      if ( makeCall != false ) {
        $.getJSON('<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $lC_ObjectInfo->getInt('products_id') . '&action=getImages&filter=originals'); ?>',
          function (data) {
            showImages(data);
          }
        );
      }
    }

    function getImagesOthers(makeCall) {
      $('#imagesOther').html('<div id="showProgressOther" style="float: left; padding-left: 10px;"><?php echo lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_loading_from_server'); ?></div>');

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
      layer1link.style.fontWeight='normal';
      layer2link.style.fontWeight='bolder';
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
                     "    <td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' name='price_breaks[qty][]' size='5' id='qty" + id + "' /></td><td width='70%'><?php echo $lC_Language->get('text_price_breaks_above'); ?></td></tr></table></td>" +
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
                     "    <td width='110px'><table width='110px' border='0' cellpadding='0' cellspacing='0'><tr><td><input type='text' name='price_breaks[qty][" + id + "][]' id='price_breaks[qty][" + id + "][]' size='5' id='qty" + id + "' /></td><td width='70%'><?php echo $lC_Language->get('text_price_breaks_above'); ?></td></tr></table></td>" +
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
          <li id="tabHeaderSectionContent" class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li id="tabHeaderSectionImages"><?php echo lc_link_object('#section_images_content', $lC_Language->get('section_images')); ?></li>
          <li id="tabHeaderSectionPricing"><?php echo lc_link_object('#section_pricing_content', $lC_Language->get('section_pricing')); ?></li>
          <li id="tabHeaderSectionData"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
          <li id="tabHeaderSectionOptions"<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? '' : ' style="display:none;"'); ?>><?php echo lc_link_object('#section_options_content', $lC_Language->get('section_options')); ?></li>
          <li id="tabHeaderSectionShipping"><?php echo lc_link_object('#section_shipping_content', $lC_Language->get('section_shipping')); ?></li>
          <li id="tabHeaderSectionRelationships"><?php echo lc_link_object('#section_relationships_content', $lC_Language->get('section_relationships')); ?></li>
        </ul>
        <div class="clearfix tabs-content">
          <!-- content_tab -->
          <div id="section_general_content" class="with-padding">
            <div class="columns">
              <div class="new-row-mobile four-columns twelve-columns-mobile">
                <!--<div class="twelve-columns hide-below-768" style="height:38px;">
                  &nbsp;
                </div>-->
                <div class="twelve-columns">
                  <span><?php echo $lC_Language->get('text_product_image'); ?></span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                  <dl class="accordion same-height small-margin-top">
                    <dt><?php echo $lC_Language->get('text_product_image_preview'); ?>
                      <!--<div class="button-group absolute-right compact mid-margin-right">
                        <a href="#" class="button icon-cloud-upload disabled">Upload</a>
                        <a href="#" class="button icon-trash with-tooltip disabled" title="Delete"></a>
                      </div>-->
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
                  <div class="twelve-columns no-margin-bottom">
                    <span class="small-margin-bottom"><?php echo $lC_Language->get('field_name'); ?></span>
                    <span class="info-spot on-left grey float-right small-margin-bottom">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom">
                    <span class="input full-width">  
                      <select name="pseudo-input-select" class="select compact expandable-list float-right prod-edit-lang-select">
                        <?php foreach ( $lC_Language->getAll() as $l ) { ?>
                        <option id="<?php echo $l['code']; ?>" value="<?php echo $l['code']; ?>"><?php echo $l['name']; ?></option>
                        <?php } ?>
                      </select>
                      <input type="text" class="required input-unstyled" style="width:60%;" value="<?php echo (isset($lC_ObjectInfo) && isset($products_name[$l['id']]) ? $products_name[$l['id']] : null); ?>" id="<?php echo 'products_name[' . $l['id'] . ']'; ?>" name="<?php echo 'products_name[' . $l['id'] . ']'; ?>">
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom mid-margin-top">
                    <span class="small-margin-bottom"><?php echo $lC_Language->get('field_description'); ?></span>
                    <span class="info-spot on-left grey float-right small-margin-bottom">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom">
                    <?php echo lc_draw_textarea_field('products_description[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_description[$l['id']]) ? $products_description[$l['id']] : null), null, 10, 'class="required input full-width autoexpanding clEditorProductDescription"'); ?>
                    <span class="float-right small-margin-top"><a href="#">Enlarge Description <span class="icon-extract icon-grey"></span></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="javascript:toggleEditor();">' . $lC_Language->get('text_toggle_html_editor') . '</a>'; ?></span>
                  </div>
                  <div class="twelve-columns no-margin-bottom mid-margin-top">
                    <span class="full-width">
                      <span class="small-margin-bottom"><?php echo $lC_Language->get('field_keyword'); ?></span>
                      <span class="info-spot on-left grey float-right small-margin-bottom">
                        <span class="icon-info-round"></span>
                        <span class="info-bubble">
                          Put the bubble text here
                        </span>
                      </span>
                    </span>
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
                    <div class="full-width clear-right mid-margin-bottom">
                      <?php echo lc_draw_input_field('products_keyword[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_keyword[$l['id']]) ? $products_keyword[$l['id']] : null), 'class="input full-width" id="keyword' . $l['id'] . '"'); ?>
                    </div>
                  </div>
                  <div class="twelve-columns no-margin-bottom mid-margin-top">
                    <span class="full-width">
                      <span class="small-margin-bottom"><?php echo $lC_Language->get('field_tags'); ?></span>
                      <span class="info-spot on-left grey float-right small-margin-bottom">
                        <span class="icon-info-round"></span>
                        <span class="info-bubble">
                          Put the bubble text here
                        </span>
                      </span>
                    </span>
                    <div class="full-width clear-right mid-margin-bottom">
                      <?php echo lc_draw_input_field('products_tags[' . $l['id'] . ']', (isset($lC_ObjectInfo) && isset($products_tags[$l['id']]) ? $products_tags[$l['id']] : null), 'class="input full-width" id="keyword' . $l['id'] . '"'); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
            </div>
            <div class="field-drop-product button-height black-inputs extreme-margin-bottom">
              <div class="columns">
                <div class="new-row-mobile four-columns twelve-columns-mobile">
                </div>
                <div class="new-row-mobile eight-columns twelve-columns-mobile">
                  <div style="width:100%;">
                    <div style="float:left;" class="new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet baseprice-status">
                      <span class="full-width">
                        <span><?php echo $lC_Language->get('field_base_price'); ?></span>
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
                        <span><?php echo $lC_Language->get('field_status'); ?></span>
                        <span class="info-spot on-left grey float-right mid-margin-top">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </span><br />
                      <span class="button-group">
                        <label for="ps_radio_1" class="button blue-active">
                          <input type="radio" name="product_status_radio_group" id="ps_radio_1" value="active"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == 1) ? ' checked' : ''); ?> />
                          <?php echo $lC_Language->get('field_status_active'); ?>
                        </label>
                        <label for="ps_radio_2" class="button blue-active">
                          <input type="radio" name="product_status_radio_group" id="ps_radio_2" value="inactive"<?php echo ((isset($lC_ObjectInfo) && $lC_ObjectInfo->getInt('products_status') == 0) ? ' checked' : ''); ?> />
                          <?php echo $lC_Language->get('field_status_inactive'); ?>
                        </label>
                        <label for="ps_radio_3" class="button blue-active disabled">
                          <input type="radio" name="product_status_radio_group" id="ps_radio_3" value="" />
                          <?php echo $lC_Language->get('field_status_coming'); ?>
                        </label>
                      </span>
                    </div>                  
                  </div>                  
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="four-columns twelve-columns-mobile large-margin-bottom">
                <center><img src="images/prodchart.png" /></center>
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
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b><?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1)) ? $lC_Language->get('text_complex_variants') : (($lC_ObjectInfo->get('products_model') != '') ? $lC_ObjectInfo->get('products_model') : $lC_Language->get('text_no_model')); ?></b></p>
                <span class="full-width">
                  <span><?php echo $lC_Language->get('field_date_available'); ?></span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b>Random Date Here</b></p>              
              </div>
              <div class="four-columns twelve-columns-mobile">
                <span class="full-width">
                  <span><?php echo $lC_Language->get('field_weight'); ?></span>
                  <span class="info-spot on-left grey float-right">
                    <span class="icon-info-round"></span>
                    <span class="info-bubble">
                      Put the bubble text here
                    </span>
                  </span>
                </span>
                <p style="background-color:#cccccc;" class="with-small-padding small-margin-top"><b><?php echo $lC_ObjectInfo->get('products_weight'); ?></b></p>              
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
                        <span class="icon file-jpg"></span><b>Product Images</b>
                      </li>
                      <!--<li id="additional-gallery-trigger" onclick="additionalGalleryTrigger();" class="grey">
                        <span class="icon folder-image"></span><b>Additional Images</b>
                      </li>-->
                    </ul>
                  </div> 
                </div>
              </div>
              <div class="panel-content linen" style="height:auto">
                <div class="panel-control align-right"></div>
                <div style="height: auto; position: relative;" class="panel-load-target scrollable with-padding custom-scroll">
                  <div class="gallery" id="images-gallery">
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="100%" height="100%" valign="top">
                          <div class="message white-gradient margin-bottom" style="min-height:37px;">
                            <div style="float: right;">
                              <?php echo $lC_Language->get('text_product_image_drag_n_drop'); ?>
                              <!--<a href="#" id="remoteFilesLink" onclick="switchImageFilesView('remote');" style="font-weight:bolder; color:#000;"><?php echo $lC_Language->get('image_remote_upload'); ?></a> | <a href="#" id="localFilesLink" onclick="switchImageFilesView('local');" style="color:#000;"><?php echo $lC_Language->get('image_local_files'); ?></a>-->
                            </div>
                            <div id="remoteFiles" style="white-space:nowrap;">
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
                              <p><?php echo $lC_Language->get('text_introduction_select_local_images'); ?></p>
                              <select id="localImagesSelection" name="localimages[]" size="5" multiple="multiple" style="width: 100%;"></select>
                              <div id="showProgressGetLocalImages" style="display: none; float: right; padding-right: 10px;"><?php echo lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_retrieving_local_files'); ?></div>
                              <p><?php echo realpath('../images/products/_upload'); ?></p>
                              <?php
                              if ( isset($lC_ObjectInfo) ) {
                                echo '<input type="button" value="Assign To Product" class="operationButton" onclick="assignLocalImages();" /><div id="showProgressAssigningLocalImages" style="display: none; padding-left: 10px;">' . lc_icon_admin('progress_ani.gif') . '&nbsp;' . $lC_Language->get('image_multiple_upload_progress') . '</div>';
                              }
                              ?>
                            </div>
                          </div>
                          <script type="text/javascript"><!--
                          getLocalImages();
                          //--></script>
                          <?php
                          if ( isset($lC_ObjectInfo) ) {
                            ?>
                            <div class="message white-gradient"><span class="anthracite"><strong><?php echo $lC_Language->get('subsection_original_images'); ?></strong></span></div>
                            <div id="imagesOriginal" style="overflow: auto;" class="small-margin-top"></div>
                            <div class="message white-gradient"><span class="anthracite"><strong><?php echo $lC_Language->get('subsection_images'); ?></strong></span></div>
                            <div id="imagesOther" style="overflow: auto;"></div>
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
                  <!--<div class="gallery" id="additional-gallery" style="display:none;">
                    Saved For Later
                  </div>-->
                </div>
              </div>
            </div>
          </div>
          <!-- pricing_tab -->
          <div id="section_pricing_content" class="with-padding">
            <fieldset class="fieldset fields-list">
              <legend class="legend"><?php echo $lC_Language->get('text_pricing_overrides'); ?></legend>
              <div class="field-block button-height">
                <label for="" class="label"><b><?php echo $lC_Language->get('text_base_price'); ?></b></label>
                <input type="text" name="" id="" value="<?php echo lc_round($lC_ObjectInfo->get('products_price'), DECIMAL_PLACES); ?>" class="input" />
              </div>
              <!-- lc_group_pricing begin -->
              <div class="field-block field-block-product button-height">
                <label for="" class="label"><b><?php echo $lC_Language->get('text_group_pricing'); ?></b></label>
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
                  <input type="checkbox" class="switch disabled margin-right" checked />
                  <span class="nowrap">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <!-- if specials enabled <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;color:#ff0000;" />-->
                  </span>
                  <small class="input-info">Price<!-- if specials enabled /Special--></small>
                </div>
                <div>
                  <label for="" class="label margin-right"><b>Employee Group</b></label>
                  <input type="checkbox" class="switch disabled margin-right" checked />
                  <span class="nowrap">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <!-- if specials enabled <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;color:#ff0000;" />-->
                  </span>
                  <small class="input-info"><?php echo $lC_Language->get('subsection_price'); ?><!-- if specials enabled /Special--></small>
                </div>
                <div>
                  <label for="" class="label margin-right"><b>Gold Reseller</b></label>
                  <input type="checkbox" class="switch disabled margin-right" checked />
                  <span class="nowrap">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <!-- if specials enabled <input type="text" name="" id="" value="" class="input small-margin-right" disabled style="width:60px;text-align:right;color:#ff0000;" />-->
                  </span>
                  <small class="input-info"><?php echo $lC_Language->get('subsection_price'); ?><!-- if specials enabled /Special--></small>
                </div>
                <?php //} ?>
              </div>
              <!-- lc_group_pricing end -->
              <!-- lc_qty_price_breaks begin -->
              <div class="field-block field-block-product button-height">
                <label for="" class="label"><b><?php echo $lC_Language->get('text_qty_break_pricing'); ?></b></label>
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
                    <small class="input-info small-margin-right"><?php echo $lC_Language->get('text_qty'); ?></small>
                  </span>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;" />
                    <!--<input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <small class="input-info"><?php echo $lC_Language->get('subsection_price'); ?><!-- if specials enabled /Special--></small>
                    <?php //} ?>
                  </span>
                </div>
                <div>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="10" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <small class="input-info small-margin-right"><?php echo $lC_Language->get('text_qty'); ?></small>
                  </span>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;" />
                    <!--<input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <small class="input-info"><?php echo $lC_Language->get('subsection_price'); ?><!-- if specials enabled /Special--></small>
                    <?php //} ?>
                  </span>
                </div>
                <div> 
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="50" class="input small-margin-right" disabled style="width:60px;text-align:right;" />
                    <small class="input-info small-margin-right"><?php echo $lC_Language->get('text_qty'); ?></small>
                  </span>
                  <span style="white-space:nowrap;">
                    <input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;" />
                    <!--<input type="text" name="" id="" value="" class="input small-margin-right" style="width:60px;text-align:right;color:#ff0000;" />-->
                    <small class="input-info"><?php echo $lC_Language->get('subsection_price'); ?><!-- if specials enabled /Special--></small>
                    <?php //} ?>
                  </span>
                </div>
                <?php //} ?>
              </div>
              <!-- lc_qty_price_breaks end --> 
              <div class="field-block field-block-product button-height">
                <label for="specials-pricing-switch" class="label"><b><?php echo $lC_Language->get('text_special_pricing'); ?></b></label>
                <input onchange="$('#specials_pricing_container').toggle('300');" id="specials-pricing-switch" type="checkbox" class="switch wider" data-text-off="DISABLED" data-text-on="ENABLED"<?php echo (($Qspecials->value('specials_new_products_price') != null) ? ' checked' : ''); ?> />
                <span class="info-spot on-left grey margin-left margin-right">
                  <span class="icon-info-round"></span>
                  <span class="info-bubble">
                    Put the bubble text here
                  </span>
                </span>
              </div>
              <div id="specials_pricing_container" class="field-drop button-height black-inputs"<?php echo (($Qspecials->value('specials_new_products_price') != null) ? ' style="display:block;"' : ' style="display:none;"'); ?>>
                <?php //foreach () { ?>
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
                <?php //} ?>
              </div>                
            </fieldset>
            <!--<dl class="accordion same-height">
              <dt>Retail Price</dt>
              <dd>
                <?php // if no options set ?>
                  <!-- Please Create your inventory Option
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
                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Special&nbsp;Price&nbsp;&nbsp;
                    <span class="info-spot on-left grey">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                </div>
                <div class="left-column-200px margin-bottom clear-left with-mid-padding">
                  <?php //foreach() { ?>
                  <div class="left-column with-small-padding">
                    Red Medium - KSRM0001
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      &nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />
                    <?php // } ?>
                  </div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding silver-bg">
                    Red Large - KSRL0023
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      &nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />
                    <?php // } ?>
                  </div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding">
                    Red X Large - KSRXL0011
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      &nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />
                    <?php // } ?>
                  </div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding silver-bg">
                    Green Medium - KSGM0054
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      &nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />
                    <?php // } ?>
                  </div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding">
                    Green Large - KSGL0055
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      &nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />
                    <?php // } ?>
                  </div>
                  <div style="height:5px;"></div>
                  <div class="left-column with-small-padding silver-bg">
                    Green X Large - KSGXL0167
                  </div>
                  <div class="right-column">
                    <input class="input" value="" name="" id="" style="width:60px;text-align:right;" />
                    <?php // if special price ?>
                      &nbsp;&nbsp;<input class="input" value="" name="" id="" style="width:60px;text-align:right;color:#ff0000;" />
                    <?php // } ?>
                  </div>
                  <div style="height:5px;"></div>
                  <?php //} //} ?>
                </div>
              </dd>
            </dl>-->
          </div>
          <!-- data_tab -->
          <div id="section_data_content" class="with-padding">
            <fieldset class="fieldset">
              <legend class="legend"><?php echo $lC_Language->get('text_inventory_settings'); ?></legend>
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
                    <input type="text" class="required input full-width" value="<?php echo (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_model') : null); ?>" id="products_model" name="products_model" />
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <!-- lc_track_inventory_override begin -->
                  <div class="twelve-columns no-margin-bottom">
                    <span><?php echo $lC_Language->get('text_track_inventory_override'); ?></span>
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
                      <label for="ti_radio_1" class="button disabled">
                        <input type="radio" name="track_inventory_radio_group" id="ti_radio_1" value="1" />
                        <?php echo $lC_Language->get('text_default'); ?>
                      </label>
                      <label for="ti_radio_2" class="button disabled">
                        <input type="radio" name="track_inventory_radio_group" id="ti_radio_2" value="2" />
                        <?php echo $lC_Language->get('text_on'); ?>
                      </label>
                      <label for="ti_radio_3" class="button disabled">
                        <input type="radio" name="track_inventory_radio_group" id="ti_radio_3" value="3" />
                        <?php echo $lC_Language->get('text_off'); ?>
                      </label>
                    </span>
                  </div>
                  <!-- lc_track_inventory_override end -->
                </div>
              </div>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span><?php echo $lC_Language->get('text_msrp'); ?></span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="" name="" disabled />
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <!-- lc_vendor_supplier begin -->
                  <div class="twelve-columns no-margin-bottom">
                    <span><?php echo $lC_Language->get('text_vendor_supplier'); ?></span>
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
                    <span><?php echo $lC_Language->get('text_inventory_control'); ?></span>
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
                      <label for="ic_radio_1" class="button green-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? '' : ' active'); ?>">
                        <!-- move onclick to function later maestro -->
                        <input type="radio" name="inventory_control_radio_group" id="ic_radio_1" value="1" onclick="$('#inventory_control_simple').show('300');$('#inventory_control_multi').hide('300');$('#section_options_content').hide();$('#tabHeaderSectionOptions').hide();" />
                        <?php echo $lC_Language->get('text_simple'); ?>
                      </label>
                      <label for="ic_radio_2" class="button green-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? ' active' : ''); ?>">
                        <!-- move onclick to function later maestro -->
                        <input type="radio" name="inventory_control_radio_group" id="ic_radio_2" value="2" onclick="$('#inventory_control_simple').hide('300');$('#inventory_control_multi').show('300');$('#tabHeaderSectionOptions').show().removeClass('active');$('label[for=\'ic-radio-1\']').removeClass('active');$('label[for=\'ic-radio-2\']').addClass('active');$('label[for=\'ioc-radio-1\']').removeClass('active');$('label[for=\'ioc-radio-2\']').addClass('active');" />
                        <?php echo $lC_Language->get('text_multi_sku'); ?>
                      </label>
                      <label for="ic_radio_3" class="button disabled green-active">
                        <input type="radio" name="inventory_control_radio_group" id="ic_radio_3" value="3" />
                        <?php echo $lC_Language->get('text_recurring'); ?>
                      </label>
                    </span>
                  </div>
                  <!-- lc_inventory_control end -->
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">&nbsp;</div>
              </div>
              <div id="inventory_control_container" class="field-drop button-height black-inputs no-margin-bottom">
                <!-- lc_inventory_control_simple begin -->
                <div id="inventory_control_simple"<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? ' style="display:none;"' : ''); ?>>
                  <div>
                    <label for="" class="label"><b><?php echo $lC_Language->get('text_qty_on_hand'); ?></b></label>
                    <input type="text" name="products_quantity" id="products_quantity" value="<?php echo $lC_ObjectInfo->get('products_quantity'); ?>" class="input small-margin-right" style="width:60px;" />
                    <input type="text" name="products_sku_ean13" id="products_sku_ean13" value="" class="input" disabled />
                    <b><?php echo $lC_Language->get('text_sku_ean13'); ?></b>
                  </div>
                  <div class="small-margin-top">
                    <input type="text" name="" id="" value="" class="input small-margin-right" disabled /> <b><?php echo $lC_Language->get('text_cost'); ?></b>
                    <span class="info-spot on-left grey small-margin-left">
                      <small class="tag red-bg" style="border:2px solid grey;">Pro</small>
                      <span class="info-bubble">
                        <b>Go Pro!</b> and enjoy this feature!
                      </span>
                    </span> 
                  </div>
                </div>
                <!-- lc_inventory_control_simple end -->                                       
                <div id="inventory_control_multi"<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? '' : ' style="display:none;"'); ?>>
                  <span class="icon-warning icon icon-size2 icon-orange small-margin-right"></span> <?php echo $lC_Language->get('text_edit_qoh_sku'); ?>
                </div>
              </div>
            </fieldset>
            <fieldset class="fieldset">
              <legend class="legend"><?php echo $lC_Language->get('text_tax_settings'); ?></legend>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span><?php echo $lC_Language->get('text_tax_class'); ?></span>
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
                    <span><?php echo $lC_Language->get('text_base_price_with_tax'); ?></span>
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
              <legend class="legend"><?php echo $lC_Language->get('text_management_settings'); ?></legend>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span><?php echo $lC_Language->get('text_manufacturer'); ?></span> 
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
                    <span><?php echo $lC_Language->get('text_product_class'); ?></span>
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
                    <span><?php echo $lC_Language->get('text_url_slug'); ?></span>
                    <span class="info-spot on-left grey float-right">
                      <span class="icon-info-round"></span>
                      <span class="info-bubble">
                        Put the bubble text here
                      </span>
                    </span>
                  </div>
                  <div class="twelve-columns no-margin-bottom small-margin-top">
                    <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="" name="" disabled />
                  </div>
                </div>
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span><?php echo $lC_Language->get('text_availability'); ?></span>
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
              <legend class="legend"><?php echo $lC_Language->get('text_product_details'); ?></legend>
              <div class="columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <?php //foreach() { ?>
                  <div class="margin-bottom">
                    <label for="" class="label">Custom Field 1</label>
                    <input type="text" name="" id="" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" class="input" disabled />
                  </div>
                  <?php //} ?>
                  <div class="margin-bottom">
                    <label for="" class="label">Custom Field 2</label>
                    <input type="text" name="" id="" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" class="input" disabled />
                  </div>
                  <div> 
                    <label for="" class="label">Custom Field 3</label>
                    <input type="text" name="" id="" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" class="input" disabled />
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
          <div id="section_options_content" class="with-padding"<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? '' : ' style="display:none;"'); ?>>
            <div class="columns">
              <div class="twelve-columns">
                <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                  <div class="twelve-columns no-margin-bottom">
                    <span class="large-margin-right"><?php echo $lC_Language->get('text_inventory_control'); ?></span>
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
                      <label for="ioc_radio_1" class="button green-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? '' : ' active'); ?>">
                        <!-- move onclick to function later maestro -->
                        <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_1" value="1" onclick="$('#inventory_control_simple').show('300');$('#inventory_control_multi').hide('300');$('#section_options_content').hide();$('#tabHeaderSectionOptions').hide();$('#section_data_content').show();$('#tabHeaderSectionData').addClass('active');$('label[for=\'ic-radio-1\']').addClass('active');$('label[for=\'ic-radio-2\']').removeClass('active');" />
                        <?php echo $lC_Language->get('text_simple'); ?>
                      </label>
                      <label for="ioc_radio_2" class="button green-active<?php echo (isset($lC_ObjectInfo) && ($lC_ObjectInfo->getInt('has_children') == 1) ? ' active' : ''); ?>">
                        <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_2" value="2" />
                        <?php echo $lC_Language->get('text_multi_sku'); ?>
                      </label>
                      <label for="ioc_radio_3" class="button disabled green-active">
                        <input type="radio" name="inventory_option_control_radio_group" id="ioc_radio_3" value="3" />
                        <?php echo $lC_Language->get('text_recurring'); ?>
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
                  <legend class="legend"><?php echo $lC_Language->get('text_inventory_options_combo_sets'); ?></legend>
                  <div class="columns">
                    <div class="twelve-columns">
                      Data Table is Going to be here :)                   
                    </div>
                  </div>
                  <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)"><?php echo $lC_Language->get('text_new_inventory_option_combo_set'); ?></a></div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend"><?php echo $lC_Language->get('text_simple_inventory_options'); ?></legend>
                  <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)"><?php echo $lC_Language->get('text_new_simple_inventory_option'); ?></a></div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend"><?php echo $lC_Language->get('text_simple_options'); ?></legend>
                  <div><a class="button icon-plus icon-size2 icon-green margin-bottom nowrap" href="javascript:void(0)"><?php echo $lC_Language->get('text_new_simple_option'); ?></a></div>
                </fieldset>
              </div>
            </div>
          </div>
          <!-- shipping_tab -->
          <div id="section_shipping_content" class="with-padding">
            <div class="columns">
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend"><?php echo $lC_Language->get('text_product_characteristics'); ?></legend>
                  <div class="columns">
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('field_weight'); ?></span>
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
                      <div class="twelve-columns no-margin-bottom margin-top grey disabled">
                        <?php echo $lC_Language->get('text_non_shippable_item'); ?>
                        <input type="checkbox" id="virtual" name="virtual" disabled /> 
                        <?php echo $lC_Language->get('text_coming_soon'); ?>
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('text_dimensional'); ?></span>
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
                      <div class="twelve-columns no-margin-bottom margin-top">
                        <div class="twelve-columns clear-both">
                          <div style="width:50px;" class="float-left small-margin-top"><label for="product_length" class="label"><?php echo $lC_Language->get('text_length'); ?></label></div>
                          <input type="text" class="input unstyled margin-bottom float-left" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="product_length" name="product_length" disabled />
                        </div>
                        <div class="twelve-columns clear-both">
                          <div style="width:50px;" class="float-left small-margin-top"><label for="product_length" class="label"><?php echo $lC_Language->get('text_width'); ?></label></div>
                          <input type="text" class="input unstyled margin-bottom" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="product_width" name="product_width" disabled />
                        </div>
                        <div class="twelve-columns">
                          <div style="width:50px;" class="float-left small-margin-top"><label for="product_length" class="label"><?php echo $lC_Language->get('text_height'); ?></label></div>
                          <input type="text" class="input unstyled margin-bottom" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="product_height" name="product_height" disabled />
                        </div>
                      </div>
                    </div>                
                  </div>
                  <div class="columns">
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('field_weight_class'); ?></span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <?php echo lc_draw_pull_down_menu('products_weight_class', $weight_class_array, (isset($lC_ObjectInfo) ? $lC_ObjectInfo->get('products_weight_class') : SHIPPING_WEIGHT_UNIT), 'class="select full-width small-margin-top required"'); ?>
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      &nbsp;
                    </div>                
                  </div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend"><?php echo $lC_Language->get('text_order_fee_modifiers'); ?></legend>
                  <div class="columns">
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('text_shipping_fee_override'); ?></span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="" name="" disabled /><small class="small-margin-top"><?php echo $lC_Language->get('text_zero_for_free_shipping'); ?></small>
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('text_add_handling_fee'); ?></span>
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
                        <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="" name="" disabled />
                      </div>
                    </div>                
                  </div>
                </fieldset>
              </div>
              <div class="twelve-columns">
                <fieldset class="fieldset">
                  <legend class="legend"><?php echo $lC_Language->get('text_supplier_characteristics'); ?></legend>
                  <div class="columns">
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('text_days_to_ship'); ?></span>
                        <span class="info-spot on-left grey float-right">
                          <span class="icon-info-round"></span>
                          <span class="info-bubble">
                            Put the bubble text here
                          </span>
                        </span>
                      </div>
                      <div class="twelve-columns no-margin-bottom small-margin-top">
                        <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="" name="" disabled />
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('text_warehouse'); ?></span>
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
                        <input type="text" class="required input full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" id="" name="" disabled />
                      </div>
                    </div>
                    <div class="new-row-mobile six-columns six-columns-tablet twelve-columns-mobile">
                      <div class="twelve-columns no-margin-bottom">
                        <span><?php echo $lC_Language->get('text_stock_date_expected'); ?></span>
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
                            <input type="text" placeholder="" class="input-unstyled datepicker full-width" value="<?php echo $lC_Language->get('text_coming_soon'); ?>" disabled />
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
            <fieldset class="fieldset">
              <legend class="legend"><?php echo $lC_Language->get('text_categories'); ?></legend>
              <table border="0" width="100%" cellspacing="0" cellpadding="2" style="margin-top:-10px;">
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
              <br />
            </fieldset>
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
              <!--<select class="select expandable-list"> 
                <option id="" value="">Related</option>
                <option id="create_order" value="create_order">Create Order</option>
                <option id="duplicate_product" value="duplicate_product">Duplicate</option>
                <option id="catalog_view" value="catalog_view">View In Catalog</option>
                <option id="view_customers" value="view_customers">View Customers</option>
                <option id="notify_customers" value="notify_customers">Notify Customers</option>
              </select>&nbsp;
              <select class="select expandable-list" style="width:96px;"> 
                <option id="" value="">Actions</option>
                <option id="save" value="save">Save</option>
                <option id="apply_changes" value="apply_changes">Apply</option>
              </select>&nbsp;-->
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