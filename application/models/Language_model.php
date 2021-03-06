<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Language_model extends Default_model{
    public $table = 'language';

    public $translate;

    public function line($line){
        if(isset($this->translate[$line])){
            return $this->translate[$line];
        }else{
            $this->db->where('line',$line);
            $this->db->limit(1);
            $query = $this->db->get('language');
            if($query->num_rows()){
                $this->translate[$line] = $query->row_array()['text'];
                return $this->translate[$line];
            }
        }

        return false;
    }

    public function language_count_all(){
        if($this->input->get()){
            if($this->input->get('filter_text')){
                $this->db->like('text', $this->input->get('filter_text',true));
            }
            if($this->input->get('filter_key')){
                $this->db->like('line', $this->input->get('filter_key',true));
            }
            return $this->db->count_all_results($this->table);
        }else{
            return $this->db->count_all($this->table);
        }
    }

    public function language_get_all($limit, $start){
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->limit((int)$limit, (int)$start);
        $this->db->order_by('id','DESC');
        if($this->input->get('filter_text')){
            $this->db->like('text', $this->input->get('filter_text',true));
        }
        if($this->input->get('filter_key')){
            $this->db->like('line', $this->input->get('filter_key',true));
        }
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
}
