<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

class Redirect_model extends Default_model{
    public $table = 'redirect';

    public function getByUri($uri){
        $this->db->where('url_from',$uri);
        return $this->db->get($this->table)->row_array();
    }
}