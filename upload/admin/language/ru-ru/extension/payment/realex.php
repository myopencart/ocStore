<?php
// Heading
$_['heading_title']					 = 'Realex Redirect';

// Text
$_['text_payment']				  	 = 'Платежи';
$_['text_success']					 = 'Success: You have modified Realex account details!';
$_['text_edit']                      = 'Edit Realex Redirect';
$_['text_live']						 = 'Live';
$_['text_demo']						 = 'Демо';
$_['text_card_type']				 = 'Тип карты';
$_['text_enabled']					 = 'Включено';
$_['text_use_default']				 = 'Использовать по умолчанию';
$_['text_merchant_id']				 = 'ID Продавца';
$_['text_subaccount']				 = 'Субсчет';
$_['text_secret']					 = 'Общий секрет';
$_['text_card_visa']				 = 'Visa';
$_['text_card_master']				 = 'MasterCard';
$_['text_card_amex']				 = 'American Express';
$_['text_card_switch']				 = 'Switch/Maestro';
$_['text_card_laser']				 = 'Laser';
$_['text_card_diners']				 = 'Diners';
$_['text_capture_ok']				 = 'Capture was successful';
$_['text_capture_ok_order']			 = 'Capture was successful, order status updated to success - settled';
$_['text_rebate_ok']				 = 'Rebate was successful';
$_['text_rebate_ok_order']			 = 'Rebate was successful, order status updated to rebated';
$_['text_void_ok']					 = 'Void was successful, order status updated to voided';
$_['text_settle_auto']				 = 'Автоматически';
$_['text_settle_delayed']			 = 'Отложено';
$_['text_settle_multi']				 = 'Multi';
$_['text_url_message']				 = 'You must supply the store URL to your Realex account manager before going live';
$_['text_payment_info']				 = 'Информация об оплате';
$_['text_capture_status']			 = 'Payment captured';
$_['text_void_status']				 = 'Оплата аннулирована';
$_['text_rebate_status']			 = 'Payment rebated';
$_['text_order_ref']				 = 'Order ref';
$_['text_order_total']				 = 'Total authorised';
$_['text_total_captured']			 = 'Total captured';
$_['text_transactions']				 = 'Transactions';
$_['text_column_amount']			 = 'Amount';
$_['text_column_type']				 = 'Type';
$_['text_column_date_added']		 = 'Created';
$_['text_confirm_void']				 = 'Are you sure you want to void the payment?';
$_['text_confirm_capture']			 = 'Are you sure you want to capture the payment?';
$_['text_confirm_rebate']			 = 'Are you sure you want to rebate the payment?';
$_['text_realex']					 = '<a target="_blank" href="http://www.realexpayments.co.uk/partner-refer?id=opencart"><img src="view/image/payment/realex.png" alt="Realex" title="Realex" style="border: 1px solid #EEEEEE;" /></a>';

// Entry
$_['entry_merchant_id']				 = 'Merchant ID';
$_['entry_secret']					 = 'Shared secret';
$_['entry_rebate_password']			 = 'Rebate password';
$_['entry_total']					 = 'Всего';
$_['entry_sort_order']				 = 'Порядок сортировки';
$_['entry_geo_zone']				 = 'Регион';
$_['entry_status']					 = 'Состояние';
$_['entry_debug']					 = 'Журнал отладки';
$_['entry_live_demo']				 = 'Live / Демо';
$_['entry_auto_settle']				 = 'Settlement type';
$_['entry_card_select']				 = 'Select card';
$_['entry_tss_check']				 = 'TSS checks';
$_['entry_live_url']				 = 'Live connection URL';
$_['entry_demo_url']				 = 'Demo connection URL';
$_['entry_status_success_settled']	 = 'Success - settled';
$_['entry_status_success_unsettled'] = 'Success - not settled';
$_['entry_status_decline']			 = 'Decline';
$_['entry_status_decline_pending']	 = 'Decline - offline auth';
$_['entry_status_decline_stolen']	 = 'Decline - lost or stolen card';
$_['entry_status_decline_bank']		 = 'Decline - bank error';
$_['entry_status_void']				 = 'Voided';
$_['entry_status_rebate']			 = 'Rebated';
$_['entry_notification_url']		 = 'Notification URL';

// Help
$_['help_total']					 = 'The checkout total the order must reach before this payment method becomes active';
$_['help_card_select']				 = 'Ask the user to choose their card type before they are redirected';
$_['help_notification']				 = 'You need to supply this URL to Realex to get payment notifications';
$_['help_debug']					 = 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise';
$_['help_dcc_settle']				 = 'If your subaccount is DCC enabled you must use Autosettle';

// Tab
$_['tab_api']					     = 'API Details';
$_['tab_account']		     		 = 'Accounts';
$_['tab_order_status']				 = 'Статус заказа';
$_['tab_payment']					 = 'Настройки платежа';
$_['tab_advanced']					 = 'Расширенный';

// Button
$_['button_capture']				 = 'Фиксация';
$_['button_rebate']					 = 'Скидка / Возврат';
$_['button_void']					 = 'Void';

// Error
$_['error_merchant_id']				 = 'Требуется ID продавца';
$_['error_secret']					 = 'Общий секрет не требуется';
$_['error_live_url']				 = 'Живой URL-адрес не требуется';
$_['error_demo_url']				 = 'Демо URL-адрес не требуется';
$_['error_data_missing']			 = 'Отсутствуют данные';
$_['error_use_select_card']			 = 'Вы должны иметь «Выбрать карту» включена для субсчет маршрутизации по типу карты для работы';