<?php
class ControllerPaymentPPPayflow extends Controller {
	public function index() {
		$this->language->load('payment/pp_payflow');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_start_date'] = $this->language->get('text_start_date');
		$data['text_issue'] = $this->language->get('text_issue');
		$data['text_wait'] = $this->language->get('text_wait');

		$data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$data['entry_cc_issue'] = $this->language->get('entry_cc_issue');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['owner'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

		$data['cards'] = array();

		$data['cards'][] = array(
			'text'  => 'Visa',
			'value' => '0'
		);

		$data['cards'][] = array(
			'text'  => 'MasterCard',
			'value' => '1'
		);

		$data['cards'][] = array(
			'text'  => 'Maestro',
			'value' => '9'
		);

		$data['cards'][] = array(
			'text'  => 'Solo',
			'value' => 'S'
		);

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_valid'] = array();

		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {
			$data['year_valid'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_payflow.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/pp_payflow.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/pp_payflow.tpl', $data);
		}
	}

	public function send() {
		$this->language->load('payment/pp_payflow');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (!$this->config->get('pp_payflow_transaction')) {
			$payment_type = 'A';
		} else {
			$payment_type = 'S';
		}

		$request  = 'USER=' . urlencode($this->config->get('pp_payflow_user'));
		$request .= '&VENDOR=' . urlencode($this->config->get('pp_payflow_vendor'));
		$request .= '&PARTNER=' . urlencode($this->config->get('pp_payflow_partner'));
		$request .= '&PWD=' . urlencode($this->config->get('pp_payflow_password'));
		$request .= '&TENDER=C';
		$request .= '&TRXTYPE=' . $payment_type;
		$request .= '&AMT=' . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
		$request .= '&CURRENCY=' . urlencode($order_info['currency_code']);
		$request .= '&NAME=' . urlencode($this->request->post['cc_owner']);
		$request .= '&STREET=' . urlencode($order_info['payment_address_1']);
		$request .= '&CITY=' . urlencode($order_info['payment_city']);
		$request .= '&STATE=' . urlencode(($order_info['payment_iso_code_2'] != 'US') ? $order_info['payment_zone'] : $order_info['payment_zone_code']);
		$request .= '&COUNTRY=' . urlencode($order_info['payment_iso_code_2']);
		$request .= '&ZIP=' . urlencode(str_replace(' ', '', $order_info['payment_postcode']));
		$request .= '&CLIENTIP=' . urlencode($this->request->server['REMOTE_ADDR']);
		$request .= '&EMAIL=' . urlencode($order_info['email']);
		$request .= '&ACCT=' . urlencode(str_replace(' ', '', $this->request->post['cc_number']));
		$request .= '&ACCTTYPE=' . urlencode($this->request->post['cc_type']);
		$request .= '&CARDSTART=' . urlencode($this->request->post['cc_start_date_month'] . substr($this->request->post['cc_start_date_year'], - 2, 2));
		$request .= '&EXPDATE=' . urlencode($this->request->post['cc_expire_date_month'] . substr($this->request->post['cc_expire_date_year'], - 2, 2));
		$request .= '&CVV2=' . urlencode($this->request->post['cc_cvv2']);
		$request .= '&CARDISSUE=' . urlencode($this->request->post['cc_issue']);
		$request .= '&BUTTONSOURCE=' . urlencode('OpenCart_2.0_PFP');

		if (!$this->config->get('pp_payflow_test')) {
			$curl = curl_init('https://payflowpro.paypal.com');
		} else {
			$curl = curl_init('https://pilot-payflowpro.paypal.com');
		}

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-VPS-REQUEST-ID: ' . md5($this->session->data['order_id'] . mt_rand())));

		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$this->log->write('DoDirectPayment failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
		}

		$response_info = array();

		parse_str($response, $response_info);

		$json = array();

		if ($response_info['RESULT'] == '0') {
			$message = '';

			if (isset($response_info['AVSCODE'])) {
				$message .= 'AVSCODE: ' . $response_info['AVSCODE'] . "\n";
			}

			if (isset($response_info['CVV2MATCH'])) {
				$message .= 'CVV2MATCH: ' . $response_info['CVV2MATCH'] . "\n";
			}

			if (isset($response_info['TRANSACTIONID'])) {
				$message .= 'TRANSACTIONID: ' . $response_info['TRANSACTIONID'] . "\n";
			}

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('pp_payflow_order_status_id'), $message, false);

			$json['success'] = $this->url->link('checkout/success');
		} else {
			switch ($response_info['RESULT']) {
				case '1':
				case '26':
					$json['error'] = $this->language->get('error_config');
					break;
				case '7':
					$json['error'] = $this->language->get('error_address');
					break;
				case '12':
					$json['error'] = $this->language->get('error_declined');
					break;
				case '23':
				case '24':
					$json['error'] = $this->language->get('error_invalid');
					break;
				default:
					$json['error'] = $this->language->get('error_general');
					break;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}