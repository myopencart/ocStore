<?php
class ControllerExtensionModuleGoogleHangouts extends Controller {
	public function index() {
		$this->load->language('extension/module/google_hangouts');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['code'] = str_replace('http', 'https', html_entity_decode($this->config->get('google_hangouts_code')));
		} else {
			$data['code'] = html_entity_decode($this->config->get('google_hangouts_code'));
		}

		return $this->load->view('extension/module/google_hangouts', $data);
	}
}