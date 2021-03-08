<?php
class ControllerExtensionCurrencyCbr extends Controller {
    private $error = array();

    public function index() {
		$this->load->language('extension/currency/cbr');

		$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('currency_cbr', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency'));
		}

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/currency/cbr', 'token=' . $this->session->data['token'])
		];

		$data['action'] = $this->url->link('extension/currency/cbr', 'token=' . $this->session->data['token']);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=currency');

		if (isset($this->request->post['currency_cbr_status'])) {
			$data['currency_cbr_status'] = $this->request->post['currency_cbr_status'];
		} else {
			$data['currency_cbr_status'] = $this->config->get('currency_cbr_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/currency/cbr', $data));
	}

    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/currency/cbr')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

		return !$this->error;
	}

	public function currency($default = '') {
		if ($this->config->get('currency_cbr_status')) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			curl_close($curl);


			if ($response) {
				$dom = new \DOMDocument('1.0', 'UTF-8');
				$dom->loadXml($response);

                $currencies = array();

                $currencies['RUB'] = 1.0000;

                $root = $dom->documentElement;
                $items = $root->getElementsByTagName('Valute');


                foreach ($items as $item)
                {
                    $code = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
                    $curs = str_replace(',', '.', $item->getElementsByTagName('Value')->item(0)->nodeValue);
                    $nominal = $item->getElementsByTagName('Nominal')->item(0)->nodeValue;
                    $currencies[$code] = floatval($curs/$nominal);
                }


				if ($currencies) {
					$this->load->model('localisation/currency');

					$results = $this->model_localisation_currency->getCurrencies();

					foreach ($results as $result) {
						if (isset($currencies[$result['code']])) {
							$from = $currencies['RUB'];

							$to = $currencies[$result['code']];

							$this->model_localisation_currency->editValueByCode($result['code'], ($currencies[$default] * ($from / $to)));
						}
					}
				}

				$this->model_localisation_currency->editValueByCode($default, '1.00000');

				$this->cache->delete('currency');
			}
		}
	}
}
