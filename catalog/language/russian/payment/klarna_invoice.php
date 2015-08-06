<?php
// Text
$_['text_title']				= 'Klarna Invoice - оплата в течение 14 дней';
$_['text_terms_fee']			= '<span id="klarna_invoice_toc"></span> (+%s)<script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\', charge: %s});</script>';
$_['text_terms_no_fee']			= '<span id="klarna_invoice_toc"></span><script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\'});</script>';
$_['text_additional']			= 'Системе Klarna Invoice необходимы некоторые дополнительные данные для дальнейшей обработки заказа.';
$_['text_male']					= 'Мужской';
$_['text_female']				= 'Женский';
$_['text_year']					= 'Год';
$_['text_month']				= 'Месяц';
$_['text_day']					= 'День';
$_['text_comment']				= 'Klarna\'s Invoice ID: %s. ' . "\n" . '%s/%s: %.4f';

// Entry
$_['entry_gender']				= 'Пол';
$_['entry_pno']					= 'Персональный номер';
$_['entry_dob']					= 'Дата рождения';
$_['entry_phone_no']			= 'Номер телефона';
$_['entry_street']				= 'Улица';
$_['entry_house_no']			= 'Дом';
$_['entry_house_ext']			= 'Корпус (строение)';
$_['entry_company']				= 'Регистрационный номер компании';

// Help
$_['help_pno']					= 'Пожалуйста, введите ваш номер социального страхования здесь (для Швеции).';
$_['help_phone_no']				= 'Пожалуйста, введите номер Вашего телефона.';
$_['help_street']				= 'Пожалуйста, обратите внимание, что доставка может осуществиться только на адрес, зарегистрированный при оплате через Klarna.';
$_['help_house_no']				= 'Пожалуйста, введите номер Вашего дома.';
$_['help_house_ext']			= 'Пожалуйста, укажите здесь корпус/строение, в котором Вы проживаете.';
$_['help_company']				= 'Пожалуйста, введите регистрационный номер Вашей компании';

// Error
$_['error_deu_terms']			= 'Вы должны согласиться с политикой конфиденциальности Klarna';
$_['error_address_match']		= 'Адреса плательщика и получателя заказа должны совпадать, если Вы хотите использовать Klarna Invoice';
$_['error_network']				= 'Произошла ошибка при подключении к Klarna. Пожалуйста, повторите попытку позже.';