<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Zundan
 *
 * PHP 5.1.6 or newer
 *
 * @package		Unimail
 * @author		Zundan - Iñaki Larrañaga Murgoitio
 * @copyright		Copyright (c) 2013, Zundan
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://zundan.com
 * @since		Version 1.0
 * @filesource
 */

if ( ! function_exists('zdn_write_custom_config')) {

	function zdn_write_custom_config($target_file, $data) {

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
}
/* End of file custom_config_helper.php */
/* Location: ./helpers/zdn/custom_config_helper.php */
