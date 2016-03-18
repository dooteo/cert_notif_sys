<?php


class Hashtype_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("hashtype");
	}

	function fetch_hashtypes( $limit="", $start="" ) {
		$this->db->from('hashtype');
		if (! empty($limit) && ! empty($start)) {
			$this->db->limit($limit, $start);
		}
		$this->db->order_by('name');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}

	function get_hashtype( $id ) {
		
		$this->db->from('hashtype');
		$this->db->where('id', $id);
	
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
		
	}
	function get_hashtype_by_name( $name ) {
		
		$this->db->from('hashtype');
		$this->db->where('name', $name);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}

}
