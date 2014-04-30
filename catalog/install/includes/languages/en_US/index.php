#  @package    catalog::install::languages
#  @author     Loaded Commerce
#  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
#  @copyright  Portions Copyright 2003 osCommerce
#  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
#  @version    $Id: index.php v1.0 2013-08-08 datazen $

page_title_welcome = Welcome to Loaded Commerce v%s

page_heading_server_requirements = Server Requirements
page_heading_installation_type = Installation Type
page_heading_permissions = File/Folder Permissions
page_heading_ioncube = ionCube Configuration
page_text_ioncube = ionCube Loader is a required component for the functionality of paid addons such as Pro and B2B versions of Loaded 7, and some individual native & third party addons.

ioncube_no_additional_config = No additional configuration required.
ioncube_installed_version = Installed:
ioncube_update_needed = Ioncube loader is installed but needs to be updated. Loaded 7 paid addons will only work with ioncube loader version 4.4.1 or later. The most recent version of the loader can be found <a href="http://www.ioncube.com/loaders.php" target="_blank"><b>here</b></a>.
ioncube_not_installed_instructions = <br />&nbsp; - Please contact your web host and ask them to install ionCube loader on your server.<br />&nbsp; - Loaders can be downloaded from <a href="http://www.ioncube.com/loaders.php" target="_blank"><b>www.ioncube.com</b></a>.<br />&nbsp; - Installation Instructions can be found <a href="http://www.ioncube.com/loader_installation.php" target="_blank"><b>here</b></a>.<br />&nbsp; - For additional questions please contact <a href="http://www.loadedcommerce.com/support-memberships-pc-175_198.html" target="_blank"><b>Loaded Commerce Support</b></a>

text_status = Status: 
text_instructions = Instructions: 
title_language = Language:
text_not_installed = Not Installed
text_welcome = Loaded Commerce is the next generation self-hosted open source ecommerce platform and is available for free under the GNU General Public License. A rich set of features and functionality allow store owners to setup, run, and maintain online stores with minimal effort and minimal cost.
text_under_development = Loaded Commerce v%s is currently in development and does not yet contain all its planned features. This release is recommended for developers and users who are participating in its development and are providing feedback.

box_server_title = Server Capabilities
box_server_php_version = PHP Version
box_server_php_settings = PHP Settings
box_server_register_globals = register_globals
box_server_magic_quotes = magic_quotes
box_server_file_uploads = file_uploads
box_server_session_auto_start = session.auto_start
box_server_session_use_trans_sid = session.use_trans_sid
box_server_post_max_size = post_max_size
post_max_size_text = 10M
box_server_upload_max_filesize = upload_max_filesize
upload_max_filesize_text = 10M
box_server_php_extensions = PHP Extensions
box_server_mysqli = MySQLi
box_server_gd = GD
box_server_curl = curlSSL
box_server_openssl = OpenSSL
box_server_phar = Phar
box_server_on = On
box_server_off = Off
box_server_writeable = Writeable
box_server_not_writeable = Not-Writeable

error_configuration_file_not_writeable = The webserver does not seem to be able to write the online store parameters to its configuration file due to file permission problems.  The configuration file is located at: %s
error_configuration_file_alternate_method = Alternatively you can copy the configuration parameters to the configuration file by hand.  This process will attempt to update the configuration file automatically at the end of the installation.
error_javascript_disabled = Javascript is required for the installation procedure and administration of this installation. Please enable it on your browser and <a href="index.php">re-run the installation procedure</a>.

image_button_install = New Install
image_button_upgrade = Upgrade from 6.x Version
image_button_phpinfo = Show PHP Information