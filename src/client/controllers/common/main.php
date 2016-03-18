<?php
include ("menu.php");

class main extends CI_Controller{

	var $data_header = array();

	public function __construct() {
		parent::__construct();

		if ( !$this->session->userdata('isLoggedIn') ) {
			redirect('/login/show_login');
		}
		$this->load->model('common/user_model');
		$user_id = $this->session->userdata('id');
		$this->data['user'] = $this->user_model->get_user($user_id);
		
		$this->data_header = get_menu($this->data['user']['isCompAdmin']) ;
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$data_header['breadcrumbs'][] = array('text' => 'Inicio', 'href'=> '/admin/', 'separator' => '');
	}

	/**
	* This is the controller method that drives the application.
	* After a user logs in, show_main() is called and the main
	* application screen is set up.
	*/
	function show_main() {

		// Get some data from the user's session
		$data = array();
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('main',$data);
		$this->load->view('templates/footer');
	}

}
