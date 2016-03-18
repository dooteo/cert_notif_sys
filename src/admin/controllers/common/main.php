<?php
class main extends CI_Controller{

	var $data_header = array();

	public function __construct() {
		parent::__construct();

		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		$this->load->library('menu');
		$prefs = array (
				'start_day'    => 'monday',
				'month_type'   => 'long',
				'day_type'     => 'short',
				'show_next_prev'  => TRUE,
				'next_prev_url'   => 'http://example.com/index.php/calendar/show/'
				);
		$this->load->library('zdn_calendar', $prefs);
		
		// Load Language's files
		$language = 'en_us';
		$this->lang->load('main', $language);
		
		date_default_timezone_set('Europe/Madrid') ;
		$this->data['firstdayweek'] = 1; // First day of Week,use 0 for Sunday
		
		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$data_header['breadcrumbs'][] = array(
			'text' =>  $this->lang->line('text_home'), 
			'href'=> '/admin/', 'separator' => '');
			
		$this->data['error_warning'] = '';
		$this->data['success'] = '';
		$this->data['text_calendar'] = 'Calendar';
		$this->data['text_dashboard'] = 'Dashboard';
		$this->data['text_statistics'] = 'Statistics';
		$this->data['text_home'] = 'Home';
	}

	/**
	* This is the controller method that drives the application.
	* After a user logs in, show_main() is called and the main
	* application screen is set up.
	*/
	function show_main() {

		// Get some data from the user's session
		$user_id = $this->session->userdata('id');
		$is_admin = $this->session->userdata('isAdmin');

		$this->data_header['breadcrumbs'][] = array('text' => $this->data['text_home'], 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
				
		$this->data['heading_title'] = '<b>'. $this->data['text_dashboard']. '</b>';
		
		$year = date('Y');
		$month = date('m');
		$cal_events = array(
		        3  => 'http://example.com/news/article/2006/03/',
		        7  => 'http://example.com/news/article/2006/07/',
		        13 => 'http://example.com/news/article/2006/13/',
		        14 => 'http://example.com/news/article/2006/26/'
		      );
		$this->data['calendar'] = $this->zdn_calendar->generate($year, $month, $cal_events);
		//$this->calendar->generate($this->uri->segment(3), $this->uri->segment(4));
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('main/main',$this->data);
		$this->load->view('templates/footer');
	}

}
