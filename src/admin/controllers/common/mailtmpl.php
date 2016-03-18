<?php
class Mailtmpl extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();
		
		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		
		$this->load->model('common/mailtmpl_model');
		$this->load->model('common/company_model');
		$this->load->config('unimail', FALSE, TRUE);
		$this->load->config('pagination');
		$this->load->library('menu');
		$this->load->library("pagination");
		
		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = '';
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: Clients', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'Mail Templates', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/mailtmpl', 'separator' => ' -&gt; ');

		
		$this->base_path = base_url() . $this->config->item('index_page') . '/common/mailtmpl/';
		
		$this->data['path_delete'] = $this->base_path. 'delete';
		$this->data['path_update'] = $this->base_path . 'update/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_csv'] = $this->base_path .'csv/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
		$this->data['path_company'] = base_url() . $this->config->item('index_page') . 
				'/common/company/update/';

		$this->data['error_warning'] = '';
		$this->data['success'] = '';
		
		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('common', $language);
		$this->lang->load('mailtmpl', $language);
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_delete'] = $this->lang->line('common_delete');
		$this->data['button_edit'] = $this->lang->line('common_edit');
		$this->data['button_new'] = $this->lang->line('common_new');
		$this->data['button_save'] = $this->lang->line('common_save');
		$this->data['button_csv'] = $this->lang->line('text_csv');
		
		$this->data['column_action'] = $this->lang->line('common_action');
		$this->data['column_name'] = $this->lang->line('text_name');
		$this->data['column_company'] = $this->lang->line('text_company');
		$this->data['column_status'] = $this->lang->line('common_status');
		
		$this->data['text_active'] = $this->lang->line('common_active');
		$this->data['text_activate'] = $this->lang->line('common_activate');
		$this->data['text_desactivate'] = $this->lang->line('common_desactivate');
		$this->data['text_edit'] = $this->lang->line('common_edit');
		$this->data['text_inactive'] = $this->lang->line('common_inactive');
		$this->data['text_new'] = $this->lang->line('common_new');
		$this->data['text_no_results'] = 'There is no results';
		$this->data['text_no_results'] = $this->lang->line('common_no_results');
		$this->data['text_view'] = $this->lang->line('common_view');
		$this->data['text_yes'] = $this->lang->line('common_yes');
		$this->data['text_no'] = $this->lang->line('common_no');
		
		$this->data['text_company'] = $this->lang->line('text_company');
		$this->data['text_name'] = $this->lang->line('text_name');
		$this->data['text_subject'] = $this->lang->line('text_subject');
		$this->data['text_subjtag'] = $this->lang->line('text_subjtag');
		$this->data['text_greeting'] = $this->lang->line('text_greeting');
		$this->data['text_bodyhdr'] = $this->lang->line('text_bodyhdr');
		$this->data['text_body'] = $this->lang->line('text_body');
		$this->data['text_bodyftr'] = $this->lang->line('text_bodyftr');
		$this->data['text_signature'] = $this->lang->line('text_signature');
		$this->data['text_comment'] = $this->lang->line('text_comment');
		$this->data['text_mailtmpl'] = $this->lang->line('text_mailtmpl');
		$this->data['text_mailtmpls_list'] = $this->lang->line('text_mailtmpls_list');
		$this->data['text_new_mailtmpl'] = $this->lang->line('text_new_mailtmpl');
		$this->data['text_URL_before_hdr'] = $this->lang->line('text_URL_before_hdr');
		$this->data['text_URL_before_body'] = $this->lang->line('text_URL_before_body');
		$this->data['text_URL_before_ftr'] = $this->lang->line('text_URL_before_ftr');
		$this->data['text_URL_before_sgnt'] = $this->lang->line('text_URL_before_sgnt');
		
	}

	function index() {	// Shows a list of msgtemplates
		$this->data['heading_title'] = $this->data['text_mailtmpls_list'];

		// Calc for pagination
		$config['base_url'] =  $this->base_path;
		$config['total_rows'] = $this->mailtmpl_model->record_count();
		$limit = $this->config->item('per_page');

		// (...)/common/mailtmpl/index/2  numer 2 is at 4th segment;
		$page = $this->uri->segment(4);
	        $this->data['mailtmpls'] = $this->mailtmpl_model->fetch_msgtemplates($limit, $page);

		$this->pagination->initialize($config);
		$this->data["pagination"] = $this->pagination->create_links();

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('mailtmpl/mailtmpl_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function insert($msg_type = ""){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', $this->data['text_name'], 'trim|required');
		$this->form_validation->set_rules('companyID', $this->data['text_name'], 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$companies = $this->company_model->fetch_companies();
			if ($companies === false) {
				// 'ncd' == no company defined
				redirect($this->data['path_insert_company']. 'ncd');
				return true;
			}
			$this->data['heading_title'] = $this->data['text_new_mailtmpl'];
			// Create a companies select options
			$companies_number = count($companies);
			$this->data['companies'] = '<select id="company" name="companyID"  tabindex="3">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .=  '<option value="'.$companies[$i]['id'].'">' .
					$companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";

			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' :: ');
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('mailtmpl/mailtmpl_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 
			
			if (! empty($data['URLhdr']) ) {
				$data['URLhdr'] = 1;
			} else {
				$data['URLhdr'] = 0;
			}
			if ( ! empty($data['URLbdy']) ) {
				$data['URLbdy'] = 1;
			} else {
				$data['URLbdy'] = 0;
			}
			if ( ! empty($data['URLftr']) ) {
				$data['URLftr'] = 1;
			} else {
				$data['URLftr'] = 0;
			}
			if ( ! empty($data['URLsgnt']) ) {
				$data['URLsgnt'] = 1;
			} else {
				$data['URLsgnt'] = 0;
			}
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}

			$this->mailtmpl_model->set_new_msgtemplate($data);
			redirect($this->base_path);
		}
	}

	function delete () {
		$data = $this->input->post(NULL, TRUE); 
		// returns all POST items with XSS filter

		$items = count ($data['selected']);
		for ($i = 0; $i < $items; $i++) {
			// delete message template
			$this->mailtmpl_model->delete_msgtemplate($data['selected'][$i]);
		}

		redirect($this->base_path);
	}

	function update (){	// Edit/Update msgtemplate data
	
		$this->load->helper('form');
		$this->load->library('form_validation');
		$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

		$this->form_validation->set_rules('id', $this->data['text_name'], 'trim|required');
		$this->form_validation->set_rules('companyID', $this->data['text_name'], 'trim|required');
		$this->form_validation->set_rules('langID', $this->data['text_name'], 'trim|required');
		
		if (empty($_FILES['upfile']['name']) && empty($data)) {
			$msgtemplate_id = $this->uri->segment(4);
			$this->data['msgtemplate'] = $this->mailtmpl_model->get_msgtemplate_fullnames($msgtemplate_id);
			if ( empty($this->data['msgtemplate']) ){
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			$companies = $this->company_model->fetch_companies();
			if ($companies === false) {
				// 'ncd' == no company defined
				redirect($this->data['path_insert_company']. 'ncd');
				return true;
			}
		
			// Create a companies select options
			$companies_number = count($companies);
			$this->data['companies'] = '<select id="company" name="companyID"  tabindex="3">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .=  '<option value="'.$companies[$i]['id'].'" ';
				if ($companies[$i]['id'] == $this->data['msgtemplate']['compID']) {
					$this->data['companies'].=' selected="selected"';
				}
				$this->data['companies'] .= '>'.$companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";
			
			if ($this->data['msgtemplate']['bfrHDR'] == 1) {
				$this->data['msgtemplate']['bfrHDR'] = 'checked="checked"';
			} else {
				$this->data['msgtemplate']['bfrHDR'] = '';
			}
			if ($this->data['msgtemplate']['bfrMDL'] == 1) {
				$this->data['msgtemplate']['bfrMDL'] = 'checked="checked"';
			} else {
				$this->data['msgtemplate']['bfrMDL'] = '';
			}
			if ($this->data['msgtemplate']['bfrFTR'] == 1) {
				$this->data['msgtemplate']['bfrFTR'] = 'checked="checked"';
			} else {
				$this->data['msgtemplate']['bfrFTR'] = '';
			}
			if ($this->data['msgtemplate']['bfrSGNT'] == 1) {
				$this->data['msgtemplate']['bfrSGNT'] = 'checked="checked"';
			} else {
				$this->data['msgtemplate']['bfrSGNT'] = '';
			}
			
			if ($this->data['msgtemplate']['active'] == 1) {
				$this->data['msgtemplate']['active'] = 'checked="checked"';
			} else {
				$this->data['msgtemplate']['active'] = '';
			}
			
			$this->data['heading_title'] = '<b>'. $this->data['text_new_mailtmpl']. '</b>';
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_edit']. '</b>',
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('mailtmpl/mailtmpl_form',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 
			if ( empty($this->mailtmpl_model->get_msgtemplate_fullnames($data['id'])) ){
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			if (! empty($data['URLhdr']) ) {
				$data['URLhdr'] = 1;
			} else {
				$data['URLhdr'] = 0;
			}
			if ( ! empty($data['URLbdy']) ) {
				$data['URLbdy'] = 1;
			} else {
				$data['URLbdy'] = 0;
			}
			if ( ! empty($data['URLftr']) ) {
				$data['URLftr'] = 1;
			} else {
				$data['URLftr'] = 0;
			}
			if ( ! empty($data['URLsgnt']) ) {
				$data['URLsgnt'] = 1;
			} else {
				$data['URLsgnt'] = 0;
			}
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}

			$this->mailtmpl_model->set_msgtemplate($data);
			redirect($this->base_path);
		}
	}

	function view (){
		$msgtemplate_id = $this->uri->segment(4);
		$this->data['msgtemplate'] = $this->mailtmpl_model->get_msgtemplate_fullnames($msgtemplate_id);
		if ( empty($this->data['msgtemplate']) ) {
			$warning = array();
			$warning['code'] = '01';
			$this->show_warning_page($warning);
			return;
		}
		if ($this->data['msgtemplate']['bfrHDR'] == 1) {
			$this->data['msgtemplate']['bfrHDR'] = $this->data['text_yes'];
		} else {
			$this->data['msgtemplate']['bfrHDR'] = $this->data['text_no'];
		}
		if ($this->data['msgtemplate']['bfrMDL'] == 1) {
			$this->data['msgtemplate']['bfrMDL'] = $this->data['text_yes'];
		} else {
			$this->data['msgtemplate']['bfrMDL'] = $this->data['text_no'];
		}
		if ($this->data['msgtemplate']['bfrFTR'] == 1) {
			$this->data['msgtemplate']['bfrFTR'] = $this->data['text_yes'];
		} else {
			$this->data['msgtemplate']['bfrFTR'] = $this->data['text_no'];
		}
		if ($this->data['msgtemplate']['bfrSGNT'] == 1) {
			$this->data['msgtemplate']['bfrSGNT'] = $this->data['text_yes'];
		} else {
			$this->data['msgtemplate']['bfrSGNT'] = $this->data['text_no'];
		}


		if ($this->data['msgtemplate']['active'] == 1) {
			$this->data['msgtemplate']['active'] = $this->data['text_yes'];
		} else {
			$this->data['msgtemplate']['active'] = $this->data['text_no'];
		}
		
		$this->data['heading_title'] = $this->data['text_mailtmpl'] . 
			' :: ' .$this->data['msgtemplate']['name'] ;
		
		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'. $this->data['text_view']. '</b>', 
			'href'=> '', 'separator' => ' :: ');

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('mailtmpl/mailtmpl_view',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function activate(){	// Updates engine status as activate
		$msgtemplate_id = $this->uri->segment(4);
		$this->mailtmpl_model->activate_msgtemplate($msgtemplate_id);

		redirect($this->base_path);
	}

	function desactivate(){	// Updates engine status as inactive
		$msgtemplate_id = $this->uri->segment(4);
		$this->mailtmpl_model->desactivate_msgtemplate($msgtemplate_id);

		redirect($this->base_path);
	}
	
	function show_warning_page($msg){
		switch ($msg['code']) {
		case "01":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Unknown engine';
			$warning['message'] = 'Sorry. There is not exists such as engine in DB.';
			break;
		case "02":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Directory exists';
			$warning['message'] = 'Engine\'s directory exists. You must use another name.';
			break;
		case "03":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Dir not writable';
			$warning['message'] = 'Engine\'s directory is not writable.';
			break;
		case "04":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Temp file not moved';
			$warning['message'] = 'Could not move uploaded temporary file to it\'s target location.';
			break;
		case "05":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Data fields not dump';
			$warning['message'] = 'Could not dump data fields from PDF file.';
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
