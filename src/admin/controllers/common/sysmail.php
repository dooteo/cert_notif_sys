<?php
class Sysmail extends CI_Controller {

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
		$this->config->load('mail');
		
		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: System', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'Mail Setup', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/mail', 'separator' => ' -&gt; ');

		
		$this->base_path = base_url() .$this->config->item('index_page') .
					'/common/sysmail/';

		$this->data['path_update'] = $this->base_path . 'update/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_cancel'] = $this->base_path;

		$this->data['error_warning'] = '';
		$this->data['success'] = '';
		
		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('common', $language);
		$this->lang->load('sysmail', $language);
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_edit'] = $this->lang->line('common_edit');
		$this->data['button_save'] = $this->lang->line('common_save');
		
		
		$this->data['text_mail_setup'] = $this->lang->line('text_mail_setup');
		$this->data['text_SMTP_host'] = $this->lang->line('text_host');
		$this->data['text_SMTP_port'] = $this->lang->line('text_port');
		$this->data['text_SMTP_auth'] = $this->lang->line('text_SMTP_auth');
		$this->data['text_SMTP_sec'] = $this->lang->line('text_SMTP_sec');
		$this->data['text_SMTP_user'] = $this->lang->line('text_SMTP_user');
		$this->data['text_SMTP_passwd'] = $this->lang->line('text_SMTP_passwd');
		$this->data['text_SMTP_From'] = $this->lang->line('text_SMTP_From');
		$this->data['text_SMTP_FromName'] = $this->lang->line('text_SMTP_FromName');
		$this->data['text_SMTP_ReplyTo'] = $this->lang->line('text_SMTP_ReplyTo');
		$this->data['text_SMTP_ReplyToName'] = $this->lang->line('text_SMTP_ReplyToName');
		$this->data['text_WordWrap'] = $this->lang->line('text_WordWrap');
		$this->data['text_none'] = $this->lang->line('common_none');
		
	}

	function index() {	// Show mail setup
		
		$this->data['heading_title'] = $this->data['text_mail_setup'];
		
		$this->data['mailconf']['host'] = $this->config->item('unml_mail_host');
		$this->data['mailconf']['port'] = $this->config->item('unml_mail_port');
		$this->data['mailconf']['SMTP_auth'] = $this->config->item('unml_mail_SMTP_auth');
		$this->data['mailconf']['SMTP_sec'] = $this->config->item('unml_mail_SMTP_sec');
		$this->data['mailconf']['SMTP_user'] = $this->config->item('unml_mail_SMTP_user');
		$this->data['mailconf']['SMTP_pswd'] = $this->config->item('unml_mail_SMTP_pswd');
		$this->data['mailconf']['From'] = $this->config->item('unml_mail_From');
		$this->data['mailconf']['FromName'] = $this->config->item('unml_mail_FromName');
		$this->data['mailconf']['ReplyTo'] = $this->config->item('unml_mail_ReplyTo');
		$this->data['mailconf']['ReplyToName'] = $this->config->item('unml_mail_ReplyToName');
		$this->data['mailconf']['WrodWrap'] = $this->config->item('unml_mail_WrodWrap');
		
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('mail/mail_view',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	function update($msg_type = ""){	// Edit/Update Company data
		$this->data['heading_title'] = $this->data['text_mail_setup'];
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('MailHost', $this->data['text_SMTP_host'], 'trim|required');
		$this->form_validation->set_rules('MailPort', $this->data['text_SMTP_port'], 'trim|required');
		$this->form_validation->set_rules('MailSMTPuser', $this->data['text_SMTP_user'], 'trim|required');
		$this->form_validation->set_rules('MailSMTPpasswd', $this->data['text_SMTP_passwd'], 'trim|required');
		$this->form_validation->set_rules('MailFrom', $this->data['text_SMTP_From'], 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			if (! empty($msg_type) ) {
				foreach ($this->msg_handler($msg_type) as $key => $value){
					$this->data[$key] = $value;
				}
			} 
			$this->data['mailconf']['host'] = $this->config->item('unml_mail_host');
			$this->data['mailconf']['port'] = $this->config->item('unml_mail_port');
			$this->data['mailconf']['SMTP_auth'] = $this->config->item('unml_mail_SMTP_auth');
			$this->data['mailconf']['SMTP_sec'] = $this->config->item('unml_mail_SMTP_sec');
			$this->data['mailconf']['SMTP_user'] = $this->config->item('unml_mail_SMTP_user');
			$this->data['mailconf']['SMTP_pswd'] = $this->config->item('unml_mail_SMTP_pswd');
			$this->data['mailconf']['From'] = $this->config->item('unml_mail_From');
			$this->data['mailconf']['FromName'] = $this->config->item('unml_mail_FromName');
			$this->data['mailconf']['ReplyTo'] = $this->config->item('unml_mail_ReplyTo');
			$this->data['mailconf']['ReplyToName'] = $this->config->item('unml_mail_ReplyToName');
			$this->data['mailconf']['WrodWrap'] = $this->config->item('unml_mail_WrodWrap');
		
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('mail/mail_form',$this->data);
			$this->load->view('templates/footer',$this->data);

		} else {

			// Update changed data into DB
			$data_aux = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
			$data = array(
				'unml_mail_host'=>$data_aux['MailHost'], 
				'unml_mail_port'=>$data_aux['MailPort'],
				'unml_mail_SMTP_sec'=>$data_aux['MailSMTPSec'],
				'unml_mail_SMTP_user'=>$data_aux['MailSMTPuser'],
				'unml_mail_SMTP_pswd'=>$data_aux['MailSMTPpasswd'],
				'unml_mail_From'=>$data_aux['MailFrom'],
				'unml_mail_FromName'=>$data_aux['MailFromName'],
				'unml_mail_ReplyTo'=>$data_aux['MailReplyTo'],
				'unml_mail_ReplyToName'=>$data_aux['MailReplyToName'],
				'unml_mail_WrodWrap'=>$data_aux['WordWrap']
				);
			if (empty($data_aux['MailSMTPAuth'])){
				$data['unml_mail_SMTP_auth'] = 'false';
			} else {
				$data['unml_mail_SMTP_auth'] = $data_aux['MailSMTPAuth'];
			}
			$config_path = $_SERVER['DOCUMENT_ROOT'] . '/config/mail.php';
			$admin_config_path = $_SERVER['DOCUMENT_ROOT'] . '/admin/config/mail.php';
			
			if (zdn_write_custom_config($config_path, $data) == false) {
				// cfnc = config file not created
				redirect($this->data['path_update']. 'cfnc');
			} else if (zdn_write_custom_config($admin_config_path, $data) == false) {
				// acfnc = admin config file not created
				redirect($this->data['path_update']. 'acfnc');
			}
			
			// Reintroduce values to be used in 'mail_view' 
			$this->data['mailconf']['host'] = $data['unml_mail_host'];
			$this->data['mailconf']['port'] = $data['unml_mail_port'];
			$this->data['mailconf']['SMTP_auth'] = $data['unml_mail_SMTP_auth'];
			$this->data['mailconf']['SMTP_sec'] = $data['unml_mail_SMTP_sec'];
			$this->data['mailconf']['SMTP_user'] = $data['unml_mail_SMTP_user'];
			$this->data['mailconf']['SMTP_pswd'] = $data['unml_mail_SMTP_pswd'];
			$this->data['mailconf']['From'] = $data['unml_mail_From'];
			$this->data['mailconf']['FromName'] = $data['unml_mail_FromName'];
			$this->data['mailconf']['ReplyTo'] = $data['unml_mail_ReplyTo'];
			$this->data['mailconf']['ReplyToName'] = $data['unml_mail_ReplyToName'];
			$this->data['mailconf']['WrodWrap'] = $data['unml_mail_WrodWrap'];

			// Codeigniter does not refresh config file load
			// we can't use next statement: redirect($this->base_path);
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('mail/mail_view',$this->data);
			$this->load->view('templates/footer',$this->data);
		}
	}

	
	function msg_handler ($msg){
		switch ($msg) {
		case "cfnc":
			$warning = array('error_warning'=>'The unimail configuration file could not be written, please chmod ' . $config_path .' file to 777');
			break;
		case "acfnc":
			$warning = array('error_warning'=>'The admin unimail configuration file could not be written, please chmod ' . $admin_config_path .' file to 777');
			break;
		default:
			$warning = "";
		}
		return $warning;
	}

}
