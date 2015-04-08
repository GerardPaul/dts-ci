<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FileFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("file_model");
    }
    
    public function addFile($document, $file_url){
        $file = new File_Model();
        $file->setDocument($document);
        $file->setFileUrl($file_url);
        
        return $file->commit();
    }
    
    public function getFiles($document){
        $sql = "SELECT * FROM dts_files WHERE document = '?'";
        $query = $this->_ci->db->query($sql, array($document));
        if ($query->num_rows() > 0) {
            $file = array();
            foreach ($query->result() as $row) {
                    $file[] = $this->createObjectFromData($row);
                }
                return $file;
        }
        return false;
    }
    
    public function createObjectFromData($row) {
        $file = new File_Model();

        $file->setId($row->id);
        $file->setDocument($row->document);
        $file->setFileUrl($row->file_url);

        return $file;
    }
}