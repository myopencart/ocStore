<?php
class ControllerShippingUPS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/ups');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ups', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_next_day_air'] = $this->language->get('text_next_day_air');
		$data['text_2nd_day_air'] = $this->language->get('text_2nd_day_air');
		$data['text_ground'] = $this->language->get('text_ground');
		$data['text_worldwide_express'] = $this->language->get('text_worldwide_express');
		$data['text_worldwide_express_plus'] = $this->language->get('text_worldwide_express_plus');
		$data['text_worldwide_expedited'] = $this->language->get('text_worldwide_expedited');
		$data['text_express'] = $this->language->get('text_express');
		$data['text_standard'] = $this->language->get('text_standard');
		$data['text_3_day_select'] = $this->language->get('text_3_day_select');
		$data['text_next_day_air_saver'] = $this->language->get('text_next_day_air_saver');
		$data['text_next_day_air_early_am'] = $this->language->get('text_next_day_air_early_am');
		$data['text_expedited'] = $this->language->get('text_expedited');
		$data['text_2nd_day_air_am'] = $this->language->get('text_2nd_day_air_am');
		$data['text_saver'] = $this->language->get('text_saver');
		$data['text_express_early_am'] = $this->language->get('text_express_early_am');
		$data['text_express_plus'] = $this->language->get('text_express_plus');
		$data['text_today_standard'] = $this->language->get('text_today_standard');
		$data['text_today_dedicated_courier'] = $this->language->get('text_today_dedicated_courier');
		$data['text_today_intercity'] = $this->language->get('text_today_intercity');
		$data['text_today_express'] = $this->language->get('text_today_express');
		$data['text_today_express_saver'] = $this->language->get('text_today_express_saver');

		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_pickup'] = $this->language->get('entry_pickup');
		$data['entry_packaging'] = $this->language->get('entry_packaging');
		$data['entry_classification'] = $this->language->get('entry_classification');
		$data['entry_origin'] = $this->language->get('entry_origin');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_state'] = $this->language->get('entry_state');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_quote_type'] = $this->language->get('entry_quote_type');
		$data['entry_service'] = $this->language->get('entry_service');
		$data['entry_insurance'] = $this->language->get('entry_insurance');
		$data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_length_code'] = $this->language->get('entry_length_code');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_debug'] = $this->language->get('entry_debug');

		$data['help_key'] = $this->language->get('help_key');
		$data['help_username'] = $this->language->get('help_username');
		$data['help_password'] = $this->language->get('help_password');
		$data['help_pickup'] = $this->language->get('help_pickup');
		$data['help_packaging'] = $this->language->get('help_packaging');
		$data['help_classification'] = $this->language->get('help_classification');
		$data['help_origin'] = $this->language->get('help_origin');
		$data['help_city'] = $this->language->get('help_city');
		$data['help_state'] = $this->language->get('help_state');
		$data['help_country'] = $this->language->get('help_country');
		$data['help_postcode'] = $this->language->get('help_postcode');
		$data['help_test'] = $this->language->get('help_test');
		$data['help_quote_type'] = $this->language->get('help_quote_type');
		$data['help_service'] = $this->language->get('help_service');
		$data['help_insurance'] = $this->language->get('help_insurance');
		$data['help_display_weight'] = $this->language->get('help_display_weight');
		$data['help_weight_class'] = $this->language->get('help_weight_class');
		$data['help_length_class'] = $this->language->get('help_length_class');
		$data['help_dimension'] = $this->language->get('help_dimension');
		$data['help_debug'] = $this->language->get('help_debug');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}

		if (isset($this->error['state'])) {
			$data['error_state'] = $this->error['state'];
		} else {
			$data['error_state'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['dimension'])) {
			$data['error_dimension'] = $this->error['dimension'];
		} else {
			$data['error_dimension'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/ups', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/ups', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['ups_key'])) {
			$data['ups_key'] = $this->request->post['ups_key'];
		} else {
			$data['ups_key'] = $this->config->get('ups_key');
		}

		if (isset($this->request->post['ups_username'])) {
			$data['ups_username'] = $this->request->post['ups_username'];
		} else {
			$data['ups_username'] = $this->config->get('ups_username');
		}

		if (isset($this->request->post['ups_password'])) {
			$data['ups_password'] = $this->request->post['ups_password'];
		} else {
			$data['ups_password'] = $this->config->get('ups_password');
		}

		if (isset($this->request->post['ups_pickup'])) {
			$data['ups_pickup'] = $this->request->post['ups_pickup'];
		} else {
			$data['ups_pickup'] = $this->config->get('ups_pickup');
		}

		$data['pickups'] = array();

		$data['pickups'][] = array(
			'value' => '01',
			'text'  => $this->language->get('text_daily_pickup')
		);

		$data['pickups'][] = array(
			'value' => '03',
			'text'  => $this->language->get('text_customer_counter')
		);

		$data['pickups'][] = array(
			'value' => '06',
			'text'  => $this->language->get('text_one_time_pickup')
		);

		$data['pickups'][] = array(
			'value' => '07',
			'text'  => $this->language->get('text_on_call_air_pickup')
		);

		$data['pickups'][] = array(
			'value' => '19',
			'text'  => $this->language->get('text_letter_center')
		);

		$data['pickups'][] = array(
			'value' => '20',
			'text'  => $this->language->get('text_air_service_center')
		);

		$data['pickups'][] = array(
			'value' => '11',
			'text'  => $this->language->get('text_suggested_retail_rates')
		);

		if (isset($this->request->post['ups_packaging'])) {
			$data['ups_packaging'] = $this->request->post['ups_packaging'];
		} else {
			$data['ups_packaging'] = $this->config->get('ups_packaging');
		}

		$data['packages'] = array();

		$data['packages'][] = array(
			'value' => '02',
			'text'  => $this->language->get('text_package')
		);

		$data['packages'][] = array(
			'value' => '01',
			'text'  => $this->language->get('text_ups_letter')
		);

		$data['packages'][] = array(
			'value' => '03',
			'text'  => $this->language->get('text_ups_tube')
		);

		$data['packages'][] = array(
			'value' => '04',
			'text'  => $this->language->get('text_ups_pak')
		);

		$data['packages'][] = array(
			'value' => '21',
			'text'  => $this->language->get('text_ups_express_box')
		);

		$data['packages'][] = array(
			'value' => '24',
			'text'  => $this->language->get('text_ups_25kg_box')
		);

		$data['packages'][] = array(
			'value' => '25',
			'text'  => $this->language->get('text_ups_10kg_box')
		);

		if (isset($this->request->post['ups_classification'])) {
			$data['ups_classification'] = $this->request->post['ups_classification'];
		} else {
			$data['ups_classification'] = $this->config->get('ups_classification');
		}

		$data['classifications'][] = array(
			'value' => '01',
			'text'  => '01'
		);

		$data['classifications'][] = array(
			'value' => '03',
			'text'  => '03'
		);

		$data['classifications'][] = array(
			'value' => '04',
			'text'  => '04'
		);

		if (isset($this->request->post['ups_origin'])) {
			$data['ups_origin'] = $this->request->post['ups_origin'];
		} else {
			$data['ups_origin'] = $this->config->get('ups_origin');
		}

		$data['origins'] = array();

		$data['origins'][] = array(
			'value' => 'US',
			'text'  => $this->language->get('text_us')
		);

		$data['origins'][] = array(
			'value' => 'CA',
			'text'  => $this->language->get('text_ca')
		);

		$data['origins'][] = array(
			'value' => 'EU',
			'text'  => $this->language->get('text_eu')
		);

		$data['origins'][] = array(
			'value' => 'PR',
			'text'  => $this->language->get('text_pr')
		);

		$data['origins'][] = array(
			'value' => 'MX',
			'text'  => $this->language->get('text_mx')
		);

		$data['origins'][] = array(
			'value' => 'other',
			'text'  => $this->language->get('text_other')
		);

		if (isset($this->request->post['ups_city'])) {
			$data['ups_city'] = $this->request->post['ups_city'];
		} else {
			$data['ups_city'] = $this->config->get('ups_city');
		}

		if (isset($this->request->post['ups_state'])) {
			$data['ups_state'] = $this->request->post['ups_state'];
		} else {
			$data['ups_state'] = $this->config->get('ups_state');
		}

		if (isset($this->request->post['ups_country'])) {
			$data['ups_country'] = $this->request->post['ups_country'];
		} else {
			$data['ups_country'] = $this->config->get('ups_country');
		}

		if (isset($this->request->post['ups_postcode'])) {
			$data['ups_postcode'] = $this->request->post['ups_postcode'];
		} else {
			$data['ups_postcode'] = $this->config->get('ups_postcode');
		}

		if (isset($this->request->post['ups_test'])) {
			$data['ups_test'] = $this->request->post['ups_test'];
		} else {
			$data['ups_test'] = $this->config->get('ups_test');
		}

		if (isset($this->request->post['ups_quote_type'])) {
			$data['ups_quote_type'] = $this->request->post['ups_quote_type'];
		} else {
			$data['ups_quote_type'] = $this->config->get('ups_quote_type');
		}

		$data['quote_types'] = array();

		$data['quote_types'][] = array(
			'value' => 'residential',
			'text'  => $this->language->get('text_residential')
		);

		$data['quote_types'][] = array(
			'value' => 'commercial',
			'text'  => $this->language->get('text_commercial')
		);

		// US
		if (isset($this->request->post['ups_us_01'])) {
			$data['ups_us_01'] = $this->request->post['ups_us_01'];
		} else {
			$data['ups_us_01'] = $this->config->get('ups_us_01');
		}

		if (isset($this->request->post['ups_us_02'])) {
			$data['ups_us_02'] = $this->request->post['ups_us_02'];
		} else {
			$data['ups_us_02'] = $this->config->get('ups_us_02');
		}

		if (isset($this->request->post['ups_us_03'])) {
			$data['ups_us_03'] = $this->request->post['ups_us_03'];
		} else {
			$data['ups_us_03'] = $this->config->get('ups_us_03');
		}

		if (isset($this->request->post['ups_us_07'])) {
			$data['ups_us_07'] = $this->request->post['ups_us_07'];
		} else {
			$data['ups_us_07'] = $this->config->get('ups_us_07');
		}

		if (isset($this->request->post['ups_us_08'])) {
			$data['ups_us_08'] = $this->request->post['ups_us_08'];
		} else {
			$data['ups_us_08'] = $this->config->get('ups_us_08');
		}

		if (isset($this->request->post['ups_us_11'])) {
			$data['ups_us_11'] = $this->request->post['ups_us_11'];
		} else {
			$data['ups_us_11'] = $this->config->get('ups_us_11');
		}

		if (isset($this->request->post['ups_us_12'])) {
			$data['ups_us_12'] = $this->request->post['ups_us_12'];
		} else {
			$data['ups_us_12'] = $this->config->get('ups_us_12');
		}

		if (isset($this->request->post['ups_us_13'])) {
			$data['ups_us_13'] = $this->request->post['ups_us_13'];
		} else {
			$data['ups_us_13'] = $this->config->get('ups_us_13');
		}

		if (isset($this->request->post['ups_us_14'])) {
			$data['ups_us_14'] = $this->request->post['ups_us_14'];
		} else {
			$data['ups_us_14'] = $this->config->get('ups_us_14');
		}

		if (isset($this->request->post['ups_us_54'])) {
			$data['ups_us_54'] = $this->request->post['ups_us_54'];
		} else {
			$data['ups_us_54'] = $this->config->get('ups_us_54');
		}

		if (isset($this->request->post['ups_us_59'])) {
			$data['ups_us_59'] = $this->request->post['ups_us_59'];
		} else {
			$data['ups_us_59'] = $this->config->get('ups_us_59');
		}

		if (isset($this->request->post['ups_us_65'])) {
			$data['ups_us_65'] = $this->request->post['ups_us_65'];
		} else {
			$data['ups_us_65'] = $this->config->get('ups_us_65');
		}

		// Puerto Rico
		if (isset($this->request->post['ups_pr_01'])) {
			$data['ups_pr_01'] = $this->request->post['ups_pr_01'];
		} else {
			$data['ups_pr_01'] = $this->config->get('ups_pr_01');
		}

		if (isset($this->request->post['ups_pr_02'])) {
			$data['ups_pr_02'] = $this->request->post['ups_pr_02'];
		} else {
			$data['ups_pr_02'] = $this->config->get('ups_pr_02');
		}

		if (isset($this->request->post['ups_pr_03'])) {
			$data['ups_pr_03'] = $this->request->post['ups_pr_03'];
		} else {
			$data['ups_pr_03'] = $this->config->get('ups_pr_03');
		}

		if (isset($this->request->post['ups_pr_07'])) {
			$data['ups_pr_07'] = $this->request->post['ups_pr_07'];
		} else {
			$data['ups_pr_07'] = $this->config->get('ups_pr_07');
		}

		if (isset($this->request->post['ups_pr_08'])) {
			$data['ups_pr_08'] = $this->request->post['ups_pr_08'];
		} else {
			$data['ups_pr_08'] = $this->config->get('ups_pr_08');
		}

		if (isset($this->request->post['ups_pr_14'])) {
			$data['ups_pr_14'] = $this->request->post['ups_pr_14'];
		} else {
			$data['ups_pr_14'] = $this->config->get('ups_pr_14');
		}

		if (isset($this->request->post['ups_pr_54'])) {
			$data['ups_pr_54'] = $this->request->post['ups_pr_54'];
		} else {
			$data['ups_pr_54'] = $this->config->get('ups_pr_54');
		}

		if (isset($this->request->post['ups_pr_65'])) {
			$data['ups_pr_65'] = $this->request->post['ups_pr_65'];
		} else {
			$data['ups_pr_65'] = $this->config->get('ups_pr_65');
		}

		// Canada
		if (isset($this->request->post['ups_ca_01'])) {
			$data['ups_ca_01'] = $this->request->post['ups_ca_01'];
		} else {
			$data['ups_ca_01'] = $this->config->get('ups_ca_01');
		}

		if (isset($this->request->post['ups_ca_02'])) {
			$data['ups_ca_02'] = $this->request->post['ups_ca_02'];
		} else {
			$data['ups_ca_02'] = $this->config->get('ups_ca_02');
		}

		if (isset($this->request->post['ups_ca_07'])) {
			$data['ups_ca_07'] = $this->request->post['ups_ca_07'];
		} else {
			$data['ups_ca_07'] = $this->config->get('ups_ca_07');
		}

		if (isset($this->request->post['ups_ca_08'])) {
			$data['ups_ca_08'] = $this->request->post['ups_ca_08'];
		} else {
			$data['ups_ca_08'] = $this->config->get('ups_ca_08');
		}

		if (isset($this->request->post['ups_ca_11'])) {
			$data['ups_ca_11'] = $this->request->post['ups_ca_11'];
		} else {
			$data['ups_ca_11'] = $this->config->get('ups_ca_11');
		}

		if (isset($this->request->post['ups_ca_12'])) {
			$data['ups_ca_12'] = $this->request->post['ups_ca_12'];
		} else {
			$data['ups_ca_12'] = $this->config->get('ups_ca_12');
		}

		if (isset($this->request->post['ups_ca_13'])) {
			$data['ups_ca_13'] = $this->request->post['ups_ca_13'];
		} else {
			$data['ups_ca_13'] = $this->config->get('ups_ca_13');
		}

		if (isset($this->request->post['ups_ca_14'])) {
			$data['ups_ca_14'] = $this->request->post['ups_ca_14'];
		} else {
			$data['ups_ca_14'] = $this->config->get('ups_ca_14');
		}

		if (isset($this->request->post['ups_ca_54'])) {
			$data['ups_ca_54'] = $this->request->post['ups_ca_54'];
		} else {
			$data['ups_ca_54'] = $this->config->get('ups_ca_54');
		}

		if (isset($this->request->post['ups_ca_65'])) {
			$data['ups_ca_65'] = $this->request->post['ups_ca_65'];
		} else {
			$data['ups_ca_65'] = $this->config->get('ups_ca_65');
		}

		// Mexico
		if (isset($this->request->post['ups_mx_07'])) {
			$data['ups_mx_07'] = $this->request->post['ups_mx_07'];
		} else {
			$data['ups_mx_07'] = $this->config->get('ups_mx_07');
		}

		if (isset($this->request->post['ups_mx_08'])) {
			$data['ups_mx_08'] = $this->request->post['ups_mx_08'];
		} else {
			$data['ups_mx_08'] = $this->config->get('ups_mx_08');
		}

		if (isset($this->request->post['ups_mx_54'])) {
			$data['ups_mx_54'] = $this->request->post['ups_mx_54'];
		} else {
			$data['ups_mx_54'] = $this->config->get('ups_mx_54');
		}

		if (isset($this->request->post['ups_mx_65'])) {
			$data['ups_mx_65'] = $this->request->post['ups_mx_65'];
		} else {
			$data['ups_mx_65'] = $this->config->get('ups_mx_65');
		}

		// EU
		if (isset($this->request->post['ups_eu_07'])) {
			$data['ups_eu_07'] = $this->request->post['ups_eu_07'];
		} else {
			$data['ups_eu_07'] = $this->config->get('ups_eu_07');
		}

		if (isset($this->request->post['ups_eu_08'])) {
			$data['ups_eu_08'] = $this->request->post['ups_eu_08'];
		} else {
			$data['ups_eu_08'] = $this->config->get('ups_eu_08');
		}

		if (isset($this->request->post['ups_eu_11'])) {
			$data['ups_eu_11'] = $this->request->post['ups_eu_11'];
		} else {
			$data['ups_eu_11'] = $this->config->get('ups_eu_11');
		}

		if (isset($this->request->post['ups_eu_54'])) {
			$data['ups_eu_54'] = $this->request->post['ups_eu_54'];
		} else {
			$data['ups_eu_54'] = $this->config->get('ups_eu_54');
		}

		if (isset($this->request->post['ups_eu_65'])) {
			$data['ups_eu_65'] = $this->request->post['ups_eu_65'];
		} else {
			$data['ups_eu_65'] = $this->config->get('ups_eu_65');
		}

		if (isset($this->request->post['ups_eu_82'])) {
			$data['ups_eu_82'] = $this->request->post['ups_eu_82'];
		} else {
			$data['ups_eu_82'] = $this->config->get('ups_eu_82');
		}

		if (isset($this->request->post['ups_eu_83'])) {
			$data['ups_eu_83'] = $this->request->post['ups_eu_83'];
		} else {
			$data['ups_eu_83'] = $this->config->get('ups_eu_83');
		}

		if (isset($this->request->post['ups_eu_84'])) {
			$data['ups_eu_84'] = $this->request->post['ups_eu_84'];
		} else {
			$data['ups_eu_84'] = $this->config->get('ups_eu_84');
		}

		if (isset($this->request->post['ups_eu_85'])) {
			$data['ups_eu_85'] = $this->request->post['ups_eu_85'];
		} else {
			$data['ups_eu_85'] = $this->config->get('ups_eu_85');
		}

		if (isset($this->request->post['ups_eu_86'])) {
			$data['ups_eu_86'] = $this->request->post['ups_eu_86'];
		} else {
			$data['ups_eu_86'] = $this->config->get('ups_eu_86');
		}

		// Other
		if (isset($this->request->post['ups_other_07'])) {
			$data['ups_other_07'] = $this->request->post['ups_other_07'];
		} else {
			$data['ups_other_07'] = $this->config->get('ups_other_07');
		}

		if (isset($this->request->post['ups_other_08'])) {
			$data['ups_other_08'] = $this->request->post['ups_other_08'];
		} else {
			$data['ups_other_08'] = $this->config->get('ups_other_08');
		}

		if (isset($this->request->post['ups_other_11'])) {
			$data['ups_other_11'] = $this->request->post['ups_other_11'];
		} else {
			$data['ups_other_11'] = $this->config->get('ups_other_11');
		}

		if (isset($this->request->post['ups_other_54'])) {
			$data['ups_other_54'] = $this->request->post['ups_other_54'];
		} else {
			$data['ups_other_54'] = $this->config->get('ups_other_54');
		}

		if (isset($this->request->post['ups_other_65'])) {
			$data['ups_other_65'] = $this->request->post['ups_other_65'];
		} else {
			$data['ups_other_65'] = $this->config->get('ups_other_65');
		}

		if (isset($this->request->post['ups_display_weight'])) {
			$data['ups_display_weight'] = $this->request->post['ups_display_weight'];
		} else {
			$data['ups_display_weight'] = $this->config->get('ups_display_weight');
		}

		if (isset($this->request->post['ups_insurance'])) {
			$data['ups_insurance'] = $this->request->post['ups_insurance'];
		} else {
			$data['ups_insurance'] = $this->config->get('ups_insurance');
		}

		if (isset($this->request->post['ups_weight_class_id'])) {
			$data['ups_weight_class_id'] = $this->request->post['ups_weight_class_id'];
		} else {
			$data['ups_weight_class_id'] = $this->config->get('ups_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['ups_length_code'])) {
			$data['ups_length_code'] = $this->request->post['ups_length_code'];
		} else {
			$data['ups_length_code'] = $this->config->get('ups_length_code');
		}

		if (isset($this->request->post['ups_length_class_id'])) {
			$data['ups_length_class_id'] = $this->request->post['ups_length_class_id'];
		} else {
			$data['ups_length_class_id'] = $this->config->get('ups_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['ups_length'])) {
			$data['ups_length'] = $this->request->post['ups_length'];
		} else {
			$data['ups_length'] = $this->config->get('ups_length');
		}

		if (isset($this->request->post['ups_width'])) {
			$data['ups_width'] = $this->request->post['ups_width'];
		} else {
			$data['ups_width'] = $this->config->get('ups_width');
		}

		if (isset($this->request->post['ups_height'])) {
			$data['ups_height'] = $this->request->post['ups_height'];
		} else {
			$data['ups_height'] = $this->config->get('ups_height');
		}

		if (isset($this->request->post['ups_tax_class_id'])) {
			$data['ups_tax_class_id'] = $this->request->post['ups_tax_class_id'];
		} else {
			$data['ups_tax_class_id'] = $this->config->get('ups_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['ups_geo_zone_id'])) {
			$data['ups_geo_zone_id'] = $this->request->post['ups_geo_zone_id'];
		} else {
			$data['ups_geo_zone_id'] = $this->config->get('ups_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['ups_status'])) {
			$data['ups_status'] = $this->request->post['ups_status'];
		} else {
			$data['ups_status'] = $this->config->get('ups_status');
		}

		if (isset($this->request->post['ups_sort_order'])) {
			$data['ups_sort_order'] = $this->request->post['ups_sort_order'];
		} else {
			$data['ups_sort_order'] = $this->config->get('ups_sort_order');
		}

		if (isset($this->request->post['ups_debug'])) {
			$data['ups_debug'] = $this->request->post['ups_debug'];
		} else {
			$data['ups_debug'] = $this->config->get('ups_debug');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/ups.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/ups')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['ups_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['ups_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['ups_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['ups_city']) {
			$this->error['city'] = $this->language->get('error_city');
		}

		if (!$this->request->post['ups_state']) {
			$this->error['state'] = $this->language->get('error_state');
		}

		if (!$this->request->post['ups_country']) {
			$this->error['country'] = $this->language->get('error_country');
		}

		if (empty($this->request->post['ups_length'])) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}

		if (empty($this->request->post['ups_width'])) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}

		if (empty($this->request->post['ups_height'])) {
			$this->error['dimension'] = $this->language->get('error_dimension');
		}

		return !$this->error;
	}
}