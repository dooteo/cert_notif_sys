<?php
/* 
Menu example:
-------------
	$data['menu'] = array(
		'Pizarra'=>'common/dashboard', 
		'Clients'=>array(
			'Companies'=>'/admin/index.php/common/company',
			'Users'=>'/admin/index.php/common/user'
		), 
		'System'=>array(
			'Setup'=>'setting/system', 
			'Administrators'=>array(
				'New'=>'setting/admin/new', 
				'Edit'=>'setting/admin/edit'
			), 
			'Ujuju'=>'common/uiui',
			'Test 01'=>array(
				'prueb1'=>'popo1',
				'prueb2'=>'popo2',
				'prueb4'=>'popo5'
			), 
			'Test'=>array(
				'prueb1'=>'popo1',
				'prueb2'=>'popo2'
			), 
			'Test3'=>array(
				'prueb1'=>'popo1',
				'prueb2'=>'popo2'
			),
		)
	);

*/
function get_menu($isCompAdmin=0) {

	$data['menu'] = array(
		'Clients'=>array(
			'Engines'=>'/client/index.php/common/pdfengine',
		), 
		'System'=>array(
			'Company data'=>'/client/index.php/common/company',
			'My data'=>'/client/index.php/common/user'
		)
	);
	if ($isCompAdmin == 1) {
		$data['menu']['System']['Company users'] = '/client/index.php/common/user/ulist';
		$data['menu']['System']['Mail Templates'] = '/client/index.php/common/mailtmpl/';
	}
		
	$data['maxmenu_level'] = 3;
	return ($data);
}
