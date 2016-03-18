<?php
class Menu extends CI_Lang{

	public function __construct() {
		parent::__construct();
		
	}
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
	function get_menu() {
		// Load Language's files
		$language = 'en_us';
		
		// Note for myself: as Menu extends CI_Lang, we don't need '->lang'
		// $this->lang->load('menu', $language) or
		// $this->lang->line('menu_new_csv_list')
		
		$this->load('menu', $language);
		
		$data['menu'] = array(
			$this->line('menu_notifications') => array(
				$this->line('menu_new_csv_list') => 
								'/admin/index.php/common/notif/insert',
				$this->line('menu_list') => '/admin/index.php/common/notif'
			),
	 		$this->line('menu_clients') =>array(
				$this->line('menu_companies') =>'/admin/index.php/common/company',
				$this->line('menu_mail_setup') =>'/admin/index.php/common/cmailcfg',
				$this->line('menu_users') =>'/admin/index.php/common/user',
				$this->line('menu_pdf_engines') =>'/admin/index.php/common/pdfengine',
				$this->line('menu_mail_templates') =>
								'/admin/index.php/common/mailtmpl'
			), 
			$this->line('menu_system') =>array(
				$this->line('menu_administrators') =>
							'/admin/index.php/common/administrator/',
				$this->line('menu_mail_setup') =>
							'/admin/index.php/common/sysmail/',
				$this->line('menu_tsa_certification') =>
							'/admin/index.php/common/tsa_cert/',
			)
		);
	
		$data['maxmenu_level'] = 3;
		return ($data);
	}
	
}
