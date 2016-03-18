<?php

class Check extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		//$this->load->config('unimail', FALSE, TRUE);
		$this->load->model('common/check_model');
		$this->load->library("Zdn_encoding");
		$this->load->helper('download');
		$this->load->config('../../client/config/unimail');
		
		$this->base_path = base_url() . $this->config->item('index_page');
		$this->data['path_check'] = $this->base_path;
		$this->data_header['base_link_path'] = base_url();
		$this->data['text_enter_identification'] = 'Please, enter your identification.';
		$this->data['text_click_selection'] = 'Please, click on your selection.';
		$this->data['text_name'] = 'Name';
		$this->data['text_lastnames'] = 'Last Names';
		$this->data['text_identifier'] = 'Identifier';
		$this->data['text_description'] = 'IE: ID number. DNI, or NIF';
		$this->data['text_error'] = 'Incorrect Username or Password!';
		$this->data['text_download_docum'] = 'Download Certified Document';
		$this->data['text_download_description'] = 'Download will begin in 5 seconds. If not, click on <strong>Download</strong> button.';
		$this->data['text_wont_download_docum'] = 'Wont Download Certified Document';
		$this->data['text_wont_download_descript'] = 'You decided Refuse or Ignore Certified Document, so that document will not download. Anyway, your decision was registered as part of process.<br>Thanks for coming.';
		$this->data['button_access'] = 'Access';
		$this->data['button_accept'] = 'Accept';
		$this->data['button_refuse'] = 'Refuse';
		$this->data['button_ignore'] = 'Ignore';
		$this->data['button_download'] = 'Download';
	}
	
	function index() {
		$posted = $this->input->post(NULL, TRUE);

		if (! empty($posted) ) {
			$keys = array_keys($posted);
			
			switch ($keys[0]) {
			case 'ident':
				if (! empty(trim($posted['ident'])) ) {
					// Receipt entered its identificator
					$this->show_notif_options($posted);
					return;
				}
				break;
			case 'answer': // Receipt entered its action
				if (empty(trim($posted['answer'])) ) {
					break;
				}
				
				// Check receipt has enter its identificator
				$ident = $this->session->userdata('ident');
				if (empty($ident)) {
					break;
				}
				
				$basedir = $this->register_down_answer($posted['answer']);
				if (empty($basedir)) {
					break;
				}
				if ( strcmp($posted['answer'], 'accept') === 0 ){
					$this->show_notif_4_download($basedir);
					return;
				} else {
					$this->show_wont_download();
				}
				break;
			default:
				// Do nothing
			}
			
		} else if (! empty($this->session->userdata['download']) && 
				($this->download_notif()) ) {
			// If true Download notification
			return;
		}

		$this->show_check_form();
		return;
	}

	private function register_down_answer($action) {
		$path = $this->session->userdata('path');
		$hash = $this->session->userdata('hash');
		$ident = $this->session->userdata('ident');
		$ident_dir = implode("/",  str_split($ident));
		
		$base_2_return = $_SERVER["DOCUMENT_ROOT"] . '/..' ;
		$base_2_return .= $this->config->item('unml_notif') . $path .
			'/receptor/'. $ident_dir . '/'. $hash;
		
		$basedir = $base_2_return . '/receptor_answer/';
		if ( (! is_dir($basedir)) && (! mkdir($basedir, 0700, true)) ){
			return false;
		}
		
		$answerjson = $basedir . 'answer.json';

		$utf8 = new ZDN_Encoding();
		$content = array();
		$content['session_id'] =  $utf8->toUTF8($this->session->userdata('session_id'));
		$content['ip_address'] =  $utf8->toUTF8($this->session->userdata('ip_address'));
		$content['user_agent'] =  $utf8->toUTF8($this->session->userdata('user_agent'));
		$content['last_activity'] =  $utf8->toUTF8(
						$this->session->userdata('last_activity'));
		$content['ident'] = $utf8->toUTF8($ident);
		$content['url_path'] = $utf8->toUTF8($path);
		$content['url_hash'] = $utf8->toUTF8($hash);
		$content['action'] = $utf8->toUTF8($action);
		
		date_default_timezone_set('Europe/Madrid');
		$which_date = date('YmdHisu');
		$content['date'] = $which_date;

		$fd = fopen($answerjson, 'w');
		fwrite($fd, json_encode($content));
		fclose($fd);
		if (! is_file($answerjson)) {
			return false;
		}
		// Calc hash and wrote info.json
		$action_hash = hash_file("sha256", $answerjson);
		$infojson = $basedir . '/info.json' ;

		$fd = fopen($infojson, 'w');
		$infocontent = array();
		$infocontent['session_id'] = $content['session_id'];
		$infocontent['ident'] = $content['ident'];
		$infocontent['file'] = $utf8->toUTF8($answerjson);
		$infocontent['file_hash'] = $utf8->toUTF8($action_hash);
		$infocontent['date'] = $which_date;
		fwrite($fd, json_encode($infocontent));
		fclose($fd);
		
		if (! is_file($infojson)) {
			unlink($answerjson);
			return false;
		}
		return ($base_2_return);
	}
	private function show_notif_options($posted) {
		// ident for user identificator, as DNI, NIF, etc.
		$this->data['ident'] = preg_replace('/[^A-Z0-9]/s', '', 
						strtoupper($posted['ident']));
		$ident_dir = implode("/",  str_split($this->data['ident']));
		
		$path = $this->session->userdata('path');
		$hash = $this->session->userdata('hash');
		
		$basedir = $_SERVER["DOCUMENT_ROOT"] . '/..' ;
		$basedir .= $this->config->item('unml_notif') . $path .
			'/receptor/'. $ident_dir . '/'. $hash;

		if (! is_dir($basedir)) {
			// company + hash dir not exist!
			$this->show_check_form();
			return false;
		}

		// Log receipt access
		$this->log_receptor_access($basedir);

		// Check previously company dir and hash was saved in session
		$session_id = $this->session->userdata('session_id');
		if (! $this->check_model->check_userdata_exists($session_id) ) {
			return;
		}
		$this->check_model->set_session_ident($this->data['ident']);

		if ( strcmp($this->did_receptor_answer($basedir), 'accept') === 0 ) {
			// Just download document
			$this->show_notif_4_download($basedir);
			return;
		}

		$infojson = $basedir . '/archive/info.json';
		$content = json_decode(file_get_contents($infojson), true);
		$this->data['name'] = $content['name'];
		$this->data['lastname'] = $content['lastnames'];
		
		// Show form to grant receptor to choose an answer
		$this->load->view('templates/header', $this->data_header);
		$this->load->view('check/options',$this->data);
		$this->load->view('templates/footer',$this->data);
		return ($basedir);
	}
	
	private function did_receptor_answer ($basedir){
		$answer = $basedir . '/receptor_answer/answer.json';
		if (! is_file($answer)) {
			return false;
		}
		
		$content = json_decode(file_get_contents($answer), true);
		if ( empty($content) || empty($content['action']) ) {
			// Todo: this must emit an error for administrator
			// There should be data writen in file.
			return false;
		}
		return $content['action'];
	}
	private function show_notif_4_download($basedir){
		
		$this->check_model->set_session_download();
		$this->data['path_download'] = $this->base_path;
		// To refresh and download notif 
		header("Refresh: 5; url=". $this->base_path);
		
		$this->load->view('templates/header', $this->data_header);
		$this->load->view('check/download',$this->data);
		$this->load->view('templates/footer',$this->data);
		
	}

	private function download_notif() {

		$path = $this->session->userdata('path');
		$hash = $this->session->userdata('hash');
		$ident = preg_replace('/[^A-Z0-9]/s', '', 
					strtoupper($this->session->userdata('ident')) );
		$action = $this->session->userdata('action');
		$download = $this->session->userdata('download');
		
		if ( $download > 1 ) {
			// Receipt is downloading certified 
			// documentation for 2nd time in same session.
			// Time to destroy session
			$this->session->sess_destroy();
			
		} else {
			// Sums another download
			$this->check_model->set_session_download();
		}
		
		$ident_dir = implode("/",  str_split($ident));
		
		$basedir = $_SERVER["DOCUMENT_ROOT"] . '/..' ;
		$basedir .= $this->config->item('unml_notif') . $path .
			'/receptor/'. $ident_dir . '/'. $hash;
		$infojson = $basedir . '/archive/info.json';
		if (! is_file($infojson) ) {
			return false;
		}
		
		$content = json_decode(file_get_contents($infojson), true);
		
		if ( (strcmp($content['ident'], $ident) !== 0) && 
			(strcmp($content['hash'], $hash) !== 0) ){
			return false;
		} 

		$filedata = file_get_contents($content['file']);
		$filename = basename($content['file']);
		force_download($filename, $filedata); // from 'download' helper
	}
	
	private function log_receptor_access($basedir){
		date_default_timezone_set('Europe/Madrid');
		$which_date = date('YmdHisu');
		$basedir .= '/access_dates/' ;
		$logjson = $basedir . $which_date.'.json';

		if ( (! is_dir($basedir)) && (! mkdir($basedir, 0700, true)) ){
			return false;
		}

		$utf8 = new ZDN_Encoding();
		$content = array();
		$content['session_id'] =  $utf8->toUTF8($this->session->userdata('session_id'));
		$content['ip_address'] =  $utf8->toUTF8($this->session->userdata('ip_address'));
		$content['user_agent'] =  $utf8->toUTF8($this->session->userdata('user_agent'));
		$content['last_activity'] =  $utf8->toUTF8(
						$this->session->userdata('last_activity'));
		$content['ident'] = $utf8->toUTF8($this->session->userdata('ident'));
		$content['url_path'] = $utf8->toUTF8($this->session->userdata('path'));
		$content['url_hash'] = $utf8->toUTF8($this->session->userdata('hash'));
		$content['date'] = $which_date;
		
		$fd = fopen($logjson, 'w');
		fwrite($fd, json_encode($content));
		fclose($fd);
		
	}
	private function show_wont_download(){
		$this->load->view('templates/header', $this->data_header);
		$this->load->view('check/not_download',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	private function show_check_form(){
		// rsegment(3) == company's path
		// rsegment(4) == hash
		if ( empty($this->uri->rsegment(3)) || empty($this->uri->rsegment(4))) {
			$comppath = '';
			$hash_id = '';
		} else {
			$comppath = $this->uri->rsegment(3);
			$hash_id = $this->uri->rsegment(4);		
		}

		$session_id = $this->session->userdata('session_id');
		if (empty($this->session->userdata('path')) || empty($this->session->userdata('hash'))) {
		//if (! $this->check_model->check_userdata_exists($session_id) ) {
			// Set session for this user
			$this->check_model->set_session_hash($comppath, $hash_id);
		}

		$this->load->view('templates/header', $this->data_header);
		$this->load->view('check/check',$this->data);
		$this->load->view('templates/footer',$this->data);
	
	}
}
