<?php
class ocStore {
	private $config;
	private $db;
	private $data = array();
	
  	public function validate($string="", $filter="2") {
      $filters["1"] = FILTER_VALIDATE_INT;
      $filters["2"] = FILTER_VALIDATE_EMAIL;
      $filters["0"] = FILTER_VALIDATE_BOOLEAN;
      
      $res = filter_var($string, $filters["".$filter.""]);
      
      return($res);
   }
}
?>