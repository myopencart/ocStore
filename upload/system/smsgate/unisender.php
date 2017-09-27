<?php
final class Unisender extends SmsGate {
	private $baseurl = 'https://api.unisender.com/ru/api/sendSms?format=json';
	public $error;
	
	public function send() {
		$ret = array();
		
		$params = array(
			'id'	=> $this->username,
			'key'	=> $this->password,
			'from'	=> $this->from,
			'to'	=> $this->to,
			'text'	=> $this->message
		);

		$params['to'] = preg_replace("/[^0-9+]/", '', $params['to']);
		
		if ($this->copy) {
			$params['to'].= ','.preg_replace("/[^0-9+]/", '', $this->copy);
		}
		$ret = $this->sendSms($params);
		
		return $ret;
	}
	
	private function sendSms($params) {
		// Создаём POST-запрос
		$POST = array (
			'api_key' => $params['key'], // Ваш ключ доступа к API Unisender (из Личного Кабинета)
			'phone' => $params['to'],
			'sender' => $params['from'],
			'text' => $params['text']
		);

		// Устанавливаем соединение
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $this->baseurl);
		$result = curl_exec($ch);

		if ($result) {
			// Раскодируем ответ API-сервера
			$jsonObj = json_decode($result);

			if(null === $jsonObj) {
				// Ошибка в полученном ответе
				$this->error = "Ошибка отправки SMS через Unisender: API-сервер вернул невалидный JSON";
				return false;
			}
			elseif(!empty($jsonObj->error)) {
				// Ошибка отправки сообщения
				$this->error = "Ошибка отправки SMS через Unisender: " . $jsonObj->error . " (код: " . $jsonObj->code . ")";
				return false;
			} else {
				// Сообщение успешно отправлено
				//echo "SMS message is sent. Message id " . $jsonObj->result->sms_id;
				//echo "SMS cost is " . $jsonObj->result->price . " " . $jsonObj->result->currency;
				return $jsonObj->result;
			}
		} else {
			// Ошибка соединения с API-сервером
			$this->error = "Ошибка отправки SMS через Unisender: не удалось соединиться с API-сервером";
			return false;
		}
	}
}
