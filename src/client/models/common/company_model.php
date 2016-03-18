<?php


class Company_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("company");
	}

	function fetch_companies( $limit="", $start="" ) {
		$this->db->select('id, name, active');	
		$this->db->from('company');
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

	function get_company( $id ) {

		$this->db->from('company');	
		$this->db->where('id', $id);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}


	function set_company( $company ) {

		$data = array (
			'name' => $company['name'],
			'nif' => $company['nif'],
			'address' => $company['address'],
			'postcode' => $company['postcode'],
			'city' => $company['city'],
			'state' => $company['state'],
			'country' => $company['country'],
			'phone1' => $company['phone1'],
			'phone2' => $company['phone2'],
			'email1' => $company['email1'],
			'email2' => $company['email2'],
			'website' => $company['website'],
			'active' => $company['active']
		);
		
		$this->db->where('id', $company['id']);
		$this->db->update('company', $data);
 
		return true;
	}
/*
	function activate_company( $id ) {

		$data = array ('active' => 1);
		
		$this->db->where('id', $id);
		$this->db->update('company', $data);
	}

	function desactivate_company( $id ) {

		$data = array ('active' => 0);
		
		$this->db->where('id', $id);
		$this->db->update('company', $data);
	}


	function  set_new_company( $company ) {
		$data = array (
			'name' => $company['name'],
			'nif' => $company['nif'],
			'address' => $company['address'],
			'postcode' => $company['postcode'],
			'city' => $company['city'],
			'state' => $company['state'],
			'country' => $company['country'],
			'phone1' => $company['phone1'],
			'phone2' => $company['phone2'],
			'email1' => $company['email1'],
			'email2' => $company['email2'],
			'website' => $company['website'],
			'active' => $company['active']
		);
		
		$this->db->insert('company',$data);
	}

	function delete_company ($id) {
		$this->db->where('id', $id);
		$this->db->delete('company');
	}
*/	
}
