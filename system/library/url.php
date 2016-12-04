<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = array();
	
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}
		
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
		
	public function link($route, $args = '', $connection = 'NONSSL') {
		if ($connection ==  'NONSSL') {
			$url = $this->url;	
		} else {
			$url = $this->ssl;	
		}
		
		$url .= 'index.php?route=' . $route;
			
		if ($args) {
			if($route == 'common/home') {
				if($connection == 'NONSSL') {
					$url = HTTP_SERVER.str_replace('&', '&amp;', '?' . ltrim($args, '&')); 
					return $url;
				}else{
					$url = HTTPS_SERVER.str_replace('&', '&amp;', '?' . ltrim($args, '&')); 
					return $url;
				}
		
			}else{
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&')); 
			}
						
		}else{
			if($route == 'common/home'){
				if($connection == 'NONSSL'){ 
					return HTTP_SERVER;
				}else{
					return HTTPS_SERVER;
				}
			}
		}
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
				
		return $url;
	}
}
?>
