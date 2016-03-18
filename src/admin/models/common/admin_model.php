<?php


class admin_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("admin");
	}
	function record_active_count() {
		$this->db->from('admin');
		$this->db->where('active', 1);
		$datarows = $this->db->get()->result_array();
		return count($datarows);
	}
	function fetch_admins( $limit="", $start="" ) {
		$this->db->select('id, username, firstName, '.
			'lastName, email, phone, cellphone, active');	
		$this->db->from('admin');
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

	function get_admin( $id ) {
		
		$this->db->from('admin');
		$this->db->where('id', $id);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}

	
	function set_admin( $user ) {

		$data = array (
			'username' => $user['username'],
			'firstName' => $user['firstName'],
			'lastName' => $user['lastName'],
			'email' => $user['email'],
			'phone' => $user['phone'],
			'cellphone' => $user['cellphone'],
			'active' => $user['active']
		);
		if (! empty($user['password']) ){
			$data['password'] = $user['password'];
		}

		$this->db->where('id', $user['id']);
		$this->db->update('admin', $data);
 
		return true;
	}
	function set_admin_password( $user ) {

		if (! empty($user['password']) ){
			$data['password'] = $user['password'];
		}

		$this->db->where('id', $user['id']);
		$this->db->update('admin', $data);
 
		return true;
	}
	function activate_admin( $id ) {

		$data = array ('active' => 1);
		
		$this->db->where('id', $id);
		$this->db->update('admin', $data);
	}

	function desactivate_admin( $id ) {
		// Always must be an active admin at least!
		$active_admins = $this->record_active_count();
		if ($active_admins > 1) {
			$data = array ('active' => 0);
			$this->db->where('id', $id);
			$this->db->update('admin', $data);
			return true;
		} else {
			return false;
		}
	}

	function  set_new_admin( $user ) {
		$data = array (
			'username' => $user['username'],
			'password' => $user['password'],
			'firstName' => $user['firstName'],
			'lastName' => $user['lastName'],
			'email' => $user['email'],
			'phone' => $user['phone'],
			'cellphone' => $user['cellphone'],
			'active' => $user['active']
		);
		
		$this->db->insert('admin',$data);
	}

	function delete_admin ($id) {
		if ($this->record_count() > 2 ) {
			$this->db->where('id', $id);
			$this->db->delete('admin');
		} else if ($this->record_count() == 2 ) {
			$this->db->where('id', $id);
			$this->db->delete('admin');
			// Be sure admin is activated
			$data = array ('active' => 1);
			$this->db->update('admin', $data);
		} else {
			return false;
		}
		
		return true;
	}
	
}
