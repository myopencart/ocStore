<?php
class ModelOcteamToolsSeoKeywordChecker extends Model {
	public function w_showColumns($table="") {
		if (empty($table)) {
			//$table = "" . DB_PREFIX . "webme_md5_checker_directory";
			$table = "" . DB_PREFIX . "url_alias";
		}
		$sql = "SHOW COLUMNS FROM `".$table."`";
		
		$query = $this->db->query($sql);
		foreach($query->rows as $row) {
			//print_r($row["Field"]." = 'asd'<br />");
			print "`".$row["Field"]."`, ";
		}
		
		//print_r($query->rows);
	}
	
	/*
	http://forums.mysql.com/read.php?10,180556,180572#msg-180572
	
		SELECT t1.* FROM t1 INNER JOIN ( 
		SELECT colA,colB,COUNT(*) FROM t1 GROUP BY colA,colB HAVING COUNT(*)>1) as t2 
		ON t1.cola = t2.cola and t1.colb = t2.colb;
	*/
	public function getDuplicates() {
		$result = array();
		$sql = "
				SELECT
					".DB_PREFIX."url_alias.*
				FROM
					".DB_PREFIX."url_alias
						INNER JOIN (SELECT `keyword`,COUNT(*) FROM ".DB_PREFIX."url_alias GROUP BY `keyword` HAVING COUNT(*)>1) as t2 ON ".DB_PREFIX."url_alias.`keyword` = t2.`keyword`
				ORDER BY
					".DB_PREFIX."url_alias.`keyword` ASC
				";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			foreach($query->rows as $row) {
				$result[] = $row;
			}
		}
		
		return $result;
	}
	
	/*
		getItemName(item_type, column, value)
		
		item_type:
			product, category, manufacturer, information
		
		column:
			product_id, category_id, manufacturer_id, information_id
		
		value:
			int ID
	*/
	public function getItemName($item_type, $column, $value) {
		$itemName = '';
		$selectColumn = ($item_type == 'information') ? 'title' : 'name';
		$table_postfix = ($item_type != 'manufacturer') ? '_description' : '';
		$langClause = (!empty($table_postfix)) ? "AND `language_id` = '".(int)$this->config->get('config_language_id')."'" : "";
		$sql = "
				SELECT
					`".$selectColumn."` as name
					FROM
						`".DB_PREFIX."".$item_type.$table_postfix."`
						WHERE
							`".$column."` = '".(int)$value."'
							".$langClause."
				";
		$query = $this->db->query($sql);
		if ($query->num_rows == 1) {
			$itemName = $query->row['name'];
		}
		
		return $itemName;
	}
	
	public function deleteUrlAliasById($url_alias_id) {
		if ($url_alias_id > 0) {
			if ($this->db->query("DELETE FROM ".DB_PREFIX."url_alias WHERE `url_alias_id`='".(int)$url_alias_id."'")) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	public function keywordIsAvailable($keyword) {
		$sql = "SELECT COUNT(`url_alias_id`) as total FROM ".DB_PREFIX."url_alias WHERE `keyword`='".$this->db->escape($keyword)."'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			if ($query->row['total'] == 0) {
				return true;
			}
		}
		return false;
	}
	
	public function updateUrlAliasById($url_alias_id, $keyword) {
		if ($url_alias_id > 0 && !empty($keyword)) {
			if ($this->db->query("UPDATE ".DB_PREFIX."url_alias SET `keyword`='".$this->db->escape($keyword)."' WHERE `url_alias_id`='".(int)$url_alias_id."'")) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
}
?>