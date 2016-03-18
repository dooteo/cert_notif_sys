<?php

class Core {

	// Function to validate the post data
	function validate_post($data)
	{
		/* Validating the hostname, DB name, DB username and DB password. */
		return !empty($data['DBhostname']) && !empty($data['DBuserName']);
		return !empty($data['DBname']) && !empty($data['DBPrefix']);
		return !empty($data['DBpassword']);
	}

	// Function to write the DB config file
	function write_DB_config($target_file, $data) {

		// Config path
		$template_path 	= 'config/database.php';
		
		// Open the file
		if (($database_file = file_get_contents($template_path)) === false){
			return false;
		}

		$new = str_replace("%HOSTNAME%", $data['DBhostname'], $database_file);
		$new = str_replace("%USERNAME%", $data['DBuserName'], $new);
		$new = str_replace("%PASSWORD%", $data['DBpassword'], $new);
		$new = str_replace("%DATABASE%", $data['DBname'], $new);
		$new = str_replace("%DBPREFIX%", $data['DBPrefix'], $new);
		
		// Write the new database.php file
		$handle = fopen($target_file,'w+');

		// Chmod the file, in case the user forgot
		chmod($target_file,0700);

		// Verify file permissions
		if(is_writable($target_file)) {

			// Write the file
			if( fwrite($handle, $new) ) {
				fclose ($handle);
				return true;
			} else {
				fclose ($handle);
				return false;
			}

		} else {
			return false;
		}
	}
	// Function to write the Unimail's custom config file
	function write_custom_config($target_file, $data) {

		// Write the new database.php file
		$handle = fopen($target_file,'w+');

		// Chmod the file, in case the user forgot
		chmod($target_file,0700);

		// Verify file permissions
		if(is_writable($target_file)) {
			$body = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
			foreach ($data as $key => $value) {
				$body .= "\$config['". $key. "'] = '" . $value . "';\n";
			}
			$base_file = basename($target_file);
			$body .= "\n/* End of file ". $base_file. " */\n";
			$body .= "/* Location: ". $target_file. " */\n";
			
			// Write the file
			if( fwrite($handle, $body) ) {
				fclose ($handle);
				return true;
			} else {
				fclose ($handle);
				return false;
			}

		} else {
			return false;
		}
	}
	
	// Function to write script to be executed by cron
	function write_cron_script($orig_file, $target_file, $data) {

		// Open the file
		if (($content_file = file_get_contents($orig_file)) === false){
			return false;
		}

		$new = str_replace("%CONFIGFILE%", $data['configfile'], $content_file);
/*		$new = str_replace("%USERNAME%", $data['DBuserName'], $new);
		$new = str_replace("%PASSWORD%", $data['DBpassword'], $new);
		$new = str_replace("%DATABASE%", $data['DBname'], $new);
		$new = str_replace("%DBPREFIX%", $data['DBPrefix'], $new);
*/		
		// Write the new database.php file
		$handle = fopen($target_file,'w+');

		// Chmod the file, in case the user forgot
		chmod($target_file,0700);

		// Verify file permissions
		if(is_writable($target_file)) {
			// Write the file
			if( fwrite($handle, $new) ) {
				fclose ($handle);
				return true;
			} else {
				fclose ($handle);
				return false;
			}
		} else {
			return false;
		}
	}
	
}

