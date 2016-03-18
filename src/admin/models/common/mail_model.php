<?php

class Mail_model extends CI_Model { 
	public function __construct() {
		parent::__construct();
		
	}
	
	function create_notif_mail($basedir, $hash, $item, $template, $mailcfg){
		print_r($item);
		$inner_msg = array();
		$inner_msg['error'] = FALSE;
		$inner_msg['item'] = '';
		
		$final_url = '{unwrap}http://unimail/notif/' . $item['company']. 
				'/'. $hash. '{/unwrap}';

		// In Debian PHP 5.5 json_encode function was removed due 
		// to its non free license. 
		// To use it must install php5-json or php-services-json package

		$content['mailcfgID'] =  $mailcfg['id'];
		$content['comppath'] =  $item['company'];
		//$content['to'] =  $item[3];
		$content['to'] =  $item['email'];
		$content['toname'] = $item['name']. " " . $item['lastnames'];
		if ( empty($template['subjtag']) ){
			$content['subject'] = $template['subject'];
		} else {
			$content['subject'] = '[' .$template['subjtag']. '] '.
						$template['subject'];
		}
		// Replace tags by receipt data
		$aux = str_replace('{name}', $item['name'], $content['subject']);
		$content['subject'] = str_replace('{lastnames}', $item['lastnames'], $aux);
		
		$content['body'] = $template['greeting'] . "\n\n";
		if ($template['bfrHDR'] == 1) {
			$content['body'] .= "\t" . $final_url . "\n\n";
		}
		$content['body'] .= $template['bodyhdr']. "\n\n";
		if ($template['bfrMDL'] == 1) {
			$content['body'] .= "\t" . $final_url . "\n\n";
		}
		$content['body'] .= $template['body']. "\n\n";
		if ($template['bfrFTR'] == 1) {
			$content['body'] .= "\t" . $final_url . "\n\n";
		}
		$content['body'] .= $template['bodyftr']. "\n\n\n";
		if ($template['bfrSGNT'] == 1) {
			$content['body'] .= "\t" . $final_url . "\n\n";
		}
		$content['body'] .= $template['signature']. "\n\n";

		$aux = str_replace('{name}', $item['name'], $content['body']);
		$content['body'] = str_replace('{lastnames}', $item['lastnames'], $aux);

		$filetmp = $basedir['outputtmp'] . '/'. $item['ident']. "_" . 
						$item['email']. "_" . $hash . '.json';
		$fd = fopen($filetmp, 'w');
		fwrite ($fd, json_encode($content));
		fclose ($fd);

		$delivery = explode(',', $item['delivery_days']);
		$max_days = count ($delivery);
		for ($i = 0; $i < $max_days; $i++){
			if (! is_numeric($delivery[$i]) ) {
				return ($inner_msg);
			}
			if (strcmp($delivery[$i], '0') === 0){
				$file = $basedir['outputnow']. $item['ident']. "_" 
						. $item['email']. "_" . $hash . '.json';
			} else {
				$deliver = new DateTime(date('Y/m/d'));
				$deliver->add(new DateInterval('P'. $delivery[$i]. 'D'));
				$deliv_dir = $deliver->format('Y/m/d');
				if (! is_dir($basedir['outputfut'] .$deliv_dir) && 
					! mkdir($basedir['outputfut'] .$deliv_dir, 755, true) ){
					
					$inner_msg['error'] = '12';
					$inner_msg['item'] .= '<li>Can\'t create dir: \'' . 
							$basedir['outputfut'] .
							$deliv_dir. '\'</li>';
				}
				$file = $basedir['outputfut'] .$deliv_dir . '/' . 
							$item['ident']. "_" . 
							$item['email']. "_" . 
							$hash . '.json';

			}
			if (! copy($filetmp, $file) ) {
				$inner_msg['error'] = '12';
				$inner_msg['item'].= '<li>Can\'t copy from \'' . 
							$filetmp. '\' to \'' .
							$file. '\'</li>';
			}
		}
		return ($inner_msg);
	}
	
	function send_mails ($maildir){
		$CI =& get_instance();
		$CI->load->model('common/stats_model');
		
		// Check semaphore
		if (is_file($maildir. '/send.locked')) {
			return;
		}

		// Semaphore On
		$fd = fopen($maildir. '/send.locked', 'w');
		fwrite ($fd, 'locked');
		fclose ($fd);
		
		$dd = opendir($maildir); // directory descriptor
		while (($file = readdir($dd)) !== false) {
			if($file!="." && $file!=".." && $file!="send.locked" ){
				$data = array();
				$data = json_decode(file_get_contents(
								$maildir . '/' . $file), 
								true);
				$mailcfg = $this->cmailcfg_model->get_mailcfg(
								$data['mailcfgID']);
				
				$this->email->protocol = 'smtp';
				$this->email->smtp_host = $mailcfg['host'];
				$this->email->smtp_port = $mailcfg['port'];
				if ($mailcfg['SMTPauth']) {
					$this->email->smtp_user = $mailcfg['username'];
					$this->email->smtp_pass = $mailcfg['password'];
					$this->email->_smtp_auth = true;
				
				}
				
				if (empty( $mailcfg['SMTPsec'])) {
					$this->email->smtp_crypto = '';
				} else {
					$this->email->smtp_crypto = $mailcfg['SMTPsec'];
				}
				if ($mailcfg['WordWrap'] > 0) {
					$this->email->wordwrap = true;
					$this->email->wrapchars = $mailcfg['WordWrap'];
				}

				$this->email->charset = 'utf-8';
				$this->email->newline = "\r\n";
				$this->email->mailtype = 'text';
				$this->email->from($mailcfg['MailFrom'], 
							$mailcfg['MailFromName']);
				$this->email->to($data['to']);
				$this->email->bcc($mailcfg['MailFrom']);
				$this->email->reply_to($mailcfg['MailReplyTo'], 
							$mailcfg['MailReplyToName']);
				$this->email->subject($data['subject']);
				$this->email->message($data['body']);
				
				$stat_data = array();
				$stat_data['id'] = $data['statID'];
					
				if ($this->email->send()) {
					// Remove sent mail
					unlink($maildir . '/' . $file);
					$path_aux = explode('_', $file);
					$hash = explode('.', $path_aux[2]);
					
					$path =  realpath(
							$_SERVER["DOCUMENT_ROOT"] . 
							'/..' .
							$this->config->item('unml_notif') .
							$data['comppath'] . '/receptor/' .
							implode("/", str_split($path_aux[0])) .
							'/'. $hash[0]. '/sent/');
					echo $path .'<br>';
					if (! is_dir($path)) {
						mkdir ($path, 0700, true);
					}
					
					$fd = fopen($path.'/'.date('YmdHisu'). '_'.
							$path_aux[1] . '_'.$hash[0].
							'.txt', 'w');
					fwrite($fd, $this->email->print_debugger());
					fclose($fd);
					
					$stat_data['sent'] = TRUE;
					$CI->stats_model->set_stat_by_id($stat_data);
					
				} else {
					$stat_data['error'] = 'Could not sent mail';
					echo $this->email->print_debugger();
				}
			}
		}
		
		closedir($dd);
		// Semaphore Off
		unlink($maildir. '/send.locked');
		
	}
}
?>
