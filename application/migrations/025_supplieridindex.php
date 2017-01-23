
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Supplieridindex extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `cmsautox`.`ax_product_price` ADD INDEX (`supplier_id`);");
    }
    public function down()
    {
        return;
    }
}