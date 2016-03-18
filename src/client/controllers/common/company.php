<?php
include ("menu.php");
class Company extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('isLoggedIn'))  {
			redirect('/login/show_login');
		}
		
		$this->load->model('common/company_model');
		$this->load->model('common/user_model');

		$user_id = $this->session->userdata('id');
		$this->data['user'] = $this->user_model->get_user($user_id);
		
		$this->data_header = get_menu($this->data['user']['isCompAdmin']);
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: System', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'My Company', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/company', 'separator' => ' -&gt; ');

		$this->base_path = base_url() . $this->config->item('index_page') . '/common/company/';
		$this->base_user_path = base_url() . $this->config->item('index_page') . '/common/user/';
		$this->data['path_delete'] = $this->base_path. 'delete';
		$this->data['path_update'] = $this->base_path . 'update/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
		$this->data['path_user_view'] = $this->base_user_path . 'view/';
		$this->data['path_user_update'] = $this->base_user_path . 'update/';
		$this->data['path_user_desactivate'] = $this->base_user_path . 'desactivate/';
		$this->data['path_user_activate'] = $this->base_user_path . 'activate/';

		$this->data['error_warning'] = '';
		$this->data['success'] = '';
		
		
		$this->data['button_cancel'] = 'Cancel';
		$this->data['button_delete'] = 'Delete';
		$this->data['button_edit'] = 'Edit';
		$this->data['button_new'] = 'New';
		$this->data['button_save'] = 'Save';
		
		$this->data['column_action'] = 'Action';
		$this->data['column_name'] = 'Name';
		$this->data['column_status'] = 'Status';
		$this->data['column_action'] = 'Action';
		$this->data['column_username'] = 'Username';
		
		
		$this->data['text_active'] = 'Active';
		$this->data['text_activate'] = 'Activate';
		$this->data['text_desactivate'] = 'Desactivate';
		$this->data['text_edit'] = 'Edit';
		$this->data['text_inactive'] = 'Inactive';
		$this->data['text_new'] = 'New';
		$this->data['text_no_results'] = 'There is no results';
		$this->data['text_view'] = 'View';
		
		$this->data['text_address'] = 'Address';
		$this->data['text_city'] = 'City';
		$this->data['text_company'] = 'Company';
		$this->data['text_companies_list'] = 'Companies list';
		$this->data['text_country'] = 'Country';
		$this->data['text_email1'] = 'Email #1';
		$this->data['text_email2'] = 'Email #2';
		$this->data['text_engines'] = 'Engines';
		$this->data['text_general'] = 'General';
		$this->data['text_name'] = 'Name';
		$this->data['text_new_company'] = 'New company';
		$this->data['text_nif'] = 'NIF';
		$this->data['text_phone1'] = 'Phone #1';
		$this->data['text_phone2'] = 'Phone #2';
		$this->data['text_postcode'] = 'Postcode';
		$this->data['text_state'] = 'State';
		$this->data['text_users'] = 'Users';
		$this->data['text_website'] = 'Web address';
	}

	function index() {	// Shows user's company data
		$company_id = $this->data['user']['companyID'];

		$this->data['company'] = $this->company_model->get_company($company_id);
		$this->data['heading_title'] = $this->data['text_company'] . 
			' :: <b>' . $this->data['company']['name']. '</b>';
			
		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'.$this->data['text_view'].'</b>', 
			'href'=> '', 'separator' => ' :: ');
		
		if ($this->data['user']['isCompAdmin'] == 1) {
			$this->data['btn_data'] = '<a onclick="$(\'#form\').submit();" tabindex="14" class="button">' . $this->data['button_edit'] . '</a>';
		} else {
			$this->data['btn_data'] = "";
		}
		
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('company/company_view',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	
	function update(){	// Edit/Update Company data
		$company_id = $this->data['user']['companyID'];
		
		if ($this->data['user']['isCompAdmin'] != 1) {
			redirect('/common/company/');
		}
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', $this->data['text_name'], 'trim|required');
		$this->form_validation->set_rules('nif', $this->data['text_nif'], 'trim|required');
		$this->form_validation->set_rules('address', $this->data['text_address'], 'trim|required');
		$this->form_validation->set_rules('postcode', $this->data['text_postcode'], 'trim|required');
		$this->form_validation->set_rules('city', $this->data['text_city'], 'trim|required');
		$this->form_validation->set_rules('state', $this->data['text_state'], 'trim|required');
		$this->form_validation->set_rules('country', $this->data['text_country'], 'trim|required');
		$this->form_validation->set_rules('email1', $this->data['text_email1'], 'trim|required');
		$this->form_validation->set_rules('phone1', $this->data['text_phone1'], 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->data['path_update'] .= $company_id;
			$this->data['company'] = $this->company_model->get_company($company_id);
			$this->data['heading_title'] = $this->data['text_company']. 
				' :: <b>' . $this->data['company']['name']. '</b>';
				
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'.$this->data['text_edit'].'</b>', 
				'href'=> '', 'separator' => ' :: ');
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('company/company_form',$this->data);
			$this->load->view('templates/footer',$this->data);

		} else {
			// Update changed data into DB
			$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}

			if (! empty($data['name']) && ! empty($data['nif'])) {
					$this->company_model->set_company($data);
			}

			redirect($this->base_path);
		}
	}

	
	function msg_handler ($msg){
		switch ($msg) {
		case "ncd":
			$warning = array('error_warning'=>'There is no company defined. You must create a company at least.');
			break;
		default:
			$warning = "";
		}
		return $warning;
	}

}
