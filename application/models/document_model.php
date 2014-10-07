<?php
class Document_Model extends CI_Model{
	private $_id = 0;
	private $_subject;
	private $_from;
	private $_dueDate;
	private $_attachment;
	private $_status;
	private $_referenceNumber;
	private $_dateReceived;

	function __construct(){
		parent::__construct();
	}

	public function setId($value){
		$this->_id = $value;
	}
	public function getId(){
		return $this->_id;
	}
	public function setSubject($value){
		$this->_subject = $value;
	}
	public function getSubject(){
		return $this->_subject;
	}
	public function setFrom($value){
		$this->_from = $value;
	}
	public function getFrom(){
		return $this->_from;
	}
	public function setDueDate($value){
		$this->_dueDate = $value;
	}
	public function getDueDate(){
		return $this->_dueDate;
	}
	public function setAttachment($value){
		$this->_attachment = $value;
	}
	public function getAttachment(){
		return $this->_attachment;
	}
	public function setStatus($value){
		$this->_status = $value;
	}
	public function getStatus(){
		return $this->_status;
	}
	public function setReferenceNumber($value){
		$this->_referenceNumber = $value;
	}
	public function getReferenceNumber(){
		return $this->_referenceNumber;
	}
	public function setDateReceived($value){
		$this->_dateReceived = $value;
	}
	public function getDateReceived(){
		return $this->_dateReceived;
	}
	/*
	* Class Methods
	*/

	/**
	*	Commit method, this will commit the entire object to the database
	*/
	public function commit()
	{
		$data = array(
			'subject' => $this->_subject,
			'from' => $this->_from,
			'dueDate' => $this->_dueDate,
			'attachment' => $this->_attachment,
			'status' => $this->_status,
			'referenceNumber' => $this->_referenceNumber,
			'dateReceived' => $this->_dateReceived,
		);

		if ($this->_id > 0) {
			//We have an ID so we need to update this object because it is not new
			if ($this->db->update("document", $data, array("id" => $this->_id))) {
				return true;
			}
		} else {
			//We dont have an ID meaning it is new and not yet in the database so we need to do an insert
			if ($this->db->insert("document", $data)) {
				//Now we can get the ID and update the newly created object
				$this->_id = $this->db->insert_id();
				$this->db->insert("rdtrack", array('document' => $this->_id));
				return true;
			}
		}
		return false;
	}
}
?>