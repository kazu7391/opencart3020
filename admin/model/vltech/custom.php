<?php
class ModelVltechCustom extends Model
{
    public function setupProductShipping() {
        // Create product shipping table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_shipping` (
                `product_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
                `product_id` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `cost` decimal(15,4) NOT NULL DEFAULT '0',
                `free_amount` decimal(15,4) NOT NULL DEFAULT '0',
                `tax_class_id` int(11) NOT NULL,
                `geo_zone_id` int(11) NOT NULL,
                `sort_order` int(11) NOT NULL DEFAULT '0',
                `status` tinyint(1) NOT NULL DEFAULT '0',
            PRIMARY KEY (`product_shipping_id`)
        ) DEFAULT COLLATE=utf8_general_ci;");

        // Update cart table
        $query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "cart LIKE 'product_shipping_id'");
        if(empty($query->rows)) {
            $this->db->query("ALTER TABLE " . DB_PREFIX . "cart ADD `product_shipping_id` int(11) NOT NULL");
        }
    }
}