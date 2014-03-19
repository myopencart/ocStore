<?php
class ControllerFeedGoogleSitemapFast extends Controller {
	protected $categories = array();

	public function index() {
		if($this->config->get('google_sitemap_status')) {
			$output = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			$this->load->model('tool/sitemap');

			$products = $this->model_tool_sitemap->getProducts();

			foreach($products as $product) {
				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $product['product_id']))) . '</loc>';
				$output .= '<lastmod>' . $product['date'] . '</lastmod>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
			}

			$this->categories = $this->model_tool_sitemap->getAllCategories();

			$output .= $this->getAllCategories(0);

			$manufacturers = $this->model_tool_sitemap->getManufacturers();

			foreach($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']))) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';
			}


			$informations = $this->model_tool_sitemap->getInformations();

			foreach($informations as $information) {
				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('information/information', 'information_id=' . $information['information_id']))) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';
			}

			$output .= '</urlset>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}

	protected function getAllCategories($parent_id = 0, $current_path = '') {
		$output = '';

		if(array_key_exists($parent_id, $this->categories)) {
			$results = $this->categories[$parent_id];

			foreach($results as $result) {
				if(!$current_path) {
					$new_path = $result['category_id'];
				} else {
					$new_path = $current_path . '_' . $result['category_id'];
				}

				$output .= '<url>';
				$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $new_path))) . '</loc>';
				$output .= '<lastmod>' . $result['date'] . '</lastmod>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';

				if(!$current_path) {
					$new_path = $result['category_id'];
				} else {
					$new_path = $current_path . '_' . $result['category_id'];
				}

				$children = '';

				if(array_key_exists($result['category_id'], $this->categories)) {
					$children = $this->getAllCategories($result['category_id'], $new_path);
				}

				$output .= $children;

			}
		}
		return $output;
	}
}
?>