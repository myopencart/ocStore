<?php
class ModelToolSitemap extends Model {
	private function getPathByCategory($category_id) {
		$category_id = (int)$category_id;
		if($category_id < 1) return false;

		$max_level = 10;

		$sql = "SELECT CONCAT_WS('_'";
		for($i = $max_level - 1; $i >= 0; --$i) {
			$sql .= ",t$i.category_id";
		}
		$sql .= ") AS path FROM " . DB_PREFIX . "category t0";
		for($i = 1; $i < $max_level; ++$i) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i - 1) . ".parent_id)";
		}
		$sql .= " WHERE t0.category_id = '" . $category_id . "'";

		$query = $this->db->query($sql);

		return $query->num_rows ? $query->row['path'] : false;
	}

	public function getProducts() {
		$product_data = $this->cache->get('product.sitemap.' . (int)$this->config->get('config_store_id'));

		if(!$product_data) {
			//cache for seo_pro
			$category_path = array();
			$query = $this->db->query("SELECT c.category_id FROM " . DB_PREFIX . "category c WHERE c.status = '1'");
			foreach($query->rows as $row) {
				$category_path[$row['category_id']] = $this->getPathByCategory($row['category_id']);
			}
			$category_path[0] = false;
			$this->cache->set('category.seopath', $category_path);

			$product_path = array();
			$query = $this->db->query("SELECT p.product_id, date(GREATEST(p.date_added, p.date_modified)) as 'date'," .
									  " (SELECT category_id FROM " . DB_PREFIX . "product_to_category p2c WHERE p2c.product_id = p.product_id ORDER BY main_category DESC LIMIT 1) as category_id" .
									  " FROM " . DB_PREFIX . "product p" .
									  " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)" .
									  " WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"
			);
			foreach($query->rows as $row) {
				$product_path[$row['product_id']] = $category_path[$row['category_id']];
			}

			$this->cache->set('product.seopath', $product_path);


			$product_data = $query->rows;

			$this->cache->set('product.sitemap.' . (int)$this->config->get('config_store_id'), $product_data);
		}
		return $product_data;
	}

	public function getAllCategories() {

		$category_data = $this->cache->get('category.sitemap.' . (int)$this->config->get('config_store_id'));

		if(!$category_data || !is_array($category_data)) {
			$query = $this->db->query("SELECT c.category_id, c.parent_id, date(GREATEST(c.date_added, c.date_modified)) as 'date' FROM " . DB_PREFIX . "category c" .
									  " LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)" .
									  " WHERE c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1'");

			$category_data = array();

			foreach($query->rows as $row) {
				$category_data[$row['parent_id']][$row['category_id']] = $row;
			}

			$this->cache->set('category.sitemap.' . (int)$this->config->get('config_store_id'), $category_data);
		}

		return $category_data;
	}

	public function getManufacturers() {

		$manufacturer_data = $this->cache->get('manufacturer.sitemap.' . (int)$this->config->get('config_store_id'));

		if(!$manufacturer_data) {
			$query = $this->db->query("SELECT m.manufacturer_id FROM " . DB_PREFIX . "manufacturer m" .
									  " LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id)" .
									  " WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

			$manufacturer_data = $query->rows;

			$this->cache->set('manufacturer.sitemap.' . (int)$this->config->get('config_store_id'), $manufacturer_data);
		}

		return $manufacturer_data;
	}

	public function getInformations() {

		$informations_data = $this->cache->get('information.sitemap.' . (int)$this->config->get('config_store_id'));

		if(!$informations_data) {
			$query = $this->db->query("SELECT i.information_id FROM " . DB_PREFIX . "information i" .
									  " LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id)" .
									  " WHERE i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1' AND i.sort_order <> '-1'");

			$informations_data = $query->rows;

			$this->cache->set('information.sitemap.' . (int)$this->config->get('config_store_id'), $informations_data);
		}

		return $informations_data;
	}
}

?>