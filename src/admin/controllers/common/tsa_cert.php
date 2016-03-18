<?php
class Tsa_cert extends CI_Controller {

	var $data_header = array();
	var $data = array();

	public function __construct() {
		parent::__construct();

		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		date_default_timezone_set('Europe/Madrid'); 
		
		$this->load->model('common/admin_model');
		$this->load->model('common/hashtype_model');
		$this->load->config('unimail', FALSE, TRUE);
		$this->load->library("timestamps");
		$this->load->library('menu');
		
		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: System', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'TSA Certification', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/tsa_cert', 'separator' => ' -&gt; ');

		$this->base_path = base_url() . $this->config->item('index_page') . '/common/tsa_cert/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_delete'] = $this->base_path. 'delete';
		$this->data['path_update'] = $this->base_path. 'update/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
						
		$this->data['error_warning'] = '';
		$this->data['success'] = '';

		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('common', $language);
		$this->lang->load('tsa_cert', $language);
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_delete'] = $this->lang->line('common_delete');
		$this->data['button_new'] = $this->lang->line('common_new');
		$this->data['button_save'] = $this->lang->line('common_save');
		$this->data['text_new'] = $this->lang->line('common_new');
		
		// For tabs in View 
		$this->data['text_settings'] = $this->lang->line('text_settings');
		$this->data['text_tsa_info'] = $this->lang->line('text_tsa_info');
		
		$this->data['text_tsa'] = $this->lang->line('text_tsa');
		$this->data['text_tsa_url'] = $this->lang->line('text_tsa_url');
		$this->data['text_secret'] = $this->lang->line('text_secret');
		$this->data['text_name'] = $this->lang->line('text_name');
		$this->data['text_new_cert'] = $this->lang->line('text_new_cert');
		$this->data['text_tsa_cert'] = $this->lang->line('text_tsa_cert');
		$this->data['text_cert_file'] = $this->lang->line('text_cert_file');
		$this->data['text_theme_name'] = $this->lang->line('text_theme_name');
		$this->data['text_identity'] = $this->lang->line('text_identity');
		$this->data['text_verif_by'] = $this->lang->line('text_verif_by');
		$this->data['text_expires'] = $this->lang->line('text_expires');
		$this->data['text_subject_name'] = $this->lang->line('text_subject_name');
		$this->data['text_c_country'] = $this->lang->line('text_c_country');
		$this->data['text_o_organization'] = $this->lang->line('text_o_organization');
		$this->data['text_ou_ogr_unit'] = $this->lang->line('text_ou_ogr_unit');
		$this->data['text_cn_common_name'] = $this->lang->line('text_cn_common_name');
		$this->data['text_email_address'] = $this->lang->line('text_email_address');
		$this->data['text_issuer_name'] = $this->lang->line('text_issuer_name');
		$this->data['text_hash_type'] = $this->lang->line('text_hash_type');
		
		$this->data['text_issued_certificate']=$this->lang->line('text_issued_certificate');
		$this->data['text_version'] = $this->lang->line('text_version');
		$this->data['text_serial_number'] = $this->lang->line('text_serial_number');
		$this->data['text_not_valid_before'] = $this->lang->line('text_not_valid_before');
		$this->data['text_not_valid_after'] = $this->lang->line('text_not_valid_after');
		$this->data['text_cert_fingerp'] = $this->lang->line('text_cert_fingerp');
		$this->data['text_pub_key_info'] = $this->lang->line('text_pub_key_info');
		$this->data['text_key_algor'] = $this->lang->line('text_key_algor');
		$this->data['text_key_param'] = $this->lang->line('text_key_param');
		$this->data['text_key_size'] = $this->lang->line('text_key_size');
		$this->data['text_key_sha1_fingp'] = $this->lang->line('text_key_sha1_fingp');
		$this->data['text_pub_key'] = $this->lang->line('text_pub_key');

		$this->data['text_basic_contraints'] = $this->lang->line('text_basic_contraints');
		$this->data['text_cert_author'] = $this->lang->line('text_cert_author');
		$this->data['text_max_path_length'] = $this->lang->line('text_max_path_length');
		$this->data['text_critical'] = $this->lang->line('text_critical');
		
		$this->data['text_extended_key_usage']=$this->lang->line('text_extended_key_usage');
		$this->data['text_allow_purposes'] = $this->lang->line('text_allow_purposes');
		$this->data['text_identifier'] = $this->lang->line('text_identifier');
		$this->data['text_value'] = $this->lang->line('text_value');
		$this->data['text_key_identifier'] = $this->lang->line('text_key_identifier');
		$this->data['text_subj_key_identfier']=$this->lang->line('text_subj_key_identfier');
		$this->data['text_extension'] = $this->lang->line('text_extension');
		$this->data['text_subj_altern_names'] = $this->lang->line('text_subj_altern_names');
		$this->data['text_email'] = $this->lang->line('text_email');
		
		$this->data['text_signature'] = $this->lang->line('text_signature');
		$this->data['text_signat_algor'] = $this->lang->line('text_signat_algor');
		$this->data['text_signat_params'] = $this->lang->line('text_signat_params');
		$this->data['text_nsCaRevocUrl'] = $this->lang->line('text_nsCaRevocUrl');
		$this->data['text_nsRevocUrl'] = $this->lang->line('text_nsRevocUrl');
		
	}

	function index() {	// Shows TSA cert content
		$cert_file = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_tsa_serverfile');
		// Show form to add new TSA cert if it not exists
		if (! is_file($cert_file)) {
			redirect($this->data['path_insert']);
			return;
		}
		
		// Check TSA settings file exists
		$cert_config = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_tsa_conffile');
		if (! is_file($cert_file)) {
			redirect($this->data['path_insert']);
			return;
		}
				
		
		// Read Cert content
		$cert_data = openssl_x509_parse( file_get_contents($cert_file) );
		//print_r($cert_data);

		$this->data['ident'] = $cert_data['subject']['CN'];
		$this->data['verif_by'] = $cert_data['issuer']['CN'];
		$aux = getdate($cert_data['validTo_time_t']);
		$this->data['expires'] = $aux['month'].' '.$aux['mday'].', '.$aux['year'] .' &nbsp; [';
		$this->data['expires'] .= $aux['hours'].':'.$aux['minutes'].':'.$aux['seconds'].']';
		$this->data['valid_not_after'] = date('Y / m / d - H:i:s', 
							$cert_data['validTo_time_t']);
		$this->data['valid_not_before'] =  date('Y / m / d - H:i:s', 
							$cert_data['validFrom_time_t']);
		
		$this->data['email'] = $cert_data['issuer']['emailAddress'];
		$this->data['version'] = hexdec($cert_data['version']); // Octal
		$this->data['serialNumber'] = hexdec($cert_data['serialNumber']); // Octal
		
		// Theme name
		$this->data['t_C'] = $cert_data['subject']['C'];
		$this->data['t_O'] = $cert_data['subject']['O'];
		$this->data['t_OU'] = $cert_data['subject']['OU'];
		$this->data['t_CN'] = $cert_data['subject']['CN'];
		
		// Issuer name
		$this->data['i_C'] = $cert_data['issuer']['C'];
		$this->data['i_O'] = $cert_data['issuer']['O'];
		$this->data['i_OU'] = $cert_data['issuer']['OU'];
		$this->data['i_CN'] = $cert_data['issuer']['CN'];

		// Netscape Revocation
		$this->data['nsCaRevocationUrl'] = $cert_data['extensions']['nsCaRevocationUrl'];
		$this->data['nsRevocationUrl'] = $cert_data['extensions']['nsRevocationUrl'];
		
		
		// Get TSA settings
		$cert_data = json_decode( file_get_contents($cert_config), true);
		$this->data['name'] = $cert_data['name'];
		$this->data['url'] = $cert_data['url'];
		$this->data['hashtype'] = strtoupper($cert_data['hashtype']);
		$this->data['identifier'] = $cert_data['identifier'];
		$this->data['secret'] = $cert_data['secret'];
		
		$this->data['heading_title'] = $this->data['text_tsa_cert'];		
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('tsacert/tsacert_view',$this->data);
		$this->load->view('templates/footer',$this->data);
		
	}

	function insert(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Just a trick, 'up' has nothing relevant. 
		$this->form_validation->set_rules('up', '', 'trim|required');
		$this->form_validation->set_rules('htypeID', '', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			$this->data['heading_title'] = '<b>'. $this->data['text_new_cert']. '</b>';
		
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' :: ');

			$hashtypes = $this->hashtype_model->fetch_hashtypes();
			$hashtypes_number = count($hashtypes);
			
			$this->data['hashtypes'] = '<select name="htypeID" style="width:150px;" tabindex="3">';
			
			for ($i = 0; $i < $hashtypes_number; $i ++) {
				$this->data['hashtypes'] .=  '<option value="'.
						$hashtypes[$i]['id'].'"> ' .
						$hashtypes[$i]['name'].' </option>';
			}
			$this->data['hashtypes'] .= "</select>";
			
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('tsacert/tsacert_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 
			
			$hashtype = $this->hashtype_model->get_hashtype($data['htypeID']);
			
			if (empty($hashtype)) {
				$warning = array(); // Can't move uploaded file
				$warning['code'] = '06';
				$warning['file'] = $data['htypeID'];
				$this->show_warning_page($warning);
				return;
			}
			
			$cert_file = realpath($_SERVER["DOCUMENT_ROOT"] . '/..').
					$this->config->item('unml_tsa_serverfile');

			if (! is_dir(dirname($cert_file)) ) {
				mkdir($cert_file, 0777, true);
			}
			
			if (! move_uploaded_file ($_FILES['upfile']['tmp_name'],$cert_file) ){
				rmdir ($pdf_dir);
				$warning = array(); // Can't move uploaded file
				$warning['code'] = '03';
				$warning['file'] = $data['path'];
				$this->show_warning_page($warning);
				return;
			}
			
			
			// Now, write setup file
			$cert_file = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_tsa_conffile');
			$fd  = fopen($cert_file, 'w');
			if (! $fd ) {
				rmdir ($pdf_dir);
				$warning = array(); // Can't create config file
				$warning['code'] = '04';
				$warning['file'] = $cert_file;
				$this->show_warning_page($warning);
				return;
			}
			$config_lines = array();
			$config_lines['name'] = trim($data['name']);
			$config_lines['url'] = trim($data['url']);
			$config_lines['hashtype'] = $hashtype['code'];
			$config_lines['identifier'] = trim($data['identifier']);
			$config_lines['secret'] =  trim($data['secret']);
			
			fwrite( $fd, json_encode($config_lines) );
			fclose($fd);
			
			redirect($this->base_path);
		}
	}

	function delete () {
		$cert_file = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_tsa_serverfile');
		// Show form to add new TSA cert if it not exists
		if (is_file($cert_file)) {
			if (! unlink($cert_file)) {
				$warning = array();
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
		}
		$cert_config = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_store') .
				'/'. $this->data['cert_file_config'];
		// Show form to add new TSA cert if it not exists
		if (is_file($cert_config)) {
			if (! unlink($cert_config)) {
				$warning = array();
				$warning['code'] = '02';
				$this->show_warning_page($warning);
				return;
			}
		}
		redirect($this->data['path_insert']);
	}

	// This functions sends a document HASH (ie SHA1) to TSA, and receive its
	// timestamp
	function certificate_notif($hash) {
		$cert_config = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_store') .
				'/'. $this->data['cert_file_config'];
		if (! is_file($cert_config) ) {
			$warning = array();
			$warning['code'] = '02';
			$this->show_warning_page($warning);
			return;
		}
		$cert_data = json_decode( file_get_contents($cert_config), true);
		
		// rqFile == Request File Path
		$rqFile = $this->timestamps->createRequestfile($my_hash);
		$response = $this->timestamps->signRequestfile(
				$rqFile, $cert_data['url'], 
				$cert_data['identifier'], $cert_data['secret']);
	
	}
	
	function show_warning_page($msg){
		switch ($msg['code']) {
		case "01":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_ok'].'</a>';

			$warning['heading_title'] = 'Can\'t delete TSA Cert file';
			$warning['message'] = 'Can not delete TSA Cert file. ';
			$warning['message'] = 'Please, try to delete manually.';
			break;
		case "02":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_ok'].'</a>';

			$warning['heading_title'] = 'Can\'t delete TSA Config file';
			$warning['message'] = 'Can not delete TSA Config file. ';
			$warning['message'] = 'Please, try to delete manually.';
			break;
		case "03":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Can\'t move uploaded TSA Cert file';
			$warning['message'] = 'Sorry. There was an error moving uploaded ';
			$warning['message'] = 'TSA Cert file to target direcotory.';
			break;
		case "04":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Can\'t create config file for TSA Server';
			$warning['message'] = 'Sorry. There was an error trying to create '; 
			$warning['message'] .= 'configuration file to keep TSA server data, ';
			$warning['message'] .= 'as username and password. ';
			$warning['message'] .= 'File should be create in: '. $msg['file'].'<br>';
			break;
		case "05":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'TSA Config file Not Exists';
			$warning['message'] = 'Sorry. There is no TSA Config file. ';
			$warning['message'] .= 'Please, delete old TSA and create brand new one!';
			break;
		case "06":
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . $this->base_path . 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'TSA Hashtype Not Exists';
			$warning['message'] = 'Sorry. Selected hashtype not exists in DB. ';
			$warning['message'] .= 'Please, choose another one.';
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
