<?php
class User extends CI_Controller {

	var $data_header = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();

		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		
		$this->load->model('common/user_model');
		$this->load->model('common/company_model');
		$this->load->config('pagination');
		$this->load->library("menu");
		$this->load->library("pagination");

		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: Clients', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'Users', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/user', 'separator' => ' -&gt; ');

		$this->base_path = base_url() . $this->config->item('index_page') . '/common/user/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_delete'] = $this->base_path. 'delete';
		$this->data['path_update'] = $this->base_path. 'update/';
		$this->data['path_update_pwd'] = $this->base_path. 'update_pwd/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
		$this->data['path_company'] = base_url() . $this->config->item('index_page') . 
				'/common/company/update/';
		$this->data['path_insert_company'] = base_url() . $this->config->item('index_page') . 
				'/common/company/insert/';
						
		$this->data['error_warning'] = '';
		$this->data['success'] = '';

		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('common', $language);
		$this->lang->load('user', $language);
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_delete'] = $this->lang->line('common_delete');
		$this->data['button_edit'] = $this->lang->line('common_edit');
		$this->data['button_new'] = $this->lang->line('common_new');
		$this->data['button_save'] = $this->lang->line('common_save');
		$this->data['button_passwd'] = $this->lang->line('text_change_passwd');

		
		$this->data['column_action'] = $this->lang->line('common_action');
		$this->data['column_company'] = $this->lang->line('text_company');
		$this->data['column_name'] = $this->lang->line('text_name');
		$this->data['column_status'] = $this->lang->line('common_status');
		$this->data['column_username'] = $this->lang->line('text_username');
		
		$this->data['text_active'] = $this->lang->line('common_active');
		$this->data['text_activate'] = $this->lang->line('common_activate');
		$this->data['text_desactivate'] = $this->lang->line('common_desactivate');
		$this->data['text_edit'] = $this->lang->line('common_edit');
		$this->data['text_inactive'] = $this->lang->line('common_inactive');
		$this->data['text_new'] = $this->lang->line('common_new');
		$this->data['text_no_results'] = $this->lang->line('common_no_results');
		$this->data['text_view'] = $this->lang->line('common_view');
		$this->data['text_yes'] = $this->lang->line('common_yes');
		$this->data['text_no'] = $this->lang->line('common_no');
		
		$this->data['text_cellphone'] = $this->lang->line('text_cellphone');
		$this->data['text_company'] = $this->lang->line('text_company');
		$this->data['text_company_admin'] = $this->lang->line('text_company_admin');
		$this->data['text_edit_passwd'] = $this->lang->line('text_edit_passwd');
		$this->data['text_email'] = $this->lang->line('text_email');
		$this->data['text_firstName'] = $this->lang->line('text_firstName');
		$this->data['text_lastName'] = $this->lang->line('text_lastName');
		$this->data['text_new_user'] = $this->lang->line('text_new_user');
		$this->data['text_password'] = $this->lang->line('text_password');
		$this->data['text_password2'] = $this->lang->line('text_password2');
		$this->data['text_phone'] = $this->lang->line('text_phone');
		$this->data['text_user'] = $this->lang->line('text_user');
		$this->data['text_username'] = $this->lang->line('text_username');
		$this->data['text_users_list'] = $this->lang->line('text_users_list');
		
	}

	function index() {	// Shows a list of users

		$this->data['heading_title'] = $this->data['text_users_list'];

		// Calc for pagination
		// base_url should use below view(header) to avoid config array overlap
		$config['base_url'] =  $this->base_path;
		$config['total_rows'] = $this->user_model->record_count();
		$limit = $this->config->item('per_page');
		// (...)/common/user/index/2  number 2 is at 4th segment;
		$page = $this->uri->segment(4);
	        $this->data['users'] = $this->user_model->fetch_users($limit, $page);

		$this->pagination->initialize($config);
		$this->data["pagination"] = $this->pagination->create_links();

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('user/user_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	function insert(){
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', $this->data['text_username'], 'trim|required');
		$this->form_validation->set_rules('firstName', $this->data['text_firstName'], 'trim|required');
		$this->form_validation->set_rules('lastName', $this->data['text_lastName'], 'trim|required');
		$this->form_validation->set_rules('email', $this->data['text_email'], 'trim|required');
		$this->form_validation->set_rules('passwd1', $this->data['text_password'], 'trim|required');
		$this->form_validation->set_rules('passwd2', $this->data['text_password2'], 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->data['heading_title'] = '<b>'. $this->data['text_new_user']. '</b>';
		
			$companies = $this->company_model->fetch_companies();
			if ($companies === false) {
				// 'ncd' == no company defined
				redirect($this->data['path_insert_company']. 'ncd');
				return true;
			}
		
			// Create a companies select options
			$companies_number = count($companies);
			$this->data['companies'] = '<select name="company" tabindex="9">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .=  '<option value="'.$companies[$i]['id'].'">' .
					$companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";
		
		
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('user/user_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 
	
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}
		
			if ( ! empty($data['isCompAdmin']) ) {
				$data['isCompAdmin'] = 1;
			} else {
				$data['isCompAdmin'] = 0;
			}
		
			if ( (empty($data['passwd1']) || empty($data['passwd2']) ) && 
				(strcmp($data['passwd1'], $data['passwd2']) != 0) ){
				redirect($this->data['path_insert']);
			} else {
				$data['password'] = sha1($data['passwd1']);
			}
			if (! empty($data['username']) && ! empty($data['email']) ){
					$this->user_model->set_new_user($data);
			}

			redirect($this->base_path);
		}
	}

	function delete () {
		$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter

		$items = count ($data['selected']);
		for ($i = 0; $i < $items; $i++) {
			$this->user_model->delete_user($data['selected'][$i]);
		}

		redirect($this->base_path);
	}

	function update (){	// Edit/Update User data
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', $this->data['text_username'], 'trim|required');
		$this->form_validation->set_rules('firstName', $this->data['text_firstName'], 'trim|required');
		$this->form_validation->set_rules('lastName', $this->data['text_lastName'], 'trim|required');
		$this->form_validation->set_rules('email', $this->data['text_email'], 'trim|required');
		$this->form_validation->set_rules('company', $this->data['text_company'], 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
		
			$user_id = $this->uri->segment(4);
			$this->data['user'] = $this->user_model->get_user($user_id);
			if ( empty($this->data['user']) ) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			if ($this->data['user']['active'] == 1) {
				$this->data['user']['active'] = 'checked="checked"';
			} else {
				$this->data['user']['active'] = '';
			}
			if ($this->data['user']['isCompAdmin'] == 1) {
				$this->data['user']['isCompAdmin'] = 'checked="checked"';
			} else {
				$this->data['user']['isCompAdmin'] = '';
			}

			// Create companies select options		
			$companies = $this->company_model->fetch_companies();
			$companies_number = count($companies);
			$this->data['companies'] = '<select name="company" tabindex="9">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .=  '<option value="'.$companies[$i]['id'] . '"';
				if ($companies[$i]['id'] == $this->data['user']['companyID'] ) {
					$this->data['companies'] .=' selected="selected" ';
				}			
				$this->data['companies'] .='>' . $companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";
		
		
			$this->data['heading_title'] = $this->data['text_user'] .
				' :: ' .$this->data['user']['username'] .
				' [<i>'. $this->data['user']['firstName'] . 
				' ' . $this->data['user']['lastName'] . '</i>]';

			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>',
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('user/user_form',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
			if ( empty($this->user_model->get_user($data['id'])) ) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}
			if ( ! empty($data['isCompAdmin']) ) {
				$data['isCompAdmin'] = 1;
			} else {
				$data['isCompAdmin'] = 0;
			}
		
			if (! empty($data['username']) && ! empty($data['email']) ){
				$this->user_model->set_user($data);
			}

			redirect($this->base_path);
		}
	}
	
	function update_pwd (){	// Edit/Update User password

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('passwd1', $this->data['text_password'], 
							'trim|required');
		$this->form_validation->set_rules('passwd2', $this->data['text_password2'], 
							'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$user_id = $this->uri->segment(4);
			if ( empty($this->user_model->get_user($user_id)) ) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			
			$this->data['user'] = $this->user_model->get_user($user_id);
			$this->data['heading_title'] = $this->data['text_user'] . 
				' :: ' .$this->data['user']['username'] .
				' [<i>'. $this->data['user']['firstName'] . 
				' ' . $this->data['user']['lastName'] . '</i>]';

			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_edit_passwd']. '</b>', 
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('user/user_passwd',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {

			$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
			if ( empty($this->user_model->get_user($data['id'])) ) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
			$data['id'] = $user_id;

			if  ( empty($data['passwd1']) && empty($data['passwd2']) ) {
				$data['password'] = '';
			} else if (strcmp($data['passwd1'], $data['passwd2']) === 0){
				$data['password'] = sha1($data['passwd1']);
			} 

			if (! empty($data['password']) ){
				$this->user_model->set_user_password($data);
			}

			redirect($this->base_path);
		}
	}

	function view (){
		$user_id = $this->uri->segment(4);
		$this->data['user'] = $this->user_model->get_user($user_id);

		if ( empty($this->data['user']) ) {
			$warning = array();
			$warning['code'] = '01';
			$this->show_warning_page($warning);
			return;
		}
		
		if ($this->data['user']['active'] == 1) {
			$this->data['user']['active'] = 'checked="checked"';
		} else {
			$this->data['user']['active'] = '';
		}
		if ($this->data['user']['isCompAdmin'] == 1) {
			$this->data['user']['isCompAdmin'] = 'checked="checked"';
		} else {
			$this->data['user']['isCompAdmin'] = '';
		}

		// Create companies select options		
		$companies = $this->company_model->fetch_companies();
		$companies_number = count($companies);
		$this->data['companies'] = '';
		for ($i = 0; $i < $companies_number; $i ++) {
			if ($companies[$i]['id'] == $this->data['user']['companyID'] ) {
				$this->data['companies'] = $companies[$i]['name'];
			}			
		}
	
		$this->data['heading_title'] = $this->data['text_user'] . 
			' :: ' .$this->data['user']['username'] .
			' [<i>'. $this->data['user']['firstName'] . 
			' ' . $this->data['user']['lastName'] . '</i>]';

		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'. $this->data['text_view']. '</b>', 
			'href'=> '', 'separator' => ' :: ');

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('user/user_view',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function activate(){	// Updates user status as activate
		$user_id = $this->uri->segment(4);
		$this->user_model->activate_user($user_id);

		redirect($this->base_path);
	}

	function desactivate(){	// Updates user status as inactive
		$user_id = $this->uri->segment(4);
		$this->user_model->desactivate_user($user_id);

		redirect($this->base_path);
	}
	
	function show_warning_page($msg){
		switch ($msg['code']) {
		case "01":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Unknown administrator';
			$warning['message'] = 'Sorry. There is not exists such as user in DB.';
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
