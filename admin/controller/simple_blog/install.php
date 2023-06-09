<?php
    class ControllerSimpleBlogInstall extends Controller {
        public function index() {
            $this->language->load('simple_blog/install');
            
            if (isset($this->error['warning'])) {
    			$data['error_warning'] = $this->error['warning'];
    		} else if (isset($this->session->data['warning']) ) {
    			$data['error_warning'] = $this->session->data['warning'];
    			unset($this->session->data['warning']);
    		}
    		else {
    			$data['error_warning'] = '';
    		}
            
            if (isset($this->session->data['success'])) {
    			$data['success'] = $this->session->data['success'];
    
    			unset($this->session->data['success']);
    		} else {
    			$data['success'] = '';
    		}
            
            $data['database_found'] = $this->validateTable();
            
            $data['error_database'] = $this->language->get('error_database');
            
            $this->response->setOutput($this->load->view('simple_blog/install.tpl', $data));
        }
        
        public function validateTable() {
               
            $table_name = $this->db->escape('simple_blog_article');
            
            $table = DB_PREFIX . $table_name;
            
    		$query = $this->db->query("SHOW TABLES LIKE '{$table}'");
               
            return $query->num_rows;      
        }
        
         public function installDatabase() {
            $this->language->load('simple_blog/install');
            
            $this->load->model('simple_blog/install');
            
            $this->model_simple_blog_install->addExtensionTables();
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
         }
        
    }
?> 