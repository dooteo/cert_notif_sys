<?php

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") {
					rrmdir($dir."/".$object); 
				} else {
					if (! unlink($dir."/".$object) ) {
						return false;
					}
				}
			}
		}
		reset($objects);
		if (! rmdir($dir) ){
			return false;
		}
	} else {
		return false;
	}
	
	return true;
}

?>
