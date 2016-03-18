<?php
class Company extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();

		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		$this->load->library('menu');
		$this->load->model('common/company_model');
		$this->load->config('unimail', FALSE, TRUE);
		$this->load->config('pagination');
		$this->load->library("pagination");

		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: Clients', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'Companies', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/company', 'separator' => ' -&gt; ');
		
		$this->base_path = base_url() . $this->config->item('index_page') . 
								'/common/company/';
		$this->base_user_path = base_url() . $this->config->item('index_page') . 
								'/common/user/';
		$this->base_engine_path = base_url() . $this->config->item('index_page') . 
								'/common/pdfengine/';
		$this->data['path_delete'] = $this->base_path. 'delete';
		$this->data['path_update'] = $this->base_path . 'update/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
		$this->data['path_setcertif'] = $this->base_path . 'setcertif/';
		$this->data['path_setnocertif'] = $this->base_path . 'setnocertif/';
		$this->data['path_user_view'] = $this->base_user_path . 'view/';
		$this->data['path_user_update'] = $this->base_user_path . 'update/';
		$this->data['path_user_desactivate'] = $this->base_user_path . 'desactivate/';
		$this->data['path_user_activate'] = $this->base_user_path . 'activate/';
		$this->data['path_engine_view'] = $this->base_engine_path . 'view/';
		$this->data['path_engine_update'] = $this->base_engine_path . 'update/';
		$this->data['path_engine_desactivate'] = $this->base_engine_path . 'desactivate/';
		$this->data['path_engine_activate'] = $this->base_engine_path . 'activate/';

		$this->data['error_warning'] = '';
		$this->data['success'] = '';
		
		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('common', $language);
		$this->lang->load('company', $language);
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_delete'] = $this->lang->line('common_delete');
		$this->data['button_edit'] = $this->lang->line('common_edit');
		$this->data['button_new'] = $this->lang->line('common_new');
		$this->data['button_save'] = $this->lang->line('common_save');
		
		$this->data['column_action'] = $this->lang->line('common_action');
		$this->data['column_name'] = $this->lang->line('text_name');
		$this->data['column_status'] = $this->lang->line('common_status');
		$this->data['column_certification'] = $this->lang->line('text_certification');
		$this->data['column_username'] = $this->lang->line('text_username');
		
		$this->data['text_active'] = $this->lang->line('common_active');
		$this->data['text_activate'] = $this->lang->line('common_activate');
		$this->data['text_desactivate'] = $this->lang->line('common_desactivate');
		$this->data['text_certificate'] = $this->lang->line('text_certificate');
		$this->data['text_no_certificate'] = $this->lang->line('text_no_certificate');
		$this->data['text_status'] = $this->lang->line('common_status');
		$this->data['text_edit'] = $this->lang->line('common_edit');
		$this->data['text_inactive'] = $this->lang->line('common_inactive');
		$this->data['text_new'] = $this->lang->line('common_new');
		$this->data['text_no_results'] = $this->lang->line('common_no_results');
		$this->data['text_view'] = $this->lang->line('common_view');
		$this->data['text_yes'] = $this->lang->line('common_yes');
		$this->data['text_no'] = $this->lang->line('common_no');
		
		$this->data['text_address'] = $this->lang->line('text_address');
		$this->data['text_city'] = $this->lang->line('text_city');
		$this->data['text_company'] = $this->lang->line('text_company');
		$this->data['text_dirpath'] = $this->lang->line('text_dirpath');
		$this->data['text_companies_list'] = $this->lang->line('text_companies_list');
		$this->data['text_country'] = $this->lang->line('text_country');
		$this->data['text_email1'] = $this->lang->line('text_email1');
		$this->data['text_email2'] = $this->lang->line('text_email2');
		$this->data['text_engines'] = $this->lang->line('text_engines');
		$this->data['text_general'] = $this->lang->line('text_general');
		$this->data['text_name'] = $this->lang->line('text_name');
		$this->data['text_new_company'] = $this->lang->line('text_new_company');
		$this->data['text_nif'] = $this->lang->line('text_nif');
		$this->data['text_phone1'] = $this->lang->line('text_phone1');
		$this->data['text_phone2'] = $this->lang->line('text_phone2');
		$this->data['text_postcode'] = $this->lang->line('text_postcode');
		$this->data['text_state'] = $this->lang->line('text_state');
		$this->data['text_users'] = $this->lang->line('text_users');
		$this->data['text_certification'] = $this->lang->line('text_certification');
		$this->data['text_website'] = $this->lang->line('text_website');

		
	}

	function index() {	// Shows a list of companies
		$this->data['heading_title'] = $this->data['text_companies_list'];

		// Calc for pagination
		$config['base_url'] =  $this->base_path;
		$config['total_rows'] = $this->company_model->record_count();
		$limit = $this->config->item('per_page');

		// (...)/common/company/index/2  numer 2 is at 4th segment;
		$page = $this->uri->segment(4);
	        $this->data['companies'] = $this->company_model->fetch_companies($limit, $page);

		$this->pagination->initialize($config);
		$this->data["pagination"] = $this->pagination->create_links();

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('company/company_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	function insert() { // Add new company
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

		if ($this->form_validation->run() == FALSE) {
			$this->data['heading_title'] = '<b>'. $this->data['text_new_company']. '</b>';
		
			$this->data_header['breadcrumbs'][] = array(
					'text' => '<b>'. $this->data['text_new']. '</b>', 
					'href'=> '', 'separator' => ' :: ');
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('company/company_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {	
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 

			if ( ! empty($data['certif']) ) {
				$data['certif'] = 1;
			} else {
				$data['certif'] = 0;
			}
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}

			if (empty($data['name']) || empty($data['nif'])) {
				// nne = name or nif not provided
				redirect($this->data['path_insert'] . 'nne');
			}
			$aux = mb_convert_case($data['name'], MB_CASE_LOWER, "UTF-8");
			$data['dirpath'] = preg_replace("![^a-z0-9]+!i", "_", $aux);
			$this->company_model->set_new_company($data);
			
			$this->create_company_dirs ($data['dirpath']);

			redirect($this->base_path);
		}
	}
	
	function delete () {
		$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

		$items = count ($data['selected']);
		for ($i = 0; $i < $items; $i++) {
			$this->company_model->delete_company($data['selected'][$i]);
		}

		redirect($this->base_path);
	}

	function update(){	// Edit/Update Company data
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
			$company_id = $this->uri->segment(4);
			$this->data['path_update'] .= $company_id;
			$this->data['company'] = $this->company_model->get_company($company_id);
			if ( empty($this->data['company']) ) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			$this->data['heading_title'] = $this->data['text_company']. 
				' :: <b>' . $this->data['company']['name']. '</b>';
				
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'.$this->data['text_edit'].'</b>', 
				'href'=> '', 'separator' => ' :: ');
			// Get a company users
			$this->load->model('common/user_model');
			$this->load->model('common/pdfengine_model');
			$this->data['users'] = $this->user_model->get_company_users($company_id);
			$this->data['engines'] = $this->pdfengine_model->get_company_engines($company_id);

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('company/company_form',$this->data);
			$this->load->view('templates/footer',$this->data);

		} else {
			// Update changed data into DB
			$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
			if ( empty($this->company_model->get_company($data['id'])) ) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}

			if ( ! empty($data['certif']) ) {
				$data['certif'] = 1;
			} else {
				$data['certif'] = 0;
			}
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}

			// Get company's old data
			$oldcomp = $this->company_model->get_company($data['id']);
			
			$aux = mb_convert_case($data['name'], MB_CASE_LOWER, "UTF-8");
			$data['dirpath'] = preg_replace("![^a-z0-9]+!i", "_", $aux);
			$this->company_model->set_company($data);

			$this->rename_company_dirs ($oldcomp['dirpath'], $data['dirpath']);
			redirect($this->base_path);
		}
	}

	
	function view() {
		$company_id = $this->uri->segment(4);
		$this->data['path_update'] .= $company_id;
		$this->data['company'] = $this->company_model->get_company($company_id);
		if ( empty($this->data['company']) ) {
			$warning = array();
			$warning['code'] = '01';
			$this->show_warning_page($warning);
			return;
		}
		if ($this->data['company']['active']) { 
			$this->data['company']['active'] = $this->data['text_active'];
		} else {
			$this->data['company']['active'] = $this->data['text_inactive'];
		}
		if ($this->data['company']['mustCert']) { 
			$this->data['company']['mustCert'] = $this->data['text_yes'];
		} else {
			$this->data['company']['mustCert'] = $this->data['text_no'];
		}
		
		$this->data['heading_title'] = $this->data['text_company'] . 
			' :: <b>' . $this->data['company']['name']. '</b>';
			
		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'.$this->data['text_view'].'</b>', 
			'href'=> '', 'separator' => ' :: ');

		// Get a company users
		$this->load->model('common/user_model');
		$this->load->model('common/pdfengine_model');
		$this->data['users'] = $this->user_model->get_company_users($company_id);
		$this->data['engines'] = $this->pdfengine_model->get_company_engines($company_id);
		
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('company/company_view',$this->data);
		$this->load->view('templates/footer',$this->data);	
	}

	function activate(){	// Updates company status as activate
		$company_id = $this->uri->segment(4);
		$this->company_model->activate_company($company_id);

		redirect($this->base_path);
	}

	function desactivate(){	// Updates company status as inactive
		$company_id = $this->uri->segment(4);
		$this->company_model->desactivate_company($company_id);

		redirect($this->base_path);
	}
	function setcertif(){	// Set company's delivery as certificate
		$company_id = $this->uri->segment(4);
		$this->company_model->certificate_company($company_id);

		redirect($this->base_path);
	}

	function setnocertif(){	// Set company's delivery as not certificate
		$company_id = $this->uri->segment(4);
		$this->company_model->nocertificate_company($company_id);

		redirect($this->base_path);
	}

	private function create_company_dirs ($name){
		$company_dir = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_companies') . '/' . 
				$name;

		if (! is_dir($company_dir)) {
			mkdir ($company_dir);
		}
		$company_dir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_engines') . '/' . 
				$name;
		if (! is_dir($company_dir)) {
			mkdir ($company_dir);
		}
		$company_dir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_notif') . '/' . 
				$name;
		if (! is_dir($company_dir)) {
			mkdir ($company_dir);
		}
	}
	private function rename_company_dirs ($oldname, $newname){
		$company_olddir = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_companies') . '/' . 
				$oldname;
		$company_newdir = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_companies') . '/' . 
				$newname;
		if (! is_dir($company_newdir)) {
			rename ($company_olddir, $company_newdir);
		}

		$company_olddir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_engines') . '/' . 
				$oldname;
		$company_newdir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_engines') . '/' . 
				$newname;
		if (! is_dir($company_newdir)) {
			rename ($company_olddir, $company_newdir);
		}

		$company_olddir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_notif') . '/' . 
				$oldname;
		$company_newdir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_notif') . '/' . 
				$newname;
		if (! is_dir($company_newdir)) {
			rename ($company_olddir, $company_newdir);
		}
	}
	
	function show_warning_page($msg){
		switch ($msg['code']) {
		case "01":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Unknown company';
			$warning['message'] = 'Sorry. There is not exists such as company in DB.';
			break;
		default:		// Unknown error
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Unknown error';
			$warning['message'] = 'Sorry. It was not able to detect error type.';
		
		}
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('warning/warning',$warning);
		$this->load->view('templates/footer','');
	}

}
