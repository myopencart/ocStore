<?php
	class ControllerExtensionPaymentOplata extends Controller {
		
		protected $RESPONCE_SUCCESS='success';
		protected $RESPONCE_FAIL = 'failure';
		protected $ORDER_SEPARATOR = '_';
		protected $SIGNATURE_SEPARATOR = '|';
		protected $ORDER_APPROVED = 'approved';
		protected $ORDER_DECLINED = 'declined';
		
		public function index() {
			$this->language->load('extension/payment/oplata');
			$order_id = $this->session->data['order_id'];
			$this->load->model('checkout/order');
			$products=$this->cart->getProducts();
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			//print_r($order_info);
			$backref = $this->url->link('extension/payment/oplata/response');
			$callback = $this->url->link('extension/payment/oplata/callback');
			$desc='';
			foreach ($products as $product){
				if (!next($products)) {
					$desc.=$product['name'];
				}
				else {
					$desc.=$product['name'].', ';
				}
				
			}
			if (($this->config->get('oplata_currency'))) {
				$oplata_currency = $this->config->get('oplata_currency');
				}else {
				$oplata_currency =  $this->session->data['currency'];
			}
			
			$oplata_args = array('order_id' => $order_id . $this->ORDER_SEPARATOR . time(),
            'merchant_id' => $this->config->get('oplata_merchant'),
            'order_desc' => $desc,
            'amount' => $order_info['total'],
            'currency' => $oplata_currency,
            'response_url' => $backref,
            'server_callback_url' => $callback,
            'lang' => $this->config->get('oplata_language'),
            'sender_email' => $order_info['email'],
            'reservation_data' => 'eyJwYXJ0bmVyX2lkIjoiNzIwNCJ9'
			);
			
			$oplata_args['signature'] = $this->getSignature($oplata_args, $this->config->get('oplata_secretkey'));
			$data['oplata_args'] = $oplata_args;
			$data['styles'] = $this->config->get('oplata_styles');
			$data['button_confirm'] = $this->language->get('button_confirm');

			return $this->load->view('/extension/payment/oplata', $data);
		}
		
		public function confirm() {
			
			$this->load->model('checkout/order');
			
			$order_id = $this->session->data['order_id'];
			
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('oplata_order_process_status_id'), $comment = '', $notify = false, $override = false);
			
			$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		}
		
		public function response() {
			$this->language->load('extension/payment/oplata');
			
			$options = array(
            'merchant' => $this->config->get('oplata_merchant'),
            'secretkey' => $this->config->get('oplata_secretkey')
			);
			
			$paymentInfo = $this->isPaymentValid($options, $this->request->post);
			
			if ($paymentInfo === true && $this->request->post['order_status'] != $this->ORDER_DECLINED) {
				
				list($order_id,) = explode($this->ORDER_SEPARATOR, $this->request->post['order_id']);
				$this->load->model('checkout/order');
				$value = serialize($this->request->post);
				if ($this->request->post['order_status'] == $this->ORDER_APPROVED) {
					$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
					}else{
					$this->session->data ['oplata_error'] = $this->language->get('error_oplata').' '. $this->request->post['response_description'].'. '.$this->language->get('error_kod'). $this->request->post['response_code'] ;
					$this->response->redirect($this->url->link('checkout/failure'));
				}
				
				} else {
				if ($this->request->post['order_status'] == $this->ORDER_DECLINED) {
					$this->session->data ['oplata_error'] = $this->language->get('error_oplata').' '. $this->request->post['response_description'].'. '.$this->language->get('error_kod'). $this->request->post['response_code'] ;
					$this->response->redirect($this->url->link('checkout/failure'));
				}
				$this->session->data ['oplata_error'] = $this->language->get('error_oplata').' '. $this->request->post['response_description'].'. '.$this->language->get('error_kod'). $this->request->post['response_code'] ;
				$this->response->redirect($this->url->link('checkout/failure'));
			}
		}
		
		public function callback() {
			
			$fap = json_decode(file_get_contents("php://input"));
			$this->request->post=array();
			foreach($fap as $key=>$val)
			{
				$this->request->post[$key] =  $val ;
			}
			
			$this->language->load('extension/payment/oplata');
			
			$options = array(
            'merchant' => $this->config->get('oplata_merchant'),
            'secretkey' => $this->config->get('oplata_secretkey')
			);
			
			$paymentInfo = $this->isPaymentValid($options, $this->request->post);
			
			if ($paymentInfo === true && $this->request->post['order_status'] != $this->ORDER_DECLINED) {
				list($order_id,) = explode($this->ORDER_SEPARATOR, $this->request->post['order_id']);
				
				$this->load->model('checkout/order');
				$value=serialize($this->request->post);
				if ($this->request->post['order_status'] == $this->ORDER_APPROVED) {
					$order_info = $this->model_checkout_order->getOrder($order_id);
					$comment = "Fondy payment id : " . $this->request->post['payment_id'];
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('oplata_order_status_id'), $comment , $notify = false, $override = false);
					echo 'done';
					}else{
					$order_info = $this->model_checkout_order->getOrder($order_id);
					$comment = "Fondy payment id : " . $this->request->post['payment_id'] . $paymentInfo;
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('oplata_order_process_status_id'), $comment , $notify = false, $override = false);	
					echo $paymentInfo;
					
				}
			} 
		}
		
		public function isPaymentValid($oplataSettings, $response){
			$this->language->load('extension/payment/oplata');
			if ($oplataSettings['merchant'] != $response['merchant_id']) {
				return $this->language->get('error_merchant');
			}
			
			$responseSignature = $response['signature'];
			if (isset($response['response_signature_string'])){
				unset($response['response_signature_string']);
			}
			if (isset($response['signature'])){
				unset($response['signature']);
			}
			if (self::getSignature($response, $oplataSettings['secretkey']) != $responseSignature) {
				return $this->language->get('error_signature');
			}
			return true;
		}
		
		public function getSignature($data, $password, $encoded = true){
			$data = array_filter($data, function($var) {
				return $var !== '' && $var !== null;
			});
			ksort($data);
			
			$str = $password;
			foreach ($data as $k => $v) {
				$str .= $this->SIGNATURE_SEPARATOR . $v;
			}
			
			if ($encoded) {
				return sha1($str);
				} else {
				return $str;
			}
		}
		
	}
?>