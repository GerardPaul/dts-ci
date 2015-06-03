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
        $sql = "SELECT d.archive, d.subject, d.description, d.action, d.from, d.dueDate, d.due15Days, d.deadline,
                        d.attachment, d.status, d.referenceNumber, d.dateReceived,
                        t.id, t.document, t.user, t.received
                FROM dts_document d, dts_track t 
                WHERE t.id = '$track' AND t.document = d.id";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            return $this->createObjectFromData($query->row());
        }
        return false;
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE dts_document SET status = '$status' WHERE id = ?";
        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }

    public function ajaxGetDocument($get, $user) {
        $sql = "SELECT t.id, d.referenceNumber, d.subject, d.from, d.dueDate, d.due15Days, d.deadline, d.dateReceived, t.received, d.status
                FROM dts_document d, dts_track t 
                WHERE t.document = d.id AND t.user = '$user'";
        if ($get !== 'All') {
            if($get === 'Archived')
                $sql .= " AND archive = 1";
            else
                $sql .= " AND status = '$get' AND archive = 0";
        }else{
            $sql .= " AND archive = 0";
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
    
    public function getNamesReceived($document){
        $sql = "SELECT CONCAT(u.lastname, ', ', u.firstname) AS 'name' FROM dts_track t, dts_user u WHERE u.id = t.user AND t.document = $document AND t.received <> '0000-00-00'";
        $query = $this->_ci->db->query($sql);
        $names = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $names .= "<i class='glyphicon glyphicon-eye-open' data-toggle='tooltip' title='Seen'></i> ". $row->name . "<br>";
            }
        }
        
        $sql1 = "SELECT CONCAT(u.lastname, ', ', u.firstname) AS 'name' FROM dts_track t, dts_user u WHERE u.id = t.user AND t.document = $document AND t.received = '0000-00-00'";
        $query1 = $this->_ci->db->query($sql1);
        if ($query1->num_rows() > 0) {
            foreach ($query1->result() as $row) {
                $names .= "<i class='glyphicon glyphicon-eye-close' data-toggle='tooltip' title='Not Seen'></i> ". $row->name . "<br>";
            }
        }
        return $names;
    }

    public function getCountUserDocuments($document) {
        $sql = "SELECT COUNT(*) AS 'count' FROM dts_track WHERE document = $document";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row('count');
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
        $track->setArchive($row->archive);

        $track->setId($row->id);
        $track->setDocument($row->document);
        $track->setUser($row->user);
        $track->setReceived($row->received);

        return $track;
    }

    public function updateReceived($user, $track) {
        $date = date('Y-m-d');
        $sql = "UPDATE dts_track SET received = '$date' WHERE id = $track AND user = $user";
        if ($this->_ci->db->query($sql))
            return true;
        return false;
    }
    
    public function forward($document, $notes, $action, $deadline, $users, $userId) {
        $sql = "UPDATE dts_document SET action = '$action', deadline = '$deadline' WHERE id = ?";
        if ($this->_ci->db->query($sql, array($document))) {
            foreach ($users as $user) {
                if(is_numeric($user)){
                    $ard = $this->getARD($user);
                    if ($ard) {
                        if (!$this->checkARD($ard, $document)) {
                            $doc = $this->addUserToTrack($ard, $document);
                            $this->addDocumentNotification($userId, $ard, $doc);
                        }
                    }

                    $doc = $this->addUserToTrack($user, $document);
                    $this->addDocumentNotification($userId, $user, $doc);
                }else{
                    $divisionID = $this->getDivisionIDByName($user);
                    $this->addDivisiontoTrack($divisionID, $document, $userId);
                }
            }
            $this->setStatusOnGoing($document);
            $this->addNoteToChat($document, $notes, $userId);
            return true;
        }
        return false;
    }
    
    private function addDivisiontoTrack($divisionID, $document,$userId){
        $sql = "SELECT id FROM dts_user WHERE division = '$divisionID' AND userType <> 'ADMIN' AND userType <> 'RD'";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $user = $row->id;
                $doc = $this->addUserToTrack($user, $document);
                $this->addDocumentNotification($userId, $user, $doc);
            }
        }
    }
    
    private function getDivisionIDByName($division){
        $sql = "SELECT id FROM dts_division WHERE name = '$division'";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                return $row->id;
            }
        }
        return false;
    }

    private function addDocumentNotification($creator, $receiver, $document){
        $data = array(
            'creator' => $creator,
            'receiver' => $receiver,
            'object' => $document,
            'type' => 1
        );
        
        $this->_ci->db->insert("dts_notification", $data);
    }
    
    public function ardForward($userId, $document, $users) {
        foreach ($users as $user) {
            $doc = $this->addUserToTrack($user, $document);
            $this->addDocumentNotification($userId, $user, $doc);
        }
        return true;
    }

    private function addNoteToChat($document, $notes, $userId) {
        $data = array(
            'document' => $document,
            'user' => $userId,
            'message' => $notes
        );
        $this->_ci->db->insert("dts_chat", $data);
    }

    private function setStatusOnGoing($document) {
        $sql = "SELECT status FROM dts_document WHERE id = '$document'";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            if ($query->row('status') == '') {
                $sql1 = "UPDATE dts_document SET status = 'On-Going' WHERE id = '$document'";
                $this->_ci->db->query($sql1);
            }
        }
    }

    private function addUserToTrack($user, $document) {
        if ($this->checkUser($user, $document) == 0) {
            $track = new Track_Model();
            $track->setDocument($document);
            $track->setUser($user);

            return $track->commit();
        }
    }

    private function checkUser($user, $document) {
        $sql = "SELECT COUNT(*) AS 'count' FROM dts_track WHERE user = '$user' AND document = '$document'";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $count = (int) $query->row('count');
            return $count;
        }
        return 0;
    }

    private function checkARD($user, $document) {
        $sql = "SELECT * FROM dts_track WHERE user = '$user' AND document = '$document'";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    private function getARD($user) {
        $sql = "SELECT userType, division FROM dts_user WHERE id = ?";
        $query = $this->_ci->db->query($sql, array($user));
        if ($query->num_rows() > 0) {
            $userType = $query->row('userType');
            $division = $query->row('division');
            if ($userType != 'ARD') {
                $sql1 = "SELECT id FROM dts_user WHERE division = '$division' AND userType = 'ARD'";
                $query1 = $this->_ci->db->query($sql1);
                if ($query1->num_rows() > 0) {
                    return $query1->row('id');
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function setUrgent($id){
        $sql = "SELECT subject FROM dts_document WHERE id = $id ";
        $query = $this->_ci->db->query($sql);
        if($query->num_rows() > 0){
            $subject  = $query->row('subject');
            if($subject[0] != '!'){
                $sql1 = "UPDATE dts_document SET subject = '!!! URGENT !!! $subject' WHERE id = $id";
                $this->_ci->db->query($sql1);
            }
        }
    }
}
