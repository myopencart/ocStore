<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$config_langdata = $this->config->get('config_langdata')[$this->config->get('config_language_id')];
		$this->document->setTitle($config_langdata['meta_title']);
		$this->document->setDescription($config_langdata['meta_description']);
		$this->document->setKeywords($config_langdata['meta_keyword']);

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
