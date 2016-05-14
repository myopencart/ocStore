<?php
// Heading
$_['heading_title']        				= 'OpenBay Pro';

// Buttons
$_['button_retry']						= 'Повторить';
$_['button_update']						= 'Обновить';
$_['button_patch']						= 'Патч';
$_['button_ftp_test']					= 'Протестировать';
$_['button_faq']						= 'Открыть FAQ';

// Tab
$_['tab_setting']						= 'Настройки';
$_['tab_update']						= 'Обновление';
$_['tab_update_v1']						= 'Автоматически';
$_['tab_update_v2']						= 'Вручную';
$_['tab_patch']							= 'Патч';
$_['tab_developer']						= 'Разработчик';

// Text
$_['text_dashboard']         			= 'Панель управления';
$_['text_success']         				= 'Настройки успешно сохранены!';
$_['text_products']          			= 'Товары';
$_['text_orders']          				= 'Заказы';
$_['text_manage']          				= 'Менеджер';
$_['text_help']                     	= 'Помощь';
$_['text_tutorials']                    = 'Примеры на YouTube';
$_['text_suggestions']                  = 'Есть идея!';
$_['text_version_latest']               = 'Вы используете последнюю версию';
$_['text_version_check']     			= 'Проверка доступной версии';
$_['text_version_installed']    		= 'Установленная версия OpenBay Pro: v';
$_['text_version_current']        		= 'Ваша версия:';
$_['text_version_available']        	= 'последняя доступная:';
$_['text_language']             		= 'Язык API';
$_['text_getting_messages']     		= 'Чтение сообщений от OpenBay Pro';
$_['text_complete']     				= 'Завершено';
$_['text_test_connection']              = 'Тест FTP соединения';
$_['text_run_update']           		= 'Запустить обновление';
$_['text_patch_complete']           	= 'Патч успешно установлен!';
$_['text_connection_ok']				= 'Подключение к серверу установлено!';
$_['text_updated']						= 'Модуль был обновлен (v.%s)';
$_['text_update_description']			= 'ВНИМАНИЕ! Обновление произведёт изменения в системных файлах. Перед обновлением убедитесь, что у Вас есть резервная копия ВСЕХ! файлов - включая базу данных!';
$_['text_patch_description']			= 'Если вы загрузили файлы обновления вручную, необходимо запустить патч для завершения обновления';
$_['text_clear_faq']                    = 'Показать скрытые подсказки';
$_['text_clear_faq_complete']           = 'Теперь Уведомления будут показываться снова';
$_['text_install_success']              = 'Торговая площадка успешно установлена!';
$_['text_uninstall_success']            = 'Торговая площадка успешно удалена!';
$_['text_title_messages']               = 'Сообщения и Уведомления';
$_['text_marketplace_shipped']			= 'The order status will be updated to shipped on the marketplace';
$_['text_action_warning']				= 'This action is dangerous so is password protected.';
$_['text_check_new']					= 'Проверка доступной версии';
$_['text_downloading']					= 'Загружаю файлы обновления';
$_['text_extracting']					= 'Распаковка файлов';
$_['text_running_patch']				= 'Запуск патча';
$_['text_fail_patch']					= 'Unable to extract update files';
$_['text_updated_ok']					= 'Обновление завершено, установленная версия ';
$_['text_check_server']					= 'Проверка требования к серверу';
$_['text_version_ok']					= 'Программное обеспечение уже готово, установлена версия ';
$_['text_remove_files']					= 'Удаление файлов больше не требуется';
$_['text_confirm_backup']				= 'Убедитесь, что у вас есть полная резервная копия, прежде чем продолжить!';

// Column
$_['column_name']          				= 'Название Plugin\'а';
$_['column_status']        				= 'Статус';
$_['column_action']        				= 'Действие';

// Entry
$_['entry_patch']            			= 'Обновить вручную';
$_['entry_ftp_username']				= 'FTP Логин';
$_['entry_ftp_password']				= 'FTP Пароль';
$_['entry_ftp_server']					= 'FTP Сервер';
$_['entry_ftp_root']					= 'FTP Каталог';
$_['entry_ftp_admin']            		= 'Директория папки Admin';
$_['entry_ftp_pasv']                    = 'PASV режим FTP';
$_['entry_ftp_beta']             		= 'Загружать бета версию';
$_['entry_courier']						= 'Courier';
$_['entry_courier_other']           	= 'Other courier';
$_['entry_tracking']                	= 'Отслеживание #';
$_['entry_empty_data']					= 'Очистить все данные?';
$_['entry_password_prompt']				= 'Please enter the data wipe password';
$_['entry_update']						= 'Установить автоматически';

// Error
$_['error_username']             		= 'Укажите Логин FTP!';
$_['error_password']             		= 'Укажите Пароль FTP!';
$_['error_server']               		= 'Укажите FTP Сервер!';
$_['error_admin']             			= 'Укажите директорию папки admin';
$_['error_no_admin']					= 'Connection OK but your OpenCart admin directory was not found';
$_['error_no_files']					= 'Connection OK but OpenCart folders were not found! Is your root path correct?';
$_['error_ftp_login']					= 'Could not login with that user';
$_['error_ftp_connect']					= 'Could not connect to server';
$_['error_failed']						= 'Failed to load, retry?';
$_['error_tracking_id_format']			= 'Your tracking ID cannot contain the characters > or <';
$_['error_tracking_courier']			= 'You must select a courier if you want to add a tracking ID';
$_['error_tracking_custom']				= 'Please leave courier field empty if you want to use custom courier';
$_['error_permission']					= 'You do not have permission to modify the OpenBay Pro extension';
$_['error_mkdir']						= 'PHP mkdir function is disabled, contact your host';
$_['error_file_delete']					= 'Unable to remove these files, you should delete them manually';
$_['error_mcrypt']            			= 'PHP function "mcrypt_encrypt" is not enabled. Contact your hosting provider.';
$_['error_mbstring']               		= 'PHP library "mb strings" is not enabled. Contact your hosting provider.';
$_['error_ftpconnect']             		= 'PHP FTP functions are not enabled. Contact your hosting provider.';
$_['error_oc_version']             		= 'Your version of OpenCart is not tested to work with this module. You may experience problems.';
$_['error_fopen']             			= 'PHP function "fopen" is disabled by your host - you will be unable to import images when importing products';
$_['lang_error_vqmod']             		= 'Your vqmod folder contains older OpenBay Pro files - these need to be removed!';

// Help
$_['help_ftp_username']           		= 'Используйте Логин от сервера';
$_['help_ftp_password']           		= 'Используйте Пароль от сервера';
$_['help_ftp_server']      				= 'IP-адрес или Домен Вашего FTP сервера';
$_['help_ftp_root']           			= 'Каталог, в который установлен Opencart; обычно \'public_html/\'';
$_['help_ftp_admin']               		= 'Если Вы изменили каталог Администратора - укажите путь к нему (без слеша на конце)';
$_['help_ftp_pasv']                    	= 'Использовать FTP соединение в пассивном режиме.';
$_['help_ftp_beta']             		= 'Внимание! Бета-версия может содержать ошибки! Не рекомендуется использовать на действующем интернет магазине!';
$_['help_clear_faq']					= 'Показать ранее скрытые подсказки.';
$_['help_empty_data']					= 'ВНИМАНИЕ! Это может привести к серьезным последствиям, вплоть до неработающего интернет магазина. Не нажимайте на Сброс - если не знаете что он делает!';
$_['help_easy_update']					= 'Установить последнюю версию OpenBay Pro в автоматическом режиме.';
$_['help_patch']						= 'Нажмите, чтобы запустить патч из сохраненного ранее скрипта.';