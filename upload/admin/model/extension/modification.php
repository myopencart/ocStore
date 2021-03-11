<?php
class ModelExtensionModification extends Model {
	public function addModification($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET code = '" . $this->db->escape($data['code']) . "', name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', version = '" . $this->db->escape($data['version']) . "', link = '" . $this->db->escape($data['link']) . "', xml = '" . $this->db->escape($data['xml']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function addModificationBackup($modification_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification_backup SET modification_id = '" . (int)$modification_id . "', code = '" . $this->db->escape($data['code']) . "', xml = '" . $this->db->escape($data['xml']) . "', date_added = NOW()");
	}

	public function editModification($modification_id, $data) {
        $xml = html_entity_decode($data['xml']);
        $name = html_entity_decode($data['name']);
	    $this->db->query("UPDATE " . DB_PREFIX . "modification SET xml = '" . $this->db->escape($xml) . "', name = '" . $this->db->escape($name) . "' WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function setModificationRestore($modification_id, $xml) {
	    $this->db->query("UPDATE " . DB_PREFIX . "modification SET xml = '" . $this->db->escape($xml) . "' WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function deleteModification($modification_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function deleteModificationBackups($modification_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification_backup WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function enableModification($modification_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET status = '1' WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function disableModification($modification_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET status = '0' WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function getModification($modification_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");

		return $query->row;
	}

	public function getModifications($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "modification";

		$sort_data = array(
			'name',
			'author',
			'version',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		return $query->rows;
	}

	public function getModificationBackups($modification_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "modification_backup  WHERE modification_id = '" . (int)$modification_id . "' ORDER BY date_added DESC";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getModificationBackup($modification_id, $backup_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "modification_backup  WHERE modification_id = '" . (int)$modification_id . "' AND backup_id = '" . (int)$backup_id . "' ORDER BY date_added DESC";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getTotalModifications() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "modification");

		return $query->row['total'];
	}
	
	public function getModificationByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}	
}