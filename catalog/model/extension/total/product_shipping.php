<?php
class ModelExtensionTotalProductShipping extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/product_shipping');

        $product_shipping_total = 0;
        foreach ($this->cart->getProducts() as $product) {
            if($product['shipping_fee'] && $product['shipping_fee'] > 0) {
                $product_shipping_total += (float) $product['shipping_fee'];
            }
        }

		$total['totals'][] = array(
			'code'       => 'product_shipping',
			'title'      => $this->language->get('text_product_shipping'),
			'value'      => $product_shipping_total,
			'sort_order' => $this->config->get('total_product_shipping_sort_order')
		);

        $total['total'] += $product_shipping_total;
	}
}