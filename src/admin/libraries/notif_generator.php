<?php
class Notif_generator {
		
	public function generate_all($basedir){
		$CI =& get_instance();
		$CI->load->model('mail_model');
		$CI->load->model('mailtmpl_model');
		$CI->load->model('common/stats_model');
		
		$inner_msg = array();
		$inner_msg['error'] = FALSE;
		$inner_msg['note'] = '';
		
		if ((! is_file($basedir['tsa_configfile'])) || 
				(! is_file($basedir['tsa_serverfile'])) ){
				return FALSE;
		}

		$tsa_conf = json_decode(file_get_contents($basedir['tsa_configfile']), TRUE);
		$tsa_conf['tsa_serverfile'] = $basedir['tsa_serverfile'];
		
		$this->clean_errors_file($basedir['errors']);

		if (! is_dir($basedir['c_init'])) {
			$inner_msg['error'] = 'pdfgen01';
			$inner_msg['note'] = '<li>Directory does not exists: '. 
							$basedir['c_init'] . '</li>';
			return ($inner_msg);
		}
		// $dird == Directory descriptor
		$dird = opendir($basedir['c_init']);
		if (! $dird) {
			$inner_msg['error'] = 'pdfgen01';
			$inner_msg['note'] = '<li>Can not open directory: '. 
							$basedir['c_init'] . '</li>';
			return ($inner_msg);
		}

		$item = 0;
		while ( ($entry = readdir($dird)) !== FALSE ) {
			
			if ($entry == "." || $entry == "..") {
				continue;
			}
			
			$stat_data = array();
			
			// Get receipt data
			$json_file = $basedir['c_init'] .'/'. $entry;
			$receipt = json_decode(file_get_contents($json_file), TRUE);

			// and begin to log into DB Stats table
			$stat_data['companyID'] = $receipt['companyID'];
			$stat_data['ident'] = $receipt['companyID'];
			$stat_data['on_date'] = date('Y-m-d H:i:s');
			$stat_data['usec'] = substr(microtime(FALSE), 0, 10);
			
			$CI->stats_model->set_new_stat($stat_data);
			
			$stat_data['id'] = $CI->stats_model->get_stat_id($stat_data);
			
			$newpdf = $this->generate_pdf($basedir, $receipt, $item);
			if (! empty($newpdf['error'])) {
				$inner_msg['error'] = $newpdf['error'];
				$stat_data['gpdf'] = FALSE;
				$stat_data['error'] = 'Can not generate PDF';
				$CI->stats_model->set_stat($stat_data);
				continue;
			} 
			
			$stat_data['gpdf'] = TRUE;
			$CI->stats_model->set_stat($stat_data);
			
			// move entry's state to c_pdf (pdf done)
			rename($basedir['c_init'].'/'.$entry, $basedir['c_pdf'].
						'/'.$entry);
			

			$pdf_hash = hash_file($tsa_conf['hashtype'], 
						$newpdf['newpdf']);
			
			$stat_data['hash'] = basename($pdf_hash);
			$CI->stats_model->set_stat($stat_data);
			
			$newpdf_path = $this->rename_path($newpdf['pdfdir'],
						$pdf_hash);
			if ( empty($newpdf_path) ){
				$stat_data['error'] = 'Can not rename to path';
				$CI->stats_model->set_stat($stat_data);
				continue;
			}
			
			$stat_data['path'] = $newpdf_path;
			$CI->stats_model->set_stat($stat_data);
			
			$infojson = $this->create_infojson($basedir, 
							$newpdf_path,
							$newpdf['receipt'], 
							$newpdf['newpdf'],
							$tsa_conf['hashtype'],
							$pdf_hash);
			if (! empty($infojson['error'])) {
				$inner_msg['error'] = $infojson['error'];
				$stat_data['error'] = 'Can not create info.json';
				$CI->stats_model->set_stat($stat_data);
				continue;
			}
			
			// move entry's state to c_info (info done)
			rename($basedir['c_pdf'].'/'.$entry, 
				$basedir['c_info'].'/'.$entry);

			$cert = $this->generate_cert( $tsa_conf, 
						$newpdf_path,
						$pdf_hash);
			if (! empty($cert['error'])) {
				$inner_msg['error'] = $cert['error'];
				$stat_data['error'] = 'Can not generate CERTIFICATION';
				$CI->stats_model->set_stat($stat_data);
				continue;
			} 
			
			// move entry's state to c_cert (cert done)
			$copied = copy($infojson['file'], 
					$basedir['c_cert'].'/'.$entry);
			if (! $copied) {
				$inner_msg['error'] = TRUE;
				$msg = '<li>Could not copy info.json in done_cert dir: ';
				$msg .= '<strong>' . $infojson .'</strong></li>';
				$this->write_errors_on_file(dirname($basedir['errors']), $msg);
				continue;
			}
			
			// Remove entry file from cert done 
			// due to it's copied into last step: to_mail
			if (is_file($basedir['c_info'] .'/'. $entry)) {
				unlink($basedir['c_info'] .'/'. $entry);
			}
			
			// Generate file for mail
			$mailcfg = $CI->cmailcfg_model->get_mailcfg_by_name(
						$newpdf['receipt']['mailsetup']);
			$mail_tmpl = $CI->mailtmpl_model->get_msgtemplate_by_name(
						$newpdf['receipt']['mail_template']);
						
			$CI->mail_model->create_notif_mail(
						$basedir, 
						$pdf_hash,
						$newpdf['receipt'],
						$mail_tmpl,
						$mailcfg);
			

			$item++;
		}
		closedir($dird);

	}
	/* @param array $tsa_conf = contains hash type, TSA server config 
	*                           and *.cer filenames
	* @param string  $cert_dir = it's the direcotry where new 
	*                            TSA Timestamp will be stored
	* @param $pdfhash: The hashed data
	*/
	private function generate_cert($tsa_conf, $cert_dir, $pdfhash){
		$CI =& get_instance();
		$CI->load->library('timestamps');
		$requestfile_path = $CI->timestamps->createRequestfile(
								$tsa_conf['hashtype'], 
								$pdfhash);
		echo '<br>'. $requestfile_path. '  :  filesize: '. filesize($requestfile_path). '<br>';

		
		$certification = $CI->timestamps->signRequestfile($requestfile_path, 
								trim($tsa_conf['url']),
								trim($tsa_conf['identifier']),
								trim($tsa_conf['secret']));
		print_r($certification);
		echo '<br><br>----------^ signRequestfile------------<br><br>';
		
		$timestamp = $CI->timestamps->getTimestampFromAnswer($certification['response_string']);
		echo $timestamp .'<br>';
		echo '<br><br>---------^ getTimestampFromAnswer-------------<br><br>';
		$is_valid = $CI->timestamps->validate(
					$tsa_conf['hashtype'], 
					$pdfhash, 
					$certification['response_string'], 
					$certification['response_time'], 
					$tsa_conf['tsa_serverfile']);
		echo("<br>Validation result: ");
		var_dump($is_valid); //bool(TRUE)
		echo '<br><br>--------^ validate--------------<br><br>';
		if (! $is_valid) {
			return FALSE;
		}
		
		if (($fd = fopen($cert_dir . '/tsa_cert_response_string', 'w')) !== FALSE) {
			fwrite($fd, $certification['response_string']);
			fclose($fd);
			chmod ($cert_dir . '/tsa_cert_response_string', 0700);
		} else {
			return FALSE;
		}
		if (($fd = fopen($cert_dir . '/tsa_cert_response_time', 'w')) !== FALSE) {
			fwrite($fd, $certification['response_time']);
			fclose($fd);
			chmod ($cert_dir . '/tsa_cert_response_time', 0700);
		} else {
			return FALSE;
		}
		return TRUE;
		
	}
	private function create_infojson($basedir, $newpdf_path, $receipt, $newpdf, 
					$hashtype, $pdfhash) {
		$inner_msg = array();
		$inner_msg['error'] = '';
		$inner_msg['file'] = '';
		
		$utf8 = new ZDN_Encoding();

		$infojson = $newpdf_path . '/archive/info.json' ;
		$newpdf2 = $newpdf_path . '/archive/' . basename($newpdf);
		
		if (($fd = fopen($infojson, 'w')) !== FALSE){
			$content['company'] = $receipt['company'];
			$content['statID'] = $receipt['statID'];
			$content['name'] = $receipt['name'];
			$content['lastnames'] = $receipt['lastnames'];
			$content['ident'] = $receipt['ident'];
			$content['email'] = $receipt['email'];
			$content['file'] = $utf8->toUTF8($newpdf2);
			$content['hash_type'] = $utf8->toUTF8($hashtype);
			$content['file_hash'] = $utf8->toUTF8($pdfhash);
			$content['date'] = $utf8->toUTF8(date('Y/m/d H:i:s:u'));
		
			fwrite($fd, json_encode($content));
			fclose($fd);
			chmod ($infojson, 0700);
		} else {
			$inner_msg['error'] = '02';
			$msg = '<li>Could not write info.json file: ';
			$msg .= '<strong>' . $infojson .'</strong></li>';
			$this->write_errors_on_file(dirname($basedir['errors']), $msg);
			return($inner_msg);
		}
		$inner_msg['file'] = $infojson;
		return($inner_msg);
		
	}
	private function generate_pdf($basedir, $receipt, $number) {
		$inner_msg = array();
		$inner_msg['error'] = '';
		
		/*$json_file = $basedir['c_init'] .'/'. $file;
		$receipt = json_decode(file_get_contents($json_file), TRUE);
		*/
		$engine = $this->check_engine($receipt['companyID'], $receipt['engine']);
		if ($engine === FALSE) {
			$inner_msg['error'] = '01';
			$msg = '<li>There is no engine for this receptor: ['.
					$receipt['ident'] . '] ' .$receipt['engine'].'</li>';
			$this->write_errors_on_file(dirname($basedir['errors']), $msg);
			return ($inner_msg);
		}

		$data_aux = array();
		$ddf_data = array();
		$ddf_type = array();
		$ddf_named = array();
		// Get fields from Engine data
		if (! empty($engine['fields'])) {
			$k = 0;
			$tok = strtok($engine['fields'], "<>");
			while ($tok !== FALSE) {
				$data_aux[$k] = explode("||", $tok);
				$ddf_type[$k] = $data_aux[$k][0] ;
				$ddf_named[$k] = $data_aux[$k][1] ;
				$tok = strtok ("<>");
				$k++;
			}
		}

		// Fields number must match
		$max_ddf_type = count($ddf_type);
		$max_ddf_named = count($ddf_named);
		$fields_number = count($receipt) - 10;

		//if (($max_ddf_fields > 0) && 
		//		((count($receipt)-10) != $max_ddf_fields) ){
		if ( ($fields_number != $max_ddf_type) || ($fields_number != $max_ddf_named) ){
			$inner_msg['error'] = '02';
			$msg = '<li>Fields number are not same. ' .$receipt['company'];
			$msg .= ' :: ' . $receipt['name']. ' ' . $receipt['lastnames'];
			$msg .= ' <strong>'.$engine['name'].' :: '.$engine['path'].'</strong> ';
			$this->write_errors_on_file(dirname($basedir['errors']), $msg);
			return ($inner_msg);
		}
		
		// Generate PDF file
		$rcptr_dir = $basedir['notif'] .'/receptor/' . 
						implode("/", str_split($receipt['ident'])) . 
						'/'.date('YmdHis') . '_'. $number.'/archive';
		if (! is_dir($rcptr_dir) && ! mkdir($rcptr_dir, 0777, TRUE) ) {
			$inner_msg['error'] = '03';
			$msg = '<li>Can not create directory for' .$receipt['company'];
			$msg .= ' :: ' . $receipt['name']. ' ' . $receipt['lastnames'];
			$msg .= ' <strong>'.$rcptr_dir.'</strong> ';
			$this->write_errors_on_file(dirname($basedir['errors']), $msg);
			return($inner_msg);
		}
		$pdf_file = realpath($_SERVER["DOCUMENT_ROOT"] .'/..' .$engine['path']);
		$new_pdf = $rcptr_dir . '/'. basename($engine['path']);
		if ($fields_number > 0) {
			$fdf_data_hidden = array();
			$fdf_data_readonly = array();
			$fdf_data_strings = array();
			$fdf_data_buttons = array();

			for ($k = 0; $k < $fields_number; $k++) {
				if ( strtolower($ddf_type[$k]) === strtolower("Text") ) {
					$fdf_data_strings[$ddf_named[$k]] = iconv('UTF-8',
								'ISO-8859-1', 
								$receipt['field_'.$k]);

				} else if ( strtolower($ddf_type[$k]) === strtolower("Button") ) {
					if (strtolower($receipt['field_'.$k]) === "on") {
						$fdf_data_buttons[$ddf_named[$k]] = "Yes";
					} else {
						$fdf_data_buttons[$ddf_named[$k]] = "Off";
					}
				}
			}
			$pdfengine = new Zdn_pdftk();
			$pdfengine->make_pdf($fdf_data_strings, $fdf_data_buttons, 
						$fdf_data_hidden, $fdf_data_readonly, 
						$pdf_file, $new_pdf, $basedir['notiftmp']);

		} else { 
			copy($pdf_file, $new_pdf);
			 
		}
		$inner_msg['pdfdir'] = $rcptr_dir;
		$inner_msg['newpdf'] = $new_pdf;
		$inner_msg['receipt'] = $receipt;

		return ($inner_msg);
	}
	private function check_engine($compID, $engPath){
		$CI =& get_instance();
		$CI->load->model('common/pdfengine_model');
		$engine = $CI->pdfengine_model->get_engine_by_compID_enginePath(
							$compID, $engPath);
		if (empty($engine)){
			return FALSE;
		}
		return ($engine);
	}
	private function rename_path ($pdfdir, $hash){
		$newpdf_path = dirname(dirname($pdfdir)) . '/' . $hash;
		
		if ( (!is_dir($newpdf_path)) && (! rename(dirname($pdfdir), $newpdf_path) ) ){
			$msg = '<li>Could not rename dir from ';
			$msg .= '<strong>' . $pdfdir .'</strong> to ';
			$msg .= '<strong>'. $newpdf_dir. '</strong></li>';
			$this->write_errors_on_file(dirname($basedir['errors']), $msg);
			return('');
		}

		return ($newpdf_path);
	}
	private function write_errors_on_file($in_dir, $msg) {
		if ($fd = fopen($in_dir . '/'. 'errors.txt', 'a')) {
			fwrite($fd, $msg);
			fclose($fd);
		}
	}
	private function clean_errors_file($in_dir) {
		if (is_file($in_dir . '/'. 'errors.txt')) {
			unlink($in_dir . '/'. 'errors.txt');
		}
	}
	
	
}
?>
