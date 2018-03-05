<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends Front_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->language('search');
        $this->load->helper('text');
    }

    public function pre_search()
    {
        $search = strip_tags($this->input->get('search', true));
        $data['brands'] = $this->product_model->get_brands($search);

        if (count($data['brands']) == 1) {
            redirect('/search?search=' . $data['brands'][0]['sku'] . '&ID_art=' . $data['brands'][0]['ID_art'] . '&brand=' . $data['brands'][0]['brand']);
        }

        $this->setH1(sprintf(lang('text_search_pre_search_h1'), $search));
        $this->setTitle(sprintf(lang('text_search_pre_search_title'), $search));
        $this->setDescription(sprintf(lang('text_search_pre_search_description'), $search));
        $this->setKeywords(sprintf(lang('text_search_pre_search_keywords'), $search));

        $this->load->view('header');
        $this->load->view('search/pre_search', $data);
        $this->load->view('footer');
    }


    public function index()
    {
        $search = strip_tags($this->input->get('search', true));

        $brand = $this->input->get('brand', true);

        $brands = $this->product_model->get_brands($search);

        if (!$brand && $brands) {
            redirect('search/pre_search?search=' . $search);
        }


        $ID_art = (int)$this->input->get('ID_art');

        if (!$ID_art && $brand && $search) {
            $tecdoc_id_art = $this->tecdoc->getIDart($this->product_model->clear_sku($search), $brand);
            if ($tecdoc_id_art) {
                $ID_art = $tecdoc_id_art[0]->ID_art;
            }
        }

        $this->load->library('user_agent');
        //Если это не робот и не админ, пишем историю поиска в базу данных
        if (!$this->agent->is_robot() && !$this->is_admin) {
            $this->load->model('search_history_model');
            $search_history = [
                'customer_id' => (int)$this->is_login,
                'sku' => (string)$search,
                'brand' => (string)$brand
            ];
            $this->search_history_model->insert($search_history);
        }

        $crosses_search = array();

        $system_cross = $this->product_model->get_crosses($ID_art, $brand, $search);

        if ($system_cross) {
            $crosses_search = $system_cross;
        }

        $cross_suppliers = $this->product_model->api_supplier($this->product_model->clear_sku($search), $brand, $crosses_search);

        if ($cross_suppliers) {
            foreach ($cross_suppliers as $cross_supplier) {
                $crosses_search = array_merge($crosses_search, $cross_supplier);
            }
        }

        $crosses_search = array_unique($crosses_search, SORT_REGULAR);

        $data['products'] = [];
        $data['filter_brands'] = [];

        $data['min_price'] = false;
        $data['min_price_cross'] = false;
        $data['min_term'] = false;

        if ($brand && $search) {
            $product = $this->product_model->get_search_products($search, $brand);
            if ($product && $product['prices']) {
                $product['is_cross'] = false;
                $tecdoc_info = $this->product_model->tecdoc_info($product['sku'], $product['brand']);

                //Если активна опция использовать наименования с текдок
                if ($this->options['use_tecdoc_name'] && @$tecdoc_info['article']['Name']) {
                    $product['name'] = @$tecdoc_info['article']['Name'];
                }


                if(!$product['image']){
                    $product['image'] =  @$tecdoc_info['article']['Image'];
                }else{
                    $product['image'] = '/uploads/product/'.$product['image'];
                }

                $product['info'] = @$tecdoc_info['article']['Info'];

                $filter_brands[] = $product['brand'];
                foreach ($product['prices'] as &$price) {
                    $p = $price['saleprice'] > 0 ? $price['saleprice'] : $price['price'];

                    if(!$data['min_price'] || $p < $data['min_price']['price']){
                        $data['min_price'] = [
                            'id' => $product['id'],
                            'supplier_id' => $price['supplier_id'],
                            'slug' => $product['slug'],
                            'sku' => $product['sku'],
                            'brand' => $product['brand'],
                            'name' => $product['name'],
                            'image' => $product['image'],
                            'price' => $p,
                            'term' => $price['term'],
                            'key' => $product['id'] . $price['supplier_id'] . $price['term']
                        ];
                    }

                    if(!$data['min_term'] || $price['term'] < $data['min_term']['term']){
                        $data['min_term'] = [
                            'id' => $product['id'],
                            'supplier_id' => $price['supplier_id'],
                            'slug' => $product['slug'],
                            'sku' => $product['sku'],
                            'brand' => $product['brand'],
                            'name' => $product['name'],
                            'image' => $product['image'],
                            'price' => $p,
                            'term' => $price['term'],
                            'key' => $product['id'] . $price['supplier_id'] . $price['term']
                        ];
                    }

                    $price['key'] = $product['id'] . $price['supplier_id'] . $price['term'];
                }

                $data['products'][] = $product;
            }
        } else {
            $products = $this->product_model->get_search_text($search);
            if ($products) {
                foreach ($products as $product) {
                    if ($product['prices']) {
                        $product['is_cross'] = 2;
                        $tecdoc_info = $this->product_model->tecdoc_info($product['sku'], $product['brand']);

                        //Если активна опция использовать наименования с текдок
                        if ($this->options['use_tecdoc_name'] && @$tecdoc_info['article']['Name']) {
                            $product['name'] = @$tecdoc_info['article']['Name'];
                        }


                        if(!$product['image']){
                            $product['image'] =  @$tecdoc_info['article']['Image'];
                        }else{
                            $product['image'] = '/uploads/product/'.$product['image'];
                        }

                        $product['info'] = @$tecdoc_info['article']['Info'];

                        $filter_brands[] = $product['brand'];
                        foreach ($product['prices'] as &$price) {
                            $p = $price['saleprice'] > 0 ? $price['saleprice'] : $price['price'];

                            if(!$data['min_price_cross'] || $p < $data['min_price_cross']['price']){
                                $data['min_price'] = [
                                    'id' => $product['id'],
                                    'supplier_id' => $price['supplier_id'],
                                    'slug' => $product['slug'],
                                    'sku' => $product['sku'],
                                    'brand' => $product['brand'],
                                    'name' => $product['name'],
                                    'image' => $product['image'],
                                    'price' => $p,
                                    'term' => $price['term'],
                                    'key' => $product['id'] . $price['supplier_id'] . $price['term']
                                ];
                            }

                            if(!$data['min_term'] || $price['term'] < $data['min_term']['term']){
                                $data['min_term'] = [
                                    'id' => $product['id'],
                                    'supplier_id' => $price['supplier_id'],
                                    'slug' => $product['slug'],
                                    'sku' => $product['sku'],
                                    'brand' => $product['brand'],
                                    'name' => $product['name'],
                                    'image' => $product['image'],
                                    'price' => $p,
                                    'term' => $price['term'],
                                    'key' => $product['id'] . $price['supplier_id'] . $price['term']
                                ];
                            }

                            $price['key'] = $product['id'] . $price['supplier_id'] . $price['term'];
                        }

                        $data['products'][] = $product;
                    }
                }
            }
        }

        if ($crosses_search) {
            $crosses = $this->product_model->get_search_crosses($crosses_search);
            if ($crosses) {
                foreach ($crosses as $product) {
                    if ($product['prices']) {
                        $product['is_cross'] = true;
                        $tecdoc_info = $this->product_model->tecdoc_info($product['sku'], $product['brand']);

                        //Если активна опция использовать наименования с текдок
                        if ($this->options['use_tecdoc_name'] && @$tecdoc_info['article']['Name']) {
                            $product['name'] = @$tecdoc_info['article']['Name'];
                        }


                        if(!$product['image']){
                            $product['image'] =  @$tecdoc_info['article']['Image'];
                        }else{
                            $product['image'] = '/uploads/product/'.$product['image'];
                        }

                        $product['info'] = @$tecdoc_info['article']['Info'];

                        $filter_brands[] = $product['brand'];
                        foreach ($product['prices'] as &$price) {
                            $p = $price['saleprice'] > 0 ? $price['saleprice'] : $price['price'];

                            if(!$data['min_price_cross'] || $p < $data['min_price_cross']['price']){
                                $data['min_price_cross'] = [
                                    'id' => $product['id'],
                                    'supplier_id' => $price['supplier_id'],
                                    'slug' => $product['slug'],
                                    'sku' => $product['sku'],
                                    'brand' => $product['brand'],
                                    'name' => $product['name'],
                                    'image' => $product['image'],
                                    'price' => $p,
                                    'term' => $price['term'],
                                    'key' => $product['id'] . $price['supplier_id'] . $price['term']
                                ];
                            }

                            if(!$data['min_term'] || $price['term'] < $data['min_term']['term']){
                                $data['min_term'] = [
                                    'id' => $product['id'],
                                    'supplier_id' => $price['supplier_id'],
                                    'slug' => $product['slug'],
                                    'sku' => $product['sku'],
                                    'brand' => $product['brand'],
                                    'name' => $product['name'],
                                    'image' => $product['image'],
                                    'price' => $p,
                                    'term' => $price['term'],
                                    'key' => $product['id'] . $price['supplier_id'] . $price['term']
                                ];
                            }

                            $price['key'] = $product['id'] . $price['supplier_id'] . $price['term'];
                        }

                        $data['products'][] = $product;
                    }
                }
            }
        }

        $this->setH1(sprintf(lang('text_search_search_h1'), $search, $brand));
        $this->setTitle(sprintf(lang('text_search_search_title'), $search, $brand));
        $this->setDescription(sprintf(lang('text_search_search_description'), $search, $brand));
        $this->setKeywords(sprintf(lang('text_search_search_keywords'), $search, $brand));

        if (isset($filter_brands)) {
            $data['filter_brands'] = array_unique($filter_brands);
            sort($data['filter_brands'], SORT_STRING);
        }

        $this->load->view('header');
        $this->load->view('search/search', $data);
        $this->load->view('footer');
    }
}
