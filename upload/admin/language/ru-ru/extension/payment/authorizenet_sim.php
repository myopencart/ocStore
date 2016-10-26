<?php
// Heading
$_['heading_title']			= 'Authorize.Net (AIM)';

// Text
$_['text_payment']			= 'Платежи';
$_['text_success']			= 'Успех: Вы изменили данные счета Authorize.Net (AIM)!';
$_['text_edit']             = 'Редактировать данные Authorize.Net (AIM)';
$_['text_authorizenet_sim']	= '<a onclick="window.open(\'http://reseller.authorize.net/application/?id=5561142\');"><img src="view/image/payment/authorizenet.png" alt="Authorize.Net" title="Authorize.Net" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_extension']                = 'Платежи';

// Entry
$_['entry_merchant']		= 'ID Продавца';
$_['entry_key']				= 'Ключ транзакций';
$_['entry_callback']		= 'URL-адрес ответа узла пересылки';
$_['entry_md5']				= 'MD5 Кодовое Значение';
$_['entry_test']			= 'Тестовый режим';
$_['entry_total']			= 'Итого';
$_['entry_order_status']	= 'Статус заказа';
$_['entry_geo_zone']		= 'Регион';
$_['entry_status']			= 'Статус';
$_['entry_sort_order']		= 'Порядок сортировки';

// Help
$_['help_callback']			= 'Пожалуйста войдите в систему и установите это в <a href="https://secure.authorize.net" target="_blank" class="txtLink"> https://secure.authorize.net</a>.';
$_['help_md5']				= 'Кодовая функция MD5 позволяет Вам убедиться в надёжности ответной транзакции, полученной от Authorize.Net. Пожалуйста войдите и установите это на <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a>. (Необязательно)';
$_['help_total']			= 'Сумма заказа должна быть выше, прежде чем этот метод оплаты станет активным.';

// Error
$_['error_permission']		= 'Внимание: Вы не имеете разрешения на изменение платежей Authorize.Net (SIM)!';
$_['error_merchant']		= 'Требуется указать ID Продавца!';
$_['error_key']				= 'Требуется ключ транзакций!';