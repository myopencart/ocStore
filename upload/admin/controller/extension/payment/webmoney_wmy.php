<?php
class ControllerExtensionPaymentWebmoneyWMY extends Controller {
	private $error = array();
  const MAX_LAST_LOG_LINES = 500;
  const FILE_NAME_LOG = 'webmoney_wmy.log';
	
	public function index() {
		$this->load->language('extension/payment/webmoney_wmy');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('webmoney_wmy', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_clear'] = $this->language->get('button_clear');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_log'] = $this->language->get('tab_log');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['entry_merch_r'] = $this->language->get('entry_merch_r');
		$data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$data['entry_secret_key_x20'] = $this->language->get('entry_secret_key_x20');
		$data['entry_result_url'] = $this->language->get('entry_result_url');
		$data['entry_success_url'] = $this->language->get('entry_success_url');
		$data['entry_fail_url'] = $this->language->get('entry_fail_url');
		
		$data['entry_order_confirm_status'] = $this->language->get('entry_order_confirm_status');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_fail_status'] = $this->language->get('entry_order_fail_status');
		$data['entry_hide_mode'] = $this->language->get('entry_hide_mode');
		$data['entry_minimal_order'] = $this->language->get('entry_minimal_order');
		$data['entry_maximal_order'] = $this->language->get('entry_maximal_order');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['entry_log'] = $this->language->get('entry_log');
		$data['entry_log_file'] = $this->language->get('entry_log_file');
		
		$data['help_merch_r'] = $this->language->get('help_merch_r');
		$data['help_order_confirm_status'] = $this->language->get('help_order_confirm_status');
		$data['help_order_status'] = $this->language->get('help_order_status');
		$data['help_order_fail_status'] = $this->language->get('help_order_fail_status');
		$data['help_hide_mode'] = $this->language->get('help_hide_mode');
		$data['help_minimal_order'] = $this->language->get('help_minimal_order');
		$data['help_maximal_order'] = $this->language->get('help_maximal_order');
		$data['help_log_file'] = sprintf($this->language->get('help_log_file'), self::MAX_LAST_LOG_LINES);
		$data['help_log'] = sprintf($this->language->get('help_log'), self::FILE_NAME_LOG);
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['merch_r'])) {
			$data['error_merch_r'] = $this->error['merch_r'];
		} else {
			$data['error_merch_r'] = '';
		}
		
		if (isset($this->error['secret_key'])) {
			$data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$data['error_secret_key'] = '';
		}

		if (isset($this->error['secret_key_x20'])) {
			$data['error_secret_key_x20'] = $this->error['secret_key_x20'];
		} else {
			$data['error_secret_key_x20'] = '';
		}
		
   	$data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/webmoney_wmy', 'token=' . $this->session->data['token'], 'SSL')
   	);
				
		$data['action'] = $this->url->link('extension/payment/webmoney_wmy', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL');
		$data['clear_log'] = str_replace('&amp;', '&', $this->url->link('extension/payment/webmoney_wmy/clearLog', 'token=' . $this->session->data['token'], 'SSL'));
		$data['log_lines'] = $this->readLastLines(DIR_LOGS . self::FILE_NAME_LOG, self::MAX_LAST_LOG_LINES);
		$data['log_filename'] = self::FILE_NAME_LOG;
		
		$data['logs'] = array(
			'0' => $this->language->get('text_log_off'),
			'1' => $this->language->get('text_log_short'),
			'2' => $this->language->get('text_log_full')
		);

		// Номер магазина
		if (isset($this->request->post['webmoney_wmy_merch_r'])) {
			$data['webmoney_wmy_merch_r'] = $this->request->post['webmoney_wmy_merch_r'];
		} else {
			$data['webmoney_wmy_merch_r'] = $this->config->get('webmoney_wmy_merch_r');
		}
		
		// zp_merhant_key
		if (isset($this->request->post['webmoney_wmy_secret_key'])) {
			$data['webmoney_wmy_secret_key'] = $this->request->post['webmoney_wmy_secret_key'];
		} else {
			$data['webmoney_wmy_secret_key'] = $this->config->get('webmoney_wmy_secret_key');
		}

		// zp_merhant_key X20
		if (isset($this->request->post['webmoney_wmy_secret_key_x20'])) {
			$data['webmoney_wmy_secret_key_x20'] = $this->request->post['webmoney_wmy_secret_key_x20'];
		} else {
			$data['webmoney_wmy_secret_key_x20'] = $this->config->get('webmoney_wmy_secret_key_x20');
		}
		
		
		// URL
		$server = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG;

		$data['webmoney_wmy_result_url'] 		= $server . 'index.php?route=extension/payment/webmoney_wmy/callback';
		$data['webmoney_wmy_success_url'] 	= $server . 'index.php?route=extension/payment/webmoney_wmy/success';
		$data['webmoney_wmy_fail_url'] 			= $server . 'index.php?route=extension/payment/webmoney_wmy/fail';
		
		
		if (isset($this->request->post['webmoney_wmy_order_confirm_status_id'])) {
			$data['webmoney_wmy_order_confirm_status_id'] = $this->request->post['webmoney_wmy_order_confirm_status_id'];
		} else {
			$data['webmoney_wmy_order_confirm_status_id'] = $this->config->get('webmoney_wmy_order_confirm_status_id'); 
		}

		if (isset($this->request->post['webmoney_wmy_order_status_id'])) {
			$data['webmoney_wmy_order_status_id'] = $this->request->post['webmoney_wmy_order_status_id'];
		} else {
			$data['webmoney_wmy_order_status_id'] = $this->config->get('webmoney_wmy_order_status_id'); 
		}

		if (isset($this->request->post['webmoney_wmy_order_fail_status_id'])) {
			$data['webmoney_wmy_order_fail_status_id'] = $this->request->post['webmoney_wmy_order_fail_status_id'];
		} else {
			$data['webmoney_wmy_order_fail_status_id'] = $this->config->get('webmoney_wmy_order_fail_status_id'); 
		}

		if (isset($this->request->post['webmoney_wmy_hide_mode'])) {
			$data['webmoney_wmy_hide_mode'] = $this->request->post['webmoney_wmy_hide_mode'];
		} else {
			$data['webmoney_wmy_hide_mode'] = $this->config->get('webmoney_wmy_hide_mode'); 
		}

		if (isset($this->request->post['webmoney_wmy_minimal_order'])) {
			$data['webmoney_wmy_minimal_order'] = $this->request->post['webmoney_wmy_minimal_order'];
		} else {
			$data['webmoney_wmy_minimal_order'] = $this->config->get('webmoney_wmy_minimal_order'); 
		}

		if (isset($this->request->post['webmoney_wmy_maximal_order'])) {
			$data['webmoney_wmy_maximal_order'] = $this->request->post['webmoney_wmy_maximal_order'];
		} else {
			$data['webmoney_wmy_maximal_order'] = $this->config->get('webmoney_wmy_maximal_order'); 
		}
		
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['webmoney_wmy_geo_zone_id'])) {
			$data['webmoney_wmy_geo_zone_id'] = $this->request->post['webmoney_wmy_geo_zone_id'];
		} else {
			$data['webmoney_wmy_geo_zone_id'] = $this->config->get('webmoney_wmy_geo_zone_id'); 
		}
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['webmoney_wmy_status'])) {
			$data['webmoney_wmy_status'] = $this->request->post['webmoney_wmy_status'];
		} else {
			$data['webmoney_wmy_status'] = $this->config->get('webmoney_wmy_status');
		}
		
		if (isset($this->request->post['webmoney_wmy_sort_order'])) {
			$data['webmoney_wmy_sort_order'] = $this->request->post['webmoney_wmy_sort_order'];
		} else {
			$data['webmoney_wmy_sort_order'] = $this->config->get('webmoney_wmy_sort_order');
		}
		
		if (isset($this->request->post['webmoney_wmy_log'])) {
			$data['webmoney_wmy_log'] = $this->request->post['webmoney_wmy_log'];
		} else {
			$data['webmoney_wmy_log'] = $this->config->get('webmoney_wmy_log');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/webmoney_wmy.tpl', $data));
	}

   public function clearLog() {
    $this->load->language('extension/payment/webmoney_wmy');

    $json = array();

    if ($this->validatePermission()) {
      if (is_file(DIR_LOGS . self::FILE_NAME_LOG)) {
        @unlink(DIR_LOGS . self::FILE_NAME_LOG);
      }
        $json['success'] = $this->language->get('text_clear_log_success');
      } else {
        $json['error'] = $this->language->get('error_clear_log');
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }
	
	protected function validate() {
		if (!$this->validatePermission()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		// TODO проверку на валидность номера!
		if (!$this->request->post['webmoney_wmy_merch_r']) {
			$this->error['merch_r'] = $this->language->get('error_merch_r');
		}
		
		if (!$this->request->post['webmoney_wmy_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}

		if (!$this->request->post['webmoney_wmy_secret_key_x20']) {
			$this->error['secret_key_x20'] = $this->language->get('error_secret_key_x20');
		}
		
		return !$this->error;
	}

  protected function validatePermission() {
    return $this->user->hasPermission('modify', 'extension/payment/webmoney_wmy');
  }

    protected function readLastLines($filename, $lines) {
        if (!is_file($filename)) {
            return array();
        }
        $handle = @fopen($filename, "r");
        if (!$handle) {
            return array();
        }
        $linecounter = $lines;
        $pos = -1;
        $beginning = false;
        $text = array();

        while ($linecounter > 0) {
            $t = " ";

            while ($t != "\n") {
                /* if fseek() returns -1 we need to break the cycle*/
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }

            $linecounter--;

            if ($beginning) {
                rewind($handle);
            }

            $text[$lines - $linecounter - 1] = fgets($handle);

            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        return array_reverse($text);
    }
}
?>