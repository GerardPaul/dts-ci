<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class TrackFactory {

    private $_ci;

    function __construct() {
        //When the class is constructed get an instance of codeigniter so we can access it locally
        $this->_ci = & get_instance();
        //Include the user_model so we can use it
        $this->_ci->load->model("track_model");
    }
	
	public function ajaxGetDocument($get, $user) {
        $sql = "SELECT d.id, d.subject, d.action, d.from, d.dueDate, d.due15Days, d.deadline,
					d.attachment,
				FROM document d, track t 
				WHERE t.document = d.id AND t.user = $user";
        if($get!=='All'){
            $sql = "SELECT * FROM document WHERE status = '$get'";
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
		
		$track->setId($row->trackId);
		$track->setDocument($row->document);
		$track->setUser($row->user);
		$track->setReceived($row->received);

        return $track;
    }
}