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
	public function updateStatus($id,$status) {
        $sql = "UPDATE document SET status = '$status' WHERE id = ?";
        if ($this->_ci->db->query($sql, array($id)))
            return true;
        return false;
    }
    
    public function ajaxGetDocument($get, $user) {
        $sql = "SELECT t.id, d.subject, d.from, d.dueDate, d.due15Days, d.deadline, d.dateReceived, t.received
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

	public function getCountUserDocuments($document){
		$sql = "SELECT COUNT(*) AS 'count' FROM track WHERE document = $document";
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

        $track->setId($row->id);
        $track->setDocument($row->document);
        $track->setUser($row->user);
        $track->setReceived($row->received);

        return $track;
    }
	
	public function updateReceived($user, $track) {
        $date = date('Y-m-d');
        $sql = "UPDATE track SET received = '$date' WHERE id = $track AND user = $user";
        if ($this->_ci->db->query($sql))
            return true;
        return false;
    }
	
	public function forward($document, $notes, $action, $deadline, $users) {
		$sql = "UPDATE document SET action = '$action', deadline = '$deadline' WHERE id = ?";
		if ($this->_ci->db->query($sql, array($document))){
			foreach($users as $user){
				$ard = $this->getARD($user);
				
				if($ard){
					if(!$this->checkARD($ard,$document)){
						if($this->addUserToTrack($ard, $document))
							echo "{Added ARD " . $ard . '}';
						else
							echo "{Failed ARD " . $ard . '}';
					}
				}
				
				if($this->addUserToTrack($user, $document))
					echo "{Added " . $user . '}';
				else
					echo "{Failed " . $user . '}';
			}
			return true;
		}
		return false;
    }
	
	private function addUserToTrack($user, $document){
		$track = new Track_Model();
		$track->setDocument($document);
        $track->setUser($user);
		
		return $track->commit();
	}
	
	private function checkARD($user, $document){
		$sql = "SELECT * FROM track WHERE user = '$user' AND document = '$document'";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
			return true;
		}
		return false;
	}
	
	private function getARD($user){
		$sql = "SELECT userType, division FROM user WHERE id = ?";
		$query = $this->_ci->db->query($sql, array($user));
        if ($query->num_rows() > 0) {
            $userType = $query->row('userType');
			$division = $query->row('division');
			if($userType != 'ARD'){
				$sql1 = "SELECT id FROM user WHERE division = '$division' AND userType = 'ARD'";
				$query1 = $this->_ci->db->query($sql1);
				if ($query1->num_rows() > 0) {
					return $query1->row('id');
				}else{
					return false;
				}
			}else{
				return false;
			}
        }else{
			return false;
		}
	}
}
