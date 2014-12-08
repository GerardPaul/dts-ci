<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class NotificationFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("notification_model");
    }

    public function ajaxCountNotifications($receiverId) {
        $sql = "SELECT COUNT(id) AS 'num' FROM dts_notification WHERE receiver = '$receiverId' AND status = '0' ORDER BY dateCreated DESC";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row('num');
        }
        return 0;
    }
    
    public function ajaxGetNotifications($receiverId) {
        $sql = "SELECT * FROM dts_notification WHERE receiver = '$receiverId' AND status = '0' ORDER BY dateCreated DESC";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $notifications = array();
            foreach ($query->result() as $row) {
                $notifications[] = $this->createObjectFromData($row);
            }
            return $notifications;
        }
        return false;
    }

    public function ajaxSeen($id) {
        
    }

    public function createObjectFromData($row) {
        $notification = new Notification_Model();

        $notification->setId($row->id);
        $notification->setCreator($row->creator);
        $notification->setReceiver($row->receiver);
        $notification->setObject($row->object);
        $notification->setStatus($row->status);
        $notification->setType($row->type);
        $notification->setDateCreated($this->formatDate($row->timeOccured));

        return $notification;
    }

    private function formatDate($date) {
        return date('Y-m-j @ H:i:s', strtotime($date));
    }

}
