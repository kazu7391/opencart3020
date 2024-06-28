<?php
class ModelExtensionTotalProductShipping extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/product_shipping');
        $this->load->model('catalog/product');

        $product_shipping_total = 0;
        foreach ($this->cart->getProducts() as $product) {
            $product_shipping_id = $product['product_shipping_id'];
            $product_shipping_data = $this->model_catalog_product->getProductShippingData($product_shipping_id);

            $unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
            $product_total = $unit_price * $product['quantity'];

            if($product_total < $product_shipping_data['free_amount'] ) {
                $query_geo_zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . $product_shipping_data['geo_zone_id'] . "'");
                if ($product_shipping_data['geo_zone_id'] == 0) {
                    $status = true;
                } elseif ($query_geo_zone->num_rows) {
                    $status = true;
                } else {
                    $status = false;
                }

                if($status) {
                    if($product_shipping_data['tax_class_id'] != 0) {
                        $product_shipping_total += $this->tax->calculate($product_shipping_data['cost'], $product_shipping_data['tax_class_id']);
                    } else {
                        $product_shipping_total += $product_shipping_data['cost'];
                    }
                }
            }
        }

        if($product_shipping_total > 0) {
            $total['totals'][] = array(
                'code'       => 'product_shipping',
                'title'      => $this->language->get('text_product_shipping'),
                'value'      => $product_shipping_total,
                'sort_order' => $this->config->get('total_product_shipping_sort_order')
            );

            $total['total'] += $product_shipping_total;
        }
	}
}