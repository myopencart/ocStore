<?php
class ModelOcteamToolsSeoManager extends Model {

	public function updateUrlAlias($data) {
		if(!empty($data['url_alias_id'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "url_alias` SET `query` = '" . $this->db->escape($data['query']) . "', `keyword` = '" . $data['keyword'] . "' WHERE `url_alias_id` = '" . (int)$data['url_alias_id'] . "'");
		} else {
			$url_alias_id = $this->db->getLastId();
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET url_alias_id = '" . (int)$url_alias_id . "', `query` = '" .  $this->db->escape($data['query']) . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('seo_pro');
		$this->cache->delete('seo_url');

		return true;
	}

	public function deleteUrlAlias($url_alias_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `url_alias_id` = '" . (int)$url_alias_id . "'");

		$this->cache->delete('seo_pro');
		$this->cache->delete('seo_url');
	}	
	
	public function getUrlAaliases($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "url_alias`";

      $implode = array();

      if (!empty($data['filter_query'])) {
        $implode[] = "query LIKE '%" . $this->db->escape($data['filter_query']) . "%'";
      }

      if (!empty($data['filter_keyword'])) {
        $implode[] = "keyword = '" . $this->db->escape($data['filter_keyword']) . "'";
      }

      if ($implode) {
        $sql .= " WHERE " . implode(" AND ", $implode);
      }

			$sort_data = array('query', 'keyword');

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY query";
			}

			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return  $query->rows;
		} else {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` ORDER BY query");
			return $query->rows;
		}
	}

	// Total Aliases
	public function getTotalUrlAalias($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "url_alias`";

		$implode = array();

		if (!empty($data['filter_query'])) {
			$implode[] = "query LIKE '%" . $this->db->escape($data['filter_query']) . "%'";
		}

		if (!empty($data['filter_keyword'])) {
			$implode[] = "keyword = '" . $this->db->escape($data['filter_keyword']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>