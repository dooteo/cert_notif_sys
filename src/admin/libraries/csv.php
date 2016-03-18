<?php

class Csv {

	
	// Puts uploaded CSV file into unimail's TMP dir
	function get_uploaded_ready($basedir, $company) {
		$inner_msg = array();
		$inner_msg['error'] = ''; // No error

		// Create tmp notification dir
		if ((! is_dir($basedir['notiftmp'])) && 
			(! mkdir ($basedir['notiftmp'], 0755, true)) ){
			$inner_msg['error'] = '02';
			$inner_msg['company'] = $company['name'];
			$inner_msg['item'] = $basedir['notiftmp'];
			return ($inner_msg);
		}
		// Save CSV file
		$calc_filename = preg_replace("![^a-z0-9\.]+!i", "-", 
				$_FILES['csvupfile']['name']);
		if (empty ($_FILES['csvupfile']['tmp_name'])) {
			$inner_msg['error'] = '03';
			$inner_msg['company'] = $company['name'];
			$inner_msg['item'] = $calc_filename;
			return ($inner_msg);
		}

		$calc_tmpfile = $basedir['notiftmp'] .'/' . $calc_filename;
		if (! move_uploaded_file ($_FILES['csvupfile']['tmp_name'], $calc_tmpfile) ){
			$inner_msg['error'] = '04';
			$inner_msg['company'] = $company['name'];
			$inner_msg['item'] = $calc_filename;
			return ($inner_msg);
		}
		
		$inner_msg['csv_tmpfile'] = $calc_tmpfile;
		$inner_msg['csv_file'] = $calc_filename;
		return($inner_msg);
	}
	
	function split_file($company, $CSV_file, $target_dir) {
		$inner_msg = array();
		$utf8 = new ZDN_Encoding();
		$csv_rows = array();
		
		if (! is_dir($target_dir) && (! mkdir($target_dir, 0777, true)) ) {
			$inner_msg['error'] = 'csv01';
			$inner_msg['company'] = $company['name'];
			$inner_msg['item'] = $target_dir;
			return ($inner_msg);
		}
		
		if ( ($fd = fopen($CSV_file, "r")) !== false ) {
			$i = 0;
			while ( $csv_row = str_replace("\"", "",fgetcsv($fd,0,"\t" )) ){
				// First column can not be empty
				if (empty($csv_row[0]) || (strcmp($csv_row[0],'Name') === 0) ){ 
					continue;
				}
				$max_cols = count($csv_row);
				if ($max_cols < 8) { // At least each row must contain 7 cols
					continue;
				}
				$dni = preg_replace('/[^A-Z0-9]/s', '', 
							strtoupper($csv_row[2]));
				$csv_cols = array(); // Initialize
				$csv_cols['company'] = $utf8->toUTF8($company['dirpath']);
				$csv_cols['companyID'] = $utf8->toUTF8($company['id']);
				$csv_cols['name'] = $utf8->toUTF8($csv_row[0]);
				$csv_cols['lastnames'] = $utf8->toUTF8($csv_row[1]);
				$csv_cols['ident'] = $utf8->toUTF8($dni);
				$csv_cols['email'] = $utf8->toUTF8($csv_row[3]);
				$csv_cols['engine'] = $utf8->toUTF8($csv_row[4]);
				$csv_cols['mail_template'] = $utf8->toUTF8($csv_row[5]);
				$csv_cols['mailsetup'] = $utf8->toUTF8($csv_row[6]);
				$csv_cols['delivery_days'] = $utf8->toUTF8($csv_row[7]);
				
				for ($j = 8; $j < $max_cols; $j++){
					if ( empty($csv_row[$j]) ){
						break;
					}
					$csv_cols['field_'.($j-8)] = $utf8->toUTF8($csv_row[$j]);
				}
				
				// Write a single file for each CSV row
				$filename = $target_dir . $company['dirpath'] .'_'. $dni. '_'. 
							$i . '_' . date('YmdHisu').'.json';
				
				if ( ($jsonfd = fopen($filename, 'w')) !== false ){
					fwrite($jsonfd, json_encode($csv_cols));
					fclose($jsonfd);
				} else {
					$inner_msg['error'] = 'csv02';
					$inner_msg['company'] = $company;
					$inner_msg['item'] = 'Can not create JSON file from CSV as:'.$filename;
					return ($inner_msg);
				}
				$i++;
				
			}
			fclose($fd); // All lines were read, so close this file
		} else {
			$inner_msg['error'] = 'csv03';
			$inner_msg['item'] = 'Could not read CSV file: '. $CSV_file;
		}
		return ($inner_msg);
		
	}
	
	
	function save_file($notif_dir, $company, $file, $on_date){
		$inner_msg = array();
		$inner_msg['error'] = ''; // No Error
		
		// format: 'uploaded'+year+month+day+hour+min+sec+milisec
		// uploaded/2/0/1/3/1/0/2/1/0/8/2/6/1800
		$csv_dir = $notif_dir. '/uploaded/' . 
				implode( "/", str_split(substr($on_date, 0, 12)) ) .
				'/'. substr($on_date, 12, 4) ;

		// true = create subdirs recursively
		if ( (! is_dir($csv_dir)) && (! mkdir ($csv_dir, 0755, true)) ){
			$inner_msg['error'] = '02';
			$inner_msg['company'] = $company['name'];
			$inner_msg['item'] = $csv_dir;
			return ($inner_msg);
		}
		
		$calc_file = $csv_dir . '/' . $file['csv_file'];
		if (is_file($calc_file)) { // File previously exist
			$path_parts = pathinfo($file['csv_file']);
			$calc_file .= '_new_'.$which_date .'.' .$path_parts['extension'];
		}
	
		if (! rename($file['csv_tmpfile'], $calc_file) ){
			$inner_msg['error'] = '09';
			$inner_msg['company'] = $company['name'];
			$inner_msg['item'] = $file['csv_tmpfile'];
		}
		return($inner_msg);
	}
}
	
?>
