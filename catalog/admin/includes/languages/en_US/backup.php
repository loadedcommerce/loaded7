#  $Id: backup.php v1.0 2013-01-01 datazen $
#
#  LoadedCommerce, Innovative eCommerce Solutions
#  http://www.loadedcommerce.com
#
#  Copyright (c) 2013 Loaded Commerce, LLC
#
#  @author     LoadedCommerce Team
#  @copyright  (c) 2013 LoadedCommerce Team
#  @license    http://loadedcommerce.com/license.html

heading_title = Backup Manager

table_heading_backups = Backups
table_heading_date = Date
table_heading_file_size = File Size
table_heading_action = Action

modal_heading_new_backup = New Database Backup
modal_heading_restore_file = Restore From Backup
modal_heading_restore_local_file = Restore From Local Backup
modal_heading_delete_backup_files = Delete Backup Files
modal_heading_batch_delete_backup_files = Batch Delete Backup Files

field_compression_none = No Compression
field_compression_gzip = GZIP Compression
field_compression_zip = ZIP Compression
field_download_only = Download Without Saving

backup_location = Backup Directory:
last_restoration_date = Last Restoration Date:
forget_restoration_date = Forget Restoration Date

introduction_new_backup = Please fill in the following information for the new database backup.
introduction_restore_file = Please verify the restoration of the following database backup file:
introduction_restore_local_file = Please select the database backup file to restore from.
introduction_delete_backup_file = Please verify the removal of the following database backup file:
introduction_batch_delete_backup_files = Please verify the removal of the selected database backup files.

message_backup_success = Database Restore SUCCESS!
ms_error_backup_directory_not_writable = Error: The database backup directory is not writable: %s
ms_error_backup_directory_non_existant = Error: The database backup directory does not exist: %s
ms_error_download_link_not_acceptable = Error: The download link is not acceptable.
