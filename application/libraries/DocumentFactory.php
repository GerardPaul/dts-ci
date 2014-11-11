<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DocumentFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("document_model");
    }

    public function getStatus($id = 0) {
        $sql = "SELECT status FROM document WHERE id = ?";
        $query = $this->_ci->db->query($sql, array($id));
        if ($query->num_rows() > 0) {
            return $query->row('status');
        }
        return false;
    }

    public function getDocument($id = 0) {
        if ($id > 0) {
            $sql = "SELECT * FROM document WHERE id = ?";
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                return $this->createObjectFromData($query->row());
            }
            return false;
        } else {
            $sql = "SELECT * FROM document";
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

    public function ajaxGetDocument($get) {
        $sql = "SELECT * FROM document";
        if ($get !== 'All') {
            $sql .= " WHERE status = '$get'";
        }
		$sql .= " ORDER BY id ASC";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $documents = array();
            foreach ($query->result() as $row) {
                $documents[] = $row;
            }
            return $documents;
        }
        return false;
    }

    public function addDocument($subject, $description, $from, $dueDate, $attachment, $referenceNumber, $dateReceived) {
        $document = new Document_Model();
        $document->setSubject($subject);
        $document->setDescription($description);
        $document->setFrom($from);
        $document->setDueDate($dueDate);
        $document->setAttachment($attachment);
        //$document->setStatus('On-Going');
        $document->setReferenceNumber($referenceNumber);
        $document->setDateReceived($dateReceived);

        $document->setDue15Days(date('Y-m-d', strtotime($dateReceived . ' + 15 days')));

        return $document->commit();
    }

    public function updateDocument($id, $subject, $status, $description, $from, $dueDate, $attachment, $referenceNumber, $dateReceived) {
        $document = new Document_Model();
        $document->setId($id);
        $document->setSubject($subject);
        $document->setDescription($description);
        $document->setFrom($from);
        $document->setDueDate($dueDate);
        $document->setAttachment($attachment);
        $document->setStatus($status);
        $document->setReferenceNumber($referenceNumber);
        $document->setDateReceived($dateReceived);

        $document->setDue15Days(date('Y-m-d', strtotime($dateReceived . ' + 15 days')));

        return $document->commit();
    }

    private function formatDate($date) {
        return date('j-M-Y', strtotime($date));
    }

    public function createObjectFromData($row) {
        $document = new Document_Model();

        $document->setId($row->id);
        $document->setSubject($row->subject);
        $document->setDescription($row->description);
        $document->setFrom($row->from);
        $document->setAttachment($row->attachment);
        $document->setStatus($row->status);
        $document->setReferenceNumber($row->referenceNumber);
        $document->setDueDate($row->dueDate);
        $document->setDeadline($row->deadline);
        $document->setDue15Days($row->due15Days);
        $document->setDateReceived($row->dateReceived);

        return $document;
    }

}

?>