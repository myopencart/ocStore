<?php
class ControllerExtensionPaymentShoputilsIk extends Controller {
    private $order;
    private $log;
    private $data = array();
    private static $ACTION = 'https://sci.interkassa.com';
    private static $LOG_OFF = 0;
    private static $LOG_SHORT = 1;
    private static $LOG_FULL = 2;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('extension/payment/shoputils_ik');
    }

    public function index() {
        $langdata = $this->config->get('shoputils_ik_langdata');
        $this->_setData(
            array(
                 'button_confirm',
                 'instruction'  => nl2br($langdata[$this->config->get('config_language_id')]['instruction']),
                 'action'       => self::$ACTION,
                 'parameters'   => $this->makeForm()
            )
        );

        return $this->load->view('extension/payment/shoputils_ik', $this->data);
    }

    public function status() {
        $this->logWrite('StatusURL: ', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

        if (!$this->validate(true)) {
            return;
        }

        if ($this->request->post['ik_inv_st']) {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($this->order['order_id'],
                                                $this->config->get('shoputils_ik_order_status_id'),
                                                sprintf($this->language->get('text_comment'), $this->request->post['ik_pw_via'], $this->request->post['ik_am']),
                                                true);
        }

        $this->sendOk();
    }

    public function success() {
        $this->logWrite('SuccessURL', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

        if ($this->validate(false)) {
          if (!isset($this->session->data['order_id'])) {
            $this->session->data['order_id'] = $this->order['order_id']; //Добавляем в сессию номер заказа на случай, если в checkout/success на экран пользователю выводится номер заказа
          }
            $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
        } else {
            $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
        }
    }

    public function fail() {
        $this->logWrite('FailURL', self::$LOG_SHORT);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
        if ($this->validate(false)) {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($this->order['order_id'],
                                                $this->config->get('shoputils_ik_order_fail_status_id'),
                                                $this->language->get('text_comment_fail'),
                                                true);
        }

        $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
    }

    public function confirm() {
        if (!empty($this->session->data['order_id']) && $this->config->get('shoputils_ik_order_confirm_status_id') && ($this->session->data['payment_method']['code'] == 'shoputils_ik')) {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('shoputils_ik_order_confirm_status_id'));
        }
    }

    protected function makeForm($order_id = false) {
        $this->load->model('checkout/order');
        if (!$order_id ) {
          if (isset($this->session->data['order_id'])) {
            $order_id  = $this->session->data['order_id'];
          } else {
            $this->logWrite('Error: Unsupported Checkout Extension', self::$LOG_SHORT);
            die($this->language->get('error_fail_checkout_extension'));
          }
        }
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $ikCurrencyCode = $this->config->get('shoputils_ik_currency');
        if (!$this->currency->has($ikCurrencyCode)) {
            die(sprintf('Currency code (for code: %s) not found', $ikCurrencyCode));
        }
        
        $ik_payment_amount = number_format($this->currency->convert($order_info['total'], $this->config->get('config_currency'), $ikCurrencyCode), 2, '.', '');
        $ik_shop_id       = $this->config->get('shoputils_ik_shop_id');
        $ik_payment_desc  = sprintf($this->language->get('text_ik_payment_desc'), $order_info['order_id']);
        $ik_lifetime      = $this->config->get('shoputils_ik_lifetime');

        $params = array(
            'ik_am'     => $ik_payment_amount,
            'ik_co_id'  => $ik_shop_id,
            'ik_cur'    => $ikCurrencyCode,
            'ik_desc'   => $ik_payment_desc,
            //'ik_ltm'    => $ik_lifetime,
            'ik_pm_no'  => $order_info['order_id']
        );
        $params['ik_sign'] = base64_encode(md5(implode(':', array_values($params)) . ':' . $this->config->get('shoputils_ik_sign_hash'), true));

        $this->logWrite('Make payment form: ', self::$LOG_SHORT);
        $this->logWrite('  DATA: ' . var_export($params, true), self::$LOG_FULL);

        return $params;
    }

    protected function validate($check_sign_hash = true) {
        $this->load->model('checkout/order');

        if ((ip2long($this->request->server['REMOTE_ADDR']) < ip2long('151.80.190.97')) && (ip2long($this->request->server['REMOTE_ADDR']) > ip2long('151.80.190.104'))) {
            $this->sendForbidden(sprintf($this->language->get('text_error_allowed_range_ip'), $this->request->server['REMOTE_ADDR']));
            return false;
        }

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->sendForbidden($this->language->get('text_error_post'));
            return false;
        }

        if ($check_sign_hash && isset($sign_ik)) {
            $ik_sign_hash_array = $this->request->post;
            unset($ik_sign_hash_array['ik_sign']);
            ksort($ik_sign_hash_array, SORT_STRING);
            array_push($ik_sign_hash_array, $this->config->get('shoputils_ik_test_mode') ? $this->config->get('shoputils_ik_sign_test_key') : $this->config->get('shoputils_ik_sign_hash')); //$this->config->get('shoputils_ik_sign_hash');
            $ik_sign_hash_string = implode(':', $ik_sign_hash_array);
            $ik_sign_hash = base64_encode(md5($ik_sign_hash_string, true));

            if ($this->request->post['ik_sign'] != $ik_sign_hash) {
                $this->sendForbidden($this->language->get('text_error_ik_sign_hash'));
                $this->logWrite($ik_sign_hash . '=md5(' . $ik_sign_hash_string . ')', self::$LOG_SHORT);
                return false;
            }
        }

        $this->order = $this->model_checkout_order->getOrder($this->request->post['ik_pm_no']);

        if (!$this->order) {
            $this->sendForbidden(sprintf($this->language->get('text_error_order_not_found'), $this->request->post['ik_pm_no']));
            return false;
        }

        return true;
    }

    protected function sendForbidden($error) {
        $this->logWrite('ERROR: ' . $error, self::$LOG_SHORT);
        $this->response->addHeader('HTTP/1.1 403 Forbidden');
        //header('HTTP/1.1 403 Forbidden');
        echo "<html>
                <head>
                   <title>403 Forbidden</title>
                </head>
                <body>
                    <p>$error.</p>
                </body>
        </html>";
    }

    protected function sendOk() {
        $this->logWrite('OK: ' . http_build_query($this->request->post, '', ','), self::$LOG_SHORT);
        $this->response->addHeader('HTTP/1.1 200 OK');
        //header('HTTP/1.1 200 OK');
        echo "<html><head><title>200 OK</title></head></html>";
    }

    protected function _setData($values) {
        $this->data = array();
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $this->data[$value] = $this->language->get($value);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    protected function logWrite($message, $type) {
        switch ($this->config->get('shoputils_ik_log')) {
            case self::$LOG_OFF:
                return;
            case self::$LOG_SHORT:
                if ($type == self::$LOG_FULL) {
                    return;
                }
        }

        if (!$this->log) {
            $this->log = new Log($this->config->get('shoputils_ik_log_filename'));
        }
        $this->log->Write($message);
    }
}
?>