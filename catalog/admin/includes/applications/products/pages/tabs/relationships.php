<?php
/**
  $Id: relationships.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
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
                echo '<tr>' . "\n" .
                     '  <td width="30px" class="cat_rel_td">' . lc_draw_checkbox_field('categories[]', $value['id'], in_array($value['id'], $product_categories_array), 'class="checkbox" id="categories_' . $value['id'] . '"') . '</td>' . "\n" .
                     '  <td class="cat_rel_td"><a href="#" onclick="document.product.categories_' . $value['id'] . '.checked=!document.product.categories_' . $value['id'] . '.checked;">' . $value['title'] . '</a></td>' . "\n" .
                     '</tr>' . "\n";
              }
            ?>
          </tbody>
        </table></td>
      </tr>
    </table>
    <br />
  </fieldset>
</div>