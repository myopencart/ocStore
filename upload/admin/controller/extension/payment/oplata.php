<?php 
class ControllerExtensionPaymentOplata extends Controller
{
	private $error = array(); 

	public function index() {
		
		$this->load->language('extension/payment/oplata');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		foreach ($languages as $language) {
			if (isset($this->error['bank' . $language['language_id']])) {
				$data['error_bank' . $language['language_id']] = $this->error['bank' . $language['language_id']];
			} else {
				$data['error_bank' . $language['language_id']] = '';
			}
		}

//------------------------------------------------------------

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('oplata', $this->request->post);				
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}
		
		$arr = array( 
				"heading_title", "text_payment", "text_success", "text_pay", "text_card", 
				"entry_merchant", "entry_styles" , "entry_secretkey", "entry_order_status",
				"entry_currency", "entry_backref", "entry_server_back", "entry_language", "entry_status",
				"entry_sort_order", "error_permission", "error_merchant", "error_secretkey", 'text_edit',"entry_help_lang");

		foreach ($arr as $v)
            $data[$v] = $this->language->get($v);
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_order_process_status'] = $this->language->get('entry_order_process_status');

		#$data['LUURL'] = "index.php?route=payment/oplata/callback";


//------------------------------------------------------------
        $arr = array("warning", "merchant", "secretkey", "type");
        foreach ( $arr as $v )
            $data['error_'.$v] = ( isset($this->error[$v]) ) ? $this->error[$v] : "";
//------------------------------------------------------------

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL')
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/oplata', 'token=' . $this->session->data['token'], 'SSL')
   		);
				
		$data['action'] = $this->url->link('extension/payment/oplata', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL');

//------------------------------------------------------------
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['oplata_currencyc']= array('','EUR','USD','GBP','RUB','UAH');
		$arr = array( "oplata_merchant", "oplata_secretkey", "oplata_backref", "oplata_server_back",
            "oplata_language", "oplata_status", "oplata_sort_order", "oplata_order_status_id", "oplata_order_process_status_id", "oplata_currency", "oplata_styles");

		
		

		foreach ( $arr as $v )
		{
			$data[$v] = ( isset($this->request->post[$v]) ) ? $this->request->post[$v] : $this->config->get($v);
		}


//------------------------------------------------------------

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('extension/payment/oplata.tpl', $data));
	}

//------------------------------------------------------------
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/oplata')) {
			
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['oplata_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['oplata_secretkey']) {
			$this->error['secretkey'] = $this->language->get('error_secretkey');
		}
		//print_R (1);die;
		return (!$this->error) ? true : false ;
	}
}
?>