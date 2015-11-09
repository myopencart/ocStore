<?php
// Heading
$_['heading_title']        			= 'Настройки магазина';
$_['text_openbay']					= 'OpenBay Pro';
$_['text_ebay']						= 'eBay';

// Text
$_['text_developer']				= 'Разработчик / поддержка';
$_['text_app_settings']				= 'Параметры приложения';
$_['text_default_import']			= 'Параметры импорта по умолчанию';
$_['text_payments']					= 'Платежи';
$_['text_notify_settings']			= 'Параметры уведомлений';
$_['text_listing']					= 'Listing defaults';
$_['text_token_register']			= 'Register for your eBay token';
$_['text_token_renew']				= 'Обновить токен eBay';
$_['text_application_settings']		= 'Настройки приложения позволяют настроить способ работы и интеграции OpenBay Pro с вашей системой.';
$_['text_import_description']		= 'Customise the status of an order during different stages. You cannot use a status on an eBay order that does not exist in this list.';
$_['text_payments_description']		= 'Pre populate your payment options for new listings, this will save you entering them for every new listing you create.';
$_['text_allocate_1']				= 'Когда клиент покупает';
$_['text_allocate_2']				= 'Когда клиент заплатил';
$_['text_developer_description']	= 'Вы не должны использовать эту область без указаний ее использования';
$_['text_payment_paypal']			= 'Принимаем платежи PayPal';
$_['text_payment_paypal_add']		= 'Aдрес электронной почты PayPal';
$_['text_payment_cheque']			= 'Принимаем чековые платежи';
$_['text_payment_card']				= 'Принимаем платежные карты';
$_['text_payment_desc']				= 'Смотрите описание (например, банковский перевод)';
$_['text_tax_use_listing'] 			= 'Use tax rate set in eBay listing';
$_['text_tax_use_value']			= 'Use a set value for everything';
$_['text_action_warning']			= 'This action is dangerous so is password protected.';
$_['text_notifications']			= 'Control when customers receive notifications from the application. Enabling update emails can improve your DSR ratings as the user will get updates about their order.';
$_['text_listing_1day']             = '1 день';
$_['text_listing_3day']             = '3 дня';
$_['text_listing_5day']             = '5 дней';
$_['text_listing_7day']             = '7 дней';
$_['text_listing_10day']            = '10 дней';
$_['text_listing_30day']            = '30 дней';
$_['text_listing_gtc']              = 'GTC- Good till cancelled';
$_['text_api_status']               = 'Состояние подключения API';
$_['text_api_ok']                   = 'Connection OK, token expires';
$_['text_api_failed']               = 'Проверка не удалась';
$_['text_api_other']        		= 'Другие действия';
$_['text_create_date_0']            = 'При добавлении в OpenCart';
$_['text_create_date_1']            = 'When created on eBay';
$_['text_obp_detail_update']        = 'Update your store URL &amp; contact email';
$_['text_success']					= 'Ваши настройки были сохранены';

// Entry
$_['entry_status']					= 'Статус';
$_['entry_token']					= 'Token';
$_['entry_secret']					= 'Secret';
$_['entry_string1']					= 'Encryption string 1';
$_['entry_string2']					= 'Encryption string 2';
$_['entry_end_items']				= 'End items?';
$_['entry_relist_items']			= 'Relist when back in stock?';
$_['entry_disable_soldout']			= 'Disable product when no stock?';
$_['entry_debug']					= 'Включить ведение журнала';
$_['entry_currency']				= 'Валюта по умолчанию';
$_['entry_customer_group']			= 'Группы клиентов';
$_['entry_stock_allocate']			= 'Allocate stock';
$_['entry_created_hours']			= 'New order age limit';
$_['entry_empty_data']				= 'Empty ALL data?';
$_['entry_developer_locks']			= 'Remove order locks?';
$_['entry_payment_instruction']		= 'Payment instructions';
$_['entry_payment_immediate']		= 'Immediate payment required';
$_['entry_payment_types']			= 'Payment types';
$_['entry_brand_disable']			= 'Disable brand link';
$_['entry_duration']				= 'Default listing duration';
$_['entry_measurement']				= 'Measurement system';
$_['entry_address_format']			= 'Default address format';
$_['entry_timezone_offset']			= 'Timezone offset';
$_['entry_tax_listing']				= 'Product tax';
$_['entry_tax']						= 'Tax % used for everything';
$_['entry_create_date']				= 'Created date for new orders';
$_['entry_password_prompt']			= 'Please enter your admin password';
$_['entry_notify_order_update']		= 'Order updates';
$_['entry_notify_buyer']			= 'New order - buyer';
$_['entry_notify_admin']			= 'New order - admin';
$_['entry_import_pending']			= 'Import unpaid orders:';
$_['entry_import_def_id']			= 'Import default status:';
$_['entry_import_paid_id']			= 'Paid status:';
$_['entry_import_shipped_id']		= 'Shipped status:';
$_['entry_import_cancelled_id']		= 'Cancelled status:';
$_['entry_import_refund_id']		= 'Refunded status:';
$_['entry_import_part_refund_id']	= 'Partially refunded status:';

// Tabs
$_['tab_api_info']					= 'API details';
$_['tab_setup']						= 'Settings';
$_['tab_defaults']					= 'Listing defaults';

// Help
$_['help_disable_soldout']			= 'When the item sells out it then disables the product in OpenCart';
$_['help_relist_items'] 			= 'If an item link existed before it will relist previous item if back in stock';
$_['help_end_items']    			= 'Если предметы проданы, прекратить показы на eBay?';
$_['help_currency']     			= 'На основе валюты из вашего магазина';
$_['help_created_hours']   			= 'Orders are new when younger than this limit (in hours). Default is 72';
$_['help_stock_allocate'] 			= 'When should stock be allocated from the store?';
$_['help_payment_instruction']  	= 'Be as descriptive as possible. Do you require payment within a certain time? Do they call to pay by card? Do you have any special payment terms?';
$_['help_payment_immediate'] 		= 'Immediate payment stops unpaid buyers, as an item is not sold until they pay.';
$_['help_listing_tax']     			= 'If you use the rate from listings ensure your items have the correct tax in eBay';
$_['help_tax']             			= 'Used when you import items or orders';
$_['help_duration']    				= 'GTC is only available is you have an eBay shop.';
$_['help_address_format']      		= 'Only used if the country does not have an address format set-up already.';
$_['help_create_date']         		= 'Choose which created time will appear on an order when it is imported';
$_['help_timezone_offset']     		= 'Based on hours. 0 is GMT timezone. Only works if eBay time is used for order creation.';
$_['help_notify_admin']   			= 'Notify the store admin with the default new order email';
$_['help_notify_order_update']		= 'This is for automated updates, for example if you update an order in eBay and the new status is updated in your store automatically.';
$_['help_notify_buyer']        		= 'Notify the user with the default new order email';
$_['help_measurement']        		= 'Choose what measurement system you want to use for listings';

// Buttons
$_['button_update']             	= 'Update';
$_['button_repair_links']    		= 'Repair item links';

// Error
$_['error_api_connect']         	= 'Failed to connect to the API';