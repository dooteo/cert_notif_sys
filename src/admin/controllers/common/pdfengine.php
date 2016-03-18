<?php
class PDFengine extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();
		
		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		$this->load->library('menu');
		$this->load->model('common/pdfengine_model');
		$this->load->model('common/company_model');
		$this->load->config('unimail', FALSE, TRUE);
		$this->load->config('pagination');
		$this->load->library("pagination");
		$this->load->library("Zdn_pdftk");
		
		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: Clients', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');
		$this->data_header['breadcrumbs'][] = array('text' => 'Engines', 
				'href'=> base_url() . $this->config->item('index_page') . 
					'/common/pdfengine', 'separator' => ' -&gt; ');

		
		$this->base_path = base_url() . $this->config->item('index_page') . '/common/pdfengine/';
		$this->base_user_path = base_url() . $this->config->item('index_page') . '/common/user/';
		$this->data['path_delete'] = $this->base_path. 'delete';
		$this->data['path_update'] = $this->base_path . 'update/';
		$this->data['path_insert'] = $this->base_path .'insert/';
		$this->data['path_view'] = $this->base_path .'view/';
		$this->data['path_csv'] = $this->base_path .'csv/';
		$this->data['path_cancel'] = $this->base_path;
		$this->data['path_desactivate'] = $this->base_path . 'desactivate/';
		$this->data['path_activate'] = $this->base_path . 'activate/';
		$this->data['path_company'] = base_url() . $this->config->item('index_page') . 
				'/common/company/update/';
		
		$this->data['error_warning'] = '';
		$this->data['success'] = '';
		
		// Load Language's files and fill l10n strings
		$language = 'en_us';
		$this->lang->load('common', $language);
		$this->lang->load('pdfengine', $language);
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_delete'] = $this->lang->line('common_delete');
		$this->data['button_edit'] = $this->lang->line('common_edit');
		$this->data['button_new'] = $this->lang->line('common_new');
		$this->data['button_save'] = $this->lang->line('common_save');
		$this->data['button_csv'] = $this->lang->line('text_csv');
		
		$this->data['column_action'] = $this->lang->line('common_action');
		$this->data['column_name'] = $this->lang->line('text_name');
		$this->data['column_company'] = $this->lang->line('text_company');
		$this->data['column_status'] = $this->lang->line('common_status');
		$this->data['column_username'] = $this->lang->line('text_username');
		
		$this->data['text_active'] = $this->lang->line('common_active');
		$this->data['text_activate'] = $this->lang->line('common_activate');
		$this->data['text_desactivate'] = $this->lang->line('common_desactivate');
		$this->data['text_edit'] = $this->lang->line('common_edit');
		$this->data['text_inactive'] = $this->lang->line('common_inactive');
		$this->data['text_new'] = $this->lang->line('common_new');
		$this->data['text_no_results'] = $this->lang->line('common_no_results');
		$this->data['text_no_fillable'] = $this->lang->line('text_no_fillable');
		$this->data['text_view'] = $this->lang->line('common_view');
		$this->data['text_yes'] = $this->lang->line('common_yes');
		$this->data['text_no'] = $this->lang->line('common_no');
		
		$this->data['text_company'] = $this->lang->line('text_company');
		$this->data['text_file_maxsize'] = $this->lang->line('text_file_maxsize');
		$this->data['text_filename'] = $this->lang->line('text_filename');
		$this->data['text_old_filename'] = $this->lang->line('text_old_filename');
		$this->data['text_new_filename'] = $this->lang->line('text_new_filename');
		$this->data['text_comment'] = $this->lang->line('text_comment');
		$this->data['text_pdfengine'] = $this->lang->line('text_pdfengine');
		$this->data['text_pdfengines_list'] = $this->lang->line('text_pdfengines_list');
		$this->data['text_new_pdfengine'] = $this->lang->line('text_new_pdfengine');
		$this->data['text_name'] = $this->lang->line('text_name');
		$this->data['text_fields'] = $this->lang->line('text_fields');
		$this->data['text_preg_type'] = $this->lang->line('text_preg_type');
		$this->data['text_preg_name'] = $this->lang->line('text_preg_name');
		
	}

	function index() {	// Shows a list of PDF engines
		$this->data['heading_title'] = $this->data['text_pdfengines_list'];

		// Calc for pagination
		$config['base_url'] =  $this->base_path;
		$config['total_rows'] = $this->pdfengine_model->record_count();
		$limit = $this->config->item('per_page');

		// (...)/common/pdfengine/index/2  numer 2 is at 4th segment;
		$page = $this->uri->segment(4);
	        $this->data['pdfengines'] = $this->pdfengine_model->fetch_engines($limit, $page);

		$this->pagination->initialize($config);
		$this->data["pagination"] = $this->pagination->create_links();

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('pdfengine/pdfengine_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function insert(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', $this->data['text_name'], 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			$this->data['heading_title'] = '<b>'. $this->data['text_new_pdfengine']. '</b>';
		
			$companies = $this->company_model->fetch_companies();
			if ($companies === false) {
				$warning = array(); // No company defined
				$warning['code'] = '01';
				$this->show_warning_page($warning);
				return;
			}
		
			// Create a companies select options
			$companies_number = count($companies);
			$this->data['companies'] = '<select name="companyID" tabindex="3">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .=  '<option value="'.
						$companies[$i]['id'].'">' .
						$companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";
			$this->data['file_max_size'] = ini_get('upload_max_filesize');
			
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' :: ');
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('pdfengine/pdfengine_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 
			$company = $this->company_model->get_company($data['companyID']);
			
			$pdf_basedir = $_SERVER["DOCUMENT_ROOT"] . '/..' ;
			$pdf_dir = $this->config->item('unml_engines')  ;
			$pdf_dir .=  $company['dirpath']. "/". $data['name'] . "/";
			$data['path'] = $pdf_dir . preg_replace("![^a-z0-9\.]+!i", "_", 
					$_FILES['upfile']['name']);
			$pdf_file = $pdf_basedir . $data['path'];
			$pdf_dir = $pdf_basedir . $pdf_dir;
			
			if ( is_dir($pdf_dir) ) {
				$warning = array(); // Engine directory exists
				$warning['code'] = '02';
				$warning['company'] = $company['name'];
				$warning['dir'] = $pdf_dir;
				$this->show_warning_page($warning);
				return;
			} else if (! mkdir ($pdf_dir, 0755)) {
				$warning = array(); // Could not create dir
				$warning['code'] = '03';
				$warning['company'] = $company['name'];
				$warning['dir'] = $pdf_dir;
				$this->show_warning_page($warning);
				return;
			}
			if (! is_writable($pdf_dir)) {
				rmdir ($pdf_dir);
				$warning = array(); // Engine directory not writable
				$warning['code'] = '04';
				$warning['company'] = $company['name'];
				$warning['dir'] = $pdf_dir;
				$this->show_warning_page($warning);
				return;
			}
			
			if (! move_uploaded_file ($_FILES['upfile']['tmp_name'],$pdf_file) ){
				rmdir ($pdf_dir);
				$warning = array(); // Can't move uploaded file
				$warning['code'] = '05';
				$warning['company'] = $company['name'];
				$warning['file'] = $data['path'];
				$this->show_warning_page($warning);
				return;
			}

			// Now, we got to get fillables fields from PDF
			$tmp_dir =  $pdf_basedir.$this->config->item('unml_engines') ."/_tmp" ;
			if ((! is_dir($tmp_dir)) && (! mkdir ($tmp_dir, 0755)) ){
				$warning = array(); // Could not create dir
				$warning['code'] = '03';
				$warning['company'] = $company['name'];
				$warning['dir'] = $tmp_dir;
				$this->show_warning_page($warning);
				return;
			}

			$txt_file =  $pdf_dir . "fields.txt";
			
			// dump_data_fields returns "" when sucessful
			$pdftk = new Zdn_pdftk();
			if ($pdftk->dump_data_fields($pdf_file, $txt_file) !== "") {
				unlink ($pdf_file);
				unlink ($txt_file);
				// remove engine dir
				rmdir ($pdf_dir);

				$warning = array(); // Could not dump data field
				$warning['code'] = '06';
				$warning['company'] = $company['name'];
				$warning['file'] = $pdf_file;
				$this->show_warning_page($warning);
				return;
			}
			$data['fields'] = $pdftk->parse_ddf_file($txt_file);
			
			if ( ! empty($data['active']) ) {
				$data['active'] = 1;
			} else {
				$data['active'] = 0;
			}
			$this->pdfengine_model->set_new_engine($data);
			redirect($this->base_path);
		}
	}

	function delete () {
		$data = $this->input->post(NULL, TRUE); 
		// returns all POST items with XSS filter
		$pdf_basedir = $_SERVER["DOCUMENT_ROOT"] . '/..' ;
		
		$items = count ($data['selected']);
		for ($i = 0; $i < $items; $i++) {
			// Todo: not sure if we need to delete engines
			// delete files and dir
			//$engine = $this->pdfengine_model->get_engine($data['selected'][$i]);
			//$dirname = $pdf_basedir . dirname($engine['path']);
			// Remove PDF and DDF files
			//unlink ($pdf_basedir. $engine['path']);
			//unlink ($dirname . "/fields.txt");
			// remove engine dir
			//rmdir ($dirname);

			$this->pdfengine_model->delete_engine($data['selected'][$i]);
		}

		redirect($this->base_path);
	}

	function update (){	// Edit/Update Engine data
		$data = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		
		if (empty($_FILES['upfile']['name']) && empty($data)) {
			$engine_id = $this->uri->segment(4);
			$this->data['engine'] = $this->pdfengine_model->get_engine($engine_id);
		
			if ($this->data['engine']['active'] == 1) {
				$this->data['engine']['active'] = $this->data['text_active'];
			} else {
				$this->data['engine']['active'] = $this->data['text_inactive'];
			}

			$company = $this->company_model->get_company($this->data['engine']['companyID']);
			$this->data['engine']['company'] = $company['name'];
			$this->data['engine']['filename'] = basename($this->data['engine']['path']);
			$this->data['heading_title'] = $this->data['text_pdfengine'] . 
			' :: ' .$this->data['engine']['name'] ;

			if (! empty($this->data['engine']['fields'])) {
			$aux1 = "<p>" . $this->data['text_preg_type'] . preg_replace("(<>)", 
				"</p><p>" . $this->data['text_preg_type'], 
				$this->data['engine']['fields']) . "</p>";
			$aux2 = preg_replace("(\|\|)", "&emsp; - " . $this->data['text_preg_name'] ,
				 $aux1);
			$this->data['engine']['fields'] = $aux2 ;
			}
			
			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_edit']. '</b>',
				'href'=> '', 'separator' => ' :: ');

			$this->load->view('templates/header',$this->data_header);
			$this->load->view('pdfengine/pdfengine_form',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			$engine_id = $this->uri->segment(4);
			$engine = $this->pdfengine_model->get_engine($engine_id);
			$pdf_dir = dirname($engine['path']);
			$old_pdf = basename($engine['path']);
			$pdf_basedir = $_SERVER["DOCUMENT_ROOT"] . '/..' ;
			if (! empty($_FILES['upfile']['name']) ){
				// Remove old files
				unlink ($pdf_basedir .$engine['path']);
				unlink ($pdf_basedir .$pdf_dir . "/fields.txt");
				// Create new ones
				$upd_data['path']= $pdf_dir . "/" . 
						preg_replace("![^a-z0-9\.]+!i", "_", 
						$_FILES['upfile']['name']);
				$new_pdf = $pdf_basedir . $upd_data['path'];
				
				if (!move_uploaded_file ($_FILES['upfile']['tmp_name'],$new_pdf)){
					$warning = array(); // Can't move uploaded file
					$warning['code'] = '05';
					$warning['company'] = $company['name'];
					$warning['file'] = $upd_data['path'];
					$this->show_warning_page($warning);
				}

				// Now, we got to get fillables fields from PDF
				$tmp_dir = $pdf_basedir . $this->config->item
								('unml_engines')."_tmp" ;
				if ((! is_dir($tmp_dir)) && (mkdir ($tmp_dir, 0755))){
					$warning = array(); // Could not create dir
					$warning['code'] = '03';
					$warning['company'] = $company['name'];
					$warning['dir'] = $tmp_dir;
					$this->show_warning_page($warning);
					return;
				}

				$txt_file = $pdf_basedir .$pdf_dir . "/fields.txt";
			
				// dump_data_fields returns "" when sucessful
				$pdftk = new Zdn_pdftk();
				if ($pdftk->dump_data_fields($new_pdf, $txt_file) !== "") {
					unlink ($new_pdf);
					unlink ($txt_file);
					// remove engine dir
					rmdir ($pdf_basedir.$pdf_dir);
					$this->pdfengine_model->delete_engine($data['id']);
					
					$warning = array(); // Could not dump data field
					$warning['code'] = '06';
					$warning['company'] = $company['name'];
					$warning['file'] = $new_pdf;
					$this->show_warning_page($warning);
					return;
				}

				$upd_data['fields'] = $pdftk->parse_ddf_file($txt_file);
			} else {
				$upd_data['path'] = "";
				$upd_data['fields'] = "";
			}
			$upd_data['id'] = $engine_id;
			$upd_data['comment'] = $data['comment'];
			
			$this->pdfengine_model->set_engine($upd_data);
			redirect($this->base_path);
		}
	}

	function view (){
		$engine_id = $this->uri->segment(4);
		$this->data['engine'] = $this->pdfengine_model->get_engine($engine_id);
		$this->data['engine']['filename'] = basename($this->data['engine']['path']);
		if ($this->data['engine']['active'] == 1) {
			$this->data['engine']['active'] = 'checked="checked"';
		} else {
			$this->data['engine']['active'] = '';
		}
		
		$company = $this->company_model->get_company($this->data['engine']['companyID']);

		$this->data['engine']['company'] = $company['name'];
	
		$this->data['heading_title'] = $this->data['text_pdfengine'] . 
			' :: ' .$this->data['engine']['name'] ;
		if (! empty($this->data['engine']['comment'])) {
			$aux1 = nl2br($this->data['engine']['comment']);
			$this->data['engine']['comment'] = $aux1;
		}
		
		if (! empty($this->data['engine']['fields'])) {
			$aux1 = "<p>" . $this->data['text_preg_type'] . preg_replace("(<>)", 
				"</p><p>" . $this->data['text_preg_type'], 
				$this->data['engine']['fields']) . "</p>";
			$aux2 = preg_replace("(\|\|)", "&emsp; - " . $this->data['text_preg_name'] ,
				 $aux1);
			$this->data['engine']['fields'] = $aux2 ;
		} else {
			$this->data['engine']['fields'] = $this->data['text_no_fillable'];
		}
		
		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'. $this->data['text_view']. '</b>', 
			'href'=> '', 'separator' => ' :: ');

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('pdfengine/pdfengine_view',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function activate(){	// Updates engine status as activate
		$engine_id = $this->uri->segment(4);
		$this->pdfengine_model->activate_engine($engine_id);

		redirect($this->base_path);
	}

	function desactivate(){	// Updates engine status as inactive
		$engine_id = $this->uri->segment(4);
		$this->pdfengine_model->desactivate_engine($engine_id);

		redirect($this->base_path);
	}
	
	// Generates a CSV from DB and send it to user
	function csv (){
		$engine_id = $this->uri->segment(4);
		$engine = $this->pdfengine_model->get_engine($engine_id);
		$company = $this->company_model->get_company($engine['companyID']);
		
		date_default_timezone_set('Europe/Madrid');
		$filename = preg_replace("![^a-z0-9\.]+!i", "_", $company['name']) . 
			"_" . $engine['name'] . 
			"_" . date('YmdHis');
		
		
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=". $filename. ".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$data = array();
		$data_type = array();
		$data_name = array();
		
		$data_type[0] = '""';
		$data_type[1] = '""';
		$data_type[2] = '""';
		$data_type[3] = '""';
		$data_type[4] = '""';
		$data_type[5] = '""';
		$data_type[6] = '""';
		$data_type[7] = '""';
		
		$data_name[0] = '"Name"';
		$data_name[1] = '"LastNames"';
		$data_name[2] = '"DNI/CIF"';
		$data_name[3] = '"Email"';
		$data_name[4] = '"PDF name/engine"';
		$data_name[5] = '"Mail Template"';
		$data_name[6] = '"MailSetup Name"';
		$data_name[7] = '"Devilery days (separated by comas:,)"';
		
		if (! empty ($engine['fields'])) {
			$i = 8;
			$tok = strtok($engine['fields'], "<>");
			while ($tok !== false) {
				$data[$i] = explode("||", $tok);
				$data_type[$i] = '"' . $data[$i][0] .'"';
				$data_name[$i] = '"' . $data[$i][1] . '"';
				$tok = strtok ("<>");
				$i++;
			}
		} 
		
		$output = fopen("php://output", "w");
		fputcsv($output, $data_type, "\t");
		fputcsv($output, $data_name, "\t");
		fclose($output);
	}
	private function create_engine_dirs ($name){
		$company_dir = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_companies') . '/' . 
				$name;

		if ((! is_dir($company_dir)) && (mkdir ($company_dir))){
			$warning = array(); // Could not create dir
			$warning['code'] = '03';
			$warning['company'] = $name;
			$warning['dir'] = $pdf_dir;
			$this->show_warning_page($warning);
			return false;
		}
		$company_dir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_engines') . '/' . 
				$name;
		if ((! is_dir($company_dir)) && (mkdir ($company_dir))){
			$warning = array(); // Could not create dir
			$warning['code'] = '03';
			$warning['dir'] = $pdf_dir;
			$warning['company'] = $name;
			$this->show_warning_page($warning);
			return false;
		}
		$company_dir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_notif') . '/' . 
				$name;
		if ((! is_dir($company_dir)) && (mkdir ($company_dir))){
			$warning = array(); // Could not create dir
			$warning['code'] = '03';
			$warning['dir'] = $pdf_dir;
			$warning['company'] = $name;
			$this->show_warning_page($warning);
			return false;
		}
	}
	private function rename_engine_dirs ($oldname, $newname){
		$company_olddir = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_companies') . '/' . 
				$oldname;
		$company_newdir = $_SERVER["DOCUMENT_ROOT"] . '/..'.
				$this->config->item('unml_companies') . '/' . 
				$newname;
		if ((! is_dir($company_newdir)) && (rename ($company_olddir, $company_newdir)) ){
			$warning = array(); // Could not rename dir
			$warning['code'] = '03';
			$warning['dir'] = 'From: '.$company_olddir .'<br>To: '.$company_newdir;
			$this->show_warning_page($warning);
			return false;
		}

		$company_olddir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_engines') . '/' . 
				$oldname;
		$company_newdir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_engines') . '/' . 
				$newname;
		if ((! is_dir($company_newdir)) && (rename ($company_olddir, $company_newdir)) ){
			$warning = array(); // Could not rename dir
			$warning['code'] = '03';
			$warning['dir'] = 'From: '.$company_olddir .'<br>To: '.$company_newdir;
			$this->show_warning_page($warning);
			return false;
		}

		$company_olddir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_notif') . '/' . 
				$oldname;
		$company_newdir = $_SERVER["DOCUMENT_ROOT"] .  '/..'.
				$this->config->item('unml_notif') . '/' . 
				$newname;
		if ((! is_dir($company_newdir)) && (rename ($company_olddir, $company_newdir)) ){
			$warning = array(); // Could not rename dir
			$warning['code'] = '03';
			$warning['dir'] = 'From: '.$company_olddir .'<br>To: '.$company_newdir;
			$this->show_warning_page($warning);
			return false;
		}
	}

	private function show_warning_page($msg) {
		
		switch ($msg['code']){
		case '01': 		// No company defined
			$warning['button_1'] = '<a href="'. $this->data['path_insert_company'].
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';
			$warning['heading_title'] =' No Company Defined';
			$warning['message'] = '<p>There is not company defined. ';
			$warning['message'] .= 'Please click on NEW button and add a new company.</p>';
			break;
		case '02': 		// Engine directory exists
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Engine directory exists';
			$warning['message'] = '<p>Could not create new engine directory, as it previously exists: </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['dir']. '</li></ul></p>';
			break;
		case '03': 		// Could not create directory
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Could not create directory';
			$warning['message'] = '<p>Could not create next directory: </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['dir']. '</li></ul></p>';
			break;
		case '04': 		// Engine directory not writable
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Directory not writable';
			$warning['message'] = '<p>Next engine directory is not writable: </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['dir']. '</li></ul></p>';
			break;
		case '05': 		// Can't move uploaded file
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Can\'t move uploaded file';
			$warning['message'] = '<p>Next uploaded file can\'t move : </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['file']. '</li></ul></p>';
			break;
		case '06': 		// Could not dump data field
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Can\'t dump data';
			$warning['message'] = '<p>Could not dump data field for next file: </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['file']. '</li></ul></p>';
			break;
		case '07': 		// Could not rename directory
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Could not rename directory';
			$warning['message'] = '<p>Could not rename next directory: </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['dir']. '</li></ul></p>';
			break;
		default:		// Unknown error
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Unknonw error';
			$warning['message'] = 'Sorry. It was not able to detect error type.';
		
		}
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('warning/warning',$warning);
		$this->load->view('templates/footer','');
	}
}
