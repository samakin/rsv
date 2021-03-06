<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/customer');
        $this->load->model('customer_model');
        $this->load->model('customergroup_model');
        $this->load->model('orderstatus_model');
        $this->load->model('order_product_model');
        $this->load->model('black_list_model');
        $this->load->model('order_model');
    }

    public function index()
    {
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/customer/index');
        $config['total_rows'] = $this->customer_model->customer_count_all();
        $config['per_page'] = 10;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);
        $data['orderstatus'] = $this->orderstatus_model->status_get_all();
        $data['customeres'] = $this->customer_model->customer_get_all($config['per_page'], $this->uri->segment(4), $data['orderstatus']);
        $data['customergroup'] = $this->customergroup_model->get_group();

        $this->load->view('admin/header');
        $this->load->view('admin/customer/customer', $data);
        $this->load->view('admin/footer');
    }

    public function create()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_group_id', lang('text_customer_group_id'), 'required|integer|trim');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'max_length[250]|trim');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'max_length[32]|trim');
            $this->form_validation->set_rules('patronymic', lang('text_patronymic'), 'max_length[255]|trim');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]|trim');
            $this->form_validation->set_rules('email', lang('text_email'), 'valid_email|trim|is_unique[customer.email]');
            $this->form_validation->set_rules('phone', lang('text_phone'), 'trim|required|is_unique[customer.phone]|min_length[10]|max_length[32]');
            $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
            $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');

            if ($this->form_validation->run() !== false) {
                $this->save_data();
            } else {
                $this->error = validation_errors();
            }
        }
        $data = [];
        $data['customergroup'] = $this->customergroup_model->get_group();

        $this->load->view('admin/header');
        $this->load->view('admin/customer/create', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id)
    {

        $data = [];
        $data['customer'] = $this->customer_model->get($id);
        if (!$data['customer']) {
            show_404();
        }

        $data['statuses'] = $this->orderstatus_model->status_get_all();
        $data['status_totals'] = $this->order_product_model->get_status_totals($data['statuses'], $id);

        $data['black_list_info'] = $this->black_list_model->get($id);

        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_group_id', lang('text_customer_group_id'), 'required|integer|trim');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'max_length[32]|trim');
            $this->form_validation->set_rules('second_name', lang('text_second_name'), 'max_length[32]|trim');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]|trim');

            $this->form_validation->set_rules('email', lang('text_email'), 'valid_email|trim');
            if($this->input->post('email', true) != $data['customer']['email']){
                $this->form_validation->set_rules('email', lang('text_email'), 'is_unique[customer.email]');
            }

            $this->form_validation->set_rules('phone', lang('text_phone'), 'trim|required|max_length[32]');
            if($this->input->post('phone', true) != $data['customer']['phone']){
                $this->form_validation->set_rules('phone', lang('text_phone'), 'is_unique[customer.phone]');
            }

            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', lang('text_password'), 'required|trim');
                $this->form_validation->set_rules('confirm_password', lang('text_confirm_password'), 'required|trim|matches[password]');
            }

            if ($this->form_validation->run() !== false) {
                $this->save_data($id);
            } else {
                $this->error = validation_errors();
            }
        }
        $data['customergroup'] = $this->customergroup_model->get_group();

        $data['orders'] = $this->order_model->get_all(5,false,['customer_id' => (int)$id], ['id' => 'DESC']);

        $data['statuses'] = $this->orderstatus_model->status_get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/customer/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        $this->customer_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/customer');
    }

    public function login($id){
        $this->load->helper('cookie');
        delete_cookie('customer');

        if($id){
            $this->customer_model->login('','',$id);
            redirect('/');
        }
    }

    private function save_data($id = false)
    {
        $save = [];
        $save['customer_group_id'] = (int)$this->input->post('customer_group_id', true);
        $save['first_name'] = $this->input->post('first_name', true);
        $save['second_name'] = $this->input->post('second_name', true);
        $save['patronymic'] = $this->input->post('patronymic', true);
        $save['address'] = $this->input->post('address', true);
        $save['email'] = $this->input->post('email', true);
        $save['phone'] = format_phone($this->input->post('phone', true));
        if ($this->input->post('password')) {
            $save['password'] = password_hash($this->input->post('password', true), PASSWORD_BCRYPT);
        }
        if ($id) {
            $save['updated_at'] = date("Y-m-d H:i:s");
        } else {
            $save['created_at'] = date("Y-m-d H:i:s");
            $save['updated_at'] = date("Y-m-d H:i:s");
        }
        $save['status'] = (bool)$this->input->post('status');
        $save['negative_balance'] = (float)$this->input->post('negative_balance');
        $save['deferment_payment'] = (int)$this->input->post('deferment_payment');
        $id = $this->customer_model->insert($save, $id);
        if ($id) {
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/customer');
        }
    }
    public function export(){
        $this->customer_model->export_csv();
    }

    public function search(){
        $term = $this->input->get('term', true);

        $customers = $this->customer_model->search($term);

        $this->output
            ->set_content_type('application/html')
            ->set_output(json_encode($customers));
    }
}