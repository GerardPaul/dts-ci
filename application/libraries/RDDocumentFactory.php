<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RDDocumentFactory {

    private $_ci;

    function __construct() {
        //When the class is constructed get an instance of codeigniter so we can access it locally
        $this->_ci = & get_instance();
        //Include the user_model so we can use it
        $this->_ci->load->model("rdtrack_model");
    }
	
	public function getEMPDocument($id = 0){
        if ($id > 0) {
            $sql = 'SELECT r.id AS "rdID", r.action, r.dateReceived AS "markReceived", r.notes,
                        d.subject, d.id AS "docID", d.from, d.dueDate, d.attachment, d.status, 
                        d.referenceNumber, d.dateReceived, r.ard, r.emp, r.ardDateReceived, r.empDateReceived,
                        (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.ard) AS "ardName",
                         (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.emp) AS "empName",
                         (SELECT v.name
                         FROM user u, division v
                         WHERE u.id = r.ard AND u.division = v.id) AS "division"
                    FROM document d, rdtrack r
                    WHERE r.document = d.id AND r.emp = ?';
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                $documents = array();
                foreach ($query->result() as $row) {
                    $documents[] = $this->createObjectFromData($row);
                }
                return $documents;
            }
            return false;
        }else{
            return false;
        }
    }
	
    public function getARDDocument($id = 0){
        if ($id > 0) {
            $sql = 'SELECT r.id AS "rdID", r.action, r.dateReceived AS "markReceived", r.notes,
                        d.subject, d.id AS "docID", d.from, d.dueDate, d.attachment, d.status, 
                        d.referenceNumber, d.dateReceived, r.ard, r.emp, r.ardDateReceived, r.empDateReceived,
                        (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.ard) AS "ardName",
                         (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.emp) AS "empName",
                         (SELECT v.name
                         FROM user u, division v
                         WHERE u.id = r.ard AND u.division = v.id) AS "division"
                    FROM document d, rdtrack r
                    WHERE r.document = d.id AND r.ard = ?';
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                $documents = array();
                foreach ($query->result() as $row) {
                    $documents[] = $this->createObjectFromData($row);
                }
                return $documents;
            }
            return false;
        }else{
            return false;
        }
    }
    
    public function getRDDocument($id = 0) {
        if ($id > 0) {
            $sql = 'SELECT r.id AS "rdID", r.action, r.dateReceived AS "markReceived", r.notes,
                        d.subject, d.id AS "docID", d.from, d.dueDate, d.attachment, d.status, 
                        d.referenceNumber, d.dateReceived, r.ard, r.emp, r.ardDateReceived, r.empDateReceived,
                        (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.ard) AS "ardName",
                         (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.emp) AS "empName",
                         (SELECT v.name
                         FROM user u, division v
                         WHERE u.id = r.ard AND u.division = v.id) AS "division"
                    FROM document d, rdtrack r
                    WHERE r.document = d.id AND d.id = ?';
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                return $this->createObjectFromData($query->row());
            }
            return false;
        } else {
            $sql = 'SELECT r.id AS "rdID", r.action, r.dateReceived AS "markReceived", r.notes,
                        d.subject, d.id AS "docID", d.from, d.dueDate, d.attachment, d.status, 
                        d.referenceNumber, d.dateReceived, r.ard, r.emp, r.ardDateReceived, r.empDateReceived,
                        (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.ard) AS "ardName",
                         (SELECT CONCAT(u.lastname, ",", u.firstname) 
                         FROM user u
                         WHERE u.id = r.emp) AS "empName",
                         (SELECT v.name
                         FROM user u, division v
                         WHERE u.id = r.ard AND u.division = v.id) AS "division"
                    FROM document d, rdtrack r
                    WHERE r.document = d.id';
            $query = $this->_ci->db->query($sql);
            if ($query->num_rows() > 0) {
                $documents = array();
                foreach ($query->result() as $row) {
                    $documents[] = $this->createObjectFromData($row);
                }
                return $documents;
            }
            return false;
        }
    }

    private function formatDate($date) {
        if ($date == '0')
            return '0';

        return date('M j, Y', strtotime($date));
    }

    public function createObjectFromData($row) {
        $document = new RDTrack_Model();

        $document->setSubject($row->subject);
        $document->setFrom($row->from);
        $document->setDueDate($this->formatDate($row->dueDate));
        $document->setAttachment($row->attachment);
        $document->setStatus($row->status);
        $document->setReferenceNumber($row->referenceNumber);
        $document->setDateReceived($this->formatDate($row->dateReceived));

        $document->setId($row->rdID);
        $document->setDocument($row->docID);
        $document->setAction($row->action);
        $document->setMarkReceived($this->formatDate($row->markReceived));
        $document->setNotes($row->notes);
        $document->setArd($row->ard);
        $document->setEmp($row->emp);
        $document->setArdDateReceived($this->formatDate($row->ardDateReceived));
        $document->setEmpDateReceived($this->formatDate($row->empDateReceived));

        $document->setArdName($row->ardName);
        $document->setEmpName($row->empName);
        $document->setDivision($row->division);

        return $document;
    }

    public function updateReceived($id) {
        $date = date('m/d/Y');
        $sql = "UPDATE rdtrack SET dateReceived = '$date' WHERE id = ?";
        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }
	
	public function updateArdReceived($id) {
        $date = date('m/d/Y');
        $sql = "UPDATE rdtrack SET ardDateReceived = '$date' WHERE id = ?";
        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }
	
	public function updateEmpReceived($id) {
        $date = date('m/d/Y');
        $sql = "UPDATE rdtrack SET empDateReceived = '$date' WHERE id = ?";
        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }
	
	public function updateStatus($id,$status) {
        $sql = "UPDATE document SET status = '$status' WHERE id = ?";
        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }

    public function forwardTo($id, $ardId, $empId, $action, $notes) {
        $sql = "UPDATE rdtrack SET ard = '$ardId', emp = '$empId', notes = '$notes', action = '$action' WHERE id = ?";
        if ($empId == '')
            $sql = "UPDATE rdtrack SET ard = '$ardId', notes = '$notes', action = '$action' WHERE id = ?";

        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }
	
	public function forwardToEmp($id, $empId) {
        $sql = "UPDATE rdtrack SET emp = '$empId' WHERE id = ?";
        
        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }

}
