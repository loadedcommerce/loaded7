<?php
  /*
  $Id: product_variants.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html

  @function The lC_Product_variants_Admin class manages product variants
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
	
	switch($type){
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
	
	switch($filter){
	  case 'none':
	    break;
	}
	
	$Qtotals = $lC_Database->query($QtotalsSQL);
	$Qtotals->execute();
	
    $result = array();
	if( !$lC_Database->isError() ){
	  $result['total'] = $Qtotals->valueInt('total');
	}

    return $result;
  }
  
 /*
  * Retutn the variant groups
  *
  * @access public
  * @return array
  */
  public static function getVariantGroups() {
    global $lC_Database, $lC_Language;

    $Qgroups = $lC_Database->query('select * from :table_products_variants_groups where languages_id = :languages_id');
    $Qgroups->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
    $Qgroups->bindInt(':languages_id', $lC_Language->getID());
    $Qgroups->execute();

    $groups = array();
    while ($Qgroups->next()) {
      $groups[] = $Qgroups->toArray();
    }
    
    $Qgroups->freeResult();
    
    return $groups;
  } 
  
 /*
  * Retutn the variant entries
  *
  * @access public
  * @return array
  */
  public static function getVariantEntries($id) {
    global $lC_Database, $lC_Language;

    $Qvalues = $lC_Database->query('select * from :table_products_variants_values where products_variants_groups_id = :products_variants_groups_id and languages_id = :languages_id');
    $Qvalues->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
    $Qvalues->bindInt(':products_variants_groups_id', $id);
    $Qvalues->bindInt(':languages_id', $lC_Language->getID());
    $Qvalues->execute();

    $entries = array();
    while ($Qvalues->next()) {
      $entries[] = $Qvalues->toArray();
    }
    
    $Qvalues->freeResult();
    
    return $entries;
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
	  $datetime = '';//lC_Datetime::getTimestamp();
	  $filename = 'products-'.$datetime.$pgtype;
	  if($pgformat == 'tabbed'){
		  $filename = $filename.'.txt';
		  $delim = "\t";
		  $seperator = ",";
	  } elseif($pgformat == 'csv'){
		  $filename = $filename.'.'.$pgformat;
		  $delim = ",";
		  $seperator = ",";
	  } else {
		  return false;
	  }
	  
	  $filepath = DIR_FS_WORK . "products_import_export/exports/".$filename;
	  if( isset($_SERVER['HTTPS'] )  && $_SERVER['HTTPS'] != 'off' ) {
        $domain = 'https://' . $_SERVER['HTTP_HOST'];
      } else {
		$domain = 'http://' . $_SERVER['HTTP_HOST'];
	  }
	  $url = $domain . '/' . str_replace(DIR_FS_CATALOG, "", $filepath);
	  
	  // make columns in clude full table names to i can implode into sql statement
	  // add image and category and other product tables to columns and query
	  $sql_columns = array('products.products_id',
	  					   'products.parent_id',
						   'products.products_quantity',
						   'products.products_price',
						   'products.products_cost',
						   'products.products_msrp',
						   'products.products_model',
						   'products.products_sku',
						   'products.products_date_added',
						   'products.products_last_modified',
						   'products.products_weight',
						   'weight_classes.weight_class_key',
						   'products.products_status',
						   'products.products_tax_class_id',
						   'manufacturers.manufacturers_name',
						   'products.products_ordered',
						   'products.has_children',
						   
						   'products_description.language_id',
						   'products_description.products_name',
						   'products_description.products_description',
						   'products_description.products_keyword',
						   'products_description.products_tags',
						   'products_description.products_meta_title',
						   'products_description.products_meta_keywords',
						   'products_description.products_meta_description',
						   'products_description.products_url',
						   'products_description.products_viewed'
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
	  
	  $sql_statement = 'SELECT '.$sql_columns.' FROM products_description, weight_classes, products LEFT JOIN manufacturers ON (products.manufacturers_id = manufacturers.manufacturers_id) WHERE products_description.products_id = products.products_id AND products.products_weight_class = weight_classes.weight_class_id';
	  
	  // make this section get the data and make a file in work folder for the url to be returned.
	  $Qproducts = $lC_Database->query($sql_statement);
	  $Qproducts->execute();
	  
	  $columns[] = 'categories';
	  $columns[] = 'base_image';
	  
	  $products = array();
      while ($Qproducts->next()) {
        $products[] = $Qproducts->toArray();
      }
	  
	  // seperate out all categories and images and comma delimited columns
	  
	  $content = '';
	  foreach($products as $product){
	  	foreach($product as $column_output){
			$content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
		}
		
		$Qcategories = $lC_Database->query("SELECT * FROM :table_products_to_categories, :table_categories_description, :table_weight_classes WHERE products_to_categories.products_id = :products_id AND products_to_categories.categories_id = categories_description.categories_id AND categories_description.language_id = :language_id");
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
		
		//$content .= '\'' . implode('\'' . "\t" . '\'', $product) . '\'' . "\n";
	  }
	  
	  $fp = fopen($filepath,"wb");
	  fwrite($fp, implode($delim, $columns) . "\n" . $content);
	  fclose($fp);
	  
	  return array('url' => $url, 'sql_statement' => $sql_statement);
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
	  $datetime = '';//lC_Datetime::getTimestamp();
	  $filename = 'categories';// . '-' . $datetime;
	  if($cgformat == 'tabbed'){
		  $filename = $filename.'.txt';
		  $delim = "\t";
		  $seperator = ",";
	  } elseif($cgformat == 'csv'){
		  $filename = $filename.'.'.$pgformat;
		  $delim = ",";
		  $seperator = ",";
	  } else {
		  return false;
	  }
	  
	  $filepath = DIR_FS_WORK . "products_import_export/exports/".$filename;
	  if( isset($_SERVER['HTTPS'] )  && $_SERVER['HTTPS'] != 'off' ) {
        $domain = 'https://' . $_SERVER['HTTP_HOST'];
      } else {
		$domain = 'http://' . $_SERVER['HTTP_HOST'];
	  }
	  $url = $domain . '/' . str_replace(DIR_FS_CATALOG, "", $filepath);
	  
	  // make columns in clude full table names to i can implode into sql statement
	  // add image and category and other product tables to columns and query
	    $sql_columns = array('categories.categories_id',
		                 'categories.categories_image',
		                 'categories.parent_id',
		                 'categories.sort_order',
		                 'categories.categories_mode',
		                 'categories.categories_link_target',
		                 'categories.categories_custom_url',
		                 'categories.categories_status',
		                 'categories.categories_visibility_nav',
		                 'categories.categories_visibility_box',
		                 'categories.date_added',
		                 'categories.last_modified',
						 
						 'categories_description.language_id',
						 'categories_description.categories_name',
						 'categories_description.categories_menu_name',
						 'categories_description.categories_blurb',
						 'categories_description.categories_description',
						 'categories_description.categories_keyword',
						 'categories_description.categories_tags',
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
	  
	  $sql_statement = 'SELECT '.$sql_columns.' FROM :table_categories, :table_categories_description WHERE categories_description.categories_id = categories.categories_id';
	  
	  // make this section get the data and make a file in work folder for the url to be returned.
	  $Qcategories = $lC_Database->query($sql_statement);
	  $Qcategories->bindTable(':table_categories', TABLE_CATEGORIES);
	  $Qcategories->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
	  $Qcategories->execute();
	  
	  $categories = array();
      while ($Qcategories->next()) {
        $categories[] = $Qcategories->toArray();
      }
	  
	  // seperate out all categories and images and comma delimited columns
	  
	  $content = '';
	  foreach($categories as $category){
	  	foreach($category as $column_output){
			$content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
		}
		$content .= "\n";
	  }
	  
	  $fp = fopen($filepath,"wb");
	  fwrite($fp, implode($delim, $columns) . "\n" . $content);
	  fclose($fp);
	  
	  return array('url' => $url, 'sql_statement' => $sql_statement);
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
	  $datetime = '';//lC_Datetime::getTimestamp();
	  $filename = 'option-groups';// . '-' . $datetime;
	  if($ogformat == 'tabbed'){
		  $filename = $filename.'.txt';
		  $delim = "\t";
		  $seperator = ",";
	  } elseif($ogformat == 'csv'){
		  $filename = $filename.'.'.$ogformat;
		  $delim = ",";
		  $seperator = ",";
	  } else {
		  return false;
	  }
	  
	  $filepath = DIR_FS_WORK . "products_import_export/exports/".$filename;
	  if( isset($_SERVER['HTTPS'] )  && $_SERVER['HTTPS'] != 'off' ) {
        $domain = 'https://' . $_SERVER['HTTP_HOST'];
      } else {
		$domain = 'http://' . $_SERVER['HTTP_HOST'];
	  }
	  $url = $domain . '/' . str_replace(DIR_FS_CATALOG, "", $filepath);
	  
	  // make columns in clude full table names to i can implode into sql statement
	  // add image and category and other product tables to columns and query
	    $sql_columns = array('products_variants_groups.id',
		                 'products_variants_groups.languages_id',
		                 'products_variants_groups.title',
		                 'products_variants_groups.sort_order',
		                 'products_variants_groups.module',
						 );
	    $columns = array('id',
		                 'languages_id',
		                 'title',
		                 'sort_order',
		                 'module',
						 );
	  
	  $sql_columns = implode(",", $sql_columns);
	  
	  $sql_statement = 'SELECT '.$sql_columns.' FROM :table_products_variants_groups';
	  
	  // make this section get the data and make a file in work folder for the url to be returned.
	  $Qoptiongroups = $lC_Database->query($sql_statement);
	  $Qoptiongroups->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
	  $Qoptiongroups->execute();
	  
	  $categories = array();
      while ($Qoptiongroups->next()) {
        $optiongroups[] = $Qoptiongroups->toArray();
      }
	  
	  // seperate out all categories and images and comma delimited columns
	  
	  $content = '';
	  foreach($optiongroups as $optiongroup){
	  	foreach($optiongroup as $column_output){
			$content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
		}
		$content .= "\n";
	  }
	  
	  $fp = fopen($filepath,"wb");
	  fwrite($fp, implode($delim, $columns) . "\n" . $content);
	  fclose($fp);
	  
	  return array('url' => $url, 'sql_statement' => $sql_statement);
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
	  $datetime = '';//lC_Datetime::getTimestamp();
	  $filename = 'option-variants';// . '-' . $datetime;
	  if($ogformat == 'tabbed'){
		  $filename = $filename.'.txt';
		  $delim = "\t";
		  $seperator = ",";
	  } elseif($cgformat == 'csv'){
		  $filename = $filename.'.'.$ogformat;
		  $delim = ",";
		  $seperator = ",";
	  } else {
		  return false;
	  }
	  
	  $filepath = DIR_FS_WORK . "products_import_export/exports/".$filename;
	  if( isset($_SERVER['HTTPS'] )  && $_SERVER['HTTPS'] != 'off' ) {
        $domain = 'https://' . $_SERVER['HTTP_HOST'];
      } else {
		$domain = 'http://' . $_SERVER['HTTP_HOST'];
	  }
	  $url = $domain . '/' . str_replace(DIR_FS_CATALOG, "", $filepath);
	  
	  $version = 'CORE';
	  // make columns in clude full table names to i can implode into sql statement
	  // add image and category and other product tables to columns and query
	    $sql_columns = array('products_variants_values.id',
		                 'products_variants_values.languages_id',
		                 'products_variants_values.products_variants_groups_id',
		                 'products_variants_values.title',
		                 'products_variants_values.sort_order',
						 );
	    $columns = array('id',
		                 'languages_id',
		                 'variants_groups_id',
		                 'title',
		                 'sort_order',
						 );
	  
	  $sql_columns = implode(",", $sql_columns);
	  
	  $sql_statement = 'SELECT '.$sql_columns.' FROM :table_products_variants_values';
	  
	  // make this section get the data and make a file in work folder for the url to be returned.
	  $Qoptionvariants = $lC_Database->query($sql_statement);
	  $Qoptionvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
	  $Qoptionvariants->execute();
	  
	  $categories = array();
      while ($Qoptionvariants->next()) {
        $optionvariants[] = $Qoptionvariants->toArray();
      }
	  
	  // seperate out all categories and images and comma delimited columns
	  
	  $content = '';
	  foreach($optionvariants as $optionvariant){
	  	foreach($optionvariant as $column_output){
			$content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
		}
		$content .= "\n";
	  }
	  
	  $fp = fopen($filepath,"wb");
	  fwrite($fp, implode($delim, $columns) . "\n" . $content);
	  fclose($fp);
	  
	  return array('url' => $url, 'sql_statement' => $sql_statement);
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
	  $datetime = '';//lC_Datetime::getTimestamp();
	  $filename = 'options-to-products';// . '-' . $datetime;
	  if($ogformat == 'tabbed'){
		  $filename = $filename.'.txt';
		  $delim = "\t";
		  $seperator = ",";
	  } elseif($ogformat == 'csv'){
		  $filename = $filename.'.'.$ogformat;
		  $delim = ",";
		  $seperator = ",";
	  } else {
		  return false;
	  }
	  
	  $filepath = DIR_FS_WORK . "products_import_export/exports/".$filename;
	  if( isset($_SERVER['HTTPS'] )  && $_SERVER['HTTPS'] != 'off' ) {
        $domain = 'https://' . $_SERVER['HTTP_HOST'];
      } else {
		$domain = 'http://' . $_SERVER['HTTP_HOST'];
	  }
	  $url = $domain . '/' . str_replace(DIR_FS_CATALOG, "", $filepath);
	  
	  // make columns in clude full table names to i can implode into sql statement
	  // add image and category and other product tables to columns and query
	    $sql_columns = array('products_simple_options_values.id',
		                 'products_simple_options_values.customers_group_id',
		                 'products_simple_options_values.values_id',
		                 'products_simple_options_values.options_id',
		                 'products_simple_options_values.price_modifier',
						 );
	    $columns = array('id',
		                 'customers_group_id',
		                 'values_id',
		                 'options_id',
		                 'price_modifier',
						 );
	  
	  $sql_columns = implode(",", $sql_columns);
	  
	  $sql_statement = 'SELECT '.$sql_columns.' FROM :table_products_simple_options_values';
	  
	  // make this section get the data and make a file in work folder for the url to be returned.
	  $Qoptionproducts = $lC_Database->query($sql_statement);
	  $Qoptionproducts->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
	  $Qoptionproducts->execute();
	  
	  $categories = array();
      while ($Qoptionproducts->next()) {
        $optionproducts[] = $Qoptionproducts->toArray();
      }
	  
	  // seperate out all categories and images and comma delimited columns
	  
	  $content = '';
	  foreach($optionproducts as $optionproduct){
	  	foreach($optionproduct as $column_output){
			$content .= "\"" . trim(preg_replace('/\s+/', ' ', $column_output)) . "\"" . $delim;
		}
		$content .= "\n";
	  }
	  
	  $fp = fopen($filepath,"wb");
	  fwrite($fp, implode($delim, $columns) . "\n" . $content);
	  fclose($fp);
	  
	  return array('url' => $url, 'sql_statement' => $sql_statement);
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
  public static function importProducts($pwizard, $ptype, $pbackup, $pmapdata = NULL) {
	  global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;
		
		$lC_Products = new lC_Products_Admin();
		
		$error = "";
		$msg = "";
		$other = "";
		$fileElementName = 'productFile';
		if(!empty($_FILES[$fileElementName]['error'])) {
			switch($_FILES[$fileElementName]['error']) {
				case '1':
					$errormsg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$errormsg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$errormsg = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$errormsg = 'No file was uploaded.';
					break;
				case '6':
					$errormsg = 'Missing a temporary folder';
					break;
				case '7':
					$errormsg = 'Failed to write file to disk';
					break;
				case '8':
					$errormsg = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$errormsg = 'No error code avaiable';
			}
		} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
			$errormsg = 'No file was uploaded...'; // didnt get a file to do the upload
		} else {
			$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES[$fileElementName]);
			
			$uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
			//$other .= 'Upload Dir: ' . $uploaddir;
			$uploadfile = $uploaddir . basename($_FILES[$fileElementName]['name']);
		
			return array('other' => $uploadfile);
			
			if(is_null($pmapdata)){
			
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
								 'keyword',
								 'permalink',
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
			
			$ext = end(explode(".", $_FILES[$fileElementName]['name']));
			$delim = (($ext == 'txt')?"\t":(($ext == 'csv')?",":"\t"));

			$row = 0;
			if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile)) {
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
			}
		}
		
		$match_count = 0;
		$insert_count = 0;
		
		if($pwizard) {
			// p wizard stuff like return columns and etc.
			//$other = 'pwizard ' . $pwizard;
		} else {
			// do the import as usual
			// utilize import array to go through each column and run on each to check for product id and if not matched import and remove from arrray
			foreach($import_array as $product){
				// Get the products ID for control
				$products_id = $product['id'];
				
				// need to get ids for these categories if they dont exist we need to make them and return that id
				$product['categories'] = explode(",",$product['categories']);
				foreach($product['categories'] as $catName){
					$catCheck = $lC_Database->query("SELECT * FROM :table_categories_description WHERE categories_desscription.categories_name = :categories_name");
					$catCheck->bindTable(':table_categories_description', TABLE_CATEGORIES_DESCRIPTION);
					$catCheck->bindValue(':categories_name', $cat_name);
					$catCheck->execute();
					
					$catCheckNum = $catCheck->numberOfRows();
					
					if($catCheckNum > 0){
						$category_ids[] = $catCheck->getValue('categories_id');
					} else {
						// insert a category that doesn't exist
					}
				}
				$product['categories'] = $category_ids;
				
				// need to get the id for the manufacturer
				$Qman = $lC_Database->query("SELECT * FROM :table_manufacturers WHERE manufacturers_name = :manufacturers_name");
				$Qman->bindTable(':table_manufacturers', TABLE_MANUFACTURERS);
				$Qman->bindValue(':manufacturers_name', $product['manufacturer']);
				$Qman->execute();
				
				$product['manufacturers_id'] = $Qman->getValue('manufacturers_id');
				
				// check for a match in the database	  
				$Qcheck = $lC_Database->query("SELECT * FROM :table_products WHERE products_id = :products_id");
				$Qcheck->bindTable(':table_products', TABLE_PRODUCTS);
				$Qcheck->bindInt(':products_id', $products_id);
				$Qcheck->execute();
				$product_check = $Qcheck->numberOfRows();
								
				if($product_check > 0){
					// the product exists in the database so were just going to update the product with the new data
					$match_count++;
				  
					// build data array of product information
					$data = $product;
							
					$lC_Products->save($products_id, $data);
							
				} else {
					// the product doesnt exist so lets write it into the database
					$insert_count++;
							
					// Insert using code from the products class
	
					
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
				
				
				///////////////////////////// REVIEW PRICING
				/*
				
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
					  
					  //////////////////// REVIEW PRICING
					  
					  */
				
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
					
					/////////////// KKEEEEEP GOING IMAGES
					/*
					
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
					
					///////////////////// REVIEW IMAGES ABOVE
					
					
					*/
					
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
				
				//////////////// ATRRIBUTES
				
				/*
				
				
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
				
				
				/////////////////////////// VARIANTS COMBO? NOT HERE
				
				
					if ( $error === false ) {
					  $variants_array = array();
					  $default_variant_combo = null;
				
					  if ( isset($data['variants_combo']) && !empty($data['variants_combo']) ) {
						foreach ( $data['variants_combo'] as $key => $combos ) {
						  if ( isset($data['variants_combo_db'][$key]) ) {
							$Qsubproduct = $lC_Database->query('update :table_products set products_quantity = :products_quantity, products_cost = :products_cost, products_price = :products_price, products_msrp = :products_msrp, products_model = :products_model, products_sku = :products_sku, products_weight = :products_weight, products_weight_class = :products_weight_class, products_status = :products_status, products_tax_class_id = :products_tax_class_id where products_id = :products_id');
							$Qsubproduct->bindInt(':products_id', $data['variants_combo_db'][$key]);
						  } else {
							$Qsubproduct = $lC_Database->query('insert into :table_products (parent_id, products_quantity, products_cost, products_price, products_msrp, products_model, products_sku, products_weight, products_weight_class, products_status, products_tax_class_id, products_date_added) values (:parent_id, :products_quantity, :products_price, :products_model, :products_sku, :products_weight, :products_weight_class, :products_status, :products_tax_class_id, :products_date_added)');
							$Qsubproduct->bindInt(':parent_id', $products_id);
							$Qsubproduct->bindRaw(':products_date_added', 'now()');
						  }
				
						  $Qsubproduct->bindTable(':table_products', TABLE_PRODUCTS);
						  $Qsubproduct->bindInt(':products_quantity', $data['variants_quantity'][$key]);
						  $Qsubproduct->bindFloat(':products_cost', $data['variants_cost'][$key]);
						  $Qsubproduct->bindFloat(':products_price', $data['variants_price'][$key]);
						  $Qsubproduct->bindFloat(':products_msrp', $data['variants_msrp'][$key]);
						  $Qsubproduct->bindValue(':products_model', $data['variants_model'][$key]);
						  $Qsubproduct->bindValue(':products_sku', $data['variants_sku'][$key]);
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
				
				
				///////////////////VARIENST
				
				
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
					
					
					//////////////// OPTOINS
					
					
					
					// simple options
					if ( $error === false ) {
					  
					  // remove old values
					  $Qcheck = $lC_Database->query('select options_id from :table_products_simple_options where products_id = :products_id');
					  $Qcheck->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
					  $Qcheck->bindInt(':products_id', $products_id);
					  $Qcheck->execute();
					  // delete the simple options values
					  while ( $Qcheck->next() ) {
						$Qdel = $lC_Database->query('delete from :table_products_simple_options_values where options_id = :options_id');
						$Qdel->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
						$Qdel->bindInt(':options_id', $Qcheck->valueInt('options_id'));
						$Qdel->setLogging($_SESSION['module'], $products_id);
						$Qdel->execute();
					  } 
					  // delete the simple option
					  $Qdel = $lC_Database->query('delete from :table_products_simple_options where products_id = :products_id');
					  $Qdel->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
					  $Qdel->bindInt(':products_id', $products_id);
					  $Qdel->setLogging($_SESSION['module'], $products_id);
					  $Qdel->execute();                    
					  
					  $Qcheck->freeResult();      
					 
					  // if values are set, save them
					  if ( isset($data['simple_options_group_name']) && !empty($data['simple_options_group_name']) ) {
						foreach ( $data['simple_options_group_name'] as $group_id => $value ) {
						  
						  // add the new option
						  $Qoptions = $lC_Database->query('insert into :table_products_simple_options (options_id, products_id, sort_order, status) values (:options_id, :products_id, :sort_order, :status)');
						  $Qoptions->bindTable(':table_products_simple_options', TABLE_PRODUCTS_SIMPLE_OPTIONS);
						  $Qoptions->bindInt(':options_id', $group_id);
						  $Qoptions->bindInt(':products_id', $products_id);
						  $Qoptions->bindInt(':sort_order', $data['simple_options_group_sort_order'][$group_id]);
						  $Qoptions->bindInt(':status', $data['simple_options_group_status'][$group_id]);
						  $Qoptions->setLogging($_SESSION['module'], $products_id);
						  $Qoptions->execute();
				
						  if ( $lC_Database->isError() ) {
							$error = true;
							break;
						  }  
						  
						  // add the new option values
						  foreach ( $data['simple_options_entry_price_modifier'] as $customers_group_id => $options ) {
							foreach ( $options as $options_id => $option_value ) {
							  if ($options_id == $group_id) {
								foreach ( $option_value as $values_id => $price_modifier ) {
								  $Qoptions = $lC_Database->query('insert into :table_products_simple_options_values (values_id, options_id, customers_group_id, price_modifier) values (:values_id, :options_id, :customers_group_id, :price_modifier)');
								  $Qoptions->bindTable(':table_products_simple_options_values', TABLE_PRODUCTS_SIMPLE_OPTIONS_VALUES);
								  $Qoptions->bindInt(':values_id', $values_id);
								  $Qoptions->bindInt(':options_id', $options_id);
								  $Qoptions->bindInt(':customers_group_id', $customers_group_id);
								  $Qoptions->bindFloat(':price_modifier', (float)$price_modifier);
								  $Qoptions->setLogging($_SESSION['module'], $products_id);
								  $Qoptions->execute();
				
								  if ( $lC_Database->isError() ) {
									$error = true;
									break 4;
								  }            
								}
							  }
							}
						  }
						}
					  }      
					}    
				
				*/
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
		
		if($errormsg){$ipreturn['error'] = $errormsg;}
		if($msg){$ipreturn['msg'] = $msg;}
		if($other){$ipreturn['other'] = $other;}
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
  public static function importCategories($cwizard, $ctype, $cbackup, $cmapdata = NULL) {
	  global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;
		
		if($cwizard == 'false'){
			$cwizard = FALSE; 
		} else {
			$cwizard = TRUE;
		}
		
		$lC_Categories = new lC_Categories_Admin();
		
		$error = FALSE;
		$errormsg = "";
		$msg = "";
		$fileElementName = 'categoriesFile';
		if(!empty($_FILES[$fileElementName]['error'])) {
			switch($_FILES[$fileElementName]['error']) {
				case '1':
					$errormsg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$errormsg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$errormsg = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$errormsg = 'No file was uploaded.';
					break;
				case '6':
					$errormsg = 'Missing a temporary folder';
					break;
				case '7':
					$errormsg = 'Failed to write file to disk';
					break;
				case '8':
					$errormsg = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$errormsg = 'No error code avaiable';
			}
		} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
			$errormsg = 'No file was uploaded...'; // didnt get a file to do the upload
		} else {
			$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES[$fileElementName]);
			
			$uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
			//$other .= 'Upload Dir: ' . $uploaddir;
			$uploadfile = $uploaddir . basename($_FILES[$fileElementName]['name']);
			
			if(is_null($cmapdata)){
			
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
			
			$ext = end(explode(".", $_FILES[$fileElementName]['name']));
			if($ext == 'txt'){
				$delim = "\t";
			} else if($ext == 'csv'){
				$delim = ",";
			} else {
				$delim = "\t";
			}

			$row = 0;
			if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile)) {
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
			}
		}
		
		$match_count = 0;
		$insert_count = 0;
		
		if($cwizard) {
			// p wizard stuff like return columns and etc.
			$msg .= 'CWIZARD AGAIN!~!!!!!!!!!!';
		} else {
			// do the import as usual
			// utilize import array to go through each column and run on each to check for product id and if not matched import and remove from arrray
			foreach($import_array as $category){
				// Get the products ID for control
				$categories_id = $category['categories_id'];
				
				// check for a match in the database	  
				$Qcheck = $lC_Database->query("SELECT * FROM :table_categories WHERE categories_id = :categories_id");
				$Qcheck->bindTable(':table_categories', TABLE_CATEGORIES);
				$Qcheck->bindInt(':categories_id', $categories_id);
				$category_check = $Qcheck->numberOfRows();
						
				if($category_check > 0){
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
		if($error || $errormsg != ''){
		  if($errormsg){$icreturn['error'] = $errormsg . 'Error: ' . $error;}
		}
		if($msg){$icreturn['msg'] = $msg . ' cwizard: ' . $cwizard;}
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
  public static function importOptionGroups($owizard, $otype, $obackup, $omapdata = NULL) {
	  global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;
		
		if($owizard == 'false'){
			$owizard = FALSE; 
		} else {
			$owizard = TRUE;
		}
		
		$error = FALSE;
		$errormsg = "";
		$msg = "";
		$fileElementName = 'optionsGroupsFile';
		if(!empty($_FILES[$fileElementName]['error'])) {
			switch($_FILES[$fileElementName]['error']) {
				case '1':
					$errormsg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$errormsg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$errormsg = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$errormsg = 'No file was uploaded.';
					break;
				case '6':
					$errormsg = 'Missing a temporary folder';
					break;
				case '7':
					$errormsg = 'Failed to write file to disk';
					break;
				case '8':
					$errormsg = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$errormsg = 'No error code avaiable';
			}
		} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
			$errormsg = 'No file was uploaded...'; // didnt get a file to do the upload
		} else {
			$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES[$fileElementName]);
			
			$uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
			//$other .= 'Upload Dir: ' . $uploaddir;
			$uploadfile = $uploaddir . basename($_FILES[$fileElementName]['name']);
			
			if(is_null($mapdata)){
			
				$columns = array('id',
								 'language_id',
								 'title',
								 'sort_order',
								 'module',
								 );
			} else {
				// do the mapping of columns here with the mapdata
			}
			
			$ext = end(explode(".", $_FILES[$fileElementName]['name']));
			if($ext == 'txt'){
				$delim = "\t";
			} else if($ext == 'csv'){
				$delim = ",";
			} else {
				$delim = "\t";
			}

			$row = 0;
			if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile)) {
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
			}
		}
		
		$match_count = 0;
		$insert_count = 0;
		
		if($owizard) {
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
						
				if($group_check > 0){
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
		if($error || $errormsg != ''){
		  if($errormsg){$iogreturn['error'] = $errormsg . 'Error: ' . $error;}
		}
		if($msg){$iogreturn['msg'] = $msg . ' owizard: ' . $owizard . ' group check ' . $group_check; }
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
  public static function importOptionVariants($owizard, $otype, $obackup, $omapdata = NULL) {
	  global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;
		
		if($owizard == 'false'){
			$owizard = FALSE; 
		} else {
			$owizard = TRUE;
		}
		
		$error = FALSE;
		$errormsg = "";
		$msg = "";
		$fileElementName = 'optionsVariantsFile';
		if(!empty($_FILES[$fileElementName]['error'])) {
			switch($_FILES[$fileElementName]['error']) {
				case '1':
					$errormsg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$errormsg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$errormsg = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$errormsg = 'No file was uploaded.';
					break;
				case '6':
					$errormsg = 'Missing a temporary folder';
					break;
				case '7':
					$errormsg = 'Failed to write file to disk';
					break;
				case '8':
					$errormsg = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$errormsg = 'No error code avaiable';
			}
		} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
			$errormsg = 'No file was uploaded...'; // didnt get a file to do the upload
		} else {
			$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES[$fileElementName]);
			
			$uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
			//$other .= 'Upload Dir: ' . $uploaddir;
			$uploadfile = $uploaddir . basename($_FILES[$fileElementName]['name']);
			
			if(is_null($mapdata)){
			
				$columns = array('id',
								 'languages_id',
								 'groups_id',
								 'title',
								 'sort_order',
								 );
			} else {
				// do the mapping of columns here with the mapdata
			}
			
			$ext = end(explode(".", $_FILES[$fileElementName]['name']));
			if($ext == 'txt'){
				$delim = "\t";
			} else if($ext == 'csv'){
				$delim = ",";
			} else {
				$delim = "\t";
			}

			$row = 0;
			if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile)) {
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
			}
		}
		
		$match_count = 0;
		$insert_count = 0;
		
		if($owizard) {
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
					
				if($variant_check > 0){
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
		if($error || $errormsg != ''){
		  if($errormsg){$iovreturn['error'] = $errormsg . 'Error: ' . $error;}
		}
		if($msg){$iovreturn['msg'] = $msg . ' owizard: ' . $owizard . ' variant check ' . $variant_check; }
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
  public static function importOptionProducts($owizard, $otype, $obackup, $omapdata = NULL) {
	  global $lC_Database, $lC_Datetime, $lC_Language, $lC_Image;
		
		if($owizard == 'false'){
			$owizard = FALSE; 
		} else {
			$owizard = TRUE;
		}
		
		$error = FALSE;
		$errormsg = "";
		$msg = "";
		$fileElementName = 'optionsProductsFile';
		if(!empty($_FILES[$fileElementName]['error'])) {
			switch($_FILES[$fileElementName]['error']) {
				case '1':
					$errormsg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$errormsg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$errormsg = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$errormsg = 'No file was uploaded.';
					break;
				case '6':
					$errormsg = 'Missing a temporary folder';
					break;
				case '7':
					$errormsg = 'Failed to write file to disk';
					break;
				case '8':
					$errormsg = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$errormsg = 'No error code avaiable';
			}
		} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
			$errormsg = 'No file was uploaded...'; // didnt get a file to do the upload
		} else {
			$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES[$fileElementName]);
			
			$uploaddir = DIR_FS_WORK . 'products_import_export/imports/';
			//$other .= 'Upload Dir: ' . $uploaddir;
			$uploadfile = $uploaddir . basename($_FILES[$fileElementName]['name']);
			
			if(is_null($mapdata)){
			
				$columns = array('id',
								 'customers_group_id',
								 'values_id',
								 'options_id',
								 'price_modifier',
								 );
			} else {
				// do the mapping of columns here with the mapdata
			}
			
			$ext = end(explode(".", $_FILES[$fileElementName]['name']));
			if($ext == 'txt'){
				$delim = "\t";
			} else if($ext == 'csv'){
				$delim = ",";
			} else {
				$delim = "\t";
			}

			$row = 0;
			if (move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $uploadfile)) {
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
			}
		}
		
		$match_count = 0;
		$insert_count = 0;
		
		if($owizard) {
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
						
				if($vproduct_check > 0){
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
		if($error || $errormsg != ''){
		  if($errormsg){$iopreturn['error'] = $errormsg . 'Error: ' . $error;}
		}
		if($msg){$iopreturn['msg'] = $msg . ' owizard: ' . $owizard . ' vproduct check ' . $vproduct_check; }
		$iopreturn['matched'] = $match_count;
		$iopreturn['inserted'] = $insert_count;
		$iopreturn['total'] = $match_count+$insert_count;

	  return $iopreturn;
  }
}
?>