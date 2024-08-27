<?php
class ControllerExtensionTotalCategoryDiscount extends Controller {
	private $error = array();

	public function install() {
		$this->load->model('setting/setting');

		$this->model_setting_setting->editSetting('total_total', [
            'total_total_status' => 1,
            'total_total_sort_order' => 99
        ]);

		$this->model_setting_setting->editSetting('total_category_discount', [
			'total_category_discount_status' => 1,
			'total_category_discount_sort_order' => 90
		]);
	}

	public function index() {
		$this->load->language('extension/total/category_discount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('total_category_discount', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/total/category_discount', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/total/category_discount', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true);

		if (isset($this->request->post['total_category_discount_status'])) {
			$data['total_category_discount_status'] = $this->request->post['total_category_discount_status'];
		} else {
			$data['total_category_discount_status'] = $this->config->get('total_category_discount_status');
		}

		if (isset($this->request->post['total_category_discount_sort_order'])) {
			$data['total_category_discount_sort_order'] = $this->request->post['total_category_discount_sort_order'];
		} else {
			$data['total_category_discount_sort_order'] = $this->config->get('total_category_discount_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/category_discount', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/category_discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}