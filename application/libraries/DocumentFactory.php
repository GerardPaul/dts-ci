<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DocumentFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("document_model");
        $this->_ci->load->model("excel_model");
    }

    public function getStatus($id = 0) {
        $sql = "SELECT status FROM dts_document WHERE id = ?";
        $query = $this->_ci->db->query($sql, array($id));
        if ($query->num_rows() > 0) {
            return $query->row('status');
        }
        return false;
    }

    public function getRefNo($id = 0) {
        $sql = "SELECT referenceNumber FROM dts_document WHERE id = ?";
        $query = $this->_ci->db->query($sql, array($id));
        if ($query->num_rows() > 0) {
            return $query->row('referenceNumber');
        }
        return false;
    }

    public function getLastRefNo() {
        $sql = "SELECT referenceNumber FROM dts_document ORDER BY referenceNumber DESC LIMIT 1";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $refNo = $query->row('referenceNumber');
            $number = explode("-", $refNo);
            $reference = $number[1] + 1;
            $reference = sprintf("%04d", $reference);

            return date('y') . "-$reference";
        }
        return false;
    }
    
    public function getNamesReceived($document){
        $sql = "SELECT CONCAT(u.lastname, ', ', u.firstname) AS 'name' FROM dts_track t, dts_user u WHERE u.id = t.user AND t.document = $document";
        $query = $this->_ci->db->query($sql);
        $names = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $names .= $row->name . "<br>";
            }
        }
        return $names;
    }

    public function getDocument($id = 0) {
        if ($id > 0) {
            $sql = "SELECT * FROM dts_document WHERE id = ?";
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                return $this->createObjectFromData($query->row());
            }
            return false;
        } else {
            $sql = "SELECT * FROM dts_document";
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
        $sql = "SELECT * FROM dts_document";
        if ($get !== 'All') {
            if($get === 'Archived')
                $sql .= " WHERE archive = 1";
            else
                $sql .= " WHERE status = '$get' AND archive = 0";
        }else{
            $sql .= " WHERE archive = 0";
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

    public function getUncompiled($from, $to) {
        $sql = "SELECT d.id, d.subject, d.description, d.dateReceived, d.from, d.dueDate, d.due15Days, d.deadline 
                FROM dts_document d 
                WHERE (d.status = 'On-Going' OR d.status = 'Cancelled') 
                AND (d.dateReceived >= '$from' AND d.dateReceived <= '$to') ORDER BY d.dateReceived DESC";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $documents = array();
            foreach ($query->result() as $row) {
                $id = $row->id;
                $chat = "SELECT c.message, DATE_FORMAT(c.created, '%b %d, %h:%i %p') AS 'created', CONCAT(u.lastname, ',', u.firstname) AS 'fullname'
                            FROM dts_chat c, dts_user u
                            WHERE c.document = '$id' AND c.user = u.id
                            ORDER BY c.created ASC";
                $chat_q = $this->_ci->db->query($chat);
                $chat_message = "";
                if ($query->num_rows() > 0) {
                    foreach($chat_q->result() as $c){
                        $chat_message .= "($c->created) $c->fullname : $c->message\n";
                    }
                }else{
                    $chat_message = "No chat messages.";
                }
                $row->chat = $chat_message;
                $documents[] = $this->createExcelObjectFromData($row);
            }
            return $documents;
        }
        return false;
    }

    public function addDocument($subject, $description, $from, $dueDate, $attachment, $referenceNumber, $dateReceived) {
        $days15 = date('Y-m-d', strtotime($dateReceived . '+ 15 days'));

        $document = new Document_Model();
        $document->setSubject($subject);
        $document->setDescription($description);
        $document->setFrom($from);
        $document->setAttachment($attachment);
        $document->setStatus('On-Going');
        $document->setReferenceNumber($referenceNumber);
        $document->setDateReceived($dateReceived);

        if ($dueDate == '00/00/0000') {
            $document->setDueDate($days15);
        } else {
            $document->setDueDate(date('Y-m-d', strtotime(str_replace('-', '/', $dueDate))));
        }

        $document->setDue15Days($days15);

        return $document->commit();
    }

    public function updateDocument($id, $subject, $status, $description, $from, $dueDate, $attachment, $referenceNumber, $dateReceived) {
        $days15 = date('Y-m-d', strtotime($dateReceived . ' + 15 days'));

        $document = new Document_Model();
        $document->setId($id);
        $document->setSubject($subject);
        $document->setDescription($description);
        $document->setFrom($from);
        $document->setAttachment($attachment);
        $document->setStatus($status);
        $document->setReferenceNumber($referenceNumber);
        $document->setDateReceived($dateReceived);

        if ($dueDate == '00/00/0000') {
            $document->setDueDate($days15);
        } else {
            $document->setDueDate(date('Y-m-d', strtotime(str_replace('-', '/', $dueDate))));
        }

        $document->setDue15Days($days15);

        return $document->commit();
    }

    private function formatDate($date) {
        return date('j-M-Y', strtotime($date));
    }

    public function createExcelObjectFromData($row) {
        $document = new Excel_Model();

        $document->setId($row->id);
        $document->setSubject($row->subject);
        $document->setDescription($row->description);
        $document->setFrom($row->from);
        $document->setDueDate($row->dueDate);
        $document->setDueRD($row->deadline);
        $document->setDue15Days($row->due15Days);
        $document->setDateReceived($row->dateReceived);
        $document->setChat($row->chat);

        return $document;
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
        $document->setAction($row->action);

        return $document;
    }

    public function getAttachments($id){
        $sql = "SELECT * FROM dts_files WHERE document = $id";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $attachments = array();
            foreach ($query->result() as $row) {
                $attachments[] = $row->file_url;
            }
            return $attachments;
        }
        return false;
    }
    
    public function updateAttachment($id, $file_url){
        $sql = "UPDATE dts_document SET attachment = '$file_url' WHERE id = '$id'";
        $query = $this->_ci->db->query($sql);
    }
}
