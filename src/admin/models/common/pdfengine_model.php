<?php


class PDFengine_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function record_count() {
		return $this->db->count_all("engine");
	}

	function fetch_engines( $limit="", $start="" ) {
		$this->db->select('t1.id, t1.name, t1.active, t2.id AS compId, t2.name AS company');	
		$this->db->from('engine AS t1');
		$this->db->join('company AS t2', 't1.companyID = t2.id');
		
		if (! empty($limit) && ! empty($start)) {
			$this->db->limit($limit, $start);
		}
		$this->db->order_by('name');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}

	function get_engine( $id ) {

		$this->db->from('engine');	
		$this->db->where('id', $id);
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}

	function get_company_engines( $id, $only_column='' ) {
		if (! empty($only_column)) {
			$this->db->select($only_column);
		}
		$this->db->from('engine');	
		$this->db->where('companyID', $id);
		$this->db->order_by('name');
		
		$datarows = $this->db->get()->result_array();
		if ( is_array($datarows) && count($datarows) >= 1 ) {
			return $datarows;
		}
		return false;
	}
	function get_engine_by_compID_enginePath($compID, $enginePath){
		$this->db->from('engine');	
		$this->db->where('companyID', $compID);
		$this->db->like('path', $enginePath, 'before');
		
		$datarow = $this->db->get()->result_array();
		if ( is_array($datarow) && count($datarow) == 1 ) {
			return $datarow[0];
		}
		return false;
	}
	function  set_new_engine( $engine ) {
		$data = array (
			'companyID' => $engine['companyID'],
			'name' => $engine['name'],
			'path' => $engine['path'],
			'fields' => $engine['fields'],
			'comment' => $engine['comment'],
			'active' => $engine['active']
		);
		
		$this->db->insert('engine',$data);
	}

	function set_engine( $engine ) {
		$data = array('comment' => $engine['comment']);
		if (! empty($engine['path']) ) {
			$data['path'] = $engine['path'];
			$data['fields'] = $engine['fields'];
		}
		$this->db->where('id', $engine['id']);
		$this->db->update('engine', $data);
 
		return true;
	}

	function delete_engine ($id) {
		$this->db->where('id', $id);
		$this->db->delete('engine');
	}

	function activate_engine( $id ) {

		$data = array ('active' => 1);
		
		$this->db->where('id', $id);
		$this->db->update('engine', $data);
	}

	function desactivate_engine( $id ) {

		$data = array ('active' => 0);
		
		$this->db->where('id', $id);
		$this->db->update('engine', $data);
	}
	
}
