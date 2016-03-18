<?php


class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("user");
	}

	function fetch_users( $limit="", $start="" ) {
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

	function get_user( $id ) {
		
		$this->db->from('user');
		$this->db->where('id', $id);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}

	function get_company_users( $company_id ) {

		$this->db->from('user');	
		$this->db->where('companyID', $company_id);
		$this->db->order_by('username');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}
	function  set_new_user( $user ) {
		$data = array (
			'companyID' => $user['company'],
			'username' => $user['username'],
			'password' => $user['password'],
			'firstName' => $user['firstName'],
			'lastName' => $user['lastName'],
			'email' => $user['email'],
			'phone' => $user['phone'],
			'cellphone' => $user['cellphone'],
			'isCompAdmin' => $user['isCompAdmin'],
			'active' => $user['active']
		);
		
		$this->db->insert('user',$data);
	}
	function set_user( $user ) {

		$data = array (
			'companyID' => $user['company'],
			'username' => $user['username'],
			'firstName' => $user['firstName'],
			'lastName' => $user['lastName'],
			'email' => $user['email'],
			'phone' => $user['phone'],
			'cellphone' => $user['cellphone'],
			'isCompAdmin' => $user['isCompAdmin'],
			'active' => $user['active']
		);
		if (! empty($user['password']) ){
			$data['password'] = $user['password'];
		}

		$this->db->where('id', $user['id']);
		$this->db->update('user', $data);
 
		return true;
	}
	function set_user_password( $user ) {

		if (! empty($user['password']) ){
			$data['password'] = $user['password'];
		} else {
			return false;
		}

		$this->db->where('id', $user['id']);
		$this->db->update('user', $data);
 
		return true;
	}
	function activate_user( $id ) {

		$data = array ('active' => 1);
		
		$this->db->where('id', $id);
		$this->db->update('user', $data);
	}

	function desactivate_user( $id ) {

		$data = array ('active' => 0);
		
		$this->db->where('id', $id);
		$this->db->update('user', $data);
	}

	function delete_user ($id) {
		$this->db->where('id', $id);
		$this->db->delete('user');
	}
	
}
