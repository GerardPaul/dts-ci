<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ChatFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("chat_model");
    }

    public function getChat($id = 0, $lastID = 0) {
        if ($id > 0) {
            $sql = 'SELECT c.id, c.document, c.user, c.message, DATE_FORMAT(c.created, "%M %d, %Y at %H:%i:%s") AS "created", CONCAT(u.lastname, ",", u.firstname) AS "fullname"
                    FROM chat c, user u
                    WHERE c.user = u.id AND c.document = ? AND c.id > ? ORDER BY c.id ASC';
            $query = $this->_ci->db->query($sql, array($id, $lastID));
            if ($query->num_rows() > 0) {
                $chat = array();
                foreach ($query->result() as $row) {
                    $chat[] = $this->createObjectFromData($row);
                }
                return $chat;
            }
            return false;
        } else {
//            $sql = 'SELECT c.id, c.document, c.user, c.message, DATE_FORMAT(c.created, "%M %d, %Y at %H:%i:%s") AS "created", CONCAT(u.lastname, ",", u.firstname) AS "fullname"
//                    FROM chat c, user u
//                    WHERE c.user = u.id AND c.id > ? ORDER BY c.id ASC';
//            $query = $this->_ci->db->query($sql, array($lastID));
//            if ($query->num_rows() > 0) {
//                $chat = array();
//                foreach ($query->result() as $row) {
//                    $chat[] = $this->createObjectFromData($row);
//                }
//                return $chat;
//            }
            return false;
        }
    }

    public function addMessage($document, $user, $message) {
        $chat = new Chat_Model();
        $chat->setDocument($document);
        $chat->setUser($user);
        $chat->setMessage($message);

        return $chat->commit();
    }

    public function createObjectFromData($row) {
        $chat = new Chat_Model();

        $chat->setId($row->id);
        $chat->setDocument($row->document);
        $chat->setUser($row->user);
        $chat->setMessage($row->message);
        $chat->setCreated($row->created);
        $chat->setFullname($row->fullname);

        return $chat;
    }

}
