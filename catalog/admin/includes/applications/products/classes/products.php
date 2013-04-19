<?php
/*
  $Id: products.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Products_Admin class manages products
*/
class lC_Products_Admin {
 /*
  * Returns the products datatable data for listings
  *
  * @param string $category_id The category id used to filter
  * @access public
  * @return array
  */
  public static function getAll($category_id = null) {
    global $lC_Database, $lC_Language, $lC_Currencies, $_module;

    if ( !is_numeric($category_id) ) {
      $category_id = 0;
    }

    $media = $_GET['media'];
    
    $result = array('aaData' => array());

    if ( $category_id > 0 ) {
      $lC_CategoryTree = new lC_CategoryTree_Admin();
      $lC_CategoryTree->setBreadcrumbUsage(false);

      $in_categories = array($category_id);

      foreach ( $lC_CategoryTree->getArray($category_id) as $category ) {
        $in_categories[] = $category['id'];
      }

      $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS distinct p.*, pd.products_name, pd.products_keyword from :table_products p, :table_products_description pd, :table_products_to_categories p2c where p.parent_id = 0 and p.products_id = pd.products_id and pd.language_id = :language_id and p.products_id = p2c.products_id and p2c.categories_id in (:categories_id)');
      $Qproducts->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qproducts->bindRaw(':categories_id', implode(',', $in_categories));
    } else {
      $Qproducts = $lC_Database->query('select SQL_CALC_FOUND_ROWS p.*, pd.products_name, pd.products_keyword from :table_products p, :table_products_description pd where p.parent_id = 0 and p.products_id = pd.products_id and pd.language_id = :language_id');
    }

    $Qproducts->appendQuery('order by pd.products_name');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());

    $Qproducts->execute();

    while ( $Qproducts->next() ) {
      $price = $lC_Currencies->format($Qproducts->value('products_price'));
      $products_status = ($Qproducts->valueInt('products_status') === 1);
      $products_quantity = $Qproducts->valueInt('products_quantity');
      $products_keyword  = $Qproducts->value('products_keyword');

      $product_icon = 'icon-bag icon-orange';
      if ( $Qproducts->valueInt('has_children') === 1 ) {
        $Qvariants = $lC_Database->query('select min(products_price) as min_price, max(products_price) as max_price, sum(products_quantity) as total_quantity, min(products_status) as products_status from :table_products where parent_id = :parent_id');
        $Qvariants->bindTable(':table_products', TABLE_PRODUCTS);
        $Qvariants->bindInt(':parent_id', $Qproducts->valueInt('products_id'));
        $Qvariants->execute();

        $products_status = ($Qvariants->valueInt('products_status') === 1);
        $products_quantity = '(' . $Qvariants->valueInt('total_quantity') . ')';

        $price = $lC_Currencies->format($Qvariants->value('min_price'));

        if ( $Qvariants->value('min_price') != $Qvariants->value('max_price') ) {
          $price .= ' - ' . $lC_Currencies->format($Qvariants->value('max_price'));
        }
        $product_icon = 'icon-paperclip icon-blue';
      }

      $extra_data = array('products_price_formatted' => $price,
                          'products_status' => $products_status,
                          'products_quantity' => $products_quantity,
                          'products_keyword' => $products_keyword);

      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qproducts->valueInt('products_id') . '" id="' . $Qproducts->valueInt('products_id') . '"></td>';
      $products = '<td><a href="javascript://" onclick="showPreview(\'' . $Qproducts->valueInt('products_id') . '\')"><span class="' . $product_icon . ' with-tooltip small-margin-right" title="' . $lC_Language->get('icon_preview') . '"></span>' . $Qproducts->value('products_name') . '</td>';
      $price = '<td>' . $price . '</td>';
      $qty = '<td>' . $products_quantity . '</td>';


      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qproducts->valueInt('products_id') . '&cID=' . $category_id . '&action=save')) . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qproducts->valueInt('products_id') . '&cID=' . $category_id . '&action=save&old=old')) . '" class="button icon-backward with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '" title="Old Edit"></a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="copyProduct(\'' . $Qproducts->valueInt('products_id') . '\', \'' . urlencode($Qproducts->value('products_name')) . '\')') . '" class="button icon-pages with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_copy') . '"></a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteProduct(\'' . $Qproducts->valueInt('products_id') . '\', \'' . urlencode($Qproducts->value('products_name')) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';
      $result['aaData'][] = array("$check", "$products", "$price", "$qty", "$action");
      $result['entries'][] = array_merge($Qproducts->toArray(), $extra_data);
    }

    $Qproducts->freeResult();

    return $result;
  }
 /*
  * Returns the data used on the dialog forms
  *
  * @param integer $id The products id
  * @access public
  * @return array
  */
  public static function getProductFormData($id = null) {
    global $_module, $lC_Database, $lC_Language;

    $lC_Language->loadIniFile('products.php');
    $lC_CategoryTree = new lC_CategoryTree_Admin();

    $result = array();

    if (isset($id) && is_numeric($id)) {

      $Qcategories = $lC_Database->query('select categories_id from :table_products_to_categories where products_id = :products_id');
      $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcategories->bindInt(':products_id', $id);
      $Qcategories->execute();

      $in_categories = array();
      while ( $Qcategories->next() ) {
        $in_categories[] = $Qcategories->valueInt('categories_id');
      }

      $cnt = 0;
      $in_categories_path = '';
      foreach ( $in_categories as $category_id ) {
        $in_categories_path .= $lC_CategoryTree->getPath($category_id, 0, ' &raquo; ') . '<br />';
        if ($category_id == 0) {
          $categories_array[] = array('id' => $category_id,
                                      'text' => $lC_Language->get('top_category'));
        } else {
          $categories_array[] = array('id' => $category_id,
                                      'text' => $lC_CategoryTree->getPath($category_id, 0, ' &raquo; '));
        }
        $cnt++;
      }
      $result['inCategoriesCount'] = $cnt;
      $result['inCategoriesCheckbox'] = lc_draw_checkbox_field('product_categories[]', $categories_array, true, null, '&nbsp;<br />');

      if ( !empty($in_categories_path) ) {
        $in_categories_path = substr($in_categories_path, 0, -6);
        if (substr($in_categories_path, 0, 6) == '<br />') $in_categories_path = $lC_Language->get('top_category') . '<br />' . $in_categories_path;
      }
      $result['categoryPath'] = $in_categories_path;
    }

    $categories_array = array('0' => $lC_Language->get('top_category'));
    foreach ( $lC_CategoryTree->getArray() as $value ) {
      $categories_array[$value['id']] = $value['title'];
    }
    $result['categoriesArray'] = $categories_array;

    return $result;
  }
 /*
  * Return the data used on the preview dialog form
  *
  * @param integer $id The products id
  * @access public
  * @return array
  */
  public static function preview($id) {
    global $lC_Database, $lC_Language, $lC_Currencies;

    $lC_Image = new lC_Image_Admin();
    $lC_Language->loadIniFile('products.php');

    $result = array();

    $Qp = $lC_Database->query('select p.products_id, p.products_quantity, p.products_price, p.products_model, p.products_weight, p.products_weight_class, p.products_date_added, p.products_last_modified, p.products_status, p.products_tax_class_id, p.manufacturers_id, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and default_flag = :default_flag) where p.products_id = :products_id');
    $Qp->bindTable(':table_products', TABLE_PRODUCTS);
    $Qp->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
    $Qp->bindInt(':products_id', $id);
    $Qp->bindInt(':default_flag', 1);
    $Qp->execute();

    $Qpd = $lC_Database->query('select products_name, products_description, products_url, language_id from :table_products_description where products_id = :products_id');
    $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qpd->bindInt(':products_id', $id);
    $Qpd->execute();

    $pd_extra = array();
    while ( $Qpd->next() ) {
      $pd_extra['products_name'][$Qpd->valueInt('language_id')] = $Qpd->valueProtected('products_name');
      $pd_extra['products_description'][$Qpd->valueInt('language_id')] = $Qpd->value('products_description');
      $pd_extra['products_url'][$Qpd->valueInt('language_id')] = $Qpd->valueProtected('products_url');
    }

    $lC_ObjectInfo = new lC_ObjectInfo(array_merge($Qp->toArray(), $pd_extra));

    $products_name = $lC_ObjectInfo->get('products_name');
    $products_description = $lC_ObjectInfo->get('products_description');
    $products_url = $lC_ObjectInfo->get('products_url');

    $result['previewHtml'] = '<div style="background-color: #fff3e7;">';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['previewHtml'] .= '<span id="lang_' . $l['code'] . '"' . (($l['code'] == $lC_Language->getCode()) ? ' class="highlight"' : '') . '><a href="javascript:toggleDivBlocks(\'pName_\', \'pName_' . $l['code'] . '\'); toggleClass(\'lang_\', \'lang_' . $l['code'] . '\', \'highlight\', \'span\');">' . $lC_Language->showImage($l['code']) . '</a></span>&nbsp;&nbsp;';
    }
    $result['previewHtml'] .= '</div>';
    foreach ( $lC_Language->getAll() as $l ) {
      $result['previewHtml'] .= '<div id="pName_' . $l['code'] . '" ' . (($l['code'] != $lC_Language->getCode()) ? ' style="display: none;"' : '') . '>';
      $result['previewHtml'] .= '  <table border="0" width="100%" cellspacing="0" cellpadding="2">';
      $result['previewHtml'] .= '    <tr>';
      $result['previewHtml'] .= '      <td><h1>' . lc_output_string_protected($products_name[$l['id']]) . (!lc_empty($lC_ObjectInfo->get('products_model')) ? '<br /><span>' . $lC_ObjectInfo->getProtected('products_model') . '</span>': '') . '</h1></td>';
      $result['previewHtml'] .= '      <td align="right"><h1>' . $lC_Currencies->format($lC_ObjectInfo->get('products_price')) . '</h1></td>';
      $result['previewHtml'] .= '    </tr>';
      $result['previewHtml'] .= '  </table>';
      $result['previewHtml'] .= '  <p>' . $lC_Image->show($lC_ObjectInfo->get('image'), $products_name[$l['id']], 'align="right" hspace="5" vspace="5"', 'product_info') . $products_description[$l['id']] . '</p>';
      if ( !empty($products_url[$l['id']]) ) {
        $result['previewHtml'] .= '<p>' . sprintf($lC_Language->get('more_product_information'), lc_output_string_protected($products_url[$l['id']])) . '</p>';
      }
      $result['previewHtml'] .= '<p align="center">' . sprintf($lC_Language->get('product_date_added'), lC_DateTime::getLong($lC_ObjectInfo->get('products_date_added'))) . '</p>';
      $result['previewHtml'] .= '</div>';
    }

    return $result;
  }

 /*
  * Returns an array of product categories
  *
  * @param integer $id The categories id
  * @access public
  * @return array
  */
  public static function getCategoriesArray($id = null) {
    global $lC_Language;

    $lC_Language->loadIniFile('products.php');
    $lC_CategoryTree = new lC_CategoryTree();

    $result = array();
    $categories_array = array('' => $lC_Language->get('all_products'));
    foreach ( $lC_CategoryTree->getArray() as $value ) {
      $categories_array[end(explode('_', $value['id']))] = $value['title'];
    }
    $result['categoriesArray'] = $categories_array;


    return $result;
  }
 /*
  * Returns the product information
  *
  * @param integer $id The products id
  * @access public
  * @return array
  */
  public static function get($id) {
    global $lC_Database, $lC_Language;

    $Qproducts = $lC_Database->query('select p.*, pd.* from :table_products p, :table_products_description pd where p.products_id = :products_id and p.products_id = pd.products_id and pd.language_id = :language_id');
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindInt(':products_id', $id);
    $Qproducts->bindInt(':language_id', $lC_Language->getID());
    $Qproducts->execute();

    $data = $Qproducts->toArray();

    $variants_array = array();

    if ( $data['has_children'] == '1' ) {
      $Qsubproducts = $lC_Database->query('select * from :table_products where parent_id = :parent_id and products_status = :products_status');
      $Qsubproducts->bindTable(':table_products', TABLE_PRODUCTS);
      $Qsubproducts->bindInt(':parent_id', $data['products_id']);
      $Qsubproducts->bindInt(':products_status', 1);
      $Qsubproducts->execute();

      while ( $Qsubproducts->next() ) {
        $variants_array[$Qsubproducts->valueInt('products_id')]['data'] = array('price' => $Qsubproducts->value('products_price'),
                                                                                'tax_class_id' => $Qsubproducts->valueInt('products_tax_class_id'),
                                                                                'model' => $Qsubproducts->value('products_model'),
                                                                                'quantity' => $Qsubproducts->value('products_quantity'),
                                                                                'weight' => $Qsubproducts->value('products_weight'),
                                                                                'weight_class_id' => $Qsubproducts->valueInt('products_weight_class'),
                                                                                'availability_shipping' => 1);

        $Qvariants = $lC_Database->query('select pv.default_combo, pvg.id as group_id, pvg.title as group_title, pvg.module, pvv.id as value_id, pvv.title as value_title, pvv.sort_order as value_sort_order from :table_products_variants pv, :table_products_variants_groups pvg, :table_products_variants_values pvv where pv.products_id = :products_id and pv.products_variants_values_id = pvv.id and pvv.languages_id = :languages_id and pvv.products_variants_groups_id = pvg.id and pvg.languages_id = :languages_id order by pvg.sort_order, pvg.title');
        $Qvariants->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
        $Qvariants->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
        $Qvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
        $Qvariants->bindInt(':products_id', $Qsubproducts->valueInt('products_id'));
        $Qvariants->bindInt(':languages_id', $lC_Language->getID());
        $Qvariants->bindInt(':languages_id', $lC_Language->getID());
        $Qvariants->execute();

        while ( $Qvariants->next() ) {
          $variants_array[$Qsubproducts->valueInt('products_id')]['values'][$Qvariants->valueInt('group_id')][$Qvariants->valueInt('value_id')] = array('value_id' => $Qvariants->valueInt('value_id'),
                                                                                                                                                        'group_title' => $Qvariants->value('group_title'),
                                                                                                                                                        'value_title' => $Qvariants->value('value_title'),
                                                                                                                                                        'sort_order' => $Qvariants->value('value_sort_order'),
                                                                                                                                                        'default' => (bool)$Qvariants->valueInt('default_combo'),
                                                                                                                                                        'module' => $Qvariants->value('module'));
        }
      }
    }

    $data['variants'] = $variants_array;

    $Qattributes = $lC_Database->query('select id, value from :table_product_attributes where products_id = :products_id and languages_id in (0, :languages_id)');
    $Qattributes->bindTable(':table_product_attributes');
    $Qattributes->bindInt(':products_id', $id);
    $Qattributes->bindInt(':languages_id', $lC_Language->getID());
    $Qattributes->execute();

    $attributes_array = array();

    while ( $Qattributes->next() ) {
      // if the value is date, reformat for datepicker
      $value = (substr($Qattributes->value('value'), 4, 1) == '-') ? lC_DateTime::getShort($Qattributes->value('value')) : $Qattributes->value('value');      
      $attributes_array[$Qattributes->valueInt('id')] = $value;
    }

    $data['attributes'] = $attributes_array;

    return $data;
  }
 /*
  * Save the product
  *
  * @param integer $id The products id to update, null on insert
  * @param array $data The products information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data) {
    global $lC_Database, $lC_Language, $lC_Image;

    $error = false;

    $lC_Database->startTransaction();

    if ( is_numeric($id) ) {
      $Qproduct = $lC_Database->query('update :table_products set products_quantity = :products_quantity, products_price = :products_price, products_model = :products_model, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id, products_last_modified = now() where products_id = :products_id');
      $Qproduct->bindInt(':products_id', $id);
    } else {
      $Qproduct = $lC_Database->query('insert into :table_products (products_quantity, products_price, products_model, products_weight, products_weight_class, products_status, products_tax_class_id, products_date_added) values (:products_quantity, :products_price, :products_model, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :products_date_added)');
      $Qproduct->bindRaw(':products_date_added', 'now()');
    }

    // set parent status to active if has variants
    if ( isset($data['variants_combo']) && !empty($data['variants_combo']) ) $data['status'] = 1;
    
    $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproduct->bindInt(':products_quantity', $data['quantity']);
    $Qproduct->bindFloat(':products_price', $data['price']);
    $Qproduct->bindValue(':products_model', $data['model']);
    $Qproduct->bindFloat(':products_weight', $data['weight']);
    $Qproduct->bindInt(':products_weight_class', $data['weight_class']);
    $Qproduct->bindInt(':products_status', $data['status']);
    $Qproduct->bindInt(':products_tax_class_id', $data['tax_class_id']);
    $Qproduct->setLogging($_SESSION['module'], $id);
    $Qproduct->execute();

    if ( $lC_Database->isError() ) {
      $error = true;
    } else {
      if ( is_numeric($id) ) {
        $products_id = $id;
      } else {
        $products_id = $lC_Database->nextID();
      }

      // remove any old pricing records
      $Qpricing = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id');
      $Qpricing->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
      $Qpricing->bindInt(':products_id', $products_id);
      $Qpricing->setLogging($_SESSION['module'], $products_id);
      $Qpricing->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      } else {
        if ( isset($data['variants_combo']) && !empty($data['variants_combo']) ) {
        } else {
          if ( isset($data['price_breaks']) && !empty($data['price_breaks']) ) {
            for ($i=0; sizeof($data['price_breaks']['group_id']) > $i; $i++) {
              if (is_array($data['price_breaks']['group_id'][$i])) continue;
              if ($data['price_breaks']['group_id'][$i] == 0) continue;
              if ($data['price_breaks']['qty'][$i] == null) continue;
              if ($data['price_breaks']['price'][$i] == 0) continue;
              $Qpb = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, qty_break, price_break, date_added) values (:products_id, :group_id, :tax_class_id, :qty_break, :price_break, :date_added)');
              $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
              $Qpb->bindInt(':products_id', $products_id );
              $Qpb->bindInt(':group_id', $data['price_breaks']['group_id'][$i] );
              $Qpb->bindInt(':tax_class_id', $data['price_breaks']['tax_class_id'][$i] );
              $Qpb->bindValue(':qty_break', $data['price_breaks']['qty'][$i] );
              $Qpb->bindValue(':price_break', $data['price_breaks']['price'][$i] );
              $Qpb->bindRaw(':date_added', 'now()');
              $Qpb->setLogging($_SESSION['module'], $products_id);
              $Qpb->execute();

              if ( $lC_Database->isError() ) {
                $error = true;
                break;
              }
            }
          }
        }
      }

      $Qcategories = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id');
      $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcategories->bindInt(':products_id', $products_id);
      $Qcategories->setLogging($_SESSION['module'], $products_id);
      $Qcategories->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
      } else {
        if ( isset($data['categories']) && !empty($data['categories']) ) {
          foreach ($data['categories'] as $category_id) {
            $Qp2c = $lC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
            $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qp2c->bindInt(':products_id', $products_id);
            $Qp2c->bindInt(':categories_id', $category_id);
            $Qp2c->setLogging($_SESSION['module'], $products_id);
            $Qp2c->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }
      }
    }

    if ( $error === false ) {
      $images = array();

      $products_image = new upload('products_image');
      $products_image->set_extensions(array('gif', 'jpg', 'jpeg', 'png'));

      if ( $products_image->exists() ) {
        $products_image->set_destination(realpath('../images/products/originals'));

        if ( $products_image->parse() && $products_image->save() ) {
          $images[] = $products_image->filename;
        }
      }

      if ( isset($data['localimages']) ) {
        foreach ($data['localimages'] as $image) {
          $image = basename($image);

          if (@file_exists('../images/products/_upload/' . $image)) {
            copy('../images/products/_upload/' . $image, '../images/products/originals/' . $image);
            @unlink('../images/products/_upload/' . $image);

            $images[] = $image;
          }
        }
      }

      $default_flag = 1;

      foreach ($images as $image) {
        $Qimage = $lC_Database->query('insert into :table_products_images (products_id, image, default_flag, sort_order, date_added) values (:products_id, :image, :default_flag, :sort_order, :date_added)');
        $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qimage->bindInt(':products_id', $products_id);
        $Qimage->bindValue(':image', $image);
        $Qimage->bindInt(':default_flag', $default_flag);
        $Qimage->bindInt(':sort_order', 0);
        $Qimage->bindRaw(':date_added', 'now()');
        $Qimage->setLogging($_SESSION['module'], $products_id);
        $Qimage->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        } else {
          foreach ($lC_Image->getGroups() as $group) {
            if ($group['id'] != '1') {
              $lC_Image->resize($image, $group['id']);
            }
          }
        }

        $default_flag = 0;
      }
    }
    
    if ( $error === false ) {
      foreach ($lC_Language->getAll() as $l) {
        if ( is_numeric($id) ) {
          $Qpd = $lC_Database->query('update :table_products_description set products_name = :products_name, products_description = :products_description, products_keyword = :products_keyword, products_tags = :products_tags, products_url = :products_url where products_id = :products_id and language_id = :language_id');
        } else {
          $Qpd = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_keyword, products_tags, products_url) values (:products_id, :language_id, :products_name, :products_description, :products_keyword, :products_tags, :products_url)');
        }
        $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qpd->bindInt(':products_id', $products_id);
        $Qpd->bindInt(':language_id', $l['id']);
        $Qpd->bindValue(':products_name', $data['products_name'][$l['id']]);
        $Qpd->bindValue(':products_description', $data['products_description'][$l['id']]);
        $Qpd->bindValue(':products_keyword', $data['products_keyword'][$l['id']]);
        $Qpd->bindValue(':products_tags', $data['products_tags'][$l['id']]);
        $Qpd->bindValue(':products_url', $data['products_url'][$l['id']]);
        $Qpd->setLogging($_SESSION['module'], $products_id);
        $Qpd->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
          break;
        }
      }
    }

    if ( $error === false ) {
      if ( isset($data['attributes']) && !empty($data['attributes']) ) {
        foreach ( $data['attributes'] as $attributes_id => $value ) {
          if ( is_array($value) ) {
          } elseif ( !empty($value) ) {
            $Qcheck = $lC_Database->query('select id from :table_product_attributes where products_id = :products_id and id = :id limit 1');
            $Qcheck->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
            $Qcheck->bindInt(':products_id', $products_id);
            $Qcheck->bindInt(':id', $attributes_id);
            $Qcheck->execute();

            if ( $Qcheck->numberOfRows() === 1 ) {
              $Qattribute = $lC_Database->query('update :table_product_attributes set value = :value where products_id = :products_id and id = :id');
            } else {
              $Qattribute = $lC_Database->query('insert into :table_product_attributes (id, products_id, languages_id, value) values (:id, :products_id, :languages_id, :value)');
              $Qattribute->bindInt(':languages_id', 0);
            }
            
            // if the value is date from datepicker, reformat it
            $value = (strstr($value, '/')) ? lC_DateTime::toDateTime($value) : $value;
                   
            $Qattribute->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
            $Qattribute->bindValue(':value', $value);
            $Qattribute->bindInt(':products_id', $products_id);
            $Qattribute->bindInt(':id', $attributes_id);
            $Qattribute->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }
        }
      }
    }

    if ( $error === false ) {
      $variants_array = array();
      $default_variant_combo = null;

      if ( isset($data['variants_combo']) && !empty($data['variants_combo']) ) {
        foreach ( $data['variants_combo'] as $key => $combos ) {
          if ( isset($data['variants_combo_db'][$key]) ) {
            $Qsubproduct = $lC_Database->query('update :table_products set products_quantity = :products_quantity, products_price = :products_price, products_model = :products_model, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id where products_id = :products_id');
            $Qsubproduct->bindInt(':products_id', $data['variants_combo_db'][$key]);
          } else {
            $Qsubproduct = $lC_Database->query('insert into :table_products (parent_id, products_quantity, products_price, products_model, products_weight, products_weight_class, products_status, products_tax_class_id, products_date_added) values (:parent_id, :products_quantity, :products_price, :products_model, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :products_date_added)');
            $Qsubproduct->bindInt(':parent_id', $products_id);
            $Qsubproduct->bindRaw(':products_date_added', 'now()');
          }

          $Qsubproduct->bindTable(':table_products', TABLE_PRODUCTS);
          $Qsubproduct->bindInt(':products_quantity', $data['variants_quantity'][$key]);
          $Qsubproduct->bindFloat(':products_price', $data['variants_price'][$key]);
          $Qsubproduct->bindValue(':products_model', $data['variants_model'][$key]);
          $Qsubproduct->bindFloat(':products_weight', $data['variants_weight'][$key]);
          $Qsubproduct->bindInt(':products_weight_class', $data['variants_weight_class'][$key]);
          $Qsubproduct->bindInt(':products_status', (isset($data['variants_status'][$key]) && $data['variants_status'][$key] == 'on') ? 1 : 0);
          $Qsubproduct->bindInt(':products_tax_class_id', $data['variants_tax_class_id'][$key]);
          $Qsubproduct->setLogging($_SESSION['module'], $id);
          $Qsubproduct->execute();

          if ( isset($data['variants_combo_db'][$key])) {
            $subproduct_id = $data['variants_combo_db'][$key];
          } else {
            $Qnext = $lC_Database->query('select max(products_id) as maxID from :table_products');
            $Qnext->bindTable(':table_products', TABLE_PRODUCTS);
            $Qnext->execute();
            $subproduct_id = $Qnext->valueInt('maxID');
            $Qnext->freeResult();
          }

          // QPB
          if ( $lC_Database->isError() ) {
            $error = true;
          } else {
            // remove any old pricing records
            $Qpricing = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id');
            $Qpricing->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
            $Qpricing->bindInt(':products_id', $subproduct_id);
            $Qpricing->setLogging($_SESSION['module'], $subproduct_id);
            $Qpricing->execute();

            if ( isset($data['price_breaks']) && !empty($data['price_breaks']) ) {
              for ($j=0; sizeof($data['price_breaks']['group_id'][$key]) > $j; $j++) {
                if ($data['price_breaks']['group_id'][$key][$j] == 0) continue;
                if ($data['price_breaks']['qty'][$key][$j] == null) continue;
                if ($data['price_breaks']['price'][$key][$j] == 0) continue;
                $Qpb = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, qty_break, price_break, date_added) values (:products_id, :group_id, :tax_class_id, :qty_break, :price_break, :date_added)');
                $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
                $Qpb->bindInt(':products_id', $subproduct_id );
                $Qpb->bindInt(':group_id', $data['price_breaks']['group_id'][$key][$j] );
                $Qpb->bindInt(':tax_class_id', $data['price_breaks']['tax_class_id'][$key][$j] );
                $Qpb->bindValue(':qty_break', $data['price_breaks']['qty'][$key][$j] );
                $Qpb->bindValue(':price_break', $data['price_breaks']['price'][$key][$j] );
                $Qpb->bindRaw(':date_added', 'now()');
                $Qpb->setLogging($_SESSION['module'], $subproduct_id);
                $Qpb->execute();

                if ( $lC_Database->isError() ) {
                  $error = true;
                  break ;
                }
              }
            }
          }

          if ( $data['variants_default_combo'] == $key ) {
            $default_variant_combo = $subproduct_id;
          }

          $combos_array = explode(';', $combos);

          foreach ( $combos_array as $combo ) {
            list($vgroup, $vvalue) = explode('_', $combo);

            $variants_array[$subproduct_id][] = $vvalue;

            $check_combos_array[] = $vvalue;

            $Qcheck = $lC_Database->query('select products_id from :table_products_variants where products_id = :products_id and products_variants_values_id = :products_variants_values_id');
            $Qcheck->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qcheck->bindInt(':products_id', $subproduct_id);
            $Qcheck->bindInt(':products_variants_values_id', $vvalue);
            $Qcheck->execute();

            if ( $Qcheck->numberOfRows() < 1 ) {
              $Qvcombo = $lC_Database->query('insert into :table_products_variants (products_id, products_variants_values_id) values (:products_id, :products_variants_values_id)');
              $Qvcombo->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
              $Qvcombo->bindInt(':products_id', $subproduct_id);
              $Qvcombo->bindInt(':products_variants_values_id', $vvalue);
              $Qvcombo->setLogging($_SESSION['module'], $products_id);
              $Qvcombo->execute();

              if ( $lC_Database->isError() ) {
                $error = true;
                break 2;
              }
            }
          }
        }
      }

      if ( $error === false ) {
        if ( empty($variants_array) ) {
          $Qcheck = $lC_Database->query('select pv.* from :table_products p, :table_products_variants pv where p.parent_id = :parent_id and p.products_id = pv.products_id');
          $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
          $Qcheck->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
          $Qcheck->bindInt(':parent_id', $products_id);
          $Qcheck->execute();

          while ( $Qcheck->next() ) {
            $Qdel = $lC_Database->query('delete from :table_products_variants where products_id = :products_id');
            $Qdel->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->execute();

            $Qdel = $lC_Database->query('delete from :table_products where products_id = :products_id');
            $Qdel->bindTable(':table_products', TABLE_PRODUCTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->execute();
          }
        } else {
          $Qcheck = $lC_Database->query('select pv.* from :table_products p, :table_products_variants pv where p.parent_id = :parent_id and p.products_id = pv.products_id and pv.products_id not in (":products_id")');
          $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
          $Qcheck->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
          $Qcheck->bindInt(':parent_id', $products_id);
          $Qcheck->bindRaw(':products_id', implode('", "', array_keys($variants_array)));
          $Qcheck->execute();

          while ( $Qcheck->next() ) {
            $Qdel = $lC_Database->query('delete from :table_products_variants where products_id = :products_id and products_variants_values_id = :products_variants_values_id');
            $Qdel->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->bindInt(':products_variants_values_id', $Qcheck->valueInt('products_variants_values_id'));
            $Qdel->execute();

            $Qdel = $lC_Database->query('delete from :table_products where products_id = :products_id');
            $Qdel->bindTable(':table_products', TABLE_PRODUCTS);
            $Qdel->bindInt(':products_id', $Qcheck->valueInt('products_id'));
            $Qdel->execute();
          }

          foreach ( $variants_array as $key => $values ) {
            $Qdel = $lC_Database->query('delete from :table_products_variants where products_id = :products_id and products_variants_values_id not in (":products_variants_values_id")');
            $Qdel->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
            $Qdel->bindInt(':products_id', $key);
            $Qdel->bindRaw(':products_variants_values_id', implode('", "', $values));
            $Qdel->execute();
          }
        }
      }

      $Qupdate = $lC_Database->query('update :table_products set has_children = :has_children where products_id = :products_id');
      $Qupdate->bindTable(':table_products', TABLE_PRODUCTS);
      $Qupdate->bindInt(':has_children', (empty($variants_array)) ? 0 : 1);
      $Qupdate->bindInt(':products_id', $products_id);
      $Qupdate->execute();
    }

    if ( $error === false ) {
      $Qupdate = $lC_Database->query('update :table_products_variants set default_combo = :default_combo where products_id in (":products_id")');
      $Qupdate->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
      $Qupdate->bindInt(':default_combo', 0);
      $Qupdate->bindRaw(':products_id', implode('", "', array_keys($variants_array)));
      $Qupdate->execute();

      if ( is_numeric($default_variant_combo) ) {
        $Qupdate = $lC_Database->query('update :table_products_variants set default_combo = :default_combo where products_id = :products_id');
        $Qupdate->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
        $Qupdate->bindInt(':default_combo', 1);
        $Qupdate->bindInt(':products_id', $default_variant_combo);
        $Qupdate->execute();
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Duplicate / Link a product
  *
  * @param integer $id The products id
  * @param integer $category_id The categories id
  * @param string $type The copy type; duplicate or link
  * @access public
  * @return boolean
  */
  public static function copy($id, $category_id, $type) {
    global $lC_Database;

    $category_array = explode('_', $category_id);

    if ( $type == 'link' ) {
      $Qcheck = $lC_Database->query('select count(*) as total from :table_products_to_categories where products_id = :products_id and categories_id = :categories_id');
      $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcheck->bindInt(':products_id', $id);
      $Qcheck->bindInt(':categories_id', end($category_array));
      $Qcheck->execute();

      if ( $Qcheck->valueInt('total') < 1 ) {
        $Qcat = $lC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
        $Qcat->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qcat->bindInt(':products_id', $id);
        $Qcat->bindInt(':categories_id', end($category_array));
        $Qcat->setLogging($_SESSION['module'], $id);
        $Qcat->execute();

        if ( $Qcat->affectedRows() ) {
          return true;
        }
      }
    } elseif ( $type == 'duplicate' ) {
      $Qproduct = $lC_Database->query('select * from :table_products where products_id = :products_id');
      $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
      $Qproduct->bindInt(':products_id', $id);
      $Qproduct->execute();

      if ( $Qproduct->numberOfRows() === 1 ) {
        $error = false;

        $lC_Database->startTransaction();

        $Qnew = $lC_Database->query('insert into :table_products (products_quantity, products_price, products_model, products_date_added, products_weight, products_weight_class, products_status, products_tax_class_id, manufacturers_id)
                                     values (:products_quantity, :products_price, :products_model, now(), :products_weight, :products_weight_class, 0, :products_tax_class_id, :manufacturers_id)');
        $Qnew->bindTable(':table_products', TABLE_PRODUCTS);
        $Qnew->bindInt(':products_quantity', $Qproduct->valueInt('products_quantity'));
        $Qnew->bindValue(':products_price', $Qproduct->value('products_price'));
        $Qnew->bindValue(':products_model', $Qproduct->value('products_model'));
        $Qnew->bindValue(':products_weight', $Qproduct->value('products_weight'));
        $Qnew->bindInt(':products_weight_class', $Qproduct->valueInt('products_weight_class'));
        $Qnew->bindInt(':products_tax_class_id', $Qproduct->valueInt('products_tax_class_id'));
        $Qnew->bindInt(':manufacturers_id', $Qproduct->valueInt('manufacturers_id'));
        $Qnew->setLogging($_SESSION['module']);
        $Qnew->execute();

        if ( $Qnew->affectedRows() ) {
          $new_product_id = $lC_Database->nextID();

          $Qdesc = $lC_Database->query('select * from :table_products_description where products_id = :products_id');
          $Qdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
          $Qdesc->bindInt(':products_id', $id);
          $Qdesc->execute();

          while ( $Qdesc->next() ) {
            $Qnewdesc = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_tags, products_url, products_viewed)
                                             values (:products_id, :language_id, :products_name, :products_description, :products_tags, :products_url, 0)');
            $Qnewdesc = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_tags, products_url, products_viewed) values (:products_id, :language_id, :products_name, :products_description, :products_tags, :products_url, 0)');
            $Qnewdesc->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
            $Qnewdesc->bindInt(':products_id', $new_product_id);
            $Qnewdesc->bindInt(':language_id', $Qdesc->valueInt('language_id'));
            $Qnewdesc->bindValue(':products_name', $Qdesc->value('products_name'));
            $Qnewdesc->bindValue(':products_description', $Qdesc->value('products_description'));
            $Qnewdesc->bindValue(':products_tags', $Qdesc->value('products_tags'));
            $Qnewdesc->bindValue(':products_url', $Qdesc->value('products_url'));
            $Qnewdesc->setLogging($_SESSION['module'], $new_product_id);
            $Qnewdesc->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }

          $Qpb = $lC_Database->query('select * from :table_products_pricing where products_id = :products_id');
          $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $Qpb->bindInt(':products_id', $id);
          $Qpb->execute();

          while ( $Qpb->next() ) {
            $Qnewpb = $lC_Database->query('insert into :table_products_pricing (products_id, group_id, tax_class_id, qty_break, price_break, date_added)
                                           values (:products_id, :group_id, :tax_class_id, :qty_break, :price_break, :date_added)');
            $Qnewpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
            $Qnewpb->bindInt(':products_id', $new_product_id);
            $Qnewpb->bindInt(':group_id', $Qpb->valueInt('group_id'));
            $Qnewpb->bindInt(':tax_class_id', $Qpb->valueInt('tax_class_id'));
            $Qnewpb->bindInt(':qty_break', $Qpb->valueInt('qty_break'));
            $Qnewpb->bindValue(':price_break', $Qpb->value('price_break'));
            $Qnewpb->bindRaw(':date_added', 'now()');
            $Qnewpb->setLogging($_SESSION['module'], $new_product_id);
            $Qnewpb->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }

          if ( $error === false ) {
            $Qp2c = $lC_Database->query('insert into :table_products_to_categories (products_id, categories_id) values (:products_id, :categories_id)');
            $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qp2c->bindInt(':products_id', $new_product_id);
            $Qp2c->bindInt(':categories_id', end($category_array));
            $Qp2c->setLogging($_SESSION['module'], $new_product_id);
            $Qp2c->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
            }
          }
        } else {
          $error = true;
        }

        if ( $error === false ) {
          $lC_Database->commitTransaction();

          lC_Cache::clear('categories');
          lC_Cache::clear('category_tree');
          lC_Cache::clear('also_purchased');

          return true;
        } else {
          $lC_Database->rollbackTransaction();
        }
      }
    }

    return false;
  }
 /*
  * Batch copy products
  *
  * @param array $batch An array of product id's
  * @param integer $new_category_id The new category id
  * @param integer $type Copy as duplicate or link
  * @access public
  * @return boolean
  */
  public static function batchCopy($batch, $new_category_id, $type) {
    foreach ( $batch as $id ) {
      lC_Products_Admin::copy($id, $new_category_id, $type);
    }
    return true;
  }
 /*
  * Delete a product
  *
  * @param integer $id The products id to delete
  * @param array $categories An array of category id's, null = delete from all categories
  * @access public
  * @return boolean
  */
  public static function delete($id, $categories = null) {
    global $lC_Database;

    $lC_Image = new lC_Image_Admin();

    $delete_product = true;
    $error = false;

    $lC_Database->startTransaction();

    if ( is_array($categories) && !empty($categories) ) {
      $Qpc = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id and categories_id in :categories_id');
      $Qpc->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qpc->bindInt(':products_id', $id);
      $Qpc->bindRaw(':categories_id', '("' . implode('", "', $categories) . '")');
      $Qpc->setLogging($_SESSION['module'], $id);
      $Qpc->execute();

      if ( !$lC_Database->isError() ) {
        $Qcheck = $lC_Database->query('select products_id from :table_products_to_categories where products_id = :products_id limit 1');
        $Qcheck->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qcheck->bindInt(':products_id', $id);
        $Qcheck->execute();

        if ( $Qcheck->numberOfRows() > 0 ) {
          $delete_product = false;
        }
      } else {
        $error = true;
      }
    }

    if ( ($error === false) && ($delete_product === true) ) {
      $Qvariants = $lC_Database->query('select products_id from :table_products where parent_id = :parent_id');
      $Qvariants->bindTable(':table_products', TABLE_PRODUCTS);
      $Qvariants->bindInt(':parent_id', $id);
      $Qvariants->execute();

      while ( $Qvariants->next() ) {
        $Qsc = $lC_Database->query('delete from :table_shopping_carts where products_id = :products_id');
        $Qsc->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        $Qsc->bindInt(':products_id', $Qvariants->valueInt('products_id'));
        $Qsc->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }

        if ( $error === false ) {
          $Qsccvv = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where products_id = :products_id');
          $Qsccvv->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
          $Qsccvv->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qsccvv->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

        if ( $error === false ) {
          $Qpa = $lC_Database->query('delete from :table_products_variants where products_id = :products_id');
          $Qpa->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
          $Qpa->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qpa->setLogging($_SESSION['module'], $id);
          $Qpa->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

        if ( $error === false ) {
          $Qp = $lC_Database->query('delete from :table_products where products_id = :products_id');
          $Qp->bindTable(':table_products', TABLE_PRODUCTS);
          $Qp->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qp->setLogging($_SESSION['module'], $id);
          $Qp->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

        // QPB
        if ( $error === false ) {
          $Qpb = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id');
          $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
          $Qpb->bindInt(':products_id', $Qvariants->valueInt('products_id'));
          $Qpb->setLogging($_SESSION['module'], $id);
          $Qpb->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          }
        }

      }

      if ( $error === false ) {
        $Qr = $lC_Database->query('delete from :table_reviews where products_id = :products_id');
        $Qr->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qr->bindInt(':products_id', $id);
        $Qr->setLogging($_SESSION['module'], $id);
        $Qr->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qsc = $lC_Database->query('delete from :table_shopping_carts where products_id = :products_id');
        $Qsc->bindTable(':table_shopping_carts', TABLE_SHOPPING_CARTS);
        $Qsc->bindInt(':products_id', $id);
        $Qsc->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qsccvv = $lC_Database->query('delete from :table_shopping_carts_custom_variants_values where products_id = :products_id');
        $Qsccvv->bindTable(':table_shopping_carts_custom_variants_values', TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES);
        $Qsccvv->bindInt(':products_id', $id);
        $Qsccvv->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qp2c = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id');
        $Qp2c->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
        $Qp2c->bindInt(':products_id', $id);
        $Qp2c->setLogging($_SESSION['module'], $id);
        $Qp2c->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qs = $lC_Database->query('delete from :table_specials where products_id = :products_id');
        $Qs->bindTable(':table_specials', TABLE_SPECIALS);
        $Qs->bindInt(':products_id', $id);
        $Qs->setLogging($_SESSION['module'], $id);
        $Qs->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qpa = $lC_Database->query('delete from :table_products_variants where products_id = :products_id');
        $Qpa->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
        $Qpa->bindInt(':products_id', $id);
        $Qpa->setLogging($_SESSION['module'], $id);
        $Qpa->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qpd = $lC_Database->query('delete from :table_products_description where products_id = :products_id');
        $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qpd->bindInt(':products_id', $id);
        $Qpd->setLogging($_SESSION['module'], $id);
        $Qpd->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qp = $lC_Database->query('delete from :table_products where products_id = :products_id');
        $Qp->bindTable(':table_products', TABLE_PRODUCTS);
        $Qp->bindInt(':products_id', $id);
        $Qp->setLogging($_SESSION['module'], $id);
        $Qp->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }

      if ( $error === false ) {
        $Qim = $lC_Database->query('select id from :table_products_images where products_id = :products_id');
        $Qim->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qim->bindInt(':products_id', $id);
        $Qim->execute();

        while ($Qim->next()) {
          $lC_Image->delete($Qim->valueInt('id'));
        }
      }

      // QPB
      if ( $error === false ) {
        $Qpb = $lC_Database->query('delete from :table_products_pricing where products_id = :products_id');
        $Qpb->bindTable(':table_products_pricing', TABLE_PRODUCTS_PRICING);
        $Qpb->bindInt(':products_id', $id);
        $Qpb->setLogging($_SESSION['module'], $id);
        $Qpb->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }
      }
    }

    if ( $error === false ) {
      $lC_Database->commitTransaction();

      lC_Cache::clear('categories');
      lC_Cache::clear('category_tree');
      lC_Cache::clear('also_purchased');
      lC_Cache::clear('box-whats_new');

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
  }
 /*
  * Batch delete products
  *
  * @param array $batch An array of product id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Products_Admin::delete($id);
    }
    return true;
  }
 /*
  * Set the product date available
  *
  * @param integer $id The products id
  * @param array $data The products data
  * @access public
  * @return boolean
  */
  public static function setDateAvailable($id, $data) {
    global $lC_Database;

    $Qattribute = $lC_Database->query('select pa.id from :table_product_attributes pa, :table_templates_boxes tb where tb.code = :code and tb.modules_group = :modules_group and tb.id = pa.id and products_id = :products_id');
    $Qattribute->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qattribute->bindTable(':table_templates_boxes', TABLE_TEMPLATES_BOXES);
    $Qattribute->bindValue(':code', 'date_available');
    $Qattribute->bindValue(':modules_group', 'product_attributes');
    $Qattribute->bindInt(':products_id', $id);
    $Qattribute->execute();

    $Qupdate = $lC_Database->query('update :table_product_attributes set value = :value where id = :id and products_id = :products_id');
    $Qupdate->bindTable(':table_product_attributes', TABLE_PRODUCT_ATTRIBUTES);
    $Qupdate->bindDate(':value', $data['date_available']);
    $Qupdate->bindInt(':id', $Qattribute->valueInt('id'));
    $Qupdate->bindInt(':products_id', $id);
    $Qupdate->setLogging($_SESSION['module'], $id);
    $Qupdate->execute();

    return ( $Qupdate->affectedRows() > 0 );
  }
 /*
  * Return the number of keywords for a product
  *
  * @param string $keyword The keyword string to count
  * @param integer $id The products id, null = count all products
  * @access public
  * @return integer
  */
  public static function getKeywordCount($keyword, $id = null) {
    global $lC_Database;

    $Qkeywords = $lC_Database->query('select count(*) as total, products_keyword from :table_products_description where products_keyword = :products_keyword');

    if ( is_numeric($id) ) {
      $Qkeywords->appendQuery('and products_id != :products_id');
      $Qkeywords->bindInt(':products_id', $id);
    }

    $Qkeywords->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qkeywords->bindValue(':products_keyword', $keyword);
    $Qkeywords->execute();

    if ( is_numeric($id) && ($keyword == $Qkeywords->value('products_keyword'))) {
      $keyword_count = 0;
    } else {
      $keyword_count = $Qkeywords->valueInt('total');
    }

    return $keyword_count;
  }
 /*
  * Validate the product keyword
  *
  * @param string $keyword The product keyword
  * @access public
  * @return array
  */
  public static function validate($keyword_array, $pid = null) {

    $validated = true;;
    foreach($keyword_array as $keyword) {
      if ( preg_match('/^[a-z0-9_-]+$/iD', $keyword) !== 1 ) $validated = false;
      if ( lC_Products_Admin::getKeywordCount($keyword, $pid) > 0) $validated = false;
    }

    return $validated;
  }
}
?>
