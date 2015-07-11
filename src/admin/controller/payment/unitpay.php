<?php
class ControllerPaymentUnitpay extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('payment/unitpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('unitpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'], 'SSL');
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_liqpay'] = $this->language->get('text_liqpay');
		$data['text_card'] = $this->language->get('text_card');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
         		
		
		$data['entry_login'] = $this->language->get('entry_login');
		$data['entry_password1'] = $this->language->get('entry_password1');
		

		// URL
		$data['copy_result_url'] 	= HTTP_CATALOG . 'index.php?route=payment/unitpay/callback';
		$data['copy_success_url']	= HTTP_CATALOG . 'index.php?route=payment/unitpay/success';
		$data['copy_fail_url'] 	= HTTP_CATALOG . 'index.php?route=payment/unitpay/fail';

		



		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		//
	
if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		//
		if (isset($this->error['login'])) {
			$data['error_login'] = $this->error['login'];
		} else {
			$data['error_login'] = '';
		}

		if (isset($this->error['password1'])) {
			$data['error_password1'] = $this->error['password1'];
		} else {
			$data['error_password1'] = '';
		}


		
$data['action'] = $this->url->link('payment/unitpay', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/unitpay', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);



         	
		
		//
		if (isset($this->request->post['unitpay_login'])) {
			$data['unitpay_login'] = $this->request->post['unitpay_login'];
		} else {
			$data['unitpay_login'] = $this->config->get('unitpay_login');
		}


		//
		if (isset($this->request->post['unitpay_key'])) {
			$data['unitpay_key'] = $this->request->post['unitpay_key'];
		} else {
			$data['unitpay_key'] = $this->config->get('unitpay_key');
		}

		
		if (isset($this->request->post['unitpay_order_status_id'])) {
			$data['unitpay_order_status_id'] = $this->request->post['unitpay_order_status_id'];
		} else {
			$data['unitpay_order_status_id'] = $this->config->get('unitpay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['unitpay_geo_zone_id'])) {
			$data['unitpay_geo_zone_id'] = $this->request->post['unitpay_geo_zone_id'];
		} else {
			$data['unitpay_geo_zone_id'] = $this->config->get('unitpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['unitpay_status'])) {
			$data['unitpay_status'] = $this->request->post['unitpay_status'];
		} else {
			$data['unitpay_status'] = $this->config->get('unitpay_status');
		}

		if (isset($this->request->post['unitpay_sort_order'])) {
			$data['unitpay_sort_order'] = $this->request->post['unitpay_sort_order'];
		} else {
			$data['unitpay_sort_order'] = $this->config->get('unitpay_sort_order');
		}

        $this->response->setOutput ($this->load->view ('payment/unitpay.tpl', $data));
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/unitpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['unitpay_login']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['unitpay_key']) {
			$this->error['password1'] = $this->language->get('error_password1');
		}

	
		return !$this->error;
	}
}
?>