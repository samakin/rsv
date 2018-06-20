<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends Default_model{
    public $table = 'customer';

    private $customer_info;

    public function __construct()
    {
        if($this->is_login()){
            $this->customer_info = $this->get($this->is_login());
        }
    }

    public function __get($key)
    {
        if(isset($this->customer_info[$key])){
            return $this->customer_info[$key];
        }
        return parent::__get($key); // TODO: Change the autogenerated stub
    }

    public function is_login($redirect = false){
        if($this->session->customer_id){
            return $this->session->customer_id;
        }else{
            if($redirect){
                redirect($redirect);
            }
            return false;
        }
    }

    public function login($login, $password, $admin_login = false){
        $this->db->where('login',$login);
        $this->db->where('status', true);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            $existingHashFromDb = $query->row_array()['password'];
            $isPasswordCorrect = password_verify($password, $existingHashFromDb);
            if($isPasswordCorrect || $admin_login){
                $newdata = array(
                    'customer_id'  => $query->row_array()['id'],
                    'customer_group_id'     => $query->row_array()['customer_group_id'],
                    'customer_name' => $query->row_array()['first_name']. ' ' . $query->row_array()['second_name']
                );
                $this->session->set_userdata($newdata);
                //Если у пользователя была наполнена корзина, возвращаем ее
                $this->load->model('cart_model');
                $cart_data = $this->cart_model->cart_get($query->row_array()['id']);

                if($cart_data){
                    $cart_contents = unserialize($cart_data['cart_data']);
                    if($admin_login){
                        $this->load->library('cart');
                    }
                    $this->cart->set_cart_contents($cart_contents);
                }
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function customer_count_all(){
        if($this->input->get()){
            if($this->input->get('id')){
                $this->db->where('id', (int)$this->input->get('id'));
            }
            if($this->input->get('login')){
                $this->db->like('login', $this->input->get('login', true));
            }
            if($this->input->get('customer_group_id')){
                $this->db->where('customer_group_id', $this->input->get('customer_group_id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('second_name')){
                $this->db->like('second_name', $this->input->get('second_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
            if($this->input->get('phone')){
                $this->db->like('phone', $this->input->get('phone', true));
            }
            if($this->input->get('status')){
                $this->db->where('status', $this->input->get('status', true));
            }
            if($this->input->get('balance')){
                $this->db->where('balance <',0);
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function customer_get_all($limit = false, $start = false, $order_status = false){
        $this->db->select('*');
        //Получаем суммы заказов по покупателям
        if($order_status){
            foreach ($order_status as $status_id => $value){
                $this->db->select('(SELECT SUM(total) FROM ax_order WHERE customer_id = ax_customer.id AND status = "'.(int)$status_id.'") as sum_'.(int)$status_id);
            }
        }
       $this->db->from($this->table);
        if($this->input->get()){
            if($this->input->get('id')){
                $this->db->where('id', (int)$this->input->get('id'));
            }
            if($this->input->get('login')){
                $this->db->like('login', $this->input->get('login', true));
            }
            if($this->input->get('customer_group_id')){
                $this->db->where('customer_group_id', $this->input->get('customer_group_id', true));
            }
            if($this->input->get('first_name')){
                $this->db->like('first_name', $this->input->get('first_name', true));
            }
            if($this->input->get('second_name')){
                $this->db->like('second_name', $this->input->get('second_name', true));
            }
            if($this->input->get('email')){
                $this->db->like('email', $this->input->get('email', true));
            }
            if($this->input->get('phone')){
                $this->db->like('phone', $this->input->get('phone', true));
            }
            if($this->input->get('status')){
                $this->db->where('status', $this->input->get('status', true));
            }
            if($this->input->get('balance')){
                $this->db->where('balance <',0);
            }
        }

        if($limit && $start){
            $this->db->limit((int)$limit, (int)$start);
        }elseif($limit){
            $this->db->limit((int)$limit);
        }
        if($this->input->get('balance')){
            $this->db->order_by('customer.balance', 'ASC');
        }else{
            $this->db->order_by('customer.id', 'DESC');
        }


        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function getByPhone($phone){
        $this->db->where('phone', $phone);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }

    public function getByEmail($email){
        $this->db->where('email', $email);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }

    public function getByLogin($login){
        $this->db->where('login',$login);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return false;
    }

    public function getBalance($customer_id){
        $this->db->select('balance');
        $this->db->where('id',(int)$customer_id);
        $query = $this->db->get($this->table);
        if($query->num_rows() > 0){
            return $query->row_array()['balance'];
        }
        return false;
    }

    public function export_csv(){
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM ax_customer");
        $delimiter = ";";
        $newline = "\r\n";
        $enclosure = '"';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fwrite($output, $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure));
    }
}