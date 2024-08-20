<?php
class ModelExtensionTotalCategoryDiscount extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/category_discount');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');


        $categoryDiscount = 0;

        $categoryDiscounts = [];
        $discountProducts = [];
        $productDiscountCount = 0;
        foreach ($this->cart->getProducts() as $product) {
            $productCategories = $this->model_catalog_category->getCategoriesByProductId($product['product_id']);
            if(!empty($productCategories)) {

                $productDiscountCount += (int) $product['quantity'];

                foreach($productCategories as $productCategory) {
                    $productCategory['cart_quantity'] = $product['quantity'];
                    $categoryDiscounts[$productCategory['category_id']]['products'][$productCategory['product_id']][] = $productCategory;
                    $categoryDiscounts[$productCategory['category_id']]['total_count'] = $productDiscountCount;
                }
            }
        }

        $formattedDiscountData = [];
        foreach($categoryDiscounts as $category_id => $categoryDiscountData) {
            $totalCount = $categoryDiscountData['total_count'];
            $productsData = $categoryDiscountData['products'];
            foreach($productsData as $product_id => $productDiscounts) {
                foreach($productDiscounts as $discount) {
                    if($totalCount == $discount['quantity']) {
                        $formattedDiscountData[$product_id][$category_id] = (float) $discount["percent"];
                        break;
                    }

                    if($totalCount > $discount['quantity']) {
                        $formattedDiscountData[$product_id][$category_id] = (float) $discount["percent"];
                    }
                }
            }
        }

        $formattedDiscountData = array_map(function($item) {
            if(!empty($item)) return max($item);
            return 0;
        }, $formattedDiscountData);



        echo '<pre>';
        var_dump($categoryDiscounts);
        echo '</pre>';
//        die;



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
