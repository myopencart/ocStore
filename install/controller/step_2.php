<?php
class ControllerStep2 extends Controller {
	private $error = array();

	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->redirect($this->url->link('step_3'));
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] = $this->url->link('step_2');

		$this->data['config_catalog'] = DIR_OPENCART . 'config.php';
		$this->data['config_admin'] = DIR_OPENCART . 'admin/config.php';

		$this->data['cache'] = DIR_SYSTEM . 'cache';
		$this->data['logs'] = DIR_SYSTEM . 'logs';
		$this->data['image'] = DIR_OPENCART . 'image';
		$this->data['image_cache'] = DIR_OPENCART . 'image/cache';
		$this->data['image_data'] = DIR_OPENCART . 'image/data';
		$this->data['download'] = DIR_OPENCART . 'download';

		$this->data['back'] = $this->url->link('step_1');

		$this->template = 'step_2.tpl';
		$this->children = array(
			'header',
			'footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (phpversion() < '5.2') {
			$this->error['warning'] = 'Внимание: Для корректной работы ocStore нужен PHP5.2 или выше!';
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = 'Внимание: file_uploads должно быть включено!';
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = 'Внимание: Для работы ocStore необходимо отключить директиву session.auto_start в файле php.ini!';
		}

		if (!extension_loaded('mysql')) {
			$this->error['warning'] = 'Внимание: Для корректной работы ocStore необходима поддержка MySQL!';
		}

		if (!extension_loaded('gd')) {
			$this->error['warning'] = 'Внимание: Для корректной работы ocStore необходима поддержка GD!';
		}

		if (!extension_loaded('curl')) {
			$this->error['warning'] = 'Внимание: Для корректной работы ocStore необходима поддержка CURL!';
		}

		if (!function_exists('mcrypt_encrypt')) {
			$this->error['warning'] = 'Внимание: Для корректной работы ocStore необходима поддержка mCrypt!';
		}

		if (!extension_loaded('zlib')) {
			$this->error['warning'] = 'Внимание: Для корректной работы ocStore необходима поддержка ZLIB!';
		}

		if (!file_exists(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = 'Внимание: config.php не найден. Необходимо переименовать config-dist.php в config.php!';
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись файла config.php!';
		}

		if (!file_exists(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = 'Внимание: admin/config.php не найден. Необходимо переименовать admin/config-dist.php в admin/config.php!';
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись файла admin/config.php!';
		}
		if (!is_writable(DIR_SYSTEM . 'cache')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись в директорию Cache!';
		}

		if (!is_writable(DIR_SYSTEM . 'logs')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись в директорию Logs!';
		}

		if (!is_writable(DIR_OPENCART . 'image')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись в директорию Image!';
		}

		if (!is_writable(DIR_OPENCART . 'image/cache')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись в директорию Image/cache!';
		}

		if (!is_writable(DIR_OPENCART . 'image/data')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись в директорию Image/data!';
		}

		if (!is_writable(DIR_OPENCART . 'download')) {
			$this->error['warning'] = 'Внимание: Необходимо разрешение на запись в директорию Download!';
		}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
	}
}
?>