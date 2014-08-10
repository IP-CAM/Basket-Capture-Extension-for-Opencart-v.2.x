<?php

class ControllerModuleBasketCapture extends Controller {

	/**
	 * Validation errors array
	 *
	 * @access	private
	 * @var		array
	 */	
	 private $error = array(); 
	
	/**
	 * Get the page
	 * 
	 * @access	protected
	 * @return	void
	 */
	public function index() {   
		
		// Load models & lang files
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		$this->data = array_merge($this->data, $this->language->load('module/basket_capture'));

		// Set meta title
		$this->document->setTitle($this->language->get('heading_title'));
				
		// Save on submit
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('basket_capture', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
 		// Error validation warnings
		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		// Breadcrumbs
  		$this->data['breadcrumbs'] = array();
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/basket_capture', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		// Buttons
		$this->data['action'] = $this->url->link('module/basket_capture', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		// Layouts
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		// Saved positions
		$this->data['modules'] = array();
		if (isset($this->request->post['basket_capture_module'])) {
			$this->data['modules'] = $this->request->post['basket_capture_module'];
		} elseif ($this->config->get('basket_capture_module')) { 
			$this->data['modules'] = $this->config->get('basket_capture_module');
		}

		if (isset($this->request->post['basket_capture_api_key'])) {
			$this->data['basket_capture_api_key'] = $this->request->post['basket_capture_api_key'];
		} else { 
			$this->data['basket_capture_api_key'] = $this->config->get('basket_capture_api_key');
		}
						
		// Set template & child controllers
		$this->template = 'module/basket_capture.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		// Render it!
		$this->response->setOutput($this->render());
	}
	
	/**
	 * Validate the form submission
	 * 
	 * @access	protected
	 * @return	bool
	 */
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/basket_capture')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {

		// Load models & lang files
		$this->load->model('design/layout');
		$this->load->model('setting/setting');

		$settings = array();

		// Add a module for every possible layout, in the content_bottom position
		foreach ($this->model_design_layout->getLayouts() as $layout) {
			$settings['basket_capture_module'][] = array(
				'layout_id' => $layout['layout_id'],
				'position' => 'content_bottom',
				'status' => '1',
				'sort_order' => '1',
			);
		}

		// Save the settings
		$this->model_setting_setting->editSetting('basket_capture', $settings);		
	}
}