<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Chat extends CI_Controller {

    var $login = FALSE;
    var $id = '';
    var $userType = '';
    var $username = '';
    
    public function index() {
        $this->error(404);
    }
    
    private function checkLogin() {
        if ($this->session->userdata('logged_in')) {
            $this->login = TRUE;
            $session_data = $this->session->userdata('logged_in');
            $this->userType = $session_data['userType'];
            $this->id = $session_data['id'];
            $this->username = $session_data['username'];
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

    public function ajaxSeenMessage(){
        $this->checkLogin();
        $document = $this->cleanString($_POST['track']);
        $user = $this->id;
        
        $this->load->library("NotificationFactory");
        if($this->notificationfactory->seenMessageNotification($document,$user)){
            $result = array('status' => 'ok');
            
            echo json_encode($result);
        }else{
            $result = array('status' => 'failed');
            
            echo json_encode($result);
        }
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
                    $chatContents .= '<div class="col-xs-12"><div class="message self"><div class="row"><div class="col-sm-10"><p>'.  stripslashes($message->getMessage()).'</p></div><div class="col-sm-2"><time>'.$message->getCreated().'</time></div></div></div></div>';
                }else{
                    $chatContents .= '<div class="col-xs-12"><span><b>'.$message->getFullname().'</b></span><div class="message other"><div class="row"><div class="col-sm-10"><p>'.  stripslashes($message->getMessage()).'</p></div><div class="col-sm-2"><time>'.$message->getCreated().'</time></div></div></div></div>';
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
