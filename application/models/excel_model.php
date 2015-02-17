<?php

class Excel_Model extends CI_Model {

    private $_id = 0;
    private $_subject;
    private $_description;
    private $_dateReceived;
    private $_dueDate;
    private $_due15Days;
    private $_dueRD;
    private $_from;    
    private $_chat;
    
    function __construct() {
        parent::__construct();
    }

    public function setId($value) {
        $this->_id = $value;
    }

    public function getId() {
        return $this->_id;
    }

    public function setSubject($value) {
        $this->_subject = $value;
    }

    public function getSubject() {
        return $this->_subject;
    }
    
    public function setDescription($value) {
        $this->_description = $value;
    }

    public function getDescription() {
        return $this->_description;
    }
    
    public function setDateReceived($value) {
        $this->_dateReceived = $value;
    }

    public function getDateReceived() {
        return $this->_dateReceived;
    }
    
    public function setDueDate($value) {
        $this->_dueDate = $value;
    }

    public function getDueDate() {
        return $this->_dueDate;
    }
    
    public function setDue15Days($value) {
        $this->_due15Days = $value;
    }

    public function getDue15Days() {
        return $this->_due15Days;
    }
    
    public function setDueRD($value) {
        $this->_dueRD = $value;
    }

    public function getDueRD() {
        return $this->_dueRD;
    }
    
    public function setFrom($value) {
        $this->_from = $value;
    }

    public function getFrom() {
        return $this->_from;
    }

    public function setChat($value) {
        $this->_chat = $value;
    }

    public function getChat() {
        return $this->_chat;
    }
}
