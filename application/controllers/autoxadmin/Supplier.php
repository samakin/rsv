<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class supplier extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/supplier');
        $this->load->model('supplier_model');
        $this->load->model('pricing_model');
        $this->load->model('product_model');

    }

    public function index()
    {
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/supplier/index');
        $config['total_rows'] = $this->supplier_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['supplieres'] = $this->supplier_model->get_all($config['per_page'], $this->uri->segment(4));

        $this->load->view('admin/header');
        $this->load->view('admin/supplier/supplier', $data);
        $this->load->view('admin/footer');
    }

    public function create()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('stock', lang('text_stock'), 'integer');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[12]');
            if ($this->form_validation->run() !== false) {
                $this->save_data();
            } else {
                $this->error = validation_errors();
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/supplier/create');
        $this->load->view('admin/footer');
    }

    public function edit($id)
    {
        $data = [];
        $data['supplier'] = $this->supplier_model->get($id);

        if (!$data['supplier']) {
            show_404();
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', lang('text_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('description', lang('text_description'), 'max_length[3000]');
            $this->form_validation->set_rules('stock', lang('text_stock'), 'integer');
            $this->form_validation->set_rules('api', lang('text_api'), 'max_length[12]');
            if ($this->form_validation->run() !== false) {
                $this->save_data($id, true);
            } else {
                $this->error = validation_errors();
            }
        }
        //Ценообразование по постащику
        $data['pricing'] = $this->pricing_model->get_by_supplier($id);
        //Статистика по поставщику
        $data['count'] = $this->product_model->product_count_all(['supplier_id' => (int)$id]);
        $data['quan'] = $this->product_model->product_count_all(['quantity >' => '0','supplier_id' => (int)$id]);
        $data['visible'] = $this->product_model->product_count_all(['status' => true,'supplier_id' => (int)$id]);
        $this->load->view('admin/header');
        $this->load->view('admin/supplier/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        $this->supplier_model->delete($id);
        //Удаляем ценообразование
        $this->db->where('supplier_id', (int)$id)->delete('pricing');
        //Удаляем товары
        $this->db->where('supplier_id', (int)$id)->delete('product_price');
        //Удаляем шаблоны
        $this->db->where('supplier_id', (int)$id)->delete('sample');

        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/supplier');
    }

    public function delete_products($id){
        //Удаляем товары
        $this->db->where('supplier_id', (int)$id)->delete('product_price');
        //Чистим кэш
        $this->clear_cache();
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/supplier/edit/'.$id);
    }

    private function save_data($id = false, $update_price = false)
    {
        $save = [];
        $save['name'] = $this->input->post('name', true);
        $save['description'] = $this->input->post('description', true);
        $save['stock'] = (bool)$this->input->post('stock', true);
        $save['api'] = (string)$this->input->post('api', true);
        $supplier_id = $this->supplier_model->insert($save, $id);
        if ($supplier_id) {
            $pricing = $this->input->post('pricing');
            //Удаляем ценобразование
            $this->db->where('supplier_id', (int)$supplier_id)->delete('pricing');
            if (!empty($pricing)) {
                foreach ($this->input->post('pricing', true) as $pricing) {
                    if ($pricing['price_from'] >= 0 && $pricing['price_to'] > 0 && $pricing['value'] > 0) {
                        $save = [];
                        $save['supplier_id'] = $supplier_id;
                        $save['price_from'] = (float)$pricing['price_from'];
                        $save['price_to'] = (float)$pricing['price_to'];
                        $save['method_price'] = (string)$pricing['method_price'];
                        $save['value'] = (int)$pricing['value'];
                        $this->pricing_model->insert($save);
                    }
                }
            }

            if($update_price){
                $this->product_model->set_price((int)$id);
            }

            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/supplier');
        }
    }

    public function get_sample()
    {
        $json = [];
        if ($this->input->is_ajax_request()) {
            $this->load->model('sample_model');
            $supplier_id = (int)$this->input->post('supplier_id', true);
            $results = $this->sample_model->get_all(false, false, array('supplier_id' => $supplier_id));
            if ($results) {
                foreach($results as $result){
                    $json['samples'][] = [
                        'id' => $result['id'],
                        'supplier_id' => $result['supplier_id'],
                        'name' => $result['name'],
                        'value' => unserialize($result['value'])
                    ];
                }

            } else {
                $json['error'] = 'No sample';
            }
        } else {
            $json['error'] = 'no_ajax';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));

    }

    public function delete_sample(){
        if ($this->input->is_ajax_request()) {
            $sample_id = $this->input->post('sample_id');
            if($sample_id){
                $this->load->model('sample_model');
                $this->sample_model->delete((int)$sample_id);
            }
        }
    }
}