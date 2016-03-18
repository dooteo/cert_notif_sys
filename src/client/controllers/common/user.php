<?php
include ("menu.php");
class User extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('isLoggedIn'))  {
			redirect('/login/show_login');
		}
		
		$this->load->model('common/user_model');
		$this->load->model('common/company_model');
		$this->load->config('pagination');
		$this->load->library("pagination");

		$user_id = $this->session->userdata('id');
		$this->data['user'] = $this->user_model->get_user($user_id);
	
		$this->data_header = get_menu($this->data['user']['isCompAdmin']) ;
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: System', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'My Data', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/user', 'separator' => ' -&gt; ');

		$this->base_path = base_url() . $this->config->item('index_page') . '/common/user/';
		$this->data['path_delete'] = $this->base_path. 'delete/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_update'] = $this->base_path. 'update/';
		$this->data['path_view'] = $this->base_path . 'view/';
		$this->data['path_list'] = $this->base_path . 'ulist/';
		$this->data['path_update_pwd'] = $this->base_path. 'update_pwd/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
		$this->data['path_company'] = base_url() . $this->config->item('index_page') . 
				'/common/company/update/';
		$this->data['path_insert_company'] = base_url() . $this->config->item('index_page') . 
				'/common/company/insert/';
						
		$this->data['error_warning'] = '';
		$this->data['success'] = '';

		$this->data['button_cancel'] = 'Cancel';
		$this->data['button_delete'] = 'Delete';
		$this->data['button_edit'] = 'Edit';
		$this->data['button_new'] = 'New';
		$this->data['button_save'] = 'Save';
		$this->data['button_passwd'] = 'Change Password';

		$this->data['column_action'] = 'Action';
		$this->data['column_company'] = 'Company';
		$this->data['column_name'] = 'Name';
		$this->data['column_username'] = 'User';
		$this->data['column_status'] = 'Status';
		
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
		
		$this->data['text_cellphone'] = 'Cellphone';
		$this->data['text_company'] = 'Company';
		$this->data['text_company_admin'] = 'Company administrator';
		$this->data['text_edit_passwd'] = 'Edit password';
		$this->data['text_email'] = 'Email';
		$this->data['text_firstName'] = 'First name';
		$this->data['text_lastName'] = 'Last name';
		$this->data['text_new_user'] = 'New user';
		$this->data['text_password'] = 'Password';
		$this->data['text_password2'] = 'Confirm password';
		$this->data['text_phone'] = 'Phone';
		$this->data['text_user'] = 'User';
		$this->data['text_username'] = 'Username';
		$this->data['text_users_list'] = 'Users list';
		
	}

	function index() {	// Shows user data
		
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

		// Get company data
		$company = $this->company_model->get_company($this->data['user']['companyID']);
		$this->data['company'] = $company['name'];

		$this->data['heading_title'] = $this->data['text_user'] . 
			' :: ' .$this->data['user']['username'] .
			' [<i>'. $this->data['user']['firstName'] . 
			' ' . $this->data['user']['lastName'] . '</i>]';

		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'. $this->data['text_view']. '</b>', 
			'href'=> '', 'separator' => ' :: ');
		$this->data['pagination']= "";
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('user/user_view',$this->data);
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
		
			$company = $this->company_model->get_company($this->data['user']['companyID']);
			if ($company === false) {
				// 'ncd' == no company defined
				redirect($this->data['path_insert_company']. 'ncd');
				return true;
			}
		
			// Create a companies select options
			$this->data['company'] = $company['name'];
		
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('user/user_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$posted = $this->input->post(NULL, TRUE); 
	
			if ( ! empty($posted['active']) ) {
				$posted['active'] = 1;
			} else {
				$posted['active'] = 0;
			}
		
			if ( ! empty($posted['isCompAdmin']) ) {
				$posted['isCompAdmin'] = 1;
			} else {
				$posted['isCompAdmin'] = 0;
			}
		
			if ( (empty($posted['passwd1']) || empty($posted['passwd2']) ) && 
				(strcmp($posted['passwd1'], $posted['passwd2']) != 0) ){
				redirect($this->data['path_insert']);
			} else {
				$posted['password'] = sha1($posted['passwd1']);
			}
			$posted['companyID'] = $this->data['user']['companyID'];

			if (! empty($posted['username']) && ! empty($posted['email']) ){
					$this->user_model->set_new_user($posted);
			}

			redirect($this->data['path_list']);
		}
	}

	function delete () {

		if ( $this->data['user']['isCompAdmin'] != 1 )  {
			redirect($this->data['path_base']);
		}
		
		$posted = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		if (empty($posted)) {
			// Nothing to delete
			redirect($this->data['path_list']);
		}		

		$admin_company_id = $this->data['user']['companyID'];
		
		$items = count ($posted['selected']);
		for ($i = 0; $i < $items; $i++) {
			$user = $this->user_model->get_user($posted['selected'][$i]);
			// user and CompAdmin must be in same company
			if ( ($admin_company_id == $user['companyID']) && 
				($this->data['user']['id'] != $posted['selected'][$i]) ){
				$this->user_model->delete_user($posted['selected'][$i]);
			}
		}
		
		redirect($this->data['path_list']);
		
	}
	
	function update (){	// Edit/Update User data
		// Only Company administrator can update company other users data

		if (($this->data['user']['isCompAdmin'] == 1)  && 
		   (! empty($this->uri->segment(4))) ){
			$user_id = $this->uri->segment(4);
			$my_own_data = 0;
		} else {
			// Get current session's user ID
			$user_id = $this->session->userdata('id');
			$my_own_data = 1;
		}
		
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('firstName', $this->data['text_firstName'], 'trim|required');
		$this->form_validation->set_rules('lastName', $this->data['text_lastName'], 'trim|required');
		$this->form_validation->set_rules('email', $this->data['text_email'], 'trim|required');
			
		if ($this->form_validation->run() == FALSE) {
			$this->data['user'] = $this->user_model->get_user($user_id);
			$isCompAdmin = $this->data['user']['isCompAdmin'];

			if ($this->data['user']['active'] == 1) {
				$this->data['user']['active'] = 'checked="checked"';
			} else {
				$this->data['user']['active'] = '';
			}
			
			if ($my_own_data === 0) {
				if ($isCompAdmin == 1) {
					$this->data['user']['isCompAdmin'] = '<input type="checkbox" name="isCompAdmin" tabindex="10" checked="checked"/>';
				} else {
					$this->data['user']['isCompAdmin'] = '<input type="checkbox" name="isCompAdmin" tabindex="10" />';
				}
			} else {
				if ($isCompAdmin == 1) {
					$this->data['user']['isCompAdmin'] = '<input type="checkbox" name="isCompAdmin" tabindex="10" checked="checked"/>';
				} else {
					$this->data['user']['isCompAdmin'] = $this->data['text_no'] ;
				}
			}

			// Create companies select options
			$this->data['company'] = $this->company_model->get_company($this->data['user']['companyID']);		
			$this->data['company'] = $this->data['company']['name'];
			$this->data['heading_title'] = $this->data['text_user'] .
				' :: ' .$this->data['user']['username'] .
				' [ <i>'. $this->data['user']['firstName'] . 
				' ' . $this->data['user']['lastName'] . '</i> ]';

			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_edit']. '</b>',
				'href'=> '', 'separator' => ' :: ');
				
			if ($my_own_data === 0) {
				// To redirect to other user data view
				$this->data['path_update'] = $this->data['path_update']. $user_id;
			}
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('user/user_form',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$posted = $this->input->post(NULL, TRUE); 
			
			// Security check, only compadmin can modify other user data
			if ( ($my_own_data === 1) && (! empty( $this->uri->segment(4))) && ($user_id !=  $this->uri->segment(4)) ) {
				redirect($this->base_path);
			}
			
			// Get user DB data
			$this->data['user'] = $this->user_model->get_user($user_id);
			$posted['id'] = $user_id; 

			if ($my_own_data === 0) {
				if (! empty($posted['isCompAdmin'])) {
					$posted['isCompAdmin'] = 1;
				} else {
					$posted['isCompAdmin'] = 0;
				}

			} else if ($this->data['user']['isCompAdmin'] == 1 ) {
				if (! empty($posted['isCompAdmin'])) {
					$posted['isCompAdmin'] = 1;
				} else {
					$posted['isCompAdmin'] = 0;
				}
			}

			if ( ! empty($posted['active']) ) {
				$posted['active'] = 1;
			} else {
				$posted['active'] = 0;
			}
			
			$this->user_model->set_user($posted);
			
			if ($my_own_data === 1) {
				redirect($this->base_path);
			} else {
				redirect($this->data['path_view'].$user_id);
			}
		}
	}

	
	function update_pwd (){	// Edit/Update User password
		// Only Company administrator can update company other users data
		if ($this->data['user']['isCompAdmin'] == 1) {
			$user_id = $this->uri->segment(4);
			$my_own_data = 0;
		} else {
			// Get current session's user ID
			$user_id = $this->session->userdata('id');
			$my_own_data = 1;
		}

		if ( empty($user_id) ){
			redirect($this->base_path);
			return false;
		}

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('passwd1', $this->data['text_password'], 'trim|required');
		$this->form_validation->set_rules('passwd2', $this->data['text_password2'], 'trim|required');



		if ($this->form_validation->run() == FALSE) {
			$user_posted_id = $this->uri->segment(4);
			
			$this->data['user'] = $this->user_model->get_user($user_id);
			$isCompAdmin = $this->data['user']['isCompAdmin'];
			// Only company administrator can edit company's other users
			// otherwise, each user can edit his own password.
			if (($user_id != $user_posted_id) && ($isCompAdmin == 1)){
				$user_id = $user_posted_id;
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
			// returns all POST items with XSS filter
			$posted = $this->input->post(NULL, TRUE);
			$user_posted_id = $this->uri->segment(4);
			//$this->data['user'] = $this->user_model->get_user($user_id);
			//$isCompAdmin = $this->data['user']['isCompAdmin'];

			if ( ($user_posted_id !== $user_id) && ($this->data['user']['isCompAdmin'] == 0) ){
				// A non company admin user only can edit its own data
				redirect($this->base_path);
			}

			if  ( empty($posted['passwd1']) && empty($posted['passwd2']) ) {
				$posted['password'] = '';
			} else if (strcmp($posted['passwd1'], $posted['passwd2']) === 0){
				$posted['password'] = sha1($posted['passwd1']);
			} 

			if (! empty($posted['password']) ){
				$posted['id'] = $user_posted_id;
				$this->user_model->set_user_password($posted);
			}

			if ($my_own_data === 1) {
				redirect($this->base_path);
			} else {
				redirect($this->data['path_view'].$user_id);
			}
		}
	}

	// Shows a company's users list
	function ulist() {
		// Only for company's administrator
		if (! $this->data['user']['isCompAdmin']) {
			redirect('/');
		}
		
		$company = $this->company_model->get_company($this->data['user']['companyID']);
		$this->data['heading_title'] = $company['name'] . " :: " . $this->data['text_users_list'];
	        $this->data['users'] = $this->user_model->get_company_users($company['id']);
		$this->data['pagination']= "";
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('user/user_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	// Shows a user data
	function view (){
		if ($this->data['user']['isCompAdmin'] != 1) {
			redirect($this->base_path);
		}
		$user_id = $this->uri->segment(4);
		$company_id = $this->data['user']['companyID'];
		$this->data['user'] = $this->user_model->get_user($user_id);
	
		if ($this->data['user']['companyID']!= $company_id) {
			redirect($this->base_path);
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

				// Create company data
		$company = $this->company_model->get_company($this->data['user']['companyID']);
		$this->data['company'] = $company['name'];
	
		$this->data['heading_title'] = $this->data['text_user'] . 
			' :: ' .$this->data['user']['username'] .
			' [<i>'. $this->data['user']['firstName'] . 
			' ' . $this->data['user']['lastName'] . '</i>]';

		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'. $this->data['text_view']. '</b>', 
			'href'=> '', 'separator' => ' :: ');

		$this->data['pagination'] = "";
		$this->data['path_update'] = $this->data['path_update']. $user_id;
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('user/user_view',$this->data);
		$this->load->view('templates/footer',$this->data);
		
	}
	function activate(){	// Updates user status as activate
		if ($this->data['user']['isCompAdmin'] != 1) {
			redirect($this->data['path_base']);
		}
		$user_id = $this->uri->segment(4);
		$this->user_model->activate_user($user_id);

		redirect($this->data['path_list']);
	}

	function desactivate(){	// Updates user status as inactive
		if ($this->data['user']['isCompAdmin'] != 1) {
			redirect($this->data['path_base']);
		}
		$user_id = $this->uri->segment(4);
		$this->user_model->desactivate_user($user_id);

		redirect($this->data['path_list']);
	}
	
	function msg_handler ($msg){
		switch ($msg) {
		case "ncd":
			$warning = array('error_warning'=>'No company defined.');
			break;
		default:
			$warning = array('error_warning'=>'');
		}
		return $warning;
	}
}
