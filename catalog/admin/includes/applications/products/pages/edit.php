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
  $pInfo = new lC_ObjectInfo(lC_Products_Admin::get($_GET[$lC_Template->getModule()]));
}

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
//$Qpi = $lC_Database->query("select image from :table_products_images where products_id = :products_id and default_flag = '1'");
//$Qpi->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
//$Qpi->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
//$Qpi->execute();

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
//$Qspecials = $lC_Database->query('select * from :table_specials where products_id = :products_id');
//$Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
//$Qspecials->bindInt(':products_id', $lC_ObjectInfo->getInt('products_id'));
//$Qspecials->execute();

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
    
    // added to update base price field
    document.getElementById('products_base_price').value = grossValue;
    
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
<style>
.legend { font-weight:bold; font-size: 1.1em; }
</style>
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
  <div class="with-padding-no-top small-margin-top">
    <form name="product" id="product" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($lC_ObjectInfo) ? $lC_ObjectInfo->getInt('products_id') : '') . '&cID=' . $_GET['cID'] . '&action=save'); ?>" method="post" enctype="multipart/form-data">
      <div id="product_tabs" class="side-tabs" style="position:relative;">  
        <ul class="tabs">
          <li id="tabHeaderSectionContent" class="active"><?php echo lc_link_object('#section_general_content', $lC_Language->get('section_general')); ?></li>
          <li id="tabHeaderSectionImages"><?php echo lc_link_object('#section_images_content', $lC_Language->get('section_images')); ?></li>
          <li id="tabHeaderSectionData"><?php echo lc_link_object('#section_data_content', $lC_Language->get('section_data')); ?></li>
          <li id="tabHeaderSectionOptions"><?php echo lc_link_object('#section_options_content', $lC_Language->get('section_options')); ?></li>
          <li id="tabHeaderSectionPricing"><?php echo lc_link_object('#section_pricing_content', $lC_Language->get('section_pricing')); ?></li>
          <li id="tabHeaderSectionShipping"><?php echo lc_link_object('#section_shipping_content', $lC_Language->get('section_shipping')); ?></li>
          <li id="tabHeaderSectionRelationships"><?php echo lc_link_object('#section_relationships_content', $lC_Language->get('section_relationships')); ?></li>
        </ul>
        <div class="tabs-content">
          <?php 
          include('includes/applications/products/pages/tabs/content.php'); 
          include('includes/applications/products/pages/tabs/images.php');  
          include('includes/applications/products/pages/tabs/data.php'); 
          include('includes/applications/products/pages/tabs/options.php'); 
          include('includes/applications/products/pages/tabs/pricing.php');
          include('includes/applications/products/pages/tabs/shipping.php'); 
          include('includes/applications/products/pages/tabs/relationships.php'); 
          ?> 
        </div>
      </div>
      <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    </form>
    
    <div class="clear-both"></div>
    
    <div id="floating-button-container" class="six-columns twelve-columns-tablet margin-bottom">
      <div id="floating-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div style="float:right;">
            <p class="button-height" align="right">
              <a class="button" href="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()); ?>">
                <span class="button-icon red-gradient glossy">
                  <span class="icon-cross"></span>
                </span><span class="button-cancel"><?php echo $lC_Language->get('button_cancel'); ?></span>
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
                </span><span class="button-save"><?php echo $lC_Language->get('button_save'); ?></span>
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
<script>
function validateForm(e) {
  // turn off messages
  jQuery.validator.messages.required = "";

  var pid = '<?php echo $_GET[$lC_Template->getModule()]; ?>';
  var jsonVKUrl = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=validateKeyword&pid=PID'); ?>';
  var bValid = $("#product").validate({
    invalidHandler: function() {
    },
    rules: {
      <?php
      foreach ( $lC_Language->getAll() as $l ) {
        ?>
        'products_keyword[<?php echo $l['id']; ?>]': {
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
    
    
  }).form();
  if (bValid) {
    $(e).submit();
  }

  return false;
}           
</script>