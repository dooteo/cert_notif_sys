<?php


class login_model extends CI_Model {

	var $details;

	public function __construct() {
		parent::__construct();
	}
	
	function validate_user( $username, $password ) {
		// Build a query to retrieve the user's details
		// based on the received username and password
		// We must check user' company is active, otherwise 
		// user must not login
		// And check user is active too.
		$this->db->select('t1.id, t1.username, t1.firstName, '.
		't1.lastName, t1.isCompAdmin, t1.active, t2.id AS compId, t2.name AS company');	
		$this->db->from('user AS t1');
		$this->db->join('company AS t2', 't1.companyId = t2.id');
		$this->db->where('t1.username',$username);
		$this->db->where('t1.password', sha1($password) );
		$this->db->where('t1.active',1);
		$this->db->where('t2.active',1);

		$login = $this->db->get()->result();
		// The results of the query are stored in $login.
		// If a value exists, then the user account exists and is validated
		if ( is_array($login) && count($login) == 1 ) {
			// Set the users details into the $details property of this class
			$this->details = $login[0];
			
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
		// stores data in CodeIgniter's session storage.  Some of the 
		// values are built in to CodeIgniter, others are added.
		//  See CodeIgniter's documentation for details.
		$this->session->set_userdata( array(
			'id'=>$this->details->id,
			'username'=>$this->details->username,
			'name'=> $this->details->firstName . ' ' . $this->details->lastName,
			'email'=>$this->details->email,
			'isLoggedIn'=>true
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
