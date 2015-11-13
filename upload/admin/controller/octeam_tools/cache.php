<?php 
class ControllerOcteamToolsCache extends Controller {
    private $error = array();
    private $version = '1.2.ocs2101';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('octeam_tools/cache');
    }

    public function index() {
        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title']      = $this->language->get('heading_title');
        $data['button_image']       = $this->language->get('button_image');
        $data['button_system']      = $this->language->get('button_system');
        $data['button_back']        = $this->language->get('button_back');
        $data['text_delete']        = $this->language->get('text_delete');
        $data['text_cache']         = $this->language->get('text_cache');
        $data['text_cleared']       = $this->language->get('text_cleared');
        $data['text_title_image']   = sprintf($this->language->get('text_buttons_help'), 'image/cache/');
        $data['text_title_system']  = sprintf($this->language->get('text_buttons_help'), 'system/storage/cache/');

        $data['action'] = str_replace('&amp;', '&', $this->url->link('octeam_tools/cache/remove', 'token=' . $this->session->data['token'], 'SSL'));
        $data['back'] = $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL');
        $data['token']  = $this->session->data['token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
          'text'    => $this->language->get('text_octeam_toolset'),
          'href'    => $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->url->link('octeam_tools/cache', 'token=' . $this->session->data['token'], 'SSL')
          );
        
        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('octeam_tools/cache.tpl', $data));
    }

    public function remove() {
      $json = array();

      if ($this->user->hasPermission('modify', 'octeam_tools/cache')) {

          if (isset($this->request->get['type']) &&  $this->request->get['type'] == 'image') {
              $json['success'] = $this->cleanDirectory(DIR_IMAGE . 'cache/');
          } else if (isset($this->request->get['type']) &&  $this->request->get['type'] == 'system') {
              $json['success'] = $this->cleanDirectory(DIR_CACHE);
          } else {
              $json['success'] = $this->language->get('error_not_found');
          }

      } else {
          $json['error'] = $this->language->get('error_permission');
      }

      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'octeam_tools/cache')) {
            $this->error['warning'] = sprintf($this->language->get('error_permission'), $this->language->get('heading_title'));
        }
        return !$this->error;
    }

    protected function cleanDirectory($directory){
        if (file_exists($directory)) {
            $result = '';
            $it = new RecursiveDirectoryIterator($directory);
            $files = new RecursiveIteratorIterator($it,
                         RecursiveIteratorIterator::CHILD_FIRST);

            foreach($files as $file) {
                if (($file->getFilename() === '.') || ($file->getFilename() === '..') ||
                    ($file->getFilename() === 'index.html') || ($file->getFilename() === 'index.htm')) {
                    continue;
                }

                if ($file->isDir()){
                    @rmdir($file->getRealPath());
                    $result .= 'Remove folder `' . $file . '`' . PHP_EOL;
                } else {
                    @unlink($file->getRealPath());
                    $result .= 'Remove file `' . $file . '`' . PHP_EOL;
                }
            }

        } else {
            $result = sprintf($this->language->get('text_folder_not_found'), $directory);
        }

        return $result;
    }
}
?>