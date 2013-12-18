<?php
require_once(DIR_SYSTEM . 'library/URLify.php');

class ModelModuleSeogen extends Model {

	public function urlifyCategory($category_id){
		$category = $this->getCategories($category_id);
		$seogen = $this->config->get('seogen');
		$template = $seogen['categories_template'];
		$this->generateCategory($category[0], $template);
	}

	public function urlifyProduct($product_id){
		$product = $this->getProducts($product_id);
		$seogen = $this->config->get('seogen');
		$template = $seogen['products_template'];
		$this->generateProduct($product[0], $template);
	}

	public function urlifyManufacturer($manufacturer_id){
		$manufacturer = $this->getManufacturers($manufacturer_id);
		$seogen = $this->config->get('seogen');
		$template = $seogen['manufacturers_template'];
		$this->generateManufacturer($manufacturer[0], $template);
	}

	public function generateCategories($template) {
		foreach($this->getCategories() as $category) {
			$this->generateCategory($category, $template);
		}
	}

	public function generateProducts($template) {
		foreach($this->getProducts() as $product) {
			$this->generateProduct($product, $template);
		}
	}

	public function generateManufacturers($template) {
		foreach($this->getManufacturers() as $manufacturer) {
			$this->generateManufacturer($manufacturer, $template);
		}
	}

	private function generateCategory($category, $template) {
		$tags = array('[category_name]' => $category['name']);
		if($template === false) {
			$seogen = $this->config->get('seogen');
			$template = $seogen['categories_template'];
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query`='category_id=" . (int)$category['category_id'] . "'");
		$urlify = $this->urlify($template, $tags);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query`='category_id=" . (int)$category['category_id'] . "', keyword='" . $this->db->escape($urlify) . "'");
	}

	public function generateProduct($product, $template) {
		$tags = array('[product_name]' => $product['name'],
					  '[model_name]' => $product['model'],
					  '[manufacturer_name]' => $product['manufacturer']);
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query`='product_id=" . (int)$product['product_id'] . "'");
		$urlify = $this->urlify($template, $tags);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query`='product_id=" . (int)$product['product_id'] . "', keyword='" . $this->db->escape($urlify) . "'");
	}

	private function generateManufacturer($manufacturer, $template) {
		$tags = array('[manufacturer_name]' => $manufacturer['name']);
		if($template === false) {
			$seogen = $this->config->get('seogen');
			$template = $seogen['products_template'];
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query`='manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "'");
		$urlify = $this->urlify($template, $tags);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query`='manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "', keyword='" . $this->db->escape($urlify) . "'");
	}

	private function getCategories($category_id = false) {
		$query = $this->db->query("SELECT c.category_id, cd.name FROM " . DB_PREFIX . "category c" .
								  " LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)" .
								  " LEFT JOIN " . DB_PREFIX . "url_alias a ON (CONCAT('category_id=', c.category_id) = a.query)" .
								  " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
								  ($category_id ? " AND c.category_id='" . (int)$category_id . "'" : ""));
		return $query->rows;
	}

	private function getProducts($product_id = false) {
		$query = $this->db->query("SELECT p.product_id, pd.name, m.name as 'manufacturer', p.model as 'model'" .
								  " FROM `" . DB_PREFIX . "product` p" .
								  " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON ( pd.product_id = p.product_id )" .
								  " LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON ( m.manufacturer_id = p.manufacturer_id )" .
								  " WHERE pd.language_id ='" . (int)$this->config->get('config_language_id') . "'" .
								  ($product_id ? " AND p.product_id='" . (int)$product_id . "'" : ""));
		return $query->rows;
	}

	private function getManufacturers($manufacturer_id = false) {
		$query = $this->db->query("SELECT manufacturer_id, name" .
								  " FROM `" . DB_PREFIX . "manufacturer` m" .
								  ($manufacturer_id ? " WHERE m.manufacturer_id='" . (int)$manufacturer_id . "'" : ""));
		return $query->rows;
	}


	private function checkDuplicate(&$keyword) {
		$counter = 0;
		do {
			$query = $this->db->query("SELECT url_alias_id FROM oc_url_alias WHERE keyword ='" . $this->db->escape($keyword) . "'");
			if($query->num_rows > 0) {
				$keyword .= '-' . ++$counter;
			}
		} while($query->num_rows > 0);
	}

	private function urlify($template, $tags) {
		$keyword = strtr($template, $tags);
		$keyword = trim(html_entity_decode($keyword, ENT_QUOTES, "UTF-8"));
		$urlify = URLify::filter($keyword);
		$this->checkDuplicate($urlify);
		return $urlify;
	}
}