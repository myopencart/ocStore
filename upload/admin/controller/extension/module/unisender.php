<?php
/**
 * Модуль интеграции с Unisender http://www.unisender.com/?a=opencart,
 * 
 * Александр Топорков
 * toporchillo@gmail.com
 */
class ControllerModuleUnisender extends Controller {
	private $error = array();
	private $form;
	private $tdata = array();

  	public function subscribtions() {
		$key = $this->request->get['key'];
        $ch = curl_init ('http://api.unisender.com/ru/api/getLists?format=json&api_key='.$key) ;
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        $res = curl_exec ($ch) ;
        curl_close ($ch) ;		
		echo $res;
	}
	
  	public function index() {
		$this->load->language('module/unisender');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

    	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
    		if ($this->validate($this->request->post)) {
				$this->model_setting_setting->editSetting('unisender', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
	
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
    		}
    	}

		$this->tdata['heading_title'] = $this->language->get('heading_title');
      	$this->tdata['breadcrumbs'] = $this->getBreadCrumbs();
      	$this->tdata['button_save'] = $this->language->get('button_save');
      	$this->tdata['button_cancel'] = $this->language->get('button_cancel');
		$this->tdata['text_enabled'] = $this->language->get('text_enabled');
		$this->tdata['text_disabled'] = $this->language->get('text_disabled');
		$this->tdata['token'] = $this->session->data['token'];
		$this->tdata['text_edit'] = $this->language->get('text_edit');
		$this->tdata['text_get_key'] = $this->language->get('text_get_key');
		$this->tdata['text_unselect'] = $this->language->get('text_unselect');
 
		if (isset($this->error['warning'])) {
			$this->tdata['error_warning'] = $this->error['warning'];
		} else {
			$this->tdata['error_warning'] = '';
		}
		$this->tdata['_error'] = $this->error;
		
    	$this->tdata['action'] = $this->url->link('module/unisender', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->tdata['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$defaults = array(
			'unisender_key' => '',
			'unisender_subscribtion' => array(),
			'unisender_status' => '',
			'unisender_ignore' => '',
		);
		foreach ($defaults as $key=>$value) {
			if (isset($this->request->post[$key])) {
				$defautls[$key] = $this->request->post[$key];
			}
			else {
				$defautls[$key] = $this->config->get($key);
			}
			$this->tdata[$key] = $defautls[$key];
			$this->tdata['entry_'.$key] = $this->language->get('entry_'.$key);
			if ($this->language->get('entry_'.$key.'_help')) {
				$this->tdata['entry_'.$key.'_help'] = $this->language->get('entry_'.$key.'_help');
			}
		}

		$template = 'module/unisender.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->tdata['header'] = $this->load->controller('common/header');
		$this->tdata['column_left'] = $this->load->controller('common/column_left');
		$this->tdata['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($template, $this->tdata));				
  	}
	
	public function install() {
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('unisender_subscribe', 'post.customer.add', 'module/unisender/subscribe_customer');
		$this->model_extension_event->addEvent('unisender_update', 'post.customer.edit.newsletter', 'module/unisender/update');
		$this->model_extension_event->addEvent('unisender_guest', 'post.order.add', 'module/unisender/subscribe_guest');
	}

	public function uninstall() {
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('unisender_subscribe');
		$this->model_extension_event->deleteEvent('unisender_update');
		$this->model_extension_event->deleteEvent('unisender_guest');
	}

  	private function validate($post_data) {
		if (!$this->user->hasPermission('modify', 'module/unisender')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$post_data['unisender_key']) {
			$this->error['warning'] = $this->language->get('error_form');
			$this->error['unisender_key'] = $this->language->get('error_empty_field');
		}
		
    	if (!$this->error || !sizeof($this->error)) {
      		return true;
    	} else {
      		return false;
    	}
  	}
	
	private function getBreadCrumbs() {
		$breadcrumbs = array();

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/unisender', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
   		
      	return $breadcrumbs;
	}
}
?>
