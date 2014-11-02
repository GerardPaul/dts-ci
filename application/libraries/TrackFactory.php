<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class TrackFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("track_model");
    }

    public function getUserDocument($track) {
        $sql = "SELECT d.subject, d.description, d.action, d.from, d.dueDate, d.due15Days, d.deadline,
                        d.attachment, d.status, d.referenceNumber, d.dateReceived,
                        t.id, t.document, t.user, t.received
                FROM document d, track t 
                WHERE t.id = '$track' AND t.document = d.id";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            return $this->createObjectFromData($query->row());
        }
        return false;
    }
    
    public function ajaxGetDocument($get, $user) {
        $sql = "SELECT t.id, d.subject, d.from, d.dueDate, d.due15Days, d.deadline, d.dateReceived
                FROM document d, track t 
                WHERE t.document = d.id AND t.user = '$user'";
        if ($get !== 'All') {
            $sql .= " AND status = '$get'";
        }
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

    public function createObjectFromData($row) {
        $track = new Track_Model();

        $track->setSubject($row->subject);
        $track->setDescription($row->description);
        $track->setFrom($row->from);
        $track->setAttachment($row->attachment);
        $track->setStatus($row->status);
        $track->setReferenceNumber($row->referenceNumber);
        $track->setDueDate($row->dueDate);
        $track->setDeadline($row->deadline);
        $track->setDue15Days($row->due15Days);
        $track->setDateReceived($row->dateReceived);
        $track->setAction($row->action);

        $track->setId($row->id);
        $track->setDocument($row->document);
        $track->setUser($row->user);
        $track->setReceived($row->received);

        return $track;
    }
}
