#  $Id: banner_manager.php v1.0 2011-11-04 datazen $
#
#  LoadedCommerce, Innovative eCommerce Solutions
#  http://www.loadedcommerce.com
#
#  Copyright (c) 2011 LoadedCommerce.com
#
#  @author     LoadedCommerce Team
#  @copyright  (c) 2011 LoadedCommerce Team
#  @license    http://loadedcommerce.com/license.html

heading_title = Banner Manager

button_new_banner = New Banner

modal_heading_new_banner = New Banner
modal_heading_edit_banner = Edit Banner
modal_heading_batch_delete_banners = Batch Delete Banners
modal_heading_delete_banner = Delete Banner
modal_heading_preview_banner = Preview Banner
modal_heading_banner_stats = Banner Statistics

operation_heading_type = Type:
operation_heading_month = Month:
operation_heading_year = Year:

table_heading_banners = Banners
table_heading_group = Group
table_heading_statistics = Statistics
table_heading_action = Action
table_heading_source = Source
table_heading_views = Views
table_heading_clicks = Clicks

section_daily = Daily
section_monthly = Monthly
section_yearly = Yearly
section_stats = Statistics Values
section_graph = Statistics Graph

subsection_heading_statistics_daily = %s Daily Statistics For %s %s
subsection_heading_statistics_monthly = %s Monthly Statistics For %s
subsection_heading_statistics_yearly = %s Yearly Statistics

field_title = Title:
field_url = URL:
field_group = Group:
field_group_new = , or enter a new group below
field_image = Image:
field_image_local = , or enter a local file below
field_image_target = Image Target (Save To):
field_html_text = HTML Text:
field_scheduled_date = Scheduled Date:
field_expiry_date = Expiry Date:
field_maximum_impressions = Maximum Impressions:
field_status = Active:
field_delete_image = Delete Banner Image?

introduction_new_banner = Please fill in the following information for the new banner.
introduction_edit_banner = Please make the necessary changes for this banner.
introduction_delete_banner = Please verify the removal of this banner.
introduction_batch_delete_banners = Please verify the removal of the selected banners.

info_banner_fields = <strong>Banner Notes:</strong><ul><li>Use an image or HTML text for the banner - <span style="color:#ff0000; font-weight:bold; text-decoration: underline;">NOT BOTH</span>.</li><li>HTML Text has priority over an image</li></ul>
<strong>Image Notes:</strong><ul><li>Upload target directories must have proper user (write) permissions setup!</li><li>Do not fill out the <u>Save To</u> field if you are not uploading an image to the webserver (ie, you are using a local (serverside) image).</li><li>The <u>Save To</u> field must be an existing directory with an ending slash (eg, banners/).</li></ul>
<strong>Expiry Notes:</strong><ul><li>Only one of the two fields should be submitted</li><li>If the banner is not to expire automatically, then leave these fields blank</li></ul>
<strong>Schedule Notes:</strong><ul><li>If a schedule is set, the banner will be activated on that date.</li><li>All scheduled banners are marked as inactive until their date has arrived, to which they will then be marked active.</li></ul>

ms_error_no_image_or_text = ERROR: You must provide an image or HTML text.  Both cannot be blank.
ms_error_graphs_directory_non_existant = ERROR: Graphs directory does not exist in %s
ms_error_graphs_directory_not_writable = ERROR: Graphs directory is not writable in %s
