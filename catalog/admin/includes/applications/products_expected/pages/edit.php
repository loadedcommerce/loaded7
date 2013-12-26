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
$lC_ObjectInfo = new lC_ObjectInfo(lC_Products_Admin::get($_GET['pID']));
$Qdata = $lC_Database->query('select str_to_date(pa.value, "%Y-%m-%d") as products_date_available from :table_product_attributes pa, :table_templates_boxes tb where tb.code = :code and tb.modules_group = :modules_group and tb.id = pa.id');
$Qdata->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
$Qdata->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
$Qdata->bindValue(':code', 'date_available');
$Qdata->bindValue(':modules_group', 'product_attributes');
$Qdata->execute();
$lC_ObjectInfo->set('products_date_available', $Qdata->value('products_date_available'));
?>
<h1><?php echo lc_link_object(lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule()), $lC_Template->getPageTitle()); ?></h1>
<?php
if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
  echo $lC_MessageStack->get($lC_Template->getModule());
}
?>
<div class="infoBoxHeading"><?php echo lc_icon_admin('edit.png') . ' ' . $lC_ObjectInfo->getProtected('products_name'); ?></div>
<div class="infoBoxContent">
  <form name="pEdit" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&page=' . $_GET['page'] . '&pID=' . $lC_ObjectInfo->getInt('products_id') . '&action=save'); ?>" method="post">
  <p><?php echo $lC_Language->get('introduction_edit_product_expected'); ?></p>
  <p><?php echo $lC_Language->get('field_date_expected') . '<br />' . lc_draw_input_field('products_date_available', $lC_ObjectInfo->get('products_date_available')); ?></p>
  <p align="center"><?php echo lc_draw_hidden_field('subaction', 'confirm') . '<input type="submit" value="' . $lC_Language->get('button_save') . '" class="operationButton" /> <input type="button" value="' . $lC_Language->get('button_cancel') . '" onclick="document.location.href=\'' . lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&page=' . $_GET['page']) . '\';" class="operationButton" />'; ?></p>
  </form>
</div>
<script type="text/javascript">
  $(function() {
    $("#products_date_available").datepicker( {
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true
    } );
  });
</script>