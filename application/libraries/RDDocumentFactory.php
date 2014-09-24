<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class DocumentFactory {

	private $_ci;

 	function __construct(){
    	//When the class is constructed get an instance of codeigniter so we can access it locally
    	$this->_ci =& get_instance();
    	//Include the user_model so we can use it
    	$this->_ci->load->model("rdtrack_model");
    }
	
	public function getRDDocument($id = 0) {
    	if ($id > 0) {
			$sql = 'SELECT r.id AS "rdID", r.action, r.dateReceived AS "markReceived", r.notes,
						d.subject, d.id AS "docID", d.from, d.dueDate, d.attachment, d.status, 
						d.referenceNumber, d.dateReceived
					FROM document d, rdtrack r
					WHERE r.document = d.id AND d.id = ?';
    		$query = $this->_ci->db->query($sql, array($id));
    		if ($query->num_rows() > 0) {
    			return $this->createObjectFromData($query->row());
    		}
    		return false;
    	} else {
    		$sql = 'SELECT r.id AS "rdID", r.action, r.dateReceived AS "markReceived", r.notes,
						d.subject, d.id AS "docID", d.from, d.dueDate, d.attachment, d.status, 
						d.referenceNumber, d.dateReceived
					FROM document d, rdtrack r
					WHERE r.document = d.id';
    		$query = $this->_ci->db->query($sql);
    		if ($query->num_rows() > 0) {
    			$users = array();
    			foreach ($query->result() as $row) {
					$users[] = $this->createObjectFromData($row);
    			}
    			return $users;
    		}
    		return false;
    	}
    }
	
	private function formatDate($date){
		if($date == '')
			return '';
	
		return date('M j, Y', strtotime($date));
	}
	
    public function createObjectFromData($row) {
    	$document = new RDTrack_Model();
		
		$document->setSubject($row->subject);
		$document->setFrom($row->from);
		$document->setDueDate($this->formatDate($row->dueDate));
		$document->setAttachment($row->attachment);
		$document->setStatus($row->status);
		$document->setReferenceNumber($row->referenceNumber);
		$document->setDateReceived($this->formatDate($row->dateReceived));
		
		$document->setId($row->rdID);
		$document->setDocument($row->docID);
		$document->setAction($row->action);
		$document->setMarkReceived($this->formatDate($row->markReceived));
		$document->setNotes($row->notes);
		
    	return $document;
    }
}