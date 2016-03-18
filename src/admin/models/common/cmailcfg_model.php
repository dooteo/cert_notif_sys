<?php


class Cmailcfg_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->config('mail', FALSE, TRUE);
	}

	function record_count() {
		return $this->db->count_all("cmailcfg");
	}

	function fetch_mailcfgs( $limit="", $start="" ) {
		$this->db->select('t1.id, t1.name, t1.comment, t1.active, '.
				't2.id AS compId, t2.name AS company');	
		$this->db->from('cmailcfg AS t1');
		$this->db->join('company AS t2', 't1.companyID = t2.id');
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

	function get_mailcfg( $id ) {
		
		if ($id == 0) { // get Unimail's Default Mail Settings
			$mailcfg = array();
			$mailcfg['host'] = $this->config->item('unml_mail_host');
			$mailcfg['port'] = $this->config->item('unml_mail_port');
			$mailcfg['SMTPauth'] = $this->config->item('unml_mail_SMTP_auth');
			$mailcfg['SMTPsec'] = $this->config->item('unml_mail_SMTP_sec');
			$mailcfg['username'] = $this->config->item('unml_mail_SMTP_user');
			$mailcfg['password'] = $this->config->item('unml_mail_SMTP_pswd');
			$mailcfg['MailFrom'] = $this->config->item('unml_mail_From');
			$mailcfg['MailFromName'] = $this->config->item('unml_mail_FromName');
			$mailcfg['MailReplyTo'] = $this->config->item('unml_mail_ReplyTo');
			$mailcfg['MailReplyToName'] = $this->config->item('unml_mail_ReplyToName');
			$mailcfg['WordWrap'] = $this->config->item('unml_mail_WordWrap');
			return ($mailcfg);
			
		} else { // Get Company's Mail Settings
			$this->db->from('cmailcfg');
			$this->db->where('id', $id);
		
			$datarow = $this->db->get()->result_array();
			if ( is_array($datarow) && count($datarow) == 1 ) {
				return $datarow[0];
			}
			return false;
		
		}
		
	}
	function get_mailcfg_by_name( $name ) {
		
		$this->db->from('cmailcfg');
		$this->db->where('name', $name);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}

	function get_company_mailcfg( $company_id ) {

		$this->db->from('cmailcfg');	
		$this->db->where('companyID', $company_id);
		$this->db->order_by('name');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}
	
	function  set_new_mailcfg( $mailcfg ) {
		$data = array (
			'companyID' => $mailcfg['company'],
			'name' => $mailcfg['name'],
			'comment' => $mailcfg['comment'],
			'host' => $mailcfg['host'],
			'port' => $mailcfg['port'],
			'username' => $mailcfg['username'],
			'password' => $mailcfg['password'],
			'SMTPsec' => $mailcfg['smtpsec'],
			'SMTPauth' => $mailcfg['smtpauth'],
			'MailFrom' => $mailcfg['mailfrom'],
			'MailFromName' => $mailcfg['mailfromname'],
			'MailReplyTo' => $mailcfg['mailreplyto'],
			'MailReplyToName' => $mailcfg['mailreplytoname'],
			'WordWrap' => $mailcfg['wordwrap'],
			'active' => $mailcfg['active']
		);
		
		$this->db->insert('cmailcfg',$data);
	}

	function set_mailcfg( $mailcfg ) {

		$data = array (
			'companyID' => $mailcfg['company'],
			'comment' => $mailcfg['comment'],
			'host' => $mailcfg['host'],
			'port' => $mailcfg['port'],
			'username' => $mailcfg['username'],
			'password' => $mailcfg['password'],
			'SMTPsec' => $mailcfg['smtpsec'],
			'SMTPauth' => $mailcfg['smtpauth'],
			'MailFrom' => $mailcfg['mailfrom'],
			'MailFromName' => $mailcfg['mailfromname'],
			'MailReplyTo' => $mailcfg['mailreplyto'],
			'MailReplyToName' => $mailcfg['mailreplytoname'],
			'WordWrap' => $mailcfg['wordwrap'],
			'active' => $mailcfg['active']
		);

		$this->db->where('id', $mailcfg['id']);
		$this->db->update('cmailcfg', $data);
 
		return true;
	}

	function delete_mailcfg ($id) {
		$this->db->where('id', $id);
		$this->db->delete('cmailcfg');
	}

	function activate_mailcfg( $id ) {

		$data = array ('active' => 1);
		
		$this->db->where('id', $id);
		$this->db->update('cmailcfg', $data);
	}

	function desactivate_mailcfg( $id ) {

		$data = array ('active' => 0);
		
		$this->db->where('id', $id);
		$this->db->update('cmailcfg', $data);
	}
	
}
