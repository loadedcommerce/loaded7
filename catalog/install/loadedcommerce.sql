#  $Id: loadedcommerce.sql v1.0 2012-12-04 datazen $
#
#  LoadedCommerce, Innovative eCommerce Solutions
#  http://www.loadedcommerce.com
#
#  Copyright (c) 2012 Loaded Commerce, LLC
#
#  @author     Loaded Commerce Team
#  @copyright  (c) 2012 Loaded Commerce Team
#  @license    http://loadedcommerce.com/license.html

DROP TABLE IF EXISTS lc_address_book;
CREATE TABLE IF NOT EXISTS lc_address_book (
  address_book_id int(11) NOT NULL AUTO_INCREMENT,
  customers_id int(11) NOT NULL,
  entry_gender char(1) NOT NULL,
  entry_company varchar(255) DEFAULT NULL,
  entry_firstname varchar(255) NOT NULL,
  entry_lastname varchar(255) NOT NULL,
  entry_street_address varchar(255) NOT NULL,
  entry_suburb varchar(255) DEFAULT NULL,
  entry_postcode varchar(255) NOT NULL,
  entry_city varchar(255) NOT NULL,
  entry_state varchar(255) DEFAULT NULL,
  entry_country_id int(11) NOT NULL,
  entry_zone_id int(11) NOT NULL,
  entry_telephone varchar(255) DEFAULT NULL,
  entry_fax varchar(255) DEFAULT NULL,
  PRIMARY KEY (address_book_id),
  KEY idx_address_book_customers_id (customers_id)
);

DROP TABLE IF EXISTS lc_administrators;
CREATE TABLE IF NOT EXISTS lc_administrators (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_name varchar(255) NOT NULL,
  user_password varchar(40) NOT NULL,
  first_name varchar(64) NOT NULL DEFAULT '',
  last_name varchar(64) NOT NULL DEFAULT '',
  image varchar(255) NOT NULL DEFAULT '',
  access_group_id int NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_administrators_access;
CREATE TABLE IF NOT EXISTS lc_administrators_access (
  id int(11) NOT NULL AUTO_INCREMENT,
  administrators_id int(11) NOT NULL DEFAULT '0',
  administrators_groups_id int(11) NOT NULL DEFAULT '0',
  module varchar(255) NOT NULL DEFAULT '',
  `level` int NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_administrators_groups;
CREATE TABLE IF NOT EXISTS lc_administrators_groups (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  last_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_administrators_log;
CREATE TABLE IF NOT EXISTS lc_administrators_log (
  id int(11) NOT NULL,
  module varchar(255) NOT NULL,
  module_action varchar(255) DEFAULT NULL,
  module_id int(11) DEFAULT NULL,
  field_key varchar(255) NOT NULL,
  old_value text,
  new_value text,
  `action` varchar(255) NOT NULL,
  administrators_id int(11) NOT NULL,
  datestamp datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY idx_administrators_log_id (id)
);

DROP TABLE IF EXISTS lc_banners;
CREATE TABLE IF NOT EXISTS lc_banners (
  banners_id int(11) NOT NULL AUTO_INCREMENT,
  banners_title varchar(255) NOT NULL,
  banners_url varchar(255) NOT NULL,
  banners_image varchar(255) NOT NULL,
  banners_group varchar(255) NOT NULL,
  banners_html_text text,
  banners_target int(1) NOT NULL DEFAULT '1',
  expires_impressions int(11) DEFAULT NULL,
  expires_date datetime DEFAULT NULL,
  date_scheduled datetime DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_status_change datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (banners_id)
);

DROP TABLE IF EXISTS lc_banners_history;
CREATE TABLE IF NOT EXISTS lc_banners_history (
  banners_history_id int(11) NOT NULL AUTO_INCREMENT,
  banners_id int(11) NOT NULL DEFAULT '0',
  banners_shown int(11) NOT NULL DEFAULT '0',
  banners_clicked int(11) NOT NULL DEFAULT '0',
  banners_history_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (banners_history_id)
);

DROP TABLE IF EXISTS lc_categories;
CREATE TABLE IF NOT EXISTS lc_categories (
  categories_id int(11) NOT NULL AUTO_INCREMENT,
  categories_image varchar(255) DEFAULT NULL,
  parent_id int(11) NOT NULL,
  sort_order int(11) DEFAULT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  PRIMARY KEY (categories_id),
  KEY idx_categories_parent_id (parent_id)
);

DROP TABLE IF EXISTS lc_categories_description;
CREATE TABLE IF NOT EXISTS lc_categories_description (
  categories_id int(11) NOT NULL,
  language_id int(11) NOT NULL DEFAULT '1',
  categories_name varchar(255) NOT NULL,
  PRIMARY KEY (categories_id,language_id),
  KEY idx_categories_name (categories_name)
);

DROP TABLE IF EXISTS lc_configuration;
CREATE TABLE IF NOT EXISTS lc_configuration (
  configuration_id int(11) NOT NULL AUTO_INCREMENT,
  configuration_title varchar(255) NOT NULL,
  configuration_key varchar(255) NOT NULL,
  configuration_value text NOT NULL,
  configuration_description varchar(255) NOT NULL,
  configuration_group_id int(11) NOT NULL,
  sort_order int(11) DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  use_function varchar(255) DEFAULT NULL,
  set_function varchar(255) DEFAULT NULL,
  PRIMARY KEY (configuration_id)
);

DROP TABLE IF EXISTS lc_configuration_group;
CREATE TABLE IF NOT EXISTS lc_configuration_group (
  configuration_group_id int(11) NOT NULL AUTO_INCREMENT,
  configuration_group_title varchar(255) NOT NULL,
  configuration_group_description varchar(255) NOT NULL,
  sort_order int(11) DEFAULT NULL,
  visible int(11) DEFAULT '1',
  PRIMARY KEY (configuration_group_id)
);

DROP TABLE IF EXISTS lc_counter;
CREATE TABLE IF NOT EXISTS lc_counter (
  startdate datetime DEFAULT NULL,
  counter int(11) DEFAULT NULL
);

DROP TABLE IF EXISTS lc_countries;
CREATE TABLE IF NOT EXISTS lc_countries (
  countries_id int(11) NOT NULL AUTO_INCREMENT,
  countries_name varchar(255) NOT NULL,
  countries_iso_code_2 char(2) NOT NULL,
  countries_iso_code_3 char(3) NOT NULL,
  address_format varchar(255) DEFAULT NULL,
  PRIMARY KEY (countries_id),
  KEY IDX_COUNTRIES_NAME (countries_name)
);

DROP TABLE IF EXISTS lc_credit_cards;
CREATE TABLE IF NOT EXISTS lc_credit_cards (
  id int(11) NOT NULL AUTO_INCREMENT,
  credit_card_name varchar(255) NOT NULL,
  pattern varchar(255) NOT NULL,
  credit_card_status char(1) NOT NULL,
  sort_order int(11) DEFAULT NULL,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_currencies;
CREATE TABLE IF NOT EXISTS lc_currencies (
  currencies_id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  `code` char(3) NOT NULL,
  symbol_left varchar(12) DEFAULT NULL,
  symbol_right varchar(12) DEFAULT NULL,
  decimal_places char(1) DEFAULT NULL,
  `value` float(13,8) DEFAULT NULL,
  last_updated datetime DEFAULT NULL,
  PRIMARY KEY (currencies_id)
);

DROP TABLE IF EXISTS lc_customers;
CREATE TABLE IF NOT EXISTS lc_customers (
  customers_id int(11) NOT NULL AUTO_INCREMENT,
  customers_group_id int(11) NOT NULL DEFAULT '1',
  customers_gender char(1) DEFAULT NULL,
  customers_firstname varchar(255) NOT NULL,
  customers_lastname varchar(255) NOT NULL,
  customers_dob datetime DEFAULT NULL,
  customers_email_address varchar(255) NOT NULL,
  customers_default_address_id int(11) DEFAULT NULL,
  customers_telephone varchar(255) DEFAULT NULL,
  customers_fax varchar(255) DEFAULT NULL,
  customers_password varchar(40) DEFAULT NULL,
  customers_newsletter char(1) DEFAULT NULL,
  customers_status int(11) DEFAULT NULL,
  customers_ip_address varchar(15) DEFAULT NULL,
  date_last_logon datetime DEFAULT NULL,
  number_of_logons int(11) DEFAULT NULL,
  date_account_created datetime DEFAULT NULL,
  date_account_last_modified datetime DEFAULT NULL,
  global_product_notifications int(11) DEFAULT NULL,
  PRIMARY KEY (customers_id)
);

DROP TABLE IF EXISTS lc_customers_groups;
CREATE TABLE IF NOT EXISTS lc_customers_groups (
  customers_group_id int(11) NOT NULL AUTO_INCREMENT,
  language_id int(11) NOT NULL DEFAULT '1',
  customers_group_name varchar(255) NOT NULL,
  PRIMARY KEY (customers_group_id,language_id),
  KEY idx_orders_status_name (customers_group_name)
);

DROP TABLE IF EXISTS lc_geo_zones;
CREATE TABLE IF NOT EXISTS lc_geo_zones (
  geo_zone_id int(11) NOT NULL AUTO_INCREMENT,
  geo_zone_name varchar(255) NOT NULL,
  geo_zone_description varchar(255) NOT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (geo_zone_id)
);

DROP TABLE IF EXISTS lc_languages;
CREATE TABLE IF NOT EXISTS lc_languages (
  languages_id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(5) NOT NULL,
  locale varchar(255) NOT NULL,
  `charset` varchar(32) NOT NULL,
  date_format_short varchar(32) NOT NULL,
  date_format_long varchar(32) NOT NULL,
  time_format varchar(32) NOT NULL,
  text_direction varchar(12) NOT NULL,
  currencies_id int(11) NOT NULL,
  numeric_separator_decimal varchar(12) NOT NULL,
  numeric_separator_thousands varchar(12) NOT NULL,
  parent_id int(11) DEFAULT NULL,
  sort_order int(11) DEFAULT NULL,
  PRIMARY KEY (languages_id)
);

DROP TABLE IF EXISTS lc_languages_definitions;
CREATE TABLE IF NOT EXISTS lc_languages_definitions (
  id int(11) NOT NULL AUTO_INCREMENT,
  languages_id int(11) NOT NULL,
  content_group varchar(255) NOT NULL,
  definition_key varchar(255) NOT NULL,
  definition_value text NOT NULL,
  PRIMARY KEY (id),
  KEY IDX_LANGUAGES_DEFINITIONS_LANGUAGES (languages_id),
  KEY IDX_LANGUAGES_DEFINITIONS (languages_id,content_group),
  KEY IDX_LANGUAGES_DEFINITIONS_GROUPS (content_group)
);

DROP TABLE IF EXISTS lc_manufacturers;
CREATE TABLE IF NOT EXISTS lc_manufacturers (
  manufacturers_id int(11) NOT NULL AUTO_INCREMENT,
  manufacturers_name varchar(255) NOT NULL,
  manufacturers_image varchar(255) DEFAULT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  PRIMARY KEY (manufacturers_id),
  KEY IDX_MANUFACTURERS_NAME (manufacturers_name)
);

DROP TABLE IF EXISTS lc_manufacturers_info;
CREATE TABLE IF NOT EXISTS lc_manufacturers_info (
  manufacturers_id int(11) NOT NULL,
  languages_id int(11) NOT NULL,
  manufacturers_url varchar(255) NOT NULL,
  url_clicked int(11) NOT NULL DEFAULT '0',
  date_last_click datetime DEFAULT NULL,
  PRIMARY KEY (manufacturers_id,languages_id)
);

DROP TABLE IF EXISTS lc_newsletters;
CREATE TABLE IF NOT EXISTS lc_newsletters (
  newsletters_id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  module varchar(255) NOT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_sent datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  locked int(11) DEFAULT NULL,
  PRIMARY KEY (newsletters_id)
);

DROP TABLE IF EXISTS lc_newsletters_log;
CREATE TABLE IF NOT EXISTS lc_newsletters_log (
  newsletters_id int(11) NOT NULL,
  email_address varchar(255) NOT NULL,
  date_sent datetime DEFAULT NULL,
  KEY IDX_NEWSLETTERS_LOG_NEWSLETTERS_ID (newsletters_id),
  KEY IDX_NEWSLETTERS_LOG_EMAIL_ADDRESS (email_address)
);

DROP TABLE IF EXISTS lc_orders;
CREATE TABLE IF NOT EXISTS lc_orders (
  orders_id int(11) NOT NULL AUTO_INCREMENT,
  customers_id int(11) NOT NULL,
  customers_name varchar(255) NOT NULL,
  customers_company varchar(255) DEFAULT NULL,
  customers_street_address varchar(255) NOT NULL,
  customers_suburb varchar(255) DEFAULT NULL,
  customers_city varchar(255) NOT NULL,
  customers_postcode varchar(255) NOT NULL,
  customers_state varchar(255) DEFAULT NULL,
  customers_state_code varchar(255) DEFAULT NULL,
  customers_country varchar(255) NOT NULL,
  customers_country_iso2 char(2) NOT NULL,
  customers_country_iso3 char(3) NOT NULL,
  customers_telephone varchar(255) NOT NULL,
  customers_email_address varchar(255) NOT NULL,
  customers_address_format varchar(255) NOT NULL,
  customers_ip_address varchar(15) DEFAULT NULL,
  delivery_name varchar(255) NOT NULL,
  delivery_company varchar(255) DEFAULT NULL,
  delivery_street_address varchar(255) NOT NULL,
  delivery_suburb varchar(255) DEFAULT NULL,
  delivery_city varchar(255) NOT NULL,
  delivery_postcode varchar(255) NOT NULL,
  delivery_state varchar(255) DEFAULT NULL,
  delivery_state_code varchar(255) DEFAULT NULL,
  delivery_country varchar(255) NOT NULL,
  delivery_country_iso2 char(2) NOT NULL,
  delivery_country_iso3 char(3) NOT NULL,
  delivery_address_format varchar(255) NOT NULL,
  billing_name varchar(255) NOT NULL,
  billing_company varchar(255) DEFAULT NULL,
  billing_street_address varchar(255) NOT NULL,
  billing_suburb varchar(255) DEFAULT NULL,
  billing_city varchar(255) NOT NULL,
  billing_postcode varchar(255) NOT NULL,
  billing_state varchar(255) DEFAULT NULL,
  billing_state_code varchar(255) DEFAULT NULL,
  billing_country varchar(255) NOT NULL,
  billing_country_iso2 char(2) NOT NULL,
  billing_country_iso3 char(3) NOT NULL,
  billing_address_format varchar(255) NOT NULL,
  payment_method varchar(512) NOT NULL,
  payment_module varchar(255) NOT NULL,
  last_modified datetime DEFAULT NULL,
  date_purchased datetime DEFAULT NULL,
  orders_status int(11) NOT NULL,
  orders_date_finished datetime DEFAULT NULL,
  currency char(3) DEFAULT NULL,
  currency_value decimal(14,6) DEFAULT NULL,
  PRIMARY KEY (orders_id)
);

DROP TABLE IF EXISTS lc_orders_products;
CREATE TABLE IF NOT EXISTS lc_orders_products (
  orders_products_id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  products_id int(11) NOT NULL,
  products_model varchar(255) DEFAULT NULL,
  products_name varchar(255) NOT NULL,
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_tax decimal(7,4) NOT NULL DEFAULT '0.0000',
  products_quantity int(11) NOT NULL,
  PRIMARY KEY (orders_products_id)
);

DROP TABLE IF EXISTS lc_orders_products_download;
CREATE TABLE IF NOT EXISTS lc_orders_products_download (
  orders_products_download_id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  orders_products_id int(11) NOT NULL,
  orders_products_filename varchar(255) NOT NULL,
  download_maxdays int(11) NOT NULL,
  download_count int(11) NOT NULL,
  PRIMARY KEY (orders_products_download_id)
);

DROP TABLE IF EXISTS lc_orders_products_variants;
CREATE TABLE IF NOT EXISTS lc_orders_products_variants (
  id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  orders_products_id int(11) NOT NULL,
  group_title varchar(255) NOT NULL,
  value_title text NOT NULL,
  PRIMARY KEY (id),
  KEY idx_orders_products_variants_orders_products_ids (orders_id,orders_products_id)
);

DROP TABLE IF EXISTS lc_orders_status;
CREATE TABLE IF NOT EXISTS lc_orders_status (
  orders_status_id int(11) NOT NULL,
  language_id int(11) NOT NULL DEFAULT '1',
  orders_status_name varchar(255) NOT NULL,
  PRIMARY KEY (orders_status_id,language_id),
  KEY idx_orders_status_name (orders_status_name)
);

DROP TABLE IF EXISTS lc_orders_status_history;
CREATE TABLE IF NOT EXISTS lc_orders_status_history (
  orders_status_history_id int(11) NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  orders_status_id int(11) NOT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  customer_notified int(11) DEFAULT NULL,
  comments text,
  PRIMARY KEY (orders_status_history_id)
);

DROP TABLE IF EXISTS lc_orders_total;
CREATE TABLE IF NOT EXISTS lc_orders_total (
  orders_total_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  orders_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  class varchar(255) NOT NULL,
  sort_order int(11) NOT NULL,
  PRIMARY KEY (orders_total_id),
  KEY idx_orders_total_orders_id (orders_id)
);

DROP TABLE IF EXISTS lc_orders_transactions_history;
CREATE TABLE IF NOT EXISTS lc_orders_transactions_history (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  orders_id int(10) unsigned NOT NULL,
  transaction_code int(11) NOT NULL,
  transaction_return_value text NOT NULL,
  transaction_return_status int(11) NOT NULL,
  date_added datetime DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_orders_transactions_history_orders_id (orders_id)
);

DROP TABLE IF EXISTS lc_orders_transactions_status;
CREATE TABLE IF NOT EXISTS lc_orders_transactions_status (
  id int(10) unsigned NOT NULL,
  language_id int(10) unsigned NOT NULL,
  status_name varchar(255) NOT NULL,
  PRIMARY KEY (id,language_id),
  KEY idx_orders_transactions_status_name (status_name)
);

DROP TABLE IF EXISTS lc_products;
CREATE TABLE IF NOT EXISTS lc_products (
  products_id int(11) NOT NULL AUTO_INCREMENT,
  parent_id int(11) NOT NULL DEFAULT '0',
  products_quantity int(11) NOT NULL DEFAULT '0',
  products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  products_model varchar(255) NOT NULL,
  products_date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  products_last_modified datetime DEFAULT NULL,
  products_weight decimal(5,2) NOT NULL DEFAULT '0.00',
  products_weight_class int(11) NOT NULL DEFAULT '0',
  products_status tinyint(1) NOT NULL DEFAULT '0',
  products_tax_class_id int(11) NOT NULL DEFAULT '0',
  manufacturers_id int(11) DEFAULT NULL,
  products_ordered int(11) NOT NULL DEFAULT '0',
  has_children int(11) DEFAULT NULL,
  PRIMARY KEY (products_id),
  KEY idx_products_date_added (products_date_added)
);

DROP TABLE IF EXISTS lc_products_description;
CREATE TABLE IF NOT EXISTS lc_products_description (
  products_id int(11) NOT NULL AUTO_INCREMENT,
  language_id int(11) NOT NULL DEFAULT '1',
  products_name varchar(255) NOT NULL,
  products_description text,
  products_keyword varchar(255) DEFAULT NULL,
  products_tags varchar(255) DEFAULT NULL,
  products_meta_title varchar(255) DEFAULT NULL,
  products_meta_keywords varchar(255) DEFAULT NULL,
  products_meta_description varchar(255) DEFAULT NULL,
  products_url varchar(255) DEFAULT NULL,
  products_viewed int(11) DEFAULT NULL,
  PRIMARY KEY (products_id,language_id),
  KEY products_name (products_name),
  KEY products_description_keyword (products_keyword)
);

DROP TABLE IF EXISTS lc_products_images;
CREATE TABLE IF NOT EXISTS lc_products_images (
  id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL,
  image varchar(255) NOT NULL,
  default_flag tinyint(1) NOT NULL,
  sort_order int(11) NOT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id),
  KEY products_images_products_id (products_id)
);

DROP TABLE IF EXISTS lc_products_images_groups;
CREATE TABLE IF NOT EXISTS lc_products_images_groups (
  id int(11) NOT NULL,
  language_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  size_width int(11) DEFAULT NULL,
  size_height int(11) DEFAULT NULL,
  force_size tinyint(1) DEFAULT NULL,
  PRIMARY KEY (id,language_id)
);

DROP TABLE IF EXISTS lc_products_notifications;
CREATE TABLE IF NOT EXISTS lc_products_notifications (
  products_id int(11) NOT NULL,
  customers_id int(11) NOT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (products_id,customers_id)
);

DROP TABLE IF EXISTS lc_products_pricing;
CREATE TABLE IF NOT EXISTS lc_products_pricing (
  products_id int(11) NOT NULL,
  group_id int(11) NOT NULL,
  tax_class_id int(11) NOT NULL,
  qty_break int(11) NOT NULL,
  price_break decimal(13,4) NOT NULL DEFAULT '0.0000',
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY group_id (group_id),
  KEY products_id (products_id)
);

DROP TABLE IF EXISTS lc_products_to_categories;
CREATE TABLE IF NOT EXISTS lc_products_to_categories (
  products_id int(11) NOT NULL,
  categories_id int(11) NOT NULL,
  PRIMARY KEY (products_id,categories_id)
);

DROP TABLE IF EXISTS lc_products_variants;
CREATE TABLE IF NOT EXISTS lc_products_variants (
  products_id int(10) unsigned NOT NULL,
  products_variants_values_id int(10) unsigned NOT NULL,
  default_combo tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (products_id,products_variants_values_id)
);

DROP TABLE IF EXISTS lc_products_variants_groups;
CREATE TABLE IF NOT EXISTS lc_products_variants_groups (
  id int(11) NOT NULL AUTO_INCREMENT,
  languages_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  sort_order int(11) NOT NULL,
  module varchar(255) NOT NULL,
  PRIMARY KEY (id,languages_id)
);

DROP TABLE IF EXISTS lc_products_variants_values;
CREATE TABLE IF NOT EXISTS lc_products_variants_values (
  id int(11) NOT NULL AUTO_INCREMENT,
  languages_id int(11) NOT NULL,
  products_variants_groups_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  sort_order int(11) NOT NULL,
  PRIMARY KEY (id,languages_id),
  KEY idx_products_variants_values_groups_id (products_variants_groups_id)
);

DROP TABLE IF EXISTS lc_product_attributes;
CREATE TABLE IF NOT EXISTS lc_product_attributes (
  id int(10) unsigned NOT NULL,
  products_id int(10) unsigned NOT NULL,
  languages_id int(10) unsigned NOT NULL,
  `value` text NOT NULL,
  KEY idx_pa_id_products_id (id,products_id),
  KEY idx_pa_languages_id (languages_id)
);

DROP TABLE IF EXISTS lc_reviews;
CREATE TABLE IF NOT EXISTS lc_reviews (
  reviews_id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL DEFAULT '0',
  customers_id int(11) DEFAULT NULL,
  customers_name varchar(255) NOT NULL,
  reviews_rating int(11) DEFAULT NULL,
  languages_id int(11) NOT NULL,
  reviews_text text NOT NULL,
  date_added datetime DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  reviews_read int(11) NOT NULL DEFAULT '0',
  reviews_status tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (reviews_id)
);

DROP TABLE IF EXISTS lc_sessions;
CREATE TABLE IF NOT EXISTS lc_sessions (
  id varchar(32) NOT NULL,
  expiry int(10) unsigned NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_shipping_availability;
CREATE TABLE IF NOT EXISTS lc_shipping_availability (
  id int(10) unsigned NOT NULL,
  languages_id int(10) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  css_key varchar(255) DEFAULT NULL,
  PRIMARY KEY (id,languages_id)
);

DROP TABLE IF EXISTS lc_shopping_carts;
CREATE TABLE IF NOT EXISTS lc_shopping_carts (
  customers_id int(10) unsigned NOT NULL,
  item_id smallint(5) unsigned NOT NULL,
  products_id int(10) unsigned NOT NULL,
  quantity smallint(5) unsigned NOT NULL,
  date_added datetime DEFAULT NULL,
  KEY idx_sc_customers_id (customers_id),
  KEY idx_sc_customers_id_products_id (customers_id,products_id)
);

DROP TABLE IF EXISTS lc_shopping_carts_custom_variants_values;
CREATE TABLE IF NOT EXISTS lc_shopping_carts_custom_variants_values (
  shopping_carts_item_id smallint(5) unsigned NOT NULL,
  customers_id int(10) unsigned NOT NULL,
  products_id int(10) unsigned NOT NULL,
  products_variants_values_id int(10) unsigned NOT NULL,
  products_variants_values_text text NOT NULL,
  KEY idx_sccvv_customers_id_products_id (customers_id,products_id)
);

DROP TABLE IF EXISTS lc_specials;
CREATE TABLE IF NOT EXISTS lc_specials (
  specials_id int(11) NOT NULL AUTO_INCREMENT,
  products_id int(11) NOT NULL,
  specials_new_products_price decimal(15,4) NOT NULL DEFAULT '0.0000',
  specials_date_added datetime DEFAULT NULL,
  specials_last_modified datetime DEFAULT NULL,
  start_date datetime DEFAULT NULL,
  expires_date datetime DEFAULT NULL,
  date_status_change datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (specials_id)
);

DROP TABLE IF EXISTS lc_tax_class;
CREATE TABLE IF NOT EXISTS lc_tax_class (
  tax_class_id int(11) NOT NULL AUTO_INCREMENT,
  tax_class_title varchar(255) NOT NULL,
  tax_class_description varchar(255) NOT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (tax_class_id)
);

DROP TABLE IF EXISTS lc_tax_rates;
CREATE TABLE IF NOT EXISTS lc_tax_rates (
  tax_rates_id int(11) NOT NULL AUTO_INCREMENT,
  tax_zone_id int(11) NOT NULL,
  tax_class_id int(11) NOT NULL,
  tax_priority int(11) DEFAULT '1',
  tax_rate decimal(7,4) NOT NULL DEFAULT '0.0000',
  tax_description varchar(255) NOT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (tax_rates_id)
);

DROP TABLE IF EXISTS lc_templates;
CREATE TABLE IF NOT EXISTS lc_templates (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  author_name varchar(255) NOT NULL,
  author_www varchar(255) DEFAULT NULL,
  markup_version varchar(255) DEFAULT NULL,
  css_based tinyint(4) DEFAULT NULL,
  `medium` varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_templates_boxes;
CREATE TABLE IF NOT EXISTS lc_templates_boxes (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  author_name varchar(255) NOT NULL,
  author_www varchar(255) DEFAULT NULL,
  modules_group varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_templates_boxes_to_pages;
CREATE TABLE IF NOT EXISTS lc_templates_boxes_to_pages (
  id int(11) NOT NULL AUTO_INCREMENT,
  templates_boxes_id int(11) NOT NULL,
  templates_id int(11) NOT NULL,
  content_page varchar(255) NOT NULL,
  boxes_group varchar(32) NOT NULL,
  sort_order int(11) DEFAULT NULL,
  page_specific int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY templates_boxes_id (templates_boxes_id,templates_id,content_page,boxes_group)
);

DROP TABLE IF EXISTS lc_updates_log;
CREATE TABLE IF NOT EXISTS lc_updates_log (
  id int(11) NOT NULL AUTO_INCREMENT,
  action varchar(32) NOT NULL DEFAULT '',
  result varchar(128) NOT NULL DEFAULT '',
  `user` varchar(64) NOT NULL DEFAULT '',
  dateCreated datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS lc_weight_classes;
CREATE TABLE IF NOT EXISTS lc_weight_classes (
  weight_class_id int(11) NOT NULL,
  weight_class_key varchar(4) NOT NULL,
  language_id int(11) NOT NULL,
  weight_class_title varchar(255) NOT NULL,
  PRIMARY KEY (weight_class_id,language_id)
);

DROP TABLE IF EXISTS lc_weight_classes_rules;
CREATE TABLE IF NOT EXISTS lc_weight_classes_rules (
  weight_class_from_id int(11) NOT NULL,
  weight_class_to_id int(11) NOT NULL,
  weight_class_rule decimal(15,4) NOT NULL DEFAULT '0.0000'
);

DROP TABLE IF EXISTS lc_whos_online;
CREATE TABLE IF NOT EXISTS lc_whos_online (
  customer_id int(11) DEFAULT NULL,
  full_name varchar(255) NOT NULL,
  session_id varchar(128) NOT NULL,
  ip_address varchar(15) NOT NULL,
  time_entry varchar(14) NOT NULL,
  time_last_click varchar(14) NOT NULL,
  last_page_url text NOT NULL
);

DROP TABLE IF EXISTS lc_zones;
CREATE TABLE IF NOT EXISTS lc_zones (
  zone_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  zone_country_id int(10) unsigned NOT NULL,
  zone_code varchar(255) NOT NULL,
  zone_name varchar(255) NOT NULL,
  PRIMARY KEY (zone_id),
  KEY idx_zones_country_id (zone_country_id),
  KEY idx_zones_code (zone_code),
  KEY idx_zones_name (zone_name)
);


DROP TABLE IF EXISTS lc_zones_to_geo_zones;
CREATE TABLE IF NOT EXISTS lc_zones_to_geo_zones (
  association_id int(11) NOT NULL AUTO_INCREMENT,
  zone_country_id int(11) NOT NULL,
  zone_id int(11) DEFAULT NULL,
  geo_zone_id int(11) DEFAULT NULL,
  last_modified datetime DEFAULT NULL,
  date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (association_id)
);

INSERT INTO lc_administrators_groups (id, `name`, date_added, last_modified) VALUES(1, 'Top Administrator', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

INSERT INTO lc_administrators_access (id, administrators_id, administrators_groups_id, module, level) VALUES(1, 1, 1, '*', 99);

INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(1, 'Store Name', 'STORE_NAME', 'Loaded Comerce Demo Store', 'The name of my store', 1, 1, '2012-09-19 11:38:17', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(2, 'Store Owner', 'STORE_OWNER', 'Store Owner', 'The name of my store owner', 1, 2, '2011-03-04 11:41:43', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(3, 'E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'noreply@mystore.com', 'The e-mail address of my store owner', 1, 3, '2010-12-02 19:14:31', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(4, 'E-Mail From', 'EMAIL_FROM', '"Store Owner" <noreply@mystore.com>', 'The e-mail address used in (sent) e-mails', 1, 4, '2012-03-30 19:26:23', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(5, 'Country', 'STORE_COUNTRY', '223', 'The country my store is located in <br><br><b>Note: Please remember to update the store zone.</b>', 1, 6, NULL, '2009-11-26 15:58:32', 'lC_Address::getCountryName', 'lc_cfg_set_countries_pulldown_menu');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(6, 'Zone', 'STORE_ZONE', '4032', 'The zone my store is located in', 1, 7, '2012-03-31 15:47:38', '2009-11-26 15:58:32', 'lC_Address::getZoneName', 'lc_cfg_set_zones_pulldown_menu');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(7, 'Send Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', 1, 11, '2012-09-19 14:19:17', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(8, 'Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', '1', 'Allow guests to tell a friend about a product', 1, 15, '2012-09-16 00:37:33', '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(9, 'Store Address and Phone', 'STORE_NAME_ADDRESS', '123 Main St.\r\nAtlanta, GA 30324\r\n(404) 123-4567', 'This is the Store Name, Address and Phone used on printable documents and displayed online', 1, 18, '2012-03-30 19:26:04', '2009-11-26 15:58:32', NULL, 'lc_cfg_set_textarea_field');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(10, 'Tax Decimal Places', 'TAX_DECIMAL_PLACES', '2', 'Pad the tax value this amount of decimal places', 1, 20, '2009-12-09 18:31:29', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(11, 'Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', '-1', 'Display prices with tax included (true) or add the tax at the end (false)', 1, 21, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(12, 'Review Text', 'REVIEW_TEXT_MIN_LENGTH', '50', 'Minimum length of review text', 2, 14, '2012-05-27 19:31:17', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(13, 'Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '5', 'Maximum address book entries a customer is allowed to have', 3, 1, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(14, 'Search Results', 'MAX_DISPLAY_SEARCH_RESULTS', '15', 'Amount of products to list', 3, 2, '2010-01-16 23:32:49', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(15, 'Page Links', 'MAX_DISPLAY_PAGE_LINKS', '5', 'Number of ''number'' links use for page-sets', 3, 3, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(16, 'Categories To List Per Row', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '3', 'How many categories to list per row', 3, 13, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(17, 'New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '3', 'Maximum number of new products to display in new products page', 3, 14, '2009-12-02 12:07:33', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(18, 'Order History', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', 3, 18, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(19, 'Heading Image Width', 'HEADING_IMAGE_WIDTH', '50', 'The pixel width of heading images', 4, 3, '2009-12-20 01:24:07', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(20, 'Heading Image Height', 'HEADING_IMAGE_HEIGHT', '', 'The pixel height of heading images', 4, 4, '2009-12-20 01:24:16', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(21, 'Image Required', 'IMAGE_REQUIRED', '1', 'Enable to display broken images. Good for development.', 4, 8, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(22, 'Gender', 'ACCOUNT_GENDER', '1', 'Ask for or require the customers gender.', 5, 10, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, 0, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(23, 'First Name', 'ACCOUNT_FIRST_NAME', '2', 'Minimum requirement for the customers first name.', 5, 11, '2012-09-27 10:02:02', '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''10''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(24, 'Last Name', 'ACCOUNT_LAST_NAME', '2', 'Minimum requirement for the customers last name.', 5, 12, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''10''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(25, 'Date Of Birth', 'ACCOUNT_DATE_OF_BIRTH', '1', 'Ask for the customers date of birth.', 5, 13, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(26, 'E-Mail Address', 'ACCOUNT_EMAIL_ADDRESS', '6', 'Minimum requirement for the customers e-mail address.', 5, 14, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''10''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(27, 'Password', 'ACCOUNT_PASSWORD', '5', 'Minimum requirement for the customers password.', 5, 15, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''10''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(28, 'Newsletter', 'ACCOUNT_NEWSLETTER', '1', 'Ask for a newsletter subscription.', 5, 16, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(29, 'Company Name', 'ACCOUNT_COMPANY', '0', 'Ask for or require the customers company name.', 5, 17, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1'', 0, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(30, 'Street Address', 'ACCOUNT_STREET_ADDRESS', '5', 'Minimum requirement for the customers street address.', 5, 18, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''10''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(31, 'Suburb', 'ACCOUNT_SUBURB', '0', 'Ask for or require the customers suburb.', 5, 19, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1'', 0, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(32, 'Post Code', 'ACCOUNT_POST_CODE', '0', 'Minimum requirement for the customers post code.', 5, 20, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(-1, 0, ''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''10''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(33, 'City', 'ACCOUNT_CITY', '4', 'Minimum requirement for the customers city.', 5, 21, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''10''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(34, 'State', 'ACCOUNT_STATE', '2', 'Ask for or require the customers state.', 5, 22, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1'', 0, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(35, 'Country', 'ACCOUNT_COUNTRY', '1', 'Ask for the customers country.', 5, 23, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(36, 'Telephone Number', 'ACCOUNT_TELEPHONE', '3', 'Ask for or require the customers telephone number.', 5, 24, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1'', 0, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(37, 'Fax Number', 'ACCOUNT_FAX', '0', 'Ask for or require the customers fax number.', 5, 25, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(''10'', ''9'', ''8'', ''7'', ''6'', ''5'', ''4'', ''3'', ''2'', ''1'', 0, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(38, 'Default Currency', 'DEFAULT_CURRENCY', 'USD', 'Default Currency', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(39, 'Default Language', 'DEFAULT_LANGUAGE', 'en_US', 'Default Language', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(40, 'Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(41, 'Default Image Group', 'DEFAULT_IMAGE_GROUP_ID', '2', 'Default image group.', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(42, 'Default Template', 'DEFAULT_TEMPLATE', 'default', 'Default Template', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(43, 'Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', 7, 1, '2011-02-09 19:03:59', '2009-11-26 15:58:32', 'lC_Address::getCountryName', 'lc_cfg_set_countries_pulldown_menu');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(44, 'Postal Code', 'SHIPPING_ORIGIN_ZIP', 'NONE', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', 7, 2, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(45, 'Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', 7, 3, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(46, 'Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '3', 'What is the weight of typical packaging of small to medium packages?', 7, 4, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(47, 'Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '10', 'For 10% enter 10', 7, 5, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(48, 'Default Shipping Unit', 'SHIPPING_WEIGHT_UNIT', '4', 'Select the unit of weight to be used for shipping.', 7, 6, NULL, '2009-11-26 15:58:32', 'lC_Weight::getTitle', 'lc_cfg_set_weight_classes_pulldown_menu');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(49, 'Display Product Image', 'PRODUCT_LIST_IMAGE', '1', 'Do you want to display the Product Image?', 8, 1, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(50, 'Display Product Manufaturer Name', 'PRODUCT_LIST_MANUFACTURER', '0', 'Do you want to display the Product Manufacturer Name?', 8, 2, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(51, 'Display Product Model', 'PRODUCT_LIST_MODEL', '0', 'Do you want to display the Product Model?', 8, 3, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(52, 'Display Product Name', 'PRODUCT_LIST_NAME', '2', 'Do you want to display the Product Name?', 8, 4, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(53, 'Display Product Price', 'PRODUCT_LIST_PRICE', '3', 'Do you want to display the Product Price', 8, 5, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(54, 'Display Product Quantity', 'PRODUCT_LIST_QUANTITY', '0', 'Do you want to display the Product Quantity?', 8, 6, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(55, 'Display Product Weight', 'PRODUCT_LIST_WEIGHT', '0', 'Do you want to display the Product Weight?', 8, 7, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(56, 'Display Buy Now column', 'PRODUCT_LIST_BUY_NOW', '4', 'Do you want to display the Buy Now column?', 8, 8, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(57, 'Display Category/Manufacturer Filter (0=disable; 1=enable)', 'PRODUCT_LIST_FILTER', '1', 'Do you want to display the Category/Manufacturer Filter?', 8, 9, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(58, 'Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '3', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 8, 10, '2011-03-08 17:14:58', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(59, 'Check stock level', 'STOCK_CHECK', '1', 'Check to see if sufficent stock is available', 9, 1, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(60, 'Subtract stock', 'STOCK_LIMITED', '1', 'Subtract product in stock by product orders', 9, 2, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(61, 'Allow Checkout', 'STOCK_ALLOW_CHECKOUT', '1', 'Allow customer to checkout even if there is insufficient stock', 9, 3, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(62, 'Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***', 'Display something on screen so customer can see which product has insufficient stock', 9, 4, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(63, 'Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', 9, 5, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(64, 'E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', 12, 1, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''sendmail'', ''smtp''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(65, 'E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', 12, 2, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''LF'', ''CRLF''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(66, 'Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', '-1', 'Send e-mails in HTML format', 12, 3, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(67, 'Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', '-1', 'Verify e-mail address through a DNS server', 12, 4, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(68, 'Send E-Mails', 'SEND_EMAILS', '1', 'Send out e-mails', 12, 5, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(69, 'Enable download', 'DOWNLOAD_ENABLED', '-1', 'Enable the products download functions.', 13, 1, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(70, 'Download by redirect', 'DOWNLOAD_BY_REDIRECT', '-1', 'Use browser redirection for download. Disable on non-Unix systems.', 13, 2, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(71, 'Expiry delay (days)', 'DOWNLOAD_MAX_DAYS', '7', 'Set number of days before the download link expires. 0 means no limit.', 13, 3, NULL, '2009-11-26 15:58:32', NULL, '');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(72, 'Maximum number of downloads', 'DOWNLOAD_MAX_COUNT', '5', 'Set the maximum number of downloads. 0 means no download authorized.', 13, 4, NULL, '2009-11-26 15:58:32', NULL, '');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(73, 'Confirm Terms and Conditions During Checkout Procedure', 'DISPLAY_CONDITIONS_ON_CHECKOUT', '-1', 'Show the Terms and Conditions during the checkout procedure which the customer must agree to.', 16, 1, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(74, 'Confirm Privacy Notice During Account Creation Procedure', 'DISPLAY_PRIVACY_CONDITIONS', '-1', 'Show the Privacy Notice during the account creation procedure which the customer must agree to.', 16, 2, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(75, 'Verify With Regular Expressions', 'CFG_CREDIT_CARDS_VERIFY_WITH_REGEXP', '1', 'Verify credit card numbers with server-side regular expression patterns.', 17, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(76, 'Verify With Javascript', 'CFG_CREDIT_CARDS_VERIFY_WITH_JS', '1', 'Verify credit card numbers with javascript based regular expression patterns.', 17, 1, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(77, 'GZIP', 'CFG_APP_GZIP', '/bin/gzip', 'The program location to gzip.', 18, 1, '2011-01-18 14:49:07', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(78, 'GUNZIP', 'CFG_APP_GUNZIP', '/bin/gunzip', 'The program location to gunzip.', 18, 2, '2011-01-18 14:49:39', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(79, 'ZIP', 'CFG_APP_ZIP', '/usr/bin/zip', 'The program location to zip.', 18, 3, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(80, 'UNZIP', 'CFG_APP_UNZIP', '/usr/bin/unzip', 'The program location to unzip.', 18, 4, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(81, 'cURL', 'CFG_APP_CURL', '/usr/bin/curl', 'The program location to cURL.', 18, 5, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(82, 'ImageMagick "convert"', 'CFG_APP_IMAGEMAGICK_CONVERT', '/usr/bin/convert', 'The program location to ImageMagicks "convert" to use when manipulating images.', 18, 6, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(83, 'Minimum List Size', 'BOX_BEST_SELLERS_MIN_LIST', '3', 'Minimum amount of products that must be shown in the listing', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(84, 'Maximum List Size', 'BOX_BEST_SELLERS_MAX_LIST', '10', 'Maximum amount of products to show in the listing', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(85, 'Cache Contents', 'BOX_BEST_SELLERS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(86, 'Manufacturers List Size', 'BOX_MANUFACTURERS_LIST_SIZE', '1', 'The size of the manufacturers pull down menu listing.', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(87, 'Random Review Selection', 'BOX_REVIEWS_RANDOM_SELECT', '10', 'Select a random review from this amount of the newest reviews available', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(88, 'Cache Contents', 'BOX_REVIEWS_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(89, 'Random Product Specials Selection', 'BOX_SPECIALS_RANDOM_SELECT', '10', 'Select a random product on special from this amount of the newest products on specials available', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(90, 'Cache Contents', 'BOX_SPECIALS_CACHE', '1', 'Number of minutes to keep the contents cached (0 = no cache)', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(91, 'GZIP Compression Level', 'SERVICE_OUTPUT_COMPRESSION_GZIP_LEVEL', '5', 'Set the GZIP compression level to this value (0=min, 9=max).', 6, 0, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''0'', ''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(92, 'Session Expiration Time', 'SERVICE_SESSION_EXPIRATION_TIME', '160', 'The time (in minutes) to keep sessions active for.', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(93, 'Force Cookie Usage', 'SERVICE_SESSION_FORCE_COOKIE_USAGE', '-1', 'Only start a session when cookies are enabled.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(94, 'Block Search Engine Spiders', 'SERVICE_SESSION_BLOCK_SPIDERS', '-1', 'Block search engine spider robots from starting a session.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(95, 'Check SSL Session ID', 'SERVICE_SESSION_CHECK_SSL_SESSION_ID', '-1', 'Check the SSL_SESSION_ID on every secure HTTPS page request.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(96, 'Check User Agent', 'SERVICE_SESSION_CHECK_USER_AGENT', '-1', 'Check the browser user agent on every page request.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(97, 'Check IP Address', 'SERVICE_SESSION_CHECK_IP_ADDRESS', '-1', 'Check the IP address on every page request.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(98, 'Regenerate Session ID', 'SERVICE_SESSION_REGENERATE_ID', '-1', 'Regenerate the session ID when a customer logs on or creates an account.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(99, 'Use Default Language Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', '-1', 'Automatically use the currency set with the language (eg, German->Euro).', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(100, 'Calculate Number Of Products In Each Category', 'SERVICES_CATEGORY_PATH_CALCULATE_PRODUCT_COUNT', '1', 'Recursively calculate how many products are in each category.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(101, 'Detect Search Engine Spider Robots', 'SERVICE_WHOS_ONLINE_SPIDER_DETECTION', '1', 'Detect search engine spider robots (GoogleBot, Yahoo, etc).', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(102, 'Special Products', 'MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 'Maximum number of products on special to display', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(103, 'New Reviews', 'MAX_DISPLAY_NEW_REVIEWS', '6', 'Maximum number of new reviews to display', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(104, 'Review Level', 'SERVICE_REVIEW_ENABLE_REVIEWS', '0', 'Customer level required to write a review.', 6, 0, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''0'', ''1'', ''2''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(105, 'Moderate Reviews', 'SERVICE_REVIEW_ENABLE_MODERATION', '-1', 'Should reviews be approved by store admin.', 6, 0, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''-1'', ''0'', ''1''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(106, 'Display latest products', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCTS', '1', 'Display recently visited products.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(107, 'Display product images', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_IMAGES', '1', 'Display the product image.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(108, 'Display product prices', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_PRICES', '-1', 'Display the products price.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(109, 'Maximum products to show', 'SERVICE_RECENTLY_VISITED_MAX_PRODUCTS', '6', 'Maximum number of recently visited products to show', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(110, 'Display latest categories', 'SERVICE_RECENTLY_VISITED_SHOW_CATEGORIES', '1', 'Display recently visited categories.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(111, 'Maximum categories to show', 'SERVICE_RECENTLY_VISITED_MAX_CATEGORIES', '3', 'Mazimum number of recently visited categories to show', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(112, 'Display latest searches', 'SERVICE_RECENTLY_VISITED_SHOW_SEARCHES', '1', 'Show recent searches.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(113, 'Maximum searches to show', 'SERVICE_RECENTLY_VISITED_MAX_SEARCHES', '3', 'Maximum number of recent searches to display', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(114, 'Service Modules', 'MODULE_SERVICES_INSTALLED', 'output_compression;session;language;breadcrumb;currencies;core;whos_online;simple_counter;category_path;recently_visited;specials;reviews;banner', 'Installed services modules', 6, 0, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(115, 'Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', 'Do you want to display the order sub-total cost?', 6, 1, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''true'', ''false''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(116, 'Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', 'Sort order of display.', 6, 2, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(117, 'Display Shipping', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'true', 'Do you want to display the order shipping cost?', 6, 1, NULL, '2009-11-26 15:58:32', NULL, 'lc_cfg_set_boolean_value(array(''true'', ''false''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(118, 'Sort Order', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '2', 'Sort order of display.', 6, 2, NULL, '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(119, 'Max Pages Display on mainpage', 'MAX_DISPLAY_CMS_ARTICLES', '5', 'Maximum number of Pages listings to display on mainpage', 3, 10, '2009-12-20 15:15:34', '2009-12-17 03:06:52', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(120, 'Max Page Links to Display', 'BOX_CMS_LIST_SIZE', '10', 'Maximum number of Page Links to display in the infobox', 3, 11, '2009-12-17 03:16:14', '2009-12-17 03:16:14', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(121, 'Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', 'Do you want to display the total order value?', 6, 1, NULL, '2009-12-17 19:00:33', NULL, 'lc_cfg_set_boolean_value(array(''true'', ''false''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(122, 'Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '4', 'Sort order of display.', 6, 2, NULL, '2009-12-17 19:00:33', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(123, 'Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'Do you want to display the order tax value?', 6, 1, NULL, '2009-12-17 19:01:01', NULL, 'lc_cfg_set_boolean_value(array(''true'', ''false''))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(124, 'Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '3', 'Sort order of display.', 6, 2, NULL, '2009-12-17 19:01:01', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(125, 'Tag Cloud Maiximum Listings', 'TAG_CLOUD_MAX_LIST', '6', 'The number of links to display in the tag cloud box.', 3, 99, '2012-09-21 18:33:12', '2009-12-20 03:41:27', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(126, 'Tag Cloud Font Size Setting', 'TAG_CLOUD_FONT_SIZE', '10,24', 'The low and high number of the font size to use for the link display in the tag cloud box, sperated by a comma.', 3, 98, NULL, '2009-12-20 03:41:27', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(127, 'Pricing Decimal Places', 'DECIMAL_PLACES', '2', 'Pad the pricing values this amount of decimal places', 1, 20, '2009-12-09 18:31:29', '2009-11-26 15:58:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(128, 'Default Customers Group ID', 'DEFAULT_CUSTOMERS_GROUP_ID', '1', 'Default Customers Group ID', 6, NULL, NULL, '2011-01-06 00:00:00', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(129, 'Maximum List Size', 'BOX_ORDER_HISTORY_MAX_LIST', '5', 'Maximum amount of products to show in the listing', 6, 0, NULL, '2011-03-05 16:34:33', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(130, 'Display Duplicate Banners', 'SERVICE_BANNER_SHOW_DUPLICATE', '-1', 'Show duplicate banners in the same banner group on the same page?', 6, 0, NULL, '2011-03-08 17:53:30', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(131, 'Show Product Count', 'BOX_CATEGORIES_FLYOUTSHOW_PRODUCT_COUNT', '1', 'Show the amount of products each category has', 6, 0, NULL, '2011-03-26 22:21:18', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(132, 'Show Product Count', 'BOX_QUICK_SHOP_SHOW_PRODUCT_COUNT', '-1', 'Show the amount of products each category has', 6, 0, NULL, '2011-03-27 14:00:55', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(133, 'Show Product Count', 'BOX_CATEGORIES_SHOW_PRODUCT_COUNT', '-1', 'Show the amount of products each category has', 6, 0, NULL, '2012-10-01 12:55:04', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(134, 'Default GeoIP Module', 'MODULE_DEFAULT_GEOIP', 'maxmind_geolite_country', 'Default GeoIP module.', 6, NULL, NULL, '2012-10-09 17:57:50', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(135, 'Page Execution Time Log File', 'SERVICE_DEBUG_EXECUTION_TIME_LOG', '', 'Location of the page execution time log file (eg, /www/log/page_parse.log).', 6, 0, NULL, '2012-10-09 18:17:08', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(136, 'Show The Page Execution Time', 'SERVICE_DEBUG_EXECUTION_DISPLAY', '1', 'Show the page execution time.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(137, 'Check Sessions Auto Start', 'SERVICE_DEBUG_CHECK_SESSION_AUTOSTART', '1', 'Show a warning if PHP is configured to automatically start sessions.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(138, 'Check Download Directory', 'SERVICE_DEBUG_CHECK_DOWNLOAD_DIRECTORY', '1', 'Show a warning if the digital product download directory does not exist.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(139, 'Cache Contents', 'MODULE_CONTENT_NEW_PRODUCTS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', 6, 0, NULL, '2012-10-25 18:08:07', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(140, 'Minimum Entries To Display', 'MODULE_CONTENT_ALSO_PURCHASED_MIN_DISPLAY', '4', 'Minimum number of also purchased products to display', 6, 0, NULL, '2012-10-26 12:42:13', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(141, 'Maximum Entries To Display', 'MODULE_CONTENT_ALSO_PURCHASED_MAX_DISPLAY', '8', 'Maximum number of also purchased products to display', 6, 0, NULL, '2012-10-26 12:42:13', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(142, 'Cache Contents', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_CACHE', '60', 'Number of minutes to keep the contents cached (0 = no cache)', 6, 0, NULL, '2012-10-26 12:42:13', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(143, 'Maximum Entries To Display', 'MODULE_CONTENT_UPCOMING_PRODUCTS_MAX_DISPLAY', '10', 'Maximum number of upcoming products to display', 6, 0, NULL, '2012-10-26 12:45:25', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(144, 'Cache Contents', 'MODULE_CONTENT_UPCOMING_PRODUCTS_CACHE', '1440', 'Number of minutes to keep the contents cached (0 = no cache)', 6, 0, NULL, '2012-10-26 12:45:25', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(145, 'Display Category Images', 'SERVICE_RECENTLY_VISITED_SHOW_CATEGORY_IMAGES', '1', 'Display the category image.', 6, 0, NULL, '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(146, 'Show Product Count', 'BOX_QUICK_SHOP_SHOW_PRODUCT_COUNT', '1', 'Show the amount of products each category has', 6, 0, NULL, '2012-10-28 09:01:31', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(147, 'Manufacturers List Size', 'BOX_MANUFACTURERS_LIST_SIZE', '1', 'The size of the manufacturers pull down menu listing.', 6, 0, NULL, '2012-11-01 17:55:43', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(148, 'Last Database Restore', 'DB_LAST_RESTORE', '', 'Last database restore file', 6, 0, NULL, '2012-11-13 15:07:32', NULL, NULL);
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(149, 'Down for Maintenance', 'STORE_DOWN_FOR_MAINTENANCE', '-1', 'Set the store to maintenance mode.', 1, 22, '2012-09-16 00:37:33', '2009-11-26 15:58:32', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(150, 'Log Database Queries', 'SERVICE_DEBUG_LOG_DB_QUERIES', '-1', 'Log all database queries in the page execution time log file.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(151, 'Show Database Queries', 'SERVICE_DEBUG_OUTPUT_DB_QUERIES', '-1', 'Show all database queries made.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(152, 'Check Language Locale', 'SERVICE_DEBUG_CHECK_LOCALE', '1', 'Show a warning message if the set language locale does not exist on the server.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(153, 'Check Installation Module', 'SERVICE_DEBUG_CHECK_INSTALLATION_MODULE', '1', 'Show a warning message if the installation module exists.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(154, 'Check Configuration File', 'SERVICE_DEBUG_CHECK_CONFIGURATION', '1', 'Show a warning if the configuration file is writeable.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(155, 'Check Sessions Directory', 'SERVICE_DEBUG_CHECK_SESSION_DIRECTORY', '1', 'Show a warning if the file-based session directory does not exist.', 6, 0, NULL, '2012-10-09 18:17:08', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))');
INSERT INTO lc_configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES(156, 'Maximum Entries To Display', 'MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY', '6', 'Maximum number of new products to display', 6, 0, NULL, '2012-10-25 18:08:07', NULL, NULL);


INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(1, 'My Store', 'General information about my store', 1, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(2, 'Minimum Values', 'The minimum values for functions / data', 2, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(3, 'Maximum Values', 'The maximum values for functions / data', 3, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(4, 'Images', 'Image parameters', 4, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(5, 'Customer Details', 'Customer account configuration', 5, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(6, 'Module Options', 'Hidden from configuration', 6, 0);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(7, 'Shipping/Packaging', 'Shipping options available at my store', 7, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(8, 'Product Listing', 'Product Listing    configuration options', 8, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(9, 'Stock', 'Stock configuration options', 9, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(12, 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', 12, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(13, 'Download', 'Downloadable products options', 13, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(16, 'Regulations', 'Regulation options', 16, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(17, 'Credit Cards', 'Credit card options', 17, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(18, 'Program Locations', 'Locations to certain programs on the server.', 18, 1);
INSERT INTO lc_configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES(19, 'Core Updates', 'Core update settings', 19, 1);

INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(3, 'Algeria', 'DZ', 'DZA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(4, 'American Samoa', 'AS', 'ASM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(5, 'Andorra', 'AD', 'AND', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(6, 'Angola', 'AO', 'AGO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(7, 'Anguilla', 'AI', 'AIA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(8, 'Antarctica', 'AQ', 'ATA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(9, 'Antigua and Barbuda', 'AG', 'ATG', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(10, 'Argentina', 'AR', 'ARG', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(11, 'Armenia', 'AM', 'ARM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(12, 'Aruba', 'AW', 'ABW', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(13, 'Australia', 'AU', 'AUS', ':name\r\n:street_address\r\n:suburb :state_code :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(14, 'Austria', 'AT', 'AUT', ':name\r\n:street_address\r\nA-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(15, 'Azerbaijan', 'AZ', 'AZE', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(16, 'Bahamas', 'BS', 'BHS', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(17, 'Bahrain', 'BH', 'BHR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(18, 'Bangladesh', 'BD', 'BGD', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(19, 'Barbados', 'BB', 'BRB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(20, 'Belarus', 'BY', 'BLR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(21, 'Belgium', 'BE', 'BEL', ':name\r\n:street_address\r\nB-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(22, 'Belize', 'BZ', 'BLZ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(23, 'Benin', 'BJ', 'BEN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(24, 'Bermuda', 'BM', 'BMU', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(25, 'Bhutan', 'BT', 'BTN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(26, 'Bolivia', 'BO', 'BOL', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(27, 'Bosnia and Herzegowina', 'BA', 'BIH', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(28, 'Botswana', 'BW', 'BWA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(29, 'Bouvet Island', 'BV', 'BVT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(30, 'Brazil', 'BR', 'BRA', ':name\r\n:street_address\r\n:state\r\n:postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(31, 'British Indian Ocean Territory', 'IO', 'IOT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(32, 'Brunei Darussalam', 'BN', 'BRN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(33, 'Bulgaria', 'BG', 'BGR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(34, 'Burkina Faso', 'BF', 'BFA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(35, 'Burundi', 'BI', 'BDI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(36, 'Cambodia', 'KH', 'KHM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(37, 'Cameroon', 'CM', 'CMR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(38, 'Canada', 'CA', 'CAN', ':name\r\n:street_address\r\n:city :state_code :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(39, 'Cape Verde', 'CV', 'CPV', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(40, 'Cayman Islands', 'KY', 'CYM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(41, 'Central African Republic', 'CF', 'CAF', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(42, 'Chad', 'TD', 'TCD', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(43, 'Chile', 'CL', 'CHL', ':name\r\n:street_address\r\n:city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(44, 'China', 'CN', 'CHN', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(45, 'Christmas Island', 'CX', 'CXR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(47, 'Colombia', 'CO', 'COL', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(48, 'Comoros', 'KM', 'COM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(49, 'Congo', 'CG', 'COG', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(50, 'Cook Islands', 'CK', 'COK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(51, 'Costa Rica', 'CR', 'CRI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(52, 'Cote D''Ivoire', 'CI', 'CIV', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(53, 'Croatia', 'HR', 'HRV', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(54, 'Cuba', 'CU', 'CUB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(55, 'Cyprus', 'CY', 'CYP', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(56, 'Czech Republic', 'CZ', 'CZE', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(57, 'Denmark', 'DK', 'DNK', ':name\r\n:street_address\r\nDK-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(58, 'Djibouti', 'DJ', 'DJI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(59, 'Dominica', 'DM', 'DMA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(60, 'Dominican Republic', 'DO', 'DOM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(61, 'East Timor', 'TP', 'TMP', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(62, 'Ecuador', 'EC', 'ECU', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(63, 'Egypt', 'EG', 'EGY', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(64, 'El Salvador', 'SV', 'SLV', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(65, 'Equatorial Guinea', 'GQ', 'GNQ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(66, 'Eritrea', 'ER', 'ERI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(67, 'Estonia', 'EE', 'EST', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(68, 'Ethiopia', 'ET', 'ETH', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(70, 'Faroe Islands', 'FO', 'FRO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(71, 'Fiji', 'FJ', 'FJI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(72, 'Finland', 'FI', 'FIN', ':name\r\n:street_address\r\nFIN-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(73, 'France', 'FR', 'FRA', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(74, 'France, Metropolitan', 'FX', 'FXX', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(75, 'French Guiana', 'GF', 'GUF', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(76, 'French Polynesia', 'PF', 'PYF', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(77, 'French Southern Territories', 'TF', 'ATF', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(78, 'Gabon', 'GA', 'GAB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(79, 'Gambia', 'GM', 'GMB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(80, 'Georgia', 'GE', 'GEO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(81, 'Germany', 'DE', 'DEU', ':name\r\n:street_address\r\nD-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(82, 'Ghana', 'GH', 'GHA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(83, 'Gibraltar', 'GI', 'GIB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(84, 'Greece', 'GR', 'GRC', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(85, 'Greenland', 'GL', 'GRL', ':name\r\n:street_address\r\nDK-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(86, 'Grenada', 'GD', 'GRD', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(87, 'Guadeloupe', 'GP', 'GLP', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(88, 'Guam', 'GU', 'GUM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(89, 'Guatemala', 'GT', 'GTM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(90, 'Guinea', 'GN', 'GIN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(91, 'Guinea-Bissau', 'GW', 'GNB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(92, 'Guyana', 'GY', 'GUY', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(93, 'Haiti', 'HT', 'HTI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(94, 'Heard and McDonald Islands', 'HM', 'HMD', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(95, 'Honduras', 'HN', 'HND', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(96, 'Hong Kong', 'HK', 'HKG', ':name\r\n:street_address\r\n:city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(97, 'Hungary', 'HU', 'HUN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(98, 'Iceland', 'IS', 'ISL', ':name\r\n:street_address\r\nIS:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(99, 'India', 'IN', 'IND', ':name\r\n:street_address\r\n:city-:postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(100, 'Indonesia', 'ID', 'IDN', ':name\r\n:street_address\r\n:city :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(101, 'Iran', 'IR', 'IRN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(102, 'Iraq', 'IQ', 'IRQ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(103, 'Ireland', 'IE', 'IRL', ':name\r\n:street_address\r\nIE-:city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(104, 'Israel', 'IL', 'ISR', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(105, 'Italy', 'IT', 'ITA', ':name\r\n:street_address\r\n:postcode-:city :state_code\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(106, 'Jamaica', 'JM', 'JAM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(107, 'Japan', 'JP', 'JPN', ':name\r\n:street_address, :suburb\r\n:city :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(108, 'Jordan', 'JO', 'JOR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(109, 'Kazakhstan', 'KZ', 'KAZ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(110, 'Kenya', 'KE', 'KEN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(111, 'Kiribati', 'KI', 'KIR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(112, 'Korea, North', 'KP', 'PRK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(113, 'Korea, South', 'KR', 'KOR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(114, 'Kuwait', 'KW', 'KWT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(115, 'Kyrgyzstan', 'KG', 'KGZ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(116, 'Laos', 'LA', 'LAO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(117, 'Latvia', 'LV', 'LVA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(118, 'Lebanon', 'LB', 'LBN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(119, 'Lesotho', 'LS', 'LSO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(120, 'Liberia', 'LR', 'LBR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(122, 'Liechtenstein', 'LI', 'LIE', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(123, 'Lithuania', 'LT', 'LTU', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(124, 'Luxembourg', 'LU', 'LUX', ':name\r\n:street_address\r\nL-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(125, 'Macau', 'MO', 'MAC', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(126, 'Macedonia', 'MK', 'MKD', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(127, 'Madagascar', 'MG', 'MDG', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(128, 'Malawi', 'MW', 'MWI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(129, 'Malaysia', 'MY', 'MYS', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(130, 'Maldives', 'MV', 'MDV', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(131, 'Mali', 'ML', 'MLI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(132, 'Malta', 'MT', 'MLT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(133, 'Marshall Islands', 'MH', 'MHL', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(134, 'Martinique', 'MQ', 'MTQ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(135, 'Mauritania', 'MR', 'MRT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(136, 'Mauritius', 'MU', 'MUS', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(137, 'Mayotte', 'YT', 'MYT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(138, 'Mexico', 'MX', 'MEX', ':name\r\n:street_address\r\n:postcode :city, :state_code\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(139, 'Micronesia', 'FM', 'FSM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(140, 'Moldova', 'MD', 'MDA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(141, 'Monaco', 'MC', 'MCO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(142, 'Mongolia', 'MN', 'MNG', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(143, 'Montserrat', 'MS', 'MSR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(144, 'Morocco', 'MA', 'MAR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(145, 'Mozambique', 'MZ', 'MOZ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(146, 'Myanmar', 'MM', 'MMR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(147, 'Namibia', 'NA', 'NAM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(148, 'Nauru', 'NR', 'NRU', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(149, 'Nepal', 'NP', 'NPL', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(150, 'Netherlands', 'NL', 'NLD', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(151, 'Netherlands Antilles', 'AN', 'ANT', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(152, 'New Caledonia', 'NC', 'NCL', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(153, 'New Zealand', 'NZ', 'NZL', ':name\r\n:street_address\r\n:suburb\r\n:city :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(154, 'Nicaragua', 'NI', 'NIC', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(155, 'Niger', 'NE', 'NER', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(156, 'Nigeria', 'NG', 'NGA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(157, 'Niue', 'NU', 'NIU', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(158, 'Norfolk Island', 'NF', 'NFK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(159, 'Northern Mariana Islands', 'MP', 'MNP', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(160, 'Norway', 'NO', 'NOR', ':name\r\n:street_address\r\nNO-:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(161, 'Oman', 'OM', 'OMN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(162, 'Pakistan', 'PK', 'PAK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(163, 'Palau', 'PW', 'PLW', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(164, 'Panama', 'PA', 'PAN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(165, 'Papua New Guinea', 'PG', 'PNG', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(166, 'Paraguay', 'PY', 'PRY', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(167, 'Peru', 'PE', 'PER', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(168, 'Philippines', 'PH', 'PHL', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(169, 'Pitcairn', 'PN', 'PCN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(170, 'Poland', 'PL', 'POL', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(171, 'Portugal', 'PT', 'PRT', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(172, 'Puerto Rico', 'PR', 'PRI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(173, 'Qatar', 'QA', 'QAT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(174, 'Reunion', 'RE', 'REU', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(175, 'Romania', 'RO', 'ROM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(176, 'Russia', 'RU', 'RUS', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(177, 'Rwanda', 'RW', 'RWA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(178, 'Saint Kitts and Nevis', 'KN', 'KNA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(179, 'Saint Lucia', 'LC', 'LCA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(181, 'Samoa', 'WS', 'WSM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(182, 'San Marino', 'SM', 'SMR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(183, 'Sao Tome and Principe', 'ST', 'STP', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(184, 'Saudi Arabia', 'SA', 'SAU', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(185, 'Senegal', 'SN', 'SEN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(186, 'Seychelles', 'SC', 'SYC', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(187, 'Sierra Leone', 'SL', 'SLE', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(188, 'Singapore', 'SG', 'SGP', ':name\r\n:street_address\r\n:city :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(189, 'Slovakia', 'SK', 'SVK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(190, 'Slovenia', 'SI', 'SVN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(191, 'Solomon Islands', 'SB', 'SLB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(192, 'Somalia', 'SO', 'SOM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(193, 'South Africa', 'ZA', 'ZAF', ':name\r\n:street_address\r\n:suburb\r\n:city\r\n:postcode :country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(195, 'Spain', 'ES', 'ESP', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(196, 'Sri Lanka', 'LK', 'LKA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(197, 'St. Helena', 'SH', 'SHN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(198, 'St. Pierre and Miquelon', 'PM', 'SPM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(199, 'Sudan', 'SD', 'SDN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(200, 'Suriname', 'SR', 'SUR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(202, 'Swaziland', 'SZ', 'SWZ', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(203, 'Sweden', 'SE', 'SWE', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(204, 'Switzerland', 'CH', 'CHE', ':name\r\n:street_address\r\n:postcode :city\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(205, 'Syrian Arab Republic', 'SY', 'SYR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(206, 'Taiwan', 'TW', 'TWN', ':name\r\n:street_address\r\n:city :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(207, 'Tajikistan', 'TJ', 'TJK', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(208, 'Tanzania', 'TZ', 'TZA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(209, 'Thailand', 'TH', 'THA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(210, 'Togo', 'TG', 'TGO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(211, 'Tokelau', 'TK', 'TKL', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(212, 'Tonga', 'TO', 'TON', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(213, 'Trinidad and Tobago', 'TT', 'TTO', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(214, 'Tunisia', 'TN', 'TUN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(215, 'Turkey', 'TR', 'TUR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(216, 'Turkmenistan', 'TM', 'TKM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(217, 'Turks and Caicos Islands', 'TC', 'TCA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(218, 'Tuvalu', 'TV', 'TUV', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(219, 'Uganda', 'UG', 'UGA', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(220, 'Ukraine', 'UA', 'UKR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(221, 'United Arab Emirates', 'AE', 'ARE', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(222, 'United Kingdom', 'GB', 'GBR', ':name\r\n:street_address\r\n:city\r\n:postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(223, 'United States of America', 'US', 'USA', ':name\r\n:street_address\r\n:city :state_code :postcode\r\n:country');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(224, 'United States Minor Outlying Islands', 'UM', 'UMI', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(225, 'Uruguay', 'UY', 'URY', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(226, 'Uzbekistan', 'UZ', 'UZB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(227, 'Vanuatu', 'VU', 'VUT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(228, 'Vatican City State (Holy See)', 'VA', 'VAT', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(229, 'Venezuela', 'VE', 'VEN', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(230, 'Vietnam', 'VN', 'VNM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(231, 'Virgin Islands (British)', 'VG', 'VGB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(233, 'Wallis and Futuna Islands', 'WF', 'WLF', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(234, 'Western Sahara', 'EH', 'ESH', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(235, 'Yemen', 'YE', 'YEM', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(236, 'Yugoslavia', 'YU', 'YUG', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(237, 'Zaire', 'ZR', 'ZAR', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(238, 'Zambia', 'ZM', 'ZMB', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(239, 'Zimbabwe', 'ZW', 'ZWE', '');
INSERT INTO lc_countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format) VALUES(242, 'BBB', 'BB', 'BBB', 'BBBB');

INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(1, 'American Express', '/^(34|37)\\d{13}$/', '1', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(2, 'Diners Club', '/^(30|36|38)\\d{12}$/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(3, 'JCB', '/^((2131|1800)\\d{11}|3[0135]\\d{14})$/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(4, 'MasterCard', '/^5[1-5]\\d{14}$/', '1', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(5, 'Visa', '/^4\\d{12}(\\d{3})?$/', '1', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(6, 'Discover Card', '/^6011\\d{12}$/', '1', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(7, 'Solo', '/^(63|67)\\d{14}(\\d{2,3})?$/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(8, 'Switch', '/^(49|56|63|67)\\d{14}(\\d{2,3})?$/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(9, 'Australian Bankcard', '/^5610\\d{12}$/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(10, 'enRoute', '/^(2014|2149)\\d{11}$/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(11, 'Laser', '/^6304\\d{12}(\\d{2,3})?$/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(12, 'Maestro', '/^(50|56|57|58|6)/', '0', 0);
INSERT INTO lc_credit_cards (id, credit_card_name, pattern, credit_card_status, sort_order) VALUES(13, 'Smartpay', '/^4\\d{12}(\\d{3})?$/', '0', 0);

INSERT INTO `lc_currencies` (`currencies_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_places`, `value`, `last_updated`) VALUES(1, 'US Dollar', 'USD', '$', '', '2', 1.00000000, '2012-12-07 09:25:45');
INSERT INTO `lc_currencies` (`currencies_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_places`, `value`, `last_updated`) VALUES(2, 'Euro', 'EUR', '€', '', '2', 1.20760000, '2012-12-07 09:25:45');
INSERT INTO `lc_currencies` (`currencies_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_places`, `value`, `last_updated`) VALUES(3, 'British Pounds', 'GBP', '£', '', '2', 1.75870001, '2012-12-07 09:25:45');

INSERT INTO lc_customers_groups (customers_group_id, language_id, customers_group_name) VALUES(1, 1, 'Retail');
INSERT INTO lc_customers_groups (customers_group_id, language_id, customers_group_name) VALUES(2, 1, 'Wholesale');

INSERT INTO lc_languages (languages_id, `name`, code, locale, `charset`, date_format_short, date_format_long, time_format, text_direction, currencies_id, numeric_separator_decimal, numeric_separator_thousands, parent_id, sort_order) VALUES(1, 'English', 'en_US', 'en_US.UTF-8,en_US,english', 'utf-8', '%m/%d/%Y', '%A %B %d, %Y at %H:%M', '%H:%M:%S', 'ltr', 1, '.', ',', 0, 10);

INSERT INTO lc_orders_status (orders_status_id, language_id, orders_status_name) VALUES(1, 1, 'Pending');
INSERT INTO lc_orders_status (orders_status_id, language_id, orders_status_name) VALUES(2, 1, 'Processing');
INSERT INTO lc_orders_status (orders_status_id, language_id, orders_status_name) VALUES(3, 1, 'Preparing');
INSERT INTO lc_orders_status (orders_status_id, language_id, orders_status_name) VALUES(4, 1, 'Delivered');

INSERT INTO lc_orders_transactions_status (id, language_id, status_name) VALUES(1, 1, 'Authorize');
INSERT INTO lc_orders_transactions_status (id, language_id, status_name) VALUES(2, 1, 'Cancel');
INSERT INTO lc_orders_transactions_status (id, language_id, status_name) VALUES(3, 1, 'Approve');
INSERT INTO lc_orders_transactions_status (id, language_id, status_name) VALUES(4, 1, 'Inquiry');

INSERT INTO lc_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) VALUES(1, 1, 'Originals', 'originals', 0, 0, 0);
INSERT INTO lc_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) VALUES(2, 1, 'Thumbnails', 'thumbnails', 220, 242, 1);
INSERT INTO lc_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) VALUES(3, 1, 'Product Information Page', 'product_info', 360, 414, 1);
INSERT INTO lc_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) VALUES(4, 1, 'Large', 'large', 375, 300, 0);
INSERT INTO lc_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) VALUES(5, 1, 'Mini', 'mini', 50, 40, 0);
INSERT INTO lc_products_images_groups (id, language_id, title, code, size_width, size_height, force_size) VALUES(6, 1, 'Popup', 'popup', 600, 450, 0);

INSERT INTO lc_tax_class VALUES (1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', now(), now());

# USA/Florida
INSERT INTO lc_tax_rates VALUES (1, 1, 1, 1, 7.0, 'FL TAX 7.0%', now(), now());
INSERT INTO lc_geo_zones (geo_zone_id,geo_zone_name,geo_zone_description,date_added) VALUES (1,"Florida","Florida local sales tax zone",now());
INSERT INTO lc_zones_to_geo_zones (association_id,zone_country_id,zone_id,geo_zone_id,date_added) VALUES (1,223,4031,1,now());

INSERT INTO lc_templates (id, title, code, author_name, author_www, markup_version, css_based, `medium`) VALUES(1, 'Default Responsive Template', 'default', 'Loaded Commerce', 'http://www.loadedcommerce.com', 'HTML 5.0', 1, 'Screen');

INSERT INTO lc_weight_classes (weight_class_id, weight_class_key, language_id, weight_class_title) VALUES(1, 'g', 1, 'Gram(s)');
INSERT INTO lc_weight_classes (weight_class_id, weight_class_key, language_id, weight_class_title) VALUES(2, 'kg', 1, 'Kilogram(s)');
INSERT INTO lc_weight_classes (weight_class_id, weight_class_key, language_id, weight_class_title) VALUES(3, 'oz', 1, 'Ounce(s)');
INSERT INTO lc_weight_classes (weight_class_id, weight_class_key, language_id, weight_class_title) VALUES(4, 'lb', 1, 'Pound(s)');

INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1, 1, 'BDS', 'بد خشان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2, 1, 'BDG', 'بادغیس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3, 1, 'BGL', 'بغلان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4, 1, 'BAL', 'بلخ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(5, 1, 'BAM', 'بامیان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(6, 1, 'DAY', 'دایکندی');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(7, 1, 'FRA', '�?راه');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(8, 1, 'FYB', '�?ارياب');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(9, 1, 'GHA', 'غزنى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(10, 1, 'GHO', 'غور');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(11, 1, 'HEL', 'هلمند');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(12, 1, 'HER', 'هرات');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(13, 1, 'JOW', 'جوزجان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(14, 1, 'KAB', 'کابل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(15, 1, 'KAN', 'قندھار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(16, 1, 'KAP', 'کاپيسا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(17, 1, 'KHO', 'خوست');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(18, 1, 'KNR', 'ک�?نَر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(19, 1, 'KDZ', 'كندوز');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(20, 1, 'LAG', 'لغمان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(21, 1, 'LOW', 'لوګر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(22, 1, 'NAN', 'ننگرهار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(23, 1, 'NIM', 'نیمروز');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(24, 1, 'NUR', 'نورستان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(25, 1, 'ORU', 'ؤروزگان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(26, 1, 'PIA', 'پکتیا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(27, 1, 'PKA', 'پکتيکا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(28, 1, 'PAN', 'پنج شیر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(29, 1, 'PAR', 'پروان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(30, 1, 'SAM', 'سمنگان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(31, 1, 'SAR', 'سر پل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(32, 1, 'TAK', 'تخار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(33, 1, 'WAR', 'وردک');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(34, 1, 'ZAB', 'زابل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(35, 2, 'BR', 'Beratit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(36, 2, 'BU', 'Bulqizës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(37, 2, 'DI', 'Dibrës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(38, 2, 'DL', 'Delvinës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(39, 2, 'DR', 'Durrësit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(40, 2, 'DV', 'Devollit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(41, 2, 'EL', 'Elbasanit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(42, 2, 'ER', 'Kolonjës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(43, 2, 'FR', 'Fierit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(44, 2, 'GJ', 'Gjirokastrës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(45, 2, 'GR', 'Gramshit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(46, 2, 'HA', 'Hasit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(47, 2, 'KA', 'Kavajës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(48, 2, 'KB', 'Kurbinit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(49, 2, 'KC', 'Kuçovës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(50, 2, 'KO', 'Korçës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(51, 2, 'KR', 'Krujës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(52, 2, 'KU', 'Kukësit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(53, 2, 'LB', 'Librazhdit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(54, 2, 'LE', 'Lezhës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(55, 2, 'LU', 'Lushnjës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(56, 2, 'MK', 'Mallakastrës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(57, 2, 'MM', 'Malësisë së Madhe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(58, 2, 'MR', 'Mirditës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(59, 2, 'MT', 'Matit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(60, 2, 'PG', 'Pogradecit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(61, 2, 'PQ', 'Peqinit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(62, 2, 'PR', 'Përmetit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(63, 2, 'PU', 'Pukës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(64, 2, 'SH', 'Shkodrës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(65, 2, 'SK', 'Skraparit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(66, 2, 'SR', 'Sarandës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(67, 2, 'TE', 'Tepelenës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(68, 2, 'TP', 'Tropojës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(69, 2, 'TR', 'Tiranës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(70, 2, 'VL', 'Vlorës');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(71, 3, '01', 'ولاية أدرار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(72, 3, '02', 'ولاية الشل�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(73, 3, '03', 'ولاية الأغواط');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(74, 3, '04', 'ولاية أم البواقي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(75, 3, '05', 'ولاية باتنة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(76, 3, '06', 'ولاية بجاية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(77, 3, '07', 'ولاية بسكرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(78, 3, '08', 'ولاية بشار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(79, 3, '09', 'البليدة‎');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(80, 3, '10', 'ولاية البويرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(81, 3, '11', 'ولاية تمنراست');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(82, 3, '12', 'ولاية تبسة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(83, 3, '13', 'تلمسان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(84, 3, '14', 'ولاية تيارت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(85, 3, '15', 'تيزي وزو');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(86, 3, '16', 'ولاية الجزائر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(87, 3, '17', 'ولاية عين الد�?لى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(88, 3, '18', 'ولاية جيجل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(89, 3, '19', 'ولاية سطي�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(90, 3, '20', 'ولاية سعيدة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(91, 3, '21', 'السكيكدة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(92, 3, '22', 'ولاية سيدي بلعباس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(93, 3, '23', 'ولاية عنابة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(94, 3, '24', 'ولاية قالمة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(95, 3, '25', 'قسنطينة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(96, 3, '26', 'ولاية المدية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(97, 3, '27', 'ولاية مستغانم');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(98, 3, '28', 'ولاية المسيلة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(99, 3, '29', 'ولاية معسكر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(100, 3, '30', 'ورقلة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(101, 3, '31', 'وهران');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(102, 3, '32', 'ولاية البيض');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(103, 3, '33', 'ولاية اليزي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(104, 3, '34', 'ولاية برج بوعريريج');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(105, 3, '35', 'ولاية بومرداس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(106, 3, '36', 'ولاية الطار�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(107, 3, '37', 'تندو�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(108, 3, '38', 'ولاية تسمسيلت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(109, 3, '39', 'ولاية الوادي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(110, 3, '40', 'ولاية خنشلة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(111, 3, '41', 'ولاية سوق أهراس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(112, 3, '42', 'ولاية تيبازة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(113, 3, '43', 'ولاية ميلة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(114, 3, '44', 'ولاية عين الد�?لى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(115, 3, '45', 'ولاية النعامة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(116, 3, '46', 'ولاية عين تموشنت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(117, 3, '47', 'ولاية غرداية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(118, 3, '48', 'ولاية غليزان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(119, 4, 'EA', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(120, 4, 'MA', 'Manu''a');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(121, 4, 'RI', 'Rose Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(122, 4, 'SI', 'Swains Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(123, 4, 'WE', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(124, 5, 'AN', 'Andorra la Vella');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(125, 5, 'CA', 'Canillo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(126, 5, 'EN', 'Encamp');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(127, 5, 'LE', 'Escaldes-Engordany');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(128, 5, 'LM', 'La Massana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(129, 5, 'OR', 'Ordino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(130, 5, 'SJ', 'Sant Juliá de Lória');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(131, 6, 'BGO', 'Bengo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(132, 6, 'BGU', 'Benguela');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(133, 6, 'BIE', 'Bié');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(134, 6, 'CAB', 'Cabinda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(135, 6, 'CCU', 'Cuando Cubango');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(136, 6, 'CNO', 'Cuanza Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(137, 6, 'CUS', 'Cuanza Sul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(138, 6, 'CNN', 'Cunene');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(139, 6, 'HUA', 'Huambo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(140, 6, 'HUI', 'Huíla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(141, 6, 'LUA', 'Luanda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(142, 6, 'LNO', 'Lunda Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(143, 6, 'LSU', 'Lunda Sul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(144, 6, 'MAL', 'Malanje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(145, 6, 'MOX', 'Moxico');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(146, 6, 'NAM', 'Namibe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(147, 6, 'UIG', 'Uíge');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(148, 6, 'ZAI', 'Zaire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(149, 9, 'BAR', 'Barbuda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(150, 9, 'SGE', 'Saint George');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(151, 9, 'SJO', 'Saint John');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(152, 9, 'SMA', 'Saint Mary');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(153, 9, 'SPA', 'Saint Paul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(154, 9, 'SPE', 'Saint Peter');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(155, 9, 'SPH', 'Saint Philip');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(156, 10, 'A', 'Salta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(157, 10, 'B', 'Buenos Aires Province');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(158, 10, 'C', 'Capital Federal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(159, 10, 'D', 'San Luis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(160, 10, 'E', 'Entre Ríos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(161, 10, 'F', 'La Rioja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(162, 10, 'G', 'Santiago del Estero');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(163, 10, 'H', 'Chaco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(164, 10, 'J', 'San Juan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(165, 10, 'K', 'Catamarca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(166, 10, 'L', 'La Pampa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(167, 10, 'M', 'Mendoza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(168, 10, 'N', 'Misiones');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(169, 10, 'P', 'Formosa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(170, 10, 'Q', 'Neuquén');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(171, 10, 'R', 'Río Negro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(172, 10, 'S', 'Santa Fe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(173, 10, 'T', 'Tucumán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(174, 10, 'U', 'Chubut');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(175, 10, 'V', 'Tierra del Fuego');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(176, 10, 'W', 'Corrientes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(177, 10, 'X', 'Córdoba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(178, 10, 'Y', 'Jujuy');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(179, 10, 'Z', 'Santa Cruz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(180, 11, 'AG', 'Արագածոտն');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(181, 11, 'AR', 'Արարատ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(182, 11, 'AV', 'Արմավիր');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(183, 11, 'ER', 'Երևան');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(184, 11, 'GR', 'Գեղարքունիք');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(185, 11, 'KT', 'Կոտայք');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(186, 11, 'LO', 'Լոռի');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(187, 11, 'SH', 'Շիրակ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(188, 11, 'SU', '�?յունիք');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(189, 11, 'TV', '�?ավուշ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(190, 11, 'VD', 'Վայո�? �?որ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(191, 13, 'ACT', 'Australian Capital Territory');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(192, 13, 'NSW', 'New South Wales');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(193, 13, 'NT', 'Northern Territory');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(194, 13, 'QLD', 'Queensland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(195, 13, 'SA', 'South Australia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(196, 13, 'TAS', 'Tasmania');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(197, 13, 'VIC', 'Victoria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(198, 13, 'WA', 'Western Australia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(199, 14, '1', 'Burgenland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(200, 14, '2', 'Kärnten');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(201, 14, '3', 'Niederösterreich');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(202, 14, '4', 'Oberösterreich');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(203, 14, '5', 'Salzburg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(204, 14, '6', 'Steiermark');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(205, 14, '7', 'Tirol');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(206, 14, '8', 'Voralberg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(207, 14, '9', 'Wien');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(208, 15, 'AB', '�?li Bayramlı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(209, 15, 'ABS', 'Abşeron');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(210, 15, 'AGC', 'Ağcabədi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(211, 15, 'AGM', 'Ağdam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(212, 15, 'AGS', 'Ağdaş');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(213, 15, 'AGA', 'Ağstafa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(214, 15, 'AGU', 'Ağsu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(215, 15, 'AST', 'Astara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(216, 15, 'BA', 'Bakı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(217, 15, 'BAB', 'Babək');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(218, 15, 'BAL', 'Balakən');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(219, 15, 'BAR', 'Bərdə');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(220, 15, 'BEY', 'Beyləqan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(221, 15, 'BIL', 'Biləsuvar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(222, 15, 'CAB', 'Cəbrayıl');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(223, 15, 'CAL', 'Cəlilabab');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(224, 15, 'CUL', 'Julfa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(225, 15, 'DAS', 'Daşkəsən');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(226, 15, 'DAV', 'Dəvəçi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(227, 15, 'FUZ', 'Füzuli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(228, 15, 'GA', 'Gəncə');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(229, 15, 'GAD', 'Gədəbəy');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(230, 15, 'GOR', 'Goranboy');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(231, 15, 'GOY', 'Göyçay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(232, 15, 'HAC', 'Hacıqabul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(233, 15, 'IMI', 'İmişli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(234, 15, 'ISM', 'İsmayıllı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(235, 15, 'KAL', 'Kəlbəcər');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(236, 15, 'KUR', 'Kürdəmir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(237, 15, 'LA', 'Lənkəran');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(238, 15, 'LAC', 'Laçın');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(239, 15, 'LAN', 'Lənkəran');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(240, 15, 'LER', 'Lerik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(241, 15, 'MAS', 'Masallı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(242, 15, 'MI', 'Mingəçevir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(243, 15, 'NA', 'Naftalan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(244, 15, 'NEF', 'Neftçala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(245, 15, 'OGU', 'Oğuz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(246, 15, 'ORD', 'Ordubad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(247, 15, 'QAB', 'Qəbələ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(248, 15, 'QAX', 'Qax');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(249, 15, 'QAZ', 'Qazax');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(250, 15, 'QOB', 'Qobustan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(251, 15, 'QBA', 'Quba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(252, 15, 'QBI', 'Qubadlı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(253, 15, 'QUS', 'Qusar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(254, 15, 'SA', 'Şəki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(255, 15, 'SAT', 'Saatlı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(256, 15, 'SAB', 'Sabirabad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(257, 15, 'SAD', 'Sədərək');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(258, 15, 'SAH', 'Şahbuz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(259, 15, 'SAK', 'Şəki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(260, 15, 'SAL', 'Salyan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(261, 15, 'SM', 'Sumqayıt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(262, 15, 'SMI', 'Şamaxı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(263, 15, 'SKR', 'Şəmkir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(264, 15, 'SMX', 'Samux');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(265, 15, 'SAR', 'Şərur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(266, 15, 'SIY', 'Siyəzən');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(267, 15, 'SS', 'Şuşa (City)');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(268, 15, 'SUS', 'Şuşa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(269, 15, 'TAR', 'Tərtər');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(270, 15, 'TOV', 'Tovuz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(271, 15, 'UCA', 'Ucar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(272, 15, 'XA', 'Xankəndi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(273, 15, 'XAC', 'Xaçmaz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(274, 15, 'XAN', 'Xanlar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(275, 15, 'XIZ', 'Xızı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(276, 15, 'XCI', 'Xocalı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(277, 15, 'XVD', 'Xocavənd');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(278, 15, 'YAR', 'Yardımlı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(279, 15, 'YE', 'Yevlax (City)');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(280, 15, 'YEV', 'Yevlax');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(281, 15, 'ZAN', 'Zəngilan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(282, 15, 'ZAQ', 'Zaqatala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(283, 15, 'ZAR', 'Zərdab');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(284, 15, 'NX', 'Nakhichevan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(285, 16, 'AC', 'Acklins and Crooked Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(286, 16, 'BI', 'Bimini');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(287, 16, 'CI', 'Cat Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(288, 16, 'EX', 'Exuma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(289, 16, 'FR', 'Freeport');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(290, 16, 'FC', 'Fresh Creek');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(291, 16, 'GH', 'Governor''s Harbour');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(292, 16, 'GT', 'Green Turtle Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(293, 16, 'HI', 'Harbour Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(294, 16, 'HR', 'High Rock');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(295, 16, 'IN', 'Inagua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(296, 16, 'KB', 'Kemps Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(297, 16, 'LI', 'Long Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(298, 16, 'MH', 'Marsh Harbour');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(299, 16, 'MA', 'Mayaguana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(300, 16, 'NP', 'New Providence');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(301, 16, 'NT', 'Nicholls Town and Berry Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(302, 16, 'RI', 'Ragged Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(303, 16, 'RS', 'Rock Sound');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(304, 16, 'SS', 'San Salvador and Rum Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(305, 16, 'SP', 'Sandy Point');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(306, 17, '01', 'الحد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(307, 17, '02', 'المحرق');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(308, 17, '03', 'المنامة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(309, 17, '04', 'جد ح�?ص');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(310, 17, '05', 'المنطقة الشمالية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(311, 17, '06', 'سترة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(312, 17, '07', 'المنطقة الوسطى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(313, 17, '08', 'مدينة عيسى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(314, 17, '09', 'الر�?اع والمنطقة الجنوبية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(315, 17, '10', 'المنطقة الغربية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(316, 17, '11', 'جزر حوار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(317, 17, '12', 'مدينة حمد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(318, 18, '01', 'Bandarban');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(319, 18, '02', 'Barguna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(320, 18, '03', 'Bogra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(321, 18, '04', 'Brahmanbaria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(322, 18, '05', 'Bagerhat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(323, 18, '06', 'Barisal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(324, 18, '07', 'Bhola');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(325, 18, '08', 'Comilla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(326, 18, '09', 'Chandpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(327, 18, '10', 'Chittagong');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(328, 18, '11', 'Cox''s Bazar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(329, 18, '12', 'Chuadanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(330, 18, '13', 'Dhaka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(331, 18, '14', 'Dinajpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(332, 18, '15', 'Faridpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(333, 18, '16', 'Feni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(334, 18, '17', 'Gopalganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(335, 18, '18', 'Gazipur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(336, 18, '19', 'Gaibandha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(337, 18, '20', 'Habiganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(338, 18, '21', 'Jamalpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(339, 18, '22', 'Jessore');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(340, 18, '23', 'Jhenaidah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(341, 18, '24', 'Jaipurhat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(342, 18, '25', 'Jhalakati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(343, 18, '26', 'Kishoreganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(344, 18, '27', 'Khulna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(345, 18, '28', 'Kurigram');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(346, 18, '29', 'Khagrachari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(347, 18, '30', 'Kushtia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(348, 18, '31', 'Lakshmipur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(349, 18, '32', 'Lalmonirhat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(350, 18, '33', 'Manikganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(351, 18, '34', 'Mymensingh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(352, 18, '35', 'Munshiganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(353, 18, '36', 'Madaripur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(354, 18, '37', 'Magura');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(355, 18, '38', 'Moulvibazar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(356, 18, '39', 'Meherpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(357, 18, '40', 'Narayanganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(358, 18, '41', 'Netrakona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(359, 18, '42', 'Narsingdi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(360, 18, '43', 'Narail');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(361, 18, '44', 'Natore');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(362, 18, '45', 'Nawabganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(363, 18, '46', 'Nilphamari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(364, 18, '47', 'Noakhali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(365, 18, '48', 'Naogaon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(366, 18, '49', 'Pabna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(367, 18, '50', 'Pirojpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(368, 18, '51', 'Patuakhali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(369, 18, '52', 'Panchagarh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(370, 18, '53', 'Rajbari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(371, 18, '54', 'Rajshahi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(372, 18, '55', 'Rangpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(373, 18, '56', 'Rangamati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(374, 18, '57', 'Sherpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(375, 18, '58', 'Satkhira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(376, 18, '59', 'Sirajganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(377, 18, '60', 'Sylhet');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(378, 18, '61', 'Sunamganj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(379, 18, '62', 'Shariatpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(380, 18, '63', 'Tangail');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(381, 18, '64', 'Thakurgaon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(382, 19, 'A', 'Saint Andrew');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(383, 19, 'C', 'Christ Church');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(384, 19, 'E', 'Saint Peter');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(385, 19, 'G', 'Saint George');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(386, 19, 'J', 'Saint John');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(387, 19, 'L', 'Saint Lucy');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(388, 19, 'M', 'Saint Michael');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(389, 19, 'O', 'Saint Joseph');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(390, 19, 'P', 'Saint Philip');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(391, 19, 'S', 'Saint James');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(392, 19, 'T', 'Saint Thomas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(393, 20, 'BR', 'Бр�?�?�?цка�? во�?бла�?ць');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(394, 20, 'HO', 'Го�?мель�?ка�? во�?бла�?ць');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(395, 20, 'HR', 'Гро�?дзен�?ка�? во�?бла�?ць');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(396, 20, 'MA', 'Магілёў�?ка�? во�?бла�?ць');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(397, 20, 'MI', 'Мі�?н�?ка�? во�?бла�?ць');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(398, 20, 'VI', 'Ві�?цеб�?ка�? во�?бла�?ць');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(399, 21, 'BRU', 'Brussel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(400, 21, 'VAN', 'Antwerpen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(401, 21, 'VBR', 'Vlaams-Brabant');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(402, 21, 'VLI', 'Limburg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(403, 21, 'VOV', 'Oost-Vlaanderen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(404, 21, 'VWV', 'West-Vlaanderen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(405, 21, 'WBR', 'Brabant Wallon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(406, 21, 'WHT', 'Hainaut');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(407, 21, 'WLG', 'Liège/Lüttich');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(408, 21, 'WLX', 'Luxembourg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(409, 21, 'WNA', 'Namur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(410, 22, 'BZ', 'Belize District');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(411, 22, 'CY', 'Cayo District');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(412, 22, 'CZL', 'Corozal District');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(413, 22, 'OW', 'Orange Walk District');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(414, 22, 'SC', 'Stann Creek District');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(415, 22, 'TOL', 'Toledo District');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(416, 23, 'AL', 'Alibori');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(417, 23, 'AK', 'Atakora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(418, 23, 'AQ', 'Atlantique');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(419, 23, 'BO', 'Borgou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(420, 23, 'CO', 'Collines');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(421, 23, 'DO', 'Donga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(422, 23, 'KO', 'Kouffo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(423, 23, 'LI', 'Littoral');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(424, 23, 'MO', 'Mono');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(425, 23, 'OU', 'Ouémé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(426, 23, 'PL', 'Plateau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(427, 23, 'ZO', 'Zou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(428, 24, 'DEV', 'Devonshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(429, 24, 'HA', 'Hamilton City');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(430, 24, 'HAM', 'Hamilton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(431, 24, 'PAG', 'Paget');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(432, 24, 'PEM', 'Pembroke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(433, 24, 'SAN', 'Sandys');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(434, 24, 'SG', 'Saint George City');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(435, 24, 'SGE', 'Saint George''s');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(436, 24, 'SMI', 'Smiths');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(437, 24, 'SOU', 'Southampton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(438, 24, 'WAR', 'Warwick');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(439, 25, '11', 'Paro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(440, 25, '12', 'Chukha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(441, 25, '13', 'Haa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(442, 25, '14', 'Samtse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(443, 25, '15', 'Thimphu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(444, 25, '21', 'Tsirang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(445, 25, '22', 'Dagana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(446, 25, '23', 'Punakha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(447, 25, '24', 'Wangdue Phodrang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(448, 25, '31', 'Sarpang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(449, 25, '32', 'Trongsa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(450, 25, '33', 'Bumthang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(451, 25, '34', 'Zhemgang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(452, 25, '41', 'Trashigang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(453, 25, '42', 'Mongar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(454, 25, '43', 'Pemagatshel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(455, 25, '44', 'Luentse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(456, 25, '45', 'Samdrup Jongkhar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(457, 25, 'GA', 'Gasa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(458, 25, 'TY', 'Trashiyangse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(459, 26, 'B', 'El Beni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(460, 26, 'C', 'Cochabamba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(461, 26, 'H', 'Chuquisaca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(462, 26, 'L', 'La Paz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(463, 26, 'N', 'Pando');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(464, 26, 'O', 'Oruro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(465, 26, 'P', 'Potosí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(466, 26, 'S', 'Santa Cruz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(467, 26, 'T', 'Tarija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(468, 28, 'CE', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(469, 28, 'GH', 'Ghanzi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(470, 28, 'KG', 'Kgalagadi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(471, 28, 'KL', 'Kgatleng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(472, 28, 'KW', 'Kweneng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(473, 28, 'NE', 'North-East');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(474, 28, 'NW', 'North-West');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(475, 28, 'SE', 'South-East');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(476, 28, 'SO', 'Southern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(477, 30, 'AC', 'Acre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(478, 30, 'AL', 'Alagoas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(479, 30, 'AM', 'Amazônia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(480, 30, 'AP', 'Amapá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(481, 30, 'BA', 'Bahia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(482, 30, 'CE', 'Ceará');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(483, 30, 'DF', 'Distrito Federal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(484, 30, 'ES', 'Espírito Santo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(485, 30, 'GO', 'Goiás');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(486, 30, 'MA', 'Maranhão');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(487, 30, 'MG', 'Minas Gerais');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(488, 30, 'MS', 'Mato Grosso do Sul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(489, 30, 'MT', 'Mato Grosso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(490, 30, 'PA', 'Pará');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(491, 30, 'PB', 'Paraíba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(492, 30, 'PE', 'Pernambuco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(493, 30, 'PI', 'Piauí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(494, 30, 'PR', 'Paraná');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(495, 30, 'RJ', 'Rio de Janeiro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(496, 30, 'RN', 'Rio Grande do Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(497, 30, 'RO', 'Rondônia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(498, 30, 'RR', 'Roraima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(499, 30, 'RS', 'Rio Grande do Sul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(500, 30, 'SC', 'Santa Catarina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(501, 30, 'SE', 'Sergipe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(502, 30, 'SP', 'São Paulo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(503, 30, 'TO', 'Tocantins');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(504, 31, 'PB', 'Peros Banhos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(505, 31, 'SI', 'Salomon Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(506, 31, 'NI', 'Nelsons Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(507, 31, 'TB', 'Three Brothers');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(508, 31, 'EA', 'Eagle Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(509, 31, 'DI', 'Danger Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(510, 31, 'EG', 'Egmont Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(511, 31, 'DG', 'Diego Garcia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(512, 32, 'BE', 'Belait');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(513, 32, 'BM', 'Brunei-Muara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(514, 32, 'TE', 'Temburong');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(515, 32, 'TU', 'Tutong');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(516, 33, '01', 'Blagoevgrad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(517, 33, '02', 'Burgas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(518, 33, '03', 'Varna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(519, 33, '04', 'Veliko Tarnovo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(520, 33, '05', 'Vidin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(521, 33, '06', 'Vratsa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(522, 33, '07', 'Gabrovo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(523, 33, '08', 'Dobrich');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(524, 33, '09', 'Kardzhali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(525, 33, '10', 'Kyustendil');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(526, 33, '11', 'Lovech');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(527, 33, '12', 'Montana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(528, 33, '13', 'Pazardzhik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(529, 33, '14', 'Pernik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(530, 33, '15', 'Pleven');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(531, 33, '16', 'Plovdiv');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(532, 33, '17', 'Razgrad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(533, 33, '18', 'Ruse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(534, 33, '19', 'Silistra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(535, 33, '20', 'Sliven');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(536, 33, '21', 'Smolyan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(537, 33, '23', 'Sofia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(538, 33, '22', 'Sofia Province');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(539, 33, '24', 'Stara Zagora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(540, 33, '25', 'Targovishte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(541, 33, '26', 'Haskovo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(542, 33, '27', 'Shumen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(543, 33, '28', 'Yambol');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(544, 34, 'BAL', 'Balé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(545, 34, 'BAM', 'Bam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(546, 34, 'BAN', 'Banwa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(547, 34, 'BAZ', 'Bazèga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(548, 34, 'BGR', 'Bougouriba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(549, 34, 'BLG', 'Boulgou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(550, 34, 'BLK', 'Boulkiemdé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(551, 34, 'COM', 'Komoé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(552, 34, 'GAN', 'Ganzourgou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(553, 34, 'GNA', 'Gnagna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(554, 34, 'GOU', 'Gourma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(555, 34, 'HOU', 'Houet');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(556, 34, 'IOB', 'Ioba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(557, 34, 'KAD', 'Kadiogo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(558, 34, 'KEN', 'Kénédougou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(559, 34, 'KMD', 'Komondjari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(560, 34, 'KMP', 'Kompienga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(561, 34, 'KOP', 'Koulpélogo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(562, 34, 'KOS', 'Kossi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(563, 34, 'KOT', 'Kouritenga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(564, 34, 'KOW', 'Kourwéogo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(565, 34, 'LER', 'Léraba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(566, 34, 'LOR', 'Loroum');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(567, 34, 'MOU', 'Mouhoun');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(568, 34, 'NAM', 'Namentenga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(569, 34, 'NAO', 'Naouri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(570, 34, 'NAY', 'Nayala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(571, 34, 'NOU', 'Noumbiel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(572, 34, 'OUB', 'Oubritenga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(573, 34, 'OUD', 'Oudalan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(574, 34, 'PAS', 'Passoré');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(575, 34, 'PON', 'Poni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(576, 34, 'SEN', 'Séno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(577, 34, 'SIS', 'Sissili');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(578, 34, 'SMT', 'Sanmatenga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(579, 34, 'SNG', 'Sanguié');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(580, 34, 'SOM', 'Soum');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(581, 34, 'SOR', 'Sourou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(582, 34, 'TAP', 'Tapoa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(583, 34, 'TUI', 'Tui');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(584, 34, 'YAG', 'Yagha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(585, 34, 'YAT', 'Yatenga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(586, 34, 'ZIR', 'Ziro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(587, 34, 'ZON', 'Zondoma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(588, 34, 'ZOU', 'Zoundwéogo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(589, 35, 'BB', 'Bubanza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(590, 35, 'BJ', 'Bujumbura Mairie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(591, 35, 'BR', 'Bururi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(592, 35, 'CA', 'Cankuzo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(593, 35, 'CI', 'Cibitoke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(594, 35, 'GI', 'Gitega');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(595, 35, 'KR', 'Karuzi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(596, 35, 'KY', 'Kayanza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(597, 35, 'KI', 'Kirundo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(598, 35, 'MA', 'Makamba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(599, 35, 'MU', 'Muramvya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(600, 35, 'MY', 'Muyinga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(601, 35, 'MW', 'Mwaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(602, 35, 'NG', 'Ngozi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(603, 35, 'RT', 'Rutana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(604, 35, 'RY', 'Ruyigi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(605, 37, 'AD', 'Adamaoua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(606, 37, 'CE', 'Centre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(607, 37, 'EN', 'Extrême-Nord');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(608, 37, 'ES', 'Est');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(609, 37, 'LT', 'Littoral');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(610, 37, 'NO', 'Nord');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(611, 37, 'NW', 'Nord-Ouest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(612, 37, 'OU', 'Ouest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(613, 37, 'SU', 'Sud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(614, 37, 'SW', 'Sud-Ouest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(615, 38, 'AB', 'Alberta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(616, 38, 'BC', 'British Columbia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(617, 38, 'MB', 'Manitoba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(618, 38, 'NB', 'New Brunswick');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(619, 38, 'NL', 'Newfoundland and Labrador');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(620, 38, 'NS', 'Nova Scotia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(621, 38, 'NT', 'Northwest Territories');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(622, 38, 'NU', 'Nunavut');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(623, 38, 'ON', 'Ontario');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(624, 38, 'PE', 'Prince Edward Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(625, 38, 'QC', 'Quebec');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(626, 38, 'SK', 'Saskatchewan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(627, 38, 'YT', 'Yukon Territory');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(628, 39, 'BR', 'Brava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(629, 39, 'BV', 'Boa Vista');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(630, 39, 'CA', 'Santa Catarina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(631, 39, 'CR', 'Santa Cruz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(632, 39, 'CS', 'Calheta de São Miguel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(633, 39, 'MA', 'Maio');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(634, 39, 'MO', 'Mosteiros');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(635, 39, 'PA', 'Paúl');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(636, 39, 'PN', 'Porto Novo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(637, 39, 'PR', 'Praia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(638, 39, 'RG', 'Ribeira Grande');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(639, 39, 'SD', 'São Domingos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(640, 39, 'SF', 'São Filipe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(641, 39, 'SL', 'Sal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(642, 39, 'SN', 'São Nicolau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(643, 39, 'SV', 'São Vicente');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(644, 39, 'TA', 'Tarrafal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(645, 40, 'CR', 'Creek');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(646, 40, 'EA', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(647, 40, 'MI', 'Midland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(648, 40, 'SO', 'South Town');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(649, 40, 'SP', 'Spot Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(650, 40, 'ST', 'Stake Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(651, 40, 'WD', 'West End');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(652, 40, 'WN', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(653, 41, 'AC ', 'Ouham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(654, 41, 'BB ', 'Bamingui-Bangoran');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(655, 41, 'BGF', 'Bangui');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(656, 41, 'BK ', 'Basse-Kotto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(657, 41, 'HK ', 'Haute-Kotto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(658, 41, 'HM ', 'Haut-Mbomou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(659, 41, 'HS ', 'Mambéré-Kadéï');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(660, 41, 'KB ', 'Nana-Grébizi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(661, 41, 'KG ', 'Kémo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(662, 41, 'LB ', 'Lobaye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(663, 41, 'MB ', 'Mbomou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(664, 41, 'MP ', 'Ombella-M''Poko');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(665, 41, 'NM ', 'Nana-Mambéré');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(666, 41, 'OP ', 'Ouham-Pendé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(667, 41, 'SE ', 'Sangha-Mbaéré');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(668, 41, 'UK ', 'Ouaka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(669, 41, 'VR ', 'Vakaga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(670, 42, 'BA ', 'Batha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(671, 42, 'BET', 'Borkou-Ennedi-Tibesti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(672, 42, 'BI ', 'Biltine');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(673, 42, 'CB ', 'Chari-Baguirmi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(674, 42, 'GR ', 'Guéra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(675, 42, 'KA ', 'Kanem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(676, 42, 'LC ', 'Lac');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(677, 42, 'LR ', 'Logone-Oriental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(678, 42, 'LO ', 'Logone-Occidental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(679, 42, 'MC ', 'Moyen-Chari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(680, 42, 'MK ', 'Mayo-Kébbi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(681, 42, 'OD ', 'Ouaddaï');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(682, 42, 'SA ', 'Salamat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(683, 42, 'TA ', 'Tandjilé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(684, 43, 'AI', 'Aisén del General Carlos Ibañez');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(685, 43, 'AN', 'Antofagasta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(686, 43, 'AR', 'La Araucanía');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(687, 43, 'AT', 'Atacama');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(688, 43, 'BI', 'Biobío');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(689, 43, 'CO', 'Coquimbo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(690, 43, 'LI', 'Libertador Bernardo O''Higgins');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(691, 43, 'LL', 'Los Lagos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(692, 43, 'MA', 'Magallanes y de la Antartica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(693, 43, 'ML', 'Maule');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(694, 43, 'RM', 'Metropolitana de Santiago');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(695, 43, 'TA', 'Tarapacá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(696, 43, 'VS', 'Valparaíso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(697, 44, '11', '北京');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(698, 44, '12', '天津');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(699, 44, '13', '河北');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(700, 44, '14', '山西');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(701, 44, '15', '内蒙�?�自治区');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(702, 44, '21', '辽�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(703, 44, '22', '�?�林');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(704, 44, '23', '黑龙江�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(705, 44, '31', '上海');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(706, 44, '32', '江�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(707, 44, '33', '浙江');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(708, 44, '34', '安徽');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(709, 44, '35', '�?建');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(710, 44, '36', '江西');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(711, 44, '37', '山东');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(712, 44, '41', '河�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(713, 44, '42', '湖北');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(714, 44, '43', '湖�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(715, 44, '44', '广东');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(716, 44, '45', '广西壮�?自治区');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(717, 44, '46', '海�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(718, 44, '50', '�?庆');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(719, 44, '51', '四�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(720, 44, '52', '贵州');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(721, 44, '53', '云�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(722, 44, '54', '西�?自治区');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(723, 44, '61', '陕西');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(724, 44, '62', '甘肃');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(725, 44, '63', '�?�海');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(726, 44, '64', '�?�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(727, 44, '65', '新疆');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(728, 44, '71', '臺�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(729, 44, '91', '香港');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(730, 44, '92', '澳門');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(731, 46, 'D', 'Direction Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(732, 46, 'H', 'Home Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(733, 46, 'O', 'Horsburgh Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(734, 46, 'S', 'South Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(735, 46, 'W', 'West Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(736, 47, 'AMA', 'Amazonas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(737, 47, 'ANT', 'Antioquia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(738, 47, 'ARA', 'Arauca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(739, 47, 'ATL', 'Atlántico');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(740, 47, 'BOL', 'Bolívar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(741, 47, 'BOY', 'Boyacá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(742, 47, 'CAL', 'Caldas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(743, 47, 'CAQ', 'Caquetá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(744, 47, 'CAS', 'Casanare');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(745, 47, 'CAU', 'Cauca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(746, 47, 'CES', 'Cesar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(747, 47, 'CHO', 'Chocó');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(748, 47, 'COR', 'Córdoba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(749, 47, 'CUN', 'Cundinamarca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(750, 47, 'DC', 'Bogotá Distrito Capital');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(751, 47, 'GUA', 'Guainía');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(752, 47, 'GUV', 'Guaviare');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(753, 47, 'HUI', 'Huila');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(754, 47, 'LAG', 'La Guajira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(755, 47, 'MAG', 'Magdalena');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(756, 47, 'MET', 'Meta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(757, 47, 'NAR', 'Nariño');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(758, 47, 'NSA', 'Norte de Santander');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(759, 47, 'PUT', 'Putumayo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(760, 47, 'QUI', 'Quindío');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(761, 47, 'RIS', 'Risaralda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(762, 47, 'SAN', 'Santander');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(763, 47, 'SAP', 'San Andrés y Providencia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(764, 47, 'SUC', 'Sucre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(765, 47, 'TOL', 'Tolima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(766, 47, 'VAC', 'Valle del Cauca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(767, 47, 'VAU', 'Vaupés');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(768, 47, 'VID', 'Vichada');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(769, 48, 'A', 'Anjouan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(770, 48, 'G', 'Grande Comore');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(771, 48, 'M', 'Mohéli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(772, 49, 'BC', 'Congo-Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(773, 49, 'BN', 'Bandundu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(774, 49, 'EQ', 'Équateur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(775, 49, 'KA', 'Katanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(776, 49, 'KE', 'Kasai-Oriental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(777, 49, 'KN', 'Kinshasa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(778, 49, 'KW', 'Kasai-Occidental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(779, 49, 'MA', 'Maniema');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(780, 49, 'NK', 'Nord-Kivu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(781, 49, 'OR', 'Orientale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(782, 49, 'SK', 'Sud-Kivu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(783, 50, 'PU', 'Pukapuka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(784, 50, 'RK', 'Rakahanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(785, 50, 'MK', 'Manihiki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(786, 50, 'PE', 'Penrhyn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(787, 50, 'NI', 'Nassau Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(788, 50, 'SU', 'Surwarrow');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(789, 50, 'PA', 'Palmerston');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(790, 50, 'AI', 'Aitutaki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(791, 50, 'MA', 'Manuae');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(792, 50, 'TA', 'Takutea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(793, 50, 'MT', 'Mitiaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(794, 50, 'AT', 'Atiu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(795, 50, 'MU', 'Mauke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(796, 50, 'RR', 'Rarotonga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(797, 50, 'MG', 'Mangaia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(798, 51, 'A', 'Alajuela');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(799, 51, 'C', 'Cartago');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(800, 51, 'G', 'Guanacaste');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(801, 51, 'H', 'Heredia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(802, 51, 'L', 'Limón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(803, 51, 'P', 'Puntarenas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(804, 51, 'SJ', 'San José');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(805, 52, '01', 'Lagunes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(806, 52, '02', 'Haut-Sassandra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(807, 52, '03', 'Savanes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(808, 52, '04', 'Vallée du Bandama');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(809, 52, '05', 'Moyen-Comoé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(810, 52, '06', 'Dix-Huit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(811, 52, '07', 'Lacs');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(812, 52, '08', 'Zanzan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(813, 52, '09', 'Bas-Sassandra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(814, 52, '10', 'Denguélé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(815, 52, '11', 'N''zi-Comoé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(816, 52, '12', 'Marahoué');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(817, 52, '13', 'Sud-Comoé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(818, 52, '14', 'Worodouqou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(819, 52, '15', 'Sud-Bandama');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(820, 52, '16', 'Agnébi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(821, 52, '17', 'Bafing');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(822, 52, '18', 'Fromager');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(823, 52, '19', 'Moyen-Cavally');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(824, 53, '01', 'Zagreba�?ka županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(825, 53, '02', 'Krapinsko-zagorska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(826, 53, '03', 'Sisa�?ko-moslava�?ka županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(827, 53, '04', 'Karlova�?ka županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(828, 53, '05', 'Varaždinska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(829, 53, '06', 'Koprivni�?ko-križeva�?ka županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(830, 53, '07', 'Bjelovarsko-bilogorska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(831, 53, '08', 'Primorsko-goranska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(832, 53, '09', 'Li�?ko-senjska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(833, 53, '10', 'Viroviti�?ko-podravska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(834, 53, '11', 'Požeško-slavonska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(835, 53, '12', 'Brodsko-posavska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(836, 53, '13', 'Zadarska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(837, 53, '14', 'Osje�?ko-baranjska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(838, 53, '15', 'Šibensko-kninska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(839, 53, '16', 'Vukovarsko-srijemska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(840, 53, '17', 'Splitsko-dalmatinska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(841, 53, '18', 'Istarska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(842, 53, '19', 'Dubrova�?ko-neretvanska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(843, 53, '20', 'Međimurska županija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(844, 53, '21', 'Zagreb');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(845, 54, '01', 'Pinar del Río');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(846, 54, '02', 'La Habana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(847, 54, '03', 'Ciudad de La Habana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(848, 54, '04', 'Matanzas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(849, 54, '05', 'Villa Clara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(850, 54, '06', 'Cienfuegos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(851, 54, '07', 'Sancti Spíritus');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(852, 54, '08', 'Ciego de �?vila');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(853, 54, '09', 'Camagüey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(854, 54, '10', 'Las Tunas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(855, 54, '11', 'Holguín');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(856, 54, '12', 'Granma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(857, 54, '13', 'Santiago de Cuba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(858, 54, '14', 'Guantánamo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(859, 54, '99', 'Isla de la Juventud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(860, 55, '01', 'Κε�?�?vεια');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(861, 55, '02', 'Λευκωσία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(862, 55, '03', 'Αμμόχωστος');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(863, 55, '04', 'Λά�?νακα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(864, 55, '05', 'Λεμεσός');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(865, 55, '06', 'Πάφος');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(866, 56, 'JC', 'Jiho�?eský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(867, 56, 'JM', 'Jihomoravský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(868, 56, 'KA', 'Karlovarský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(869, 56, 'VY', 'Vyso�?ina kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(870, 56, 'KR', 'Královéhradecký kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(871, 56, 'LI', 'Liberecký kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(872, 56, 'MO', 'Moravskoslezský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(873, 56, 'OL', 'Olomoucký kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(874, 56, 'PA', 'Pardubický kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(875, 56, 'PL', 'Plzeňský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(876, 56, 'PR', 'Hlavní město Praha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(877, 56, 'ST', 'Středo�?eský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(878, 56, 'US', 'Ústecký kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(879, 56, 'ZL', 'Zlínský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(880, 57, '040', 'Bornholms Regionskommune');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(881, 57, '101', 'København');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(882, 57, '147', 'Frederiksberg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(883, 57, '070', 'Århus Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(884, 57, '015', 'Københavns Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(885, 57, '020', 'Frederiksborg Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(886, 57, '042', 'Fyns Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(887, 57, '080', 'Nordjyllands Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(888, 57, '055', 'Ribe Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(889, 57, '065', 'Ringkjøbing Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(890, 57, '025', 'Roskilde Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(891, 57, '050', 'Sønderjyllands Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(892, 57, '035', 'Storstrøms Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(893, 57, '060', 'Vejle Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(894, 57, '030', 'Vestsjællands Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(895, 57, '076', 'Viborg Amt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(896, 58, 'AS', 'Region d''Ali Sabieh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(897, 58, 'AR', 'Region d''Arta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(898, 58, 'DI', 'Region de Dikhil');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(899, 58, 'DJ', 'Ville de Djibouti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(900, 58, 'OB', 'Region d''Obock');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(901, 58, 'TA', 'Region de Tadjourah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(902, 59, 'AND', 'Saint Andrew Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(903, 59, 'DAV', 'Saint David Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(904, 59, 'GEO', 'Saint George Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(905, 59, 'JOH', 'Saint John Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(906, 59, 'JOS', 'Saint Joseph Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(907, 59, 'LUK', 'Saint Luke Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(908, 59, 'MAR', 'Saint Mark Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(909, 59, 'PAT', 'Saint Patrick Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(910, 59, 'PAU', 'Saint Paul Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(911, 59, 'PET', 'Saint Peter Parish');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(912, 60, '01', 'Distrito Nacional');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(913, 60, '02', '�?zua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(914, 60, '03', 'Baoruco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(915, 60, '04', 'Barahona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(916, 60, '05', 'Dajabón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(917, 60, '06', 'Duarte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(918, 60, '07', 'Elías Piña');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(919, 60, '08', 'El Seibo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(920, 60, '09', 'Espaillat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(921, 60, '10', 'Independencia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(922, 60, '11', 'La Altagracia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(923, 60, '12', 'La Romana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(924, 60, '13', 'La Vega');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(925, 60, '14', 'María Trinidad Sánchez');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(926, 60, '15', 'Monte Cristi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(927, 60, '16', 'Pedernales');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(928, 60, '17', 'Peravia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(929, 60, '18', 'Puerto Plata');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(930, 60, '19', 'Salcedo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(931, 60, '20', 'Samaná');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(932, 60, '21', 'San Cristóbal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(933, 60, '22', 'San Juan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(934, 60, '23', 'San Pedro de Macorís');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(935, 60, '24', 'Sánchez Ramírez');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(936, 60, '25', 'Santiago');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(937, 60, '26', 'Santiago Rodríguez');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(938, 60, '27', 'Valverde');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(939, 60, '28', 'Monseñor Nouel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(940, 60, '29', 'Monte Plata');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(941, 60, '30', 'Hato Mayor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(942, 61, 'AL', 'Aileu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(943, 61, 'AN', 'Ainaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(944, 61, 'BA', 'Baucau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(945, 61, 'BO', 'Bobonaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(946, 61, 'CO', 'Cova-Lima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(947, 61, 'DI', 'Dili');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(948, 61, 'ER', 'Ermera');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(949, 61, 'LA', 'Lautem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(950, 61, 'LI', 'Liquiçá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(951, 61, 'MF', 'Manufahi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(952, 61, 'MT', 'Manatuto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(953, 61, 'OE', 'Oecussi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(954, 61, 'VI', 'Viqueque');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(955, 62, 'A', 'Azuay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(956, 62, 'B', 'Bolívar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(957, 62, 'C', 'Carchi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(958, 62, 'D', 'Orellana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(959, 62, 'E', 'Esmeraldas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(960, 62, 'F', 'Cañar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(961, 62, 'G', 'Guayas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(962, 62, 'H', 'Chimborazo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(963, 62, 'I', 'Imbabura');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(964, 62, 'L', 'Loja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(965, 62, 'M', 'Manabí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(966, 62, 'N', 'Napo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(967, 62, 'O', 'El Oro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(968, 62, 'P', 'Pichincha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(969, 62, 'R', 'Los Ríos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(970, 62, 'S', 'Morona-Santiago');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(971, 62, 'T', 'Tungurahua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(972, 62, 'U', 'Sucumbíos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(973, 62, 'W', 'Galápagos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(974, 62, 'X', 'Cotopaxi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(975, 62, 'Y', 'Pastaza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(976, 62, 'Z', 'Zamora-Chinchipe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(977, 63, 'ALX', 'الإسكندرية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(978, 63, 'ASN', 'أسوان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(979, 63, 'AST', 'أسيوط');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(980, 63, 'BA', 'البحر الأحمر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(981, 63, 'BH', 'البحيرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(982, 63, 'BNS', 'بني سوي�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(983, 63, 'C', 'القاهرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(984, 63, 'DK', 'الدقهلية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(985, 63, 'DT', 'دمياط');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(986, 63, 'FYM', 'ال�?يوم');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(987, 63, 'GH', 'الغربية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(988, 63, 'GZ', 'الجيزة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(989, 63, 'IS', 'الإسماعيلية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(990, 63, 'JS', 'جنوب سيناء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(991, 63, 'KB', 'القليوبية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(992, 63, 'KFS', 'ك�?ر الشيخ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(993, 63, 'KN', 'قنا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(994, 63, 'MN', 'محا�?ظة المنيا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(995, 63, 'MNF', 'المنو�?ية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(996, 63, 'MT', 'مطروح');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(997, 63, 'PTS', 'محا�?ظة بور سعيد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(998, 63, 'SHG', 'محا�?ظة سوهاج');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(999, 63, 'SHR', 'المحا�?ظة الشرقيّة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1000, 63, 'SIN', 'شمال سيناء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1001, 63, 'SUZ', 'السويس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1002, 63, 'WAD', 'الوادى الجديد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1003, 64, 'AH', 'Ahuachapán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1004, 64, 'CA', 'Cabañas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1005, 64, 'CH', 'Chalatenango');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1006, 64, 'CU', 'Cuscatlán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1007, 64, 'LI', 'La Libertad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1008, 64, 'MO', 'Morazán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1009, 64, 'PA', 'La Paz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1010, 64, 'SA', 'Santa Ana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1011, 64, 'SM', 'San Miguel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1012, 64, 'SO', 'Sonsonate');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1013, 64, 'SS', 'San Salvador');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1014, 64, 'SV', 'San Vicente');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1015, 64, 'UN', 'La Unión');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1016, 64, 'US', 'Usulután');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1017, 65, 'AN', 'Annobón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1018, 65, 'BN', 'Bioko Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1019, 65, 'BS', 'Bioko Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1020, 65, 'CS', 'Centro Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1021, 65, 'KN', 'Kié-Ntem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1022, 65, 'LI', 'Litoral');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1023, 65, 'WN', 'Wele-Nzas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1024, 66, 'AN', 'Zoba Anseba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1025, 66, 'DK', 'Zoba Debubawi Keyih Bahri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1026, 66, 'DU', 'Zoba Debub');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1027, 66, 'GB', 'Zoba Gash-Barka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1028, 66, 'MA', 'Zoba Ma''akel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1029, 66, 'SK', 'Zoba Semienawi Keyih Bahri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1030, 67, '37', 'Harju maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1031, 67, '39', 'Hiiu maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1032, 67, '44', 'Ida-Viru maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1033, 67, '49', 'Jõgeva maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1034, 67, '51', 'Järva maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1035, 67, '57', 'Lääne maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1036, 67, '59', 'Lääne-Viru maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1037, 67, '65', 'Põlva maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1038, 67, '67', 'Pärnu maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1039, 67, '70', 'Rapla maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1040, 67, '74', 'Saare maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1041, 67, '78', 'Tartu maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1042, 67, '82', 'Valga maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1043, 67, '84', 'Viljandi maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1044, 67, '86', 'Võru maakond');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1045, 68, 'AA', 'አዲስ አበባ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1046, 68, 'AF', 'አ�?�ር');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1047, 68, 'AH', 'አማራ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1048, 68, 'BG', 'ቤንሻንጉ�?-ጉ�?�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1049, 68, 'DD', 'ድሬዳዋ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1050, 68, 'GB', 'ጋ�?ቤላ ሕ�?ቦች');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1051, 68, 'HR', 'ሀረሪ ሕ�?ብ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1052, 68, 'OR', 'ኦሮሚያ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1053, 68, 'SM', 'ሶማሌ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1054, 68, 'SN', 'ደቡብ ብሔሮች ብሔረሰቦችና ሕ�?ቦች');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1055, 68, 'TG', 'ት�?ራይ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1056, 71, 'C', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1057, 71, 'E', 'Northern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1058, 71, 'N', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1059, 71, 'R', 'Rotuma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1060, 71, 'W', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1061, 72, 'AL', 'Ahvenanmaan maakunta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1062, 72, 'ES', 'Etelä-Suomen lääni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1063, 72, 'IS', 'Itä-Suomen lääni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1064, 72, 'LL', 'Lapin lääni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1065, 72, 'LS', 'Länsi-Suomen lääni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1066, 72, 'OL', 'Oulun lääni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1067, 73, '01', 'Ain');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1068, 73, '02', 'Aisne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1069, 73, '03', 'Allier');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1070, 73, '04', 'Alpes-de-Haute-Provence');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1071, 73, '05', 'Hautes-Alpes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1072, 73, '06', 'Alpes-Maritimes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1073, 73, '07', 'Ardèche');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1074, 73, '08', 'Ardennes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1075, 73, '09', 'Ariège');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1076, 73, '10', 'Aube');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1077, 73, '11', 'Aude');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1078, 73, '12', 'Aveyron');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1079, 73, '13', 'Bouches-du-Rhône');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1080, 73, '14', 'Calvados');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1081, 73, '15', 'Cantal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1082, 73, '16', 'Charente');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1083, 73, '17', 'Charente-Maritime');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1084, 73, '18', 'Cher');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1085, 73, '19', 'Corrèze');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1086, 73, '21', 'Côte-d''Or');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1087, 73, '22', 'Côtes-d''Armor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1088, 73, '23', 'Creuse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1089, 73, '24', 'Dordogne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1090, 73, '25', 'Doubs');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1091, 73, '26', 'Drôme');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1092, 73, '27', 'Eure');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1093, 73, '28', 'Eure-et-Loir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1094, 73, '29', 'Finistère');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1095, 73, '2A', 'Corse-du-Sud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1096, 73, '2B', 'Haute-Corse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1097, 73, '30', 'Gard');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1098, 73, '31', 'Haute-Garonne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1099, 73, '32', 'Gers');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1100, 73, '33', 'Gironde');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1101, 73, '34', 'Hérault');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1102, 73, '35', 'Ille-et-Vilaine');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1103, 73, '36', 'Indre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1104, 73, '37', 'Indre-et-Loire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1105, 73, '38', 'Isère');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1106, 73, '39', 'Jura');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1107, 73, '40', 'Landes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1108, 73, '41', 'Loir-et-Cher');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1109, 73, '42', 'Loire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1110, 73, '43', 'Haute-Loire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1111, 73, '44', 'Loire-Atlantique');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1112, 73, '45', 'Loiret');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1113, 73, '46', 'Lot');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1114, 73, '47', 'Lot-et-Garonne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1115, 73, '48', 'Lozère');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1116, 73, '49', 'Maine-et-Loire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1117, 73, '50', 'Manche');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1118, 73, '51', 'Marne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1119, 73, '52', 'Haute-Marne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1120, 73, '53', 'Mayenne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1121, 73, '54', 'Meurthe-et-Moselle');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1122, 73, '55', 'Meuse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1123, 73, '56', 'Morbihan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1124, 73, '57', 'Moselle');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1125, 73, '58', 'Nièvre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1126, 73, '59', 'Nord');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1127, 73, '60', 'Oise');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1128, 73, '61', 'Orne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1129, 73, '62', 'Pas-de-Calais');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1130, 73, '63', 'Puy-de-Dôme');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1131, 73, '64', 'Pyrénées-Atlantiques');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1132, 73, '65', 'Hautes-Pyrénées');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1133, 73, '66', 'Pyrénées-Orientales');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1134, 73, '67', 'Bas-Rhin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1135, 73, '68', 'Haut-Rhin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1136, 73, '69', 'Rhône');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1137, 73, '70', 'Haute-Saône');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1138, 73, '71', 'Saône-et-Loire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1139, 73, '72', 'Sarthe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1140, 73, '73', 'Savoie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1141, 73, '74', 'Haute-Savoie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1142, 73, '75', 'Paris');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1143, 73, '76', 'Seine-Maritime');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1144, 73, '77', 'Seine-et-Marne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1145, 73, '78', 'Yvelines');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1146, 73, '79', 'Deux-Sèvres');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1147, 73, '80', 'Somme');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1148, 73, '81', 'Tarn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1149, 73, '82', 'Tarn-et-Garonne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1150, 73, '83', 'Var');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1151, 73, '84', 'Vaucluse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1152, 73, '85', 'Vendée');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1153, 73, '86', 'Vienne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1154, 73, '87', 'Haute-Vienne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1155, 73, '88', 'Vosges');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1156, 73, '89', 'Yonne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1157, 73, '90', 'Territoire de Belfort');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1158, 73, '91', 'Essonne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1159, 73, '92', 'Hauts-de-Seine');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1160, 73, '93', 'Seine-Saint-Denis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1161, 73, '94', 'Val-de-Marne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1162, 73, '95', 'Val-d''Oise');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1163, 73, 'NC', 'Territoire des Nouvelle-Calédonie et Dependances');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1164, 73, 'PF', 'Polynésie Française');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1165, 73, 'PM', 'Saint-Pierre et Miquelon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1166, 73, 'TF', 'Terres australes et antarctiques françaises');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1167, 73, 'YT', 'Mayotte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1168, 73, 'WF', 'Territoire des îles Wallis et Futuna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1169, 76, 'M', 'Archipel des Marquises');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1170, 76, 'T', 'Archipel des Tuamotu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1171, 76, 'I', 'Archipel des Tubuai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1172, 76, 'V', 'Iles du Vent');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1173, 76, 'S', 'Iles Sous-le-Vent ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1174, 77, 'C', 'Iles Crozet');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1175, 77, 'K', 'Iles Kerguelen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1176, 77, 'A', 'Ile Amsterdam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1177, 77, 'P', 'Ile Saint-Paul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1178, 77, 'D', 'Adelie Land');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1179, 78, 'ES', 'Estuaire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1180, 78, 'HO', 'Haut-Ogooue');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1181, 78, 'MO', 'Moyen-Ogooue');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1182, 78, 'NG', 'Ngounie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1183, 78, 'NY', 'Nyanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1184, 78, 'OI', 'Ogooue-Ivindo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1185, 78, 'OL', 'Ogooue-Lolo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1186, 78, 'OM', 'Ogooue-Maritime');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1187, 78, 'WN', 'Woleu-Ntem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1188, 79, 'AH', 'Ashanti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1189, 79, 'BA', 'Brong-Ahafo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1190, 79, 'CP', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1191, 79, 'EP', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1192, 79, 'AA', 'Greater Accra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1193, 79, 'NP', 'Northern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1194, 79, 'UE', 'Upper East');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1195, 79, 'UW', 'Upper West');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1196, 79, 'TV', 'Volta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1197, 79, 'WP', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1198, 80, 'AB', '�?ფხ�?ზეთი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1199, 80, 'AJ', '�?ჭ�?რ�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1200, 80, 'GU', 'გური�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1201, 80, 'IM', 'იმერეთი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1202, 80, 'KA', 'კ�?ხეთი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1203, 80, 'KK', 'ქვემ�? ქ�?რთლი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1204, 80, 'MM', 'მცხეთ�?-მთი�?ნეთი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1205, 80, 'RL', 'რ�?ჭ�?-ლეჩხუმი დ�? ქვემ�? სვ�?ნეთი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1206, 80, 'SJ', 'ს�?მცხე-ჯ�?ვ�?ხეთი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1207, 80, 'SK', 'შიდ�? ქ�?რთლი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1208, 80, 'SZ', 'ს�?მეგრელ�?-ზემ�? სვ�?ნეთი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1209, 80, 'TB', 'თბილისი');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1210, 81, 'BE', 'Berlin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1211, 81, 'BR', 'Brandenburg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1212, 81, 'BW', 'Baden-Württemberg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1213, 81, 'BY', 'Bayern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1214, 81, 'HB', 'Bremen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1215, 81, 'HE', 'Hessen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1216, 81, 'HH', 'Hamburg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1217, 81, 'MV', 'Mecklenburg-Vorpommern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1218, 81, 'NI', 'Niedersachsen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1219, 81, 'NW', 'Nordrhein-Westfalen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1220, 81, 'RP', 'Rheinland-Pfalz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1221, 81, 'SH', 'Schleswig-Holstein');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1222, 81, 'SL', 'Saarland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1223, 81, 'SN', 'Sachsen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1224, 81, 'ST', 'Sachsen-Anhalt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1225, 81, 'TH', 'Thüringen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1226, 82, 'AA', 'Greater Accra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1227, 82, 'AH', 'Ashanti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1228, 82, 'BA', 'Brong-Ahafo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1229, 82, 'CP', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1230, 82, 'EP', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1231, 82, 'NP', 'Northern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1232, 82, 'TV', 'Volta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1233, 82, 'UE', 'Upper East');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1234, 82, 'UW', 'Upper West');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1235, 82, 'WP', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1236, 84, '01', 'Αιτωλοακα�?νανία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1237, 84, '03', 'Βοιωτία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1238, 84, '04', 'Ε�?βοια');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1239, 84, '05', 'Ευ�?υτανία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1240, 84, '06', 'Φθιώτιδα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1241, 84, '07', 'Φωκίδα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1242, 84, '11', 'Α�?γολίδα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1243, 84, '12', 'Α�?καδία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1244, 84, '13', 'Ἀχα�?α');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1245, 84, '14', 'Ηλεία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1246, 84, '15', 'Κο�?ινθία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1247, 84, '16', 'Λακωνία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1248, 84, '17', 'Μεσσηνία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1249, 84, '21', 'Ζάκυνθος');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1250, 84, '22', 'Κέ�?κυ�?α');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1251, 84, '23', 'Κεφαλλονιά');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1252, 84, '24', 'Λευκάδα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1253, 84, '31', 'Ά�?τα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1254, 84, '32', 'Θεσπ�?ωτία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1255, 84, '33', 'Ιωάννινα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1256, 84, '34', 'Π�?εβεζα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1257, 84, '41', 'Κα�?δίτσα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1258, 84, '42', 'Λά�?ισα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1259, 84, '43', 'Μαγνησία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1260, 84, '44', 'Τ�?ίκαλα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1261, 84, '51', 'Γ�?εβενά');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1262, 84, '52', 'Δ�?άμα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1263, 84, '53', 'Ημαθία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1264, 84, '54', 'Θεσσαλονίκη');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1265, 84, '55', 'Καβάλα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1266, 84, '56', 'Καστο�?ιά');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1267, 84, '57', 'Κιλκίς');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1268, 84, '58', 'Κοζάνη');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1269, 84, '59', 'Πέλλα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1270, 84, '61', 'Πιε�?ία');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1271, 84, '62', 'Σε�?�?ών');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1272, 84, '63', 'Φλώ�?ινα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1273, 84, '64', 'Χαλκιδική');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1274, 84, '69', 'Ό�?ος Άθως');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1275, 84, '71', 'Έβ�?ος');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1276, 84, '72', 'Ξάνθη');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1277, 84, '73', 'Ροδόπη');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1278, 84, '81', 'Δωδεκάνησα');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1279, 84, '82', 'Κυκλάδες');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1280, 84, '83', 'Λέσβου');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1281, 84, '84', 'Σάμος');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1282, 84, '85', 'Χίος');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1283, 84, '91', 'Η�?άκλειο');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1284, 84, '92', 'Λασίθι');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1285, 84, '93', 'Ρεθ�?μνο');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1286, 84, '94', 'Χανίων');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1287, 84, 'A1', 'Αττική');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1288, 85, 'A', 'Avannaa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1289, 85, 'T', 'Tunu ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1290, 85, 'K', 'Kitaa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1291, 86, 'A', 'Saint Andrew');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1292, 86, 'D', 'Saint David');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1293, 86, 'G', 'Saint George');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1294, 86, 'J', 'Saint John');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1295, 86, 'M', 'Saint Mark');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1296, 86, 'P', 'Saint Patrick');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1297, 89, 'AV', 'Alta Verapaz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1298, 89, 'BV', 'Baja Verapaz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1299, 89, 'CM', 'Chimaltenango');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1300, 89, 'CQ', 'Chiquimula');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1301, 89, 'ES', 'Escuintla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1302, 89, 'GU', 'Guatemala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1303, 89, 'HU', 'Huehuetenango');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1304, 89, 'IZ', 'Izabal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1305, 89, 'JA', 'Jalapa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1306, 89, 'JU', 'Jutiapa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1307, 89, 'PE', 'El Petén');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1308, 89, 'PR', 'El Progreso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1309, 89, 'QC', 'El Quiché');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1310, 89, 'QZ', 'Quetzaltenango');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1311, 89, 'RE', 'Retalhuleu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1312, 89, 'SA', 'Sacatepéquez');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1313, 89, 'SM', 'San Marcos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1314, 89, 'SO', 'Sololá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1315, 89, 'SR', 'Santa Rosa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1316, 89, 'SU', 'Suchitepéquez');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1317, 89, 'TO', 'Totonicapán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1318, 89, 'ZA', 'Zacapa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1319, 90, 'BE', 'Beyla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1320, 90, 'BF', 'Boffa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1321, 90, 'BK', 'Boké');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1322, 90, 'CO', 'Coyah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1323, 90, 'DB', 'Dabola');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1324, 90, 'DI', 'Dinguiraye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1325, 90, 'DL', 'Dalaba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1326, 90, 'DU', 'Dubréka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1327, 90, 'FA', 'Faranah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1328, 90, 'FO', 'Forécariah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1329, 90, 'FR', 'Fria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1330, 90, 'GA', 'Gaoual');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1331, 90, 'GU', 'Guékédou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1332, 90, 'KA', 'Kankan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1333, 90, 'KB', 'Koubia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1334, 90, 'KD', 'Kindia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1335, 90, 'KE', 'Kérouané');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1336, 90, 'KN', 'Koundara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1337, 90, 'KO', 'Kouroussa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1338, 90, 'KS', 'Kissidougou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1339, 90, 'LA', 'Labé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1340, 90, 'LE', 'Lélouma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1341, 90, 'LO', 'Lola');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1342, 90, 'MC', 'Macenta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1343, 90, 'MD', 'Mandiana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1344, 90, 'ML', 'Mali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1345, 90, 'MM', 'Mamou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1346, 90, 'NZ', 'Nzérékoré');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1347, 90, 'PI', 'Pita');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1348, 90, 'SI', 'Siguiri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1349, 90, 'TE', 'Télimélé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1350, 90, 'TO', 'Tougué');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1351, 90, 'YO', 'Yomou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1352, 91, 'BF', 'Bafata');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1353, 91, 'BB', 'Biombo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1354, 91, 'BS', 'Bissau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1355, 91, 'BL', 'Bolama');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1356, 91, 'CA', 'Cacheu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1357, 91, 'GA', 'Gabu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1358, 91, 'OI', 'Oio');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1359, 91, 'QU', 'Quinara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1360, 91, 'TO', 'Tombali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1361, 92, 'BA', 'Barima-Waini');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1362, 92, 'CU', 'Cuyuni-Mazaruni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1363, 92, 'DE', 'Demerara-Mahaica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1364, 92, 'EB', 'East Berbice-Corentyne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1365, 92, 'ES', 'Essequibo Islands-West Demerara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1366, 92, 'MA', 'Mahaica-Berbice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1367, 92, 'PM', 'Pomeroon-Supenaam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1368, 92, 'PT', 'Potaro-Siparuni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1369, 92, 'UD', 'Upper Demerara-Berbice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1370, 92, 'UT', 'Upper Takutu-Upper Essequibo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1371, 93, 'AR', 'Artibonite');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1372, 93, 'CE', 'Centre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1373, 93, 'GA', 'Grand''Anse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1374, 93, 'NI', 'Nippes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1375, 93, 'ND', 'Nord');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1376, 93, 'NE', 'Nord-Est');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1377, 93, 'NO', 'Nord-Ouest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1378, 93, 'OU', 'Ouest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1379, 93, 'SD', 'Sud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1380, 93, 'SE', 'Sud-Est');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1381, 94, 'F', 'Flat Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1382, 94, 'M', 'McDonald Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1383, 94, 'S', 'Shag Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1384, 94, 'H', 'Heard Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1385, 95, 'AT', 'Atlántida');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1386, 95, 'CH', 'Choluteca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1387, 95, 'CL', 'Colón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1388, 95, 'CM', 'Comayagua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1389, 95, 'CP', 'Copán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1390, 95, 'CR', 'Cortés');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1391, 95, 'EP', 'El Paraíso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1392, 95, 'FM', 'Francisco Morazán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1393, 95, 'GD', 'Gracias a Dios');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1394, 95, 'IB', 'Islas de la Bahía');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1395, 95, 'IN', 'Intibucá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1396, 95, 'LE', 'Lempira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1397, 95, 'LP', 'La Paz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1398, 95, 'OC', 'Ocotepeque');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1399, 95, 'OL', 'Olancho');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1400, 95, 'SB', 'Santa Bárbara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1401, 95, 'VA', 'Valle');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1402, 95, 'YO', 'Yoro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1403, 96, 'HCW', '中西�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1404, 96, 'HEA', '�?��?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1405, 96, 'HSO', '�?��?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1406, 96, 'HWC', '�?�仔�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1407, 96, 'KKC', '�?�?城�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1408, 96, 'KKT', '觀塘�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1409, 96, 'KSS', '深水埗�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1410, 96, 'KWT', '黃大仙�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1411, 96, 'KYT', '油尖旺�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1412, 96, 'NIS', '離島�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1413, 96, 'NKT', '葵�?��?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1414, 96, 'NNO', '北�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1415, 96, 'NSK', '西貢�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1416, 96, 'NST', '沙田�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1417, 96, 'NTP', '大埔�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1418, 96, 'NTW', '�?��?��?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1419, 96, 'NTM', '屯門�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1420, 96, 'NYL', '元朗�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1421, 97, 'BA', 'Baranya megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1422, 97, 'BC', 'Békéscsaba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1423, 97, 'BE', 'Békés megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1424, 97, 'BK', 'Bács-Kiskun megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1425, 97, 'BU', 'Budapest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1426, 97, 'BZ', 'Borsod-Abaúj-Zemplén megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1427, 97, 'CS', 'Csongrád megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1428, 97, 'DE', 'Debrecen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1429, 97, 'DU', 'Dunaújváros');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1430, 97, 'EG', 'Eger');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1431, 97, 'FE', 'Fejér megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1432, 97, 'GS', 'Győr-Moson-Sopron megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1433, 97, 'GY', 'Győr');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1434, 97, 'HB', 'Hajdú-Bihar megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1435, 97, 'HE', 'Heves megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1436, 97, 'HV', 'Hódmezővásárhely');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1437, 97, 'JN', 'Jász-Nagykun-Szolnok megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1438, 97, 'KE', 'Komárom-Esztergom megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1439, 97, 'KM', 'Kecskemét');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1440, 97, 'KV', 'Kaposvár');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1441, 97, 'MI', 'Miskolc');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1442, 97, 'NK', 'Nagykanizsa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1443, 97, 'NO', 'Nógrád megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1444, 97, 'NY', 'Nyíregyháza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1445, 97, 'PE', 'Pest megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1446, 97, 'PS', 'Pécs');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1447, 97, 'SD', 'Szeged');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1448, 97, 'SF', 'Székesfehérvár');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1449, 97, 'SH', 'Szombathely');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1450, 97, 'SK', 'Szolnok');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1451, 97, 'SN', 'Sopron');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1452, 97, 'SO', 'Somogy megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1453, 97, 'SS', 'Szekszárd');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1454, 97, 'ST', 'Salgótarján');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1455, 97, 'SZ', 'Szabolcs-Szatmár-Bereg megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1456, 97, 'TB', 'Tatabánya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1457, 97, 'TO', 'Tolna megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1458, 97, 'VA', 'Vas megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1459, 97, 'VE', 'Veszprém megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1460, 97, 'VM', 'Veszprém');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1461, 97, 'ZA', 'Zala megye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1462, 97, 'ZE', 'Zalaegerszeg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1463, 98, '1', 'Höfuðborgarsvæðið');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1464, 98, '2', 'Suðurnes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1465, 98, '3', 'Vesturland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1466, 98, '4', 'Vestfirðir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1467, 98, '5', 'Norðurland vestra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1468, 98, '6', 'Norðurland eystra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1469, 98, '7', 'Austfirðir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1470, 98, '8', 'Suðurland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1471, 99, 'IN-AN', 'अंडमान और निकोबार द�?वीप');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1472, 99, 'IN-AP', 'ఆంధ�?ర ప�?రదేశ�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1473, 99, 'IN-AR', 'अर�?णाचल प�?रदेश');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1474, 99, 'IN-AS', 'অসম');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1475, 99, 'IN-BR', 'बिहार');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1476, 99, 'IN-CH', 'चंडीगढ़');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1477, 99, 'IN-CT', 'छत�?तीसगढ़');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1478, 99, 'IN-DD', 'દમણ અને દિવ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1479, 99, 'IN-DL', 'दिल�?ली');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1480, 99, 'IN-DN', 'દાદરા અને નગર હવેલી');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1481, 99, 'IN-GA', 'गोंय');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1482, 99, 'IN-GJ', 'ગ�?જરાત');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1483, 99, 'IN-HP', 'हिमाचल प�?रदेश');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1484, 99, 'IN-HR', 'हरियाणा');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1485, 99, 'IN-JH', '�?ारखंड');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1486, 99, 'IN-JK', 'जम�?मू और कश�?मीर');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1487, 99, 'IN-KA', 'ಕನಾ೯ಟಕ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1488, 99, 'IN-KL', 'കേരളം');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1489, 99, 'IN-LD', 'ലക�?ഷദ�?വീപ�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1490, 99, 'IN-ML', 'मेघालय');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1491, 99, 'IN-MH', 'महाराष�?ट�?र');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1492, 99, 'IN-MN', 'मणिप�?र');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1493, 99, 'IN-MP', 'मध�?य प�?रदेश');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1494, 99, 'IN-MZ', 'मिज़ोरम');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1495, 99, 'IN-NL', 'नागालैंड');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1496, 99, 'IN-OR', 'उड़ीसा');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1497, 99, 'IN-PB', 'ਪੰਜਾਬ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1498, 99, 'IN-PY', 'ப�?த�?ச�?சேரி');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1499, 99, 'IN-RJ', 'राजस�?थान');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1500, 99, 'IN-SK', 'सिक�?किम');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1501, 99, 'IN-TN', 'தமிழ�? நாட�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1502, 99, 'IN-TR', 'ত�?রিপ�?রা');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1503, 99, 'IN-UL', 'उत�?तरांचल');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1504, 99, 'IN-UP', 'उत�?तर प�?रदेश');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1505, 99, 'IN-WB', 'পশ�?চিমবঙ�?গ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1506, 100, 'AC', 'Aceh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1507, 100, 'BA', 'Bali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1508, 100, 'BB', 'Bangka-Belitung');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1509, 100, 'BE', 'Bengkulu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1510, 100, 'BT', 'Banten');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1511, 100, 'GO', 'Gorontalo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1512, 100, 'IJ', 'Papua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1513, 100, 'JA', 'Jambi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1514, 100, 'JI', 'Jawa Timur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1515, 100, 'JK', 'Jakarta Raya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1516, 100, 'JR', 'Jawa Barat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1517, 100, 'JT', 'Jawa Tengah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1518, 100, 'KB', 'Kalimantan Barat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1519, 100, 'KI', 'Kalimantan Timur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1520, 100, 'KS', 'Kalimantan Selatan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1521, 100, 'KT', 'Kalimantan Tengah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1522, 100, 'LA', 'Lampung');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1523, 100, 'MA', 'Maluku');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1524, 100, 'MU', 'Maluku Utara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1525, 100, 'NB', 'Nusa Tenggara Barat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1526, 100, 'NT', 'Nusa Tenggara Timur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1527, 100, 'RI', 'Riau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1528, 100, 'SB', 'Sumatera Barat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1529, 100, 'SG', 'Sulawesi Tenggara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1530, 100, 'SL', 'Sumatera Selatan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1531, 100, 'SN', 'Sulawesi Selatan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1532, 100, 'ST', 'Sulawesi Tengah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1533, 100, 'SW', 'Sulawesi Utara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1534, 100, 'SU', 'Sumatera Utara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1535, 100, 'YO', 'Yogyakarta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1536, 101, '01', 'محا�?ظة آذربایجان شرقي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1537, 101, '02', 'محا�?ظة آذربایجان غربي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1538, 101, '03', 'محا�?ظة اردبیل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1539, 101, '04', 'محا�?ظة اص�?هان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1540, 101, '05', 'محا�?ظة ایلام');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1541, 101, '06', 'محا�?ظة بوشهر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1542, 101, '07', 'محا�?ظة طهران');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1543, 101, '08', 'محا�?ظة چهارمحل و بختیاري');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1544, 101, '09', 'محا�?ظة خراسان رضوي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1545, 101, '10', 'محا�?ظة خوزستان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1546, 101, '11', 'محا�?ظة زنجان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1547, 101, '12', 'محا�?ظة سمنان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1548, 101, '13', 'محا�?ظة سيستان وبلوتشستان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1549, 101, '14', 'محا�?ظة �?ارس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1550, 101, '15', 'محا�?ظة کرمان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1551, 101, '16', 'محا�?ظة کردستان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1552, 101, '17', 'محا�?ظة کرمانشاه');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1553, 101, '18', 'محا�?ظة کهکیلویه و بویر أحمد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1554, 101, '19', 'محا�?ظة گیلان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1555, 101, '20', 'محا�?ظة لرستان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1556, 101, '21', 'محا�?ظة مازندران');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1557, 101, '22', 'محا�?ظة مرکزي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1558, 101, '23', 'محا�?ظة هرمزگان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1559, 101, '24', 'محا�?ظة همدان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1560, 101, '25', 'محا�?ظة یزد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1561, 101, '26', 'محا�?ظة قم');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1562, 101, '27', 'محا�?ظة گلستان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1563, 101, '28', 'محا�?ظة قزوين');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1564, 102, 'AN', 'محا�?ظة الأنبار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1565, 102, 'AR', 'أربيل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1566, 102, 'BA', 'محا�?ظة البصرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1567, 102, 'BB', 'بابل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1568, 102, 'BG', 'محا�?ظة بغداد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1569, 102, 'DA', 'دهوك');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1570, 102, 'DI', 'ديالى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1571, 102, 'DQ', 'ذي قار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1572, 102, 'KA', 'كربلاء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1573, 102, 'MA', 'ميسان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1574, 102, 'MU', 'المثنى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1575, 102, 'NA', 'النج�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1576, 102, 'NI', 'نینوى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1577, 102, 'QA', 'القادسية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1578, 102, 'SD', 'صلاح الدين');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1579, 102, 'SW', 'محا�?ظة السليمانية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1580, 102, 'TS', 'التأمیم');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1581, 102, 'WA', 'واسط');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1582, 103, 'C', 'Corcaigh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1583, 103, 'CE', 'Contae an Chláir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1584, 103, 'CN', 'An Cabhán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1585, 103, 'CW', 'Ceatharlach');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1586, 103, 'D', 'Baile �?tha Cliath');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1587, 103, 'DL', 'Dún na nGall');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1588, 103, 'G', 'Gaillimh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1589, 103, 'KE', 'Cill Dara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1590, 103, 'KK', 'Cill Chainnigh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1591, 103, 'KY', 'Contae Chiarraí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1592, 103, 'LD', 'An Longfort');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1593, 103, 'LH', 'Contae Lú');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1594, 103, 'LK', 'Luimneach');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1595, 103, 'LM', 'Contae Liatroma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1596, 103, 'LS', 'Contae Laoise');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1597, 103, 'MH', 'Contae na Mí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1598, 103, 'MN', 'Muineachán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1599, 103, 'MO', 'Contae Mhaigh Eo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1600, 103, 'OY', 'Contae Uíbh Fhailí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1601, 103, 'RN', 'Ros Comáin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1602, 103, 'SO', 'Sligeach');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1603, 103, 'TA', 'Tiobraid �?rann');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1604, 103, 'WD', 'Port Lairge');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1605, 103, 'WH', 'Contae na hIarmhí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1606, 103, 'WW', 'Cill Mhantáin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1607, 103, 'WX', 'Loch Garman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1608, 104, 'D ', 'מחוז הדרו�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1609, 104, 'HA', 'מחוז חיפה');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1610, 104, 'JM', 'ירושלי�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1611, 104, 'M ', 'מחוז המרכז');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1612, 104, 'TA', 'תל �?ביב-יפו');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1613, 104, 'Z ', 'מחוז הצפון');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1614, 105, 'AG', 'Agrigento');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1615, 105, 'AL', 'Alessandria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1616, 105, 'AN', 'Ancona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1617, 105, 'AO', 'Valle d''Aosta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1618, 105, 'AP', 'Ascoli Piceno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1619, 105, 'AQ', 'L''Aquila');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1620, 105, 'AR', 'Arezzo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1621, 105, 'AT', 'Asti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1622, 105, 'AV', 'Avellino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1623, 105, 'BA', 'Bari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1624, 105, 'BG', 'Bergamo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1625, 105, 'BI', 'Biella');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1626, 105, 'BL', 'Belluno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1627, 105, 'BN', 'Benevento');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1628, 105, 'BO', 'Bologna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1629, 105, 'BR', 'Brindisi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1630, 105, 'BS', 'Brescia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1631, 105, 'BT', 'Barletta-Andria-Trani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1632, 105, 'BZ', 'Alto Adige');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1633, 105, 'CA', 'Cagliari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1634, 105, 'CB', 'Campobasso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1635, 105, 'CE', 'Caserta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1636, 105, 'CH', 'Chieti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1637, 105, 'CI', 'Carbonia-Iglesias');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1638, 105, 'CL', 'Caltanissetta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1639, 105, 'CN', 'Cuneo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1640, 105, 'CO', 'Como');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1641, 105, 'CR', 'Cremona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1642, 105, 'CS', 'Cosenza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1643, 105, 'CT', 'Catania');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1644, 105, 'CZ', 'Catanzaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1645, 105, 'EN', 'Enna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1646, 105, 'FE', 'Ferrara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1647, 105, 'FG', 'Foggia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1648, 105, 'FI', 'Firenze');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1649, 105, 'FM', 'Fermo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1650, 105, 'FO', 'Forlì-Cesena');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1651, 105, 'FR', 'Frosinone');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1652, 105, 'GE', 'Genova');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1653, 105, 'GO', 'Gorizia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1654, 105, 'GR', 'Grosseto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1655, 105, 'IM', 'Imperia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1656, 105, 'IS', 'Isernia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1657, 105, 'KR', 'Crotone');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1658, 105, 'LC', 'Lecco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1659, 105, 'LE', 'Lecce');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1660, 105, 'LI', 'Livorno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1661, 105, 'LO', 'Lodi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1662, 105, 'LT', 'Latina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1663, 105, 'LU', 'Lucca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1664, 105, 'MC', 'Macerata');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1665, 105, 'MD', 'Medio Campidano');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1666, 105, 'ME', 'Messina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1667, 105, 'MI', 'Milano');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1668, 105, 'MN', 'Mantova');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1669, 105, 'MO', 'Modena');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1670, 105, 'MS', 'Massa-Carrara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1671, 105, 'MT', 'Matera');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1672, 105, 'MZ', 'Monza e Brianza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1673, 105, 'NA', 'Napoli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1674, 105, 'NO', 'Novara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1675, 105, 'NU', 'Nuoro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1676, 105, 'OG', 'Ogliastra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1677, 105, 'OR', 'Oristano');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1678, 105, 'OT', 'Olbia-Tempio');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1679, 105, 'PA', 'Palermo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1680, 105, 'PC', 'Piacenza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1681, 105, 'PD', 'Padova');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1682, 105, 'PE', 'Pescara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1683, 105, 'PG', 'Perugia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1684, 105, 'PI', 'Pisa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1685, 105, 'PN', 'Pordenone');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1686, 105, 'PO', 'Prato');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1687, 105, 'PR', 'Parma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1688, 105, 'PS', 'Pesaro e Urbino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1689, 105, 'PT', 'Pistoia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1690, 105, 'PV', 'Pavia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1691, 105, 'PZ', 'Potenza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1692, 105, 'RA', 'Ravenna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1693, 105, 'RC', 'Reggio Calabria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1694, 105, 'RE', 'Reggio Emilia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1695, 105, 'RG', 'Ragusa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1696, 105, 'RI', 'Rieti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1697, 105, 'RM', 'Roma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1698, 105, 'RN', 'Rimini');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1699, 105, 'RO', 'Rovigo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1700, 105, 'SA', 'Salerno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1701, 105, 'SI', 'Siena');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1702, 105, 'SO', 'Sondrio');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1703, 105, 'SP', 'La Spezia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1704, 105, 'SR', 'Siracusa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1705, 105, 'SS', 'Sassari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1706, 105, 'SV', 'Savona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1707, 105, 'TA', 'Taranto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1708, 105, 'TE', 'Teramo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1709, 105, 'TN', 'Trento');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1710, 105, 'TO', 'Torino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1711, 105, 'TP', 'Trapani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1712, 105, 'TR', 'Terni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1713, 105, 'TS', 'Trieste');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1714, 105, 'TV', 'Treviso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1715, 105, 'UD', 'Udine');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1716, 105, 'VA', 'Varese');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1717, 105, 'VB', 'Verbano-Cusio-Ossola');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1718, 105, 'VC', 'Vercelli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1719, 105, 'VE', 'Venezia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1720, 105, 'VI', 'Vicenza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1721, 105, 'VR', 'Verona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1722, 105, 'VT', 'Viterbo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1723, 105, 'VV', 'Vibo Valentia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1724, 106, '01', 'Kingston');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1725, 106, '02', 'Half Way Tree');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1726, 106, '03', 'Morant Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1727, 106, '04', 'Port Antonio');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1728, 106, '05', 'Port Maria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1729, 106, '06', 'Saint Ann''s Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1730, 106, '07', 'Falmouth');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1731, 106, '08', 'Montego Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1732, 106, '09', 'Lucea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1733, 106, '10', 'Savanna-la-Mar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1734, 106, '11', 'Black River');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1735, 106, '12', 'Mandeville');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1736, 106, '13', 'May Pen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1737, 106, '14', 'Spanish Town');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1738, 107, '01', '北海�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1739, 107, '02', '�?�森');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1740, 107, '03', '岩手');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1741, 107, '04', '宮城');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1742, 107, '05', '秋田');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1743, 107, '06', '山形');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1744, 107, '07', '�?島');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1745, 107, '08', '茨城');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1746, 107, '09', '栃木');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1747, 107, '10', '群馬');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1748, 107, '11', '埼玉');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1749, 107, '12', '�?�葉');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1750, 107, '13', '�?�京');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1751, 107, '14', '神奈�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1752, 107, '15', '新潟');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1753, 107, '16', '富山');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1754, 107, '17', '石�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1755, 107, '18', '�?井');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1756, 107, '19', '山梨');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1757, 107, '20', '長野');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1758, 107, '21', '�?阜');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1759, 107, '22', '�?�岡');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1760, 107, '23', '愛知');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1761, 107, '24', '三�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1762, 107, '25', '滋賀');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1763, 107, '26', '京都');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1764, 107, '27', '大阪');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1765, 107, '28', '兵庫');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1766, 107, '29', '奈良');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1767, 107, '30', '和歌山');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1768, 107, '31', '鳥�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1769, 107, '32', '島根');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1770, 107, '33', '岡山');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1771, 107, '34', '広島');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1772, 107, '35', '山�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1773, 107, '36', '徳島');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1774, 107, '37', '香�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1775, 107, '38', '愛媛');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1776, 107, '39', '高知');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1777, 107, '40', '�?岡');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1778, 107, '41', '�?賀');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1779, 107, '42', '長崎');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1780, 107, '43', '熊本');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1781, 107, '44', '大分');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1782, 107, '45', '宮崎');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1783, 107, '46', '鹿�?島');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1784, 107, '47', '沖縄');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1785, 108, 'AJ', 'محا�?ظة عجلون');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1786, 108, 'AM', 'محا�?ظة العاصمة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1787, 108, 'AQ', 'محا�?ظة العقبة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1788, 108, 'AT', 'محا�?ظة الط�?يلة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1789, 108, 'AZ', 'محا�?ظة الزرقاء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1790, 108, 'BA', 'محا�?ظة البلقاء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1791, 108, 'JA', 'محا�?ظة جرش');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1792, 108, 'JR', 'محا�?ظة إربد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1793, 108, 'KA', 'محا�?ظة الكرك');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1794, 108, 'MA', 'محا�?ظة الم�?رق');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1795, 108, 'MD', 'محا�?ظة مادبا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1796, 108, 'MN', 'محا�?ظة معان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1797, 109, 'AL', '�?лматы');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1798, 109, 'AC', 'Almaty City');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1799, 109, 'AM', '�?қмола');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1800, 109, 'AQ', '�?қтөбе');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1801, 109, 'AS', '�?�?тана');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1802, 109, 'AT', '�?тырау');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1803, 109, 'BA', 'Баты�? Қазақ�?тан');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1804, 109, 'BY', 'Байқоңыр');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1805, 109, 'MA', 'Маңғы�?тау');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1806, 109, 'ON', 'Оңтү�?тік Қазақ�?тан');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1807, 109, 'PA', 'Павлодар');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1808, 109, 'QA', 'Қарағанды');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1809, 109, 'QO', 'Қо�?танай');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1810, 109, 'QY', 'Қызылорда');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1811, 109, 'SH', 'Шығы�? Қазақ�?тан');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1812, 109, 'SO', 'Солтү�?тік Қазақ�?тан');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1813, 109, 'ZH', 'Жамбыл');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1814, 110, '110', 'Nairobi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1815, 110, '200', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1816, 110, '300', 'Mombasa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1817, 110, '400', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1818, 110, '500', 'North Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1819, 110, '600', 'Nyanza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1820, 110, '700', 'Rift Valley');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1821, 110, '900', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1822, 111, 'G', 'Gilbert Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1823, 111, 'L', 'Line Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1824, 111, 'P', 'Phoenix Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1825, 112, 'CHA', '�?강�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1826, 112, 'HAB', '함경 �?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1827, 112, 'HAN', '함경 남�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1828, 112, 'HWB', '황해 �?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1829, 112, 'HWN', '황해 남�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1830, 112, 'KAN', '강�?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1831, 112, 'KAE', '개성시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1832, 112, 'NAJ', '�?�선 �?할시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1833, 112, 'NAM', '남�?� 특급시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1834, 112, 'PYB', '�?�안 �?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1835, 112, 'PYN', '�?�안 남�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1836, 112, 'PYO', '�?�양 �?할시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1837, 112, 'YAN', '량강�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1838, 113, '11', '서울특별시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1839, 113, '26', '부산 광역시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1840, 113, '27', '대구 광역시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1841, 113, '28', '�?�천광역시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1842, 113, '29', '광주 광역시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1843, 113, '30', '대전 광역시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1844, 113, '31', '울산 광역시');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1845, 113, '41', '경기�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1846, 113, '42', '강�?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1847, 113, '43', '충청 �?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1848, 113, '44', '충청 남�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1849, 113, '45', '전�?� �?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1850, 113, '46', '전�?� 남�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1851, 113, '47', '경�? �?�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1852, 113, '48', '경�? 남�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1853, 113, '49', '제주특별�?치�?�');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1854, 114, 'AH', 'الاحمدي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1855, 114, 'FA', 'ال�?روانية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1856, 114, 'JA', 'الجهراء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1857, 114, 'KU', 'ألعاصمه');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1858, 114, 'HW', 'حولي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1859, 114, 'MU', 'مبارك الكبير');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1860, 115, 'B', 'Баткен обла�?ты');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1861, 115, 'C', 'Чүй обла�?ты');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1862, 115, 'GB', 'Бишкек');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1863, 115, 'J', 'Жалал-�?бад обла�?ты');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1864, 115, 'N', '�?арын обла�?ты');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1865, 115, 'O', 'Ош обла�?ты');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1866, 115, 'T', 'Тала�? обла�?ты');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1867, 115, 'Y', 'Ы�?ык-Көл обла�?ты');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1868, 116, 'AT', 'ອັດຕະປື');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1869, 116, 'BK', 'ບ�?່�?�?້ວ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1870, 116, 'BL', 'ບ�?ລິຄ�?າໄຊ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1871, 116, 'CH', 'ຈ�?າປາສັ�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1872, 116, 'HO', 'ຫົວພັນ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1873, 116, 'KH', 'ຄ�?າມ່ວນ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1874, 116, 'LM', 'ຫລວງນ�?້າທາ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1875, 116, 'LP', 'ຫລວງພະບາງ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1876, 116, 'OU', 'ອຸດົມໄຊ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1877, 116, 'PH', 'ຜົງສາລີ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1878, 116, 'SL', 'ສາລະວັນ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1879, 116, 'SV', 'ສະຫວັນນະເຂດ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1880, 116, 'VI', 'ວຽງຈັນ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1881, 116, 'VT', 'ວຽງຈັນ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1882, 116, 'XA', 'ໄຊ�?ະບູລີ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1883, 116, 'XE', 'ເຊ�?ອງ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1884, 116, 'XI', 'ຊຽງຂວາງ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1885, 116, 'XN', 'ໄຊສົມບູນ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1886, 117, 'AI', 'Aizkraukles rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1887, 117, 'AL', 'Alūksnes rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1888, 117, 'BL', 'Balvu rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1889, 117, 'BU', 'Bauskas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1890, 117, 'CE', 'Cēsu rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1891, 117, 'DA', 'Daugavpils rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1892, 117, 'DGV', 'Daugpilis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1893, 117, 'DO', 'Dobeles rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1894, 117, 'GU', 'Gulbenes rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1895, 117, 'JEL', 'Jelgava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1896, 117, 'JK', 'Jēkabpils rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1897, 117, 'JL', 'Jelgavas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1898, 117, 'JUR', 'Jūrmala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1899, 117, 'KR', 'Kr�?slavas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1900, 117, 'KU', 'Kuldīgas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1901, 117, 'LE', 'Liep�?jas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1902, 117, 'LM', 'Limbažu rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1903, 117, 'LPX', 'Liepoja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1904, 117, 'LU', 'Ludzas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1905, 117, 'MA', 'Madonas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1906, 117, 'OG', 'Ogres rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1907, 117, 'PR', 'Preiļu rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1908, 117, 'RE', 'Rēzeknes rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1909, 117, 'REZ', 'Rēzekne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1910, 117, 'RI', 'Rīgas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1911, 117, 'RIX', 'Rīga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1912, 117, 'SA', 'Saldus rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1913, 117, 'TA', 'Talsu rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1914, 117, 'TU', 'Tukuma rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1915, 117, 'VE', 'Ventspils rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1916, 117, 'VEN', 'Ventspils');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1917, 117, 'VK', 'Valkas rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1918, 117, 'VM', 'Valmieras rajons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1919, 119, 'A', 'Maseru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1920, 119, 'B', 'Butha-Buthe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1921, 119, 'C', 'Leribe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1922, 119, 'D', 'Berea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1923, 119, 'E', 'Mafeteng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1924, 119, 'F', 'Mohale''s Hoek');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1925, 119, 'G', 'Quthing');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1926, 119, 'H', 'Qacha''s Nek');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1927, 119, 'J', 'Mokhotlong');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1928, 119, 'K', 'Thaba-Tseka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1929, 120, 'BG', 'Bong');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1930, 120, 'BM', 'Bomi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1931, 120, 'CM', 'Grand Cape Mount');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1932, 120, 'GB', 'Grand Bassa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1933, 120, 'GG', 'Grand Gedeh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1934, 120, 'GK', 'Grand Kru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1935, 120, 'GP', 'Gbarpolu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1936, 120, 'LO', 'Lofa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1937, 120, 'MG', 'Margibi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1938, 120, 'MO', 'Montserrado');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1939, 120, 'MY', 'Maryland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1940, 120, 'NI', 'Nimba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1941, 120, 'RG', 'River Gee');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1942, 120, 'RI', 'Rivercess');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1943, 120, 'SI', 'Sinoe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1944, 121, 'AJ', 'Ajd�?biy�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1945, 121, 'BA', 'Bangh�?zī');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1946, 121, 'BU', 'Al Buţn�?n');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1947, 121, 'BW', 'Banī Walīd');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1948, 121, 'DR', 'Darnah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1949, 121, 'GD', 'Ghad�?mis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1950, 121, 'GR', 'Ghary�?n');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1951, 121, 'GT', 'Gh�?t');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1952, 121, 'HZ', 'Al Ḩiz�?m al Akhḑar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1953, 121, 'JA', 'Al Jabal al Akhḑar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1954, 121, 'JB', 'Jaghbūb');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1955, 121, 'JI', 'Al Jif�?rah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1956, 121, 'JU', 'Al Jufrah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1957, 121, 'KF', 'Al Kufrah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1958, 121, 'MB', 'Al Marqab');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1959, 121, 'MI', 'Mişr�?tah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1960, 121, 'MJ', 'Al Marj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1961, 121, 'MQ', 'Murzuq');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1962, 121, 'MZ', 'Mizdah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1963, 121, 'NL', 'N�?lūt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1964, 121, 'NQ', 'An Nuqaţ al Khams');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1965, 121, 'QB', 'Al Qubbah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1966, 121, 'QT', 'Al Qaţrūn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1967, 121, 'SB', 'Sabh�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1968, 121, 'SH', 'Ash Sh�?ţi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1969, 121, 'SR', 'Surt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1970, 121, 'SS', 'Şabr�?tah Şurm�?n');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1971, 121, 'TB', 'Ţar�?bulus');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1972, 121, 'TM', 'Tarhūnah-Masall�?tah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1973, 121, 'TN', 'T�?jūr�? wa an Naw�?ḩī al Arb�?ʻ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1974, 121, 'WA', 'Al W�?ḩah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1975, 121, 'WD', 'W�?dī al Ḩay�?t');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1976, 121, 'YJ', 'Yafran-J�?dū');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1977, 121, 'ZA', 'Az Z�?wiyah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1978, 122, 'B', 'Balzers');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1979, 122, 'E', 'Eschen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1980, 122, 'G', 'Gamprin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1981, 122, 'M', 'Mauren');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1982, 122, 'P', 'Planken');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1983, 122, 'R', 'Ruggell');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1984, 122, 'A', 'Schaan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1985, 122, 'L', 'Schellenberg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1986, 122, 'N', 'Triesen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1987, 122, 'T', 'Triesenberg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1988, 122, 'V', 'Vaduz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1989, 123, 'AL', 'Alytaus Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1990, 123, 'KL', 'Klaipėdos Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1991, 123, 'KU', 'Kauno Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1992, 123, 'MR', 'Marijampolės Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1993, 123, 'PN', 'Panevėžio Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1994, 123, 'SA', 'Šiaulių Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1995, 123, 'TA', 'Tauragės Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1996, 123, 'TE', 'Telšių Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1997, 123, 'UT', 'Utenos Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1998, 123, 'VL', 'Vilniaus Apskritis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(1999, 124, 'D', 'Diekirch');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2000, 124, 'G', 'Grevenmacher');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2001, 124, 'L', 'Luxemburg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2002, 125, 'I', '海島市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2003, 125, 'M', '澳門市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2004, 126, 'BR', 'Berovo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2005, 126, 'CH', 'Чешиново-Облешево');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2006, 126, 'DL', 'Делчево');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2007, 126, 'KB', 'Карбинци');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2008, 126, 'OC', 'Кочани');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2009, 126, 'LO', 'Лозово');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2010, 126, 'MK', 'Македон�?ка каменица');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2011, 126, 'PH', 'Пехчево');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2012, 126, 'PT', 'Пробиштип');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2013, 126, 'ST', 'Штип');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2014, 126, 'SL', 'Свети �?иколе');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2015, 126, 'NI', 'Виница');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2016, 126, 'ZR', 'Зрновци');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2017, 126, 'KY', 'Кратово');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2018, 126, 'KZ', 'Крива Паланка');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2019, 126, 'UM', 'Куманово');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2020, 126, 'LI', 'Липково');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2021, 126, 'RN', 'Ранковце');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2022, 126, 'NA', 'Старо �?агоричане');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2023, 126, 'TL', 'Битола');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2024, 126, 'DM', 'Демир Хи�?ар');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2025, 126, 'DE', 'Долнени');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2026, 126, 'KG', 'Кривогаштани');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2027, 126, 'KS', 'Крушево');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2028, 126, 'MG', 'Могила');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2029, 126, 'NV', '�?оваци');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2030, 126, 'PP', 'Прилеп');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2031, 126, 'RE', 'Ре�?ен');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2032, 126, 'VJ', 'Боговиње');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2033, 126, 'BN', 'Брвеница');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2034, 126, 'GT', 'Го�?тивар');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2035, 126, 'JG', 'Јегуновце');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2036, 126, 'MR', 'Маврово и Ро�?туша');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2037, 126, 'TR', 'Теарце');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2038, 126, 'ET', 'Тетово');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2039, 126, 'VH', 'Врапчиште');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2040, 126, 'ZE', 'Желино');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2041, 126, 'AD', '�?еродром');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2042, 126, 'AR', '�?рачиново');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2043, 126, 'BU', 'Бутел');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2044, 126, 'CI', 'Чаир');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2045, 126, 'CE', 'Центар');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2046, 126, 'CS', 'Чучер Сандево');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2047, 126, 'GB', 'Гази Баба');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2048, 126, 'GP', 'Ѓорче Петров');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2049, 126, 'IL', 'Илинден');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2050, 126, 'KX', 'Карпош');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2051, 126, 'VD', 'Ки�?ела Вода');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2052, 126, 'PE', 'Петровец');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2053, 126, 'AJ', 'Сарај');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2054, 126, 'SS', 'Сопиште');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2055, 126, 'SU', 'Студеничани');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2056, 126, 'SO', 'Шуто Оризари');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2057, 126, 'ZK', 'Зелениково');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2058, 126, 'BG', 'Богданци');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2059, 126, 'BS', 'Бо�?илово');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2060, 126, 'GV', 'Гевгелија');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2061, 126, 'KN', 'Конче');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2062, 126, 'NS', '�?ово Село');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2063, 126, 'RV', 'Радовиш');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2064, 126, 'SD', 'Стар Дојран');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2065, 126, 'RU', 'Струмица');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2066, 126, 'VA', 'Валандово');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2067, 126, 'VL', 'Ва�?илево');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2068, 126, 'CZ', 'Центар Жупа');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2069, 126, 'DB', 'Дебар');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2070, 126, 'DA', 'Дебарца');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2071, 126, 'DR', 'Другово');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2072, 126, 'KH', 'Кичево');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2073, 126, 'MD', 'Македон�?ки Брод');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2074, 126, 'OD', 'Охрид');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2075, 126, 'OS', 'О�?ломеј');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2076, 126, 'PN', 'Пла�?ница');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2077, 126, 'UG', 'Струга');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2078, 126, 'VV', 'Вевчани');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2079, 126, 'VC', 'Вранештица');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2080, 126, 'ZA', 'Заја�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2081, 126, 'CA', 'Чашка');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2082, 126, 'DK', 'Демир Капија');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2083, 126, 'GR', 'Град�?ко');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2084, 126, 'AV', 'Кавадарци');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2085, 126, 'NG', '�?еготино');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2086, 126, 'RM', 'Ро�?оман');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2087, 126, 'VE', 'Веле�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2088, 127, 'A', 'Toamasina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2089, 127, 'D', 'Antsiranana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2090, 127, 'F', 'Fianarantsoa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2091, 127, 'M', 'Mahajanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2092, 127, 'T', 'Antananarivo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2093, 127, 'U', 'Toliara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2094, 128, 'BA', 'Balaka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2095, 128, 'BL', 'Blantyre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2096, 128, 'C', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2097, 128, 'CK', 'Chikwawa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2098, 128, 'CR', 'Chiradzulu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2099, 128, 'CT', 'Chitipa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2100, 128, 'DE', 'Dedza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2101, 128, 'DO', 'Dowa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2102, 128, 'KR', 'Karonga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2103, 128, 'KS', 'Kasungu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2104, 128, 'LK', 'Likoma Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2105, 128, 'LI', 'Lilongwe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2106, 128, 'MH', 'Machinga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2107, 128, 'MG', 'Mangochi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2108, 128, 'MC', 'Mchinji');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2109, 128, 'MU', 'Mulanje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2110, 128, 'MW', 'Mwanza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2111, 128, 'MZ', 'Mzimba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2112, 128, 'N', 'Northern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2113, 128, 'NB', 'Nkhata');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2114, 128, 'NK', 'Nkhotakota');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2115, 128, 'NS', 'Nsanje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2116, 128, 'NU', 'Ntcheu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2117, 128, 'NI', 'Ntchisi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2118, 128, 'PH', 'Phalombe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2119, 128, 'RU', 'Rumphi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2120, 128, 'S', 'Southern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2121, 128, 'SA', 'Salima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2122, 128, 'TH', 'Thyolo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2123, 128, 'ZO', 'Zomba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2124, 129, '01', 'Johor Darul Takzim');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2125, 129, '02', 'Kedah Darul Aman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2126, 129, '03', 'Kelantan Darul Naim');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2127, 129, '04', 'Melaka Negeri Bersejarah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2128, 129, '05', 'Negeri Sembilan Darul Khusus');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2129, 129, '06', 'Pahang Darul Makmur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2130, 129, '07', 'Pulau Pinang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2131, 129, '08', 'Perak Darul Ridzuan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2132, 129, '09', 'Perlis Indera Kayangan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2133, 129, '10', 'Selangor Darul Ehsan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2134, 129, '11', 'Terengganu Darul Iman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2135, 129, '12', 'Sabah Negeri Di Bawah Bayu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2136, 129, '13', 'Sarawak Bumi Kenyalang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2137, 129, '14', 'Wilayah Persekutuan Kuala Lumpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2138, 129, '15', 'Wilayah Persekutuan Labuan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2139, 129, '16', 'Wilayah Persekutuan Putrajaya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2140, 130, 'THU', 'Thiladhunmathi Uthuru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2141, 130, 'THD', 'Thiladhunmathi Dhekunu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2142, 130, 'MLU', 'Miladhunmadulu Uthuru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2143, 130, 'MLD', 'Miladhunmadulu Dhekunu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2144, 130, 'MAU', 'Maalhosmadulu Uthuru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2145, 130, 'MAD', 'Maalhosmadulu Dhekunu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2146, 130, 'FAA', 'Faadhippolhu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2147, 130, 'MAA', 'Male Atoll');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2148, 130, 'AAU', 'Ari Atoll Uthuru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2149, 130, 'AAD', 'Ari Atoll Dheknu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2150, 130, 'FEA', 'Felidhe Atoll');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2151, 130, 'MUA', 'Mulaku Atoll');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2152, 130, 'NAU', 'Nilandhe Atoll Uthuru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2153, 130, 'NAD', 'Nilandhe Atoll Dhekunu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2154, 130, 'KLH', 'Kolhumadulu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2155, 130, 'HDH', 'Hadhdhunmathi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2156, 130, 'HAU', 'Huvadhu Atoll Uthuru');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2157, 130, 'HAD', 'Huvadhu Atoll Dhekunu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2158, 130, 'FMU', 'Fua Mulaku');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2159, 130, 'ADD', 'Addu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2160, 131, '1', 'Kayes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2161, 131, '2', 'Koulikoro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2162, 131, '3', 'Sikasso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2163, 131, '4', 'Ségou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2164, 131, '5', 'Mopti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2165, 131, '6', 'Tombouctou');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2166, 131, '7', 'Gao');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2167, 131, '8', 'Kidal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2168, 131, 'BK0', 'Bamako');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2169, 132, 'ATT', 'Attard');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2170, 132, 'BAL', 'Balzan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2171, 132, 'BGU', 'Birgu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2172, 132, 'BKK', 'Birkirkara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2173, 132, 'BRZ', 'Birzebbuga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2174, 132, 'BOR', 'Bormla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2175, 132, 'DIN', 'Dingli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2176, 132, 'FGU', 'Fgura');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2177, 132, 'FLO', 'Floriana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2178, 132, 'GDJ', 'Gudja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2179, 132, 'GZR', 'Gzira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2180, 132, 'GRG', 'Gargur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2181, 132, 'GXQ', 'Gaxaq');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2182, 132, 'HMR', 'Hamrun');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2183, 132, 'IKL', 'Iklin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2184, 132, 'ISL', 'Isla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2185, 132, 'KLK', 'Kalkara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2186, 132, 'KRK', 'Kirkop');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2187, 132, 'LIJ', 'Lija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2188, 132, 'LUQ', 'Luqa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2189, 132, 'MRS', 'Marsa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2190, 132, 'MKL', 'Marsaskala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2191, 132, 'MXL', 'Marsaxlokk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2192, 132, 'MDN', 'Mdina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2193, 132, 'MEL', 'Melliea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2194, 132, 'MGR', 'Mgarr');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2195, 132, 'MST', 'Mosta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2196, 132, 'MQA', 'Mqabba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2197, 132, 'MSI', 'Msida');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2198, 132, 'MTF', 'Mtarfa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2199, 132, 'NAX', 'Naxxar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2200, 132, 'PAO', 'Paola');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2201, 132, 'PEM', 'Pembroke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2202, 132, 'PIE', 'Pieta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2203, 132, 'QOR', 'Qormi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2204, 132, 'QRE', 'Qrendi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2205, 132, 'RAB', 'Rabat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2206, 132, 'SAF', 'Safi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2207, 132, 'SGI', 'San Giljan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2208, 132, 'SLU', 'Santa Lucija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2209, 132, 'SPB', 'San Pawl il-Bahar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2210, 132, 'SGW', 'San Gwann');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2211, 132, 'SVE', 'Santa Venera');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2212, 132, 'SIG', 'Siggiewi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2213, 132, 'SLM', 'Sliema');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2214, 132, 'SWQ', 'Swieqi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2215, 132, 'TXB', 'Ta Xbiex');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2216, 132, 'TRX', 'Tarxien');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2217, 132, 'VLT', 'Valletta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2218, 132, 'XGJ', 'Xgajra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2219, 132, 'ZBR', 'Zabbar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2220, 132, 'ZBG', 'Zebbug');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2221, 132, 'ZJT', 'Zejtun');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2222, 132, 'ZRQ', 'Zurrieq');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2223, 132, 'FNT', 'Fontana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2224, 132, 'GHJ', 'Ghajnsielem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2225, 132, 'GHR', 'Gharb');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2226, 132, 'GHS', 'Ghasri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2227, 132, 'KRC', 'Kercem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2228, 132, 'MUN', 'Munxar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2229, 132, 'NAD', 'Nadur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2230, 132, 'QAL', 'Qala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2231, 132, 'VIC', 'Victoria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2232, 132, 'SLA', 'San Lawrenz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2233, 132, 'SNT', 'Sannat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2234, 132, 'ZAG', 'Xagra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2235, 132, 'XEW', 'Xewkija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2236, 132, 'ZEB', 'Zebbug');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2237, 133, 'ALK', 'Ailuk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2238, 133, 'ALL', 'Ailinglapalap');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2239, 133, 'ARN', 'Arno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2240, 133, 'AUR', 'Aur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2241, 133, 'EBO', 'Ebon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2242, 133, 'ENI', 'Eniwetok');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2243, 133, 'JAB', 'Jabat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2244, 133, 'JAL', 'Jaluit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2245, 133, 'KIL', 'Kili');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2246, 133, 'KWA', 'Kwajalein');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2247, 133, 'LAE', 'Lae');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2248, 133, 'LIB', 'Lib');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2249, 133, 'LIK', 'Likiep');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2250, 133, 'MAJ', 'Majuro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2251, 133, 'MAL', 'Maloelap');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2252, 133, 'MEJ', 'Mejit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2253, 133, 'MIL', 'Mili');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2254, 133, 'NMK', 'Namorik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2255, 133, 'NMU', 'Namu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2256, 133, 'RON', 'Rongelap');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2257, 133, 'UJA', 'Ujae');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2258, 133, 'UJL', 'Ujelang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2259, 133, 'UTI', 'Utirik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2260, 133, 'WTJ', 'Wotje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2261, 133, 'WTN', 'Wotho');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2262, 135, '01', 'ولاية الحوض الشرقي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2263, 135, '02', 'ولاية الحوض الغربي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2264, 135, '03', 'ولاية العصابة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2265, 135, '04', 'ولاية كركول');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2266, 135, '05', 'ولاية البراكنة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2267, 135, '06', 'ولاية الترارزة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2268, 135, '07', 'ولاية آدرار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2269, 135, '08', 'ولاية داخلت نواذيبو');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2270, 135, '09', 'ولاية تكانت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2271, 135, '10', 'ولاية كيدي ماغة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2272, 135, '11', 'ولاية تيرس زمور');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2273, 135, '12', 'ولاية إينشيري');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2274, 135, 'NKC', 'نواكشوط');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2275, 136, 'AG', 'Agalega Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2276, 136, 'BL', 'Black River');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2277, 136, 'BR', 'Beau Bassin-Rose Hill');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2278, 136, 'CC', 'Cargados Carajos Shoals');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2279, 136, 'CU', 'Curepipe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2280, 136, 'FL', 'Flacq');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2281, 136, 'GP', 'Grand Port');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2282, 136, 'MO', 'Moka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2283, 136, 'PA', 'Pamplemousses');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2284, 136, 'PL', 'Port Louis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2285, 136, 'PU', 'Port Louis City');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2286, 136, 'PW', 'Plaines Wilhems');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2287, 136, 'QB', 'Quatre Bornes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2288, 136, 'RO', 'Rodrigues');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2289, 136, 'RR', 'Riviere du Rempart');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2290, 136, 'SA', 'Savanne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2291, 136, 'VP', 'Vacoas-Phoenix');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2292, 138, 'AGU', 'Aguascalientes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2293, 138, 'BCN', 'Baja California');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2294, 138, 'BCS', 'Baja California Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2295, 138, 'CAM', 'Campeche');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2296, 138, 'CHH', 'Chihuahua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2297, 138, 'CHP', 'Chiapas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2298, 138, 'COA', 'Coahuila');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2299, 138, 'COL', 'Colima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2300, 138, 'DIF', 'Distrito Federal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2301, 138, 'DUR', 'Durango');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2302, 138, 'GRO', 'Guerrero');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2303, 138, 'GUA', 'Guanajuato');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2304, 138, 'HID', 'Hidalgo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2305, 138, 'JAL', 'Jalisco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2306, 138, 'MEX', 'Mexico');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2307, 138, 'MIC', 'Michoacán');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2308, 138, 'MOR', 'Morelos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2309, 138, 'NAY', 'Nayarit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2310, 138, 'NLE', 'Nuevo León');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2311, 138, 'OAX', 'Oaxaca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2312, 138, 'PUE', 'Puebla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2313, 138, 'QUE', 'Querétaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2314, 138, 'ROO', 'Quintana Roo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2315, 138, 'SIN', 'Sinaloa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2316, 138, 'SLP', 'San Luis Potosí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2317, 138, 'SON', 'Sonora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2318, 138, 'TAB', 'Tabasco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2319, 138, 'TAM', 'Tamaulipas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2320, 138, 'TLA', 'Tlaxcala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2321, 138, 'VER', 'Veracruz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2322, 138, 'YUC', 'Yucatan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2323, 138, 'ZAC', 'Zacatecas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2324, 139, 'KSA', 'Kosrae');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2325, 139, 'PNI', 'Pohnpei');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2326, 139, 'TRK', 'Chuuk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2327, 139, 'YAP', 'Yap');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2328, 140, 'BA', 'Bălţi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2329, 140, 'CA', 'Cahul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2330, 140, 'CU', 'Chişinău');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2331, 140, 'ED', 'Edineţ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2332, 140, 'GA', 'Găgăuzia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2333, 140, 'LA', 'Lăpuşna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2334, 140, 'OR', 'Orhei');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2335, 140, 'SN', 'Stânga Nistrului');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2336, 140, 'SO', 'Soroca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2337, 140, 'TI', 'Tighina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2338, 140, 'UN', 'Ungheni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2339, 141, 'MC', 'Monte Carlo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2340, 141, 'LR', 'La Rousse');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2341, 141, 'LA', 'Larvotto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2342, 141, 'MV', 'Monaco Ville');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2343, 141, 'SM', 'Saint Michel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2344, 141, 'CO', 'Condamine');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2345, 141, 'LC', 'La Colle');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2346, 141, 'RE', 'Les Révoires');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2347, 141, 'MO', 'Moneghetti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2348, 141, 'FV', 'Fontvieille');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2349, 142, '1', 'Улаанбаатар');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2350, 142, '035', 'Орхон аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2351, 142, '037', 'Дархан-Уул аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2352, 142, '039', 'Х�?нтий аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2353, 142, '041', 'Хөв�?гөл аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2354, 142, '043', 'Ховд аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2355, 142, '046', 'Ув�? аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2356, 142, '047', 'Төв аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2357, 142, '049', 'С�?л�?нг�? аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2358, 142, '051', 'Сүхбаатар аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2359, 142, '053', 'Өмнөговь аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2360, 142, '055', 'Өвөрхангай аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2361, 142, '057', 'Завхан аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2362, 142, '059', 'Дундговь аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2363, 142, '061', 'Дорнод аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2364, 142, '063', 'Дорноговь аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2365, 142, '064', 'Говь�?үмб�?р аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2366, 142, '065', 'Говь-�?лтай аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2367, 142, '067', 'Булган аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2368, 142, '069', 'Ба�?нхонгор аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2369, 142, '071', 'Ба�?н Өлгий аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2370, 142, '073', '�?рхангай аймаг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2371, 143, 'A', 'Saint Anthony');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2372, 143, 'G', 'Saint Georges');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2373, 143, 'P', 'Saint Peter');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2374, 145, 'A', 'Niassa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2375, 145, 'B', 'Manica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2376, 145, 'G', 'Gaza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2377, 145, 'I', 'Inhambane');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2378, 145, 'L', 'Maputo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2379, 145, 'MPM', 'Maputo cidade');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2380, 145, 'N', 'Nampula');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2381, 145, 'P', 'Cabo Delgado');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2382, 145, 'Q', 'Zambézia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2383, 145, 'S', 'Sofala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2384, 145, 'T', 'Tete');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2385, 146, 'AY', 'ဧရာ�?��?ီ�?ိုင္‌း');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2386, 146, 'BG', 'ပဲ�?ူး�?ုိင္‌း');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2387, 146, 'MG', 'မကေ္�?း�?ိုင္‌း');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2388, 146, 'MD', 'မန္�?လေး�?ုိင္‌း');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2389, 146, 'SG', 'စစ္‌ကုိင္‌း‌�?ုိင္‌း');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2390, 146, 'TN', '�?နင္သာရိ�?ုိင္‌း');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2391, 146, 'YG', 'ရန္‌ကုန္‌�?ုိင္‌း');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2392, 146, 'CH', '�?္ယင္‌းပ္ရည္‌နယ္‌');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2393, 146, 'KC', 'က�?္ယင္‌ပ္ရည္‌နယ္‌');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2394, 146, 'KH', 'ကယား‌ပ္ရည္‌နယ္‌');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2395, 146, 'KN', 'ကရင္‌‌ပ္ရည္‌နယ္‌');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2396, 146, 'MN', 'မ္�?န္‌ပ္ရည္‌နယ္‌');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2397, 146, 'RK', 'ရ�?ုိင္‌ပ္ရည္‌နယ္‌');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2398, 146, 'SH', 'ရုမ္‌းပ္ရည္‌နယ္‌');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2399, 147, 'CA', 'Caprivi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2400, 147, 'ER', 'Erongo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2401, 147, 'HA', 'Hardap');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2402, 147, 'KA', 'Karas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2403, 147, 'KH', 'Khomas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2404, 147, 'KU', 'Kunene');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2405, 147, 'OD', 'Otjozondjupa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2406, 147, 'OH', 'Omaheke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2407, 147, 'OK', 'Okavango');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2408, 147, 'ON', 'Oshana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2409, 147, 'OS', 'Omusati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2410, 147, 'OT', 'Oshikoto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2411, 147, 'OW', 'Ohangwena');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2412, 148, 'AO', 'Aiwo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2413, 148, 'AA', 'Anabar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2414, 148, 'AT', 'Anetan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2415, 148, 'AI', 'Anibare');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2416, 148, 'BA', 'Baiti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2417, 148, 'BO', 'Boe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2418, 148, 'BU', 'Buada');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2419, 148, 'DE', 'Denigomodu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2420, 148, 'EW', 'Ewa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2421, 148, 'IJ', 'Ijuw');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2422, 148, 'ME', 'Meneng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2423, 148, 'NI', 'Nibok');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2424, 148, 'UA', 'Uaboe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2425, 148, 'YA', 'Yaren');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2426, 149, 'BA', 'Bagmati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2427, 149, 'BH', 'Bheri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2428, 149, 'DH', 'Dhawalagiri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2429, 149, 'GA', 'Gandaki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2430, 149, 'JA', 'Janakpur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2431, 149, 'KA', 'Karnali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2432, 149, 'KO', 'Kosi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2433, 149, 'LU', 'Lumbini');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2434, 149, 'MA', 'Mahakali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2435, 149, 'ME', 'Mechi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2436, 149, 'NA', 'Narayani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2437, 149, 'RA', 'Rapti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2438, 149, 'SA', 'Sagarmatha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2439, 149, 'SE', 'Seti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2440, 150, 'DR', 'Drenthe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2441, 150, 'FL', 'Flevoland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2442, 150, 'FR', 'Friesland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2443, 150, 'GE', 'Gelderland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2444, 150, 'GR', 'Groningen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2445, 150, 'LI', 'Limburg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2446, 150, 'NB', 'Noord-Brabant');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2447, 150, 'NH', 'Noord-Holland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2448, 150, 'OV', 'Overijssel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2449, 150, 'UT', 'Utrecht');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2450, 150, 'ZE', 'Zeeland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2451, 150, 'ZH', 'Zuid-Holland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2452, 152, 'L', 'Province des Îles');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2453, 152, 'N', 'Province Nord');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2454, 152, 'S', 'Province Sud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2455, 153, 'AUK', 'Auckland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2456, 153, 'BOP', 'Bay of Plenty');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2457, 153, 'CAN', 'Canterbury');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2458, 153, 'GIS', 'Gisborne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2459, 153, 'HKB', 'Hawke''s Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2460, 153, 'MBH', 'Marlborough');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2461, 153, 'MWT', 'Manawatu-Wanganui');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2462, 153, 'NSN', 'Nelson');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2463, 153, 'NTL', 'Northland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2464, 153, 'OTA', 'Otago');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2465, 153, 'STL', 'Southland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2466, 153, 'TAS', 'Tasman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2467, 153, 'TKI', 'Taranaki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2468, 153, 'WGN', 'Wellington');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2469, 153, 'WKO', 'Waikato');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2470, 153, 'WTC', 'West Coast');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2471, 154, 'AN', 'Atlántico Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2472, 154, 'AS', 'Atlántico Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2473, 154, 'BO', 'Boaco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2474, 154, 'CA', 'Carazo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2475, 154, 'CI', 'Chinandega');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2476, 154, 'CO', 'Chontales');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2477, 154, 'ES', 'Estelí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2478, 154, 'GR', 'Granada');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2479, 154, 'JI', 'Jinotega');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2480, 154, 'LE', 'León');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2481, 154, 'MD', 'Madriz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2482, 154, 'MN', 'Managua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2483, 154, 'MS', 'Masaya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2484, 154, 'MT', 'Matagalpa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2485, 154, 'NS', 'Nueva Segovia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2486, 154, 'RI', 'Rivas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2487, 154, 'SJ', 'Río San Juan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2488, 155, '1', 'Agadez');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2489, 155, '2', 'Daffa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2490, 155, '3', 'Dosso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2491, 155, '4', 'Maradi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2492, 155, '5', 'Tahoua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2493, 155, '6', 'Tillabéry');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2494, 155, '7', 'Zinder');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2495, 155, '8', 'Niamey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2496, 156, 'AB', 'Abia State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2497, 156, 'AD', 'Adamawa State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2498, 156, 'AK', 'Akwa Ibom State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2499, 156, 'AN', 'Anambra State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2500, 156, 'BA', 'Bauchi State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2501, 156, 'BE', 'Benue State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2502, 156, 'BO', 'Borno State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2503, 156, 'BY', 'Bayelsa State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2504, 156, 'CR', 'Cross River State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2505, 156, 'DE', 'Delta State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2506, 156, 'EB', 'Ebonyi State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2507, 156, 'ED', 'Edo State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2508, 156, 'EK', 'Ekiti State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2509, 156, 'EN', 'Enugu State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2510, 156, 'GO', 'Gombe State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2511, 156, 'IM', 'Imo State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2512, 156, 'JI', 'Jigawa State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2513, 156, 'KB', 'Kebbi State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2514, 156, 'KD', 'Kaduna State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2515, 156, 'KN', 'Kano State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2516, 156, 'KO', 'Kogi State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2517, 156, 'KT', 'Katsina State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2518, 156, 'KW', 'Kwara State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2519, 156, 'LA', 'Lagos State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2520, 156, 'NA', 'Nassarawa State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2521, 156, 'NI', 'Niger State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2522, 156, 'OG', 'Ogun State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2523, 156, 'ON', 'Ondo State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2524, 156, 'OS', 'Osun State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2525, 156, 'OY', 'Oyo State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2526, 156, 'PL', 'Plateau State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2527, 156, 'RI', 'Rivers State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2528, 156, 'SO', 'Sokoto State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2529, 156, 'TA', 'Taraba State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2530, 156, 'ZA', 'Zamfara State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2531, 159, 'N', 'Northern Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2532, 159, 'R', 'Rota');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2533, 159, 'S', 'Saipan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2534, 159, 'T', 'Tinian');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2535, 160, '01', 'Østfold fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2536, 160, '02', 'Akershus fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2537, 160, '03', 'Oslo fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2538, 160, '04', 'Hedmark fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2539, 160, '05', 'Oppland fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2540, 160, '06', 'Buskerud fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2541, 160, '07', 'Vestfold fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2542, 160, '08', 'Telemark fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2543, 160, '09', 'Aust-Agder fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2544, 160, '10', 'Vest-Agder fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2545, 160, '11', 'Rogaland fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2546, 160, '12', 'Hordaland fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2547, 160, '14', 'Sogn og Fjordane fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2548, 160, '15', 'Møre og Romsdal fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2549, 160, '16', 'Sør-Trøndelag fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2550, 160, '17', 'Nord-Trøndelag fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2551, 160, '18', 'Nordland fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2552, 160, '19', 'Troms fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2553, 160, '20', 'Finnmark fylke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2554, 161, 'BA', 'الباطنة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2555, 161, 'DA', 'الداخلية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2556, 161, 'DH', 'ظ�?ار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2557, 161, 'MA', 'مسقط');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2558, 161, 'MU', 'مسندم');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2559, 161, 'SH', 'الشرقية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2560, 161, 'WU', 'الوسطى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2561, 161, 'ZA', 'الظاهرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2562, 162, 'BA', 'بلوچستان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2563, 162, 'IS', 'و�?اقی دارالحکومت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2564, 162, 'JK', 'آزاد کشمیر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2565, 162, 'NA', 'شمالی علاق�? جات');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2566, 162, 'NW', 'شمال مغربی سرحدی صوب�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2567, 162, 'PB', 'پنجاب');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2568, 162, 'SD', 'سندھ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2569, 162, 'TA', 'و�?اقی قبائلی علاق�? جات');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2570, 163, 'AM', 'Aimeliik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2571, 163, 'AR', 'Airai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2572, 163, 'AN', 'Angaur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2573, 163, 'HA', 'Hatohobei');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2574, 163, 'KA', 'Kayangel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2575, 163, 'KO', 'Koror');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2576, 163, 'ME', 'Melekeok');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2577, 163, 'NA', 'Ngaraard');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2578, 163, 'NG', 'Ngarchelong');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2579, 163, 'ND', 'Ngardmau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2580, 163, 'NT', 'Ngatpang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2581, 163, 'NC', 'Ngchesar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2582, 163, 'NR', 'Ngeremlengui');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2583, 163, 'NW', 'Ngiwal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2584, 163, 'PE', 'Peleliu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2585, 163, 'SO', 'Sonsorol');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2586, 164, '1', 'Bocas del Toro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2587, 164, '2', 'Coclé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2588, 164, '3', 'Colón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2589, 164, '4', 'Chiriquí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2590, 164, '5', 'Darién');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2591, 164, '6', 'Herrera');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2592, 164, '7', 'Los Santos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2593, 164, '8', 'Panamá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2594, 164, '9', 'Veraguas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2595, 164, 'Q', 'Kuna Yala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2596, 165, 'CPK', 'Chimbu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2597, 165, 'CPM', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2598, 165, 'EBR', 'East New Britain');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2599, 165, 'EHG', 'Eastern Highlands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2600, 165, 'EPW', 'Enga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2601, 165, 'ESW', 'East Sepik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2602, 165, 'GPK', 'Gulf');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2603, 165, 'MBA', 'Milne Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2604, 165, 'MPL', 'Morobe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2605, 165, 'MPM', 'Madang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2606, 165, 'MRL', 'Manus');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2607, 165, 'NCD', 'National Capital District');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2608, 165, 'NIK', 'New Ireland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2609, 165, 'NPP', 'Northern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2610, 165, 'NSA', 'North Solomons');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2611, 165, 'SAN', 'Sandaun');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2612, 165, 'SHM', 'Southern Highlands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2613, 165, 'WBK', 'West New Britain');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2614, 165, 'WHM', 'Western Highlands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2615, 165, 'WPD', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2616, 166, '1', 'Concepción');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2617, 166, '2', 'San Pedro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2618, 166, '3', 'Cordillera');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2619, 166, '4', 'Guairá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2620, 166, '5', 'Caaguazú');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2621, 166, '6', 'Caazapá');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2622, 166, '7', 'Itapúa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2623, 166, '8', 'Misiones');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2624, 166, '9', 'Paraguarí');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2625, 166, '10', 'Alto Paraná');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2626, 166, '11', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2627, 166, '12', 'Ñeembucú');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2628, 166, '13', 'Amambay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2629, 166, '14', 'Canindeyú');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2630, 166, '15', 'Presidente Hayes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2631, 166, '16', 'Alto Paraguay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2632, 166, '19', 'Boquerón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2633, 166, 'ASU', 'Asunción');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2634, 167, 'AMA', 'Amazonas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2635, 167, 'ANC', 'Ancash');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2636, 167, 'APU', 'Apurímac');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2637, 167, 'ARE', 'Arequipa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2638, 167, 'AYA', 'Ayacucho');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2639, 167, 'CAJ', 'Cajamarca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2640, 167, 'CAL', 'Callao');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2641, 167, 'CUS', 'Cuzco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2642, 167, 'HUC', 'Huánuco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2643, 167, 'HUV', 'Huancavelica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2644, 167, 'ICA', 'Ica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2645, 167, 'JUN', 'Junín');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2646, 167, 'LAL', 'La Libertad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2647, 167, 'LAM', 'Lambayeque');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2648, 167, 'LIM', 'Lima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2649, 167, 'LOR', 'Loreto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2650, 167, 'MDD', 'Madre de Dios');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2651, 167, 'MOQ', 'Moquegua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2652, 167, 'PAS', 'Pasco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2653, 167, 'PIU', 'Piura');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2654, 167, 'PUN', 'Puno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2655, 167, 'SAM', 'San Martín');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2656, 167, 'TAC', 'Tacna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2657, 167, 'TUM', 'Tumbes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2658, 167, 'UCA', 'Ucayali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2659, 168, 'ABR', 'Abra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2660, 168, 'AGN', 'Agusan del Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2661, 168, 'AGS', 'Agusan del Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2662, 168, 'AKL', 'Aklan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2663, 168, 'ALB', 'Albay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2664, 168, 'ANT', 'Antique');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2665, 168, 'APA', 'Apayao');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2666, 168, 'AUR', 'Aurora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2667, 168, 'BAN', 'Bataan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2668, 168, 'BAS', 'Basilan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2669, 168, 'BEN', 'Benguet');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2670, 168, 'BIL', 'Biliran');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2671, 168, 'BOH', 'Bohol');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2672, 168, 'BTG', 'Batangas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2673, 168, 'BTN', 'Batanes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2674, 168, 'BUK', 'Bukidnon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2675, 168, 'BUL', 'Bulacan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2676, 168, 'CAG', 'Cagayan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2677, 168, 'CAM', 'Camiguin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2678, 168, 'CAN', 'Camarines Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2679, 168, 'CAP', 'Capiz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2680, 168, 'CAS', 'Camarines Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2681, 168, 'CAT', 'Catanduanes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2682, 168, 'CAV', 'Cavite');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2683, 168, 'CEB', 'Cebu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2684, 168, 'COM', 'Compostela Valley');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2685, 168, 'DAO', 'Davao Oriental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2686, 168, 'DAS', 'Davao del Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2687, 168, 'DAV', 'Davao del Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2688, 168, 'EAS', 'Eastern Samar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2689, 168, 'GUI', 'Guimaras');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2690, 168, 'IFU', 'Ifugao');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2691, 168, 'ILI', 'Iloilo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2692, 168, 'ILN', 'Ilocos Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2693, 168, 'ILS', 'Ilocos Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2694, 168, 'ISA', 'Isabela');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2695, 168, 'KAL', 'Kalinga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2696, 168, 'LAG', 'Laguna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2697, 168, 'LAN', 'Lanao del Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2698, 168, 'LAS', 'Lanao del Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2699, 168, 'LEY', 'Leyte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2700, 168, 'LUN', 'La Union');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2701, 168, 'MAD', 'Marinduque');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2702, 168, 'MAG', 'Maguindanao');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2703, 168, 'MAS', 'Masbate');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2704, 168, 'MDC', 'Mindoro Occidental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2705, 168, 'MDR', 'Mindoro Oriental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2706, 168, 'MOU', 'Mountain Province');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2707, 168, 'MSC', 'Misamis Occidental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2708, 168, 'MSR', 'Misamis Oriental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2709, 168, 'NCO', 'Cotabato');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2710, 168, 'NSA', 'Northern Samar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2711, 168, 'NEC', 'Negros Occidental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2712, 168, 'NER', 'Negros Oriental');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2713, 168, 'NUE', 'Nueva Ecija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2714, 168, 'NUV', 'Nueva Vizcaya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2715, 168, 'PAM', 'Pampanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2716, 168, 'PAN', 'Pangasinan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2717, 168, 'PLW', 'Palawan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2718, 168, 'QUE', 'Quezon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2719, 168, 'QUI', 'Quirino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2720, 168, 'RIZ', 'Rizal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2721, 168, 'ROM', 'Romblon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2722, 168, 'SAR', 'Sarangani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2723, 168, 'SCO', 'South Cotabato');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2724, 168, 'SIG', 'Siquijor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2725, 168, 'SLE', 'Southern Leyte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2726, 168, 'SLU', 'Sulu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2727, 168, 'SOR', 'Sorsogon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2728, 168, 'SUK', 'Sultan Kudarat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2729, 168, 'SUN', 'Surigao del Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2730, 168, 'SUR', 'Surigao del Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2731, 168, 'TAR', 'Tarlac');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2732, 168, 'TAW', 'Tawi-Tawi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2733, 168, 'WSA', 'Samar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2734, 168, 'ZAN', 'Zamboanga del Norte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2735, 168, 'ZAS', 'Zamboanga del Sur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2736, 168, 'ZMB', 'Zambales');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2737, 168, 'ZSI', 'Zamboanga Sibugay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2738, 170, 'DS', 'Dolnośląskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2739, 170, 'KP', 'Kujawsko-Pomorskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2740, 170, 'LU', 'Lubelskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2741, 170, 'LB', 'Lubuskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2742, 170, 'LD', '�?ódzkie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2743, 170, 'MA', 'Małopolskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2744, 170, 'MZ', 'Mazowieckie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2745, 170, 'OP', 'Opolskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2746, 170, 'PK', 'Podkarpackie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2747, 170, 'PD', 'Podlaskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2748, 170, 'PM', 'Pomorskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2749, 170, 'SL', 'Śląskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2750, 170, 'SK', 'Świętokrzyskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2751, 170, 'WN', 'Warmińsko-Mazurskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2752, 170, 'WP', 'Wielkopolskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2753, 170, 'ZP', 'Zachodniopomorskie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2754, 171, '01', 'Aveiro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2755, 171, '02', 'Beja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2756, 171, '03', 'Braga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2757, 171, '04', 'Bragança');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2758, 171, '05', 'Castelo Branco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2759, 171, '06', 'Coimbra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2760, 171, '07', 'Évora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2761, 171, '08', 'Faro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2762, 171, '09', 'Guarda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2763, 171, '10', 'Leiria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2764, 171, '11', 'Lisboa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2765, 171, '12', 'Portalegre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2766, 171, '13', 'Porto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2767, 171, '14', 'Santarém');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2768, 171, '15', 'Setúbal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2769, 171, '16', 'Viana do Castelo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2770, 171, '17', 'Vila Real');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2771, 171, '18', 'Viseu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2772, 171, '20', 'Região Autónoma dos Açores');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2773, 171, '30', 'Região Autónoma da Madeira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2774, 173, 'DA', 'الدوحة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2775, 173, 'GH', 'الغويرية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2776, 173, 'JB', 'جريان الباطنة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2777, 173, 'JU', 'الجميلية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2778, 173, 'KH', 'الخور');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2779, 173, 'ME', 'مسيعيد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2780, 173, 'MS', 'الشمال');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2781, 173, 'RA', 'الريان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2782, 173, 'US', 'أم صلال');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2783, 173, 'WA', 'الوكرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2784, 175, 'AB', 'Alba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2785, 175, 'AG', 'Argeş');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2786, 175, 'AR', 'Arad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2787, 175, 'B', 'Bucureşti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2788, 175, 'BC', 'Bacău');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2789, 175, 'BH', 'Bihor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2790, 175, 'BN', 'Bistriţa-Năsăud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2791, 175, 'BR', 'Brăila');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2792, 175, 'BT', 'Botoşani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2793, 175, 'BV', 'Braşov');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2794, 175, 'BZ', 'Buzău');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2795, 175, 'CJ', 'Cluj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2796, 175, 'CL', 'Călăraşi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2797, 175, 'CS', 'Caraş-Severin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2798, 175, 'CT', 'Constanţa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2799, 175, 'CV', 'Covasna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2800, 175, 'DB', 'Dâmboviţa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2801, 175, 'DJ', 'Dolj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2802, 175, 'GJ', 'Gorj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2803, 175, 'GL', 'Galaţi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2804, 175, 'GR', 'Giurgiu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2805, 175, 'HD', 'Hunedoara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2806, 175, 'HG', 'Harghita');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2807, 175, 'IF', 'Ilfov');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2808, 175, 'IL', 'Ialomiţa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2809, 175, 'IS', 'Iaşi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2810, 175, 'MH', 'Mehedinţi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2811, 175, 'MM', 'Maramureş');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2812, 175, 'MS', 'Mureş');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2813, 175, 'NT', 'Neamţ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2814, 175, 'OT', 'Olt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2815, 175, 'PH', 'Prahova');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2816, 175, 'SB', 'Sibiu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2817, 175, 'SJ', 'Sălaj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2818, 175, 'SM', 'Satu Mare');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2819, 175, 'SV', 'Suceava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2820, 175, 'TL', 'Tulcea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2821, 175, 'TM', 'Timiş');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2822, 175, 'TR', 'Teleorman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2823, 175, 'VL', 'Vâlcea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2824, 175, 'VN', 'Vrancea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2825, 175, 'VS', 'Vaslui');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2826, 176, 'AD', '�?дыге�?�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2827, 176, 'AGB', '�?ги�?н�?кий-Бур�?�?т�?кий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2828, 176, 'AL', '�?лта�?й Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2829, 176, 'ALT', '�?лта�?й�?кий край');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2830, 176, 'AMU', '�?му�?р�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2831, 176, 'ARK', '�?рха�?нгель�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2832, 176, 'AST', '�?�?траха�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2833, 176, 'BA', 'Башкорто�?та�?н Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2834, 176, 'BEL', 'Белгоро�?д�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2835, 176, 'BRY', 'Бр�?�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2836, 176, 'BU', 'Бур�?�?ти�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2837, 176, 'CE', 'Чече�?н�?ка�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2838, 176, 'CHE', 'Чел�?�?бин�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2839, 176, 'CHI', 'Чити�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2840, 176, 'CHU', 'Чуко�?т�?кий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2841, 176, 'CU', 'Чува�?ш�?ка�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2842, 176, 'DA', 'Даге�?та�?н Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2843, 176, 'EVE', 'Эвенки�?й�?кий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2844, 176, 'IN', 'Ингуше�?ти�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2845, 176, 'IRK', 'Ирку�?т�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2846, 176, 'IVA', 'Ива�?нов�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2847, 176, 'KAM', 'Камча�?т�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2848, 176, 'KB', 'Кабарди�?но-Балка�?р�?ка�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2849, 176, 'KC', 'Карача�?ево-Черке�?�?�?ка�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2850, 176, 'KDA', 'Кра�?нода�?р�?кий край');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2851, 176, 'KEM', 'Ке�?меров�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2852, 176, 'KGD', 'Калинингра�?д�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2853, 176, 'KGN', 'Курга�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2854, 176, 'KHA', 'Хаба�?ров�?кий край');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2855, 176, 'KHM', 'Ха�?нты-Ман�?и�?й�?кий автоно�?мный о�?круг—Югра�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2856, 176, 'KIA', 'Кра�?но�?�?р�?кий край');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2857, 176, 'KIR', 'Ки�?ров�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2858, 176, 'KK', 'Хака�?�?и�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2859, 176, 'KL', 'Калмы�?ки�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2860, 176, 'KLU', 'Калу�?ж�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2861, 176, 'KO', 'Ко�?ми Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2862, 176, 'KOR', 'Кор�?�?к�?кий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2863, 176, 'KOS', 'Ко�?тром�?ка�?�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2864, 176, 'KR', 'Каре�?ли�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2865, 176, 'KRS', 'Ку�?р�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2866, 176, 'LEN', 'Ленингра�?д�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2867, 176, 'LIP', 'Ли�?пецка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2868, 176, 'MAG', 'Магада�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2869, 176, 'ME', 'Мари�?й Эл Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2870, 176, 'MO', 'Мордо�?ви�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2871, 176, 'MOS', 'Мо�?ко�?в�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2872, 176, 'MOW', 'Мо�?ква�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2873, 176, 'MUR', 'Му�?рман�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2874, 176, 'NEN', '�?ене�?цкий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2875, 176, 'NGR', '�?овгоро�?д�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2876, 176, 'NIZ', '�?ижегоро�?д�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2877, 176, 'NVS', '�?ово�?иби�?р�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2878, 176, 'OMS', 'О�?м�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2879, 176, 'ORE', 'Оренбу�?рг�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2880, 176, 'ORL', 'Орло�?в�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2881, 176, 'PNZ', 'Пе�?нзен�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2882, 176, 'PRI', 'Примо�?р�?кий край');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2883, 176, 'PSK', 'П�?ко�?в�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2884, 176, 'ROS', 'Ро�?то�?в�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2885, 176, 'RYA', 'Р�?за�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2886, 176, 'SA', 'Саха�? (Яку�?ти�?) Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2887, 176, 'SAK', 'Сахали�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2888, 176, 'SAM', 'Сама�?р�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2889, 176, 'SAR', 'Сара�?тов�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2890, 176, 'SE', 'Се�?верна�? О�?е�?ти�?–�?ла�?ни�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2891, 176, 'SMO', 'Смол�?ен�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2892, 176, 'SPE', 'Санкт-Петербу�?рг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2893, 176, 'STA', 'Ставропо�?ль�?кий край');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2894, 176, 'SVE', 'Свердло�?в�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2895, 176, 'TA', 'Ре�?пу�?блика Татар�?та�?н');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2896, 176, 'TAM', 'Тамбо�?в�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2897, 176, 'TAY', 'Таймы�?р�?кий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2898, 176, 'TOM', 'То�?м�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2899, 176, 'TUL', 'Ту�?ль�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2900, 176, 'TVE', 'Твер�?ка�?�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2901, 176, 'TY', 'Тыва�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2902, 176, 'TYU', 'Тюме�?н�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2903, 176, 'UD', 'Удму�?рт�?ка�? Ре�?пу�?блика');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2904, 176, 'ULY', 'Уль�?�?нов�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2905, 176, 'UOB', 'У�?ть-Орды�?н�?кий Бур�?�?т�?кий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2906, 176, 'VGG', 'Волгогра�?д�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2907, 176, 'VLA', 'Влади�?мир�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2908, 176, 'VLG', 'Волого�?д�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2909, 176, 'VOR', 'Воро�?неж�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2910, 176, 'XXX', 'Пе�?рм�?кий край');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2911, 176, 'YAN', 'Яма�?ло-�?ене�?цкий автоно�?мный о�?круг');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2912, 176, 'YAR', 'Яро�?ла�?в�?ка�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2913, 176, 'YEV', 'Евре�?й�?ка�? автоно�?мна�? о�?бла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2914, 177, 'N', 'Nord');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2915, 177, 'E', 'Est');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2916, 177, 'S', 'Sud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2917, 177, 'O', 'Ouest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2918, 177, 'K', 'Kigali');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2919, 178, 'K', 'Saint Kitts');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2920, 178, 'N', 'Nevis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2921, 179, 'AR', 'Anse-la-Raye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2922, 179, 'CA', 'Castries');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2923, 179, 'CH', 'Choiseul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2924, 179, 'DA', 'Dauphin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2925, 179, 'DE', 'Dennery');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2926, 179, 'GI', 'Gros-Islet');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2927, 179, 'LA', 'Laborie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2928, 179, 'MI', 'Micoud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2929, 179, 'PR', 'Praslin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2930, 179, 'SO', 'Soufriere');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2931, 179, 'VF', 'Vieux-Fort');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2932, 180, 'C', 'Charlotte');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2933, 180, 'R', 'Grenadines');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2934, 180, 'A', 'Saint Andrew');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2935, 180, 'D', 'Saint David');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2936, 180, 'G', 'Saint George');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2937, 180, 'P', 'Saint Patrick');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2938, 181, 'AA', 'A''ana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2939, 181, 'AL', 'Aiga-i-le-Tai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2940, 181, 'AT', 'Atua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2941, 181, 'FA', 'Fa''asaleleaga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2942, 181, 'GE', 'Gaga''emauga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2943, 181, 'GI', 'Gaga''ifomauga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2944, 181, 'PA', 'Palauli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2945, 181, 'SA', 'Satupa''itea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2946, 181, 'TU', 'Tuamasaga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2947, 181, 'VF', 'Va''a-o-Fonoti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2948, 181, 'VS', 'Vaisigano');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2949, 182, 'AC', 'Acquaviva');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2950, 182, 'BM', 'Borgo Maggiore');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2951, 182, 'CH', 'Chiesanuova');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2952, 182, 'DO', 'Domagnano');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2953, 182, 'FA', 'Faetano');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2954, 182, 'FI', 'Fiorentino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2955, 182, 'MO', 'Montegiardino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2956, 182, 'SM', 'Citta di San Marino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2957, 182, 'SE', 'Serravalle');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2958, 183, 'P', 'Príncipe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2959, 183, 'S', 'São Tomé');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2960, 184, '01', 'الرياض');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2961, 184, '02', 'مكة المكرمة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2962, 184, '03', 'المدينه');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2963, 184, '04', 'الشرقية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2964, 184, '05', 'القصيم');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2965, 184, '06', 'حائل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2966, 184, '07', 'تبوك');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2967, 184, '08', 'الحدود الشمالية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2968, 184, '09', 'جيزان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2969, 184, '10', 'نجران');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2970, 184, '11', 'الباحة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2971, 184, '12', 'الجو�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2972, 184, '14', 'عسير');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2973, 185, 'DA', 'Dakar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2974, 185, 'DI', 'Diourbel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2975, 185, 'FA', 'Fatick');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2976, 185, 'KA', 'Kaolack');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2977, 185, 'KO', 'Kolda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2978, 185, 'LO', 'Louga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2979, 185, 'MA', 'Matam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2980, 185, 'SL', 'Saint-Louis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2981, 185, 'TA', 'Tambacounda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2982, 185, 'TH', 'Thies ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2983, 185, 'ZI', 'Ziguinchor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2984, 186, 'AP', 'Anse aux Pins');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2985, 186, 'AB', 'Anse Boileau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2986, 186, 'AE', 'Anse Etoile');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2987, 186, 'AL', 'Anse Louis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2988, 186, 'AR', 'Anse Royale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2989, 186, 'BL', 'Baie Lazare');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2990, 186, 'BS', 'Baie Sainte Anne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2991, 186, 'BV', 'Beau Vallon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2992, 186, 'BA', 'Bel Air');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2993, 186, 'BO', 'Bel Ombre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2994, 186, 'CA', 'Cascade');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2995, 186, 'GL', 'Glacis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2996, 186, 'GM', 'Grand'' Anse (on Mahe)');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2997, 186, 'GP', 'Grand'' Anse (on Praslin)');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2998, 186, 'DG', 'La Digue');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(2999, 186, 'RA', 'La Riviere Anglaise');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3000, 186, 'MB', 'Mont Buxton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3001, 186, 'MF', 'Mont Fleuri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3002, 186, 'PL', 'Plaisance');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3003, 186, 'PR', 'Pointe La Rue');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3004, 186, 'PG', 'Port Glaud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3005, 186, 'SL', 'Saint Louis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3006, 186, 'TA', 'Takamaka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3007, 187, 'E', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3008, 187, 'N', 'Northern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3009, 187, 'S', 'Southern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3010, 187, 'W', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3011, 189, 'BC', 'Banskobystrický kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3012, 189, 'BL', 'Bratislavský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3013, 189, 'KI', 'Košický kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3014, 189, 'NJ', 'Nitrianský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3015, 189, 'PV', 'Prešovský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3016, 189, 'TA', 'Trnavský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3017, 189, 'TC', 'Tren�?ianský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3018, 189, 'ZI', 'Žilinský kraj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3019, 190, '001', 'Ajdovš�?ina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3020, 190, '002', 'Beltinci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3021, 190, '003', 'Bled');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3022, 190, '004', 'Bohinj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3023, 190, '005', 'Borovnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3024, 190, '006', 'Bovec');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3025, 190, '007', 'Brda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3026, 190, '008', 'Brezovica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3027, 190, '009', 'Brežice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3028, 190, '010', 'Tišina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3029, 190, '011', 'Celje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3030, 190, '012', 'Cerklje na Gorenjskem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3031, 190, '013', 'Cerknica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3032, 190, '014', 'Cerkno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3033, 190, '015', 'Črenšovci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3034, 190, '016', 'Črna na Koroškem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3035, 190, '017', 'Črnomelj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3036, 190, '018', 'Destrnik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3037, 190, '019', 'Diva�?a');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3038, 190, '020', 'Dobrepolje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3039, 190, '021', 'Dobrova-Polhov Gradec');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3040, 190, '022', 'Dol pri Ljubljani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3041, 190, '023', 'Domžale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3042, 190, '024', 'Dornava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3043, 190, '025', 'Dravograd');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3044, 190, '026', 'Duplek');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3045, 190, '027', 'Gorenja vas-Poljane');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3046, 190, '028', 'Gorišnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3047, 190, '029', 'Gornja Radgona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3048, 190, '030', 'Gornji Grad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3049, 190, '031', 'Gornji Petrovci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3050, 190, '032', 'Grosuplje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3051, 190, '033', 'Šalovci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3052, 190, '034', 'Hrastnik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3053, 190, '035', 'Hrpelje-Kozina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3054, 190, '036', 'Idrija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3055, 190, '037', 'Ig');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3056, 190, '038', 'Ilirska Bistrica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3057, 190, '039', 'Ivan�?na Gorica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3058, 190, '040', 'Izola');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3059, 190, '041', 'Jesenice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3060, 190, '042', 'Juršinci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3061, 190, '043', 'Kamnik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3062, 190, '044', 'Kanal ob So�?i');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3063, 190, '045', 'Kidri�?evo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3064, 190, '046', 'Kobarid');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3065, 190, '047', 'Kobilje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3066, 190, '048', 'Ko�?evje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3067, 190, '049', 'Komen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3068, 190, '050', 'Koper');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3069, 190, '051', 'Kozje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3070, 190, '052', 'Kranj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3071, 190, '053', 'Kranjska Gora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3072, 190, '054', 'Krško');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3073, 190, '055', 'Kungota');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3074, 190, '056', 'Kuzma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3075, 190, '057', 'Laško');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3076, 190, '058', 'Lenart');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3077, 190, '059', 'Lendava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3078, 190, '060', 'Litija');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3079, 190, '061', 'Ljubljana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3080, 190, '062', 'Ljubno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3081, 190, '063', 'Ljutomer');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3082, 190, '064', 'Logatec');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3083, 190, '065', 'Loška Dolina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3084, 190, '066', 'Loški Potok');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3085, 190, '067', 'Lu�?e');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3086, 190, '068', 'Lukovica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3087, 190, '069', 'Majšperk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3088, 190, '070', 'Maribor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3089, 190, '071', 'Medvode');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3090, 190, '072', 'Mengeš');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3091, 190, '073', 'Metlika');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3092, 190, '074', 'Mežica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3093, 190, '075', 'Miren-Kostanjevica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3094, 190, '076', 'Mislinja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3095, 190, '077', 'Morav�?e');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3096, 190, '078', 'Moravske Toplice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3097, 190, '079', 'Mozirje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3098, 190, '080', 'Murska Sobota');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3099, 190, '081', 'Muta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3100, 190, '082', 'Naklo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3101, 190, '083', 'Nazarje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3102, 190, '084', 'Nova Gorica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3103, 190, '085', 'Novo mesto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3104, 190, '086', 'Odranci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3105, 190, '087', 'Ormož');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3106, 190, '088', 'Osilnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3107, 190, '089', 'Pesnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3108, 190, '090', 'Piran');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3109, 190, '091', 'Pivka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3110, 190, '092', 'Pod�?etrtek');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3111, 190, '093', 'Podvelka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3112, 190, '094', 'Postojna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3113, 190, '095', 'Preddvor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3114, 190, '096', 'Ptuj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3115, 190, '097', 'Puconci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3116, 190, '098', 'Ra�?e-Fram');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3117, 190, '099', 'Rade�?e');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3118, 190, '100', 'Radenci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3119, 190, '101', 'Radlje ob Dravi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3120, 190, '102', 'Radovljica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3121, 190, '103', 'Ravne na Koroškem');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3122, 190, '104', 'Ribnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3123, 190, '106', 'Rogaška Slatina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3124, 190, '105', 'Rogašovci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3125, 190, '107', 'Rogatec');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3126, 190, '108', 'Ruše');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3127, 190, '109', 'Semi�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3128, 190, '110', 'Sevnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3129, 190, '111', 'Sežana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3130, 190, '112', 'Slovenj Gradec');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3131, 190, '113', 'Slovenska Bistrica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3132, 190, '114', 'Slovenske Konjice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3133, 190, '115', 'Starše');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3134, 190, '116', 'Sveti Jurij');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3135, 190, '117', 'Šen�?ur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3136, 190, '118', 'Šentilj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3137, 190, '119', 'Šentjernej');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3138, 190, '120', 'Šentjur pri Celju');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3139, 190, '121', 'Škocjan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3140, 190, '122', 'Škofja Loka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3141, 190, '123', 'Škofljica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3142, 190, '124', 'Šmarje pri Jelšah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3143, 190, '125', 'Šmartno ob Paki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3144, 190, '126', 'Šoštanj');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3145, 190, '127', 'Štore');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3146, 190, '128', 'Tolmin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3147, 190, '129', 'Trbovlje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3148, 190, '130', 'Trebnje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3149, 190, '131', 'Trži�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3150, 190, '132', 'Turniš�?e');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3151, 190, '133', 'Velenje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3152, 190, '134', 'Velike Laš�?e');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3153, 190, '135', 'Videm');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3154, 190, '136', 'Vipava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3155, 190, '137', 'Vitanje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3156, 190, '138', 'Vodice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3157, 190, '139', 'Vojnik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3158, 190, '140', 'Vrhnika');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3159, 190, '141', 'Vuzenica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3160, 190, '142', 'Zagorje ob Savi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3161, 190, '143', 'Zavr�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3162, 190, '144', 'Zre�?e');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3163, 190, '146', 'Železniki');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3164, 190, '147', 'Žiri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3165, 190, '148', 'Benedikt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3166, 190, '149', 'Bistrica ob Sotli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3167, 190, '150', 'Bloke');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3168, 190, '151', 'Braslov�?e');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3169, 190, '152', 'Cankova');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3170, 190, '153', 'Cerkvenjak');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3171, 190, '154', 'Dobje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3172, 190, '155', 'Dobrna');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3173, 190, '156', 'Dobrovnik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3174, 190, '157', 'Dolenjske Toplice');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3175, 190, '158', 'Grad');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3176, 190, '159', 'Hajdina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3177, 190, '160', 'Ho�?e-Slivnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3178, 190, '161', 'Hodoš');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3179, 190, '162', 'Horjul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3180, 190, '163', 'Jezersko');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3181, 190, '164', 'Komenda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3182, 190, '165', 'Kostel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3183, 190, '166', 'Križevci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3184, 190, '167', 'Lovrenc na Pohorju');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3185, 190, '168', 'Markovci');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3186, 190, '169', 'Miklavž na Dravskem polju');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3187, 190, '170', 'Mirna Pe�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3188, 190, '171', 'Oplotnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3189, 190, '172', 'Podlehnik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3190, 190, '173', 'Polzela');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3191, 190, '174', 'Prebold');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3192, 190, '175', 'Prevalje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3193, 190, '176', 'Razkrižje');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3194, 190, '177', 'Ribnica na Pohorju');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3195, 190, '178', 'Selnica ob Dravi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3196, 190, '179', 'Sodražica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3197, 190, '180', 'Sol�?ava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3198, 190, '181', 'Sveta Ana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3199, 190, '182', 'Sveti Andraž v Slovenskih goricah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3200, 190, '183', 'Šempeter-Vrtojba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3201, 190, '184', 'Tabor');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3202, 190, '185', 'Trnovska vas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3203, 190, '186', 'Trzin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3204, 190, '187', 'Velika Polana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3205, 190, '188', 'Veržej');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3206, 190, '189', 'Vransko');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3207, 190, '190', 'Žalec');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3208, 190, '191', 'Žetale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3209, 190, '192', 'Žirovnica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3210, 190, '193', 'Žužemberk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3211, 190, '194', 'Šmartno pri Litiji');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3212, 191, 'CE', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3213, 191, 'CH', 'Choiseul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3214, 191, 'GC', 'Guadalcanal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3215, 191, 'HO', 'Honiara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3216, 191, 'IS', 'Isabel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3217, 191, 'MK', 'Makira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3218, 191, 'ML', 'Malaita');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3219, 191, 'RB', 'Rennell and Bellona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3220, 191, 'TM', 'Temotu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3221, 191, 'WE', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3222, 192, 'AD', 'Awdal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3223, 192, 'BK', 'Bakool');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3224, 192, 'BN', 'Banaadir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3225, 192, 'BR', 'Bari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3226, 192, 'BY', 'Bay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3227, 192, 'GD', 'Gedo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3228, 192, 'GG', 'Galguduud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3229, 192, 'HR', 'Hiiraan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3230, 192, 'JD', 'Jubbada Dhexe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3231, 192, 'JH', 'Jubbada Hoose');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3232, 192, 'MD', 'Mudug');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3233, 192, 'NG', 'Nugaal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3234, 192, 'SD', 'Shabeellaha Dhexe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3235, 192, 'SG', 'Sanaag');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3236, 192, 'SH', 'Shabeellaha Hoose');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3237, 192, 'SL', 'Sool');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3238, 192, 'TG', 'Togdheer');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3239, 192, 'WG', 'Woqooyi Galbeed');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3240, 193, 'EC', 'Eastern Cape');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3241, 193, 'FS', 'Free State');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3242, 193, 'GT', 'Gauteng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3243, 193, 'LP', 'Limpopo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3244, 193, 'MP', 'Mpumalanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3245, 193, 'NC', 'Northern Cape');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3246, 193, 'NL', 'KwaZulu-Natal');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3247, 193, 'NW', 'North-West');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3248, 193, 'WC', 'Western Cape');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3249, 195, 'AN', 'Andalucía');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3250, 195, 'AR', 'Aragón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3251, 195, 'A', 'Alicante');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3252, 195, 'AB', 'Albacete');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3253, 195, 'AL', 'Almería');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3254, 195, 'AN', 'Andalucía');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3255, 195, 'AV', '�?vila');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3256, 195, 'B', 'Barcelona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3257, 195, 'BA', 'Badajoz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3258, 195, 'BI', 'Vizcaya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3259, 195, 'BU', 'Burgos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3260, 195, 'C', 'A Coruña');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3261, 195, 'CA', 'Cádiz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3262, 195, 'CC', 'Cáceres');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3263, 195, 'CE', 'Ceuta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3264, 195, 'CL', 'Castilla y León');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3265, 195, 'CM', 'Castilla-La Mancha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3266, 195, 'CN', 'Islas Canarias');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3267, 195, 'CO', 'Córdoba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3268, 195, 'CR', 'Ciudad Real');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3269, 195, 'CS', 'Castellón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3270, 195, 'CT', 'Catalonia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3271, 195, 'CU', 'Cuenca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3272, 195, 'EX', 'Extremadura');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3273, 195, 'GA', 'Galicia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3274, 195, 'GC', 'Las Palmas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3275, 195, 'GI', 'Girona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3276, 195, 'GR', 'Granada');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3277, 195, 'GU', 'Guadalajara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3278, 195, 'H', 'Huelva');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3279, 195, 'HU', 'Huesca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3280, 195, 'IB', 'Islas Baleares');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3281, 195, 'J', 'Jaén');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3282, 195, 'L', 'Lleida');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3283, 195, 'LE', 'León');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3284, 195, 'LO', 'La Rioja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3285, 195, 'LU', 'Lugo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3286, 195, 'M', 'Madrid');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3287, 195, 'MA', 'Málaga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3288, 195, 'ML', 'Melilla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3289, 195, 'MU', 'Murcia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3290, 195, 'NA', 'Navarre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3291, 195, 'O', 'Asturias');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3292, 195, 'OR', 'Ourense');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3293, 195, 'P', 'Palencia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3294, 195, 'PM', 'Baleares');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3295, 195, 'PO', 'Pontevedra');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3296, 195, 'PV', 'Basque Euskadi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3297, 195, 'S', 'Cantabria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3298, 195, 'SA', 'Salamanca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3299, 195, 'SE', 'Seville');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3300, 195, 'SG', 'Segovia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3301, 195, 'SO', 'Soria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3302, 195, 'SS', 'Guipúzcoa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3303, 195, 'T', 'Tarragona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3304, 195, 'TE', 'Teruel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3305, 195, 'TF', 'Santa Cruz De Tenerife');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3306, 195, 'TO', 'Toledo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3307, 195, 'V', 'Valencia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3308, 195, 'VA', 'Valladolid');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3309, 195, 'VI', '�?lava');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3310, 195, 'Z', 'Zaragoza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3311, 195, 'ZA', 'Zamora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3312, 196, 'CE', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3313, 196, 'NC', 'North Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3314, 196, 'NO', 'North');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3315, 196, 'EA', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3316, 196, 'NW', 'North Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3317, 196, 'SO', 'Southern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3318, 196, 'UV', 'Uva');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3319, 196, 'SA', 'Sabaragamuwa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3320, 196, 'WE', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3321, 199, 'ANL', 'أعالي النيل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3322, 199, 'BAM', 'البحر الأحمر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3323, 199, 'BRT', 'البحيرات');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3324, 199, 'JZR', 'ولاية الجزيرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3325, 199, 'KRT', 'الخرطوم');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3326, 199, 'QDR', 'القضار�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3327, 199, 'WDH', 'الوحدة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3328, 199, 'ANB', 'النيل الأبيض');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3329, 199, 'ANZ', 'النيل الأزرق');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3330, 199, 'ASH', 'الشمالية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3331, 199, 'BJA', 'الاستوائية الوسطى');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3332, 199, 'GIS', 'غرب الاستوائية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3333, 199, 'GBG', 'غرب بحر الغزال');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3334, 199, 'GDA', 'غرب دار�?ور');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3335, 199, 'GKU', 'غرب كرد�?ان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3336, 199, 'JDA', 'جنوب دار�?ور');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3337, 199, 'JKU', 'جنوب كرد�?ان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3338, 199, 'JQL', 'جونقلي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3339, 199, 'KSL', 'كسلا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3340, 199, 'NNL', 'ولاية نهر النيل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3341, 199, 'SBG', 'شمال بحر الغزال');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3342, 199, 'SDA', 'شمال دار�?ور');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3343, 199, 'SKU', 'شمال كرد�?ان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3344, 199, 'SIS', 'شرق الاستوائية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3345, 199, 'SNR', 'سنار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3346, 199, 'WRB', 'واراب');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3347, 200, 'BR', 'Brokopondo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3348, 200, 'CM', 'Commewijne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3349, 200, 'CR', 'Coronie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3350, 200, 'MA', 'Marowijne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3351, 200, 'NI', 'Nickerie');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3352, 200, 'PM', 'Paramaribo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3353, 200, 'PR', 'Para');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3354, 200, 'SA', 'Saramacca');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3355, 200, 'SI', 'Sipaliwini');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3356, 200, 'WA', 'Wanica');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3357, 202, 'HH', 'Hhohho');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3358, 202, 'LU', 'Lubombo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3359, 202, 'MA', 'Manzini');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3360, 202, 'SH', 'Shiselweni');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3361, 203, 'AB', 'Stockholms län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3362, 203, 'C', 'Uppsala län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3363, 203, 'D', 'Södermanlands län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3364, 203, 'E', 'Östergötlands län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3365, 203, 'F', 'Jönköpings län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3366, 203, 'G', 'Kronobergs län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3367, 203, 'H', 'Kalmar län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3368, 203, 'I', 'Gotlands län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3369, 203, 'K', 'Blekinge län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3370, 203, 'M', 'Skåne län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3371, 203, 'N', 'Hallands län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3372, 203, 'O', 'Västra Götalands län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3373, 203, 'S', 'Värmlands län;');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3374, 203, 'T', 'Örebro län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3375, 203, 'U', 'Västmanlands län;');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3376, 203, 'W', 'Dalarnas län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3377, 203, 'X', 'Gävleborgs län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3378, 203, 'Y', 'Västernorrlands län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3379, 203, 'Z', 'Jämtlands län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3380, 203, 'AC', 'Västerbottens län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3381, 203, 'BD', 'Norrbottens län');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3382, 204, 'ZH', 'Zürich');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3383, 204, 'BE', 'Bern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3384, 204, 'LU', 'Luzern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3385, 204, 'UR', 'Uri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3386, 204, 'SZ', 'Schwyz');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3387, 204, 'OW', 'Obwalden');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3388, 204, 'NW', 'Nidwalden');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3389, 204, 'GL', 'Glasrus');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3390, 204, 'ZG', 'Zug');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3391, 204, 'FR', 'Fribourg');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3392, 204, 'SO', 'Solothurn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3393, 204, 'BS', 'Basel-Stadt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3394, 204, 'BL', 'Basel-Landschaft');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3395, 204, 'SH', 'Schaffhausen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3396, 204, 'AR', 'Appenzell Ausserrhoden');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3397, 204, 'AI', 'Appenzell Innerrhoden');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3398, 204, 'SG', 'Saint Gallen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3399, 204, 'GR', 'Graubünden');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3400, 204, 'AG', 'Aargau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3401, 204, 'TG', 'Thurgau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3402, 204, 'TI', 'Ticino');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3403, 204, 'VD', 'Vaud');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3404, 204, 'VS', 'Valais');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3405, 204, 'NE', 'Nuechâtel');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3406, 204, 'GE', 'Genève');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3407, 204, 'JU', 'Jura');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3408, 205, 'DI', 'دمشق');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3409, 205, 'DR', 'درعا');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3410, 205, 'DZ', 'دير الزور');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3411, 205, 'HA', 'الحسكة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3412, 205, 'HI', 'حمص');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3413, 205, 'HL', 'حلب');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3414, 205, 'HM', 'حماه');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3415, 205, 'ID', 'ادلب');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3416, 205, 'LA', 'اللاذقية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3417, 205, 'QU', 'القنيطرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3418, 205, 'RA', 'الرقة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3419, 205, 'RD', 'ری�? دمشق');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3420, 205, 'SU', 'السويداء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3421, 205, 'TA', 'طرطوس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3422, 206, 'CHA', '彰化縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3423, 206, 'CYI', '嘉義市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3424, 206, 'CYQ', '嘉義縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3425, 206, 'HSQ', '新竹縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3426, 206, 'HSZ', '新竹市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3427, 206, 'HUA', '花蓮縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3428, 206, 'ILA', '宜蘭縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3429, 206, 'KEE', '基隆市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3430, 206, 'KHH', '高雄市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3431, 206, 'KHQ', '高雄縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3432, 206, 'MIA', '苗栗縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3433, 206, 'NAN', '�?�投縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3434, 206, 'PEN', '澎湖縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3435, 206, 'PIF', '�?�?�縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3436, 206, 'TAO', '桃�?县');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3437, 206, 'TNN', '�?��?�市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3438, 206, 'TNQ', '�?��?�縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3439, 206, 'TPE', '臺北市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3440, 206, 'TPQ', '臺北縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3441, 206, 'TTT', '�?��?�縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3442, 206, 'TXG', '�?�中市');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3443, 206, 'TXQ', '�?�中縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3444, 206, 'YUN', '雲林縣');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3445, 207, 'GB', 'کوهستان بدخشان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3446, 207, 'KT', 'ختلان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3447, 207, 'SU', 'سغد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3448, 208, '01', 'Arusha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3449, 208, '02', 'Dar es Salaam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3450, 208, '03', 'Dodoma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3451, 208, '04', 'Iringa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3452, 208, '05', 'Kagera');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3453, 208, '06', 'Pemba Sever');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3454, 208, '07', 'Zanzibar Sever');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3455, 208, '08', 'Kigoma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3456, 208, '09', 'Kilimanjaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3457, 208, '10', 'Pemba Jih');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3458, 208, '11', 'Zanzibar Jih');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3459, 208, '12', 'Lindi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3460, 208, '13', 'Mara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3461, 208, '14', 'Mbeya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3462, 208, '15', 'Zanzibar Západ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3463, 208, '16', 'Morogoro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3464, 208, '17', 'Mtwara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3465, 208, '18', 'Mwanza');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3466, 208, '19', 'Pwani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3467, 208, '20', 'Rukwa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3468, 208, '21', 'Ruvuma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3469, 208, '22', 'Shinyanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3470, 208, '23', 'Singida');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3471, 208, '24', 'Tabora');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3472, 208, '25', 'Tanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3473, 208, '26', 'Manyara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3474, 209, 'TH-10', '�?รุงเทพมหานคร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3475, 209, 'TH-11', 'สมุทรปรา�?าร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3476, 209, 'TH-12', 'นนทบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3477, 209, 'TH-13', 'ปทุมธานี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3478, 209, 'TH-14', 'พระนครศรีอยุธยา');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3479, 209, 'TH-15', 'อ่างทอง');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3480, 209, 'TH-16', 'ลพบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3481, 209, 'TH-17', 'สิงห์บุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3482, 209, 'TH-18', 'ชัยนาท');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3483, 209, 'TH-19', 'สระบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3484, 209, 'TH-20', 'ชลบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3485, 209, 'TH-21', 'ระยอง');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3486, 209, 'TH-22', 'จันทบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3487, 209, 'TH-23', 'ตราด');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3488, 209, 'TH-24', 'ฉะเชิงเทรา');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3489, 209, 'TH-25', 'ปราจีนบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3490, 209, 'TH-26', 'นครนาย�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3491, 209, 'TH-27', 'สระ�?�?้ว');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3492, 209, 'TH-30', 'นครราชสีมา');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3493, 209, 'TH-31', 'บุรีรัมย์');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3494, 209, 'TH-32', 'สุรินทร์');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3495, 209, 'TH-33', 'ศรีสะเ�?ษ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3496, 209, 'TH-34', 'อุบลราชธานี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3497, 209, 'TH-35', 'ยโสธร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3498, 209, 'TH-36', 'ชัยภูมิ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3499, 209, 'TH-37', 'อำนาจเจริ�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3500, 209, 'TH-39', 'หนองบัวลำภู');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3501, 209, 'TH-40', 'ขอน�?�?่น');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3502, 209, 'TH-41', 'อุดรธานี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3503, 209, 'TH-42', 'เลย');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3504, 209, 'TH-43', 'หนองคาย');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3505, 209, 'TH-44', 'มหาสารคาม');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3506, 209, 'TH-45', 'ร้อยเอ็ด');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3507, 209, 'TH-46', '�?าฬสินธุ์');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3508, 209, 'TH-47', 'ส�?ลนคร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3509, 209, 'TH-48', 'นครพนม');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3510, 209, 'TH-49', 'มุ�?ดาหาร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3511, 209, 'TH-50', 'เชียงใหม่');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3512, 209, 'TH-51', 'ลำพูน');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3513, 209, 'TH-52', 'ลำปาง');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3514, 209, 'TH-53', 'อุตรดิตถ์');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3515, 209, 'TH-55', 'น่าน');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3516, 209, 'TH-56', 'พะเยา');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3517, 209, 'TH-57', 'เชียงราย');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3518, 209, 'TH-58', '�?ม่ฮ่องสอน');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3519, 209, 'TH-60', 'นครสวรรค์');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3520, 209, 'TH-61', 'อุทัยธานี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3521, 209, 'TH-62', '�?ำ�?พงเพชร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3522, 209, 'TH-63', 'ตา�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3523, 209, 'TH-64', 'สุโขทัย');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3524, 209, 'TH-66', 'ชุมพร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3525, 209, 'TH-67', 'พิจิตร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3526, 209, 'TH-70', 'ราชบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3527, 209, 'TH-71', '�?า�?จนบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3528, 209, 'TH-72', 'สุพรรณบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3529, 209, 'TH-73', 'นครป�?ม');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3530, 209, 'TH-74', 'สมุทรสาคร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3531, 209, 'TH-75', 'สมุทรสงคราม');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3532, 209, 'TH-76', 'เพชรบุรี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3533, 209, 'TH-77', 'ประจวบคีรีขันธ์');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3534, 209, 'TH-80', 'นครศรีธรรมราช');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3535, 209, 'TH-81', '�?ระบี่');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3536, 209, 'TH-82', 'พังงา');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3537, 209, 'TH-83', 'ภูเ�?็ต');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3538, 209, 'TH-84', 'สุราษฎร์ธานี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3539, 209, 'TH-85', 'ระนอง');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3540, 209, 'TH-86', 'ชุมพร');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3541, 209, 'TH-90', 'สงขลา');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3542, 209, 'TH-91', 'สตูล');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3543, 209, 'TH-92', 'ตรัง');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3544, 209, 'TH-93', 'พัทลุง');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3545, 209, 'TH-94', 'ปัตตานี');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3546, 209, 'TH-95', 'ยะลา');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3547, 209, 'TH-96', 'นราธิวาส');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3548, 210, 'C', 'Centrale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3549, 210, 'K', 'Kara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3550, 210, 'M', 'Maritime');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3551, 210, 'P', 'Plateaux');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3552, 210, 'S', 'Savanes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3553, 211, 'A', 'Atafu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3554, 211, 'F', 'Fakaofo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3555, 211, 'N', 'Nukunonu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3556, 212, 'H', 'Ha''apai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3557, 212, 'T', 'Tongatapu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3558, 212, 'V', 'Vava''u');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3559, 213, 'ARI', 'Arima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3560, 213, 'CHA', 'Chaguanas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3561, 213, 'CTT', 'Couva-Tabaquite-Talparo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3562, 213, 'DMN', 'Diego Martin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3563, 213, 'ETO', 'Eastern Tobago');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3564, 213, 'RCM', 'Rio Claro-Mayaro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3565, 213, 'PED', 'Penal-Debe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3566, 213, 'PTF', 'Point Fortin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3567, 213, 'POS', 'Port of Spain');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3568, 213, 'PRT', 'Princes Town');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3569, 213, 'SFO', 'San Fernando');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3570, 213, 'SGE', 'Sangre Grande');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3571, 213, 'SJL', 'San Juan-Laventille');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3572, 213, 'SIP', 'Siparia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3573, 213, 'TUP', 'Tunapuna-Piarco');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3574, 213, 'WTO', 'Western Tobago');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3575, 214, '11', 'ولاية تونس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3576, 214, '12', 'ولاية أريانة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3577, 214, '13', 'ولاية بن عروس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3578, 214, '14', 'ولاية منوبة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3579, 214, '21', 'ولاية نابل');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3580, 214, '22', 'ولاية زغوان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3581, 214, '23', 'ولاية بنزرت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3582, 214, '31', 'ولاية باجة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3583, 214, '32', 'ولاية جندوبة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3584, 214, '33', 'ولاية الكا�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3585, 214, '34', 'ولاية سليانة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3586, 214, '41', 'ولاية القيروان');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3587, 214, '42', 'ولاية القصرين');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3588, 214, '43', 'ولاية سيدي بوزيد');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3589, 214, '51', 'ولاية سوسة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3590, 214, '52', 'ولاية المنستير');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3591, 214, '53', 'ولاية المهدية');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3592, 214, '61', 'ولاية ص�?اقس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3593, 214, '71', 'ولاية ق�?صة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3594, 214, '72', 'ولاية توزر');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3595, 214, '73', 'ولاية قبلي');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3596, 214, '81', 'ولاية قابس');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3597, 214, '82', 'ولاية مدنين');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3598, 214, '83', 'ولاية تطاوين');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3599, 215, '01', 'Adana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3600, 215, '02', 'Adıyaman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3601, 215, '03', 'Afyonkarahisar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3602, 215, '04', 'Ağrı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3603, 215, '05', 'Amasya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3604, 215, '06', 'Ankara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3605, 215, '07', 'Antalya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3606, 215, '08', 'Artvin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3607, 215, '09', 'Aydın');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3608, 215, '10', 'Balıkesir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3609, 215, '11', 'Bilecik');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3610, 215, '12', 'Bingöl');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3611, 215, '13', 'Bitlis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3612, 215, '14', 'Bolu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3613, 215, '15', 'Burdur');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3614, 215, '16', 'Bursa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3615, 215, '17', 'Çanakkale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3616, 215, '18', 'Çankırı');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3617, 215, '19', 'Çorum');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3618, 215, '20', 'Denizli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3619, 215, '21', 'Diyarbakır');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3620, 215, '22', 'Edirne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3621, 215, '23', 'Elazığ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3622, 215, '24', 'Erzincan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3623, 215, '25', 'Erzurum');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3624, 215, '26', 'Eskişehir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3625, 215, '27', 'Gaziantep');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3626, 215, '28', 'Giresun');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3627, 215, '29', 'Gümüşhane');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3628, 215, '30', 'Hakkari');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3629, 215, '31', 'Hatay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3630, 215, '32', 'Isparta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3631, 215, '33', 'Mersin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3632, 215, '34', 'İstanbul');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3633, 215, '35', 'İzmir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3634, 215, '36', 'Kars');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3635, 215, '37', 'Kastamonu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3636, 215, '38', 'Kayseri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3637, 215, '39', 'Kırklareli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3638, 215, '40', 'Kırşehir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3639, 215, '41', 'Kocaeli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3640, 215, '42', 'Konya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3641, 215, '43', 'Kütahya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3642, 215, '44', 'Malatya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3643, 215, '45', 'Manisa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3644, 215, '46', 'Kahramanmaraş');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3645, 215, '47', 'Mardin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3646, 215, '48', 'Muğla');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3647, 215, '49', 'Muş');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3648, 215, '50', 'Nevşehir');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3649, 215, '51', 'Niğde');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3650, 215, '52', 'Ordu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3651, 215, '53', 'Rize');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3652, 215, '54', 'Sakarya');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3653, 215, '55', 'Samsun');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3654, 215, '56', 'Siirt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3655, 215, '57', 'Sinop');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3656, 215, '58', 'Sivas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3657, 215, '59', 'Tekirdağ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3658, 215, '60', 'Tokat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3659, 215, '61', 'Trabzon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3660, 215, '62', 'Tunceli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3661, 215, '63', 'Şanlıurfa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3662, 215, '64', 'Uşak');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3663, 215, '65', 'Van');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3664, 215, '66', 'Yozgat');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3665, 215, '67', 'Zonguldak');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3666, 215, '68', 'Aksaray');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3667, 215, '69', 'Bayburt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3668, 215, '70', 'Karaman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3669, 215, '71', 'Kırıkkale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3670, 215, '72', 'Batman');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3671, 215, '73', 'Şırnak');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3672, 215, '74', 'Bartın');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3673, 215, '75', 'Ardahan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3674, 215, '76', 'Iğdır');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3675, 215, '77', 'Yalova');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3676, 215, '78', 'Karabük');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3677, 215, '79', 'Kilis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3678, 215, '80', 'Osmaniye');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3679, 215, '81', 'Düzce');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3680, 216, 'A', 'Ahal welaýaty');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3681, 216, 'B', 'Balkan welaýaty');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3682, 216, 'D', 'Daşoguz welaýaty');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3683, 216, 'L', 'Lebap welaýaty');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3684, 216, 'M', 'Mary welaýaty');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3685, 217, 'AC', 'Ambergris Cays');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3686, 217, 'DC', 'Dellis Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3687, 217, 'FC', 'French Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3688, 217, 'LW', 'Little Water Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3689, 217, 'RC', 'Parrot Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3690, 217, 'PN', 'Pine Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3691, 217, 'SL', 'Salt Cay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3692, 217, 'GT', 'Grand Turk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3693, 217, 'SC', 'South Caicos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3694, 217, 'EC', 'East Caicos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3695, 217, 'MC', 'Middle Caicos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3696, 217, 'NC', 'North Caicos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3697, 217, 'PR', 'Providenciales');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3698, 217, 'WC', 'West Caicos');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3699, 218, 'FUN', 'Funafuti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3700, 218, 'NMA', 'Nanumea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3701, 218, 'NMG', 'Nanumanga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3702, 218, 'NIT', 'Niutao');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3703, 218, 'NIU', 'Nui');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3704, 218, 'NKF', 'Nukufetau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3705, 218, 'NKL', 'Nukulaelae');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3706, 218, 'VAI', 'Vaitupu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3707, 219, '101', 'Kalangala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3708, 219, '102', 'Kampala');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3709, 219, '103', 'Kiboga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3710, 219, '104', 'Luwero');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3711, 219, '105', 'Masaka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3712, 219, '106', 'Mpigi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3713, 219, '107', 'Mubende');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3714, 219, '108', 'Mukono');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3715, 219, '109', 'Nakasongola');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3716, 219, '110', 'Rakai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3717, 219, '111', 'Sembabule');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3718, 219, '112', 'Kayunga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3719, 219, '113', 'Wakiso');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3720, 219, '201', 'Bugiri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3721, 219, '202', 'Busia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3722, 219, '203', 'Iganga');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3723, 219, '204', 'Jinja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3724, 219, '205', 'Kamuli');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3725, 219, '206', 'Kapchorwa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3726, 219, '207', 'Katakwi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3727, 219, '208', 'Kumi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3728, 219, '209', 'Mbale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3729, 219, '210', 'Pallisa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3730, 219, '211', 'Soroti');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3731, 219, '212', 'Tororo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3732, 219, '213', 'Kaberamaido');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3733, 219, '214', 'Mayuge');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3734, 219, '215', 'Sironko');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3735, 219, '301', 'Adjumani');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3736, 219, '302', 'Apac');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3737, 219, '303', 'Arua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3738, 219, '304', 'Gulu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3739, 219, '305', 'Kitgum');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3740, 219, '306', 'Kotido');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3741, 219, '307', 'Lira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3742, 219, '308', 'Moroto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3743, 219, '309', 'Moyo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3744, 219, '310', 'Nebbi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3745, 219, '311', 'Nakapiripirit');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3746, 219, '312', 'Pader');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3747, 219, '313', 'Yumbe');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3748, 219, '401', 'Bundibugyo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3749, 219, '402', 'Bushenyi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3750, 219, '403', 'Hoima');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3751, 219, '404', 'Kabale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3752, 219, '405', 'Kabarole');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3753, 219, '406', 'Kasese');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3754, 219, '407', 'Kibale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3755, 219, '408', 'Kisoro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3756, 219, '409', 'Masindi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3757, 219, '410', 'Mbarara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3758, 219, '411', 'Ntungamo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3759, 219, '412', 'Rukungiri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3760, 219, '413', 'Kamwenge');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3761, 219, '414', 'Kanungu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3762, 219, '415', 'Kyenjojo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3763, 220, '05', 'Вінницька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3764, 220, '07', 'Волин�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3765, 220, '09', 'Луган�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3766, 220, '12', 'Дніпропетров�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3767, 220, '14', 'Донецька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3768, 220, '18', 'Житомир�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3769, 220, '19', 'Рівнен�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3770, 220, '21', 'Закарпат�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3771, 220, '23', 'Запорізька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3772, 220, '26', 'Івано-Франків�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3773, 220, '30', 'Київ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3774, 220, '32', 'Київ�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3775, 220, '35', 'Кіровоград�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3776, 220, '40', 'Сева�?тополь');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3777, 220, '43', '�?втономна�? Ре�?публика Крым');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3778, 220, '46', 'Львів�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3779, 220, '48', 'Миколаїв�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3780, 220, '51', 'Оде�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3781, 220, '53', 'Полтав�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3782, 220, '59', 'Сум�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3783, 220, '61', 'Тернопіль�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3784, 220, '63', 'Харків�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3785, 220, '65', 'Хер�?он�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3786, 220, '68', 'Хмельницька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3787, 220, '71', 'Черка�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3788, 220, '74', 'Чернігів�?ька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3789, 220, '77', 'Чернівецька обла�?ть');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3790, 222, 'ABD', 'Aberdeenshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3791, 222, 'ABE', 'Aberdeen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3792, 222, 'AGB', 'Argyll and Bute');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3793, 222, 'AGY', 'Isle of Anglesey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3794, 222, 'ANS', 'Angus');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3795, 222, 'ANT', 'Antrim');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3796, 222, 'ARD', 'Ards');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3797, 222, 'ARM', 'Armagh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3798, 222, 'BAS', 'Bath and North East Somerset');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3799, 222, 'BBD', 'Blackburn with Darwen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3800, 222, 'BDF', 'Bedfordshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3801, 222, 'BDG', 'Barking and Dagenham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3802, 222, 'BEN', 'Brent');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3803, 222, 'BEX', 'Bexley');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3804, 222, 'BFS', 'Belfast');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3805, 222, 'BGE', 'Bridgend');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3806, 222, 'BGW', 'Blaenau Gwent');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3807, 222, 'BIR', 'Birmingham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3808, 222, 'BKM', 'Buckinghamshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3809, 222, 'BLA', 'Ballymena');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3810, 222, 'BLY', 'Ballymoney');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3811, 222, 'BMH', 'Bournemouth');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3812, 222, 'BNB', 'Banbridge');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3813, 222, 'BNE', 'Barnet');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3814, 222, 'BNH', 'Brighton and Hove');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3815, 222, 'BNS', 'Barnsley');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3816, 222, 'BOL', 'Bolton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3817, 222, 'BPL', 'Blackpool');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3818, 222, 'BRC', 'Bracknell');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3819, 222, 'BRD', 'Bradford');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3820, 222, 'BRY', 'Bromley');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3821, 222, 'BST', 'Bristol');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3822, 222, 'BUR', 'Bury');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3823, 222, 'CAM', 'Cambridgeshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3824, 222, 'CAY', 'Caerphilly');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3825, 222, 'CGN', 'Ceredigion');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3826, 222, 'CGV', 'Craigavon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3827, 222, 'CHS', 'Cheshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3828, 222, 'CKF', 'Carrickfergus');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3829, 222, 'CKT', 'Cookstown');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3830, 222, 'CLD', 'Calderdale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3831, 222, 'CLK', 'Clackmannanshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3832, 222, 'CLR', 'Coleraine');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3833, 222, 'CMA', 'Cumbria');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3834, 222, 'CMD', 'Camden');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3835, 222, 'CMN', 'Carmarthenshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3836, 222, 'CON', 'Cornwall');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3837, 222, 'COV', 'Coventry');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3838, 222, 'CRF', 'Cardiff');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3839, 222, 'CRY', 'Croydon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3840, 222, 'CSR', 'Castlereagh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3841, 222, 'CWY', 'Conwy');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3842, 222, 'DAL', 'Darlington');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3843, 222, 'DBY', 'Derbyshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3844, 222, 'DEN', 'Denbighshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3845, 222, 'DER', 'Derby');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3846, 222, 'DEV', 'Devon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3847, 222, 'DGN', 'Dungannon and South Tyrone');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3848, 222, 'DGY', 'Dumfries and Galloway');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3849, 222, 'DNC', 'Doncaster');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3850, 222, 'DND', 'Dundee');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3851, 222, 'DOR', 'Dorset');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3852, 222, 'DOW', 'Down');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3853, 222, 'DRY', 'Derry');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3854, 222, 'DUD', 'Dudley');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3855, 222, 'DUR', 'Durham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3856, 222, 'EAL', 'Ealing');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3857, 222, 'EAY', 'East Ayrshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3858, 222, 'EDH', 'Edinburgh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3859, 222, 'EDU', 'East Dunbartonshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3860, 222, 'ELN', 'East Lothian');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3861, 222, 'ELS', 'Eilean Siar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3862, 222, 'ENF', 'Enfield');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3863, 222, 'ERW', 'East Renfrewshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3864, 222, 'ERY', 'East Riding of Yorkshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3865, 222, 'ESS', 'Essex');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3866, 222, 'ESX', 'East Sussex');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3867, 222, 'FAL', 'Falkirk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3868, 222, 'FER', 'Fermanagh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3869, 222, 'FIF', 'Fife');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3870, 222, 'FLN', 'Flintshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3871, 222, 'GAT', 'Gateshead');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3872, 222, 'GLG', 'Glasgow');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3873, 222, 'GLS', 'Gloucestershire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3874, 222, 'GRE', 'Greenwich');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3875, 222, 'GSY', 'Guernsey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3876, 222, 'GWN', 'Gwynedd');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3877, 222, 'HAL', 'Halton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3878, 222, 'HAM', 'Hampshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3879, 222, 'HAV', 'Havering');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3880, 222, 'HCK', 'Hackney');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3881, 222, 'HEF', 'Herefordshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3882, 222, 'HIL', 'Hillingdon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3883, 222, 'HLD', 'Highland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3884, 222, 'HMF', 'Hammersmith and Fulham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3885, 222, 'HNS', 'Hounslow');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3886, 222, 'HPL', 'Hartlepool');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3887, 222, 'HRT', 'Hertfordshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3888, 222, 'HRW', 'Harrow');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3889, 222, 'HRY', 'Haringey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3890, 222, 'IOS', 'Isles of Scilly');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3891, 222, 'IOW', 'Isle of Wight');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3892, 222, 'ISL', 'Islington');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3893, 222, 'IVC', 'Inverclyde');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3894, 222, 'JSY', 'Jersey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3895, 222, 'KEC', 'Kensington and Chelsea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3896, 222, 'KEN', 'Kent');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3897, 222, 'KHL', 'Kingston upon Hull');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3898, 222, 'KIR', 'Kirklees');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3899, 222, 'KTT', 'Kingston upon Thames');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3900, 222, 'KWL', 'Knowsley');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3901, 222, 'LAN', 'Lancashire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3902, 222, 'LBH', 'Lambeth');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3903, 222, 'LCE', 'Leicester');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3904, 222, 'LDS', 'Leeds');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3905, 222, 'LEC', 'Leicestershire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3906, 222, 'LEW', 'Lewisham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3907, 222, 'LIN', 'Lincolnshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3908, 222, 'LIV', 'Liverpool');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3909, 222, 'LMV', 'Limavady');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3910, 222, 'LND', 'London');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3911, 222, 'LRN', 'Larne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3912, 222, 'LSB', 'Lisburn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3913, 222, 'LUT', 'Luton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3914, 222, 'MAN', 'Manchester');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3915, 222, 'MDB', 'Middlesbrough');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3916, 222, 'MDW', 'Medway');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3917, 222, 'MFT', 'Magherafelt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3918, 222, 'MIK', 'Milton Keynes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3919, 222, 'MLN', 'Midlothian');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3920, 222, 'MON', 'Monmouthshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3921, 222, 'MRT', 'Merton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3922, 222, 'MRY', 'Moray');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3923, 222, 'MTY', 'Merthyr Tydfil');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3924, 222, 'MYL', 'Moyle');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3925, 222, 'NAY', 'North Ayrshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3926, 222, 'NBL', 'Northumberland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3927, 222, 'NDN', 'North Down');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3928, 222, 'NEL', 'North East Lincolnshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3929, 222, 'NET', 'Newcastle upon Tyne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3930, 222, 'NFK', 'Norfolk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3931, 222, 'NGM', 'Nottingham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3932, 222, 'NLK', 'North Lanarkshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3933, 222, 'NLN', 'North Lincolnshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3934, 222, 'NSM', 'North Somerset');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3935, 222, 'NTA', 'Newtownabbey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3936, 222, 'NTH', 'Northamptonshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3937, 222, 'NTL', 'Neath Port Talbot');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3938, 222, 'NTT', 'Nottinghamshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3939, 222, 'NTY', 'North Tyneside');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3940, 222, 'NWM', 'Newham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3941, 222, 'NWP', 'Newport');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3942, 222, 'NYK', 'North Yorkshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3943, 222, 'NYM', 'Newry and Mourne');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3944, 222, 'OLD', 'Oldham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3945, 222, 'OMH', 'Omagh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3946, 222, 'ORK', 'Orkney Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3947, 222, 'OXF', 'Oxfordshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3948, 222, 'PEM', 'Pembrokeshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3949, 222, 'PKN', 'Perth and Kinross');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3950, 222, 'PLY', 'Plymouth');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3951, 222, 'POL', 'Poole');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3952, 222, 'POR', 'Portsmouth');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3953, 222, 'POW', 'Powys');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3954, 222, 'PTE', 'Peterborough');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3955, 222, 'RCC', 'Redcar and Cleveland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3956, 222, 'RCH', 'Rochdale');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3957, 222, 'RCT', 'Rhondda Cynon Taf');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3958, 222, 'RDB', 'Redbridge');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3959, 222, 'RDG', 'Reading');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3960, 222, 'RFW', 'Renfrewshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3961, 222, 'RIC', 'Richmond upon Thames');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3962, 222, 'ROT', 'Rotherham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3963, 222, 'RUT', 'Rutland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3964, 222, 'SAW', 'Sandwell');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3965, 222, 'SAY', 'South Ayrshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3966, 222, 'SCB', 'Scottish Borders');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3967, 222, 'SFK', 'Suffolk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3968, 222, 'SFT', 'Sefton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3969, 222, 'SGC', 'South Gloucestershire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3970, 222, 'SHF', 'Sheffield');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3971, 222, 'SHN', 'Saint Helens');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3972, 222, 'SHR', 'Shropshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3973, 222, 'SKP', 'Stockport');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3974, 222, 'SLF', 'Salford');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3975, 222, 'SLG', 'Slough');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3976, 222, 'SLK', 'South Lanarkshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3977, 222, 'SND', 'Sunderland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3978, 222, 'SOL', 'Solihull');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3979, 222, 'SOM', 'Somerset');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3980, 222, 'SOS', 'Southend-on-Sea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3981, 222, 'SRY', 'Surrey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3982, 222, 'STB', 'Strabane');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3983, 222, 'STE', 'Stoke-on-Trent');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3984, 222, 'STG', 'Stirling');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3985, 222, 'STH', 'Southampton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3986, 222, 'STN', 'Sutton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3987, 222, 'STS', 'Staffordshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3988, 222, 'STT', 'Stockton-on-Tees');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3989, 222, 'STY', 'South Tyneside');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3990, 222, 'SWA', 'Swansea');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3991, 222, 'SWD', 'Swindon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3992, 222, 'SWK', 'Southwark');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3993, 222, 'TAM', 'Tameside');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3994, 222, 'TFW', 'Telford and Wrekin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3995, 222, 'THR', 'Thurrock');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3996, 222, 'TOB', 'Torbay');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3997, 222, 'TOF', 'Torfaen');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3998, 222, 'TRF', 'Trafford');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(3999, 222, 'TWH', 'Tower Hamlets');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4000, 222, 'VGL', 'Vale of Glamorgan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4001, 222, 'WAR', 'Warwickshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4002, 222, 'WBK', 'West Berkshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4003, 222, 'WDU', 'West Dunbartonshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4004, 222, 'WFT', 'Waltham Forest');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4005, 222, 'WGN', 'Wigan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4006, 222, 'WIL', 'Wiltshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4007, 222, 'WKF', 'Wakefield');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4008, 222, 'WLL', 'Walsall');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4009, 222, 'WLN', 'West Lothian');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4010, 222, 'WLV', 'Wolverhampton');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4011, 222, 'WNM', 'Windsor and Maidenhead');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4012, 222, 'WOK', 'Wokingham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4013, 222, 'WOR', 'Worcestershire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4014, 222, 'WRL', 'Wirral');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4015, 222, 'WRT', 'Warrington');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4016, 222, 'WRX', 'Wrexham');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4017, 222, 'WSM', 'Westminster');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4018, 222, 'WSX', 'West Sussex');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4019, 222, 'YOR', 'York');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4020, 222, 'ZET', 'Shetland Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4021, 223, 'AK', 'Alaska');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4022, 223, 'AL', 'Alabama');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4023, 223, 'AS', 'American Samoa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4024, 223, 'AR', 'Arkansas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4025, 223, 'AZ', 'Arizona');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4026, 223, 'CA', 'California');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4027, 223, 'CO', 'Colorado');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4028, 223, 'CT', 'Connecticut');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4029, 223, 'DC', 'District of Columbia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4030, 223, 'DE', 'Delaware');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4031, 223, 'FL', 'Florida');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4032, 223, 'GA', 'Georgia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4033, 223, 'GU', 'Guam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4034, 223, 'HI', 'Hawaii');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4035, 223, 'IA', 'Iowa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4036, 223, 'ID', 'Idaho');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4037, 223, 'IL', 'Illinois');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4038, 223, 'IN', 'Indiana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4039, 223, 'KS', 'Kansas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4040, 223, 'KY', 'Kentucky');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4041, 223, 'LA', 'Louisiana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4042, 223, 'MA', 'Massachusetts');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4043, 223, 'MD', 'Maryland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4044, 223, 'ME', 'Maine');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4045, 223, 'MI', 'Michigan');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4046, 223, 'MN', 'Minnesota');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4047, 223, 'MO', 'Missouri');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4048, 223, 'MS', 'Mississippi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4049, 223, 'MT', 'Montana');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4050, 223, 'NC', 'North Carolina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4051, 223, 'ND', 'North Dakota');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4052, 223, 'NE', 'Nebraska');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4053, 223, 'NH', 'New Hampshire');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4054, 223, 'NJ', 'New Jersey');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4055, 223, 'NM', 'New Mexico');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4056, 223, 'NV', 'Nevada');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4057, 223, 'NY', 'New York');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4058, 223, 'MP', 'Northern Mariana Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4059, 223, 'OH', 'Ohio');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4060, 223, 'OK', 'Oklahoma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4061, 223, 'OR', 'Oregon');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4062, 223, 'PA', 'Pennsylvania');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4063, 223, 'PR', 'Puerto Rico');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4064, 223, 'RI', 'Rhode Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4065, 223, 'SC', 'South Carolina');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4066, 223, 'SD', 'South Dakota');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4067, 223, 'TN', 'Tennessee');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4068, 223, 'TX', 'Texas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4069, 223, 'UM', 'U.S. Minor Outlying Islands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4070, 223, 'UT', 'Utah');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4071, 223, 'VA', 'Virginia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4072, 223, 'VI', 'Virgin Islands of the U.S.');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4073, 223, 'VT', 'Vermont');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4074, 223, 'WA', 'Washington');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4075, 223, 'WI', 'Wisconsin');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4076, 223, 'WV', 'West Virginia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4077, 223, 'WY', 'Wyoming');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4078, 224, 'BI', 'Baker Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4079, 224, 'HI', 'Howland Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4080, 224, 'JI', 'Jarvis Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4081, 224, 'JA', 'Johnston Atoll');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4082, 224, 'KR', 'Kingman Reef');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4083, 224, 'MA', 'Midway Atoll');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4084, 224, 'NI', 'Navassa Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4085, 224, 'PA', 'Palmyra Atoll');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4086, 224, 'WI', 'Wake Island');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4087, 225, 'AR', 'Artigas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4088, 225, 'CA', 'Canelones');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4089, 225, 'CL', 'Cerro Largo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4090, 225, 'CO', 'Colonia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4091, 225, 'DU', 'Durazno');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4092, 225, 'FD', 'Florida');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4093, 225, 'FS', 'Flores');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4094, 225, 'LA', 'Lavalleja');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4095, 225, 'MA', 'Maldonado');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4096, 225, 'MO', 'Montevideo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4097, 225, 'PA', 'Paysandu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4098, 225, 'RN', 'Río Negro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4099, 225, 'RO', 'Rocha');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4100, 225, 'RV', 'Rivera');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4101, 225, 'SA', 'Salto');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4102, 225, 'SJ', 'San José');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4103, 225, 'SO', 'Soriano');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4104, 225, 'TA', 'Tacuarembó');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4105, 225, 'TT', 'Treinta y Tres');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4106, 226, 'AN', 'Andijon viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4107, 226, 'BU', 'Buxoro viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4108, 226, 'FA', 'Farg''ona viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4109, 226, 'JI', 'Jizzax viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4110, 226, 'NG', 'Namangan viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4111, 226, 'NW', 'Navoiy viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4112, 226, 'QA', 'Qashqadaryo viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4113, 226, 'QR', 'Qoraqalpog''iston Respublikasi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4114, 226, 'SA', 'Samarqand viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4115, 226, 'SI', 'Sirdaryo viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4116, 226, 'SU', 'Surxondaryo viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4117, 226, 'TK', 'Toshkent');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4118, 226, 'TO', 'Toshkent viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4119, 226, 'XO', 'Xorazm viloyati');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4120, 227, 'MAP', 'Malampa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4121, 227, 'PAM', 'Pénama');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4122, 227, 'SAM', 'Sanma');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4123, 227, 'SEE', 'Shéfa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4124, 227, 'TAE', 'Taféa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4125, 227, 'TOB', 'Torba');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4126, 229, 'A', 'Distrito Capital');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4127, 229, 'B', 'Anzoátegui');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4128, 229, 'C', 'Apure');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4129, 229, 'D', 'Aragua');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4130, 229, 'E', 'Barinas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4131, 229, 'F', 'Bolívar');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4132, 229, 'G', 'Carabobo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4133, 229, 'H', 'Cojedes');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4134, 229, 'I', 'Falcón');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4135, 229, 'J', 'Guárico');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4136, 229, 'K', 'Lara');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4137, 229, 'L', 'Mérida');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4138, 229, 'M', 'Miranda');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4139, 229, 'N', 'Monagas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4140, 229, 'O', 'Nueva Esparta');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4141, 229, 'P', 'Portuguesa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4142, 229, 'R', 'Sucre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4143, 229, 'S', 'Tachira');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4144, 229, 'T', 'Trujillo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4145, 229, 'U', 'Yaracuy');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4146, 229, 'V', 'Zulia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4147, 229, 'W', 'Capital Dependencia');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4148, 229, 'X', 'Vargas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4149, 229, 'Y', 'Delta Amacuro');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4150, 229, 'Z', 'Amazonas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4151, 230, '01', 'Lai Châu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4152, 230, '02', 'Lào Cai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4153, 230, '03', 'Hà Giang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4154, 230, '04', 'Cao Bằng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4155, 230, '05', 'Sơn La');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4156, 230, '06', 'Yên Bái');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4157, 230, '07', 'Tuyên Quang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4158, 230, '09', 'Lạng Sơn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4159, 230, '13', 'Quảng Ninh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4160, 230, '14', 'Hòa Bình');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4161, 230, '15', 'Hà Tây');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4162, 230, '18', 'Ninh Bình');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4163, 230, '20', 'Thái Bình');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4164, 230, '21', 'Thanh Hóa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4165, 230, '22', 'Nghệ An');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4166, 230, '23', 'Hà Tĩnh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4167, 230, '24', 'Quảng Bình');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4168, 230, '25', 'Quảng Trị');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4169, 230, '26', 'Thừa Thiên-Huế');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4170, 230, '27', 'Quảng Nam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4171, 230, '28', 'Kon Tum');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4172, 230, '29', 'Quảng Ngãi');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4173, 230, '30', 'Gia Lai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4174, 230, '31', 'Bình �?ịnh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4175, 230, '32', 'Phú Yên');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4176, 230, '33', '�?ắk Lắk');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4177, 230, '34', 'Khánh Hòa');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4178, 230, '35', 'Lâm �?ồng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4179, 230, '36', 'Ninh Thuận');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4180, 230, '37', 'Tây Ninh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4181, 230, '39', '�?ồng Nai');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4182, 230, '40', 'Bình Thuận');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4183, 230, '41', 'Long An');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4184, 230, '43', 'Bà Rịa-Vũng Tàu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4185, 230, '44', 'An Giang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4186, 230, '45', '�?ồng Tháp');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4187, 230, '46', 'Ti�?n Giang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4188, 230, '47', 'Kiên Giang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4189, 230, '48', 'Cần Thơ');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4190, 230, '49', 'Vĩnh Long');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4191, 230, '50', 'Bến Tre');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4192, 230, '51', 'Trà Vinh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4193, 230, '52', 'Sóc Trăng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4194, 230, '53', 'Bắc Kạn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4195, 230, '54', 'Bắc Giang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4196, 230, '55', 'Bạc Liêu');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4197, 230, '56', 'Bắc Ninh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4198, 230, '57', 'Bình Dương');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4199, 230, '58', 'Bình Phước');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4200, 230, '59', 'Cà Mau');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4201, 230, '60', '�?à Nẵng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4202, 230, '61', 'Hải Dương');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4203, 230, '62', 'Hải Phòng');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4204, 230, '63', 'Hà Nam');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4205, 230, '64', 'Hà Nội');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4206, 230, '65', 'Sài Gòn');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4207, 230, '66', 'Hưng Yên');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4208, 230, '67', 'Nam �?ịnh');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4209, 230, '68', 'Phú Th�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4210, 230, '69', 'Thái Nguyên');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4211, 230, '70', 'Vĩnh Phúc');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4212, 230, '71', '�?iện Biên');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4213, 230, '72', '�?ắk Nông');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4214, 230, '73', 'Hậu Giang');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4215, 232, 'C', 'Saint Croix');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4216, 232, 'J', 'Saint John');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4217, 232, 'T', 'Saint Thomas');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4218, 233, 'A', 'Alo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4219, 233, 'S', 'Sigave');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4220, 233, 'W', 'Wallis');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4221, 235, 'AB', 'أبين‎');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4222, 235, 'AD', 'عدن');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4223, 235, 'AM', 'عمران');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4224, 235, 'BA', 'البيضاء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4225, 235, 'DA', 'الضالع');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4226, 235, 'DH', 'ذمار');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4227, 235, 'HD', 'حضرموت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4228, 235, 'HJ', 'حجة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4229, 235, 'HU', 'الحديدة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4230, 235, 'IB', 'إب');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4231, 235, 'JA', 'الجو�?');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4232, 235, 'LA', 'لحج');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4233, 235, 'MA', 'مأرب');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4234, 235, 'MR', 'المهرة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4235, 235, 'MW', 'المحويت');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4236, 235, 'SD', 'صعدة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4237, 235, 'SN', 'صنعاء');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4238, 235, 'SH', 'شبوة');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4239, 235, 'TA', 'تعز');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4240, 238, '01', 'Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4241, 238, '02', 'Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4242, 238, '03', 'Eastern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4243, 238, '04', 'Luapula');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4244, 238, '05', 'Northern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4245, 238, '06', 'North-Western');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4246, 238, '07', 'Southern');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4247, 238, '08', 'Copperbelt');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4248, 238, '09', 'Lusaka');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4249, 239, 'MA', 'Manicaland');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4250, 239, 'MC', 'Mashonaland Central');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4251, 239, 'ME', 'Mashonaland East');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4252, 239, 'MI', 'Midlands');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4253, 239, 'MN', 'Matabeleland North');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4254, 239, 'MS', 'Matabeleland South');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4255, 239, 'MV', 'Masvingo');
INSERT INTO `lc_zones` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES(4256, 239, 'MW', 'Mashonaland West');

