<?php
class Track_Model extends CI_Model{
	private $_subject;
	private $_description;
	private $_from;
	private $_dueDate;
	private $_attachment;
	private $_status;
	private $_referenceNumber;
	private $_dateReceived;
	private $_action;
	private $_due15Days;
	private $_deadline;
	
	private $_id = 0;
	private $_document;
	private $_user;
	private $_received;

	function __construct(){
		parent::__construct();
	}

	public function setId($value){
		$this->_id = $value;
	}
	public function getId(){
		return $this->_id;
	}
	public function setDocument($value){
		$this->_document = $value;
	}
	public function getDocument(){
		return $this->_document;
	}
	public function setUser($value){
		$this->_user = $value;
	}
	public function getUser(){
		return $this->_user;
	}
	public function setReceived($value){
		$this->_received = $value;
	}
	public function getReceived(){
		return $this->_received;
	}
	
	public function setSubject($value){
		$this->_subject = $value;
	}
	public function getSubject(){
		return $this->_subject;
	}
	public function setDescription($value){
		$this->_description = $value;
	}
	public function getDescription(){
		return $this->_description;
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
	public function setDue15Days($value){
		$this->_due15Days = $value;
	}
	public function getDue15Days(){
		return $this->_due15Days;
	}
	public function setDeadline($value){
		$this->_deadline = $value;
	}
	public function getDeadline(){
		return $this->_deadline;
	}
	
	public function commit()
	{
		$data = array(
			'document' => $this->_document,
			'user' => $this->_user,
			'received' => $this->_received,
		);

		if ($this->_id > 0) {
			//We have an ID so we need to update this object because it is not new
			if ($this->db->update("track", $data, array("id" => $this->_id))) {
				return true;
			}
		} else {
			//We dont have an ID meaning it is new and not yet in the database so we need to do an insert
			if ($this->db->insert("track", $data)) {
				//Now we can get the ID and update the newly created object
				$this->_id = $this->db->insert_id();
				return true;
			}
		}
		return false;
	}
}