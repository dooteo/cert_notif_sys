<?php
include ("menu.php");
class PDFengine extends CI_Controller {

	var $data_header = array();
	var $data = array();
	var $base_path = '';
	
	public function __construct() {
		parent::__construct();
		
		if (!$this->session->userdata('isLoggedIn')) {
			redirect('/login/show_login');
		}
		$this->load->model('common/pdfengine_model');
		$this->load->model('common/company_model');
		$this->load->model('common/user_model');
		$this->load->config('unimail');
		$this->load->config('pagination');
		$this->load->library("pagination");
		$this->load->library("Zdn_pdftk");
		
		$this->data_header = get_menu();
		$user_id = $this->session->userdata('id');
		$this->data['user'] = $this->user_model->get_user($user_id);
		
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
		
		$this->data['button_cancel'] = 'Cancel';
		$this->data['button_delete'] = 'Delete';
		$this->data['button_edit'] = 'Edit';
		$this->data['button_new'] = 'New';
		$this->data['button_save'] = 'Save';
		$this->data['button_csv'] = 'CSV';
		
		$this->data['column_action'] = 'Action';
		$this->data['column_name'] = 'Name';
		$this->data['column_company'] = 'Company';
		$this->data['column_status'] = 'Status';
		$this->data['column_action'] = 'Action';
		$this->data['column_username'] = 'Username';
		
		$this->data['text_active'] = 'Active';
		$this->data['text_activate'] = 'Activate';
		$this->data['text_desactivate'] = 'Desactivate';
		$this->data['text_edit'] = 'Edit';
		$this->data['text_inactive'] = 'Inactive';
		$this->data['text_new'] = 'New';
		$this->data['text_no_results'] = 'There is no results';
		$this->data['text_view'] = 'View';
		$this->data['text_add_new_field'] = 'Add new field';
		$this->data['text_del_field'] = 'Delete last field';

		$this->data['text_company'] = 'Company';
		$this->data['text_filename'] = 'Filename';
		$this->data['text_old_filename'] = 'Old Filename';
		$this->data['text_new_filename'] = 'New Filename';
		$this->data['text_comment'] = 'Comment';
		$this->data['text_pdfengine'] = 'PDF engine';
		$this->data['text_pdfengines_list'] = 'PDF engines list';
		$this->data['text_new_pdfengine'] = 'New PDF engine';
		$this->data['text_name'] = 'Name';
		$this->data['text_fields'] = 'PDF Fields';
		$this->data['text_preg_type'] = 'Type: &nbsp;&nbsp;';
		$this->data['text_preg_name'] = '&emsp;Name: &nbsp;&nbsp;';
		
	}

	function index() {	// Shows a list of PDF engines
		$this->data['heading_title'] = $this->data['text_pdfengines_list'];
		
		// Calc for pagination
		$config['base_url'] =  $this->base_path;
		$config['total_rows'] = $this->pdfengine_model->record_count();
		$limit = $this->config->item('per_page');

		// (...)/common/pdfengine/index/2  numer 2 is at 4th segment;
		$page = $this->uri->segment(4);
	        $this->data['pdfengines'] = $this->pdfengine_model->fetch_engines(
	        	$this->data['user']['companyID'], $limit, $page);

		$this->pagination->initialize($config);
		$this->data["pagination"] = $this->pagination->create_links();

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('pdfengine/pdfengine_list',$this->data);
		$this->load->view('templates/footer',$this->data);
	}
	
	function insert($msg_type = ""){
		if ($this->data['user']['isCompAdmin'] != 1) {
			redirect($this->data['path_base']);
		}
		
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', $this->data['text_name'], 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			$this->data['heading_title'] = '<b>'. $this->data['text_new_pdfengine']. '</b>';
		
			$company = $this->company_model->get_company($this->data['user']['companyID']);
			if ($company === false) {
				// 'ncd' == no company defined
				redirect($this->data['path_insert_company']. 'ncd');
				return true;
			}
		
			// Create a companies select options
			$this->data['company'] = $company['name'];
		
			if (! empty($msg_type) ) {

				foreach ($this->msg_handler($msg_type) as $key => $value){
					$this->data[$key] = $value;
				}
			} 

			$this->data_header['breadcrumbs'][] = array(
				'text' => '<b>'. $this->data['text_new']. '</b>', 
				'href'=> '', 'separator' => ' :: ');
			$this->load->view('templates/header',$this->data_header);
			$this->load->view('pdfengine/pdfengine_new',$this->data);
			$this->load->view('templates/footer',$this->data);
		} else {
			// returns all POST items with XSS filter
			$posted = $this->input->post(NULL, TRUE); 
			$posted['companyID'] = $this->data['user']['companyID'];
			$pdf_dir = "/var/unimail_engines/". $company['dirpath'] ;
			$pdf_dir .=	"/". $posted['name'] . "/";
			$pdf_file =  $pdf_dir . preg_replace("![^a-z0-9]+!i", "_", 
					$_FILES['upfile']['name']);
			
			if (is_dir($pdf_dir)) {
				// 'ede' == engine directory exists
				redirect($this->data['path_insert']. 'ede');
			} else {
				mkdir ($pdf_dir, 0755);
			}
			if (! is_writable($pdf_dir)) {
				// 'ednw' = engine directory not writable
				redirect($this->data['path_insert']. 'ednw');
			}
			if (! move_uploaded_file ($_FILES['upfile']['tmp_name'], $pdf_file) ){
				// 'emuf' = error move uploaded file
				redirect($this->data['path_insert']. 'emuf');
			}

			// Now, we got to get fillables fields from PDF
			$tmp_dir = "/var/unimail_engines/_tmp";
			if (! is_dir($tmp_dir)) {
				mkdir ($tmp_dir, 0755);
			}

			$txt_file = $pdf_dir . "fields.txt";
			
			// dump_data_fields returns "" when sucessful
			$pdfengine = new Zdn_pdftk();
			if ($pdfengine->dump_data_fields($pdf_file, $txt_file) !== "") {
				unlink ($pdf_file);
				unlink ($txt_file);
				// remove engine dir
				rmdir ($pdf_dir);
				// 'eddf' = error to dump data field
				redirect($this->data['path_insert']. 'eddf');
			}
			$posted['fields'] = $pdfengine->parse_ddf_file($txt_file);
			if (empty($posted['fields'])) {
				// 'wef' = warning empty fields
				redirect($this->data['path_insert']. 'wef');
			}
			
			$posted['path'] = $pdf_file;
			if ( ! empty($posted['active']) ) {
				$posted['active'] = 1;
			} else {
				$posted['active'] = 0;
			}
			$this->pdfengine_model->set_new_engine($posted);
			redirect($this->base_path);
		}
	}

	function delete () {
		if ( $this->data['user']['isCompAdmin'] != 1 )  {
			redirect($this->data['path_base']);
		}

		$posted = $this->input->post(NULL, TRUE); 
		// returns all POST items with XSS filter
		if (empty($posted)) {
			// Nothing to delete
			redirect($this->data['path_list']);
		}

		$admin_company_id = $this->data['user']['companyID'];
		$items = count ($posted['selected']);
		for ($i = 0; $i < $items; $i++) {
			// delete files and dir
			$engine = $this->pdfengine_model->get_engine($posted['selected'][$i]);
			if ($admin_company_id == $engine['companyID']) {
				$filename = basename($engine['path']);
				$dirname = dirname($engine['path']);
				// Remove PDF and DDF files
				unlink ($engine['path']);
				unlink ($dirname . "/fields.txt");
				// remove engine dir
				rmdir ($dirname);
				$this->pdfengine_model->delete_engine($posted['selected'][$i]);
			}
		}

		redirect($this->base_path);
	}

	function update (){	// Edit/Update Engine data
		if ( $this->data['user']['isCompAdmin'] != 1 )  {
			redirect($this->data['path_list']);
		}
		$posted = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		
		if (empty($_FILES['upfile']['name']) && empty($posted)) {
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

			if (! empty($_FILES['upfile']['name']) ){
				// Remove old files
				unlink ($engine['path']);
				unlink ($pdf_dir . "/fields.txt");
				// Create new ones				
				$new_pdf =  $pdf_dir . "/" . preg_replace("![^a-z0-9]+!i", "_", 
					$_FILES['upfile']['name']);
				$upd_data['path'] = $new_pdf;
				
				if (! move_uploaded_file ($_FILES['upfile']['tmp_name'], $new_pdf) ){
					// 'emuf' = error move uploaded file
					redirect($this->data['path_update']. 'emuf');
				}

				// Now, we got to get fillables fields from PDF
				$tmp_dir = "/var/unimail_engines/_tmp";
				if (! is_dir($tmp_dir)) {
					mkdir ($tmp_dir, 0755);
				}

				$txt_file = $pdf_dir . "/fields.txt";
			
				// dump_data_fields returns "" when sucessful
				$pdftk = new Zdn_pdftk();
				if ($pdftk->dump_data_fields($new_pdf, $txt_file) !== "") {
					unlink ($new_pdf);
					unlink ($txt_file);
					// remove engine dir
					rmdir ($pdf_dir);
					$this->pdfengine_model->delete_engine($posted['id']);
					// 'eddf' = error to dump data field
					redirect($this->data['path_update']. 'eddf');
				}

				$upd_data['fields'] = $pdftk->parse_ddf_file($txt_file);
/*				if (empty($upd_data['fields'])) {
					// PDF is not fillable
					redirect($this->data['path_update']. 'wef');
				}
*/			} else {
				$upd_data['path'] = "";
				$upd_data['fields'] = "";
			}
			$upd_data['id'] = $engine_id;
			$upd_data['comment'] = $posted['comment'];
			
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
		}
		
		$this->data_header['breadcrumbs'][] = array(
			'text' => '<b>'. $this->data['text_view']. '</b>', 
			'href'=> '', 'separator' => ' :: ');

		$this->load->view('templates/header',$this->data_header);
		$this->load->view('pdfengine/pdfengine_view',$this->data);
		$this->load->view('templates/footer',$this->data);
	}

	// Generates a CSV from DB and send it to user
	function csv (){
		$engine_id = $this->uri->segment(4);
		$engine = $this->pdfengine_model->get_engine($engine_id);

		// engine must belong to user's company
		if ($engine['companyID']!=$this->data['user']['companyID']) {
			redirect($this->base_path);
		}

		$company = $this->company_model->get_company($engine['companyID']);
		
		date_default_timezone_set('Europe/Madrid');
		$filename = preg_replace("![^a-z0-9]+!i", "_", $company['name']) . 
			"_" . $engine['name'] . 
			"_" . date('YmdHis');
		
		
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=". $filename. ".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$data = array();
		$data_type = array();
		$data_name = array();
		
		$i = 0;
		$tok = strtok($engine['fields'], "<>");
		while ($tok !== false) {
			$data[$i] = explode("||", $tok);
			$data_type[$i] = '"' . $data[$i][0] .'"';
			$data_name[$i] = '"' . $data[$i][1] . '"';
			$tok = strtok ("<>");
			$i++;
		}
		
		$output = fopen("php://output", "w");
		fputcsv($output, $data_type, "\t");
		fputcsv($output, $data_name, "\t");
		fclose($output);
	}

	function activate(){	// Updates engine status as activate
		if ($this->data['user']['isCompAdmin'] != 1) {
			redirect($this->data['path_base']);
		}
		$engine_id = $this->uri->segment(4);
		$this->pdfengine_model->activate_engine($engine_id);

		redirect($this->base_path);
	}

	function desactivate(){	// Updates engine status as inactive
		if ($this->data['user']['isCompAdmin'] != 1) {
			redirect($this->data['path_base']);
		}
		$engine_id = $this->uri->segment(4);
		$this->pdfengine_model->desactivate_engine($engine_id);

		redirect($this->base_path);
	}
	function msg_handler ($msg){
		switch ($msg) {
		case "ede":
			$warning = array('error_warning'=>'Engine\'s directory exists. You must use another name.');
			break;
		case "ednw":
			$warning = array('error_warning'=>'Engine\'s directory is not writable.');
			break;
		case "emuf":
			$warning = array('error_warning'=>'Could not move uploaded temporary file to it\'s target location.');
			break;
		case "eddf":
			$warning = array('error_warning'=>'Could not dump data fields from PDF file.');
			break;
		case "wef":
			$warning = array('error_warning'=>'Warning! That PDF had no fillable fields. Anyway, Engine created.');
			break;
		case "ncd":
			$warning = array('error_warning'=>'No company defined.');
			break;
		default:
			$warning = "";
		}
		return $warning;
	}
}
