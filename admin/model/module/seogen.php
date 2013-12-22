<?php

class ModelModuleSeogen extends Model {
	private $keywords = false;
	private $manufr_desc = false;

	public function __construct($registry) {
		parent::__construct($registry);

		require_once(DIR_SYSTEM . 'library/URLify.php');

		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "manufacturer_description'");
		$this->manufr_desc = $query->num_rows;
	}

	private function loadKeywords() {
		$this->keywords = array();
		$query = $this->db->query("SELECT LOWER(`keyword`) as 'keyword' FROM " . DB_PREFIX . "url_alias");
		foreach($query->rows as $row) {
			$this->keywords[] = $row['keyword'];
		}
		return $query;
	}

	public function urlifyCategory($category_id) {
		$category = $this->getCategories($category_id);
		$data = $this->config->get('seogen');

		if($data['seogen_overwrite'] || is_null($category[0]['keyword'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` ='category_id=" . (int)$category_id . "';");
		}
		$this->generateCategory($category[0], $data);
	}

	public function urlifyProduct($product_id) {
		$product = $this->getProducts($product_id);
		$this->generateProduct($product[0], $this->config->get('seogen'));
	}

	public function urlifyManufacturer($manufacturer_id) {
		$manufacturer = $this->getManufacturers($manufacturer_id);
		$this->generateManufacturer($manufacturer[0], $this->config->get('seogen'));
	}

	public function generateCategories($data) {
		if($data['seogen_overwrite']) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('category_id=%');");
		}
		$this->loadKeywords();
		foreach($this->getCategories() as $category) {
			$this->generateCategory($category, $data);
		}
	}

	public function generateProducts($data) {
		if($data['seogen_overwrite']) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('product_id=%');");
		}
		$this->loadKeywords();
		foreach($this->getProducts() as $product) {
			$this->generateProduct($product, $data);
		}
	}

	public function generateManufacturers($data) {
		if($data['seogen_overwrite']) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` LIKE ('manufacturer_id=%');");
		}
		$this->loadKeywords();
		foreach($this->getManufacturers() as $manufacturer) {
			$this->generateManufacturer($manufacturer, $data);
		}
	}

	private function generateCategory($category, $data) {
		$tags = array('[category_name]' => $category['name']);

		if($data['seogen_overwrite'] || is_null($category['keyword'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query`='category_id=" . (int)$category['category_id'] . "'");
			$keyword = $this->urlify($data['categories_template'], $tags);
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query`='category_id=" . (int)$category['category_id'] . "', keyword='" . $this->db->escape($keyword) . "'");
		}


		$updates = array();
		if(isset($category['seo_h1']) && ($data['seogen_overwrite'] || (strlen(trim($category['seo_h1']))) == 0)) {
			$h1 = trim(strtr($data['categories_h1_template'], $tags));
			$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
		}
		if(isset($category['seo_title']) && ($data['seogen_overwrite'] || (strlen(trim($category['seo_title']))) == 0)) {
			$title = trim(strtr($data['categories_title_template'], $tags));
			$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
		}
		if(isset($category['meta_keyword']) && ($data['seogen_overwrite'] || (strlen(trim($category['meta_keyword']))) == 0)) {
			$meta_keyword = trim(strtr($data['categories_meta_keyword_template'], $tags));
			$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
		}
		if(isset($category['meta_description']) && ($data['seogen_overwrite'] || (strlen(trim($category['meta_description']))) == 0)) {
			$meta_description = trim(strtr($data['categories_meta_description_template'], $tags));
			$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
		}

		if(count($updates)) {
			$this->db->query("UPDATE `" . DB_PREFIX . "category_description`" .
							 " SET " . implode(", ", $updates) .
							 " WHERE category_id='" . (int)$category['category_id'] . "' AND language_id='" . (int)$this->config->get('config_language_id') . "'");
		}
	}

	public function generateProduct($product, $data) {
		$tags = array('[product_name]' => $product['name'],
					  '[model_name]' => $product['model'],
					  '[manufacturer_name]' => $product['manufacturer']);
		if($data['seogen_overwrite'] || is_null($product['keyword'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query`='product_id=" . (int)$product['product_id'] . "'");
			$keyword = $this->urlify($data['products_template'], $tags);
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query`='product_id=" . (int)$product['product_id'] . "', keyword='" . $this->db->escape($keyword) . "'");
		}

		$updates = array();
		if(isset($product['seo_h1']) && ($data['seogen_overwrite'] || (strlen(trim($product['seo_h1']))) == 0)) {
			$h1 = trim(strtr($data['products_h1_template'], $tags));
			$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
		}
		if(isset($product['seo_title']) && ($data['seogen_overwrite'] || (strlen(trim($product['seo_title']))) == 0)) {
			$title = trim(strtr($data['products_title_template'], $tags));
			$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
		}
		if(isset($product['meta_keyword']) && ($data['seogen_overwrite'] || (strlen(trim($product['meta_keyword']))) == 0)) {
			$meta_keyword = trim(strtr($data['products_meta_keyword_template'], $tags));
			$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
		}
		if(isset($product['meta_description']) && ($data['seogen_overwrite'] || (strlen(trim($product['meta_description']))) == 0)) {
			$meta_description = trim(strtr($data['products_meta_description_template'], $tags));
			$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
		}

		if(count($updates)) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product_description`" .
							 " SET " . implode(", ", $updates) .
							 " WHERE product_id='" . (int)$product['product_id'] . "' AND language_id='" . (int)$this->config->get('config_language_id') . "'");
		}
	}

	private function generateManufacturer($manufacturer, $data) {
		$tags = array('[manufacturer_name]' => $manufacturer['name']);
		if($data['seogen_overwrite'] || is_null($manufacturer['keyword'])) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query`='manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "'");
			$keyword = $this->urlify($data['manufacturers_template'], $tags);
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query`='manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "', keyword='" . $this->db->escape($keyword) . "'");
		}

		if($this->manufr_desc) {
			$updates = array();
			if(isset($manufacturer['seo_h1']) && ($data['seogen_overwrite'] || (strlen(trim($manufacturer['seo_h1']))) == 0)) {
				$h1 = trim(strtr($data['manufacturers_h1_template'], $tags));
				$updates[] = "`seo_h1`='" . $this->db->escape($h1) . "'";
			}
			if(isset($manufacturer['seo_title']) && ($data['seogen_overwrite'] || (strlen(trim($manufacturer['seo_title']))) == 0)) {
				$title = trim(strtr($data['manufacturers_title_template'], $tags));
				$updates[] = "`seo_title`='" . $this->db->escape($title) . "'";
			}
			if(isset($manufacturer['meta_keyword']) && ($data['seogen_overwrite'] || (strlen(trim($manufacturer['meta_keyword']))) == 0)) {
				$meta_keyword = trim(strtr($data['manufacturers_meta_keyword_template'], $tags));
				$updates[] = "`meta_keyword`='" . $this->db->escape($meta_keyword) . "'";
			}
			if(isset($manufacturer['meta_description']) && ($data['seogen_overwrite'] || (strlen(trim($manufacturer['meta_description']))) == 0)) {
				$meta_description = trim(strtr($data['manufacturers_meta_description_template'], $tags));
				$updates[] = "`meta_description`='" . $this->db->escape($meta_description) . "'";
			}
			if(count($updates)) {
				$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer_description`" .
								 " SET " . implode(", ", $updates) .
								 " WHERE manufacturer_id='" . (int)$manufacturer['manufacturer_id'] . "' AND language_id='" . (int)$this->config->get('config_language_id') . "'");
			}
		}
	}

	private function getCategories($category_id = false) {
		$query = $this->db->query("SELECT cd.*, u.keyword FROM " . DB_PREFIX . "category_description cd" .
								  " LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('category_id=', cd.category_id) = u.query)" .
								  " WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'" .
								  ($category_id ? " AND cd.category_id='" . (int)$category_id . "'" : "") .
								  " ORDER BY cd.category_id");
		return $query->rows;
	}

	private function getProducts($product_id = false) {
		$query = $this->db->query("SELECT pd.*, u.keyword, m.name as 'manufacturer', p.model as 'model'" .
								  " FROM `" . DB_PREFIX . "product` p" .
								  " LEFT JOIN `" . DB_PREFIX . "product_description` pd ON ( pd.product_id = p.product_id )" .
								  " LEFT JOIN `" . DB_PREFIX . "manufacturer` m ON ( m.manufacturer_id = p.manufacturer_id )" .
								  " LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('product_id=', p.product_id) = u.query)" .
								  " WHERE pd.language_id ='" . (int)$this->config->get('config_language_id') . "'" .
								  ($product_id ? " AND p.product_id='" . (int)$product_id . "'" : "") .
								  " ORDER BY p.product_id");
		return $query->rows;
	}

	private function getManufacturers($manufacturer_id = false) {
		if($this->manufr_desc) {
			$query = $this->db->query("SELECT md.*, u.keyword, m.name" .
									  " FROM `" . DB_PREFIX . "manufacturer` m" .
									  " LEFT JOIN `" . DB_PREFIX . "manufacturer_description` md ON (m.manufacturer_id=md.manufacturer_id)" .
									  " LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('manufacturer_id=', m.manufacturer_id) = u.query)" .
									  " WHERE md.language_id='" . (int)$this->config->get('config_language_id') . "'" .
									  ($manufacturer_id ? " AND m.manufacturer_id='" . (int)$manufacturer_id . "'" : "") .
									  " ORDER BY m.manufacturer_id");
		} else {
			$query = $this->db->query("SELECT manufacturer_id, name, u.keyword" .
									  " FROM `" . DB_PREFIX . "manufacturer` m" .
									  " LEFT JOIN " . DB_PREFIX . "url_alias u ON (CONCAT('manufacturer_id=', m.manufacturer_id) = u.query)" .
									  ($manufacturer_id ? " WHERE m.manufacturer_id='" . (int)$manufacturer_id . "'" : "") .
									  " ORDER BY m.manufacturer_id");
		}
		return $query->rows;
	}

	private function checkDuplicate(&$keyword) {
		$counter = 0;
		$k = $keyword;
		if($this->keywords !== false) {
			while(in_array($keyword, $this->keywords)) {
				$keyword = $k . '-' . ++$counter;
			}
			$this->keywords[] = $keyword;
		} else {
			do {
				$query = $this->db->query("SELECT url_alias_id FROM " . DB_PREFIX . "url_alias WHERE keyword ='" . $this->db->escape($keyword) . "'");
				if($query->num_rows > 0) {
					$keyword = $k . '-' . ++$counter;
				}
			} while($query->num_rows > 0);
		}
	}

	private function urlify($template, $tags) {
		$keyword = strtr($template, $tags);
		$keyword = trim(html_entity_decode($keyword, ENT_QUOTES, "UTF-8"));
		$urlify = URLify::filter($keyword);
		$this->checkDuplicate($urlify);
		return $urlify;
	}
}