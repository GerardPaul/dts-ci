<?php

class Log_Model extends CI_Model {

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

    public function setTimeOccured($value) {
        $this->_timeOccured = $value;
    }

    public function getTimeOccured() {
        return $this->_timeOccured;
    }

    public function setActionTaken($value) {
        $this->_actionTaken = $value;
    }

    public function getActionTaken() {
        return $this->_actionTaken;
    }

}
