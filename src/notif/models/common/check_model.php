<?php


class check_model extends CI_Model {

	var $details;

	public function __construct() {
		parent::__construct();
	}
	
	function check_userdata_exists($id) {
		$this->db->select('user_data');
		$this->db->from('ci_sessions');	
		$this->db->where('session_id', $id);

		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 
				&& ! empty($datarow[0]['user_data'])) {
			return true;
		}
		return false;
	}

	function set_session_hash($comppath, $hash) {
		$this->session->set_userdata( array(
			'path' => $comppath,
			'hash'=> $hash)
		);
		
	}
	function set_session_ident($ident) {
		$this->session->set_userdata('ident', $ident);
		
	}
	
	function set_session_download() {
		$value = $this->session->userdata('download');
		if ( empty($value) ) {
			$value = 1;
		} else {
			$value++;
		}
		$this->session->set_userdata('download', $value);
	}

}
