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

// ------------------------------------------------------------------------

/**
 * Zundan Menu List Helpers
 *
 * This function creates a max level menu. If max level is 3:
 * Level3 A             Level3 B                    Level3 C
 *  |-> Sublevel2 a      |-> Sublevel2 a             |-> Sublevel2 a
 *  |-> Sublevel2 b      |-> Sublevel2 b             |    |-> subsublevel3 1
 *  |-> Sublevel2 c      |-> Sublevel2 c             |    |-> subsublevel3 2
 *                       |     |-> subsublevel3 1    |-> Sublevel2 b
 *                       |     |-> subsublevel3 2    |    |-> subsublevel3 1
 *                       |     |-> subsublevel3 3    |    |-> subsublevel3 2
 *                       |                           |    |-> subsublevel3 3
 *                       |-> Sublevel2 d             |-> Sublevel3 c
 *                       |-> Sublevel2 e
 *                       |-> Sublevel2 f
 *
 * @package		Zundan
 * @subpackage		Helpers
 * @category		Helpers
 * @author		Zundan - Iñaki Larrañaga Murgoitio
 * @link		
 */

if ( ! function_exists('zdn_header_menu')) {

	function zdn_header_menu($aux_menu, $which_level, $addsubclass="sub") {
		$data = "";
		if ($which_level < 1) { /* Do nothing at this level */
			return $data;
		}
		$data = "";
		foreach ($aux_menu as $level1 => $href1) {
			$data .= '<li>';
			if ( is_array($href1) ) {
				$data .= '<a href="">'. $level1. '</a>'."\n".'<ul class="'. $addsubclass. 'menu">';
				/* recursive call*/
				$data .= zdn_header_menu($href1, ($which_level - 1), "sub". $addsubclass);
				$data .=  "</ul>\n";
			} else {
				$data .=  '<a href="' . $href1. '">'. $level1. '</a>';
			}
			$data .=  "</li>\n";
		}
		return ($data);
	}
}
/* End of file header_menu.php */
/* Location: ./helpers/header_menu.php */
