<?php
class Cache { 
	private $expire = 3600; 

	 public function get($key) {
		$data = null;
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		if ($files) {
		for ($n=0, $lenght = count($files); $n < $lenght; $n++) {
		$file = $files[$n];
		$time = substr(strrchr($file, '.'), 1);
		      if ($time < time()) {
			if (file_exists($file)) { unlink($file); } 
		      } elseif (!$n) { 
			$cache = file_get_contents($file); $data = unserialize($cache);
			} 
		} 
		}  
		return $data; 
	}

  	public function set($key, $value, $expire=false) {
    	$this->delete($key);
		if (!$expire) $expire=$this->expire;
		$file = DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $expire);
    	
		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));
		
    	fclose($handle);
  	}
	
  	public function delete($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					unlink($file);
				}
    		}
		}
  	}
}
?>