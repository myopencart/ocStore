<?php
class ControllerExtensionPaymentQiwiRest extends Controller {
	public function index() {
	    $data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_back'] = $this->language->get('button_back');

		$data['continue'] = $this->url->link('checkout/success');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['action'] = 'https://qiwi.com/order/external/main.action';

		$this->load->model('checkout/order');

		$order_id = isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 0;
		$order_info = $this->model_checkout_order->getOrder($order_id);


		$this->load->language('extension/payment/qiwi_rest');


		$data['qiwi_error'] = $this->language->get('qiwi_error');
		$data['qiwi_error_5'] = $this->language->get('qiwi_error_5');
		$data['qiwi_error_13'] = $this->language->get('qiwi_error_13');
		$data['qiwi_error_78'] = $this->language->get('qiwi_error_78');
		$data['qiwi_error_150'] = $this->language->get('qiwi_error_150');
		$data['qiwi_error_152'] = $this->language->get('qiwi_error_152');
		$data['qiwi_error_210'] = $this->language->get('qiwi_error_210');
		$data['qiwi_error_215'] = $this->language->get('qiwi_error_215');
		$data['qiwi_error_220'] = $this->language->get('qiwi_error_220');
		$data['qiwi_error_241'] = $this->language->get('qiwi_error_241');
		$data['qiwi_error_242'] = $this->language->get('qiwi_error_242');
		$data['qiwi_error_298'] = $this->language->get('qiwi_error_298');
		$data['qiwi_error_300'] = $this->language->get('qiwi_error_300');
		$data['qiwi_error_303'] = $this->language->get('qiwi_error_303');
		$data['qiwi_error_316'] = $this->language->get('qiwi_error_316');
		$data['qiwi_error_319'] = $this->language->get('qiwi_error_319');
		$data['qiwi_error_341'] = $this->language->get('qiwi_error_341');
		$data['qiwi_error_700'] = $this->language->get('qiwi_error_700');
		$data['qiwi_error_702'] = $this->language->get('qiwi_error_702');
		$data['qiwi_error_1001'] = $this->language->get('qiwi_error_1001');
		$data['qiwi_error_1003'] = $this->language->get('qiwi_error_1003');
		$data['qiwi_error_1019'] = $this->language->get('qiwi_error_1019');

		$data['qiwi_continue'] = $this->language->get('qiwi_continue');


		//$data['sub_text_info'] = $this->language->get('sub_text_info');
		$data['sub_text_info_phone'] = $this->language->get('sub_text_info_phone');

		$data['qiwi_rest_limit'] = $this->language->get('qiwi_rest_limit');
		$data['button_back'] = $this->language->get('button_back');


		// Переменные
		$data['shop'] = $this->config->get('qiwi_rest_shop_id');
		$data['transaction'] = $order_id;

		$data['successUrl'] = $this->url->link('extension/payment/qiwi_rest/success', '', 'SSL'); 
		$data['failUrl'] =  $this->url->link('extension/payment/qiwi_rest/fail', '', 'SSL');

		$data['qiwi_rest_show_pay_now'] = $this->config->get('qiwi_rest_show_pay_now');

		$this->load->model('account/customer');

		$qiwi_rest_markup = $this->config->get('qiwi_rest_markup');
		if ($this->customer->isLogged())
		{
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$customer_group_id = $customer_info['customer_group_id']; 

			foreach($this->config->get('qiwi_rest_group_markup') as $group_id => $group_markup)
			{
				if ($group_id == $customer_group_id) 
					if ($group_markup!="") $qiwi_rest_markup = $group_markup;
			}
		}

		
		$ccy_code = $this->config->get('qiwi_rest_ccy_select');
		$ccy_order_total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $ccy_code);
		$data['summ'] = $this->currency->format($ccy_order_total, $ccy_code, $order_info['currency_value'], FALSE);
		$data['summ'] = round((float)$data['summ'] + (float)$data['summ']/100*(float)$qiwi_rest_markup, 2);
		$data['ccy'] = $ccy_code;


		$data['phone'] = preg_replace("/\D+/", "", $order_info['telephone']);
		//if (strlen ($data['phone']) > 10) $data['phone'] = substr($data['phone'], -10);

		$text_title = '<b>'.$this->language->get('text_title').'</b>';
		if ($this->customer->isLogged())
		{
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$customer_group_id = $customer_info['customer_group_id']; 

			foreach($this->config->get('qiwi_rest_group_desc') as $group_id => $group_desc)
			{
				if ($group_id == $customer_group_id) 
					if ($group_desc!="") $text_title = '<b>'.$group_desc.'</b>';
			}
		}

		$data['markup'] = sprintf ($this->language->get('markup'), $text_title, $qiwi_rest_markup).$data['summ'].' '.$this->config->get('qiwi_rest_ccy_select'); 

		return $this->load->view('extension/payment/qiwi_rest', $data);
	}


	public function confirm() {
		if ($this->session->data['payment_method']['code'] != 'qiwi_rest') return;

		$this->response->addHeader('Content-Type: application/json');

		$json = array();

		$this->load->language('extension/payment/qiwi_rest');

		$this->load->model('checkout/order');

		$order_id = $this->session->data['order_id'];

		$order_info = $this->model_checkout_order->getOrder($order_id);
		if(!$order_info)
		{
			$this->log->write('qiwi_rest confirm no order');

			$json['error'] = 210;
			$this->response->setOutput(json_encode($json));
			return;
		}

		$this->load->model('account/customer');

		$qiwi_rest_markup = $this->config->get('qiwi_rest_markup');
		if ($this->customer->isLogged())
		{
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$customer_group_id = $customer_info['customer_group_id']; 

			foreach($this->config->get('qiwi_rest_group_markup') as $group_id => $group_markup)
			{
				if ($group_id == $customer_group_id) 
					$qiwi_rest_markup = $group_markup;
			}
		}


		$ccy_code = $this->config->get('qiwi_rest_ccy_select');
		$ccy_order_total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $ccy_code);
		$summ = $this->currency->format($ccy_order_total, $ccy_code, $order_info['currency_value'], FALSE);
		$summ = round((float)$summ + (float)$summ/100*(float)$qiwi_rest_markup, 2);

		$objDateTime = new DateTime('NOW');
		$qiwi_rest_lifetime = $this->config->get('qiwi_rest_lifetime');

		if (!isset($qiwi_rest_lifetime)) $qiwi_rest_lifetime = '24';
		if ($qiwi_rest_lifetime=="") $qiwi_rest_lifetime = '24';

		$objDateTime->modify('+'.$qiwi_rest_lifetime.' hour');

		//$objDateTime->add(new DateInterval('PT'.$this->config->get('qiwi_rest_lifetime').'H'));		
		$qiwi_rest_order_end_time = $objDateTime->format(DateTime::ISO8601);

		$name = '';
		$name = $this->config->get('config_store');
		if ($name=='') $name = $this->config->get('config_name');
		if ($name=='') $name = 'qiwi';
		$name=htmlspecialchars_decode($name);

		if (mb_strlen($name,'UTF-8')>99)
			$name = mb_substr($name, 0, 90, 'UTF-8').'...';


		$products = $this->cart->getProducts();
		$_prods = $this->language->get('order_id') . $order_id;
		foreach ($products as $product) 
		{ 
			if ($_prods == "")
				$_prods = $_prods . $product['name'] . " - " . $product['quantity'] . $this->language->get('qiwi_pcs');
			else
				$_prods = $_prods . ', ' . $product['name'] . " - " . $product['quantity'] . $this->language->get('qiwi_pcs');

		} 
		$_prods = " [ " . $_prods . " ] ";

		if (mb_strlen($_prods,'UTF-8')>200) $_prods = mb_substr($_prods, 0, 200, 'UTF-8').'...';
		$comment = addslashes(html_entity_decode($_prods, ENT_QUOTES, 'UTF-8'));

		if (mb_strlen($comment,'UTF-8')>250)
			$comment = mb_substr($comment, 0, 250, 'UTF-8').'...';

		$data = array( 
			"user" => "tel:+" . $this->request->post['qiwi_phone'], 
			"amount" => $summ, 
			"ccy" => $ccy_code, 
			"comment" => $comment, 
			"lifetime" => $qiwi_rest_order_end_time, 
			"pay_source" => "qw", 
			"prv_name" => $name
		); 


		if ($this->config->get('qiwi_rest_mode_select')=='qiwi_rest_mode_debug')
		{
			$this->log->write('qiwi_rest _order ' . print_r($data, true));
		}


		$service_url = 'https://qiwi.com/api/v2/prv/' . $this->config->get('qiwi_rest_shop_id') . '/bills/' . $order_id;
		$ch = curl_init($service_url);

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 

    		curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('qiwi_rest_id') . ":" . $this->config->get('qiwi_rest_password'));
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	    	curl_setopt($ch, CURLOPT_HTTPHEADER, array (
		    "Accept: application/json"
    		));


		$curl_response = curl_exec($ch);
		if ($curl_response === false) {
		    	$info = curl_error($ch);
		    	curl_close($ch);

		    	$this->log->write('qiwi_rest error ' .  $info);
			$json['error'] = 0;
			$this->response->setOutput(json_encode($json));
		    	return; 
		}
		curl_close($ch);
		$decoded = json_decode($curl_response);

		if ($this->config->get('qiwi_rest_mode_select')=='qiwi_rest_mode_debug')
		{
			$this->log->write('qiwi_rest order ' . ' ' . print_r($decoded->response, true));
		}

		if ($decoded->response->result_code != 0) {
			//$this->log->write('qiwi_rest ' . 'error occured: ' . $decoded->response->errormessage);

			$json['error'] = $decoded->response->result_code;
			$this->response->setOutput(json_encode($json));
			return;
		}

		//var_export($decoded->response);


		if( (int)$order_info['order_status_id'] == 0) {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('qiwi_rest_order_status_progress_id'), 'qiwi_rest');
			//return;
		}

		if( $order_info['order_status_id'] != $this->config->get('qiwi_rest_order_status_progress_id')) {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('qiwi_rest_order_status_progress_id'),'qiwi_rest',TRUE);
		}

		$json['error'] = 'no_error';
		$this->response->setOutput(json_encode($json));

   	}


	public function fail() {

		$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));

	}

	public function success() {

		$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));

	}


	private function sendAnswer($error) {
		$x = '<?xml version="1.0"?>';
		$x .= '<result>';
		$x .= '<result_code>'.$error.'</result_code>';
		$x .= '</result>';

		header ("HTTP/1.1 200 OK");
		header ("Content-Type: text/xml" , false); 
		echo($x);

		//$this->log->write('qiwi_rest order5 ' . ' ' .$x);
	}


	private function cidr_match($ip, $cidr)
	{
	    	list($subnet, $mask) = explode('/', $cidr);
	
	    	if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet))
	    	{ 
	      	  return true;
	    	}
	
	    	return false;
	}

	public function callback() {

		if ($this->config->get('qiwi_rest_mode_select')=='qiwi_rest_mode_debug')
		{
			$this->log->write('qiwi_rest callback order ' . ' ' .http_build_query($this->request->post));
		}


		$in = $this->cidr_match($_SERVER["REMOTE_ADDR"], "91.232.230.0/23");
		if ($in == false) $in = $this->cidr_match($_SERVER["REMOTE_ADDR"], "79.142.16.0/20");

		if ($in == false)
		{
		//	$this->log->write('qiwi_rest attack ' . $_SERVER["REMOTE_ADDR"]);
		//	$this->sendAnswer(5);
		//	exit;
		}

		if (isset($this->request->post['command'])) {
			$command = $this->request->post['command'];
		} else {
			$this->sendAnswer(5);
			exit;
		}

		if ($command != 'bill')
		{
			$this->sendAnswer(5);
			exit;
		}

		if (isset($this->request->post['bill_id'])) {
			$bill_id = $this->request->post['bill_id'];
		} else {
			$this->sendAnswer(5);
			exit;
		}

		if (isset($this->request->post['status'])) {
			$status = $this->request->post['status'];
		} else {
			$this->sendAnswer(5);
			exit;
		}

		if (isset($this->request->post['error'])) {
			$error = $this->request->post['error'];
		} else {
			$this->sendAnswer(5);
			exit;
		}

		if (isset($this->request->post['amount'])) {
			$amount = $this->request->post['amount'];
		} else {
			$this->sendAnswer(5);
			exit;
		}

		// for test
		if (strstr($bill_id, "_TEST_")!=false)
		{
			$this->sendAnswer(0);
			exit;
		}



		$service_url = 'https://qiwi.com/api/v2/prv/' . $this->config->get('qiwi_rest_shop_id') . '/bills/' . $bill_id;
		$ch = curl_init($service_url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
    		curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('qiwi_rest_id') . ":" . $this->config->get('qiwi_rest_password'));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    	curl_setopt($ch, CURLOPT_HTTPHEADER, array (
		    "Accept: text/json"
    		));


		$curl_response = curl_exec($ch);
		if ($curl_response === false) {
		    	$info = curl_error($ch);
		    	curl_close($ch);
		    	$this->log->write('qiwi_rest get error ' .  $info);
		    	$this->sendAnswer(5);
		    	return ; 
		}
		curl_close($ch);
		$decoded = json_decode($curl_response);

		if ($decoded->response->result_code != 0) {
		    	$this->log->write('qiwi_rest get error code ' . $decoded->response->result_code);
		    	$this->sendAnswer(5);
			return;
		}

		$bill_amount = $decoded->response->bill->amount;
		$bill_status = $decoded->response->bill->status;

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($bill_id);
		if(!$order_info) 
		{
			$this->sendAnswer(210);
			exit;
		}


		// Статус проведения счета.
		if( $status == 'paid' ) {

			if ($amount != $bill_amount)
			{
			    	$this->log->write('qiwi_rest bill_amount error - ' . $amount, ':' . $bill_amount );
			    	$this->sendAnswer(5);
				return;
			}
	
			if ($status != $bill_status)
			{
			    	$this->log->write('qiwi_rest bill_status error - ' . $status, ':' . $bill_status);
			    	$this->sendAnswer(5);
				return;
			}

			if( $order_info['order_status_id'] == 0) {
				$this->model_checkout_order->addOrderHistory($bill_id, $this->config->get('qiwi_rest_order_status_id'), 'qiwi_rest');
				return $param;
			}

			if( $order_info['order_status_id'] != $this->config->get('qiwi_rest_order_status_id')) {
				$this->model_checkout_order->addOrderHistory($bill_id, $this->config->get('qiwi_rest_order_status_id'),'qiwi_rest',TRUE);
			}
		} elseif( $status == 'waiting') {

			if( $order_info['order_status_id'] == 0) {
				$this->model_checkout_order->addOrderHistory($bill_id, $this->config->get('qiwi_rest_order_status_progress_id'), 'qiwi_rest');
				return $param;
			}

			if( $order_info['order_status_id'] != $this->config->get('qiwi_rest_order_status_progress_id')) {
				$this->model_checkout_order->addOrderHistory($bill_id, $this->config->get('qiwi_rest_order_status_progress_id'),'qiwi_rest',TRUE);
			}

		} else {

			if( $order_info['order_status_id'] == 0) {
				$this->model_checkout_order->addOrderHistory($bill_id, $this->config->get('qiwi_rest_order_status_cancel_id'), 'qiwi_rest');
				return $param;
			}

			if( $order_info['order_status_id'] != $this->config->get('qiwi_rest_order_status_cancel_id')) {
				$this->model_checkout_order->addOrderHistory($bill_id, $this->config->get('qiwi_rest_order_status_cancel_id'),'qiwi_rest',TRUE);
			}

		}


		$this->sendAnswer(0);

	}


}
