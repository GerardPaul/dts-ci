<?php

class Notification_model extends CI_Model {

    private $_id = 0;
    private $_creator;
    private $_receiver;
    private $_object;
    private $_status;
    private $_dateCreated;
    private $_type;

    function __construct() {
        parent::__construct();
    }

    public function setId($value) {
        $this->_id = $value;
    }

    public function getId() {
        return $this->_id;
    }
    
    public function setCreator($value) {
        $this->_creator = $value;
    }
    
    public function getCreator() {
        return $this->_creator;
    }

    public function getReceiver() {
        return $this->_receiver;
    }
    
    public function setReceiver($value) {
        $this->_receiver = $value;
    }
    
    public function setObject($value) {
        $this->_object = $value;
    }

    public function getObject() {
        return $this->_object;
    }
    
    public function setType($value) {
        $this->_type = $value;
    }

    public function getType() {
        return $this->_type;
    }
    
    public function setStatus($value) {
        $this->_status = $value;
    }

    public function getStatus() {
        return $this->_status;
    }
    
    public function setDateCreated($value) {
        $this->_dateCreated = $value;
    }

    public function getDateCreated() {
        return $this->_dateCreated;
    }
    
    public function commit() {
        $data = array(
            'creator' => $this->_creator,
            'receiver' => $this->_receiver,
            'object' => $this->_object,
            'type' => $this->_type,
            'dateCreated' => $this->_dateCreated
        );

        if ($this->_id > 0) {
            //We have an ID so we need to update this object because it is not new
            if ($this->db->update("dts_notification", $data, array("id" => $this->_id))) {
                return true;
            }
        } else {
            //We dont have an ID meaning it is new and not yet in the database so we need to do an insert
            if ($this->db->insert("dts_notification", $data)) {
                //Now we can get the ID and update the newly created object
                $this->_id = $this->db->insert_id();
                return true;
            }
        }
        return false;
    }

}