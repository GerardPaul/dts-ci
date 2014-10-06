<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Chat extends CI_Controller {

    var $login = FALSE;
    var $id = '';

//    public function Chat() {
//        parent::_construct();
//    }
    private function checkLogin() {
        if ($this->session->userdata('logged_in')) {
            $this->login = TRUE;
            $session_data = $this->session->userdata('logged_in');
            $this->id = $session_data['id'];
        } else {
            $this->login = FALSE;
        }
    }

    private function cleanString($string) {
        $detagged = strip_tags($string);
        if (get_magic_quotes_gpc()) {
            $stripped = stripslashes($detagged);
            $escaped = mysql_real_escape_string($stripped);
        } else {
            $escaped = mysql_real_escape_string($detagged);
        }
        return $escaped;
    }

    private function error($er) {
        $header = '';
        $content = '';
        if ($er == 403) {
            $header = 'Forbidden';
            $content = 'You have no permission to view this page.';
        } else if ($er == 404) {
            $header = 'Not Found';
            $content = 'We cannot find the page you are looking for.';
        }
        $data = array(
            "title" => 'Error ' . $er,
            "header" => 'Error ' . $er . ' ' . $header,
            "content" => $content,
            "userType" => $this->userType
        );
        $this->load->admin_template('error_view', $data);
    }

    public function ajaxAddMessage() {
        $this->checkLogin();
        $document = $this->cleanString($_POST['document']);
        $user = $this->id;
        $message = $this->cleanString($_POST['message']);

        $this->load->library("ChatFactory");
        $this->chatfactory->addMessage($document, $user, $message);
        
        echo $this->_getMessage($user, $document, '0');
    }

    public function ajaxGetMessages(){
        $this->checkLogin();
        $document = 0;
        $chat = $_POST['chat'];
        if(isset($_POST['document'])){
            $document = $_POST['document'];
        }
        $user = $this->id;
        echo $this->_getMessage($user, $document,$chat);
    }
    
    function _getMessage($user,$document,$chat) {
        $session_data = $this->session->userdata('logged_in');
        if($chat=='1' || !isset($session_data['lastID_'.$user.'_'.$document])){
            $lastID = 0;
        }
        else{
            $lastID = (int)$session_data['lastID_'.$user.'_'.$document];
        }
        
        $this->load->library("ChatFactory");
        $messages = $this->chatfactory->getChat($document, $lastID);
        
        if($messages){
            $size = count($messages);
            $lastID = $messages[$size-1]->getId();
            
            $session_data['lastID_'.$user.'_'.$document] = $lastID;
            $this->session->set_userdata('logged_in', $session_data);
            
            $chatContents = '';
            foreach($messages as $message){
                if($message->getUser() == $user){
                    $chatContents .= '<div class="message self"><p>'.$message->getMessage().'</p><time>'.$message->getCreated().'</time></div>';
                }else{
                    $chatContents .= '<div class="message other"><p>'.$message->getFullname().'</p><p>'.$message->getMessage().'</p><time>'.$message->getCreated().'</time></div>';
                }
            }
            $result = array('status' => 'ok', 'content' => $chatContents);
            
            return json_encode($result);
            exit();
        }else{
            $result = array('status' => 'ok', 'content' => '');
            
            return json_encode($result);
            exit();
        }
    }

}
