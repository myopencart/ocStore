<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/

class ControllerExtensionPaymentOcstorePayeer extends Controller {
    private $order;
    private $log;
    private $version;
    private $payeer_currencies = array('RUB', 'USD', 'EUR');
    private $data = array();
    private static $ACTION = '//payeer.com/merchant/';
    private static $LOG_OFF = 0;
    private static $LOG_SHORT = 1;
    private static $LOG_FULL = 2;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('extension/payment/ocstore_payeer');
    }

    public function index() {
        $langdata = $this->config->get('ocstore_payeer_langdata');
        $this->_setData(
            array(
                 'text_loading',
                 'button_confirm'   => $langdata[$this->config->get('config_language_id')]['button_confirm'] ?: $this->language->get('button_confirm'),
                 'instruction'  => nl2br($langdata[$this->config->get('config_language_id')]['instruction']),
                 'continue'     => $this->url->link('checkout/success', '', 'SSL'),
                 'pay_status'   => ((!$this->config->get('ocstore_payeer_laterpay_mode')) || ($this->config->get('ocstore_payeer_order_later_status_id') == $this->config->get('ocstore_payeer_order_confirm_status_id'))),
                 'action'       => self::$ACTION,
                 'parameters'   => $this->makeForm()
            )
        );


        return $this->load->view('extension/payment/ocstore_payeer', $this->data);
    }

    public function laterpay() {
        if ($this->validateLaterpay()) {
            $this->_setData(
                array(
                     'action'     => self::$ACTION,
                     'parameters' => $this->makeForm($this->request->get['order_id'])
                )
            );

            $this->response->setOutput($this->load->view('extension/payment/ocstore_payeer_laterpay.tpl', $this->data));
        } else {
            $this->logWrite('Fail Validate Laterpay:', self::$LOG_FULL);
            $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
            $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
            $this->response->redirect($this->url->link('common/home', '', 'SSL'));
        }
    }

    public function status() {
        $this->logWrite('StatusURL: ', self::$LOG_FULL);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
        
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->sendForbidden($this->language->get('text_error_post'));
            return;
          }

        if (!$this->validate(true)) {
            $this->sendFail($this->order['order_id']);
            return;
        }

        if ($this->request->post['m_status'] == 'success') {
            if ($this->order['order_status_id']) {
                //Reverse $this->config->get('ocstore_payeer_notify_customer_success')
                $notify = !$this->config->get('ocstore_payeer_notify_customer_success');
                $this->model_checkout_order->addOrderHistory($this->order['order_id'],
                    $this->config->get('ocstore_payeer_order_status_id'),
                    sprintf($this->language->get('text_comment'),
                        $this->request->post['m_operation_ps'],
                        $this->request->post['m_amount'],
                        $this->request->post['m_operation_id']
                    ),
                    $notify);
                if (!$notify) {
                    $langdata = $this->config->get('ocstore_payeer_langdata');
                    $langdata = $langdata[(int)$this->config->get('config_language_id')];
                    $this->sendMail($langdata['mail_customer_success_subject'],
                                    $langdata['mail_customer_success_content'],
                                    $this->config->get('ocstore_payeer_order_status_id'),
                                    'customer',
                                    'Mail to Customer Sent Successfully: Payment Success');
                }

                if ($this->config->get('ocstore_payeer_notify_admin_success')) {
                    $this->sendMail($this->config->get('ocstore_payeer_mail_admin_success_subject'),
                                    $this->config->get('ocstore_payeer_mail_admin_success_content'),
                                    $this->config->get('ocstore_payeer_order_status_id'),
                                    'admin',
                                    'Mail to Admin Sent Successfully: Payment Success');
                }
            }

        }
        $this->sendOk($this->order['order_id']);
    }

    public function success() {
        $this->logWrite('SuccessURL', self::$LOG_FULL);
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
        $this->logWrite('FailURL', self::$LOG_FULL);
        $this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
        $this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);
        $this->load->model('checkout/order');

        //Система будет работать как с POST так и с GET данными
        $this->request->post = array_merge($this->request->post, $this->request->get);

        if ($this->validate(false)) {
            if ($this->order['order_status_id']) {
                //Reverse $this->config->get('ocstore_payeer_notify_customer_fail')
                $notify = !$this->config->get('ocstore_payeer_notify_customer_fail');

                $this->model_checkout_order->addOrderHistory($this->order['order_id'],
                                                    $this->config->get('ocstore_payeer_order_fail_status_id'),
                                                    'PAYEER - Payment Fail, Order ID: ' . $this->request->post['m_orderid'],
                                                    $notify);

              if (!$notify) {
                  $langdata = $this->config->get('ocstore_payeer_langdata');
                  $langdata = $langdata[(int)$this->config->get('config_language_id')];
                  $this->sendMail($langdata['mail_customer_fail_subject'],
                                  $langdata['mail_customer_fail_content'],
                                  $this->config->get('ocstore_payeer_order_fail_status_id'),
                                  'customer',
                                  'Mail to Customer Sent Successfully: Payment Fail');
              }

              if ($this->config->get('ocstore_payeer_notify_admin_fail')) {
                  $this->sendMail($this->config->get('ocstore_payeer_mail_admin_fail_subject'),
                                       $this->config->get('ocstore_payeer_mail_admin_fail_content'),
                                       $this->config->get('ocstore_payeer_order_fail_status_id'),
                                       'admin',
                                       'Mail to Admin Sent Successfully: Payment Fail');
              }
            }
        }
        $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
    }

    public function confirm() {
        if (!empty($this->session->data['order_id']) && $this->session->data['payment_method']['code'] == 'ocstore_payeer') {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'],
                                                         $this->config->get('ocstore_payeer_order_confirm_status_id'));
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

        $current_currency = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');
        $sCurrencyCode = in_array($current_currency, $this->payeer_currencies) ? $current_currency : $this->config->get('ocstore_payeer_currency');

        if (!$this->currency->has($sCurrencyCode)) {
            die(sprintf('Currency code (%s) not found', $sCurrencyCode));
        }

        $m_amount   = number_format($this->currency->convert($order_info['total'], $this->config->get('config_currency'), $sCurrencyCode), 2, '.', '');
        $m_desc     = base64_encode(sprintf($this->language->get('text_order_description'), $order_id , $m_amount, $sCurrencyCode));

        $params = array(
                             'm_shop' => $this->config->get('ocstore_payeer_shop_id'),  //payeer_shop_id
                             'm_orderid' => $order_id,
                             'm_amount' => $m_amount,
                             'm_curr' => $sCurrencyCode,
                             'm_desc' => $m_desc
        );
        $params['m_sign'] = strtoupper(hash('sha256', implode(':', array_values($params)) . ':' . $this->config->get('ocstore_payeer_sign_hash')));

        $this->logWrite('Make payment form: ', self::$LOG_FULL);
        $this->logWrite('  DATA: ' . var_export($params, true), self::$LOG_FULL);

        return $params;
    }

    protected function validateLaterpay() {
        if (!isset($this->request->get['order_id']) || !isset($this->request->get['order_tt'])) {
            return false;
        } else {
            $this->load->model('checkout/order');
            $order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);
            if (!$order_info || ($this->request->get['order_id'] != $order_info['order_id']) || ($this->request->get['order_tt'] != $order_info['total'])) {
                return false;
            }
        }
        return true;
    }

    private function validate($check_sign_hash = true) {
        $this->load->model('checkout/order');

        if (isset($this->request->post['m_orderid'])) {
          $order_id = $this->request->post['m_orderid'];
        } elseif (isset($this->request->get['m_orderid'])) {
          $order_id = $this->request->get['m_orderid'];
        } else {
          $order_id = 0;
        }
        $this->order = $this->model_checkout_order->getOrder($order_id);

        if (!$this->order) {
            $this->sendForbidden(sprintf($this->language->get('text_error_order_not_found'), $order_id));
            return false;
        }


        if ($check_sign_hash) {
          if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->sendForbidden($this->language->get('text_error_post'));
            return false;
          } else {
            $m_sign_string =
                    $this->request->post['m_operation_id'] . ':' .
                    $this->request->post['m_operation_ps'] . ':' .
                    $this->request->post['m_operation_date'] . ':' .
                    $this->request->post['m_operation_pay_date'] . ':' .
                    $this->request->post['m_shop'] . ':' .
                    $this->request->post['m_orderid'] . ':' .
                    $this->request->post['m_amount'] . ':' .
                    $this->request->post['m_curr'] . ':' .
                    $this->request->post['m_desc'] . ':' .
                    $this->request->post['m_status'] . ':' .
                    $this->config->get('ocstore_payeer_sign_hash');
            $sign_hash = strtoupper(hash('sha256', $m_sign_string));

            if ($this->request->post['m_sign'] != $sign_hash) {
                $this->sendForbidden($this->language->get('text_error_m_sign'));

                $this->logWrite($sign_hash . '=hash(' . $m_sign_string . ')', self::$LOG_SHORT);

                return false;
            }
          }
        }
        
        return true;
    }

    private function sendForbidden($error) {
        $this->logWrite('ERROR: ' . $error, self::$LOG_SHORT);

        echo "<html>
                <head>
                   <title>403 Forbidden</title>
                </head>
                <body>
                    <p>" . $error . "</p>
                </body>
        </html>";
    }

    private function sendOk($order_id) {
        $this->logWrite('OK: ' . http_build_query($this->request->post, '', ','), self::$LOG_SHORT);

        header('HTTP/1.1 200 OK');

        echo $order_id . "|success";

    }
    
    private function sendFail($order_id) {
        $this->logWrite('Fail: ' . $order_id . '|error', self::$LOG_SHORT);

        header('HTTP/1.1 200 OK');

        echo $order_id . "|error";;
    }

    //type = 'admin' - mail send to admin; type = 'customer' - mail send to customer
    protected function sendMail($subject, $content, $order_status_id, $type = 'admin', $log_result = '') {
				$this->load->model('extension/payment/ocstore_payeer');

				$order_info = $this->model_extension_payment_ocstore_payeer->getOrder($this->order['order_id']);

				$input = array(
            '{order_id}',
				    '{store_name}',
						'{logo}',
						//'{products}',
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
						//'products'            => $this->model_extension_payment_ocstore_payeer->getProducts($order_info['order_id']),
						'total'               => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']),
						'customer_firstname'  => $order_info['payment_firstname'],
						'customer_lastname'   => $order_info['payment_lastname'],
						'customer_group'      => $this->model_extension_payment_ocstore_payeer->getCustomerGroup($order_info['customer_group_id']),
						'customer_email'      => $order_info['email'],
						'customer_telephone'  => $order_info['telephone'],
						'order_status'        => $this->model_extension_payment_ocstore_payeer->getOrderStatusById($order_status_id, $order_info['language_id']),
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

    private function logWrite($message, $type) {
        switch ($this->config->get('ocstore_payeer_log')) {
            case self::$LOG_OFF:
                return;
            case self::$LOG_SHORT:
                if ($type == self::$LOG_FULL) {
                    return;
                }
        }

        if (!$this->log) {
            $this->log = new Log($this->config->get('ocstore_payeer_log_filename'));
        }
        $this->log->Write($message);
    }
}
?>