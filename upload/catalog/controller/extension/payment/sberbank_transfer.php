<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 *
*/
class ControllerExtensionPaymentSberBankTransfer extends Controller {

    private static $valid_currencies = array('RUB', 'RUR');
    private $currency_code;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('extension/payment/sberbank_transfer');
    }

    public function index() {
        $this->getCurrencyCode();
        
        $data['text_printpay']        = sprintf($this->language->get('text_printpay'), $this->url->link('extension/payment/sberbank_transfer/printpay', '', 'SSL'));
        $data['text_instruction']     = $this->language->get('text_instruction');
        $data['text_payment']         = $this->language->get('text_payment');
        $data['text_payment_comment'] = $this->language->get('text_payment_comment');
        $data['text_order_history']   = $this->customer->isLogged() ? sprintf($this->language->get('text_order_history'), $this->url->link('account/order', '', 'SSL')) : '';
        $data['button_confirm']       = $this->config->get('sberbank_transfer_button_confirm_' . $this->config->get('config_language_id'));
        $data['continue']             = $this->url->link('checkout/success');

        return $this->load->view('extension/payment/sberbank_transfer', $data);
    }

    public function printpay() {
        $this->load->model('checkout/order');
        
        if (!empty($this->request->get['order_id'])) {
            $order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);

            if (!$order_info || !$this->validateTransferPay()) {
                $this->response->redirect($this->url->link('account/order', '', 'SSL'));
            }
        } else {
            $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            if (!$order_info) {
                $this->response->redirect($this->url->link('account/order', '', 'SSL'));
            }
        }


        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_back']    = $this->language->get('button_back');
        $data['text_confirm']   = $this->language->get('text_confirm');

        $data['bank']     = nl2br($this->config->get('sberbank_transfer_bank_' . $this->config->get('config_language_id')));
        $data['inn']      = $this->config->get('sberbank_transfer_inn');
        $data['rs']       = $this->config->get('sberbank_transfer_rs');
        $data['bankuser'] = $this->config->get('sberbank_transfer_bankuser_' . $this->config->get('config_language_id'));
        $data['bik']      = $this->config->get('sberbank_transfer_bik');
        $data['ks']       = $this->config->get('sberbank_transfer_ks');

        $this->getCurrencyCode();
        $rur_order_total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $this->currency_code);

        $data['amount']   = $this->currency->format($rur_order_total, $this->currency_code, $order_info['currency_value'], false);
        $data['order_id'] = $order_info['order_id'];
        $data['name']     = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
        $data['address']  = $order_info['payment_zone'] . ', ' . $order_info['payment_city'] . ', ' .$order_info['payment_address_1'] . ($order_info['payment_address_2'] ? ', ' . $order_info['payment_address_2'] : '');
        $data['postcode'] = $order_info['payment_postcode'];

        $this->response->setOutput($this->load->view('extension/payment/sberbank_transfer_printpay.tpl', $data));
    }

    public function confirm() {
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info) {
            $comment  = $this->language->get('text_instruction') . "\n\n";
            $comment .= sprintf($this->language->get('text_printpay'), $this->url->link('extension/payment/sberbank_transfer/printpay', 'order_id=' . $order_info['order_id'] . '&order_ttl=' . $order_info['total'] . '&order_tel=' . $order_info['telephone'], 'SSL')) . "\n\n";
            $comment .= $this->language->get('text_payment_comment');

            $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('sberbank_transfer_order_status_id'), $comment, true);
        }
    }


    protected function validateTransferPay() {
        if ((!isset($this->request->get['order_id'])) || (!isset($this->request->get['order_ttl'])) || (!isset($this->request->get['order_tel']))) {
            return false;
        } else {
            $order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);
            return $order_info && ($this->request->get['order_id'] == $order_info['order_id']) && ($this->request->get['order_ttl'] == $order_info['total']) && ($this->request->get['order_tel'] == $order_info['telephone']);
        }
    }

    protected function getCurrencyCode() {
        $found = false;
        foreach (self::$valid_currencies as $code) {
            if ($this->currency->has($code)) {
                $this->currency_code = $code;
                $found = true;
                break;
            }
        }

        if (!$found) {
            die(sprintf('Currency code (%s) not found', implode(', ', self::$valid_currencies)));
        }
    }
}
?>