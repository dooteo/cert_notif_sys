<?php


class Mailtmpl_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("engine");
	}

	function fetch_msgtemplates( $limit="", $start="" ) {
		$this->db->select('t1.id, t1.name AS name, t1.active, t2.id AS compId, t2.name AS company');	
		$this->db->from('msgtemplate AS t1');
		$this->db->from('company AS t2');
		$this->db->where('t1.companyID','t2.id', FALSE);
		
		if (! empty($limit) && ! empty($start)) {
			$this->db->limit($limit, $start);
		}
		$this->db->order_by('name');
		
		$datarows = $this->db->get()->result_array();
		if (! is_array($datarows) || count($datarows) < 1) {
			return false;
		}
		// Remove slashes
		$aux_row = array();
		$max_rows = count($datarows);
		for ($i = 0; $i < $max_rows; $i++) {
			foreach($datarows[$i] AS $k => $v){
				$aux_rows[$i][$k] = stripslashes($v);
			}
		}
		return $aux_rows;
	}

	function get_msgtemplate( $id ) {

		$this->db->from('msgtemplate');	
		$this->db->where('id', $id);
		
		$datarow = $this->db->get()->result_array();
		if (! is_array($datarow) || count($datarow) != 1) {
			return false;
		}
		// Remove slashes
		$aux_row = array();
		foreach($datarow[0] AS $k => $v){
			$aux_row[$k] = stripslashes($v);
			
		}
		return $aux_row;
	}
	function get_msgtemplate_by_name($name) {

		$this->db->from('msgtemplate');	
		$this->db->where('name', $name);
		
		$datarow = $this->db->get()->result_array();
		if (! is_array($datarow) || count($datarow) != 1) {
			return false;
		}
		// Remove slashes
		$aux_row = array();
		foreach($datarow[0] AS $k => $v){
			$aux_row[$k] = stripslashes($v);
			
		}
		return $aux_row;
	}
	function get_msgtemplate_fullnames( $id ) {
		$select = 't1.id, t1.name AS name, t1.subjtag, t1.subject, ';
		$select .= 't1.greeting, t1.bodyhdr, t1.body, t1.bodyftr, t1.signature, ';
		$select .= 't1.comment, t1.bfrHDR, t1.bfrMDL, t1.bfrFTR, t1.bfrSGNT, ';
		$select .= 't1.active, t2.id AS compID, t2.name AS company';
		$this->db->select($select);	
		
		$this->db->from('msgtemplate AS t1');
		$this->db->from('company AS t2');
		
		$this->db->where('t1.id', $id);
		$this->db->where('t1.companyID','t2.id', FALSE);
		
		$datarow = $this->db->get()->result_array();
		if (! is_array($datarow) || count($datarow) != 1) {
			return false;
		}
		// Remove slashes
		$aux_row = array();
		foreach($datarow[0] AS $k => $v){
			$aux_row[$k] = stripslashes($v);
			
		}
		return $aux_row;
	}

	function get_company_msgtemplates( $compID ) {
		$select = 't1.id, t1.name AS name, t1.comment ';
		$this->db->select($select);
		
		$this->db->from('msgtemplate AS t1');
		
		$this->db->where('t1.companyID', $compID);
		$this->db->where('t1.active','1', FALSE);
		
		$this->db->order_by('name');
		
		$datarows = $this->db->get()->result_array();
		if (! is_array($datarows) || count($datarows) < 1) {
			return false;
		}
		// Remove slashes
		$aux_row = array();
		$max_rows = count($datarows);
		for ($i = 0; $i < $max_rows; $i++) {
			foreach($datarows[$i] AS $k => $v){
				$aux_rows[$i][$k] = stripslashes($v);
			}
		}
		return $aux_rows;
	}
	
	function  set_new_msgtemplate( $msgtmpl ) {
		$data = array (
			'companyID' => $msgtmpl['companyID'],
			'name' => addslashes($msgtmpl['name']),
			'subjtag' => addslashes($msgtmpl['subjtag']),
			'subject' => addslashes($msgtmpl['subject']),
			'greeting' => addslashes($msgtmpl['greeting']),
			'bodyhdr' => addslashes($msgtmpl['bodyhdr']),
			'body' => addslashes($msgtmpl['body']),
			'bodyftr' => addslashes($msgtmpl['bodyftr']),
			'signature' => addslashes($msgtmpl['signature']),
			'comment' => addslashes($msgtmpl['comment']),
			'bfrHDR' => $msgtmpl['URLhdr'],
			'bfrMDL' => $msgtmpl['URLbdy'],
			'bfrFTR' => $msgtmpl['URLftr'],
			'bfrSGNT' => $msgtmpl['URLsgnt'],
			'active' => $msgtmpl['active']
		);
		
		$this->db->insert('msgtemplate',$data);
	}

	function set_msgtemplate( $msgtmpl ) {
		$data = array (
			'companyID' => $msgtmpl['companyID'],
			'subjtag' => addslashes($msgtmpl['subjtag']),
			'subject' => addslashes($msgtmpl['subject']),
			'greeting' => addslashes($msgtmpl['greeting']),
			'bodyhdr' => addslashes($msgtmpl['bodyhdr']),
			'body' => addslashes($msgtmpl['body']),
			'bodyftr' => addslashes($msgtmpl['bodyftr']),
			'signature' => addslashes($msgtmpl['signature']),
			'comment' => addslashes($msgtmpl['comment']),
			'bfrHDR' => $msgtmpl['URLhdr'],
			'bfrMDL' => $msgtmpl['URLbdy'],
			'bfrFTR' => $msgtmpl['URLftr'],
			'bfrSGNT' => $msgtmpl['URLsgnt'],
			'active' => $msgtmpl['active']
		);

		$this->db->where('id', $msgtmpl['id']);
		$this->db->update('msgtemplate', $data);
 
		return true;
	}

	function delete_msgtemplate ($id) {
		$this->db->where('id', $id);
		$this->db->delete('msgtemplate');
	}

	function activate_msgtemplate( $id ) {

		$data = array ('active' => 1);
		
		$this->db->where('id', $id);
		$this->db->update('msgtemplate', $data);
	}

	function desactivate_msgtemplate( $id ) {

		$data = array ('active' => 0);
		
		$this->db->where('id', $id);
		$this->db->update('msgtemplate', $data);
	}
	
}
