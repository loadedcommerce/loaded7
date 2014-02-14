<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: products_import_export.php v1.0 2013-12-01 resultsonlyweb $
*/
include_once('includes/applications/products/classes/products.php');
include_once('includes/applications/categories/classes/categories.php');

class lC_Products_import_export_Admin {
 /*
  * Get filter totals
  *
  * @param string $filter A string of the filter that is selected
  * @param string $type A string of the export type
  * @access public
  * @return array
  */
  public static function getFilterTotal($filter, $type) {
    global $lC_Database, $lC_Language;

    $QtotalsSQL = 'select count(*) as total from ';

    switch($type) {
      case 'products':
        $QtotalsSQL .= TABLE_PRODUCTS;
        break;
      case 'categories':
        $QtotalsSQL .= TABLE_CATEGORIES;
        break;
      case 'options':
      $QtotalsSQL .= TABLE_PRODUCTS_VARIANTS;
      break;
    }

    switch($filter) {
      case 'none':
        break;
    }

    $Qtotals = $lC_Database->query($QtotalsSQL);
    $Qtotals->execute();

    $result = array();
    if ( !$lC_Database->isError() ) {
      $result['total'] = $Qtotals->valueInt('total');
    }

    return $result;
  }

 /*
  * Return the temp file name for downloading products
  *
  * @param string $pfilter A string of the filter that is selected
  * @param string $pgtype A string of the export type
  * @param string $pgformat A string of the file format
  * @access public
  * @return array
  */
  public static function getProducts($pfilter, $pgtype, $pgformat) {
    global $lC_Database, $lC_Datetime, $lC_Language;

    // generate file name to use with this file
    $datetime = ''; // lC_Datetime::getTimestamp();
    $filename = 'products-' . $datetime . $pgtype;
    if ($pgformat == 'tabbed') {
      $filename = $filename . '.txt';
      $delim = "\t";
      $seperator = ",";
    } else if ($pgformat == 'csv') {
      $filename = $filename . '.' . $pgformat;
      $delim = ",";
      $seperator = ",";
    } else {
      return false;
    }

    $filepath = DIR_FS_DOWNLOAD . $filename;

    $errormsg = '';

    // make columns in clude full table names to i can implode into sql statement
    // add image and category and other product tables to columns and query
    $sql_columns = array('p.products_id',
                         'p.parent_id',
                         'p.products_quantity',
                         'p.products_price',
                         'p.products_cost',
                         'p.products_msrp',
                         'p.products_model',
                         'p.products_sku',
                         'p.products_date_added',
                         'p.products_last_modified',
                         'p.products_weight',
                         'wc.weight_class_key',
                         'p.products_status',
                         'p.products_tax_class_id',
                         'm.manufacturers_name',
                         'p.products_ordered',
                         'p.has_children',

                         'pd.language_id',
                         'pd.products_name',
                         'pd.products_description',
                         'pd.products_keyword',
                         'pd.products_tags',
                         'pd.products_meta_title',
                         'pd.products_meta_keywords',
                         'pd.products_meta_description',
                         'pd.products_url',
                         'pd.products_viewed'
                         );
    $columns = array('id',
                     'parent_id',
                     'quantity',
                     'price',
                     'cost',
                     'msrp',
                     'model',
                     'sku',
                     'date_added',
                     'last_modified',
                     'weight',
                     'weight_class',
                     'status',
                     'tax_class_id',
                     'manufacturer',
                     'products_ordered',
                     'has_children',

                     'language_id',
                     'name',
                     'description',
                     'permalink',
                     'tags',
                     'meta_title',
                     'meta_keywords',
                     'meta_description',
                     'url',
                     'products_viewed'
                     );

    $sql_columns = implode(", ", $sql_columns);

    $sql_statement = 'SELECT '.$sql_columns.' FROM :table_products_description pd, :table_weight_classes wc, :table_products p LEFT JOIN :table_manufacturers m ON (p.manufacturers_id = m.manufacturers_id) WHERE pd.products_id = p.products_id AND p.products_weight_class = wc.weight_class_id';

    // make this section get the data and make a file in work folder for the url to be returned.
    $Qproducts = $lC_Database->query($sql_statement);
    $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qproducts->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
    $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
    $Qproducts->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
    $Qproducts->execute();

    if ($lC_Database->isError()) {
      $errormsg .= $lC_Database->getError();
    }

    $columns[] = 'categories';
    $columns[] = 'base_image';

    $products = array();
    while ($Qproducts->next()) {
      $products[] = $Qproducts->toArray();
    }

    // seperate out all categories and images and comma delimited columns

    $content = '';
    foreach ($products as $product) {
      foreach ($product as $column_output) {
        $content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
      }

      $Qcategories = $lC_Database->query("SELECT * FROM :table_products_to_categories ptc, :table_categories_description cd WHERE ptc.products_id = :products_id AND ptc.categories_id = cd.categories_id AND cd.language_id = :language_id");
      $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
      $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
      $Qcategories->bindInt(':products_id', $product['products_id']);
      $Qcategories->bindInt(':language_id', $product['language_id']);
      $Qcategories->execute();

      $categories = array();
      while ($Qcategories->next()) {
        $categories[] = $Qcategories->value('categories_name');
      }
      $content .= "\"" . implode($seperator, $categories) . "\"" . $delim;

      $Qimage = $lC_Database->query("SELECT * FROM :table_products_images WHERE products_id = :products_id");
      $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qimage->bindInt(':products_id', $product['products_id']);
      $Qimage->execute();

      $content .= $Qimage->value('image');

      $content .= "\n";

      // $content .= '\'' . implode('\'' . "\t" . '\'', $product) . '\'' . "\n";
    }

    $fp = fopen($filepath,"wb");
    fwrite($fp, implode($delim, $columns) . "\n" . $content);
    fclose($fp);

    return array('filename' => $filename, 'sql_statement' => $sql_statement, 'errors' => $errormsg);
  }

 /*
  * Return the temp file name for downloading categories
  *
  * @param string $cfilter A string of the filter that is selected
  * @param string $cgformat A string of the file format
  * @access public
  * @return array
  */
  public static function getCategories($cfilter, $cgformat) {
    global $lC_Database, $lC_Datetime, $lC_Language;

    // generate file name to use with this file
    $datetime = ''; // lC_Datetime::getTimestamp();
    $filename = 'categories'; // . '-' . $datetime;
    if ($cgformat == 'tabbed') {
      $filename = $filename.'.txt';
      $delim = "\t";
      $seperator = ",";
    } else if ($cgformat == 'csv') {
      $filename = $filename.'.'.$pgformat;
      $delim = ",";
      $seperator = ",";
    } else {
      return false;
    }

    $filepath = DIR_FS_DOWNLOAD . $filename;

    // make columns in clude full table names to i can implode into sql statement
    // add image and category and other product tables to columns and query
    $sql_columns = array('c.categories_id',
                         'c.categories_image',
                         'c.parent_id',
                         'c.sort_order',
                         'c.categories_mode',
                         'c.categories_link_target',
                         'c.categories_custom_url',
                         'c.categories_status',
                         'c.categories_visibility_nav',
                         'c.categories_visibility_box',
                         'c.date_added',
                         'c.last_modified',

                         'cd.language_id',
                         'cd.categories_name',
                         'cd.categories_menu_name',
                         'cd.categories_blurb',
                         'cd.categories_description',
                         'cd.categories_keyword',
                         'cd.categories_tags',
                         );
    $columns = array('id',
                     'image',
                     'parent_id',
                     'sort_order',
                     'mode',
                     'link_target',
                     'custom_url',
                     'status',
                     'visibility_nav',
                     'visibility_box',
                     'date_added',
                     'last_modified',

                     'language_id',
                     'name',
                     'menu_name',
                     'blurb',
                     'description',
                     'keyword',
                     'tags'
                     );

    $sql_columns = implode(",", $sql_columns);

    $sql_statement = 'SELECT '.$sql_columns.' FROM :table_categories c, :table_categories_description cd WHERE cd.categories_id = c.categories_id';

    $errormsg = '';

    // make this section get the data and make a file in work folder for the url to be returned.
    $Qcategories = $lC_Database->query($sql_statement);
    $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
    $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
    $Qcategories->execute();

    if ($lC_Database->isError()) {
      $errormsg .= $lC_Database->getError();
    }

    $categories = array();
    while ($Qcategories->next()) {
      $categories[] = $Qcategories->toArray();
    }

    // seperate out all categories and images and comma delimited columns

    $content = '';
    foreach ($categories as $category) {
      foreach ($category as $column_output) {
        $content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
      }
      $content .= "\n";
    }

    $fp = fopen($filepath,"wb");
    fwrite($fp, implode($delim, $columns) . "\n" . $content);
    fclose($fp);

    return array('filename' => $filename, 'sql_statement' => $sql_statement, 'errors' => $errormsg);
  }

 /*
  * Return the temp file name for downloading option groups
  *
  * @param string $ofilter A string of the filter that is selected
  * @param string $ogformat A string of the file format
  * @access public
  * @return array
  */
  public static function getOptionGroups($ofilter, $ogformat) {
    global $lC_Database, $lC_Datetime, $lC_Language;

    // generate file name to use with this file
    $datetime = ''; // lC_Datetime::getTimestamp();
    $filename = 'option-groups'; // . '-' . $datetime;
    if ($ogformat == 'tabbed') {
      $filename = $filename . '.txt';
      $delim = "\t";
      $seperator = ",";
    } else if ($ogformat == 'csv') {
      $filename = $filename . '.' . $ogformat;
      $delim = ",";
      $seperator = ",";
    } else {
      return false;
    }

    $filepath = DIR_FS_DOWNLOAD . $filename;

    // make columns in clude full table names to i can implode into sql statement
    // add image and category and other product tables to columns and query
    $sql_columns = array('pvg.id',
                         'pvg.languages_id',
                         'pvg.title',
                         'pvg.sort_order',
                         'pvg.module',
                         );
    $columns = array('id',
                     'languages_id',
                     'title',
                     'sort_order',
                     'module',
                     );

    $sql_columns = implode(",", $sql_columns);

    $sql_statement = 'SELECT ' . $sql_columns . ' FROM :table_products_variants_groups pvg';

    $errormsg = '';

    // make this section get the data and make a file in work folder for the url to be returned.
    $Qoptiongroups = $lC_Database->query($sql_statement);
    $Qoptiongroups->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
    $Qoptiongroups->execute();

    if ($lC_Database->isError()) {
      $errormsg .= $lC_Database->getError();
    }

    $optiongroups = array();
    while ($Qoptiongroups->next()) {
      $optiongroups[] = $Qoptiongroups->toArray();
    }

    // seperate out all categories and images and comma delimited columns

    $content = '';
    foreach ($optiongroups as $optiongroup) {
      foreach ($optiongroup as $column_output) {
        $content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
      }
      $content .= "\n";
    }

    $fp = fopen($filepath,"wb");
    fwrite($fp, implode($delim, $columns) . "\n" . $content);
    fclose($fp);

    return array('filename' => $filename, 'sql_statement' => $sql_statement, 'errors' => $errormsg);
  }

 /*
  * Return the temp file name for downloading option variants
  *
  * @param string $ofilter A string of the filter that is selected
  * @param string $ogformat A string of the file format
  * @access public
  * @return array
  */
  public static function getOptionVariants($ofilter, $ogformat) {
    global $lC_Database, $lC_Datetime, $lC_Language;

    // generate file name to use with this file
    $datetime = ''; // lC_Datetime::getTimestamp();
    $filename = 'option-variants'; // . '-' . $datetime;
    if ($ogformat == 'tabbed') {
      $filename = $filename . '.txt';
      $delim = "\t";
      $seperator = ",";
    } else if ($cgformat == 'csv') {
      $filename = $filename . '.' . $ogformat;
      $delim = ",";
      $seperator = ",";
    } else {
      return false;
    }

    $filepath = DIR_FS_DOWNLOAD . $filename;

    // make columns include full table names to i can implode into sql statement
    $sql_columns = array('pvv.id',
                         'pvv.languages_id',
                         'pvv.products_variants_groups_id',
                         'pvv.title',
                         'pvv.sort_order',
                         );
    $columns = array('id',
                     'languages_id',
                     'variants_groups_id',
                     'title',
                     'sort_order',
                     );

    $sql_columns = implode(",", $sql_columns);

    $sql_statement = 'SELECT ' . $sql_columns . ' FROM :table_products_variants_values pvv';

    $errormsg = '';

    // make this section get the data and make a file in work folder for the url to be returned.
    $Qoptionvariants = $lC_Database->query($sql_statement);
    $Qoptionvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qoptionvariants->execute();

    if ($lC_Database->isError()) {
      $errormsg .= $lC_Database->getError();
    }

    $optionvariants = array();
    while ($Qoptionvariants->next()) {
      $optionvariants[] = $Qoptionvariants->toArray();
    }

    // seperate out all categories and images and comma delimited columns

    $content = '';
    foreach ($optionvariants as $optionvariant) {
      foreach ($optionvariant as $column_output) {
        $content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
      }
      $content .= "\n";
    }

    $fp = fopen($filepath,"wb");
    fwrite($fp, implode($delim, $columns) . "\n" . $content);
    fclose($fp);

    return array('filename' => $filename, 'sql_statement' => $sql_statement, 'errors' => $errormsg);
  }

 /*
  * Return the temp file name for downloading option 2 products
  *
  * @param string $cfilter A string of the filter that is selected
  * @param string $cgformat A string of the file format
  * @access public
  * @return array
  */
  public static function getOptionProducts($ofilter, $ogformat) {
    global $lC_Database, $lC_Datetime, $lC_Language;

    // generate file name to use with this file
    $datetime = ''; // lC_Datetime::getTimestamp();
    $filename = 'options-to-products'; // . '-' . $datetime;
    if ($ogformat == 'tabbed') {
      $filename = $filename . '.txt';
      $delim = "\t";
      $seperator = ",";
    } else if ($ogformat == 'csv') {
      $filename = $filename . '.' . $ogformat;
      $delim = ",";
      $seperator = ",";
    } else {
      return false;
    }

    $filepath = DIR_FS_DOWNLOAD . $filename;

    // make columns in clude full table names to i can implode into sql statement
    // add image and category and other product tables to columns and query
    $sql_columns = array('psov.id',
                         'psov.customers_group_id',
                         'psov.values_id',
                         'psov.options_id',
                         'psov.price_modifier',
                         );
    $columns = array('id',
                     'customers_group_id',
                     'values_id',
                     'options_id',
                     'price_modifier',
                     );

    $sql_columns = implode(",", $sql_columns);

    $sql_statement = 'SELECT ' . $sql_columns . ' FROM :table_products_simple_options_values psov';

    $errormsg = '';

    // make this section get the data and make a file in work folder for the url to be returned.
    $Qoptionproducts = $lC_Database->query($sql_statement);
    $Qoptionproducts->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
    $Qoptionproducts->execute();

    if ($lC_Database->isError()) {
      $errormsg .= $lC_Database->getError();
    }

    $optionproducts = array();
    while ($Qoptionproducts->next()) {
      $optionproducts[] = $Qoptionproducts->toArray();
    }

    // seperate out all categories and images and comma delimited columns

    $content = '';
    foreach ($optionproducts as $optionproduct) {
      foreach ($optionproduct as $column_output) {
        $content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
      }
      $content .= "\n";
    }

    $fp = fopen($filepath,"wb");
    fwrite($fp, implode($delim, $columns) . "\n" . $content);
    fclose($fp);

    return array('filename' => $filename, 'sql_statement' => $sql_statement, 'errors' => $errormsg);
  }

 /* 
  * Permalink function 
  */
  public static function generate_clean_permalink($str)  {
    setlocale(LC_ALL, 'en_US.UTF8');
    $plink = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $plink = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $plink);
    $plink = strtolower(trim($plink, '-'));
    $plink = preg_replace("/[\/_| -]+/", '-', $plink);

    return $plink;
  }

 /*
  * Return the temp file name for downloading products
  *
  * @param boolean $pwizard A boolean saying do pwizard or not
  * @param string $ptype A string of the import type
  * @param boolean $pbackup A boolean letting me know to backup the whole table or not
  * @access public
  * @return array
  */
  public static function importProducts($filename, $pwizard, $ptype, $pbackup, $pmapdata = NULL) {
    global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;

    $lC_Products = new lC_Products_Admin();

    $error = "";
    $msg = "";
    $other = "";

    $uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
    $uploadfile = $uploaddir . basename($filename);

    if (is_null($pmapdata)) {
      $columns = array('id',
                       'parent_id',
                       'quantity',
                       'price',
                       'cost',
                       'msrp',
                       'model',
                       'sku',
                       'date_added',
                       'last_modified',
                       'weight',
                       'weight_class',
                       'status',
                       'tax_class_id',
                       'manufacturer',
                       'ordered',
                       'has_children',

                       'language_id',
                       'name',
                       'description',
                       'permalink',
                       'tags',
                       'meta_title',
                       'meta_keywords',
                       'meta_description',
                       'url',
                       'products_viewed',

                       'categories',
                       'base_image'
                       );
    } else {
      // do the mapping of columns here with the mapdata
    }

    $ext = end(explode(".", $filename));
    $delim = (($ext == 'txt')?"\t":(($ext == 'csv')?",":"\t"));

    $row = 0;
    if (($handle = fopen($uploadfile, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, $delim)) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
          if($row != 0){
            $import_array[$row][$columns[$c]] = $data[$c];
          }
        }
        $row++;
      }
      fclose($handle);
    }

    // Need to find and remove blank rows
    $match_count = 0;
    $insert_count = 0;

    if ($pwizard != 'false') {
      // p wizard stuff like return columns and etc.
      // $other = 'pwizard ' . $pwizard;
    } else {
      // do the import as usual
      // utilize import array to go through each column and run on each to check for product id and if not matched import and remove from arrray
      foreach ($import_array as $product) {
        // Get the products ID for control
        $products_id = $product['id'];

        // need to get the weight class since Im outputting lb and kg instead of the ids
        if ($product['weight_class'] != '') {
          $QweightClass = $lC_Database->query("SELECT * FROM :table_weight_classes wc WHERE wc.weight_class_key = :weight_class_key");
          $QweightClass->bindTable(':table_weight_classes', TABLE_WEIGHT_CLASS);
          $QweightClass->bindValue(':weight_class_key', $product['weight_class']);
          $QweightClass->execute();

          if ($lC_Database->isError()) {
            $errormsg .= 'weight class err ' . $lC_Database->getError();
          } else {
            $product['weight_class_id'] = $QweightClass->value('weight_class_id');
          }
        }

        // convert the permalink to a safe output
        $product['permalink'] = lC_Products_import_export_Admin::generate_clean_permalink($product['permalink']);

        // need to get ids for these categories if they dont exist we need to make them and return that id
        if ($product['categories'] != '') {
          $product['categories'] = explode(",", $product['categories']);
          foreach ($product['categories'] as $catName) {
            if ($catName != '') {
              $catCheck = $lC_Database->query("SELECT * FROM :table_categories_description cd WHERE cd.categories_name = :categories_name");
              $catCheck->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
              $catCheck->bindValue(':categories_name', $catName);
              $catCheck->execute();

              if ($catCheck->numberOfRows()) {
                $category_ids[] = $catCheck->value('categories_id');
              } else {
                // insert a category that doesnt exist
                $QcatInsert = $lC_Database->query("INSERT INTO :table_categories (parent_id, categories_status, categories_mode) values (:parent_id, :categories_status, :categories_mode)");
                $QcatInsert->bindTable(':table_categories', TABLE_CATEGORIES);
                $QcatInsert->bindInt(':parent_id', '0');
                $QcatInsert->bindInt(':categories_status', '1');
                $QcatInsert->bindValue(':categories_mode', 'category');
                $QcatInsert->execute();

                if ($lC_Database->isError()) {
                  $errormsg .= $lC_Database->getError();
                } else {
                  // if we did ok inserting to the main cat table lets do the description
                  $currentCatId = $lC_Database->nextID();
                  $QcatDescInsert = $lC_Database->query("INSERT INTO :table_categories_description (categories_id, language_id, categories_name) VALUES (:categories_id, :language_id, :categories_name)");
                  $QcatDescInsert->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
                  $QcatDescInsert->bindInt(':categories_id', $currentCatId);
                  $QcatDescInsert->bindInt(':language_id', $product['language_id']);
                  $QcatDescInsert->bindValue(':categories_name', $catName);
                  $QcatDescInsert->execute();

                  if ($lC_Database->isError()) {
                    $errormsg .= 'descerr: ' . $lC_Database->getError();
                  } else {
                    // if were successful add the inserted id to the category ids
                    $category_ids[] = $currentCatId;
                  }
                }
              }
            }
          }
          $product['categories'] = $category_ids;
        }

        // need to get the id for the manufacturerif($product['manufacturer'] != ''){
        if ($product['manufacturer'] != '') {
          $Qman = $lC_Database->query("SELECT * FROM :table_manufacturers WHERE manufacturers_name = :manufacturers_name");
          $Qman->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
          $Qman->bindValue(':manufacturers_name', $product['manufacturer']);
          $Qman->execute();

          if($Qman->numberOfRows()){
            $product['manufacturers_id'] = $Qman->value('manufacturers_id');
          } else {
            // insert a manufacture that doesn't exist
            $QmanInsert = $lC_Database->query("INSERT INTO :table_manufacturers (manufacturers_name) VALUES (:manufacturers_name)");
            $QmanInsert->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
            $QmanInsert->bindValue(':manufacturers_name', $product['manufacturer']);
            $QmanInsert->execute();

            if($lC_Database->isError()){
              $errormsg .= 'man insert err '.$lC_Database->getError();;
            } else {
              $product['manufacturers_id'] = $lC_Database->nextID();
            }
          }
        } 

        // check for a match in the database	  
        $Qcheck = $lC_Database->query("SELECT * FROM :table_products WHERE products_id = :products_id");
        $Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
        $Qcheck->bindInt(':products_id', $products_id);
        $Qcheck->execute();

        if ($Qcheck->numberOfRows()) {
          // the product exists in the database so were just going to update the product with the new data
          $match_count++;

          $error = false;

          $lC_Database->startTransaction();

          $Qproduct = $lC_Database->query('update :table_products set products_quantity = :products_quantity, products_cost = :products_cost, products_price = :products_price, products_msrp = :products_msrp, products_model = :products_model, products_sku = :products_sku, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id, manufacturers_id = :manufacturers_id, products_date_added = :products_date_added WHERE products_id = :products_id');
          $Qproduct->bindInt(':products_id', $products_id);
          $Qproduct->bindValue(':products_date_added', $product['date_added']);
          $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
          $Qproduct->bindValue(':products_quantity', $product['quantity']);
          $Qproduct->bindValue(':products_cost', $product['cost']);
          $Qproduct->bindValue(':products_price', $product['price']);
          $Qproduct->bindValue(':products_msrp', $product['msrp']);
          $Qproduct->bindValue(':products_model', $product['model']);
          $Qproduct->bindValue(':products_sku', $product['sku']);
          $Qproduct->bindValue(':products_weight', $product['weight']);
          $Qproduct->bindInt(':products_weight_class', $product['weight_class_id']);
          $Qproduct->bindInt(':products_status', $product['status']);
          $Qproduct->bindInt(':products_tax_class_id', $product['tax_class_id']);
          $Qproduct->bindInt(':manufacturers_id', $product['manufacturers_id']);
          $Qproduct->setLogging($_SESSION['module'], $products_id);
          $Qproduct->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            $errormsg .= '   initial: '.$products_id.'  '.$lC_Database->getError();
          } else { 
            $Qcategories = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id');
            $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qcategories->bindInt(':products_id', $products_id);
            $Qcategories->setLogging($_SESSION['module'], $products_id);
            $Qcategories->execute();

            if ( $lC_Database->isError() ) { 
              $error = true;
            } else {
              $categories = $product['categories'];
              if ( isset($categories) && !empty($categories) ) {
                foreach ($categories as $category_id) {
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
            $Qpd = $lC_Database->query('update :table_products_description set products_name = :products_name, products_description = :products_description, products_keyword = :products_keyword, products_tags = ":products_tags", products_url = ":products_url" WHERE products_id = :products_id AND language_id = :language_id');
            $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
            $Qpd->bindInt(':products_id', $products_id);
            $Qpd->bindInt(':language_id', $product['language_id']);
            $Qpd->bindValue(':products_name', $product['name']);
            $Qpd->bindValue(':products_description', $product['description']);
            $Qpd->bindValue(':products_keyword', $product['permalink']);
            $Qpd->bindValue(':products_tags', $product['tags']);
            $Qpd->bindValue(':products_url', $product['url']);
            $Qpd->setLogging($_SESSION['module'], $products_id);
            $Qpd->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }

          if ( $error === false ) {
            $lC_Database->commitTransaction();

            lC_Cache::clear('categories');
            lC_Cache::clear('category_tree');
            lC_Cache::clear('also_purchased');
          } else {
            $lC_Database->rollbackTransaction();
            $errormsg .= '    Error on product id: '.$products_id.'  ';
          }
        } else {
          // the product doesnt exist so lets write it into the database
          $insert_count++;

          $error = false;

          $lC_Database->startTransaction();

          $Qproduct = $lC_Database->query('insert into :table_products (products_id, products_quantity, products_cost, products_price, products_msrp, products_model, products_sku, products_weight, products_weight_class, products_status, products_tax_class_id, manufacturers_id, products_date_added) values (:products_id, :products_quantity, :products_cost, :products_price, :products_msrp, :products_model, :products_sku, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :manufacturers_id, :products_date_added)');
          $Qproduct->bindInt(':products_id', $products_id);
          $Qproduct->bindRaw(':products_date_added', 'now()');
          $Qproduct->bindTable(':table_products', TABLE_PRODUCTS);
          $Qproduct->bindInt(':products_quantity', $product['quantity']);
          $Qproduct->bindFloat(':products_cost', $product['cost']);
          $Qproduct->bindFloat(':products_price', $product['price']);
          $Qproduct->bindFloat(':products_msrp', $product['msrp']);
          $Qproduct->bindValue(':products_model', $product['model']);
          $Qproduct->bindValue(':products_sku', $product['sku']);
          $Qproduct->bindFloat(':products_weight', $product['weight']);
          $Qproduct->bindInt(':products_weight_class', $product['weight_class']);
          $Qproduct->bindInt(':products_status', $product['status']);
          $Qproduct->bindInt(':products_tax_class_id', $product['tax_class_id']);
          $Qproduct->bindInt(':manufacturers_id', $product['manufacturers_id']);
          $Qproduct->setLogging($_SESSION['module'], $products_id);
          $Qproduct->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
          } else {
            $Qcategories = $lC_Database->query('delete from :table_products_to_categories where products_id = :products_id');
            $Qcategories->bindTable(':table_products_to_categories', TABLE_PRODUCTS_TO_CATEGORIES);
            $Qcategories->bindInt(':products_id', $products_id);
            $Qcategories->setLogging($_SESSION['module'], $products_id);
            $Qcategories->execute();

            if ( $lC_Database->isError() ) { 
              $error = true;
            } else {
              $categories = explode(',',$product['categories']);
              if ( isset($categories) && !empty($categories) ) {
                foreach ($categories as $category_id) {
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
            $Qimage = $lC_Database->query('insert into :table_products_images (products_id, image, default_flag, sort_order, date_added) values (:products_id, :image, :default_flag, :sort_order, :date_added)');
            $Qimage->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
            $Qimage->bindInt(':products_id', $products_id);
            $Qimage->bindValue(':image', $product['base_image']);
            $Qimage->bindInt(':default_flag', '1');
            $Qimage->bindInt(':sort_order', 0);
            $Qimage->bindRaw(':date_added', 'now()');
            $Qimage->setLogging($_SESSION['module'], $products_id);
            $Qimage->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
            }
          }

          if ( $error === false ) {
            $Qpd = $lC_Database->query('insert into :table_products_description (products_id, language_id, products_name, products_description, products_keyword, products_tags, products_url) values (:products_id, :language_id, :products_name, :products_description, :products_keyword, :products_tags, :products_url)');
            $Qpd->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
            $Qpd->bindInt(':products_id', $products_id);
            $Qpd->bindInt(':language_id', $product['language_id']);
            $Qpd->bindValue(':products_name', $product['name']);
            $Qpd->bindValue(':products_description', $product['description']);
            $Qpd->bindValue(':products_keyword', $product['permalink']);
            $Qpd->bindValue(':products_tags', $product['tags']);
            $Qpd->bindValue(':products_url', $product['url']);
            $Qpd->setLogging($_SESSION['module'], $products_id);
            $Qpd->execute();

            if ( $lC_Database->isError() ) {
              $error = true;
              break;
            }
          }

          if ( $error === false ) {
            $lC_Database->commitTransaction();

            lC_Cache::clear('categories');
            lC_Cache::clear('category_tree');
            lC_Cache::clear('also_purchased');

          } else {
            $lC_Database->rollbackTransaction();
          }
        }
      }
    } // end if $do
    // for all left in array match and update the records
    // use columns from import to figure out what columns are what

    if ($errormsg) { $ipreturn['error'] = $errormsg; }
    if ($msg) { $ipreturn['msg'] = $msg; }
    if ($other) { $ipreturn['other'] = $other; }
    $ipreturn['matched'] = $match_count;
    $ipreturn['inserted'] = $insert_count;
    $ipreturn['total'] = $match_count+$insert_count;

    return $ipreturn;
  }

 /*
  * Return the temp file name for downloading categories
  *
  * @param boolean $cwizard A boolean saying do cwizard or not
  * @param string $ctype A string of the import type
  * @param boolean $cbackup A boolean letting me know to backup the whole table or not
  * @access public
  * @return array
  */
  public static function importCategories($filename, $cwizard, $ctype, $cbackup, $cmapdata = NULL) {
    global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;

    if ($cwizard == 'false') {
      $cwizard = FALSE; 
    } else {
      $cwizard = TRUE;
    }

    $lC_Categories = new lC_Categories_Admin();

    $error = FALSE;
    $errormsg = "";
    $msg = "";

    $uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
    // $other .= 'Upload Dir: ' . $uploaddir;
    $uploadfile = $uploaddir . basename($filename);

    if (is_null($cmapdata)) {
      $columns = array('categories_id',
                       'image',
                       'parent_id',
                       'sort_order',
                       'mode',
                       'link_target',
                       'custom_url',
                       'status',
                       'nav',
                       'box',
                       'date_added',
                       'last_modified',

                       'language_id',
                       'name',
                       'menu_name',
                       'blurb',
                       'description',
                       'keyword',
                       'tags',
                       );
    } else {

      // do the mapping of columns here with the mapdata
      $columns = array('categories_id',
                       'image',
                       'parent_id',
                       'sort_order',
                       'mode',
                       'link_target',
                       'custom_url',
                       'status',
                       'nav',
                       'box',
                       'date_added',
                       'last_modified',
                                               
                       'language_id',
                       'name',
                       'menu_name',
                       'blurb',
                       'description',
                       'keyword',
                       'tags',
                       );
    }

    $ext = end(explode(".", $filename));
    if ($ext == 'txt') {
      $delim = "\t";
    } else if($ext == 'csv'){
      $delim = ",";
    } else {
      $delim = "\t";
    }

    $row = 0;
    if (($handle = fopen($uploadfile, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
          if($row != 0){
            $import_array[$row][$columns[$c]] = $data[$c];
          }
        }
        $row++;
      }
      fclose($handle);
    }

    $match_count = 0;
    $insert_count = 0;

    if ($cwizard) {
      // p wizard stuff like return columns and etc.
      $msg .= 'CWIZARD AGAIN!~!!!!!!!!!!';
    } else {
      // do the import as usual
      // utilize import array to go through each column and run on each to check for product id and if not matched import and remove from arrray
      foreach ($import_array as $category) {
        // Get the products ID for control
        $categories_id = $category['categories_id'];

        // check for a match in the database	  
        $Qcheck = $lC_Database->query("SELECT * FROM :table_categories WHERE categories_id = :categories_id");
        $Qcheck->bindTable(':table_categories', TABLE_CATEGORIES);
        $Qcheck->bindInt(':categories_id', $categories_id);
        $category_check = $Qcheck->numberOfRows();

        if ($category_check > 0) {
          // the product exists in the database so were just going to update the product with the new data
          $match_count++;

          // build data array of product information
          $data['categories_id'] = $category['categories_id'];
          $data['image'] = $category['image'];
          $data['parent_id'] = $category['parent_id'];
          $data['sort_order'] = $category['sort_order'];
          $data['mode'] = $category['mode'];
          $data['link_target'] = $category['link_target'];
          $data['custom_url'] = $category['custom_url'];
          $data['status'] = $category['status'];
          $data['nav'] = $category['nav'];
          $data['box'] = $category['box'];
          $data['date_added'] = $category['date_added'];
          $data['last_modified'] = $category['last_modified'];

          $data['name'][$category['language_id']] = $category['name'];
          $data['menu_name'][$category['language_id']] = $category['menu_name'];
          $data['blurb'][$category['language_id']] = $category['blurb'];
          $data['description'][$category['language_id']] = $category['description'];
          $data['keyword'][$category['language_id']] = $category['keyword'];
          $data['tags'][$category['language_id']] = $category['tags'];

          $lC_Categories->save($categories_id, $data);

        } else {
          // the product doesnt exist so lets write it into the database
          $insert_count++;

          // Insert using code from the catgories class
          $error = false;

          $lC_Database->startTransaction();

          $Qcat = $lC_Database->query('insert into :table_categories (categories_id, categories_image, parent_id, sort_order, categories_mode, categories_link_target, categories_custom_url, categories_status, categories_visibility_nav, categories_visibility_box, date_added) values (:categories_id, :categories_image, :parent_id, :sort_order, :categories_mode, :categories_link_target, :categories_custom_url, :categories_status, :categories_visibility_nav, :categories_visibility_box, now())');
          $Qcat->bindInt(':categories_id', $category['categories_id']);
          $Qcat->bindInt(':parent_id', $category['parent_id']);

          $Qcat->bindTable(':table_categories', TABLE_CATEGORIES);
          $Qcat->bindValue(':categories_image', $category['image']);
          $Qcat->bindInt(':parent_id', $category['parent_id']);
          $Qcat->bindInt(':sort_order', $category['sort_order']);
          $Qcat->bindValue(':categories_mode', $category['mode']);
          $Qcat->bindInt(':categories_link_target', $category['link_target']);
          $Qcat->bindValue(':categories_custom_url', $category['custom_url']);
          $Qcat->bindInt(':categories_status', $category['status']);
          $Qcat->bindInt(':categories_visibility_nav', $category['nav']);
          $Qcat->bindInt(':categories_visibility_box', $category['box']);
          $Qcat->setLogging($_SESSION['module'], $id);
          $Qcat->execute();


          // remove this line from categories for it to be re inserted
          $Qrcd = $lC_Database->query('delete from :table_categories_description where categories_id = :categories_id and language_id = :language_id');
          $Qrcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qrcd->bindInt(':categories_id', $categories_id);
          $Qrcd->bindInt(':language_id', $category['language_id']);
          $Qrcd->execute();


          $Qcd = $lC_Database->query('insert into :table_categories_description (categories_id, language_id, categories_name, categories_menu_name, categories_blurb, categories_description, categories_tags) values (:categories_id, :language_id, :categories_name, :categories_menu_name, :categories_blurb, :categories_description, :categories_tags)');

          $Qcd->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
          $Qcd->bindInt(':categories_id', $categories_id);
          $Qcd->bindInt(':language_id', $category['language_id']);
          $Qcd->bindValue(':categories_name', $category['name']);
          $Qcd->bindValue(':categories_menu_name', $category['menu_name']);
          $Qcd->bindValue(':categories_blurb', $category['blurb']);
          $Qcd->bindValue(':categories_description', $category['description']);
          $Qcd->bindValue(':categories_tags', $category['tags']);
          $Qcd->setLogging($_SESSION['module'], $categories_id);
          $Qcd->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            break;
          }

          if ( $error === false ) {
            $lC_Database->commitTransaction();
          } else {
            $lC_Database->rollbackTransaction();
          }
        }
      }
    } // end if $do
    // for all left in array match and update the records
    // use columns from import to figure out what columns are what
    if ($error || $errormsg != '') {
      if ($errormsg) { $icreturn['error'] = $errormsg . 'Error: ' . $error; }
    }
    if ($msg) { $icreturn['msg'] = $msg . ' cwizard: ' . $cwizard; }
    $icreturn['matched'] = $match_count;
    $icreturn['inserted'] = $insert_count;
    $icreturn['total'] = $match_count+$insert_count;

    return $icreturn;
  }

 /*
  * Return the temp file name for downloading categories
  *
  * @param boolean $cwizard A boolean saying do cwizard or not
  * @param string $ctype A string of the import type
  * @param boolean $cbackup A boolean letting me know to backup the whole table or not
  * @access public
  * @return array
  */
  public static function importOptionGroups($filename, $owizard, $otype, $obackup, $omapdata = NULL) {
    global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;

    if ($owizard == 'false') {
      $owizard = FALSE; 
    } else {
      $owizard = TRUE;
    }

    $error = FALSE;
    $errormsg = "";
    $msg = "";

    $uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
    // $other .= 'Upload Dir: ' . $uploaddir;
    $uploadfile = $uploaddir . basename($filename);

    if (is_null($mapdata)) {
      $columns = array('id',
                       'language_id',
                       'title',
                       'sort_order',
                       'module',
                       );
    } else {
      // do the mapping of columns here with the mapdata
    }

    $ext = end(explode(".", $filename));
    if ($ext == 'txt') {
      $delim = "\t";
    } else if ($ext == 'csv') {
      $delim = ",";
    } else {
      $delim = "\t";
    }

    $row = 0;
    if (($handle = fopen($uploadfile, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, $delim)) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
          if($row != 0){
            $import_array[$row][$columns[$c]] = $data[$c];
          }
        }
        $row++;
      }
      fclose($handle);
    }

    $match_count = 0;
    $insert_count = 0;

    if ($owizard) {
      // o wizard stuff like return columns and etc.
    } else {
      // do the import as usual
      // utilize import array to go through each column and run on each to check for product id and if not matched import and remove from arrray
      $znum = count($import_array);
      for ($z=0; $z < $znum; $z++) {
        $group = $import_array[$z];
        // Get the products ID for control
        $group_id = $group['id'];

        // check for a match in the database	  
        $Qcheck = $lC_Database->query("SELECT * FROM :table_products_variants_groups WHERE id = :id and language_id = :language_id");
        $Qcheck->bindTable(':table_products_variants_groups', 'products_variants_groups');
        $Qcheck->bindInt(':id', $group_id);
        $Qcheck->bindInt(':language_id', $group['language_id']);
        $group_check = $Qcheck->numberOfRows();

        if ($group_check > 0) {
          // the product exists in the database so were just going to update the product with the new data
          $match_count++;

          // build data array of product information
          $lC_Database->startTransaction();

          $Qcat = $lC_Database->query('update :table_products_variants_groups set title = :title, sort_order = :sort_order, module = :module where id = :id and language_id = :language_id');
          $Qcat->bindInt(':id', $group['id']);
          $Qcat->bindInt(':language_id', $group['language_id']);

          $Qcat->bindTable(':table_products_variants_groups', 'products_variants_groups');
          $Qcat->bindValue(':title', $group['title']);
          $Qcat->bindInt(':sort_order', $group['sort_order']);
          $Qcat->bindValue(':module', $group['module']);
          $Qcat->setLogging($_SESSION['module'], $group_id);
          $Qcat->execute();

        } else {
          // the product doesnt exist so lets write it into the database
          $insert_count++;

          // Insert using code from the catgories class
          $error = false;

          $lC_Database->startTransaction();

          $Qcat = $lC_Database->query('insert into :table_products_variants_groups (id, language_id, title, sort_order, module) values (:id, :language_id, :title, :sort_order, :module');
          $Qcat->bindInt(':id', $group['id']);
          $Qcat->bindInt(':language_id', $group['language_id']);

          $Qcat->bindTable(':table_products_variants_groups', 'products_variants_groups');
          $Qcat->bindValue(':title', $group['title']);
          $Qcat->bindInt(':sort_order', $group['sort_order']);
          $Qcat->bindValue(':module', $group['module']);
          $Qcat->setLogging($_SESSION['module'], $group_id);
          $Qcat->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            break;
          }

          if ( $error === false ) {
            $lC_Database->commitTransaction();
          } else {
            $lC_Database->rollbackTransaction();
          } 
        }
      }
    } // end if $do
    // for all left in array match and update the records
    // use columns from import to figure out what columns are what
    if ($error || $errormsg != '') {
      if ($errormsg) { $iogreturn['error'] = $errormsg . 'Error: ' . $error; }
    }
    if ($msg) { $iogreturn['msg'] = $msg . ' owizard: ' . $owizard . ' group check ' . $group_check; }
    $iogreturn['matched'] = $match_count;
    $iogreturn['inserted'] = $insert_count;
    $iogreturn['total'] = $match_count+$insert_count;

    return $iogreturn;
  }

 /*
  * Return the temp file name for downloading categories
  *
  * @param boolean $cwizard A boolean saying do cwizard or not
  * @param string $ctype A string of the import type
  * @param boolean $cbackup A boolean letting me know to backup the whole table or not
  * @access public
  * @return array
  */
  public static function importOptionVariants($filename, $owizard, $otype, $obackup, $omapdata = NULL) {
    global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;

    if ($owizard == 'false') {
      $owizard = FALSE; 
    } else {
      $owizard = TRUE;
    }

    $error = FALSE;
    $errormsg = "";
    $msg = "";

    $uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
    // $other .= 'Upload Dir: ' . $uploaddir;
    $uploadfile = $uploaddir . basename($filename);

    if (is_null($mapdata)) {
      $columns = array('id',
                       'languages_id',
                       'groups_id',
                       'title',
                       'sort_order',
                       );
    } else {
      // do the mapping of columns here with the mapdata
    }

    $ext = end(explode(".", $filename));
    if ($ext == 'txt') {
      $delim = "\t";
    } else if($ext == 'csv'){
      $delim = ",";
    } else {
      $delim = "\t";
    }

    $row = 0;
    if (($handle = fopen($uploadfile, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, $delim)) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
          if($row != 0){
            $import_array[][$columns[$c]] = $data[$c];
          }
        }
        $row++;
      }
      fclose($handle);
    }

    $match_count = 0;
    $insert_count = 0;

    if ($owizard) {
      // o wizard stuff like return columns and etc.
    } else {
      // do the import as usual
      // utilize import array to go through each column and run on each to check for product id and if not matched import and remove from arrray
      $znum = count($import_array);
      for ($z=0; $z < $znum; $z++) {
        $variant = $import_array[$z];
        // Get the products ID for control
        $variant_id = $variant['id'];

        // check for a match in the database	  
        $Qcheck = $lC_Database->query("SELECT * FROM :table_products_variants_values WHERE id = :id AND languages_id = :languages_id");
        $Qcheck->bindTable(':table_products_variants_values', 'products_variants_values');
        $Qcheck->bindInt(':id', $variant_id);
        $Qcheck->bindInt(':languages_id', $variant['languages_id']);
        $variant_check = $Qcheck->numberOfRows();

        if ($variant_check > 0) {
          // the product exists in the database so were just going to update the product with the new data
          $match_count++;

          // build data array of product information
          $lC_Database->startTransaction();

          $Qvar = $lC_Database->query('update :table_products_variants_values set groups_id = :groups_id, title = :title, sort_order = :sort_order where id = :id and languages_id = :languages_id');
          $Qvar->bindInt(':id', $variant['id']);
          $Qvar->bindInt(':languages_id', $variant['languages_id']);

          $Qvar->bindTable(':table_products_variants_values', 'products_variants_values');
          $Qvar->bindInt(':groups_id', $variant['groups_id']);
          $Qvar->bindValue(':title', $variant['title']);
          $Qvar->bindInt(':sort_order', $variant['sort_order']);
          $Qvar->setLogging($_SESSION['module'], $variant_id);
          $Qvar->execute();

        } else {
          // the product doesnt exist so lets write it into the database
          $insert_count++;

          // Insert using code from the catgories class
          $error = false;

          $lC_Database->startTransaction();

          $Qvar = $lC_Database->query('insert into :table_products_variants_values (id, languages_id, products_variants_groups_id, title, sort_order) values (:id, :languages_id, :groups_id, :title, :sort_order');
          $Qvar->bindInt(':id', $variant['id']);
          $Qvar->bindInt(':languages_id', $variant['languages_id']);

          $Qvar->bindTable(':table_products_variants_values', 'products_variants_values');
          $Qvar->bindInt(':groups_id', $variant['groups_id']);
          $Qvar->bindValue(':title', $variant['title']);
          $Qvar->bindInt(':sort_order', $variant['sort_order']);
          $Qvar->setLogging($_SESSION['module'], $variant_id);
          $Qvar->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            break;
          }

          if ( $error === false ) {
            $lC_Database->commitTransaction();
          } else {
            $lC_Database->rollbackTransaction();
          } 
        }
      }
    } // end if $do
    // for all left in array match and update the records
    // use columns from import to figure out what columns are what
    if ($error || $errormsg != '') {
      if ($errormsg) { $iovreturn['error'] = $errormsg . 'Error: ' . $error; }
    }
    if ($msg) { $iovreturn['msg'] = $msg . ' owizard: ' . $owizard . ' variant check ' . $variant_check; }
    $iovreturn['matched'] = $match_count;
    $iovreturn['inserted'] = $insert_count;
    $iovreturn['total'] = $match_count+$insert_count;

    return $iovreturn;
  }

 /*
  * Return the temp file name for downloading categories
  *
  * @param boolean $cwizard A boolean saying do cwizard or not
  * @param string $ctype A string of the import type
  * @param boolean $cbackup A boolean letting me know to backup the whole table or not
  * @access public
  * @return array
  */
  public static function importOptionProducts($filename, $owizard, $otype, $obackup, $omapdata = NULL) {
    global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;

    if ($owizard == 'false') {
      $owizard = FALSE; 
    } else {
      $owizard = TRUE;
    }

    $error = FALSE;
    $errormsg = "";
    $msg = "";

    $uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
    // $other .= 'Upload Dir: ' . $uploaddir;
    $uploadfile = $uploaddir . basename($filename);

    if (is_null($mapdata)) {

      $columns = array('id',
        'customers_group_id',
        'values_id',
        'options_id',
        'price_modifier',
      );
    } else {
      // do the mapping of columns here with the mapdata
    }

    $ext = end(explode(".", $filename));
    if ($ext == 'txt') {
      $delim = "\t";
    } else if($ext == 'csv'){
      $delim = ",";
    } else {
      $delim = "\t";
    }

    $row = 0;
    if (($handle = fopen($uploadfile, "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, $delim)) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
          if($row != 0){
            $import_array[$row][$columns[$c]] = $data[$c];
          }
        }
        $row++;
      }
      fclose($handle);
    }

    $match_count = 0;
    $insert_count = 0;

    if ($owizard) {
      // o wizard stuff like return columns and etc.
    } else {
      // do the import as usual
      // utilize import array to go through each column and run on each to check for product id and if not matched import and remove from arrray
      $znum = count($import_array);
      for ($z=0; $z < $znum; $z++) {
        $vproduct = $import_array[$z];
        // Get the products ID for control
        $vproduct_id = $vproduct['id'];

        // check for a match in the database	  
        $Qcheck = $lC_Database->query("SELECT * FROM :table_products_simple_options_values WHERE id = :id");
        $Qcheck->bindTable(':table_products_simple_options_values', 'products_simple_options_values');
        $Qcheck->bindInt(':id', $vproduct_id);
        $vproduct_check = $Qcheck->numberOfRows();

        if ($vproduct_check > 0) {
          // the product exists in the database so were just going to update the product with the new data
          $match_count++;

          // build data array of product information
          $lC_Database->startTransaction();

          $Qvprod = $lC_Database->query('update :table_products_simple_options_values set customers_group_id = :customers_group_id, values_id = :values_id, options_id = :options_id, price_modifier = :price_modifier where id = :id');
          $Qvprod->bindInt(':id', $vproduct['id']);

          $Qvprod->bindTable(':table_products_simple_options_values', 'products_simple_options_values');
          $Qvprod->bindInt(':customers_group_id', $vproduct['customers_group_id']);
          $Qvprod->bindInt(':values_id', $vproduct['values_id']);
          $Qvprod->bindInt(':options_id', $vproduct['options_id']);
          $Qvprod->bindInt(':price_modifier', $vproduct['price_modifier']);
          $Qvprod->setLogging($_SESSION['module'], $vproduct_id);
          $Qvprod->execute();

        } else {
          // the product doesnt exist so lets write it into the database
          $insert_count++;

          // Insert using code from the catgories class
          $error = false;

          $lC_Database->startTransaction();

          $Qvprod = $lC_Database->query('insert into :table_products_simple_options_values (id, title, sort_order, module) values (:id, :language_id, :title, :sort_order, :module');
          $Qvprod->bindInt(':id', $vproduct['id']);

          $Qvprod->bindTable(':table_products_simple_options_values', 'products_simple_options_values');
          $Qvprod->bindInt(':customers_group_id', $vproduct['customers_group_id']);
          $Qvprod->bindInt(':values_id', $vproduct['values_id']);
          $Qvprod->bindInt(':options_id', $vproduct['options_id']);
          $Qvprod->bindInt(':price_modifier', $vproduct['price_modifier']);
          $Qvprod->setLogging($_SESSION['module'], $vproduct_id);
          $Qvprod->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            break;
          }

          if ( $error === false ) {
            $lC_Database->commitTransaction();
          } else {
            $lC_Database->rollbackTransaction();
          } 
        }
      }
    } // end if $do
    // for all left in array match and update the records
    // use columns from import to figure out what columns are what
    if ($error || $errormsg != '') {
      if ($errormsg){ $iopreturn['error'] = $errormsg . 'Error: ' . $error; }
    }
    if ($msg) { $iopreturn['msg'] = $msg . ' owizard: ' . $owizard . ' vproduct check ' . $vproduct_check; }
    $iopreturn['matched'] = $match_count;
    $iopreturn['inserted'] = $insert_count;
    $iopreturn['total'] = $match_count+$insert_count;

    return $iopreturn;
  }
}
?>