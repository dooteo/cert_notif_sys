<?php
class Uaction extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();


		$this->data_header['base_link_path'] = base_url();
		$this->base_path = base_url() . $this->config->item('index_page') . '/common/uaction/';
		$this->data['path_index'] = $this->base_path;
		$this->data['path_accept'] = $this->base_path . "accept";
		$this->data['path_deny'] = $this->base_path . "deny";

		$this->data['error_warning'] = '';
		$this->data['success'] = '';

		$this->data['text_name'] = 'Name';
		$this->data['text_document'] = 'Document';
		$this->data['text_dni'] = 'DNI';
		$this->data['text_document_not_found'] = 'Document not found.';
		$this->data['text_dnf_body'] = 'Sorry. Requested document not found in server.<br />';
		$this->data['text_dnf_body'] .= 'Please try again with another document, or contact with sysadmin.';
		$this->data['button_accept'] = 'Accept';
		$this->data['button_deny'] = 'Deny';
	}

	function index() {	// Show warning: Document not found
	require '../admin/PHPMailer/class.phpmailer.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'authsmtp.zundan.com';  // Specify main and backup server
$mail->Port = '587';
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'info@zundan.com';                            // SMTP username
$mail->Password = 'tU!27_H1@';                           // SMTP password
$mail->SMTPSecure = '';                            // Enable encryption, 'tls' and 'ssl' also accepted

$mail->From = 'info@zundan.com';
$mail->FromName = 'Zundan Informazioa';
$mail->addAddress('dooteo@zundan.com', 'Dooteo TMA');  // Add a recipient
$mail->addAddress('dooteo@yahoo.es');               // Name is optional
$mail->addReplyTo('info@zundan.com', 'Information');
$mail->addCC('inaki@zundan.com');
$mail->addBCC('dooteo@zundan.com');

$mail->WordWrap = '70';                                 // Set word wrap to 50 characters
$mail->addAttachment('/var/www/unimail.sql');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
//$mail->isHTML(true);                                  // Set email format to HTML
$mail->isHTML(false);

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';
	/*
		$this->data['heading_title'] = $this->data['text_document_not_found'];

		$this->load->view('uaction/header',$this->data_header);
		// DNF == document not found
		$this->load->view('uaction/uaction_dnf',$this->data);
		$this->load->view('uaction/footer',$this->data);
	*/
	}
	
	function doc() {	// Shows options for final user
		$this->data['notif']['getted'] = $this->uri->segment(4);
		$this->data['heading_title'] = 'Certificated Document';
		$this->data['notif']['name'] = 'Iñaki Larrañaga';
		$this->data['notif']['dni'] = '72442966G';
		$this->data['notif']['document'] = 'popo_on_the_fly_checkitoutpopo_on_the_fly_checkitout.pdf';
		$this->data['notif']['id'] = 'kkkkkkkkkkkkkkkkkkkkkkkk';

		$this->load->view('uaction/header',$this->data_header);
		$this->load->view('uaction/uaction_view',$this->data);
		$this->load->view('uaction/footer',$this->data);
	}
	function accept() {	// Download document to final user
		$file_sha1 = $this->uri->segment(4);
		$this->data['heading_title'] = 'Certificated Document';
		$this->data['notif']['name'] = 'Iñaki Larrañaga';
		$this->data['notif']['dni'] = '72442966G';
		$this->data['notif']['document'] = 'popo_on_the_fly_checkitoutpopo_on_the_fly_checkitout.pdf';
		$this->data['notif']['id'] = 'kkkkkkkkkkkkkkkkkkkkkkkk';

		$this->load->view('uaction/header',$this->data_header);
		$this->load->view('uaction/uaction_view',$this->data);
		$this->load->view('uaction/footer',$this->data);
	}
	
	function deny() {	// Don't download document to final user
		$file_sha1 = $this->uri->segment(4);
		$this->data['heading_title'] = 'Certificated Document';
		$this->data['notif']['name'] = 'Iñaki Larrañaga';
		$this->data['notif']['dni'] = '72442966G';
		$this->data['notif']['document'] = 'popo_on_the_fly_checkitoutpopo_on_the_fly_checkitout.pdf';
		$this->data['notif']['id'] = 'kkkkkkkkkkkkkkkkkkkkkkkk';

		$this->load->view('uaction/header',$this->data_header);
		$this->load->view('uaction/uaction_view',$this->data);
		$this->load->view('uaction/footer',$this->data);
	}
	function msg_handler ($msg){
		switch ($msg) {
		case "ncd":
			$warning = array('error_warning'=>'There is no company defined. You must create a company at least.');
			break;
		default:
			$warning = "";
		}
		return $warning;
	}

}
