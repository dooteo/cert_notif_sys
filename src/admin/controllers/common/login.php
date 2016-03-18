<?php

class Login extends CI_Controller {
	var $data = array();
	
	public function __construct() {
		parent::__construct();
		
		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('login', $language);
		
		$this->data['text_enter_details'] = $this->lang->line('text_enter_details');
		$this->data['text_username'] = $this->lang->line('text_username');
		$this->data['text_password'] = $this->lang->line('text_password');
		$this->data['text_forgot_password'] = $this->lang->line('text_forgot_password');
		$this->data['text_error'] = $this->lang->line('text_error');
		$this->data['button_start_session'] = $this->lang->line('text_start_session');
		
	}
	
	function index() {
		if( $this->session->userdata('isLoggedIn') ) {
			redirect('/common/main/show_main');
		} else {
			$this->show_login(false);
		}
	}

	function login_user() {
		// Create an instance of the user model
		$this->load->model('common/login_model');

		// Grab the email and password from the form POST
		$username = $this->input->post('username');
		$pass  = $this->input->post('password');

		//Ensure values exist for email and pass, and validate the user's credentials
		if( $username && $pass && $this->login_model->validate_user($username,$pass)) {
			// If the user is valid, redirect to the main view
			redirect('common/main/show_main');
		} else {
			// Otherwise show the login screen with an error message.
			$this->show_login(true);
		}
	}

	function show_login( $show_error = false ) {
		$this->data['error'] = $show_error;
		$this->data['attributes'] = array('id' => 'login_form');
		$this->load->helper('form');
		$this->load->view('templates/header_login',$this->data);
		$this->load->view('login/login',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	function logout_user() {
		$this->load->model('common/login_model');
		$this->login_model->set_logout($this->session->userdata('username'));
		$this->session->sess_destroy();

		$this->index();
	}
}
