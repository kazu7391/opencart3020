<?php
class ModelExtensionTotalCategoryDiscount extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/category_discount');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $categoryDiscount = 0;
        $pDiscountCount = 0;
        $pDiscounts = [];
        foreach ($this->cart->getProducts() as $product) {
            $specialStatus = $this->model_catalog_category->checkSpecialCategoryByProduct($product['product_id']);
            if($specialStatus) {
                $pDiscountCount += (int) $product['quantity'];
                $pDiscounts[] = $product['product_id'];
            }
        }

        if($pDiscountCount > 0) {
            $discounts = $this->model_catalog_category->getCategoryDiscountFromSpecial();

            $discounts = array_intersect_key(
                $discounts,
                array_unique(array_column($discounts, 'quantity'))
            );

            $discounts = array_filter($discounts, function ($discount) use ($pDiscountCount) {
                $discountQty = (int) $discount['quantity'];
                return $pDiscountCount >= $discountQty;
            });

            $discountPercent = 0;
            foreach($discounts as $discount) {
                $discountQty = (int) $discount['quantity'];

                if($pDiscountCount >= $discountQty) {
                    $discountPercent = (float) $discount['percent'];
                    if($discountQty == $pDiscountCount) break;
                }
            }

            foreach ($this->cart->getProducts() as $product) {
                if(in_array($product['product_id'], $pDiscounts)) {
                    $categoryDiscount += (float) ($product['total'] * $discountPercent / 100);
                }
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
