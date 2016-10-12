<?php
class ControllerApiTrackNo extends Controller {

	public function save() {
		$this->load->language('api/order');

		$json = array();

		if (!$this->config->get('track_no_ignore_security') && !isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/shipping/track_no');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
				$res = $this->model_extension_shipping_track_no->save($order_id, $this->request->post['track_no']);
				$json['success'] = 'Трек-номер (идентификатор отправления) сохранен!';
				
				if (isset($res['error'])) {
					$json['error'] = $res['error'];
				}
				if (isset($res['success'])) {
					$json['success'].= "\n".$res['success'];
				}
			} else {
				$order_id = 0;
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
    
    public function update_liveinform() {
		if (isset($_SERVER['HTTP_HOST'])) {
			header("Content-Type: text/html; charset=utf-8");
		}
		if (!$this->config->get('track_no_liveinform_sync')) {
			echo 'Синхронизация заказов с LiveInform отключена. Включить можно в настройках модуля "Трек-номер заказа"';
		}
		else {
			$this->load->model('extension/shipping/track_no');
			echo "Синхронизация заказов с LiveInform...<br/>\n";
			$orders = $this->model_extension_shipping_track_no->updateLiveinform();
			echo "Синхронизация завершена\n";
		}
    }
}