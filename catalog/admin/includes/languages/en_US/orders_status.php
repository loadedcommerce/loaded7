#  $Id: orders_status.php v1.0 2013-01-01 datazen $
#
#  LoadedCommerce, Innovative eCommerce Solutions
#  http://www.loadedcommerce.com
#
#  Copyright (c) 2013 Loaded Commerce, LLC
#
#  @author     LoadedCommerce Team
#  @copyright  (c) 2013 LoadedCommerce Team
#  @license    http://loadedcommerce.com/license.html

heading_title = Order Statuses

table_heading_order_statuses = Order Statuses
table_heading_action = Action

button_new_status = New Status

modal_heading_new_order_status = New Order Status
modal_heading_edit_order_status = Edit Order Status
modal_heading_delete_order_status = Delete Order Status
modal_heading_batch_delete_order_status = Batch Delete Order Statuses

field_name = Name:
field_set_as_default = Set as Default?

orders = Orders
orders_history = Orders History

introduction_new_order_status = Please fill in the following information for the new order status.
introduction_edit_order_status = Please make the necessary changes for this order status.

introduction_delete_order_status = Please verify the removal of this order status.
delete_error_order_status_prohibited = ERROR: The default order status cannot be removed. Please set another order status as the default status and try again.
delete_error_order_status_in_use = ERROR: This order status is currently assigned to
delete_error_order_status_in_use_end = orders and cannot be removed.
delete_error_order_status_used = ERROR: This order status has been used by
delete_error_order_status_used_end = orders and cannot be removed.

introduction_batch_delete_order_statuses = Please verify the removal of the selected order statuses.
batch_delete_error_order_status_prohibited = ERROR: One or more order statuses are currently assigned to orders or have been used in orders or is marked as default and could not be removed.
