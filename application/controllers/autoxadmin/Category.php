<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/category');
        $this->load->model('category_model');
        $this->load->model('product_attribute_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/category/index');
        $config['per_page'] = 20;
        $data['categorys'] = $this->category_model->cat_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->category_model->total_rows;
        $config['reuse_query_string'] = TRUE;

        $this->pagination->initialize($config);

        $this->load->view('admin/header');
        $this->load->view('admin/category/category', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        $data = [];
        if($this->input->post()){
            if(empty($_POST['slug'])){
                $_POST['slug'] = url_title($this->input->post('name', true));
            }
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[255]|trim');
            $this->form_validation->set_rules('h1', lang('text_h1'), 'max_length[255]|trim');
            $this->form_validation->set_rules('meta_description', lang('text_meta_description'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('meta_keywords', lang('text_meta_keywords'), 'max_length[255]|trim');
            $this->form_validation->set_rules('description', lang('text_description'), 'trim');
            $this->form_validation->set_rules('slug', lang('text_slug'), 'is_unique[category.slug]|max_length[255]|trim');
            $this->form_validation->set_rules('sort', lang('text_sort'), 'integer');

            if ($this->form_validation->run() !== false){
                $this->save_data();
            }else{
                $this->error = validation_errors();
            }
        }
        $data['categories'] = $this->category_model->get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/category/create',$data);
        $this->load->view('admin/footer');
    }

    public function edit($id){

        $data = [];
        $data['category'] = $this->category_model->get($id);
        if(!$data['category']){
            show_404();
        }

        if($this->input->post()){
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[255]|trim');
            $this->form_validation->set_rules('h1', lang('text_h1'), 'max_length[255]|trim');
            $this->form_validation->set_rules('meta_description', lang('text_meta_description'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('meta_keywords', lang('text_meta_keywords'), 'max_length[255]|trim');
            $this->form_validation->set_rules('description', lang('text_description'), 'trim');
            if($data['category']['slug'] != $this->input->post('slug')){
                $this->form_validation->set_rules('slug', lang('text_slug'), 'is_unique[category.slug]|max_length[255]|trim');
            }
            $this->form_validation->set_rules('sort', lang('text_sort'), 'integer');

            if ($this->form_validation->run() !== false){
                $this->save_data($id);
            }else{
                $this->error = validation_errors();
            }
        }
        $data['categories'] = $this->category_model->get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/category/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->category_model->delete($id);
        $this->product_attribute_model->delete_by_category($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/category');
    }

    private function save_data($id = false){
        $save = [];
        $save['parent_id'] = (int)$this->input->post('parent_id', true);
        $save['name'] = $this->input->post('name', true);
        $save['h1'] = $this->input->post('h1', true);
        $save['title'] = strip_tags($this->input->post('title', true));
        $save['meta_description'] = strip_tags($this->input->post('meta_description', true));
        $save['meta_keywords'] = strip_tags($this->input->post('meta_keywords', true));
        $save['description'] = $this->input->post('description');
        if($this->input->post('slug', true)){
            $save['slug'] = $this->input->post('slug', true);
        }else{
            $save['slug'] = url_title($this->input->post('name', true),'dash',true);
        }
        if($id){
            $save['updated_at'] = date('Y-m-d H:i:s');
        }else{
            $save['created_at'] = date('Y-m-d H:i:s');
            $save['updated_at'] = date('Y-m-d H:i:s');
        }
        $save['sort'] = (int)$this->input->post('sort', true);
        $save['status'] = (bool)$this->input->post('status', true);

        $id = $this->category_model->insert($save, $id);
        $this->clear_cache('categories');
        if($id){
            $this->session->set_flashdata('success', lang('text_success'));
            $this->cache->file->delete('all_category');
            redirect('autoxadmin/category');
        }
    }
}