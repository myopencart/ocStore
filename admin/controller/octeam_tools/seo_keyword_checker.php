<?php
class ControllerOcteamToolsSeoKeywordChecker extends Controller {
	
	/* [w] update checker data __DO_NOT_EDIT__ :: begin */
	private $version = "0.1.ocs1531";
	private $updateCheckerUrl = 'http://store.webme.com.ua/index.php?route=webme/update_checker/remoteCheck';
	private $updateCheckerExtensionId = '1286236821';
	/* [w] update checker data __DO_NOT_EDIT__ :: end */
	
	private $error = array();
	private $duplicates = array();
	private $validItemTypes = array('product', 'category', 'manufacturer', 'information');
	
	public function index() {
		$route = explode("/", $this->request->get['route']);
		$this->data['tool'] = end($route);
		
		$this->load->language('octeam_tools/seo_keyword_checker');
		
		/* [w] update checker data __DO_NOT_EDIT__ :: begin */
		$this->data['text_check_updates'] = $this->language->get('text_check_updates');
		$this->data['updateCheckerUrl'] = HTTPS_SERVER.'index.php?route=octeam_tools/seo_keyword_checker/checkUpdate&token=' . $this->session->data['token'];
		$this->data['error_check_update'] = $this->language->get('error_check_update');
		/* [w] update checker data __DO_NOT_EDIT__ :: end */
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title').sprintf($this->language->get('version'), $this->version);
		
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_updated'] = $this->language->get('text_updated');
		$this->data['text_deleted'] = $this->language->get('text_deleted');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_find_duplicates'] = $this->language->get('button_find_duplicates');
		$this->data['button_update'] = $this->language->get('button_update');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_query'] = $this->language->get('column_query');
		$this->data['column_keyword'] = $this->language->get('column_keyword');
		$this->data['column_action'] = $this->language->get('column_action');
		
		
		$this->data['error_unexpected_error'] = $this->language->get('error_unexpected_error');
		
		
		/* warning */
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		/* breadcrumbs */
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_octeam_toolset'),
			'href'      => $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('octeam_tools/seo_keyword_checker', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->link('octeam_tools/seo_keyword_checker', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['searchForDuplicatesUrl'] = $this->url->link('octeam_tools/seo_keyword_checker/searchForDuplicates', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['duplicateDeleteUrl'] = $this->url->link('octeam_tools/seo_keyword_checker/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['duplicateUpdateUrl'] = $this->url->link('octeam_tools/seo_keyword_checker/update', 'token=' . $this->session->data['token'], 'SSL');
		
		
		$this->template = 'octeam_tools/seo_keyword_checker.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'octeam_tools/seo_keyword_checker')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function searchForDuplicates() {
		$json = array();
		
		$this->load->language('octeam_tools/seo_keyword_checker');
		$this->load->model('octeam_tools/seo_keyword_checker');
		
		$duplicates = $this->model_octeam_tools_seo_keyword_checker->getDuplicates();
		if (sizeof($duplicates)>0) {
			$json['error'] = $this->language->get('error_duplicates_found');
			foreach($duplicates as $i => $duplicate) {
				$typeArray = explode('=', $duplicate['query']);
				$typeArrayParts = explode('_', $typeArray[0]);
				
				$itemName = '';
				if (in_array($typeArrayParts[0], $this->validItemTypes)) {
					$type = $this->language->get('text_'.$typeArrayParts[0]);
					/*
						getItemName(item_type, column, value)
						
						item_type:
							product, category, manufacturer, information
						
						column:
							product_id, category_id, manufacturer_id, information_id
						
						value:
							int ID
					*/
					$itemName = $this->model_octeam_tools_seo_keyword_checker->getItemName($typeArrayParts[0], $typeArray[0], $typeArray[1]);
				} else {
					//$type = $this->language->get('text_unsupported_type');
					$type = $this->language->get('text_unknown_type');
				}
				
				$this->duplicates[] = array(
					'url_alias_id'    => $duplicate['url_alias_id'],
					'query'           => $duplicate['query'],
					'keyword'         => $duplicate['keyword'],
					'type'            => $type,
					'itemName'        => $itemName,
				);
			}
			$json['duplicates'] = $this->duplicates;
		} else {
			$json['success'] = $this->language->get('success_no_duplicates');
		}
		
		/* set output */
		$this->response->setOutput(json_encode($json));
	}
	
	/* url_alias updating */
	public function update() {
		$json = array();
		
		$this->load->language('octeam_tools/seo_keyword_checker');
		if (isset($this->request->post['url_alias_id']) && ($this->request->post['url_alias_id'] > 0)) {
			if (isset($this->request->post['keyword']) && !empty($this->request->post['keyword'])) {
				$this->load->model('octeam_tools/seo_keyword_checker');
				
				$keywordIsAvailable = $this->model_octeam_tools_seo_keyword_checker->keywordIsAvailable($this->request->post['keyword']);
				if ($keywordIsAvailable) {
					if ($this->model_octeam_tools_seo_keyword_checker->updateUrlAliasById($this->request->post['url_alias_id'], $this->request->post['keyword'])) {
						$json['success'] = $this->language->get('text_updated');
					} else {
						$json['error'] = $this->language->get('error_update_error');
					}
				} else {
					$json['error'] = $this->language->get('error_keyword_taken');
				}
			} else {
				$json['error'] = $this->language->get('error_update_no_keyword');
			}
		} else {
			$json['error'] = $this->language->get('error_no_url_alias_id');
		}
		
		/* set output */
		$this->response->setOutput(json_encode($json));
	}
	
	/* url_alias deletion */
	public function delete() {
		$json = array();
		
		$this->load->language('octeam_tools/seo_keyword_checker');
		if (isset($this->request->post['url_alias_id']) && ($this->request->post['url_alias_id'] > 0)) {
			$this->load->model('octeam_tools/seo_keyword_checker');
			if ($this->model_octeam_tools_seo_keyword_checker->deleteUrlAliasById($this->request->post['url_alias_id'])) {
				$json['success'] = $this->language->get('text_deleted');
			} else {
				$json['error'] = $this->language->get('error_url_alias_delete_error');
			}
		} else {
			$json['error'] = $this->language->get('error_no_url_alias_id');
		}
		
		/* set output */
		$this->response->setOutput(json_encode($json));
	}
	
	
	
	public function getCurrentVersion() {
		return $this->version;
	}
	
	
	/* [w] update checker data __DO_NOT_EDIT__ :: begin */
	public function checkUpdate() {
		$json = array();
		$this->load->language('octeam_tools/seo_keyword_checker');
		
		if (isset($this->updateCheckerExtensionId) && (isset($this->version) && preg_match("(\.ocs)", $this->version))){
			
			$url = $this->updateCheckerUrl."&extension_ien=".$this->updateCheckerExtensionId."&version=".$this->version."&dmn=".$this->request->server['SERVER_NAME']."&https_server=".HTTPS_CATALOG;
			$curl_url = str_replace( "&amp;", "&", urldecode(trim($url)) );
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $curl_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_REFERER, HTTPS_SERVER);
			$content = curl_exec( $ch );
			$response = curl_getinfo( $ch );
			curl_close ( $ch );
			
			$checkResult = $content;
			
			if (empty($checkResult) || is_null($checkResult)) {
				$json['error'] = $this->language->get('error_no_extension');
			} else {
				$response = json_decode($checkResult);
				if (is_null($response)) {
					$json['error'] = $this->language->get('error_check_update');
				} else {
					$json = $response;
				}
			}
		} else {
			$json['error'] = $this->language->get('error_no_extension');
		}
		
		$this->response->setOutput(json_encode($json));
	}
	/* [w] update checker data __DO_NOT_EDIT__ :: end */
}
?>