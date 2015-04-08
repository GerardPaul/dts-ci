<?php

class File_Model extends CI_Model {

    private $_id = 0;
    private $_document;
    private $_file_url;

    function __construct() {
        parent::__construct();
    }

    public function setId($value) {
        $this->_id = $value;
    }

    public function getId() {
        return $this->_id;
    }

    public function getDocument() {
        return $this->_document;
    }

    public function setDocument($value) {
        $this->_document = $value;
    }

    public function setFileUrl($value) {
        $this->_file_url = $value;
    }

    public function getFileUrl() {
        return $this->_file_url;
    }

    public function commit() {
        $data = array(
            'document' => $this->_document,
            'file_url' => $this->_file_url
        );

        if ($this->_id > 0) {
            //We have an ID so we need to update this object because it is not new
            if ($this->db->update("dts_files", $data, array("id" => $this->_id))) {
                return true;
            }
        } else {
            //We dont have an ID meaning it is new and not yet in the database so we need to do an insert
            if ($this->db->insert("dts_files", $data)) {
                //Now we can get the ID and update the newly created object
                $this->_id = $this->db->insert_id();
                return true;
            }
        }
      
    }
}
