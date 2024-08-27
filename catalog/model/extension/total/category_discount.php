<?php
class ModelExtensionTotalCategoryDiscount extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/category_discount');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $categoryDiscount = 0;
        $pDiscountCount = 0;
        foreach ($this->cart->getProducts() as $product) {
            $specialStatus = $this->model_catalog_category->checkSpecialCategoryByProduct($product['product_id']);
            if($specialStatus) {
                $pDiscountCount += (int) $product['quantity'];
            }
        }

        if($categoryDiscount > 0) {
            $total['totals'][] = array(
                'code'       => 'category_discount',
                'title'      => $this->language->get('text_category_discount'),
                'value'      => $categoryDiscount,
                'sort_order' => $this->config->get('total_category_discount_sort_order')
            );

            $total['total'] -= $categoryDiscount;
        }
	}
}
