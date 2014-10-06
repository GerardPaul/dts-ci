<?php

class Chat_Model extends CI_Model {

    private $_id = 0;
    private $_document;
    private $_user;
    private $_message;
    private $_created;
    
    private $_fullname;

    function __construct() {
        parent::__construct();
    }

    public function setId($value) {
        $this->_id = $value;
    }

    public function getId() {
        return $this->_id;
    }
    
    public function setDocument($value) {
        $this->_document = $value;
    }

    public function getDocument() {
        return $this->_document;
    }
    
    public function setUser($value) {
        $this->_user = $value;
    }

    public function getUser() {
        return $this->_user;
    }
    
    public function setMessage($value) {
        $this->_message = $value;
    }

    public function getMessage() {
        return $this->_message;
    }
    
    public function setCreated($value) {
        $this->_created = $value;
    }

    public function getCreated() {
        return $this->_created;
    }
    
    public function setFullname($value) {
        $this->_fullname = $value;
    }

    public function getFullname() {
        return $this->_fullname;
    }
    
    public function commit() {
        $data = array(
            'document' => $this->_document,
            'user' => $this->_user,
            'message' => $this->_message
        );

        if ($this->_id > 0) {
            //We have an ID so we need to update this object because it is not new
            if ($this->db->update("chat", $data, array("id" => $this->_id))) {
                return true;
            }
        } else {
            //We dont have an ID meaning it is new and not yet in the database so we need to do an insert
            if ($this->db->insert("chat", $data)) {
                //Now we can get the ID and update the newly created object
                $this->_id = $this->db->insert_id();
                return true;
            }
        }
        return false;
    }
}
