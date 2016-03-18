<?php


class Stats_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("stats");
	}

	function fetch_stats( $limit="", $start="" ) {
		$this->db->select('t1.id, t1.username, t1.firstName, '.
			't1.lastName, t1.isCompAdmin, t1.active, t2.id AS compId, t2.name AS company');	
		$this->db->from('user AS t1');
		$this->db->join('company AS t2', 't1.companyID = t2.id');
		if (! empty($limit) && ! empty($start)) {
			$this->db->limit($limit, $start);
		}
		$this->db->order_by('username');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}

	function get_stat( $id ) {
		
		$this->db->from('stats');
		$this->db->where('id', $id);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}

	function get_company_stats( $company_id ) {

		$this->db->from('stats');	
		$this->db->where('companyID', $company_id);
		$this->db->order_by('on_date');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}
	
	function get_stat_id( $stat ) {
		$this->db->select('id');
		$this->db->from('stats');	
		$this->db->where('companyID', $stat['companyID']);
		$this->db->where('rident', $stat['ident']);
		$this->db->where('on_date', $stat['on_date']);
		$this->db->where('usec', $stat['usec']);

		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0]['id'];
		}
		return false;
	
	function set_new_stat ($stat) {
		$data = array (
			'companyID' => $stat['companyID'],
			'rident' => $stat['ident'],
			'raction' => '',
			'path' => '',
			'hash' => '',
			'sent' => 0,
			'gpdf' => 0,
			'error' => '',
			'on_date' => $stat['on_date'],
			'usec' => $stat['usec']
		);
		
		$this->db->insert('stats',$data);
	}
	function set_stat_by_id ($stat) {
		$data = array();

		if (! empty($stat['action'])) 
			$data['raction'] = $stat['action'];
		if (! empty($stat['path'])) 
			$data['path'] = $stat['path'];
		if (! empty($stat['sent'])) 
			$data['sent'] = $stat['sent'];
		if (! empty($stat['gpdf'])) 
			$data['gpdf'] = $stat['gpdf'];
		if (! empty($stat['hash'])) 
			$data['hash'] = $stat['hash'];
		if (! empty($stat['error'])) 
			$data['error'] = $stat['error'];

		$this->db->where('id', $stat['id']);
		$this->db->update('stats', $data);
 
		return true;
	}
	function set_stat ($stat) {
		$data = array();

		if (! empty($stat['action'])) 
			$data['raction'] = $stat['action'];
		if (! empty($stat['path'])) 
			$data['path'] = $stat['path'];
		if (! empty($stat['sent'])) 
			$data['sent'] = $stat['sent'];
		if (! empty($stat['gpdf'])) 
			$data['gpdf'] = $stat['gpdf'];
		if (! empty($stat['hash'])) 
			$data['hash'] = $stat['hash'];
		if (! empty($stat['error'])) 
			$data['error'] = $stat['error'];

		
		$this->db->where('companyID', $stat['companyID']);
		$this->db->where('rident', $stat['ident']);
		$this->db->where('on_date', $stat['on_date']);
		$this->db->where('usec', $stat['usec']);
		$this->db->update('stats', $data);
 
		return true;
	}

	function delete_stats ($id) {
		$this->db->where('id', $id);
		$this->db->delete('stats');
	}
	
	function delete_company_stats($companyID) {
		$this->db->where('companyID', $companyID);
		$this->db->delete('stats');
	}
	
}
