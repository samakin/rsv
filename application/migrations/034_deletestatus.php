<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Deletestatus extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_product_price` DROP `status`;");
    }
    public function down()
    {
        return;
    }
}