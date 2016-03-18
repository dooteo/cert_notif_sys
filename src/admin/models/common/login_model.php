<?php


class login_model extends CI_Model {

	var $details;

	public function __construct() {
		parent::__construct();
	}
	
	function validate_user( $username, $password ) {
		// Build a query to retrieve the user's details
		// based on the received username and password
		$this->db->from('admin');
		$this->db->where('username',$username);
		$this->db->where('password', sha1($password) );

		$login = $this->db->get()->result();

		// The results of the query are stored in $login.
		// If a value exists, then the user account exists and is validated
		if ( is_array($login) && count($login) == 1 ) {
			// Set the users details into the $details property of this class
			$this->details = $login[0];
			if ($this->details->active == 0) {
				return false;
			}
			// Call set_session to set the user's session vars via CodeIgniter
			$this->set_session();
			$data = array (
				'username' => $username,
				'body' => '[login]'
			);
		
			$this->db->insert('logs', $data);
			return true;
		}

		return false;
	}

	function set_session() {
		// session->set_userdata is a CodeIgniter function that
		// stores data in CodeIgniter's session storage.  Some of the values are built in
		// to CodeIgniter, others are added.  See CodeIgniter's documentation for details.
		$this->session->set_userdata( array(
			'id'=>$this->details->id,
			'username'=>$this->details->username,
			'name'=> $this->details->firstName . ' ' . $this->details->lastName,
			'email'=>$this->details->email,
			'isLoggedIn'=>true, 
			'isAdmin'=>true 
			)
		);
	}

	function set_logout($username) {
		$data = array (
			'username' => $username,
			'body' => '[logout]'
		);
	
		$this->db->insert('logs', $data);

	}
}
