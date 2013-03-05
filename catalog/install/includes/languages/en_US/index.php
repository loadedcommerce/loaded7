#  $Id: index.php v1.0 2012-12-08 datazen $
#
#  LoadedCommerce, Innovative eCommerce Solutions
#  http://www.loadedcommerce.com
#
#  Copyright (c) 2012 Loaded Commerce, LLC
#
#  @author     LoadedCommerce Team
#  @copyright  (c) 2012 LoadedCommerce Team
#  @license    http://loadedcommerce.com/license.html

page_title_welcome = Welcome to Loaded Commerce v7.0 Alpha 1!

page_heading_server_requirements = Server Requirements
page_heading_installation_type = Installation Type
page_heading_permissions = File/Folder Permissions

title_language = Language:

text_welcome = Loaded Commerce is the next generation self-hosted open source ecommerce platform and is available for free under the GNU General Public License. A rich set of features and functionality allow store owners to setup, run, and maintain online stores with minimal effort and minimal cost.

text_under_development = Loaded Commerce v7.0a1 is currently in development and does not yet contain all its planned features. This release is recommended for developers and users who are participating in its development and are providing feedback.

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
box_server_curl = cURL
box_server_openssl = OpenSSL
box_server_on = On
box_server_off = Off
box_server_writeable = Writeable
box_server_not_writeable = Not-Writeable

error_configuration_file_not_writeable = The webserver does not seem to be able to write the online store parameters to its configuration file due to file permission problems.  The configuration file is located at: %s
error_configuration_file_alternate_method = Alternatively you can copy the configuration parameters to the configuration file by hand.  This process will attempt to update the configuration file automatically at the end of the installation.
error_javascript_disabled = Javascript is required for the installation procedure and administration of this installation. Please enable it on your browser and <a href="index.php">re-run the installation procedure</a>.

image_button_install = Install
image_button_upgrade = Upgrade