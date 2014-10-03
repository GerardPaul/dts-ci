<?php
class RDTrack_Model extends CI_Model{
	private $_subject;
	private $_from;
	private $_dueDate;
	private $_attachment;
	private $_status;
	private $_referenceNumber;
	private $_dateReceived;
	
	private $_id = 0;
	private $_document;
	private $_action;
	private $_markReceived;
	private $_notes;
	
	private $_ardDateReceived;
	private $_empDateReceived;
	private $_ard;
	private $_emp;
	
	private $_ardName;
	private $_empName;
	private $_division;
	
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
	public function setAction($value){
		$this->_action = $value;
	}
	public function getAction(){
		return $this->_action;
	}
	public function setMarkReceived($value){
		$this->_markReceived = $value;
	}
	public function getMarkReceived(){
		return $this->_markReceived;
	}
	public function setNotes($value){
		$this->_notes = $value;
	}
	public function getNotes(){
		return $this->_notes;
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
	
	public function setEmpDateReceived($value){
		$this->_empDateReceived = $value;
	}
	public function getEmpDateReceived(){
		return $this->_empDateReceived;
	}
	public function setArdDateReceived($value){
		$this->_ardDateReceived = $value;
	}
	public function getArdDateReceived(){
		return $this->_ardDateReceived;
	}
	public function setEmp($value){
		$this->_emp = $value;
	}
	public function getEmp(){
		return $this->_emp;
	}
	public function setArd($value){
		$this->_ard = $value;
	}
	public function getArd(){
		return $this->_ard;
	}
	public function setArdName($value){
		$this->_ardName = $value;
	}
	public function getArdName(){
		return $this->_ardName;
	}
	public function setEmpName($value){
		$this->_empName = $value;
	}
	public function getEmpName(){
		return $this->_empName;
	}
	public function setDivision($value){
		$this->_division = $value;
	}
	public function getDivision(){
		return $this->_division;
	}
	
	public function commit()
	{
		$data = array(
			'document' => $this->_document,
			'action' => $this->_action,
			'dateReceived' => $this->_markReceived,
			'notes' => $this->_notes,
		);

		if ($this->_id > 0) {
			//We have an ID so we need to update this object because it is not new
			if ($this->db->update("rdtrack", $data, array("id" => $this->_id))) {
				return true;
			}
		} else {
			//We dont have an ID meaning it is new and not yet in the database so we need to do an insert
			if ($this->db->insert("rdtrack", $data)) {
				//Now we can get the ID and update the newly created object
				$this->_id = $this->db->insert_id();
				return true;
			}
		}
		return false;
	}
}