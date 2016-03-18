<?php
include ("menu.php");
class Mailtmpl extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();
		
		if (!$this->session->userdata('isLoggedIn') ) {
			redirect('/login/show_login');
		}
		$this->load->model('common/user_model');
		$this->load->model('common/mailtmpl_model');
		$this->load->model('common/company_model');

		$this->load->config('unimail', FALSE, TRUE);
		
		$this->load->config('pagination');
		$this->load->library("pagination");
		$user_id = $this->session->userdata('id');
		$this->data['user'] = $this->user_model->get_user($user_id);
		
		$this->data_header = get_menu();
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
		
		$this->data['button_cancel'] = 'Cancel';
		$this->data['button_delete'] = 'Delete';
		$this->data['button_edit'] = 'Edit';
		$this->data['button_new'] = 'New';
		$this->data['button_save'] = 'Save';
		$this->data['button_csv'] = 'CSV';
		
		$this->data['column_action'] = 'Action';
		$this->data['column_name'] = 'Name';
		$this->data['column_company'] = 'Company';
		$this->data['column_language'] = 'Language';
		$this->data['column_multilang'] = 'Is Multilang';
		$this->data['column_status'] = 'Status';
		$this->data['column_action'] = 'Action';
		
		$this->data['text_active'] = 'Active';
		$this->data['text_activate'] = 'Activate';
		$this->data['text_desactivate'] = 'Desactivate';
		$this->data['text_edit'] = 'Edit';
		$this->data['text_inactive'] = 'Inactive';
		$this->data['text_new'] = 'New';
		$this->data['text_no_results'] = 'There is no results';
		$this->data['text_view'] = 'View';
		$this->data['text_yes'] = 'Yes';
		$this->data['text_no'] = 'No';

		$this->data['text_company'] = 'Company';
		$this->data['text_language'] = 'Language';
		$this->data['text_name'] = 'Name';
		$this->data['text_multilang'] = 'MultiLanguage';
		$this->data['text_subject'] = 'Subject';
		$this->data['text_subjtag'] = 'Subject Tag';
		$this->data['text_greeting'] = 'Greeting line';
		$this->data['text_bodyhdr'] = 'Body header text';
		$this->data['text_body'] = 'Body middle text';
		$this->data['text_bodyftr'] = 'Body Footer text';
		$this->data['text_signature'] = 'Company\'s Signature line';
		$this->data['text_comment'] = 'Comment';
		$this->data['text_isHTML'] = 'Is in HTML format';
		$this->data['text_mailtmpl'] = 'Mail template';
		$this->data['text_mailtmpls_list'] = 'Mail template list';
		$this->data['text_new_mailtmpl'] = 'New Mail template';
		$this->data['text_URL_before_hdr'] = 'Insert URL before body header';
		$this->data['text_URL_before_body'] = 'Insert URL before middle body';
		$this->data['text_URL_before_ftr'] = 'Insert URL before body footer';
		$this->data['text_URL_before_sgnt'] = 'Insert URL before signature';
		
	}

	function index() {	// Shows a list of msgtemplates
		$this->data['heading_title'] = $this->data['text_mailtmpls_list'];

		// Calc for pagination
		$config['base_url'] =  $this->base_path;
		$config['total_rows'] = $this->mailtmpl_model->record_count();
		$limit = $this->config->item('per_page');

		// (...)/common/mailtmpl/index/2  numer 2 is at 4th segment;
		$page = $this->uri->segment(4);
	        $this->data['mailtmpls'] = $this->mailtmpl_model->get_company_msgtemplates(
	        				$this->data['user']['companyID'], 
	        				$limit, $page);

		$this->pagination->initialize($config);
		$this->data["pagination"] = $this->pagination->create_links();

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('mailtmpl/mailtmpl_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function insert($msg_type = ""){
		// This section only belongs to company's administrator
		if (! $this->data['user']['isCompAdmin']) {
			redirect('/');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', $this->data['text_name'], 'trim|required');
		$this->form_validation->set_rules('langID', $this->data['text_name'], 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			$this->data['heading_title'] = '<b>'. $this->data['text_new_mailtmpl']. '</b>';

			// Get company data
			$this->data['company'] = $this->company_model->get_company(
						$this->data['user']['companyID']);

			// false == get_langpercompany_HTML function not called by AJAX
			$languages = $this->langcompany_model->get_company_languages(
							$this->data['user']['companyID']);
			$lang_count = count($languages);
			$this->data['languages'] = '<select name="langID" tabindex="4">';
			for ($i = 0; $i < $lang_count; $i++) {
				$this->data['languages'] .= '<option value="'.$languages[$i]['complangId'];
				$this->data['languages'] .= '">'. $languages[$i]['name'].' -- [' ;
				$this->data['languages'] .= $languages[$i]['code'].']</option>';
			}
			$this->data['languages'] .= '</select>';
			
			if (! empty($msg_type) ) {

				foreach ($this->msg_handler($msg_type) as $key => $value){
					$this->data[$key] = $value;
				}
			} 

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
			if ( ! empty($data['multilang']) ) {
				$data['multilang'] = 1;
			} else {
				$data['multilang'] = 0;
			}
			if ( ! empty($data['isHTML']) ) {
				$data['isHTML'] = 1;
			} else {
				$data['isHTML'] = 0;
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
		// This section only belongs to company's administrator
		if (! $this->data['user']['isCompAdmin']) {
			redirect('/');
		}
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
		// This section only belongs to company's administrator
		if (! $this->data['user']['isCompAdmin']) {
			redirect('/');
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

		$this->form_validation->set_rules('id', $this->data['text_name'], 'trim|required');
		$this->form_validation->set_rules('langID', $this->data['text_name'], 'trim|required');
		
		if (empty($_FILES['upfile']['name']) && empty($data)) {

			$msgtemplate_id = $this->uri->segment(4);
			$this->data['msgtemplate'] = $this->mailtmpl_model->get_msgtemplate_fullnames($msgtemplate_id);
		
			// Create a companies select options
			// Get company data
			$this->data['company'] = $this->company_model->get_company(
						$this->data['user']['companyID']);

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
			if ( ! empty($data['multilang']) ) {
				$data['multilang'] = 1;
			} else {
				$data['multilang'] = 0;
			}
			if ( ! empty($data['isHTML']) ) {
				$data['isHTML'] = 1;
			} else {
				$data['isHTML'] = 0;
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
		// This section only belongs to company's administrator
		if (! $this->data['user']['isCompAdmin']) {
			redirect('/');
		}
		$msgtemplate_id = $this->uri->segment(4);
		$this->mailtmpl_model->activate_msgtemplate($msgtemplate_id);

		redirect($this->base_path);
	}

	function desactivate(){	// Updates engine status as inactive
		// This section only belongs to company's administrator
		if (! $this->data['user']['isCompAdmin']) {
			redirect('/');
		}
		$msgtemplate_id = $this->uri->segment(4);
		$this->mailtmpl_model->desactivate_msgtemplate($msgtemplate_id);

		redirect($this->base_path);
	}
	

	function msg_handler ($msg){
		switch ($msg) {
		case 'msg':
			$warning = array('error_warning'=>'');
			break;
		default:
			$warning = "";
		}
		return $warning;
	}
}
