<?php
require_once(DIR_SYSTEM."library/sms.php");

class ModelExtensionShippingTrackNo extends Model {
	public $liveinform_error;
	public $liveinform_success;
    
	function getQuote($address) {
		return array();
	}
	
	public function save($order_id, $track_no) {
		if (!$this->config->get('track_no_status')) {
			return false;
		}
		$track_no = trim($track_no);
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$order_info['order_id'] = $order_id;

		if ($order_info['track_no'] == $track_no) {
			return false;
		}
				
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET track_no='" . $this->db->escape($track_no) . "' WHERE order_id = '" . (int)$order_id . "'");
		if (!$track_no) {
			return true;
		}
		
		$order_info['track_no'] = $track_no;
		if ($this->config->get('track_no_change_status')) {
			$order_info['order_status_id'] = $this->config->get('track_no_order_status');
			$comment = 'Заказу присвоен трек-номер: '.$track_no;
			$this->model_checkout_order->addOrderHistory($order_id, $order_info['order_status_id'], $comment, false, true);
		}
		if ($this->config->get('track_no_email_notify')) {
			$comment = $this->getComment($order_info, $this->config->get('track_no_email_text'));
			$this->model_checkout_order->addOrderHistory($order_id, $order_info['order_status_id'], $comment, true, true);
		}
		if ($this->config->get('track_no_sms_notify')) {
			$this->smsNotify($order_info, $this->getComment($order_info, $this->config->get('track_no_sms_text')));
		}
		if ($this->config->get('track_no_export_liveinform') && (preg_match('/\w\w\d{9}\w\w/i', $track_no) || preg_match('/\d{14}/i', $track_no) || preg_match('/\d{10}/i', $track_no))) {
			$this->exportLiveinform($this->config->get('track_no_liveinform_api_id'), $this->config->get('track_no_liveinform_type'), $order_info);
		}
		return array('success'=>$this->liveinform_success, 'error'=>$this->liveinform_error);
	}
	
	protected function smsNotify($order, $message) {
		$options = array(
			'to'       => $order['telephone'],
			'copy'     => '',
			'from'     => $this->config->get('config_sms_from'),
			'username'    => $this->config->get('config_sms_gate_username'),
			'password' => $this->config->get('config_sms_gate_password'),
			'message'  => $message,
			'ext'      => null
		);
			
		$sms = new Sms($this->config->get('config_sms_gatename'), $options);
		$sms->send();
		if ($sms->error) {
			$this->liveinform_error.= "\n".trim($sms->error);
			$this->log('Заказ #'.$order['track_no'].' (ID:'.$order['order_id'].'). Не удалось отправить SMS, тел.: ' . $order['telephone'] . ' (' . $sms->error . ').', 4);
		}
		else {
			$this->liveinform_success.= "\nSMS отправлено.";
			$this->log('Заказ #'.$order['track_no'].' (ID:'.$order['order_id'].'). Отправляем SMS, тел.: ' . $order['telephone'] . ' (' . $message . ').', 4);
		}
	}

	private function getComment($order, $text) {
		foreach ($order as $key=>$val) {
			if (strpos($text, $key) !== false) {
				$text = str_replace('{'.$key.'}', $val, $text);
			}
		}
		return $text;
	}
	
	private $LIVEINFORM_RESP = array(
		'100' => 'Отслеживание LiveInform успешно добавлено.',
		'200' => 'Неправильный api_id.',
		'201' => 'Неправильно введен телефон.',
		'202' => 'Неправильно введен трек-номер.',
		'203' => 'Не указан тип отслеживания.',
		'204' => 'Не хватает денежных средств.',
		'205' => 'Отслеживание с таким телефоном клиента и трек-номером уже создано.',
        '208' => 'Заказ не найден.',
        '210' => 'Информация по треку пока не поступало.'
	);

	/**
	* Регистрировать заказ в LiveInform
	* @param str $api_id
	* @param int $type
	* @param array $order_info
	**/
	public function exportLiveinform($api_id, $type, $order_info) {
		$phone = $this->preparePhone($order_info['telephone']);
		if (!$phone) {
			$this->liveinform_error.= "\n".$this->LIVEINFORM_RESP['201'];
			return false;
		}
		$body = file_get_contents('http://www.liveinform.ru/api/add/?api_id='.$api_id.'&phone='.$phone.'&tracking='.urlencode($order_info['track_no'])
			.'&type='.$type.'&order_id='.urlencode($order_info['order_id']).'&email='.urlencode($order_info['email'])
            .'&firstname='.urlencode($order_info['shipping_firstname']).'&lastname='.urlencode($order_info['shipping_lastname']));
		$code = substr($body, 0, 3);
		if ($code == '100') {
			$this->liveinform_success.= "\n".$this->LIVEINFORM_RESP[$code];
			$this->log('Заказ #'.$order_info['track_no'].' (ID:'.$order_info['order_id'].'). Отправлен в LiveInform, тел.: ' . $order_info['telephone'], 4);
			return true;
		}
		else {
			$this->liveinform_error.= "\nLiveInform не принял трек-номер для отслеживания: ".isset($this->LIVEINFORM_RESP[$code]) ? $this->LIVEINFORM_RESP[$code] : 'Неизвестная ошибка ('.$code.')';
			$this->log('Заказ #'.$order_info['track_no'].' (ID:'.$order_info['order_id'].'). Отправить в LiveInform не получилось, тел.: ' . $order_info['telephone'].' ('.$this->liveinform_error.')', 3);
			return false;
		}
	}
	
    public function updateLiveinform() {
        $api_id = $this->config->get('track_no_liveinform_api_id');
        if (!$api_id) {
            echo 'LiveInform API ID не указан. <a href="http://www.liveinform.ru/?partner=2324">Получите его</a> и укажите в настройках модуля "Трек-номер заказа"!<br/>';
            return false;
        }
        $this->ECHO = true;
		$this->load->model('checkout/order');
		$orders = $this->getLiveinformOrdersToUpdate();
		foreach ($orders as $order) {
            if (!preg_match('/\w\w\d{9}\w\w/i', $order['track_no']) && !preg_match('/\d{14}/i', $order['track_no']) && !preg_match('/\d{10}/i', $track_no)) {
                continue;
            }
            $states = $this->getLiveinformStatus($api_id, $order);
            if ($states === false) {
                continue;
            }
            $query = $this->db->query("SELECT comment FROM `".DB_PREFIX."order_history` WHERE order_id='".(int)$order['order_id']."' ORDER BY date_added DESC");
			$history_pul = array();
            foreach ($states as $state) {
				if (!isset($state['date']) || !$state['date']) {
					continue;
				}
                $already_added = false;
                foreach($query->rows as $row) {
                    if (strpos($row['comment'], $state['date']) === 0) {
                        $already_added = true;
                        $this->log('Заказ #'.$order['track_no'].' (ID:'.$order['order_id'].'). Данные уже обработаны ранее.', 5);
                        break;
                    }
                }
                if (!$already_added) {
					$order['operation'] = $state['operation'];
					$order['operation_text'] = $state['text'];
					$order['geo'] = $state['geo'];
					$order['index'] = $state['index'];
                    $comment = $state['date'].' '.$this->getComment($order, $this->config->get('track_no_sync_comment'));
					$order_status_id = $order['order_status_id'];
					if ($state['operation'] == 'Вручение') {
						$order_status_id = $this->config->get('track_no_issued_status');
					}
					elseif ($state['text'] == 'Прибыло в место вручения' || $state['text'] == 'Временное отсутствие адресата') {
						$order_status_id = $this->config->get('track_no_postoffice_status');
					}
					elseif ($state['operation'] == 'Возврат') {
						$order_status_id = $this->config->get('track_no_return_status');
					}
					else {
						$order_status_id = $this->config->get('track_no_shipping_status');
					}
					$history_pul[] = array('order_status_id'=>$order_status_id, 'comment'=>$comment);
                }
            }
			foreach($history_pul as $history) {
				$this->model_checkout_order->addOrderHistory($order['order_id'], $history['order_status_id'], $history['comment'], false, true);
				$this->log('Заказ #'.$order['track_no'].' (ID:'.$order['order_id'].'). Добавлен комментарий: `'.$history['comment'].'`.', 4);
			}
        }
		
        $this->ECHO = false;
    }
    
    public function getLiveinformOrdersToUpdate() {
		$not_in = ($this->config->get('track_no_order_statuses') ? $this->config->get('track_no_order_statuses') : '0');
		$query = $this->db->query("SELECT o.* FROM `" . DB_PREFIX . "order` o
			WHERE o.track_no <> '' AND NOT(o.order_status_id IN($not_in)) ORDER BY order_id DESC");
		return $query->rows;
    }
    
	/**
	* Узнать статусы заказа у LiveInform
	* @param str $api_id
	* @param array $order_info
	**/
    public function getLiveinformStatus($api_id, $order_info) {
		$phone = $this->preparePhone($order_info['telephone']);
		if (!$phone) {
			$this->liveinform_error = $this->LIVEINFORM_RESP['201'];
			return false;
		}
        $body = file_get_contents('http://www.liveinform.ru/api/getinfo/?api_id='.$api_id.'&phone='.$phone.'&tracking='.urlencode($order_info['track_no'])); 
		$code = substr($body, 0, 3);
		if ($code == '100') {
			$this->liveinform_success = $this->LIVEINFORM_RESP[$code];
            $json = trim(substr($body, 3));
            try {
                $state_data = json_decode($json, true);
                return array_reverse($state_data);
            }
            catch (Exception $e) {
                $this->log('Заказ #'.$order_info['track_no'].' (ID:'.$order_info['order_id'].'). LiveInform вернул невалидный JSON: `'.$json.'`', 3);
                return false;
            }
		}
		else {
			$this->liveinform_error = isset($this->LIVEINFORM_RESP[$code]) ? $this->LIVEINFORM_RESP[$code] : 'Неизвестная ошибка LiveInform ('.$code.')';
			$this->log('Заказ #'.$order_info['track_no'].' (ID:'.$order_info['order_id'].'). Узнать статус в LiveInform не получилось, тел.: ' . $order_info['telephone'].' ('.$this->liveinform_error.')', 3);
			return false;
		}
    }

	private function preparePhone($phone) {
		if (!$phone)
			return false;
		$phone = preg_replace('/[^\d]/i', '', $phone);
		if (strlen($phone) < 11) {
			$phone = '+7'.$phone;
		}
		else {
			$phone = '+'.$phone;
			if ($phone[1] == 8)
				$phone[1] = 7;
		}
		return $phone;
	}
    
    public $ECHO = false;
    public $LOG_LEVEL = 4;
	/**
	* Писать в журнал ошибки и сообщения
	* @param str $msg запись
	* @param int $level приоритет ошибки/сообщения. Если приоритет больше $this->LOG_LEVEL, то он записан не будет
	**/
	private function log($msg, $level = 0) {
		if ($level > $this->LOG_LEVEL) return;
		$fp = fopen(DIR_SYSTEM.'storage/logs/track_no_liveinform.log', 'a');
		fwrite($fp, date('Y-m-d H:i:s').': '.str_replace("\n", '', $msg)."\n");
		if ($this->ECHO) echo nl2br(htmlspecialchars($msg))."<br/>\n";
		fclose($fp);
	}

}
