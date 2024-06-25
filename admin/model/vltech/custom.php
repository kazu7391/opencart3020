<?php
class ModelVltechCustom extends Model
{
    public function setupProductShippingCost() {
        // Add shipping cost for product in database
        $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product` LIKE 'shipping_fee'");
        if($query->rows) {
            // do nothing
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "product` ADD `shipping_fee` decimal(15,4) DEFAULT 0";
            $this->db->query($sql);
        }
    }
}