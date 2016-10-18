<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/
class ControllerExtensionPaymentocstoreYk extends Controller {
    private $order;
    private $log;
    private $pay = 'extension/payment/ocstore_yk/pay';
    private static $LOG_OFF = 0;
    private static $LOG_SHORT = 1;
    private static $LOG_FULL = 2;
    private static $valid_currencies = array(
        'RUB', 'RUR'
    );

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('extension/payment/ocstore_yk');
    }

    public function index($setting) {
        $payment_type = isset($setting['paymentType']) ? $setting['paymentType'] : 'AC';
        $langdata = $this->config->get('shopuils_yk_langdata');
        $payment_langdata = $this->config->get($this->getSubMethod($payment_type, 'langdata'));

        $data = $this->_setData(array(
                 'entry_payment_methods',
                 'button_laterpay'  => $this->language->get('button_laterpay'),
                 'button_confirm'    => $payment_langdata[$this->config->get('config_language_id')]['button_confirm'] ?: $this->language->get('button_confirm'),
                 'instruction'    => nl2br($langdata[$this->config->get('config_language_id')]['instruction']),
                 'continue'       => $this->url->link('checkout/success', '', 'SSL'),
                 'laterpay_mode'  => $this->getLaterpayMode(),
                 'action'         => $this->url->link($this->pay, '', 'SSL'),
                 'confirm'        => $this->url->link('extension/payment/ocstore_yk/confirm', '', 'SSL'),
                 'parameters'     => array('paymentType'  => $payment_type)
                 //'parameters'   => $this->makeForm()
            )
        );

        return $this->load->view('extension/payment/ocstore_yk', $data);
    }

    public function laterpay() {
        if ($this->validateLaterpay()) {
            $data = $this->_setData(array(
                'action'     => $this->url->link($this->pay, '&order_id=' . $this->request->get['order_id'], 'SSL'),
                'parameters' => $this->getCustomerData()
                //'parameters' => $this->makeForm($this->request->get['order_id'])
            ));

            $this->response->setOutput($this->load->view('extension/payment/ocstore_yk_laterpay', $data));
        } else {
            $this->logWrite('Fail Validate Laterpay:', self::$LOG_SHORT);
            $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
            $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
            $this->response->redirect($this->url->link('common/home', '', 'SSL'));
        }
    }

    public function pay() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
              $server = $this->config->get('config_ssl');
            } else {
              $server = $this->config->get('config_url');
            }

            $data = $this->_setData(array(
                     'text_proceed_payment',
                     'action'     => $this->getUrl(),
                     'parameters' => $this->makeForm(isset($this->request->get['order_id']) ? $this->request->get['order_id'] : false),
                     'loading'    => $server . 'catalog/view/theme/default/image/ocstore_yk_loading.gif'
            ));

            $this->response->setOutput($this->load->view('extension/payment/ocstore_yk_pay', $data));
        } else {
            //откуда ушел - туда и вернется
            if ($this->validateSession()) {
                $this->logWrite('Page Pay: validateSession Success', self::$LOG_SHORT);
                $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
            } else {
                $this->logWrite('Page Pay: validateSession Fail', self::$LOG_SHORT);
                $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
            }
        }
    }

    //Physical Callback
    public function status() {
        $this->logWrite('StatusURL: ', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

        //Test Request from Yandex
        if (isset($this->request->post['test_notification'])) {
            $this->sendOk();
            exit;
        }

        if (!$this->validate()) {
            $this->logWrite('StatusURL: Validate Fail', self::$LOG_SHORT);
            $this->sendOk();
            exit;
        }

        if ($this->order['order_status_id']){
            //Reverse $this->config->get('ocstore_yk_notify_customer_success')
            $notify = !$this->config->get('ocstore_yk_notify_customer_success');
            $text_mode = $this->config->get('ocstore_yk_test_mode') ? '(Test Mode)' : '(Real Mode)';
            $this->model_checkout_order->addOrderHistory($this->order['order_id'],
                                                 $this->config->get('ocstore_yk_order_status_id'),
                                                 'Yandex.Money: Payment Success ' . $text_mode . ': ' . $this->request->post['notification_type'],
                                                  $notify);
            if (!$notify) {
                $langdata = $this->config->get('ocstore_yk_langdata');
                $langdata = $langdata[(int)$this->config->get('config_language_id')];
                $this->sendMail($langdata['mail_customer_success_subject'],
                                $langdata['mail_customer_success_content'],
                                $this->config->get('ocstore_yk_order_status_id'),
                                'customer',
                                'Mail to Customer Sent Successfully: Payment Success ' . $text_mode);
            }

            if ($this->config->get('ocstore_yk_notify_admin_success')) {
                $this->sendMail($this->config->get('ocstore_yk_mail_admin_success_subject'),
                                $this->config->get('ocstore_yk_mail_admin_success_content'),
                                $this->config->get('ocstore_yk_order_status_id'),
                                'admin',
                                'Mail to Admin Sent Successfully: Payment Success ' . $text_mode);
            }
        }
        $this->sendOk();
        $this->success();
    }

    //Company Check
    public function check() {
        $this->logWrite('CheckURL: ', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

        //Защита от 'дурака', на случай, если в анкете перепутали урлы check и aviso
        $this->session->data['ocstore_yk_check'] = isset($this->request->post['orderNumber']) ? $this->request->post['orderNumber'] : 0;
        //Если все OK - checkURL должен вернуть шлюзу 'code = 0', иначе code = 1
        $this->sendOk((int)!$this->validate(), 'check');
    }


    //Company Callback (Aviso)
    public function callback() {
        $this->logWrite('CallbackURL: ', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

        if (!$this->validate()) {
        //Validate Fail - paymentAvisoURL должен вернуть шлюзу 'code = 1'
            $this->sendOk(1, 'aviso');
            exit;
        }
        
        //Защита от 'дурака', на случай, если в анкете перепутали урлы check и aviso
        if (isset($this->session->data['ocstore_yk_check']) && ($this->session->data['ocstore_yk_check'] == $this->order['order_id'])) {
            unset($this->session->data['ocstore_yk_check']);
        } else {
            $this->sendForbidden('CallbackURL: First there should be a request to the Check URL', self::$LOG_SHORT);
            $this->sendOk(1, 'aviso');
            exit;
       }

        if ($this->order['order_status_id']){
            //Reverse $this->config->get('ocstore_yk_notify_customer_success')
            $notify = !$this->config->get('ocstore_yk_notify_customer_success');
            $text_mode = $this->config->get('ocstore_yk_test_mode') ? '(Test Mode)' : '(Real Mode)';
            $this->model_checkout_order->addOrderHistory($this->order['order_id'],
                                                 $this->config->get('ocstore_yk_order_status_id'),
                                                 'Yandex.Kassa: Payment Success ' . $text_mode . ': ' . $this->request->post['paymentType'],
                                                  $notify);
            if (!$notify) {
                $langdata = $this->config->get('ocstore_yk_langdata');
                $langdata = $langdata[(int)$this->config->get('config_language_id')];
                $this->sendMail($langdata['mail_customer_success_subject'],
                                $langdata['mail_customer_success_content'],
                                $this->config->get('ocstore_yk_order_status_id'),
                                'customer',
                                'Mail to Customer Sent Successfully: Payment Success ' . $text_mode);
            }

            if ($this->config->get('ocstore_yk_notify_admin_success')) {
                $this->sendMail($this->config->get('ocstore_yk_mail_admin_success_subject'),
                                $this->config->get('ocstore_yk_mail_admin_success_content'),
                                $this->config->get('ocstore_yk_order_status_id'),
                                'admin',
                                'Mail to Admin Sent Successfully: Payment Success ' . $text_mode);
            }
        }
        //Все OK - paymentAvisoURL должен вернуть шлюзу 'code = 0'
        $this->sendOk(0, 'aviso');
    }

    public function success() {
        $this->logWrite('SuccessURL: ', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

        if (!isset($this->session->data['order_id'])) {
          $this->session->data['order_id'] = $this->order['order_id']; //Добавляем в сессию номер заказа на случай, если в checkout/success на экран пользователю выводится номер заказа
        }
        
        if ($this->config->get('ocstore_yk_type')) {
            $this->response->redirect($this->url->link('checkout/success', 'SSL'));
        }
    }

    public function fail() {
        $this->logWrite('FailURL: ', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

        if (!$this->validate(false, false)) {
            $this->logWrite('FailURL: Validate Fail', self::$LOG_SHORT);
        }

        if (isset($this->order['order_status_id']) && $this->order['order_status_id']) {
              //Reverse $this->config->get('ocstore_yk_notify_customer_fail')
              $notify = !$this->config->get('ocstore_yk_notify_customer_fail');
              $text_mode = $this->config->get('ocstore_yk_test_mode') ? '(Test Mode)' : '(Real Mode)';

              $this->model_checkout_order->addOrderHistory($this->order['order_id'],
                                                  $this->config->get('ocstore_yk_order_fail_status_id'),
                                                  'Yandex.Kassa: Payment Fail ' . $text_mode,
                                                  $notify);

            if (!$notify) {
                $langdata = $this->config->get('ocstore_yk_langdata');
                $langdata = $langdata[(int)$this->config->get('config_language_id')];
                $this->sendMail($langdata['mail_customer_fail_subject'],
                                $langdata['mail_customer_fail_content'],
                                $this->config->get('ocstore_yk_order_fail_status_id'),
                                'customer',
                                'Mail to Customer Sent Successfully: Payment Fail ' . $text_mode);
            }

            if ($this->config->get('ocstore_yk_notify_admin_fail')) {
                $this->sendMail($this->config->get('ocstore_yk_mail_admin_fail_subject'),
                                     $this->config->get('ocstore_yk_mail_admin_fail_content'),
                                     $this->config->get('ocstore_yk_order_fail_status_id'),
                                     'admin',
                                     'Mail to Admin Sent Successfully: Payment Fail ' . $text_mode);
            }
        }

        $this->logWrite('FailURL: Payment Fail', self::$LOG_SHORT);
        if ($this->config->get('ocstore_yk_type')) {
            $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
        }
    }

    public function confirm() {
        if (!empty($this->session->data['order_id'])) {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ocstore_yk_order_confirm_status_id'));
            //откуда ушел - туда и вернется
            $this->session->data['return_from_yandex']['order_id'] = $this->session->data['order_id'];
        }
    }

    protected function makeForm($order_id = false) {
        if (!$order_id ) {
          if (isset($this->session->data['order_id'])) {
            $order_id  = $this->session->data['order_id'];
          } else {
            $this->logWrite('Error: Unsupported Checkout Extension', self::$LOG_SHORT);
            die($this->language->get('error_fail_checkout_extension'));
          }
        }
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $sCurrencyCode = $this->getCurrencyCode();

        $payment_type = $this->request->post['paymentType'];
        //$sum = number_format($this->currency->convert($order_info['total'], $this->config->get('config_currency'), $sCurrencyCode), 2, '.', '');
        $sum = $this->currency->convert($order_info['total'], $this->config->get('config_currency'), $sCurrencyCode);
        $comission = (float)$this->config->get($this->getSubMethod($payment_type, 'comission'));
        if (is_numeric($comission)) {
            $sum += $sum * ($comission / 100);
        }
        $sum = number_format($sum, 2, '.', '');
        $targets = sprintf($this->language->get('text_order_description'), $order_id, $sum, $sCurrencyCode);
        
        $server = isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] ? $this->config->get('config_ssl') : $this->config->get('config_url');
        $server = str_replace('http://', 'https://', $server);

        if ($this->config->get('ocstore_yk_type')) {
            //company form
            $params = array(
                'shopId'          => $this->config->get('ocstore_yk_shop_id'), //было shopid
                'scid'            => $this->config->get('ocstore_yk_scid'),
                'orderNumber'     => $order_id,
                'sum'             => $sum,
                'customerNumber'  => $order_info['payment_firstname'] . ' ' . $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'] . ' ' . $order_info['payment_city'] . ' ' . $order_info['email'], //???
                'shopSuccessURL'  => $server . 'index.php?route=extension/payment/ocstore_yk/success',
                'shopFailURL'     => $server . 'index.php?route=extension/payment/ocstore_yk/fail',
                'cps_email'       => $order_info['email'],
                'cps_phone'       => $order_info['telephone'],
                'paymentType'     => $payment_type
            );
        } else {
            //physical form
            $params = array(
                'receiver'          => $this->config->get('ocstore_yk_wallet'), //кошелек мерчанта
                'formcomment'       => $this->config->get('config_name'), //(до 50 символов) — название платежа в истории отправителя. Сделать сочетание с $order_id. Можно частично взять из Промсвязьбанка
                'short_dest'        => $this->config->get('config_name'), //название платежа на странице подтверждения. Рекомендуем делать его таким же, как formcomment
                'quickpay-form'     => 'shop',
                'targets'           => $targets, //(до 150 символов) — назначение платежа,
                'sum'               => $sum, //сумма перевода. Из этой суммы вычитается комиссия:0,5%, если перевод отправлен из электронного кошелька;2%, если перевод отправлен с произвольной банковской карты,
                'label'             => $order_id, //(до 64 символов) — метка, которую сайт или приложение присваивает конкретному переводу. Например, в качестве метки можно указывать код или идентификатор заказа,
                'comment'           => $order_info['comment'], //(до 200 символов) — поле, в котором можно передать комментарий отправителя перевода
                'paymentType'       => $payment_type //(тип платежа)
            );
        }

        $this->logWrite('Make payment form: ', self::$LOG_FULL);
        $this->logWrite('  DATA: ' . var_export($params, true), self::$LOG_FULL);

        return $params;
    }

    protected function getCustomerData() {
        if (isset($this->request->get['paymentType']) && $this->request->get['paymentType']) {
            return array('paymentType'  => utf8_substr($this->request->get['paymentType'], -2, 2)); //Нужно вернуть 2 последних символа кода метода оплаты
        } else {
            return 'AC';
        }
        //return array('paymentType'  => isset($this->request->get['paymentType']) ? $this->request->get['paymentType'] : 'AC');
    }

    protected function validateLaterpay() {
        if ((!isset($this->request->get['order_id'])) || (!isset($this->request->get['order_ttl']))) {
          return false;
        } else {
          $this->load->model('checkout/order');
          $order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);
          if ((!$order_info) || ($this->request->get['order_id'] != $order_info['order_id']) || ($this->request->get['order_ttl'] != $order_info['total'])) {
            return false;
          }
        }
        return true;
    }

    protected function validateSession() {
        if (isset($this->session->data['return_from_yandex'])) {
            $this->load->model('checkout/order');
            $success_order_info = $this->model_checkout_order->getOrder($this->session->data['return_from_yandex']['order_id']);
            unset($this->session->data['return_from_yandex']);
            if (!$success_order_info) {
                return false;
            }
            return $success_order_info['order_status_id'] == $this->config->get('ocstore_yk_order_status_id');
        } else {
            return false;
        }
    }

    protected function validate($check_sign_hash = true, $check_request_method = true) {
        $this->load->model('checkout/order');

        if ($check_request_method) {
            if ($this->request->server['REQUEST_METHOD'] != 'POST') {
                $this->sendForbidden($this->language->get('text_error_post'));
                return false;
            }
        } else {
            //Если запрос был с payment/ocstore_yk/fail - в $_POST помещаем данные из $_GET (нам нужно узнать отсюда только order_id)
            $this->request->post = array_merge($this->request->post, $this->request->get);
        }

        if ($check_sign_hash) {
            $signature = $this->calculateHash();

            $payment_hash = $this->config->get('ocstore_yk_type') ? $this->request->post['md5'] : $this->request->post['sha1_hash'];
            if (strtoupper($payment_hash) != $signature) {
                $this->sendForbidden(sprintf($this->language->get('text_error_sign'), $signature));
                return false;
            }

            if ($this->config->get('ocstore_yk_type') &&
               (($this->request->post['action'] != 'checkOrder') && ($this->request->post['action'] != 'paymentAviso'))) {
                $this->sendForbidden($this->language->get('text_error_action'));
                return false;
            }
        }

        if ($this->config->get('ocstore_yk_type')) {
            $order_id = isset($this->request->post['orderNumber']) ? $this->request->post['orderNumber']: 0;
        } else {
            $order_id = isset($this->request->post['label']) ? $this->request->post['label']: 0;
        }
        $this->order = $this->model_checkout_order->getOrder($order_id);

        if (!$this->order) {
            $this->sendForbidden(sprintf($this->language->get('text_error_order_not_found'), $order_id));
            return false;
        }

        // Fraud Detection
        if ($check_request_method) {
            $amount_order = $this->currency->format($this->order['total'], $this->order['currency_code'], $this->order['currency_value'], false);
                $payment_type = $this->getPaymentType();
                $comission = (float)$this->config->get($this->getSubMethod($payment_type, 'comission'));
                if (is_numeric($comission)) {
                    $amount_order += $amount_order * ($comission / 100);
                }
            $amount_order = number_format(ceil($amount_order), 2, '.', '');

            $amount_payment = ceil($this->getAmountPayment());
            if ($amount_order != $amount_payment) {
                $this->sendForbidden(sprintf($this->language->get('text_error_fraud'),
                                             $amount_payment, $this->order['currency_code'], $amount_order, $this->order['currency_code']));
                return false;
            }
        }

        return true;
    }

    protected function calculateHash() {
        if ($this->config->get('ocstore_yk_type')) {
            //company mode
            if (isset($this->request->post['action']) &&
                isset($this->request->post['orderSumAmount']) &&
                isset($this->request->post['orderSumCurrencyPaycash']) &&
                isset($this->request->post['orderSumBankPaycash']) &&
                isset($this->request->post['shopId']) &&
                isset($this->request->post['invoiceId']) &&
                isset($this->request->post['customerNumber'])) {
                
                return strtoupper(md5($this->request->post['action'] . ';' .
                                       $this->request->post['orderSumAmount'] . ';' .
                                       $this->request->post['orderSumCurrencyPaycash'] . ';' .
                                       $this->request->post['orderSumBankPaycash'] . ';' .
                                       $this->request->post['shopId'] . ';' .
                                       $this->request->post['invoiceId'] . ';' .
                                       $this->request->post['customerNumber'] . ';' .
                                       $this->config->get('ocstore_yk_shop_password')
                                      ));
            } else {
                return '';
            }
        } else {
            //physical  mode
            if (isset($this->request->post['notification_type']) &&
                isset($this->request->post['operation_id']) &&
                isset($this->request->post['amount']) &&
                isset($this->request->post['currency']) &&
                isset($this->request->post['datetime']) &&
                isset($this->request->post['sender']) &&
                isset($this->request->post['codepro']) &&
                isset($this->request->post['label'])) {
                
                return strtoupper(hash('sha1',
                                       $this->request->post['notification_type'] . '&' .
                                       $this->request->post['operation_id'] . '&' .
                                       $this->request->post['amount'] . '&' .
                                       $this->request->post['currency'] . '&' .
                                       $this->request->post['datetime'] . '&' .
                                       $this->request->post['sender'] . '&' .
                                       $this->request->post['codepro'] . '&' .
                                       $this->config->get('ocstore_yk_shop_password') . '&' .
                                       $this->request->post['label']
                                      ));
            } else {
                return '';
            }
        }
    }

    protected function sendForbidden($error, $action = false) {
        $this->logWrite('ERROR: ' . $error, self::$LOG_SHORT);
        if ($action) {
            //header('HTTP/1.1 403 Forbidden');
            echo 'Error: ' . $error;
        }
    }

    protected function sendOk($code = 1, $type = 'check') {
        if (!$this->config->get('ocstore_yk_type')) {
            //physical mode && Payment Success
            $this->logWrite('OK: ' . http_build_query($this->request->post, '', ','), self::$LOG_SHORT);
            //header('HTTP/1.1 200 OK');
            echo "HTTP 200 OK";
        } else {
            //company mode
            $this->logWrite('OK: code = ' . $code . '; type = ' . ($type == 'check' ? 'check' : 'aviso') . '; POST: ' . http_build_query($this->request->post, '', ','), self::$LOG_SHORT);
            header("Content-type: text/xml; charset=utf-8");
            $message =  '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $message .=      '<' . ($type == 'check' ? 'checkOrderResponse' : 'paymentAvisoResponse') . ' performedDatetime="' . date("c") . '" code="' . $code . '" invoiceId="' . (isset($this->request->post['invoiceId']) ? $this->request->post['invoiceId'] : '') . '" shopId="'.$this->config->get('ocstore_yk_shop_id') . '" />';
            echo $message;
        }
    }

    //type = 'admin' - mail send to admin; type = 'customer' - mail send to customer
    protected function sendMail($subject, $content, $order_status_id, $type = 'admin', $log_result = '') {
				$this->load->model('extension/payment/ocstore_yk');

				$order_info = $this->model_extension_payment_ocstore_yk->getOrder($this->order['order_id']);

				$input = array(
            '{order_id}',
				    '{store_name}',
						'{logo}',
						'{total}',
						'{customer_firstname}',
						'{customer_lastname}',
						'{customer_group}',
						'{customer_email}',
						'{customer_telephone}',
						'{order_status}',
						'{comment}',
						'{ip}',
						'{date_added}',
						'{date_modified}'
			  );

				$output = array(
            'order_id'            => $order_info['order_id'],
				    'store_name'          => $this->config->get('config_name'),
				    'logo'                => '<a href="' . HTTP_SERVER . '"><img src="' . HTTP_SERVER . 'image/' . $this->config->get('config_logo') . '" / ></a>',
						'total'               => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']),
						'customer_firstname'  => $order_info['payment_firstname'],
						'customer_lastname'   => $order_info['payment_lastname'],
						'customer_group'      => $this->model_extension_payment_ocstore_yk->getCustomerGroup($order_info['customer_group_id']),
						'customer_email'      => $order_info['email'],
						'customer_telephone'  => $order_info['telephone'],
						'order_status'        => $this->model_extension_payment_ocstore_yk->getOrderStatusById($order_status_id, $order_info['language_id']),
						'comment'             => $order_info['comment'],
						'ip'                  => $order_info['ip'],
						'date_added'          => $order_info['date_added'],
						'date_modified'       => $order_info['date_modified']
			  );

		    $subject = html_entity_decode(trim(str_replace($input, $output, $subject)), ENT_QUOTES, 'UTF-8');
		    $content = html_entity_decode(str_replace($input, $output, $content), ENT_QUOTES, 'UTF-8');
		    $to = $type == 'admin' ? $this->config->get('config_email') : $order_info['email'];
		    $this->_sendMail($subject, $content, $type, $to);
		    $this->logWrite($log_result, self::$LOG_FULL);
    }

    protected function _sendMail($subject, $content, $type, $to) {
				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '  <head>' . "\n";
				$message .= '    <title>' . $subject . '</title>' . "\n";
				$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '  </head>' . "\n";
				$message .= '  <body>' . $content . '</body>' . "\n";
				$message .= '</html>' . "\n";

        if (version_compare(VERSION, '2.0.0.0', '>=') && version_compare(VERSION, '2.0.2.0', '<')) { // 2.0.0.0 || 2.0.1 || 2.0.1.1 || 2.0.1.2_rc
            $mail = new Mail($this->config->get('config_mail'));
        } elseif (version_compare(VERSION, '2.0.3.1', '>=')) { // 2.0.3.1 || 2.1.x || 2.2.x
            $mail = new Mail();
            $mail->protocol   = $this->config->get('config_mail_protocol');
            $mail->parameter  = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname   = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username   = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password   = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port       = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout    = $this->config->get('config_mail_smtp_timeout');
        } else { // 2.0.2.0
            $mail = new Mail();
            $mail->protocol   = $this->config->get('config_mail_protocol');
            $mail->parameter  = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname   = $this->config->get('config_mail_smtp_host');
            $mail->smtp_username   = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password   = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port       = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout    = $this->config->get('config_mail_smtp_timeout  ');
        }

				$mail->setTo($to);
        $mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setHtml($message);
				$mail->send();

				if ($type == 'admin') {
            $emails = explode(',', $this->config->get('config_mail_alert'));
            $regexp = $this->config->get('config_mail_regexp') ?: '/^[^\@]+@.*.[a-z]{2,15}$/i';
            foreach ($emails as $email) {
                if ($email && preg_match($regexp, $email)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
				}
    }

    protected function getAmountPayment() {
        //return number_format($this->currency->convert($this->config->get('ocstore_yk_type') ? $this->request->post['orderSumAmount'] : $this->request->post['withdraw_amount'],
        //                      $this->getCurrencyCode(), $this->config->get('config_currency')), 2, '.', '');
        return $this->currency->format($this->config->get('ocstore_yk_type') ? $this->request->post['orderSumAmount'] : $this->request->post['withdraw_amount'],
                                        $this->getCurrencyCode(), 1, false);
    }

    protected function getPaymentType() {
        if ($this->config->get('ocstore_yk_type')) {
            return $this->request->post['paymentType'];
        } else {
            return isset($this->request->post['notification_type']) && ($this->request->post['notification_type'] == 'card-incoming') ? 'AC' : 'PC';
        }
    }

    protected function getUrl() {
        if ($this->config->get('ocstore_yk_type')) {
            return $this->config->get('ocstore_yk_test_mode') ? 'https://demomoney.yandex.ru/eshop.xml': 'https://money.yandex.ru/eshop.xml';
        } else {
            return $this->config->get('ocstore_yk_test_mode') ? 'https://demomoney.yandex.ru/quickpay/confirm.xml': 'https://money.yandex.ru/quickpay/confirm.xml';
        }
    }

    protected function getCurrencyCode() {
        foreach (self::$valid_currencies as $code) {
            if ($this->currency->has($code)) {
                return $code;
            }
        }
        die(sprintf('Currency code (%s) not found', implode(', ', self::$valid_currencies)));
    }

    protected function getSubMethod($payment_type, $method) {
        return sprintf('ocstore_yk_%s_%s_%s', $this->config->get('ocstore_yk_type') ? 'company' : 'physical', $payment_type, $method);
    }

    protected function getLaterpayMode() {
        $result = (int)$this->config->get('ocstore_yk_laterpay_mode');
        if ($result && ($this->config->get('ocstore_yk_order_later_status_id') == $this->config->get('ocstore_yk_order_confirm_status_id'))) {
            $result = 0;
        }
        return $result;
    }

    protected function _setData($values) {
        $data = array();
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $data[$value] = $this->language->get($value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    protected function logWrite($message, $type) {
        switch ($this->config->get('ocstore_yk_log')) {
            case self::$LOG_OFF:
                return;
            case self::$LOG_SHORT:
                if ($type == self::$LOG_FULL) {
                    return;
                }
        }

        if (!$this->log) {
            $this->log = new Log($this->config->get('ocstore_yk_log_filename'));
        }
        $this->log->Write($message);
    }
}
?>