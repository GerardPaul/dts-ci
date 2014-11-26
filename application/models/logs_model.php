<?php

class Logs_model extends CI_Model {

    private $_id = 0;
    private $_actionTaken;
    private $_timeOccured;

    function __construct() {
        parent::__construct();
    }

    public function setId($value) {
        $this->_id = $value;
    }

    public function getId() {
        return $this->_id;
    }

    public function getActionTaken() {
        return $this->_actionTaken;
    }

    public function setActionTaken($value) {
        $this->_actionTaken = $value;
    }

    public function getTimeOccured() {
        return $this->_timeOccured;
    }

    public function setTimeOccured($value) {
        $this->_timeOccured = $value;
    }

    public function commit() {
        $data = array(
            'actionTaken' => $this->_actionTaken
        );

        if ($this->_id > 0) {
            //We have an ID so we need to update this object because it is not new
            if ($this->db->update("dts_logs", $data, array("id" => $this->_id))) {
                return true;
            }
        } else {
            //We dont have an ID meaning it is new and not yet in the database so we need to do an insert
            if ($this->db->insert("dts_logs", $data)) {
                //Now we can get the ID and update the newly created object
                $this->_id = $this->db->insert_id();
                return true;
            }
        }
        return false;
    }

}

?>