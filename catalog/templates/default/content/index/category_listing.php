<?php
/*
  $Id: category_listing.php v1.0 2011-11-04 datazen $ 

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<!--CATEGORY LISTING SECTION STARTS-->
  <div class="full_page">
    <!--CATEGORY LISTING CONTENT STARTS-->
    <div class="content">
      <div class="short-code-column">
        <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
            <?php
              if (isset($cPath) && strpos($cPath, '_')) {
                // check to see if there are deeper categories within the current category
                $category_links = array_reverse($cPath_array);
                for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
                  $Qcategories = $lC_Database->query('select count(*) as total from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id');
                  $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
                  $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
                  $Qcategories->bindInt(':parent_id', $category_links[$i]);
                  $Qcategories->bindInt(':language_id', $lC_Language->getID());
                  $Qcategories->execute();

                  if ($Qcategories->valueInt('total') < 1) {
                    // do nothing, go through the loop
                  } else {
                    $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id order by sort_order, cd.categories_name');
                    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
                    $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
                    $Qcategories->bindInt(':parent_id', $category_links[$i]);
                    $Qcategories->bindInt(':language_id', $lC_Language->getID());
                    $Qcategories->execute();
                    break; // we've found the deepest category the customer is in
                  }
                }
              } else {
                $Qcategories = $lC_Database->query('select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from :table_categories c, :table_categories_description cd where c.parent_id = :parent_id and c.categories_id = cd.categories_id and cd.language_id = :language_id order by sort_order, cd.categories_name');
                $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
                $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
                $Qcategories->bindInt(':parent_id', $current_category_id);
                $Qcategories->bindInt(':language_id', $lC_Language->getID());
                $Qcategories->execute();
              }
              $number_of_categories = $Qcategories->numberOfRows();
              $rows = 0;
              while ($Qcategories->next()) {
                $rows++;
                $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
                $exists = ($Qcategories->value('categories_image') != null) ? true : false;
                echo '    <td style="text-align:center;" class="categoryListing" width="' . $width . '" valign="top">' . lc_link_object(lc_href_link(FILENAME_DEFAULT, 'cPath=' . $lC_CategoryTree->buildBreadcrumb($Qcategories->valueInt('categories_id'))), ( ($exists === true) ? lc_image(DIR_WS_IMAGES . 'categories/' . $Qcategories->value('categories_image'), $Qcategories->value('categories_name')) : lc_image(DIR_WS_TEMPLATE_IMAGES . 'no_image.png', $lC_Language->get('image_not_found')) ) . '<br />' . $Qcategories->value('categories_name')) . '</td>' . "\n";
                if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
                  echo '  </tr>' . "\n";
                  echo '  <tr>' . "\n";
                }
              }
            ?>
            </tr>
          </table>
      </div>
      <div style="clear:both;">&nbsp;</div>
      <!--CATEGORY LISTING ACTIONS ENDS-->
    </div>
    <!--CATEGORY LISTING CONTENT ENDS-->
  </div>
<!--CATEGORY LISTING SECTION ENDS-->