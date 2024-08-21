<?php
class ModelExtensionTotalCategoryDiscount extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/category_discount');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $categoryDiscount = 0;
        $categoryDiscounts = [];
        $discountProducts = [];
        $pCounts = [];
        $pCartQty = [];
        foreach ($this->cart->getProducts() as $product) {
            $productCategoriesDiscounts = $this->model_catalog_category->getCategoryDiscount($product['product_id']);

            if(!empty($productCategoriesDiscounts)) {
                $pCateDiscounts = [];
                foreach($productCategoriesDiscounts as $productCategoryDiscount) {
                    $pCateDiscounts[$productCategoryDiscount['category_id']][] = [
                        'quantity' => $productCategoryDiscount['quantity'],
                        'percent' => $productCategoryDiscount['percent']
                    ];

                }

                $categoryDiscounts[$product['product_id']] = $pCateDiscounts;
            }

            $pCartQty[$product['product_id']] = $product['quantity'];
        }

        foreach($categoryDiscounts as $productId => $categoryDiscountData) {
            foreach($categoryDiscountData as $categoryId => $productDiscounts) {
                $pCounts[$categoryId][] = $productId;
            }
        }

        $pCounts = array_map(function($ids) use ($pCartQty) {
            $c = 0;
            foreach ($ids as $pId) {
                $c += (int) $pCartQty[$pId];
            }
            return $c;
        }, $pCounts);

        foreach($categoryDiscounts as $productId => $categoryDiscountData) {
            foreach($categoryDiscountData as $categoryId => $cateDiscounts) {
                $total_count = $pCounts[$categoryId];
                foreach($cateDiscounts as $discountData) {
                    if($total_count == $discountData['quantity']) {
                        $discountProducts[$productId] = (float) $discountData['percent'];
                        break;
                    }

                    if($total_count > $discountData['quantity']) {
                        $discountProducts[$productId] = (float) $discountData['percent'];
                    }
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
