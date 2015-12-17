# ocStore 2 change log

## v2.1.0.1.1 (17.12.2015)
#### Исправлено:
* Невалидные URL картинок с русскими символами в названии
* Устранена ошибка добавления производителя
* Устранена ошибка при создании баннеров
* Правки некорректного перевода
* Устранение ошибки при отправке рассылки 
* Исправлена проблема, возникавшая в сторонних модулях, ничего не знающих про CKEditor в ocStore, в случае, если редактором по умолчанию выбран CKEditor.
* Исправление заголовка в статьях
* Исправление ошибки с выбором производителя при редактировании товара
* Восстановление работы модуля доставки
* Исправлены мелкие ошибки
 
#### Изменено:
* В  главной категории можно выбрать категорию из всех категорий
* Удаление лишних нолей в Д x Ш x В и других местах 
* Символ рубля в Unicode заменен на р.
 
#### Добавлено:
* Фильтр по категориям на странице списка товаров в админке


## v2.1.0.1 (16.10.2015)
#### Исправлено
* Исправлена пагинация (удалено дубль первой страницы, убрано дублирование мета) (https://github.com/myopencart/ocStore/commit/000464351be6bdceb1e7f6d1c312920430ac909b)
* Ошибка при просмотре заказа если не IP yt lj

#### Изменено
* В логотипе на главной убрано ссылку на саму себя (https://github.com/myopencart/ocStore/commit/000464351be6bdceb1e7f6d1c312920430ac909b)
* Исправлена пагинация (удалено дубль первой страницы, убрано дублирование мета) (https://github.com/myopencart/ocStore/commit/000464351be6bdceb1e7f6d1c312920430ac909b)
* Скрыто владку Google через дублирование функционала (https://github.com/myopencart/ocStore/commit/8c69328587afed7314ccc16be2dd6c33825a97aa)
* Изменена организация вывода категорий в админке (https://github.com/myopencart/ocStore/commit/ce3a87686f409bc27afbba93066948ab73ae66b2)
* Изменен сервис получения информации о IP-адресе покупателей с www.geoiptool.com на ipgeobase.ru
* В списке заказов кнопки редактирования заказа становится не активной, если нет разрешенного IP в API

#### Добавлено
* Добавлен русский языковой пакет
* Локализация базы (схемы, статусы, возвраты)
* Добавлена мультиязычность календаря
* Добавлен редактор CKEditor, появилась возможность выбора редактора (https://github.com/myopencart/ocStore/commit/12133094f78ef255e8c54e284492514581e3fde9)
* Добавлена мультиязычность редактора summernote (https://github.com/myopencart/ocStore/commit/a3c9fc8ae3a276f3bc35b4a870051c05ac265141)
* Модуль оплаты Сбербанк России (https://github.com/myopencart/ocStore/commit/12ec37fef518adb419716f3ce4800b37b8d7d42e)
* Модуль оплаты Qiwi (https://github.com/myopencart/ocStore/commit/6f3c823144f5177465c8392c4664444b8daf53e3)
* Модуль для создания рассылок через сервис Unisender (https://github.com/myopencart/ocStore/commit/6008dbe82466afd80fb9e461d705aa7442ef7403)
* Модуль доставки - доставка в зависимости от суммы заказа (https://github.com/myopencart/ocStore/commit/94cdb34c5e6cc5ae16527d084044e7490f8579fc)
* Возможность самостоятельного ввода регулярного выражения для валидации email (https://github.com/myopencart/ocStore/commit/614b8ea91d0820835c5e8839542ae41bce754ce5) (https://github.com/myopencart/ocStore/commit/e2f13036e3dd5b4f77d7c0114f078c040a38dee3)
* Добавление мета-тегов og:url, og:image, og:type, og:title (https://github.com/myopencart/ocStore/commit/000464351be6bdceb1e7f6d1c312920430ac909b)
* Добавление файла robots.txt (https://github.com/myopencart/ocStore/commit/000464351be6bdceb1e7f6d1c312920430ac909b)
* Добавлены title и h1 для товаров (https://github.com/myopencart/ocStore/commit/1fcdc182c0ec079a73ca97ac9bdb685cdbdab089)
* Добавлены title и h1 для категорий (https://github.com/myopencart/ocStore/commit/a653a171e03e111a423b4ddcec65607bacb49291)
* Для статей добавлены title, h1, meta keywords и meta description; (https://github.com/myopencart/ocStore/commit/b01352a7e52f3faab7155a903a77576a75138cce)
* Для производителей добавлены мультиязычные имена, title, h1, meta keywords, meta description и description; (https://github.com/myopencart/ocStore/commit/6f3da8c5d059a08fb3ea07fd1dc3f555a6a24cbb)
* Добавлен альтернативный метод формирования ЧПУ исключающий формирование разных ссылок для одной страницы; (https://github.com/myopencart/ocStore/commit/2bbb96c5ec2fd09821cf33c6b19e70ffb8fd303f) (https://github.com/myopencart/ocStore/commit/1bec354689300dfbd2dcf6242ccafcde06316419)
* Добавлено url alias для базовых страниц
* Добавлен SeoPro
* Добавлена отправка SMS уведомлений
* Добавлено полезные инструменты от OC Team (https://github.com/myopencart/ocStore/commit/6ad5ef1f9b33727e9d27ca16142036400770787f)
* Добавлена возможность удаления кеша системы и изображений
* Добавлено возможность скрытия не часто используемых полей через настройки магазина (MPN, ISBN, JAN и т.д.) (https://github.com/myopencart/ocStore/commit/ae421d72af8545a9e7194cbe43c84330950f84e7)
* Добавлена возможность скрытия не используемых модулей, методов оплат и доставок (https://github.com/myopencart/ocStore/commit/36a616f3cc613dcb4fd491772c41f7966cd0ea22)