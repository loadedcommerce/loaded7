<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: relationships.php v1.0 2013-08-08 datazen $
*/
global $lC_Language, $product_categories_array, $assignedCategoryTree;
?>
<div id="section_relationships_content" class="with-padding"> 
  <fieldset class="fieldset">
    <legend class="legend"><?php echo $lC_Language->get('text_categories'); ?></legend>
    <table border="0" width="100%" cellspacing="0" cellpadding="2" style="margin-top:-10px;">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tbody>
            <?php
              foreach ($assignedCategoryTree->getArray() as $value) {
                if ($value['mode'] == 'category') {
                  echo '<tr>' . "\n" .
                       '  <td width="30px" class="cat_rel_td">' . lc_draw_checkbox_field('categories[]', $value['id'], in_array($value['id'], $product_categories_array), 'class="checkbox" id="categories_' . $value['id'] . '"') . '</td>' . "\n" .
                       '  <td class="cat_rel_td"><a href="#" onclick="document.product.categories_' . $value['id'] . '.checked=!document.product.categories_' . $value['id'] . '.checked;">' . $value['title'] . '</a></td>' . "\n" .
                       '</tr>' . "\n";
                }
              }
            ?>
          </tbody>
        </table></td>
      </tr>
    </table>
    <br />
  </fieldset>
</div>