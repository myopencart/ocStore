<?php
class ControllerPaymentBluePayRedirect extends Controller {
	public function index() {
		$this->load->language('payment/bluepay_redirect');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_card_type'] = $this->language->get('text_card_type');
		$data['text_card_name'] = $this->language->get('text_card_name');
		$data['text_card_digits'] = $this->language->get('text_card_digits');
		$data['text_card_expiry'] = $this->language->get('text_card_expiry');

		$data['entry_card'] = $this->language->get('entry_card');
		$data['entry_card_existing'] = $this->language->get('entry_card_existing');
		$data['entry_card_new'] = $this->language->get('entry_card_new');
		$data['entry_card_save'] = $this->language->get('entry_card_save');
		$data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$data['entry_cc_address'] = $this->language->get('entry_cc_address');
		$data['entry_cc_city'] = $this->language->get('entry_cc_city');
		$data['entry_cc_state'] = $this->language->get('entry_cc_state');
		$data['entry_cc_zipcode'] = $this->language->get('entry_cc_zipcode');
		$data['entry_cc_phone'] = $this->language->get('entry_cc_phone');
		$data['entry_cc_email'] = $this->language->get('entry_cc_email');
		$data['entry_cc_choice'] = $this->language->get('entry_cc_choice');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text' => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		if ($this->config->get('bluepay_redirect_card') == '1') {
			$data['bluepay_redirect_card'] = true;
		} else {
			$data['bluepay_redirect_card'] = false;
		}

		$data['existing_cards'] = array();
		if ($this->customer->isLogged() && $data['bluepay_redirect_card']) {
			$this->load->model('payment/bluepay_redirect');

			$cards = $this->model_payment_bluepay_redirect->getCards($this->customer->getId());

			$data['existing_cards'] = $cards;
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bluepay_redirect.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/bluepay_redirect.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/bluepay_redirect.tpl', $data);
		}
	}

	public function send() {
		$this->load->language('payment/bluepay_redirect');

		$this->load->model('checkout/order');

		$this->load->model('payment/bluepay_redirect');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$post_data = $this->request->post;

		$post_data['MERCHANT'] = $this->config->get('bluepay_redirect_account_id');
		$post_data["TRANSACTION_TYPE"] = $this->config->get('bluepay_redirect_transaction');
		$post_data["MODE"] = strtoupper($this->config->get('bluepay_redirect_test'));
		$post_data["AMOUNT"] = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);

		if (isset($this->request->post['RRNO'])) {
			$post_data["RRNO"] = $this->request->post['RRNO'];
		} else {
			$post_data["RRNO"] = '';
		}

		$post_data["NAME"] = substr($order_info['payment_firstname'], 0, 20) . ' ' . substr($order_info['payment_lastname'], 0, 20);
		$post_data["ADDR1"] = $post_data['BillingAddress1'] = substr($order_info['payment_address_1'], 0, 100);
		$post_data["CITY"] = $order_info['payment_city'];
		$post_data['STATE'] = $order_info['payment_zone_code'];
		$post_data["PHONE"] = substr($order_info['telephone'], 0, 20);
		$post_data["EMAIL"] = substr($order_info['email'], 0, 255);
		$post_data["ORDER_ID"] = $this->session->data['order_id'];
		$post_data['ZIPCODE'] = substr($order_info['payment_postcode'], 0, 10);

		$post_data['APPROVED_URL'] = $this->url->link('payment/bluepay_redirect/callback', '', 'SSL');
		$post_data['DECLINED_URL'] = $this->url->link('payment/bluepay_redirect/callback', '', 'SSL');
		$post_data['MISSING_URL'] = $this->url->link('payment/bluepay_redirect/callback', '', 'SSL');

		if (isset($this->request->server["REMOTE_ADDR"])) {
			$post_data["REMOTE_IP"] = $this->request->server["REMOTE_ADDR"];
		}

		$tamper_proof_data = $this->config->get('bluepay_redirect_secret_key') . $post_data['MERCHANT'] . $post_data["TRANSACTION_TYPE"] . $post_data['AMOUNT'] . $post_data["RRNO"] . $post_data["MODE"];

		$post_data["TAMPER_PROOF_SEAL"] = md5($tamper_proof_data);

		$response_data = $this->model_payment_bluepay_redirect->sendCurl("https://secure.bluepay.com/interfaces/bp10emu", $post_data);

		if ($response_data['Result'] == 'APPROVED') {
			$bluepay_redirect_order_id = $this->model_payment_bluepay_redirect->addOrder($order_info, $response_data);

			$this->model_payment_bluepay_redirect->addTransaction($bluepay_redirect_order_id, $this->config->get('bluepay_redirect_transaction'), $order_info);

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('bluepay_redirect_order_status_id'));

			$json['redirect'] = $this->url->link('checkout/success', '', 'SSL');
		} else {
			$json['error'] = $response_data['Result'] . ' : ' . $response_data['MESSAGE'];
		}

		if (isset($post_data['CreateToken']) && $response_data['Result'] == 'APPROVED') {
			$card_data['customer_id'] = $this->customer->getId();
			$card_data['Last4Digits'] = substr(str_replace(' ', '', $post_data['CC_NUM']), -4, 4);
			$card_data['ExpiryDate'] = $post_data['CC_EXPIRES_MONTH'] . '/' . substr($post_data['CC_EXPIRES_YEAR'], 2);
			$card_data['CardType'] = $response_data['CARD_TYPE'];
			$card_data['Token'] = $response_data['RRNO'];

			$this->model_payment_bluepay_redirect->addCard($card_data);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function callback() {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->request->get));
	}
}