<?php 
	/******************************************************
	 * @package Pav blog module for Opencart 1.5.x
	 * @version 1.0
	 * @author http://www.pavothemes.com
	 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
	 * @license		GNU General Public License version 2
	*******************************************************/

/**
 * class ControllerPavblogCategory 
 */
	class ControllerPavblogCategory extends Controller {
	
		private $mparams = '';
		
		/**
		 *
		 */
		public function preload(){
			$this->load->model('pavblog/blog');
			$this->load->model('pavblog/comment');
			$this->load->model('tool/image'); 	
			$mparams = $this->config->get( 'pavblog' );
			$default = $this->model_pavblog_blog->getDefaultConfig();
			
			$mparams = !empty($mparams)?$mparams:array();

			if( $mparams ){
				$mparams =  array_merge( $default,$mparams);
			}else{
				$mparams = $default;
			}
			$config = new Config();
			if( $mparams ){
				foreach( $mparams as $key => $value ){
					$config->set( $key, $value );
				}
			}
			$this->mparams = $config; 
			if( $this->mparams->get('comment_engine') == '' ||  $this->mparams->get('comment_engine') == 'local' ) {
			}else {			
				$this->mparams->set( 'blog_show_comment_counter', 0 );	
				$this->mparams->set( 'cat_show_comment_counter', 0 );	
			}	
			
			
			$this->language->load('module/pavblog');
			$this->load->model("pavblog/category");
			if( !defined("_PAVBLOG_MEDIA_") ){
				if (file_exists('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/pavblog.css')) {
					$this->document->addStyle('front/ui/theme/' . $this->config->get('config_template') . '/stylesheet/pavblog.css');
				} else {
					$this->document->addStyle('front/ui/theme/default/stylesheet/pavblog.css');
				}
				define("_PAVBLOG_MEDIA_",true);
			}
		}
		
		/**
		 * get object model
		 */
		public function getModel($model='category'){
			return $this->{"model_pavblog_{$model}"};
		}
		
		public function getImageType(){
		
		}
		
		/**
		 * index action
	     *
		 */
		public function index() {  
		
			$this->preload();
			
			
			$this->load->model('pavblog/blog');
			
			if (isset($this->request->get['filter'])) {
				$filter = $this->request->get['filter'];
			} else {
				$filter = '';
			}
					
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'p.sort_order';
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
								
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit =  (int)$this->mparams->get( 'cat_limit_leading_blog' ) +  (int)$this->mparams->get( 'cat_limit_secondary_blog' );
			}
			
			$this->data['breadcrumbs'] = array();
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);
			if( !isset($this->request->get['id']) ){
				$this->request->get['id'] = null;
			}
			
			
			$parts = explode('_', (string)$this->request->get['id']);
			$category_id = (int)array_pop($parts);
			
			$category_info = $this->getModel()->getInfo( $category_id );	
			$children = $this->getModel()->getChildren( $category_id );
			
		
			foreach( $children as $key => $sub ){
				$sub['description'] = html_entity_decode($sub['description'], ENT_QUOTES, 'UTF-8');
				if( $sub['image'] ){
					$sub['thumb'] = $this->model_tool_image->resize($sub['image'], $this->mparams->get('general_cwidth'), $this->mparams->get('general_cheight') ,'w');
				}else {
					$sub['thumb'] = '';
				}		
				$data = array(
					'filter_category_id' =>$sub['category_id'] 
				);
				
				$sub['count_blogs']	 = $this->getModel( 'blog' )->getTotal( $data );
				$sub['link']  =  $this->url->link( 'pavblog/category', 'id=' .  $sub['category_id'] );
				
				$children[$key]=$sub;
			}
		//	echo '<pre>'.print_r( $children,1 ); die;
			$this->data['children'] = $children; 

			$data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter, 
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$blogs = $this->getModel('blog')->getListBlogs(  $data );
		
			$users = $this->getModel()->getUsers();
			
			$count = count( $blogs ); 
			if ($category_info) {
				$total = $this->getModel( 'blog' )->getTotal( $data );
				
				$title = $category_info['meta_title'] ? $category_info['meta_title']:$category_info['title']; 
				$this->document->setTitle( $title ); 

				$this->data['breadcrumbs'][] = array(
					'text'      => $category_info['title'],
					'href'      => $this->url->link('pavblog/category', 'id=' .  $category_id),      		
					'separator' => $this->language->get('text_separator')
				);		
				

				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}	
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				
				$this->data['heading_title'] = $category_info['title'];
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
				
				$this->data['continue'] = $this->url->link('common/home');
				$limit_leading_blogs = (int)$this->mparams->get( 'cat_limit_leading_blog' );

				$type = array('l'=>'thumb_large','s'=>'thumb_small');
				
				$limageType = isset($type[$this->mparams->get('cat_leading_image_type')])?$type[$this->mparams->get('cat_leading_image_type')]:'thumb_xsmall';
				$simageType = isset($type[$this->mparams->get('cat_secondary_image_type')])?$type[$this->mparams->get('cat_secondary_image_type')]:'thumb_xsmall';
				
	
				foreach( $blogs as $key => $blog ){
					if( $blogs[$key]['image'] ){	
						$blogs[$key]['thumb_large'] = $this->model_tool_image->resize($blog['image'], $this->mparams->get('general_lwidth'), $this->mparams->get('general_lheight'),'w' );
						$blogs[$key]['thumb_small'] = $this->model_tool_image->resize($blog['image'], $this->mparams->get('general_swidth'), $this->mparams->get('general_sheight') ,'w' );
						$blogs[$key]['thumb_xsmall'] = $this->model_tool_image->resize($blog['image'],$this->mparams->get('general_xwidth'), $this->mparams->get('general_xheight') ,'w' );
					}else {
						$blogs[$key]['thumb_large'] = '';
						$blogs[$key]['thumb_small'] = '';
						$blogs[$key]['thumb_xsmall'] = '';
					}
					if( $key < $limit_leading_blogs ){
						$blogs[$key]['thumb'] = $blogs[$key][$limageType];
					}else {
						$blogs[$key]['thumb'] = $blogs[$key][$simageType];
					}					
					
					$blogs[$key]['description'] = html_entity_decode($blog['description'], ENT_QUOTES, 'UTF-8');
					$blogs[$key]['author'] = isset($users[$blog['user_id']])?$users[$blog['user_id']]:$this->language->get('text_none_author');
					$blogs[$key]['category_link'] =  $this->url->link( 'pavblog/category', "id=".$blog['category_id'] );
					
					if( $this->mparams->get( 'cat_show_comment_counter' )  ) {	 
						$blogs[$key]['comment_count'] =  $this->getModel('comment')->countComment( $blog['blog_id'] );
					}else {
						$blogs[$key]['comment_count'] = 0;
					}
					
					$blogs[$key]['link'] =  $this->url->link( 'pavblog/blog','id='.$blog['blog_id'] );
				}
				
				
				$leading_blogs 		 = array_slice( $blogs,0, $limit_leading_blogs );
				$secondary_blogs 	 = array_splice( $blogs, $limit_leading_blogs, count($blogs) );
		
				
				$this->data['total'] = $total;
			 	$this->data['config'] = $this->mparams;
				$this->data['leading_blogs'] = $leading_blogs;
				$this->data['secondary_blogs'] = $secondary_blogs;
				$this->data['category_rss'] =  $this->url->link( 'pavblog/category/rss', "id=".$category_id );
				
				$pagination = new Pagination();
				$pagination->total = $total;
				$pagination->page = $page;
				$pagination->limit =  $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('pavblog/category', 'id=' . $this->request->get['id'] . $url . '&page={page}');
				
				$this->data['pagination'] = $pagination->render();
 
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/pavblog/category.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/pavblog/category.tpl';
				} else {
					$this->template = 'default/template/pavblog/category.tpl';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
							
				$this->response->setOutput($this->render());
			} else {
				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_error'),
					'href'      => $this->url->link('information/information', 'category_id=' . $category_id),
					'separator' => $this->language->get('text_separator')
				);
					
				$this->document->setTitle($this->language->get('text_error'));
				
				$this->data['heading_title'] = $this->language->get('text_error');

				$this->data['text_error'] = $this->language->get('text_error');

				$this->data['button_continue'] = $this->language->get('button_continue');

				$this->data['continue'] = $this->url->link('common/home');

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
						
				$this->response->setOutput($this->render());
			}
		}
		
		/**
		 * get rss feed by category id 
		 */
		public function rss(){
			
			$this->preload();
			if( isset($this->request->get['id']) ){
				$id = (int)$this->request->get['id'];
			} else {
				$id = 0;
			}
			
			if ($this->request->server['HTTPS']) {
	            $server = $this->config->get('config_ssl');
	        } else {
	            $server = $this->config->get('config_url');
	        }

			$category_info = $this->getModel()->getInfo( $id );	
			
			$output = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0">';
			$output .= '<channel>';

			$output .= '<title><![CDATA[' . $category_info['title'] . " - " . $this->config->get('config_name') . ']]></title>';
			$output .= '<description><![CDATA[' . $this->config->get('config_meta_description') . ']]></description>';
			$output .= '<link><![CDATA[' . $server . ']]></link>';
			
			$page = 1;
			$limit = (int)$this->mparams->get('rss_limit_item')?(int)$this->mparams->get('rss_limit_item'):100;
			
			$data = array(
				'filter_category_id' => $id,
				'sort'               => 'created',
				'order'              => 'ASC',
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$blogs = $this->getModel('blog')->getListBlogs(  $data );
			

			foreach( $blogs as $blog ){
				$link =  str_replace("&amp;","&",$this->url->link( 'pavblog/blog','id='.$blog['blog_id'] ));
				if( $blog['image'] ){
					$image = $this->model_tool_image->resize($blog['image'], $this->mparams->get('general_swidth'), $this->mparams->get('general_sheight') ,'w' );
					$description = '<a href="'.$link.'"><img class="rss_blog_image" src="'.$image.'"/></a>'.  html_entity_decode($blog['description'], ENT_QUOTES, 'UTF-8'); 
				}else {
					$description =  html_entity_decode($blog['description'], ENT_QUOTES, 'UTF-8');
				} 
			
				$output .= '<item>';
				$output .= '<title><![CDATA[' . $blog['title'] . ']]></title>';
				$output .= '<link><![CDATA[' .$link. ']]></link>';
				$output .= '<description><![CDATA[' . $description . ']]></description>';
				$output .= '<guid>' . $blog['blog_id'] . '</guid>';
				$output .= '<pubDate>' . date('D, j F Y H:i:s e', strtotime($blog['created'])) . '</pubDate>';
				$output .= '</item>';
			}
			$output .= '</channel>';
			$output .= '</rss>';
			$this->response->addHeader('Content-Type: application/rss+xml');
			$this->response->setOutput($output);
		}
		
	}	
	?>