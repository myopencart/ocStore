<?php  
class ControllerModuleInformation extends Controller {
	protected function index() {
		$this->language->load('module/information');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_contact'] = $this->language->get('text_contact');
    	$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		
		$this->load->model('catalog/information');
		
		$this->data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
	      		if ($result['sort_order'] != '-1') {
				$this->data['informations'][] = array(
	        			'title' => $result['title'],
		    			'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
	      			);
	    		}
		}

		$this->data['contact'] = $this->url->link('information/contact');
    	$this->data['sitemap'] = $this->url->link('information/sitemap');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/information.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/information.tpl';
		} else {
			$this->template = 'default/template/module/information.tpl';
		}
		
		$this->render();
	}
}
?>
