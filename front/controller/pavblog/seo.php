<?php

/******************************************************
 * @package Pav blog module for Opencart 1.5.x
 * @version 1.1
 *
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

    /**
     * class ControllerPavblogSeo inherit core functions of SEO URL class (in common/seo_url.php)
     * Then improving detections to process SEO for pavo blog modules.
     * To use this you should disable Opencart SEO by comment $controller->addPreAction(new Action('common/seo_url')); in index.php file
     * And Then add This in next line: $controller->addPreAction(new Action('pavblog/seo'));.
     */
    class ControllerPavblogSeo extends Controller
    {
        /**
         * Add Hanlder to detect url getting parameters to build real url of category and blog page.
         */
        public function index()
        {
            // Add rewrite to url class

            $this->load->model('setting/setting');

            if ($this->config->get('config_seo_url')) {
                $this->url->addRewrite($this);
            }

            // Decode URL
            if (isset($this->request->get['_path_'])) {
                $parts = explode('/', $this->request->get['_path_']);

                /** BEGIN PROCESSING TO DECORD REQUET SEO URL FOR  PAVO BLOG MODULE **/
                $blogConfig = $this->config->get('pavblog');
                $seo = isset($blogConfig['keyword_listing_blogs_page']) ? trim($blogConfig['keyword_listing_blogs_page']) : 'blogs';

                if ($this->request->get['_path_'] == $seo) {
                    $this->request->get['path'] = 'pavblog/blogs';

                    return $this->forward($this->request->get['path']);
                }
                /* END OF PROCESSING TO DECORD REQUET SEO URL FOR  PAVO BLOG MODULE **/

                foreach ($parts as $part) {
                    $query = $this->model_setting_setting->getUrlAlias($part);

                    if ($query->num_rows) {
                        $url = explode('=', $query->row['query']);

                        if ('product_id' == $url[0]) {
                            $this->request->get['product_id'] = $url[1];
                        }

                        if ('category_id' == $url[0]) {
                            if (!isset($this->request->get['category'])) {
                                $this->request->get['category'] = $url[1];
                            } else {
                                $this->request->get['category'] .= '_'.$url[1];
                            }
                        }

                        if ('manufacturer_id' == $url[0]) {
                            $this->request->get['manufacturer_id'] = $url[1];
                        }

                        if ('information_id' == $url[0]) {
                            $this->request->get['information_id'] = $url[1];
                        }

                        /* BEGIN PROCESSING TO DECORD REQUET SEO URL FOR  PAVO BLOG MODULE **/
                        if (2 == count($url) && (preg_match('#pavblog#', $url[0]))) {
                            $this->request->get['path'] = $url[0];
                            $this->request->get['id'] = $url[1];
                        }
                        /* END OF PROCESSING TO DECORD REQUET SEO URL FOR  PAVO BLOG MODULE **/
                    } else {
                        $this->request->get['path'] = 'error/not_found';
                    }
                }

                if (isset($this->request->get['product_id'])) {
                    $this->request->get['path'] = 'product/product';
                } elseif (isset($this->request->get['category'])) {
                    $this->request->get['path'] = 'product/category';
                } elseif (isset($this->request->get['manufacturer_id'])) {
                    $this->request->get['path'] = 'product/manufacturer/info';
                } elseif (isset($this->request->get['information_id'])) {
                    $this->request->get['path'] = 'information/information';
                }

                if (isset($this->request->get['path'])) {
                    return $this->forward($this->request->get['path']);
                }
            }
        }

        public function rewrite($link)
        {
            $url_info = parse_url(str_replace('&amp;', '&', $link));
            $url = '';
            $data = [];

            parse_str($url_info['query'], $data);

            foreach ($data as $key => $value) {
                if (isset($data['path'])) {
                    if (('product/product' == $data['path'] && 'product_id' == $key) || (('product/manufacturer/info' == $data['path'] || 'product/product' == $data['path']) && 'manufacturer_id' == $key) || ('information/information' == $data['path'] && 'information_id' == $key)) {
                        $query = $this->model_setting_setting->getUrlAliasKeyValue($key, $value);

                        if ($query->num_rows) {
                            $url .= '/'.$query->row['keyword'];

                            unset($data[$key]);
                        }
                    } elseif ('path' == $key) {
                        $categories = explode('_', $value);

                        foreach ($categories as $category) {
                            $query = $this->model_setting_setting->getUrlAliasByCatogoryId($category);

                            if ($query->num_rows) {
                                $url .= '/'.$query->row['keyword'];
                            }
                        }

                        unset($data[$key]);
                    }
                }
            }

            /* BEGIN PROCESSING TO REWRITE SEO URL FOR  PAVO BLOG MODULE **/
            if ((preg_match('#pavblog#', $data['path'])) && isset($data['id'])) {
                $query = $this->model_setting_setting->getUrlAliasByPathAndId($data['path'], $data['id']);

                if ($query->num_rows) {
                    $url .= '/'.$query->row['keyword'];
                    unset($data[$key]);
                    unset($data['id']);
                }

                if (preg_match('#pavblog/category#', $data['path']) && preg_match("#\{page\}#", $url_info['query']) && !isset($data['page'])) {
                    $data['page'] = '{page}';
                }
            } elseif ('pavblog/blogs' == $data['path']) {
                $blogConfig = $this->config->get('pavblog');
                $seo = isset($blogConfig['keyword_listing_blogs_page']) ? trim($blogConfig['keyword_listing_blogs_page']) : 'blogs';
                $url .= '/'.$seo;
            }
            /* END OF PROCESSING SEO URL FOR PAVO BLOG MODULE **/
            if ($url) {
                unset($data['path']);

                $query = '';

                if ($data) {
                    foreach ($data as $key => $value) {
                        $query .= '&'.$key.'='.$value;
                    }

                    if ($query) {
                        $query = '?'.trim($query, '&');
                    }
                }

                return $url_info['scheme'].'://'.$url_info['host'].(isset($url_info['port']) ? ':'.$url_info['port'] : '').str_replace('/index.php', '', $url_info['path']).$url.$query;
            } else {
                return $link;
            }
        }
    }
