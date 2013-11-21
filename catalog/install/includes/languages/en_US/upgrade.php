#  @package    catalog::install::languages
#  @author     Loaded Commerce
#  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
#  @copyright  Portions Copyright 2003 osCommerce
#  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
#  @version    $Id: upgrade.php v1.0 2013-08-08 datazen $

page_title_installation = New Installation

page_heading_step_1 = Upgrade Paths
page_heading_step_2 = Web Server
page_heading_step_3 = Online Store Settings
page_heading_step_4 = Finished!

text_installation = This web-based installation routine will correctly setup and configure Loaded Commerce to run on this server. Please following the on-screen instructions that will take you through the database server, web server, and store configuration options. If help is needed at any stage, please consult the documentation or seek help at the community support forums.
text_successful_installation = The installation and configuration was successful!
text_go_to_shop_after_cfg_file_is_saved = Please visit your store after the configuration file has been saved:
text_go = Go

param_database_server = Database Host
param_database_server_description = The address of the database server in the form of a hostname or IP address.
param_database_username = Username
param_database_username_description = The username used to connect to the database server.
param_database_password = Password
param_database_password_description = The password that is used together with the username to connect to the database server.
param_database_name = Database Name
param_database_name_description = The name of the database to hold the data in.
param_database_type = Database Type
param_database_type_description = The database server software that is used.
param_database_prefix = Table Prefix
param_database_prefix_description = The prefix to use for the database tables (optional).
param_database_import_sample_data = Import Sample Data
param_database_import_sample_data_description = Inserting sample data into the database is recommended for first time installations.

param_existing_install_path = Install Path
param_existing_install_path_description = directory path on your server where your existing cart is installed 

param_database_file_path = SQL File Path
param_database_file_path_description = path to your SQL file.

param_web_address = HTTP Address
param_web_ssl_address = HTTPS Address
param_web_address_description = The web address (http://) to the online store.
param_web_ssl_address_description = The secure (https://) web address to the online store.
param_web_root_directory = Root Directory
param_web_use_ssl = Use SSL?
param_web_use_ssl_description = Select to enable SSL (https://) on your site.  If not selected, WWW (http://) address will be used.
param_web_root_directory_description = The directory where the online store is installed on the server.
param_web_work_directory = Work Directory
param_web_work_directory_description = The working directory for temporarily created files. (Shared hosting servers should not use /tmp/)


param_store_name = Store Name
param_store_name_description = The name of the online store that is presented to the public.
param_store_owner_first_name = First Name
param_store_owner_first_name_description = The first name of the store owner that is presented to the public.
param_store_owner_last_name = Last Name
param_store_owner_last_name_description = The last name of the store owner that is presented to the public.
param_store_owner_email_address = Store Owner E-Mail
param_store_owner_email_address_description = The e-mail address of the store owner that is presented to the public.
param_administrator_username = Admin Email
param_administrator_username_description = The administrator username to use for the administration tool.
param_administrator_password = Admin Password
param_administrator_password_description = The password to use for the administrator account.

box_info_step_1_title = Path to Existing Installation
box_info_step_1_text = Path to Existing Installation description text.

box_info_step_1_title_R = Remote Database Connection Values
box_info_step_1_text = Remote Database Connection Values Description text.

box_info_step_1_title_D = Path to Database File
box_info_step_1_text = Path to Database File description text.
                                                                                                                              
box_info_step_2_title = Web Server                                                                                            
box_info_step_2_text = The web server takes care of serving the pages of the online store to the visitors and customers. The web server parameters make sure the links to the pages point to the correct location. Temporary files such as session data and cache files are stored in the work directory. It is important that this directory is located outside the web server root directory and is protected from public access.

box_info_step_3_title = Store Settings
box_info_step_3_text = Here you can define the name of your online store, and the contact information for the store owner.  The administrator username and password are used to log into the protected administration tool section.

box_info_step_4_title = Finished!
box_info_step_4_text = Congratulations on installing and configuring Loaded Commerce as your online store solution! We hope you all the best with your online store and welcome you to join and participate in our community.<br /><br /><span style="float:right;">- The Loaded Commerce Team</span>
box_info_step_5_text = Your installation ID is

rpc_database_connection_test = Testing database connection...
rpc_database_connection_error = There was a problem connecting to the database server. The following error occured: <b>%s</b>.  Please verify the connection parameters and try again.
rpc_database_connected = Successfully connected to the database.
rpc_database_importing = The database structure is now being imported. Please be patient during this procedure.
rpc_database_imported = Database imported successfully.
rpc_database_import_error = There was a problem importing the database. The following error occured: <b>%s</b>. Please verify the connection parameters and try again.

rpc_work_directory_test = Testing work directory...
rpc_work_directory_error_non_existent = There was a problem accessing the working directory. The following error occured: <b>The directory does not exist: %s</b>. Please verify the directory and try again.
rpc_work_directory_error_not_writeable = There was a problem accessing the working directory. The following error occured: <b>The webserver does not have write permissions to the directory: %s</b>. Please verify the permissions of the directory and try again.
rpc_work_directory_configured = Working directory successfully configured.

rpc_database_sample_data_importing = The sample data is now being imported into the database. Please be patient during this procedure.
rpc_database_sample_data_imported = Database sample data imported successfully.
rpc_database_sample_data_import_error = There was a problem importing the database sample data. The following error occured: <b>%s</b>. Please verify the database server and try again.

rpc_configfile_verified = config file verified
rpc_sqlfile_verified = SQL file verified 

rpc_configfile_error = No previous installation found. Please check the path and/or permissions
rpc_sqlfile_error = Cannot locate source configuration file 

param_import_categories = CATEGORIES
param_import_categories_description = importing Category data 

param_import_products = PRODUCTS
param_import_products_description = importing Product data

param_import_attributes = ATTRIBUTES
param_import_attributes_description = importing Product Attributes data

param_import_customers = CUSTOMERS
param_import_customers_description = importing Customer data 

param_import_customer_groups = CUSTOMER GROUPS
param_import_customer_groups_description = importing Customer Groups data

param_import_orders = ORDERS 
param_import_orders_description = importing Orders data

param_import_cds = CONTENT DIRECTOR 
param_import_cds_description = importing Content Director data 

param_import_admin = ADMINISTRATORS 
param_import_admin_description = importing Administrator data 

param_import_newsletter = NEWSLETTER 
param_import_newsletter_description = importing Newsletter data 

param_import_banners = BANNERS 
param_import_banners_description = importing Banner data 

param_import_config = CONFIGURATION 
param_import_config_description = importing Configuration data 

param_import_coupon = COUPONS 
param_import_coupon_description = importing Coupon data 

param_import_tax = TAX CLASSES/RATES 
param_import_tax_description = importing Tax Class and Rate data 

rpc_upgrade_success = import completed
rpc_upgrade_inprogress = import in progress

param_upgrade_existing_store = Existing Store name
param_upgrade_store_name = Store Name
param_upgrade_store_admin = Admin Name
param_upgrade_store_dbase = Existing Database

param_upgrade_step1_title_confirm = Existing Store Configuration
param_upgrade_step1_desc_confirm = The following store information was located. If this is correct, click 'Continue' below. To select a different store, click 'Back'.

upgrade_step2_title = New Database Settings
upgrade_step2_desc = Please enter settings of the new database. The new database must be empty. The installer will copy your existing data to this new database .

upgrade_step2_title_success = New Database Settings
upgrade_step2_desc_success = Successfully conected to the database server .

upgrade_step3_title = Database Import
upgrade_step3_desc = The installer will copy the existing database to the new database and convert the data to work with Loaded7 . This may take a few minutes. Please do not close the browser, press the back button or navigate away from this page .

image_button_retry = Retry

upgrade_nav_text_1 = Welcome
upgrade_nav_text_2 = Upgrade Path
upgrade_nav_text_3 = Configure
upgrade_nav_text_4 = Settings
upgrade_nav_text_5 = Finished 

upgrade_main_page_title = Upgrade From 6.x Version
upgrade_main_page_desc = Use this option if you have a previous version of Loaded Commerce, also known as CRE Loaded. Upgradeable versions are 6.2 and up ( 6.3, 6.4, 6.5 ) Standard, Community Edition, Pro and B2B.

upgrade_main_option_same = Same Server
upgrade_main_option_same_desc = Your previous installation is located on the same server /hosting space as the new installation and this script can access it's configure file to auto configure necessary connections.

upgrade_main_option_remote = Remote Server
upgrade_main_option_remote_desc = Your previous installation is located on another server with remote DB access. You will need to configure all aspects of the store but the database will be converted automatically.

upgrade_main_option_dbfile = Database File
upgrade_main_option_dbfile_desc = No previous installation but have a database backup of your old store? We can attempt to convert the data however you will need to configure all aspects of the store after the upgrade.

upgrade_step1_page_title = Path to Existing Installation
upgrade_step1_page_desc = Enter the directory path to your existing installation.

upgrade_step1_page_title_confirm = Existing Store Configuration
upgrade_step1_page_desc_confirm = The following store information was located. If this is correct, click 'Continue' below. To select a different store, click 'Back'.
upgrade_step1_label = Path to existing install

upgrade_step1_err_pathsame = The existing store file system path cannot match the currently executing file system path
upgrade_step1_err_noconfig = The exisiting store configuration files (admin/includes/configure.php) cannot be accessed

upgrade_step1_err_noserver	= The existing store file (admin/includes/configure.php) missing the value of DB_SERVER
upgrade_step1_err_nouid 		= The existing store file (admin/includes/configure.php) missing the value of DB_SERVER_USERNAME
upgrade_step1_err_nopass 		= The existing store file (admin/includes/configure.php) missing the value of DB_SERVER_PASSWORD
upgrade_step1_err_nodb 			= The existing store file (admin/includes/configure.php) missing the value of DB_DATABASE
upgrade_step1_err_noimage		= The existing store file (admin/includes/configure.php) missing the value of DIR_WS_IMAGES

upgrade_step2_page_title = New Database Settings
upgrade_step2_page_desc = Please enter settings of the new database. The new database must be empty. The installer will copy your existing data to this new database .

upgrade_step2_page_title_success = New Database Settings
upgrade_step2_page_desc_success = Successfully connected to the database server .

upgrade_step3_page_title = Database Import
upgrade_step3_page_desc = The installer will copy the existing database to the new database and convert the data to work with Loaded7 . This may take a few minutes. Please do not close the browser, press the back button or navigate away from this page .
upgrade_step3_page_errfound = ERROR(S) FOUND . Please correct to proceed

upgrade_step4_page_title = Image Import
upgrade_step4_page_desc = The installer will copy the existing images to the new database and convert the data to work with Loaded7 . This may take a few minutes. Please do not close the browser, press the back button or navigate away from this page .

upgrade_step4_label_import_product_images = Product Images
upgrade_step4_label_import_categ_images = Category Images

upgrade_step4_odesc_import_product_images = import product images
upgrade_step4_odesc_import_categ_images = import category images

upgrade_step4_import_product_images_zipcreateerror = unable to create zip file for product images
upgrade_step4_import_product_images_zipextracterror = unable to extract product zipped images to target directory
upgrade_step4_import_category_images_zipcreateerror = unable to create zip file for product images
upgrade_step4_import_category_images_zipextracterror = unable to extract category zipped images to target directory

upgrade_step4_zipoverrideerror = unable to override existing zip file 
upgrade_step4_zipopenerror = unable to open zip file for writing 
upgrade_step4_zipextracterror = unable to extract zip file to destination 

upgrade_step4_page_errfound = Unable to copy files from source. Please manually copy your product and category image from your old creloaded site to loaded7 images/products/originals directory

upgrade_step5_page_title = Store Settings
upgrade_step5_page_desc = Here you can define the name of your online store, and the contact information for the store owner.  The administrator username and password are used to log into the protected administration tool section. 
                                                                                                                              
upgrade_step6_page_title = Web Server                                                                                            
upgrade_step6_page_desc = The web server takes care of serving the pages of the online store to the visitors and customers. The web server parameters make sure the links to the pages point to the correct location. Temporary files such as session data and cache files are stored in the work directory. It is important that this directory is located outside the web server root directory and is protected from public access. 

upgrade_step7_page_title = Finished!
upgrade_step7_page_desc = Congratulations on installing and configuring Loaded Commerce as your online store solution! We hope you all the best with your online store and welcome you to join and participate in our community.<br /><br /><span style="float:right;">- The Loaded Commerce Team</span>

error_configuration_file_not_writeable = The webserver does not seem to be able to write the online store parameters to its configuration file due to file permission problems.  The configuration file is located at: %s
error_configuration_file_alternate_method = Alternatively you can copy the configuration parameters to the configuration file by hand.  This process will attempt to update the configuration file automatically at the end of the installation.
error_javascript_disabled = Javascript is required for the installation procedure and administration of this installation. Please enable it on your browser and <a href="index.php">re-run the installation procedure</a>.
