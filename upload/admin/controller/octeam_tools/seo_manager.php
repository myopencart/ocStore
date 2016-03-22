<?php
class ControllerOcteamToolsSeoManager extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('octeam_tools/seo_manager');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('octeam_tools/seo_manager');
        $this->getForm();
    }

    protected function getForm() {
        $filter_query = isset($this->request->get['filter_query']) ? $this->request->get['filter_query'] : null;
        $filter_keyword = isset($this->request->get['filter_keyword']) ? $this->request->get['filter_keyword'] : null;

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'query';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_query'])) {
          $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_keyword'])) {
          $url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_octeam_toolset'),
            'href' => $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
       
        $data['delete'] = $this->url->link('octeam_tools/seo_manager/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['save'] = $this->url->link('octeam_tools/seo_manager/update', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['clear'] = $this->url->link('octeam_tools/seo_manager/clear', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['cancel'] = $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL');

        $data['url_aliases'] = array();

        $filter_data = array(
            'filter_query'    => $filter_query,
            'filter_keyword'  => $filter_keyword,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        $url_alias_total = $this->model_octeam_tools_seo_manager->getTotalUrlAalias($filter_data);

        $results = $this->model_octeam_tools_seo_manager->getUrlAaliases($filter_data);

        foreach ($results as $result) {
            $data['url_aliases'][] = array(
                'url_alias_id' => $result['url_alias_id'], 
                'query' => $result['query'],
                'keyword' => $result['keyword'],
                'selected' => isset($this->request->post['selected']) && in_array($result['url_alias_id'], $this->request->post['selected']), 
                'action_text' => $this->language->get('text_edit')
            );
         }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_filter_query'] = $this->language->get('column_filter_query');
        $data['column_filter_keyword'] = $this->language->get('column_filter_keyword');
        $data['column_query'] = $this->language->get('column_query');
        $data['column_keyword'] = $this->language->get('column_keyword');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_clear_cache'] = $this->language->get('button_clear_cache');

        $data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
            unset($this->error);
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_query'])) {
          $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_keyword'])) {
          $url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_query'] = $this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'] . '&sort=query' . $url, 'SSL');
        $data['sort_keyword'] = $this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'] . '&sort=keyword' . $url, 'SSL');

        $url = '';

        if (isset($this->request->get['filter_query'])) {
          $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_keyword'])) {
          $url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $url_alias_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($url_alias_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($url_alias_total - $this->config->get('config_limit_admin'))) ? $url_alias_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $url_alias_total, ceil($url_alias_total / $this->config->get('config_limit_admin')));

        $data['filter_query'] = $filter_query;
        $data['filter_keyword'] = $filter_keyword;
        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('octeam_tools/seo_manager.tpl', $data));
    }

        public function update() {
                $this->load->language('octeam_tools/seo_manager');
                $this->document->setTitle($this->language->get('heading_title'));
                $this->load->model('octeam_tools/seo_manager');

                $url = '';

                if (isset($this->request->get['filter_query'])) {
                  $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->get['filter_keyword'])) {
                  $url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
                }

                if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                }

                if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                        $this->model_octeam_tools_seo_manager->updateUrlAlias($this->request->post);
                        $this->session->data['success'] = $this->language->get('text_success');
                    
                    $this->response->redirect($this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
                
                $this->getForm();
        }
        
        public function clear() {
            $this->load->language('octeam_tools/seo_manager');
                $url = '';

                if (isset($this->request->get['filter_query'])) {
                  $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->get['filter_keyword'])) {
                  $url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
                }

                if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                }
                $this->cache->delete('seo_pro');
                $this->cache->delete('seo_url');

                $this->session->data['success'] = $this->language->get('text_success_clear');
                $this->response->redirect($this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

    public function delete() {
                $this->load->language('octeam_tools/seo_manager');
                $this->load->model('octeam_tools/seo_manager');
                $url = '';

                if (isset($this->request->get['filter_query'])) {
                  $url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->get['filter_keyword'])) {
                  $url .= '&filter_keyword=' . $this->request->get['filter_keyword'];
                }

                if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                        $url .= '&page=' . $this->request->get['page'];
                }

                if (isset($this->request->post['selected']) && $this->validate()) {
                        foreach ($this->request->post['selected'] as $url_alias_id) {
                                $this->model_octeam_tools_seo_manager->deleteUrlAlias($url_alias_id);
                        }
                        $this->session->data['success'] = $this->language->get('text_success');
                }

        $this->response->redirect($this->url->link('octeam_tools/seo_manager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    }

    public function validate() {
        if (!$this->validatePermission()) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateForm() {
        if (!$this->validatePermission()) {
            $this->error['warning'] = $this->language->get('error_permission');
            return !$this->error;
        }

        if ((utf8_strlen($this->request->post['query']) < 3) || (utf8_strlen($this->request->post['query']) > 255)) {
            $this->error['warning'] = $this->language->get('error_query');
        }

        $this->load->model('catalog/url_alias');

        $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

        if ($url_alias_info && ($url_alias_info['query'] != $this->request->post['query'])) {
            $this->error['warning'] = sprintf($this->language->get('error_keyword'));
        }

        return !$this->error;
    }

    protected function validatePermission() {
        return $this->user->hasPermission('modify', 'octeam_tools/seo_manager');
    }
}