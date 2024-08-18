<?php
class ModelExtensionTotalCategoryDiscount extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/category_discount');

		$category_discount = 0;

		$total['totals'][] = array(
			'code'       => 'category_discount',
			'title'      => $this->language->get('text_category_discount'),
			'value'      => $category_discount,
			'sort_order' => $this->config->get('total_category_discount_sort_order')
		);

		$total['total'] -= $category_discount;
	}
}
