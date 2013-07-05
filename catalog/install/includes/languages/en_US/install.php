#  $Id: install.php v1.0 2012-12-08 datazen $
#
#  LoadedCommerce, Innovative eCommerce Solutions
#  http://www.loadedcommerce.com
#
#  Copyright (c) 2012 Loaded Commerce, LLC
#
#  @author     LoadedCommerce Team
#  @copyright  (c) 2012 LoadedCommerce Team
#  @license    http://loadedcommerce.com/license.html

page_title_installation = New Installation

page_heading_step_1 = Database Server
page_heading_step_2 = Web Server
page_heading_step_3 = Online Store Settings
page_heading_step_4 = Finished!

text_installation = This web-based installation routine will correctly setup and configure Loaded Commerce to run on this server. Please following the on-screen instructions that will take you through the database server, web server, and store configuration options. If help is needed at any stage, please consult the documentation or seek help at the community support forums.
text_successful_installation = The installation and configuration was successful!
text_go_to_shop_after_cfg_file_is_saved = Please visit your store after the configuration file has been saved:

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
param_administrator_username = Admin Username
param_administrator_username_description = The administrator username to use for the administration tool.
param_administrator_password = Admin Password
param_administrator_password_description = The password to use for the administrator account.

box_info_step_1_title = Database Server
box_info_step_1_text = The database server stores the content of the online store such as product information, customer information, and the orders that have been made. Please consult your server administrator if your database server parameters are not yet known.
                                                                                                                              
box_info_step_2_title = Web Server                                                                                            
box_info_step_2_text = The web server takes care of serving the pages of the online store to the visitors and customers. The web server parameters make sure the links to the pages point to the correct location. Temporary files such as session data and cache files are stored in the work directory. It is important that this directory is located outside the web server root directory and is protected from public access.

box_info_step_3_title = Store Settings
box_info_step_3_text = Here you can define the name of your online store, and the contact information for the store owner.  The administrator username and password are used to log into the protected administration tool section.

box_info_step_4_title = Finished!
box_info_step_4_text = Congratulations on installing and configuring Loaded Commerce as your online store solution! We hope you all the best with your online store and welcome you to join and participate in our community.<br /><br /><span style="float:right;">- The Loaded Commerce Team</span>
box_info_step_5_text = Your installation ID is

error_configuration_file_not_writeable = The webserver does not seem to be able to write the online store parameters to its configuration file due to file permission problems.  The configuration file is located at: %s
error_configuration_file_alternate_method = Alternatively you can copy the configuration parameters to the configuration file by hand.  This process will attempt to update the configuration file automatically at the end of the installation.
error_javascript_disabled = Javascript is required for the installation procedure and administration of this installation. Please enable it on your browser and <a href="index.php">re-run the installation procedure</a>.

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