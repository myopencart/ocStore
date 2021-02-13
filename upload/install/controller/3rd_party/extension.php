<?php
class Controller3rdPartyExtension extends Controller {
	public function index() {
		$curl = curl_init();

        $curl = curl_init('https://ocstore.com/index.php?route=extension/json/extensions');

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($response);
	}
}