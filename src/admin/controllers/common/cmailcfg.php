<?php
class Cmailcfg extends CI_Controller { // Controller for companies mail system configuration

	var $data_header = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();

		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		$this->load->library('menu');
		$this->load->model('common/cmailcfg_model');
		$this->load->model('common/company_model');
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
		$this->data_header['breadcrumbs'][] = array('text' => 'Mail Configs', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/cmailcfg', 'separator' => ' -&gt; ');

		$this->base_path = base_url() .$this->config->item('index_page') .
					'/common/cmailcfg/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_delete'] = $this->base_path. 'delete';
		$this->data['path_update'] = $this->base_path. 'update/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
		$this->data['path_company'] = base_url() . $this->config->item('index_page') . 
				'/common/company/update/';
		$this->data['path_insert_company'] = base_url() . 
				$this->config->item('index_page') . 
				'/common/company/insert/';
						
		$this->data['error_warning'] = '';
		$this->data['success'] = '';

		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('common', $language);
		$this->lang->load('cmailcfg', $language);
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_delete'] = $this->lang->line('common_delete');
		$this->data['button_edit'] = $this->lang->line('common_edit');
		$this->data['button_new'] = $this->lang->line('common_new');
		$this->data['button_save'] = $this->lang->line('common_save');
		
		$this->data['column_action'] = $this->lang->line('common_action');
		$this->data['column_company'] = $this->lang->line('text_company');
		$this->data['column_name'] = $this->lang->line('text_name');
		$this->data['column_mailcfg'] = $this->lang->line('text_mailcfg');
		$this->data['column_status'] = $this->lang->line('common_status');
		
		$this->data['text_active'] = $this->lang->line('common_active');
		$this->data['text_activate'] = $this->lang->line('common_activate');
		$this->data['text_desactivate'] = $this->lang->line('common_desactivate');
		$this->data['text_edit'] = $this->lang->line('common_edit');
		$this->data['text_inactive'] = $this->lang->line('common_inactive');
		$this->data['text_new'] = $this->lang->line('common_new');
		$this->data['text_no_results'] = $this->lang->line('common_no_results');
		$this->data['text_view'] = $this->lang->line('common_view');
		$this->data['text_yes'] = $this->lang->line('common_yes');
		$this->data['text_no'] = $this->lang->line('common_none');
		
		$this->data['text_company'] = $this->lang->line('text_company');
		$this->data['text_mailcfg'] = $this->lang->line('text_mailcfg');
		$this->data['text_name'] = $this->lang->line('text_name');
		$this->data['text_comment'] = $this->lang->line('text_comment');
		$this->data['text_host'] = $this->lang->line('text_host');
		$this->data['text_port'] = $this->lang->line('text_port');
		$this->data['text_username'] = $this->lang->line('text_username');
		$this->data['text_password'] = $this->lang->line('text_password');
		$this->data['text_smtpsec'] = $this->lang->line('text_smtpsec');
		$this->data['text_smtpauth'] = $this->lang->line('text_smtpauth');
		$this->data['text_mailfrom'] = $this->lang->line('text_mailfrom');
		$this->data['text_mailfromname'] = $this->lang->line('text_mailfromname');
		$this->data['text_mailreplyto'] = $this->lang->line('');'Mail ReplayTo';
		$this->data['text_mailreplytoname'] = $this->lang->line('text_mailreplytoname');
		$this->data['text_wordwrap'] = $this->lang->line('text_wordwrap');
		$this->data['text_mailcfg_list'] = $this->lang->line('text_mailcfg_list');
		$this->data['text_new_mailcfg'] = $this->lang->line('text_new_mailcfg');
		
	}

	function index() {	// Shows a list of mail configs

		$this->data['heading_title'] = $this->data['text_mailcfg_list'];

		// Calc for pagination
		// base_url should use below view(header) to avoid config array overlap
		$config['base_url'] =  $this->base_path;
		$config['total_rows'] = $this->cmailcfg_model->record_count();
		$limit = $this->config->item('per_page');
		// (...)/common/cmailcfg/index/2  number 2 is at 4th segment;
		$page = $this->uri->segment(4);
	        $this->data['mailcfgs'] = $this->cmailcfg_model->fetch_mailcfgs($limit, $page);

		$this->pagination->initialize($config);
		$this->data["pagination"] = $this->pagination->create_links();

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('cmailcfg/cmailcfg_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	function insert(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', $this->data['text_name'], 'trim|required');
		$this->form_validation->set_rules('host', $this->data['text_host'], 'trim|required');
		$this->form_validation->set_rules('port', $this->data['text_port'], 'trim|required');
		$this->form_validation->set_rules('mailfrom', $this->data['text_mailfrom'], 'trim|required');
	
		if ($this->form_validation->run() == FALSE) {
			$this->data['heading_title'] = '<b>'. $this->data['text_new_mailcfg']. '</b>';
		
			$companies = $this->company_model->fetch_companies();
			if ($companies === false) {
				// 'ncd' == no company defined
				redirect($this->data['path_insert_company']. 'ncd');
				return true;
			}
		
			// Create a companies select options
			$companies_number = count($companies);
			$this->data['companies'] = '<select name="company" tabindex="3">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .=  '<option value="'.$companies[$i]['id'].'">' .
					$companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";
		
		
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('cmailcfg/cmailcfg_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 
	
			if ( ! empty($data['smtpauth']) ) {
				$data['smtpauth'] = 1;
			} else {
				$data['smtpauth'] = 0;
			}
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}
		
			if (! empty($data['name']) && ! empty($data['host']) ){
					$this->cmailcfg_model->set_new_mailcfg($data);
			}

			redirect($this->base_path);
		}
	}

	function delete () {
		$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

		$items = count ($data['selected']);
		for ($i = 0; $i < $items; $i++) {
			$this->cmailcfg_model->delete_mailcfg($data['selected'][$i]);
		}

		redirect($this->base_path);
	}

	function update (){	// Edit/Update mailcfg data
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('host', $this->data['text_host'], 'trim|required');
		$this->form_validation->set_rules('port', $this->data['text_port'], 'trim|required');
		$this->form_validation->set_rules('mailfrom', $this->data['text_mailfrom'], 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
		
			$mailcfg_id = $this->uri->segment(4);
			$this->data['mailcfg'] = $this->cmailcfg_model->get_mailcfg($mailcfg_id);
			if ( empty($this->data['mailcfg']) ) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			
			if ($this->data['mailcfg']['SMTPauth'] == 1) {
				$this->data['mailcfg']['SMTPauth'] = 'checked="checked"';
			} else {
				$this->data['mailcfg']['SMTPauth'] = '';
			}
			if ($this->data['mailcfg']['active'] == 1) {
				$this->data['mailcfg']['active'] = 'checked="checked"';
			} else {
				$this->data['mailcfg']['active'] = '';
			}
			
			// Create companies select options		
			$companies = $this->company_model->fetch_companies();
			$companies_number = count($companies);
			$this->data['companies'] = '<select name="company" tabindex="9">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .=  '<option value="'.$companies[$i]['id'] . '"';
				if ($companies[$i]['id'] == $this->data['mailcfg']['companyID'] ) {
					$this->data['companies'] .=' selected="selected" ';
				}			
				$this->data['companies'] .='>' . $companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";
		
		
			$this->data['heading_title'] = $this->data['text_mailcfg'] .
				' :: ' .$this->data['mailcfg']['name'] ;

			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>',
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('cmailcfg/cmailcfg_form',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
			if ( empty($this->cmailcfg_model->get_mailcfg($data['id'])) ) {
				$warning = array();
				$warning['code'] = '01';
				$warning['mailcfgid'] = $mailcfg_id;
				$this->show_warning_page($warning);
				return;
			}

			if ( ! empty($data['smtpauth']) ) {
				$data['smtpauth'] = 1;
			} else {
				$data['smtpauth'] = 0;
			}
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}

			$this->cmailcfg_model->set_mailcfg($data);

			redirect($this->base_path);
		}
	}
	
	function view (){
		$mailcfg_id = $this->uri->segment(4);
		$this->data['mailcfg'] = $this->cmailcfg_model->get_mailcfg($mailcfg_id);
		if ( empty($this->data['mailcfg']) ) {
			$warning = array();
			$warning['code'] = '01';
			$this->show_warning_page($warning);
			return;
		}
		if ($this->data['mailcfg']['active'] == 1) {
			$this->data['mailcfg']['active'] = 'checked="checked"';
		} else {
			$this->data['mailcfg']['active'] = '';
		}

		// Create companies select options	
		$company = $this->company_model->get_company($this->data['mailcfg']['companyID']);
		$this->data['company'] = $company['name'];
	
		$this->data['heading_title'] = $this->data['text_mailcfg'] . 
			' :: ' .$this->data['mailcfg']['name'] ;

		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'. $this->data['text_view']. '</b>', 
			'href'=> '', 'separator' => ' :: ');

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('cmailcfg/cmailcfg_view',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function activate(){	// Updates mailcfg status as activate
		$mailcfg_id = $this->uri->segment(4);
		$this->cmailcfg_model->activate_mailcfg($mailcfg_id);

		redirect($this->base_path);
	}

	function desactivate(){	// Updates mailcfg status as inactive
		$mailcfg_id = $this->uri->segment(4);
		$this->cmailcfg_model->desactivate_mailcfg($mailcfg_id);

		redirect($this->base_path);
	}
	
	function show_warning_page($msg){
		switch ($msg['code']) {
		case "01":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Unknown mail config';
			$warning['message'] = 'Sorry. There is not exists such as company mail config in DB.';
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
