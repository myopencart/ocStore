<?php
namespace Session;
class Native extends \SessionHandler {
	#[\ReturnTypeWillChange]
	public function create_sid() {
		return parent::create_sid();
	}

	#[\ReturnTypeWillChange]
	public function open($path, $name) {
		return parent::open($path, $name);
	}

	#[\ReturnTypeWillChange]
	public function close() {
		return parent::close();
	}

	#[\ReturnTypeWillChange]
	public function read($session_id) {
		return parent::read($session_id);
	}

	#[\ReturnTypeWillChange]
	public function write($session_id, $data) {
		return parent::write($session_id, $data);
	}

	#[\ReturnTypeWillChange]
	public function destroy($session_id) {
		return parent::destroy($session_id);
	}

	#[\ReturnTypeWillChange]
	public function gc($maxlifetime) {
		return parent::gc($maxlifetime);
	}	
}
