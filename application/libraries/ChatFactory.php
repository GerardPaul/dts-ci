<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ChatFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("chat_model");
    }

    public function getChat($document = 0) {
        if ($id > 0) {
            $sql = 'SELECT c.id, c.document, c. user, c.message, c.created, CONCAT(u.lastname, \',\', u.firstname) AS "fullname"
                    FROM chat c, user u
                    WHERE c.user = u.id AND document = ?';
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                return $this->createObjectFromData($query->row());
            }
            return false;
        } else {
            $sql = 'SELECT c.id, c.document, c.user, c.message, c.created, CONCAT(u.lastname, \',\', u.firstname) AS "fullname"
                    FROM chat c, user u
                    WHERE c.user = u.id';
            $query = $this->_ci->db->query($sql);
            if ($query->num_rows() > 0) {
                $division = array();
                foreach ($query->result() as $row) {
                    $division[] = $this->createObjectFromData($row);
                }
                return $division;
            }
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
