<?php


class Mailtmpl_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("engine");
	}

	function get_company_msgtemplates($companyId, $limit="", $start="" ) {
		$select = 't1.id AS id, t1.name AS name, t1.active, ';
		$select .= 't2.id AS companyID, t2.name AS company, ';
		$this->db->select($select);
		
		$this->db->from('msgtemplate AS t1');
		$this->db->from('company AS t2');
		
		$this->db->where('t2.id', $companyId);
		$this->db->where('t2.id', 't1.companyID', FALSE);
		$this->db->group_by('name');

		if (! empty($limit) && ! empty($start)) {
			$this->db->limit($limit, $start);
		}
		$this->db->order_by('name asc');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}

	function get_msgtemplate( $id ) {

		$this->db->from('msgtemplate');	
		$this->db->where('id', $id);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}
	
	function get_msgtemplate_fullnames( $id ) {
		$select = 't1.id, t1.name AS name, t1.subjtag, ';
		$select .= 't1.subject, t1.greeting, t1.bodyhdr, t1.body, ';
		$select .= 't1.bodyftr, t1.signature, t1.comment, t1.bfrHDR, ';
		$select .= 't1.bfrMDL, t1.bfrFTR, t1.bfrSGNT, t1.active, ';
		$select .= 't2.id AS compID, t2.name AS company, ';
		$this->db->select($select);	
		
		$this->db->from('msgtemplate AS t1');
		$this->db->from('company AS t2');

		
		$this->db->where('t1.id', $id);
		$this->db->where('t1.companyID','t2.id', FALSE);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}


	function  set_new_msgtemplate( $msgtmpl ) {
		$data = array (
			'companyID' => $msgtmpl['langID'],
			'name' => $msgtmpl['name'],
			'subjtag' => $msgtmpl['subjtag'],
			'subject' => $msgtmpl['subject'],
			'greeting' => $msgtmpl['greeting'],
			'bodyhdr' => $msgtmpl['bodyhdr'],
			'body' => $msgtmpl['body'],
			'bodyftr' => $msgtmpl['bodyftr'],
			'signature' => $msgtmpl['signature'],
			'comment' => $msgtmpl['comment'],
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
//			'companyID' => $msgtmpl['langID'],
			'subjtag' => $msgtmpl['subjtag'],
			'subject' => $msgtmpl['subject'],
			'greeting' => $msgtmpl['greeting'],
			'bodyhdr' => $msgtmpl['bodyhdr'],
			'body' => $msgtmpl['body'],
			'bodyftr' => $msgtmpl['bodyftr'],
			'signature' => $msgtmpl['signature'],
			'comment' => $msgtmpl['comment'],
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
