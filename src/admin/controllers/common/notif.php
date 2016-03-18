<?php
class Notif extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();
		
		if ( (!$this->session->userdata('isLoggedIn')) || 
		  	(!$this->session->userdata('isAdmin')) ) {
			redirect('/login/show_login');
		}
		$this->load->model('common/pdfengine_model');
		$this->load->model('common/company_model');
		$this->load->model('common/mailtmpl_model');
		$this->load->model('common/cmailcfg_model');
		$this->load->model('common/mail_model');
		$this->load->config('unimail', FALSE, TRUE);
		$this->load->config('pagination');
		$this->load->library("pagination");
		$this->load->library("menu");
		$this->load->library("csv");
		$this->load->library("notif_generator");
		$this->load->library("Zdn_encoding");
		$this->load->library("Zdn_pdftk");
		$this->load->library('email');

		date_default_timezone_set('Europe/Madrid');
		
		$this->data_header = $this->menu->get_menu();
		$this->data_header['javascript'] = "";
		$this->data_header['name'] = $this->session->userdata('name');
		$this->data_header['base_link_path'] = base_url();
		$this->data_header['breadcrumbs'] = array();
		$this->data_header['breadcrumbs'][] = array('text' => 'Home :: Notifications', 
				'href'=> base_url() . $this->config->item('index_page'), 
				'separator' => '');

		
		$this->base_path = base_url() . $this->config->item('index_page') . 
							'/common/notif/';
		$this->base_user_path = base_url() . $this->config->item('index_page') . 
							'/common/user/';
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
		$this->lang->load('notif', $language);
		
		
		$this->data['button_cancel'] = $this->lang->line('common_cancel');
		$this->data['button_delete'] = $this->lang->line('common_delete');
		$this->data['button_edit'] = $this->lang->line('common_edit');
		$this->data['button_new'] = $this->lang->line('common_new');
		$this->data['button_save'] = $this->lang->line('common_save');
		$this->data['button_see_engine'] = $this->lang->line('text_see_engine');
		$this->data['button_csv'] = $this->lang->line('text_csv');
		$this->data['button_return'] = $this->lang->line('text_return');
		
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
		$this->data['text_view'] = $this->lang->line('common_view');
		$this->data['text_add_new_field'] = $this->lang->line('text_add_new_field');
		$this->data['text_del_field'] = $this->lang->line('text_del_field');
		$this->data['text_return'] = $this->lang->line('text_return');

		
		$this->data['text_company'] = $this->lang->line('text_company');
		$this->data['text_filename'] = $this->lang->line('text_filename');
		$this->data['text_csv_filename'] = $this->lang->line('text_csv_filename');
		$this->data['text_pdf_filename'] = $this->lang->line('text_pdf_filename');
		$this->data['text_old_filename'] = $this->lang->line('text_old_filename');
		$this->data['text_new_filename'] = $this->lang->line('text_new_filename');
		$this->data['text_comment'] = $this->lang->line('text_comment');
		$this->data['text_pdfengine'] = $this->lang->line('text_pdfengine');
		$this->data['text_pdfengines_list'] = $this->lang->line('text_pdfengines_list');
		$this->data['text_new_notification'] = $this->lang->line('text_new_notification');
		$this->data['text_name'] = $this->lang->line('text_name');
		$this->data['text_fields'] = $this->lang->line('text_fields');
		$this->data['text_fillable'] = $this->lang->line('text_fillable');
		$this->data['text_preg_type'] = $this->lang->line('text_preg_type');
		$this->data['text_preg_name'] = $this->lang->line('text_preg_name');
		$this->data['text_must_cert'] = $this->lang->line('text_must_cert');
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
		$warning = array();
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('companyID', 
						$this->data['text_company'], 
						'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			$this->data_header['javascript'] = '
			<script type="text/javascript">
			$(document).ready(function(){ 
			  $(\'#company\').change(function(){
			    $(\'#msgtmpls\').empty();
			    var company_id = $(\'#company\').val();
			    $.ajax({
			      type: \'POST\',
			      url: \''. $this->base_path. 
			      '/get_msgtmplts_by_company_HTML/\'+company_id,
			      success: function(response) {
				$(\'#msgtmpls\').html(response);
			      }
			    });
			  });
			});
			</script>';
			$this->data['heading_title'] = '<b>'. 
					$this->data['text_new_notification']. '</b>';
		
			$companies = $this->company_model->fetch_companies();
			if ($companies === false) {
				$warning['error'] = '01';
				$this->show_warning_page($warning);
				return;
			}
		
			// Create a companies select options
			$companies_number = count($companies);
			$this->data['companies'] = '<select id="company" class="width10" name="companyID" tabindex="5">';
			for ($i = 0; $i < $companies_number; $i ++) {
				$this->data['companies'] .= 
					'<option value="'.$companies[$i]['id'].'">' .
					$companies[$i]['name'].'</option>';
			}
			$this->data['companies'] .= "</select>";

			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' -&gt; ');
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('notif/notification_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$data = $this->input->post(NULL, TRUE); 
			$company = $this->company_model->get_company($data['companyID']);
			
			$which_date = date('YmdHisu');
			// Get unimail config based dirs, and 
			$basedir = $this->get_unimail_basedir($company);
			
			// Moves uploaded CSV file to TMP dir
			$csv_file = $this->csv->get_uploaded_ready($basedir, $company);
			if (! empty($result['error'])) { 
				$this->show_warning_page($csv_file); 
			}
			
			// Read tmp CSV file and split it into several JSON files in _todo_ dir
			$result = $this->csv->split_file($company, 
							$csv_file['csv_tmpfile'], 
							$basedir['c_init']);
			if (! empty($result['error'])) { 
				$this->show_warning_page($result); 
			}
			
			$result = $this->csv->save_file($basedir['notif'], $company,
							$csv_file, $which_date);
			if (! empty($result['error'])) { 
				$this->show_warning_page($result); 
			}
			// Check engines for each recently created _todo_'s JSON
			$result = $this->notif_generator->generate_all($basedir);
			
			$this->mail_model->send_mails($basedir['outputnow']);

		}
	}
	
	
	// Get unimail config based dirs, and create them if they don't exists
	private function get_unimail_basedir($company) {
		$unimail_dir = array();
		$unimail_dir['todo'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/..' ).
				$this->config->item('unml_todo');
		$unimail_dir['notif'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/..') .
				$this->config->item('unml_notif') .
				$company['dirpath'];
		$unimail_dir['output'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/..') .
				$this->config->item('unml_cron_output');
		$unimail_dir['outputnow'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/..') .
				$this->config->item('unml_cron_output_now');
		$unimail_dir['outputfut'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/..') .
				$this->config->item('unml_cron_output_future');
		$unimail_dir['outputtmp'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/..') .
				$this->config->item('unml_cron_output_tmp');
		$unimail_dir['tsa'] = realpath($_SERVER["DOCUMENT_ROOT"] . '/..' )
				.$this->config->item('unml_tsa');
		$unimail_dir['tsa_configfile'] = realpath($_SERVER["DOCUMENT_ROOT"] .
				 '/..') .$this->config->item('unml_tsa_conffile');
		$unimail_dir['tsa_serverfile'] = realpath($_SERVER["DOCUMENT_ROOT"] .
				 '/..') .$this->config->item('unml_tsa_serverfile');
		$unimail_dir['notiftmp'] = $unimail_dir['notif'] . '/tmp/';
		$unimail_dir['errors'] = $unimail_dir['todo'] . '/errors/';
		$unimail_dir['cert'] = $unimail_dir['todo'] . '/cert/';
		
		// c_init == means cert_init
		$unimail_dir['c_init'] = $unimail_dir['cert'] . '/01_init/';
		$unimail_dir['c_pdf'] = $unimail_dir['cert'] . '/02_done_pdf/';
		$unimail_dir['c_info'] = $unimail_dir['cert'] . '/03_done_info/';
		$unimail_dir['c_cert'] = $unimail_dir['cert'] . '/04_done_cert/';
		$unimail_dir['c_mail'] = $unimail_dir['cert'] . '/05_to_mail/';

		foreach ($unimail_dir as $key => $dir) {
			if ((! is_dir($dir)) && (!is_file($dir))){
				mkdir ($dir, 0777, true);
			}
		}
		return ($unimail_dir);
	}
	
	private function show_warning_page($msg) {
		
		switch ($msg['error']){
		case '01': 		// No company defined
			$warning['button_1'] = '<a href="'. base_url(). 
					$this->config->item('index_page'). 
					'/common/company/insert' .
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';
			$warning['heading_title'] =' No Company Defined';
			$warning['message'] = '<p>There is not company defined. ';
			$warning['message'] .= 'Please click on NEW button and add '.
						'a new company.</p>';
			break;
		case '02': 		// Could not create a directory
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Can\'t create directory';
			$warning['message'] = '<p>Could not create next directory: </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['item']. '</li></ul></p>';
			break;
		case '03': 		// CSV file not uploaded
			$warning['button_1'] = '<a href="'. $this->data['path_insert'].
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';
					
			$warning['heading_title'] = $msg['company'].' :: CSV file not uploaded';
			$warning['message'] = '<p>Due to unknown reason CSV file <strong>';
			$warning['message'] .= $msg['item'].'</strong> can\'t be upload</p>';
			$warning['message'] .=	'<p>Note: maximum size allowed is: <strong>';
			$warning['message'] .= ini_get('upload_max_filesize') . '</strong></p>';
			break;
		case '04': 		// CSV file not moved to TMP dir
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] .
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Can\'t move CSV to tmp dir';
			$warning['message'] = '<p>Due to unknown reason CSV file <strong>';
			$warning['message'] .= $msg['item'].'</strong> can\'t move to TMP dir</p>';
			break;
		case '05': 		// Missing PDF engine
			$warning['button_1'] = '<a href="'. base_url().
						$this->config->item('index_page'). 
						'/common/pdfengine/insert'.
						 '" tabindex="1" class="button">'. 
						$this->data['button_new'].'</a>';

			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Missing PDF Engines';
			$warning['message'] = '<p>Next PDF engines are not in the system.';
			$warning['message'] .= ' Please click on NEW button and add '.
						'those engines for ';
			$warning['message'] .= '<strong>'.$msg['company'].'</strong>';
			$warning['message'] .=	' company (before upload a CSV file):</p><p><ul>';
			$max_rows = count($msg['item']);
			for ($i = 0; $i < $max_rows; $i++){
				$warning['message'] .= '<li><strong>' . $msg['item'][$i] . 
						'</strong></li><br />';
			}
			$warning['message'] .= '</ul></p>';
			break;
		case '06': 		// CSV fields number does not match with Engine fields number
			$warning['button_1'] = '<a href="'. $this->data['path_insert'].
					'" tabindex="1" class="button">'. 
					$this->data['button_see_engine'].'</a>';

			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Wrong fields number';
			$warning['message'] = '<p>CSV next lines have not match with PDF engines fields number:</p><p><ul>';
			
			$max_rows = count($msg['item']);
			for ($i = 0; $i < $max_rows; $i++){
				$warning['message'] .= $msg['item'][$i] ;
			}

			$warning['message'] .= '</ul></p><p>Please click on <strong>See '.
					'Engine</strong> button or try to upload new CSV '.
					'file with <strong>New</strong> button.</p><p><ul>';
			break;
		case '07': 		// CSV fields number does not match with Engine fields number
			$warning['button_1'] = '<a href="'. $this->data['path_insert'].
					'" tabindex="1" class="button">'. 
					$this->data['button_see_engine'].'</a>';

			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Can\'t Create PDF Document';
			$warning['message'] = '<p>It could not create PDF for receipt:</p><p><ul>';
			
			$max_rows = count($msg['item']);
			for ($i = 0; $i < $max_rows; $i++){
				$warning['message'] .= '<li>'.$msg['item'][$i].'</li>' ;
			}

			$warning['message'] .= '</ul></p><p>Please click on <strong>See '.
					'Engine</strong> button or try to upload new CSV '.
					'file with <strong>New</strong> button.</p><p><ul>';
			break;
		case '08': 		// Can't create info.json file
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].
						' :: Could not generate HASH';
			$warning['message'] = '<p>Could not create HASH for PDF files:</p>';
			$warning['message'] .=	'<p><ul>';
			$max_rows = count($msg['item']);
			for ($i = 0; $i < $max_rows; $i++){
				$warning['message'] .= '<li>'.$msg['item'][$i].'</li>' ;
			}
			$warning['message'] .=	'</ul></p>';
			break;
		case '09': 		// Can't rename/move file
			$warning['button_1'] = '<a href="'. $this->data['path_insert'] . 
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';
			$warning['button_2'] = '<a href="' . 
					base_url().$this->config->item('index_page'). 
					'/common/notif'. '" tabindex="1" class="button">'. 
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Can\'t rename/move';
			$warning['message'] = '<p>Could not rename/move file or directory: </p>';
			$warning['message'] .=	'<p><ul><li>' . $msg['item']. '</li></ul></p>';
			break;
		case '10': 		// Receptor directory could not create
			$warning['button_1'] = '<a href="'. $this->data['path_insert'].
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';

			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].' :: Can\'t '.
						'create receptor dirs';
			$warning['message'] = '<p>Some receptors dirs could not '.
						'be created:</p><p><ul>';
			
			$max_rows = count($msg['item']);
			for ($i = 0; $i < $max_rows; $i++){
				$warning['message'] .= '<li><strong>' . $msg['item'][$i] . 
						'</strong></li><br />';
			}

			$warning['message'] .= '</ul></p><p>Please click on NEW button '.
						'and try to upload new CSV file.</p><p><ul>';
			break;
		case '11': 		// There are some errors with Message Templates
			$warning['button_1'] = '<a href="'. $this->data['path_insert'].
					'" tabindex="1" class="button">'. 
					$this->data['button_new'].'</a>';

			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = $msg['company'].
						' :: Error with Message Template';
			$warning['message'] = '<p>Some Message Templates have next errors: </p>'.
						'<p><ul>';
			
			$max_rows = count($msg['item']);
			for ($i = 0; $i < $max_rows; $i++){
				$warning['message'] .= '<li><strong>' . $msg['item'][$i] . 
						'</strong></li><br />';
			}

			$warning['message'] .= '</ul></p><p>Please click on NEW button '.
						'and try to upload new CSV file.</p><p><ul>';
			break;
		default:		// Unknown error
			$warning['button_1'] = ''; // No button to show
			$warning['button_2'] = '<a href="' . base_url(). 
					$this->config->item('index_page'). '/common/notif'. 
					'" tabindex="1" class="button">'.
					$this->data['button_cancel'].'</a>';

			$warning['heading_title'] = 'Unknown error';
			$warning['message'] = 'Sorry. It was not able to detect error type.';
		
		}
		$this->load->view('templates/header',$this->data_header);
		$this->load->view('warning/warning',$warning);
		$this->load->view('templates/footer','');
		exit;
	}

}
