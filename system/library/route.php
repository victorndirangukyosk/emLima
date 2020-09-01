<?php

class Route extends SmartObject
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
        //echo "<pre>";print_r("parse");
        // Stop if SEO is disabled
        if (!$this->config->get('config_seo_url')) {
            return;
        }

        //echo "<pre>";print_r($this->request->get);

        // Attach the URL builder
        $this->url->addRewrite($this);

        $query_string = rawurldecode($this->uri->getQuery());
        //$query_string = $this->uri->getQuery();
        //echo "<pre>";print_r($query_string);die;
        $route = str_replace($this->url->getFullUrl(), '', rawurldecode($this->uri->toString()));
        //echo "<pre>";print_r($route);
        // echo "<pre>";print_r($query_string);
        $route = str_replace('?'.$query_string, '', $route);
        //echo "<pre>";print_r($route);die;

        // www Redirection
        if ($this->config->get('config_seo_www_red')) {
            $this->checkWwwRedirection();
        }

        // Don't parse if home page
        if (empty($route)) {
            $this->request->get['path'] = 'common/home';

            return;
        }
        // Don't parse if route is already set
        if (!empty($this->request->get['path'])) {
            //echo "cre";
            // non-SEO to SEO URLs Redirection
            //echo "cer";
            if ($this->config->get('config_seo_nonseo_red')) {
                $this->checkNonseoRedirection($this->request->get['path']);
            }

            return;
        }

        // non-SEO variables
        if (!empty($query_string)) {
            $query_array = $this->uri->getQuery(true);
            $this->parseNonSeoVariables($query_array);
            if ($this->config->get('config_seo_canonical')) {
                $canonical_link = htmlspecialchars($this->url->getDomain().$route);

                $this->document->addLink($canonical_link, 'canonical');
            }
        }

        $seo_url = str_replace('index.php', '', $route);
        $seo_url = ltrim($seo_url, '/');

        // Add language code to URL
        $is_lang_home = false;
        if ($this->config->get('config_seo_lang_code')) {
            if ($seo_url == $this->session->data['language']) {
                $is_lang_home = true;
            }

            $seo_url = ltrim($seo_url, $this->session->data['language']);
            $seo_url = ltrim($seo_url, '/');
        }

        // URLs are stored without suffix in database
        if ($this->config->get('config_seo_suffix')) {
            $seo_url = substr($seo_url, 0, -5);
        }

        $parts = explode('/', $seo_url);

        // remove any empty arrays from trailing
        if (0 == utf8_strlen(end($parts))) {
            array_pop($parts);
        }

        $seo = new Seo($this->registry);

        //$parts = $seo_url;
        //echo "<pre>";print_r($parts);die;
        foreach ($parts as $part) {
            $query = $seo->getAliasQuery($part);

            //echo "<pre>";print_r($query);

            //product/store store_id=8 category_id=6
            //http://localhost/suacompraonline/store/riesbecks/entraos-pr/alho
            //http://dev.suacompraonline.com.br/index.php?path=product/category&category=6
            //$query = null;

            if (!empty($query)) {
                $url = explode('=', $query);
                //echo "<pre>";print_r($url);
                //Array ( [0] => store_group_id [1] => 3 )
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
                                $this->request->get['path'] = implode('_', $categories);
                            }
                        }
                        break;
                    case 'category_id':

                        $this->request->get['path'] = 'product/category';

                        if (!isset($this->request->get['category'])) {
                            $this->request->get['category'] = $url[1];
                        } else {
                            //$this->request->get['path'] .= '_' . $url[1];
                            $this->request->get['category'] .= '_'.$url[1];
                        }

                        /*if ($this->config->get('config_seo_category') == 'last') {
                            $categories = $seo->getParentCategoriesIds($url[1]);

                            $categories[] = $url[1];
                            if (!empty($categories)) {
                                $this->request->get['path'] = implode('_', $categories);
                            }
                        } else {

                            //echo "cee";
                            //echo $this->request->get['path'];

                        }*/

                        break;

                        /*if ($url[0] == 'page_id') {
                        $this->request->get['page_id'] = $url[1];
                    }*/

                    case 'product_collection_id':

                        $this->request->get['path'] = 'product/collection';
                        if (!isset($this->request->get['product_collection_id'])) {
                            $this->request->get['product_collection_id'] = $url[1];
                        }

                        break;
                    case 'manufacturer_id':
                        $this->request->get['manufacturer_id'] = $url[1];
                        break;
                    case 'information_id':
                        $this->request->get['information_id'] = $url[1];
                        break;
                    case 'store_id':
                    $this->request->get['store_id'] = $url[1];
                    break;

                    case 'store_group_id':
                    $this->request->get['store_group_id'] = $url[1];
                    $this->request->get['collection_id'] = $url[1];
                    break;

                    case 'help_id':
                        $this->request->get['category_id'] = $url[1];
                    break;

                    // case 'product/store':
                    //     $this->request->get['path'] = 'product/category';
                    // break;

                    default:
                        //echo $query;
                        $this->request->get['path'] = $query;
                        //$this->request->get['path'] = 'product/category';

                    break;
                }
            } elseif ($is_lang_home) {
                $this->request->get['path'] = 'common/home';

                break;
            } elseif (in_array($seo_url, $this->getSeoRouteList())) {
                $this->request->get['path'] = $seo_url;

                break;
            } else {
                $this->request->get['path'] = 'error/not_found';

                break;
            }

            //echo "<pre>";print_r($this->request->get);
        }

        //echo "<pre>";print_r($this->request->get);die;
        if (!isset($this->request->get['path'])) {
            if (isset($this->request->get['product_collection_id'])) {
                $this->request->get['path'] = 'product/collection';
            } elseif (isset($this->request->get['product_id'])) {
                $this->request->get['path'] = 'product/product';
            } elseif (isset($this->request->get['path'])) {
                $this->request->get['path'] = 'product/category';
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $this->request->get['path'] = 'product/manufacturer/info';
            } elseif (isset($this->request->get['information_id'])) {
                $this->request->get['path'] = 'information/information';
            } elseif (isset($this->request->get['store_id'])) {
                $this->request->get['path'] = 'product/store';
            } elseif (isset($this->request->get['store_group_id'])) {
                //$this->request->get['path'] = 'store/collection?collection_id='.$this->request->get['store_group_id'];
                $this->request->get['path'] = 'store/collection';
            }
        }

        //echo "<pre>";print_r($this->request->get);die;

        unset($this->request->get['_route_']); // For B/C purpose
    }

    public function rewrite($link)
    {
        // echo "clinkr";
        // echo $link;
        $url = '';
        $is_home = false;

        // common/currency, $data['redirect']
        //$link = str_replace('amp;amp;', 'amp;', $link);

        $uri = new Uri($link);
        //echo $uri;
        if ($uri->getVar('path')) {
            $seo = new Seo($this->registry);

            //echo $uri->getVar('path');

            switch ($uri->getVar('path')) {
                case 'common/home/index':
                    $is_home = true;
                    $uri->delVar('path');
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
                    $uri->delVar('path');
                    break;
                case 'product/category':

                    //print_r($uri->getVar('path'));
                    //print_r($uri->getVar('category'));

                    // if ($uri->getVar('path')) {
                    //     $categories = explode('_', $uri->getVar('path'));

                    //     foreach ($categories as $category) {
                    //         $alias = $seo->getAlias($category, 'category');

                    //         if ($alias) {
                    //             $url .= '/' . $alias;
                    //         }
                    //     }

                    //     $uri->delVar('path');
                    // }
                    // $uri->delVar('path');

                    /*
                        My code
                    */

                        //echo "<pre>";print_r($url);die;
                    if ($uri->getVar('path')) {
                        $categories = explode('_', $uri->getVar('category'));

                        //print_r($categories);
                        foreach ($categories as $category) {
                            $alias = $seo->getAlias($category, 'category');

                            if ($alias) {
                                $url .= '/'.$alias;
                            }
                        }
                        //echo $url;
                        //print_r($this->session->data['config_store_id']);
                        if (isset($this->session->data['config_store_id'])) {
                            $store_name = $seo->getAlias($this->session->data['config_store_id'], 'store');

                            /*if ($store_name) {
                                //$url .=  $alias;
                                $url = '/store/' . $store_name .$url;
                            }*/

                            if ($store_name) {
                                $uri->delVar('category');

                                $alias1 = $seo->getAliasByQuery('product/store');

                                if ($alias1) {
                                    $url = $alias1.'/'.$store_name.$url;
                                } else {
                                    $url .= $alias.'/'.$url;
                                }
                            }

                            $uri->delVar('path');
                        }

                        $uri->delVar('path');
                        $uri->delVar('category');
                    }
                    $uri->delVar('path');

                    break;
                case 'information/information':
                if ($uri->getVar('information_id')) {
                    $alias = $seo->getAlias($uri->getVar('information_id'), 'information');

                    if ($alias) {
                        $url .= '/'.$alias;
                    }

                    $uri->delVar('information_id');
                }

                //echo "info 1";print_r($url);
                $uri->delVar('path');
                break;
                case 'product/store':

                    if ($uri->getVar('store_id')) {
                        //http://localhost/suacompraonline/index.php?path=information/locations/start&store_id=8
                        //http://localhost/suacompraonline/index.php?path=information/locations/start&store_id=19
                        //rainbow-grocery

                        $alias = $seo->getAlias($uri->getVar('store_id'), 'store');

                        if ($alias) {
                            /*$url .= '/store/' . $alias;

                            $uri->delVar('store_id');
                            $uri->delVar('path');*/

                            $uri->delVar('store_id');

                            $alias1 = $seo->getAliasByQuery($uri->getVar('path'));

                            if ($alias1) {
                                $url .= $alias1.'/'.$alias;
                            } else {
                                $url .= $alias;
                            }

                            $uri->delVar('path');
                        }
                    }
                break;

                case 'store/collection':

                    if ($uri->getVar('collection_id')) {
                        //echo "<pre>";print_r($alias);die;

                        $alias = $seo->getAlias($uri->getVar('collection_id'), 'store_group');

                        if ($alias) {
                            $url .= $alias;
                            //$url .= '/store/' . $alias;
                            $uri->delVar('collection_id');
                            $uri->delVar('path');
                        }
                    }
                break;

                case 'information/help':

                    //echo "<pre>";print_r("er");die;
                    if ($uri->getVar('category_id')) {
                        $alias = $seo->getAlias($uri->getVar('category_id'), 'help');

                        if ($alias) {
                            $uri->delVar('category_id');

                            $alias1 = $seo->getAliasByQuery($uri->getVar('path'));

                            $url .= $alias1.'/'.$alias;
                        }

                        $uri->delVar('path');
                    } else {
                        $alias = $seo->getAliasByQuery($uri->getVar('path'));

                        if ($alias) {
                            $url .= $alias;
                        }
                        $uri->delVar('path');
                    }

                    //$uri->delVar('category_id');
                    //$uri->delVar('path');
                break;
                case 'product/manufacturer/info':
                    if ($uri->getVar('manufacturer_id')) {
                        $alias = $seo->getAlias($uri->getVar('manufacturer_id'), 'manufacturer');

                        if ($alias) {
                            $url .= '/'.$alias;
                        }

                        $uri->delVar('manufacturer_id');
                    }
                    $uri->delVar('path');
                    break;
                default:

                    if (!$this->seoDisabled($uri->getVar('path'))) {
                        //print_r($uri->getVar('path'));
                        $alias = $seo->getAliasByQuery($uri->getVar('path'));
                        //print_r($alias);
                        if ($alias) {
                            $url .= $alias;
                        }

                        /*if($uri->getVar('category_id')) {
                            $alias = $seo->getAliasByQuery($uri->getVar('path').'&category_id='.$uri->getVar('category_id'));

                            //print_r($alias);die;
                            if ($alias) {
                                //$url .= '/' . $alias;
                                $url .= $alias;
                            }
                            $uri->delVar('category_id');

                        } else {
                            $alias = $seo->getAliasByQuery($uri->getVar('path'));
                            if ($alias) {
                                $url .= $alias;
                            }
                        }*/

                        //echo "<pre>";print_r("rewrite");print_r($url);print_r("rewrite"); print($uri->getVar('path'));
                    }
                    //echo "info 2";
                    $uri->delVar('path');
                    break;
            }

            //echo "info 3";
            //$uri->delVar('path');
            //echo "<pre>rewind";print_r($uri);
        }

        //echo "<pre>";print_r("rewrite");print_r($url);
        if ($is_home) {
            // Add language code to URL
            if ($this->config->get('config_seo_lang_code')) {
                $url = '/'.$this->session->data['language'].$url;
            }
            $uri->delVar('lang');

            // Append the suffix if enabled
            if ($this->config->get('config_seo_suffix') && !$is_home) {
                $url .= '.html';
            }

            $path = $uri->getPath();
            //if ($this->config->get('config_seo_rewrite') || ($is_home && !$this->config->get('config_seo_lang_code'))) {
            if ($this->config->get('config_seo_url') || ($is_home && !$this->config->get('config_seo_lang_code'))) {
                $path = str_replace('index.php/', '', $path);
                $path = str_replace('index.php', '', $path);
            }
            $path .= $url;

            $uri->setPath($_SERVER['SCRIPT_NAME']);

            return $uri->toString();
        } elseif ($url) {
            // Add language code to URL
            if ($this->config->get('config_seo_lang_code')) {
                $url = '/'.$this->session->data['language'].$url;
            }
            $uri->delVar('lang');

            // Append the suffix if enabled
            if ($this->config->get('config_seo_suffix') && !$is_home) {
                $url .= '.html';
            }

            $path = $uri->getPath();
            //if ($this->config->get('config_seo_rewrite') || ($is_home && !$this->config->get('config_seo_lang_code'))) {
            if ($this->config->get('config_seo_url') || ($is_home && !$this->config->get('config_seo_lang_code'))) {
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

    public function checkNonseoRedirection($route)
    {
        //echo "checkNonseoRedirection";die;

        if ($this->seoDisabled($route)) {
            return;
        }

        $domain = $this->url->getDomain();

        // Home page, redirect to domain with empty query
        if ('common/home' == $route) {
            $url = $this->rewrite($domain);

            $this->response->redirect($url, 301);
        } else {
            $url_data = $this->request->get;
            unset($url_data['lang']);
            unset($url_data['_route_']); // For B/C purpose

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

    public function seoDisabled($route = '')
    {
        $status = false;

        if (!in_array($route, $this->getSeoRouteList())) {
            $status = true;
        }

        /*if (($status == false) and $this->request->isAjax()) {
            $status = true;
        }*/

        return $status;
    }

    public function getSeoRouteList()
    {
        static $route = [];

        if (empty($route)) {
            $route[] = 'common/home';
            $route[] = 'blog/category';
            $route[] = 'blog/article';
            $route[] = 'blog/article/view';
            $route[] = 'information/help';
            $route[] = 'information/locations/start';
            $route[] = 'information/locations/stores';
            $route[] = 'information/locations';
            $route[] = 'information/enquiries';
            $route[] = 'information/shopper';
            $route[] = 'information/contact';
            $route[] = 'information/information';
            $route[] = 'information/sitemap';
            $route[] = 'information/information/agree';
            $route[] = 'information/shopper/success';
            $route[] = 'information/enquiries/success';
            $route[] = 'product/recipe';
            $route[] = 'product/store';
            $route[] = 'product/offers';
            // $route[] = 'product/search';
            $route[] = 'product/category';
            $route[] = 'product/manufacturer/info';
            $route[] = 'product/product';
            $route[] = 'product/special';
            $route[] = 'account/member/payu';
            $route[] = 'account/address/add';
            $route[] = 'account/member';
            $route[] = 'account/edit';
            $route[] = 'account/logout';
            $route[] = 'account/success';
            $route[] = 'account/password';
            $route[] = 'account/refer';
            $route[] = 'account/account';
            $route[] = 'account/invite';
            $route[] = 'account/address';
            $route[] = 'account/credit';
            $route[] = 'account/download';
            $route[] = 'account/forgotten';
            //$route[] = 'account/login';
            $route[] = 'account/newsletter';
            $route[] = 'account/order';
            $route[] = 'account/recurring';
            $route[] = 'account/register';
            $route[] = 'account/return';
            $route[] = 'account/reward';
            $route[] = 'account/voucher';
            $route[] = 'account/wishlist';
            $route[] = 'account/wishlist/info';

            $route[] = 'account/order/info';
            $route[] = 'account/order/reorder';
            $route[] = 'account/return/add';
            $route[] = 'account/refer/success';
            $route[] = 'account/return/success';
            $route[] = 'account/address/edit';
            $route[] = 'account/address/delete';
            $route[] = 'affiliate/account';
            $route[] = 'affiliate/forgotten';
            $route[] = 'affiliate/login';
            $route[] = 'affiliate/register';
            $route[] = 'checkout/cart';
            $route[] = 'checkout/failure';
            $route[] = 'checkout/success';
            $route[] = 'checkout/checkout';
            $route[] = 'checkout/login';
            $route[] = 'account/facebook';
            //$route[] = 'deliversystem/deliversystem';
            $route[] = 'account/google';
            $route[] = 'checkout/checkout/checkForMinimuOrderAmount';
        }

        return $route;
    }
}
