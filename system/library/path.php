<?php

class Path extends Object
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    public function parse()
    {
        //echo "21";die;
        // Stop if SEO is disabled
        if (!$this->config->get('config_seo_url')) {
            return;
        }

        // Attach the URL builder
        $this->url->addRewrite($this);

        $query_string = $this->uri->getQuery();

        $path = str_replace($this->url->getDomain(), '', rawurldecode($this->uri->toString()));
        $path = str_replace('?'.$query_string, '', $path);

        // Don't parse if home page
        if (empty($path)) {
            $this->request->get['path'] = 'common/home';

            return;
        }

        // Don't parse if path is already set
        if (!empty($this->request->get['path'])) {
            // non-SEO to SEO URLs Redirection
            if ($this->config->get('config_seo_nonseo_red')) {
                $this->checkNonseoRedirection($this->request->get['path']);
            }

            return;
        }

        // www Redirection
        if ($this->config->get('config_seo_www_red')) {
            $this->checkWwwRedirection();
        }

        // non-SEO variables
        if (!empty($query_string)) {
            $query_array = $this->uri->getQuery(true);
            $this->parseNonSeoVariables($query_array);

            if ($this->config->get('config_seo_canonical')) {
                $this->document->addLink($this->url->getDomain().$path, 'canonical');
            }
        }

        $seo_url = str_replace('index.php/', '', $path);

        $temp = explode('_', $seo_url);

        if (isset($temp[0]) && 'product' == $temp[0]) {
            $this->request->get['path'] = 'product/category&category='.$temp[1];

            return;
        }

        // Add language code to URL
        $is_lang_home = false;
        if ($this->config->get('config_seo_lang_code')) {
            $seo_url = ltrim($seo_url, '/');

            if ($seo_url == $this->session->data['language']) {
                $is_lang_home = true;
            }

            $seo_url = ltrim($seo_url, $this->session->data['language']);
            $seo_url = ltrim($seo_url, '/');
        }

        // URLs are stored without suffix in database
        if ($this->config->get('config_seo_suffix')) {
            $seo_url = rtrim($seo_url, '.html');
        }

        $parts = explode('/', $seo_url);

        // remove any empty arrays from trailing
        if (0 == utf8_strlen(end($parts))) {
            array_pop($parts);
        }

        $seo = new Seo($this->registry);

        foreach ($parts as $part) {
            $query = $seo->getAliasQuery($part);

            if (!empty($query)) {
                $url = explode('=', $query);

                switch ($url[0]) {
                    case 'product_id':
                        $this->request->get['product_id'] = $url[1];

                        if (!$this->config->get('config_seo_category')) {
                            $categories = [];

                            $category_id = $seo->getCategoryIdBySortOrder($url[1]);

                            if (!is_null($category_id)) {
                                $categories = $seo->getParentCategoriesIds($category_id);

                                $categories[] = $category_id;
                            }

                            if (!empty($categories)) {
                                $this->request->get['category'] = implode('_', $categories);
                            }
                        }
                        break;
                    case 'category_id':
                        if ('last' == $this->config->get('config_seo_category')) {
                            $categories = $seo->getParentCategoriesIds($url[1]);

                            $categories[] = $url[1];

                            if (!empty($categories)) {
                                $this->request->get['category'] = implode('_', $categories);
                            }
                        } else {
                            if (!isset($this->request->get['category'])) {
                                $this->request->get['category'] = $url[1];
                            } else {
                                $this->request->get['category'] .= '_'.$url[1];
                            }
                        }
                        break;
                    case 'manufacturer_id':
                        $this->request->get['manufacturer_id'] = $url[1];
                        break;
                    case 'information_id':
                        $this->request->get['information_id'] = $url[1];
                        break;
                    default:
                        $this->request->get['path'] = $query;
                        break;
                }
            } elseif ($is_lang_home) {
                $this->request->get['path'] = 'common/home';

                break;
            } elseif (in_array($seo_url, $this->getSeoPathList())) {
                $this->request->get['path'] = $seo_url;

                break;
            } else {
                $this->request->get['path'] = 'error/not_found';

                break;
            }
        }

        if (!isset($this->request->get['path'])) {
            if (isset($this->request->get['product_id'])) {
                $this->request->get['path'] = 'product/product';
            } elseif (isset($this->request->get['category'])) {
                $this->request->get['path'] = 'product/category';
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $this->request->get['path'] = 'product/manufacturer/info';
            } elseif (isset($this->request->get['information_id'])) {
                $this->request->get['path'] = 'information/information';
            }
        }

        unset($this->request->get['_path_']); // For B/C purpose
    }

    public function rewrite($link)
    {
        //echo "ref";
        $url = '';
        $is_home = false;

        $uri = new Uri($link);

        if ($uri->getVar('path')) {
            $seo = new Seo($this->registry);

            switch ($uri->getVar('path')) {
                case 'common/home':
                    $is_home = true;
                    break;
                case 'product/product':
                    if ($this->config->get('config_seo_category')) {
                        if ($uri->getVar('path') and ('last' == $this->config->get('config_seo_category'))) {
                            $categories = explode('_', $uri->getVar('path'));

                            $categories = [end($categories)];
                        } else {
                            $categories = [];

                            $category_id = $seo->getCategoryIdBySortOrder($uri->getVar('product_id'));

                            if (!is_null($category_id)) {
                                $categories = $seo->getParentCategoriesIds($category_id);

                                $categories[] = $category_id;

                                if ('last' == $this->config->get('config_seo_category')) {
                                    $categories = [end($categories)];
                                }
                            }
                        }

                        foreach ($categories as $category) {
                            $alias = $seo->getAlias($category, 'category');

                            if ($alias) {
                                $url .= '/'.$alias;
                            }
                        }

                        $uri->delVar('path');
                    }

                    if ($uri->getVar('product_id')) {
                        $alias = $seo->getAlias($uri->getVar('product_id'), 'product');

                        if ($alias) {
                            $url .= '/'.$alias;
                        }

                        $uri->delVar('product_id');
                        $uri->delVar('manufacturer_id');
                        $uri->delVar('path');
                        $uri->delVar('search');
                    }
                    break;
                case 'product/category':
                    if ($uri->getVar('path')) {
                        $categories = explode('_', $uri->getVar('path'));

                        foreach ($categories as $category) {
                            $alias = $seo->getAlias($category, 'category');

                            if ($alias) {
                                $url .= '/'.$alias;
                            }
                        }

                        $uri->delVar('path');
                    }

                    break;
                case 'information/information':
                    if ($uri->getVar('information_id')) {
                        $alias = $seo->getAlias($uri->getVar('information_id'), 'information');

                        if ($alias) {
                            $url .= '/'.$alias;
                        }

                        $uri->delVar('information_id');
                    }
                    break;
                case 'product/manufacturer/info':
                    if ($uri->getVar('manufacturer_id')) {
                        $alias = $seo->getAlias($uri->getVar('manufacturer_id'), 'manufacturer');

                        if ($alias) {
                            $url .= '/'.$alias;
                        }

                        $uri->delVar('manufacturer_id');
                    }
                    break;
                default:

                   // if (!$this->seoDisabled($uri->getVar('path'))) {
                   //     $url = '/' . $uri->getVar('path');
                   // }else{
                        $row = $this->db->query('select * from `'.DB_PREFIX.'url_alias` WHERE query="'.$uri->getVar('path').'"')->row;

                        if ($row) {
                            $url = '/'.$row['keyword'];
                        } else {
                            $url = '/'.$uri->getVar('path');
                        }
                  //  }

                    break;
            }

            $uri->delVar('path');
        }

        if ($url or $is_home) {
            // Add language code to URL
            if ($this->config->get('config_seo_lang_code')) {
                $url = '/'.$this->session->data['language'].$url;
            }
            $uri->delVar('lang');

            // Append the suffix if enabled
            if ($this->config->get('config_seo_suffix')) {
                $url .= '.html';
            }

            $path = $uri->getPath();

            if ($is_home or $this->config->get('config_seo_rewrite')) {
                $path = str_replace('index.php/', '', $path);
                $path = str_replace('index.php', '', $path);
            }

            $path .= $url;

            $uri->setPath($path);

            return $uri->toString();
        } else {
            return $link;
        }
    }

    public function checkNonseoRedirection($path)
    {
        if ($this->seoDisabled($path)) {
            return;
        }

        $domain = $this->url->getDomain();

        // Home page, redirect to domain with empty query
        if ('common/home' == $path) {
            $url = $this->rewrite($domain);

            $this->response->redirect($url, 301);
        } else {
            $url_data = $this->request->get;
            unset($url_data['lang']);
            unset($url_data['_path_']); // For B/C purpose

            if (!isset($url_data['path'])) {
                $url_data['path'] = 'common/home';
            }

            $query = '';
            if ($url_data) {
                $query = 'index.php?'.urldecode(http_build_query($url_data, '', '&'));
            }

            $url = $domain.$query;

            $url = $this->rewrite($url);

            $this->response->redirect($url, 301);
        }
    }

    public function checkWwwRedirection()
    {
        $redirect = false;

        $host = $this->uri->getHost();

        $www_red = $this->config->get('config_seo_www_red');
        if (('with' == $www_red) and (0 !== strpos($host, 'www'))) {
            $redirect = true;
            $this->uri->setHost('www.'.$host);
        } elseif (('non' == $www_red) and 0 === strpos($host, 'www')) {
            $redirect = true;
            $this->uri->setHost(substr($host, 4, strlen($host)));
        }

        if (false === $redirect) {
            return;
        }

        $this->response->redirect($this->uri->toString(), 301);
    }

    public function parseNonSeoVariables($query)
    {
        if (empty($query)) {
            return;
        }

        foreach ($query as $variable => $value) {
            if (is_array($value)) {
                $this->parseNonSeoVariables($value);
            } else {
                $value = urlencode($value);

                $this->request->get[$variable] = $value;
            }
        }
    }

    public function seoDisabled($path = '')
    {
        $status = false;

        if (!in_array($path, $this->getSeoPathList())) {
            $status = true;
        }

        if ((false == $status) and $this->request->isAjax()) {
            $status = true;
        }

        return $status;
    }

    public function getSeoPathList()
    {
        static $path = [];

        if (empty($path)) {
            $path[] = 'account/account';
            $path[] = 'account/address';
            $path[] = 'account/credit';
            $path[] = 'account/download';
            $path[] = 'account/forgotten';
            $path[] = 'account/login';
            $path[] = 'account/newsletter';
            $path[] = 'account/order';
            $path[] = 'account/recurring';
            $path[] = 'account/register';
            $path[] = 'account/return';
            $path[] = 'account/reward';
            $path[] = 'account/voucher';
            $path[] = 'account/wishlist';
            $path[] = 'affiliate/account';
            $path[] = 'affiliate/forgotten';
            $path[] = 'affiliate/login';
            $path[] = 'affiliate/register';
            $path[] = 'checkout/cart';
            $path[] = 'checkout/checkout';
            $path[] = 'checkout/success';
            $path[] = 'common/home';
            $path[] = 'product/store';
            $path[] = 'information/contact';
            $path[] = 'information/information';
            $path[] = 'information/sitemap';
            $path[] = 'product/category';
            $path[] = 'product/manufacturer/info';
            $path[] = 'product/product';
            $path[] = 'product/special';
        }

        return $path;
    }
}
