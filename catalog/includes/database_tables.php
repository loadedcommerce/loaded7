<?php
/*
  $Id: database_tables.php v1.0 2011-11-04 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2011 LoadedCommerce.com

  @author     LoadedCommerce Team
  @copyright  (c) 2011 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
  define('TABLE_ADMINISTRATORS', DB_TABLE_PREFIX . 'administrators');
  define('TABLE_ADMINISTRATORS_GROUPS', DB_TABLE_PREFIX . 'administrators_groups');
  define('TABLE_ADMINISTRATORS_ACCESS', DB_TABLE_PREFIX . 'administrators_access');
  define('TABLE_ADMINISTRATORS_LOG', DB_TABLE_PREFIX . 'administrators_log');
  define('TABLE_ADDRESS_BOOK', DB_TABLE_PREFIX . 'address_book');
  define('TABLE_BANNERS', DB_TABLE_PREFIX . 'banners');
  define('TABLE_BANNERS_HISTORY', DB_TABLE_PREFIX . 'banners_history');
  define('TABLE_CATEGORIES', DB_TABLE_PREFIX . 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', DB_TABLE_PREFIX . 'categories_description');
  define('TABLE_COMPONENTS', DB_TABLE_PREFIX . 'components');
  define('TABLE_COMPONENTS_FILES', DB_TABLE_PREFIX . 'components_files');
  define('TABLE_PLUGINS', DB_TABLE_PREFIX . 'plugins');
  define('TABLE_PLUGINS_GROUP', DB_TABLE_PREFIX . 'plugins_group');
  define('TABLE_CONFIGURATION', DB_TABLE_PREFIX . 'configuration');
  define('TABLE_CONFIGURATION_GROUP', DB_TABLE_PREFIX . 'configuration_group');
  define('TABLE_COUNTER', DB_TABLE_PREFIX . 'counter');
  define('TABLE_COUNTER_HISTORY', DB_TABLE_PREFIX . 'counter_history');
  define('TABLE_COUNTRIES', DB_TABLE_PREFIX . 'countries');
  define('TABLE_CREDIT_CARDS', DB_TABLE_PREFIX . 'credit_cards');
  define('TABLE_CURRENCIES', DB_TABLE_PREFIX . 'currencies');
  define('TABLE_CUSTOMERS', DB_TABLE_PREFIX . 'customers');
  define('TABLE_CUSTOMERS_GROUPS', DB_TABLE_PREFIX . 'customers_groups');
  define('TABLE_GEO_ZONES', DB_TABLE_PREFIX . 'geo_zones');
  define('TABLE_LANGUAGES', DB_TABLE_PREFIX . 'languages');
  define('TABLE_LANGUAGES_DEFINITIONS', DB_TABLE_PREFIX . 'languages_definitions');
  define('TABLE_MANUFACTURERS', DB_TABLE_PREFIX . 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', DB_TABLE_PREFIX . 'manufacturers_info');
  define('TABLE_NEWSLETTERS', DB_TABLE_PREFIX . 'newsletters');
  define('TABLE_NEWSLETTERS_LOG', DB_TABLE_PREFIX . 'newsletters_log');
  define('TABLE_ORDERS', DB_TABLE_PREFIX . 'orders');
  define('TABLE_ORDERS_PRODUCTS', DB_TABLE_PREFIX . 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', DB_TABLE_PREFIX . 'orders_products_download');
  define('TABLE_ORDERS_PRODUCTS_VARIANTS', DB_TABLE_PREFIX . 'orders_products_variants');
  define('TABLE_ORDERS_STATUS', DB_TABLE_PREFIX . 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', DB_TABLE_PREFIX . 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', DB_TABLE_PREFIX . 'orders_total');
  define('TABLE_ORDERS_TRANSACTIONS_HISTORY', DB_TABLE_PREFIX . 'orders_transactions_history');
  define('TABLE_ORDERS_TRANSACTIONS_STATUS', DB_TABLE_PREFIX . 'orders_transactions_status');
  define('TABLE_PRODUCT_ATTRIBUTES', DB_TABLE_PREFIX . 'product_attributes');
  define('TABLE_PRODUCTS', DB_TABLE_PREFIX . 'products');
  define('TABLE_PRODUCTS_DESCRIPTION', DB_TABLE_PREFIX . 'products_description');
  define('TABLE_PRODUCTS_IMAGES', DB_TABLE_PREFIX . 'products_images');
  define('TABLE_PRODUCTS_IMAGES_GROUPS', DB_TABLE_PREFIX . 'products_images_groups');
  define('TABLE_PRODUCTS_NOTIFICATIONS', DB_TABLE_PREFIX . 'products_notifications');
  define('TABLE_PRODUCTS_PRICING', DB_TABLE_PREFIX . 'products_pricing');
  define('TABLE_PRODUCTS_TO_CATEGORIES', DB_TABLE_PREFIX . 'products_to_categories');
  define('TABLE_PRODUCTS_VARIANTS', DB_TABLE_PREFIX . 'products_variants');
  define('TABLE_PRODUCTS_VARIANTS_GROUPS', DB_TABLE_PREFIX . 'products_variants_groups');
  define('TABLE_PRODUCTS_VARIANTS_GROUPS_LANG', DB_TABLE_PREFIX . 'products_variants_groups_lang');
  define('TABLE_PRODUCTS_VARIANTS_VALUES', DB_TABLE_PREFIX . 'products_variants_values');
  define('TABLE_PRODUCTS_VARIANTS_VALUES_LANG', DB_TABLE_PREFIX . 'products_variants_values_lang');
  define('TABLE_REVIEWS', DB_TABLE_PREFIX . 'reviews');
  define('TABLE_SESSIONS', DB_TABLE_PREFIX . 'sessions');
  define('TABLE_SHIPPING_AVAILABILITY', DB_TABLE_PREFIX . 'shipping_availability');
  define('TABLE_SHOPPING_CARTS', DB_TABLE_PREFIX . 'shopping_carts');
  define('TABLE_SHOPPING_CARTS_CUSTOM_VARIANTS_VALUES', DB_TABLE_PREFIX . 'shopping_carts_custom_variants_values');
  define('TABLE_SPECIALS', DB_TABLE_PREFIX . 'specials');
  define('TABLE_TAX_CLASS', DB_TABLE_PREFIX . 'tax_class');
  define('TABLE_TAX_RATES', DB_TABLE_PREFIX . 'tax_rates');
  define('TABLE_TEMPLATES', DB_TABLE_PREFIX . 'templates');
  define('TABLE_TEMPLATES_BOXES', DB_TABLE_PREFIX . 'templates_boxes');
  define('TABLE_TEMPLATES_BOXES_TO_PAGES', DB_TABLE_PREFIX . 'templates_boxes_to_pages');
  define('TABLE_WEIGHT_CLASS', DB_TABLE_PREFIX . 'weight_classes');
  define('TABLE_WEIGHT_CLASS_RULES', DB_TABLE_PREFIX . 'weight_classes_rules');
  define('TABLE_WHOS_ONLINE', DB_TABLE_PREFIX . 'whos_online');
  define('TABLE_ZONES', DB_TABLE_PREFIX . 'zones');
  define('TABLE_ZONES_TO_GEO_ZONES', DB_TABLE_PREFIX . 'zones_to_geo_zones');
?>