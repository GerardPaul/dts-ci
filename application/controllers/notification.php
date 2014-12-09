<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification extends CI_Controller {

    var $login = FALSE;
    var $userType = '';
    var $userId = '';
    var $username = '';
    
    public function index() {
        $this->error(404);
    }
    
    private function checkLogin() {
        if ($this->session->userdata('logged_in')) {
            $this->login = TRUE;
            $session_data = $this->session->userdata('logged_in');
            $this->userType = $session_data['userType'];
            $this->userId = $session_data['id'];
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

    public function ajaxAddMessage() {
        $this->checkLogin();
        $document = $this->cleanString($_POST['document']);
        $user = $this->id;
        $message = $this->cleanString($_POST['message']);

        $this->load->library("ChatFactory");
        $this->chatfactory->addMessage($document, $user, $message);
        
        echo $this->_getMessage($user, $document, '0');
    }

    public function ajaxCountNotifications(){
        $this->checkLogin();
        echo $this->_getCountNotifications();
    }
    
    function _getCountNotifications(){
        $this->load->library("NotificationFactory");
        $count = (int) $this->notificationfactory->ajaxCountNotifications($this->userId);
        
        if($count > 0){
            $result = array('status' => 'ok', 'num' => $count);
            
            return json_encode($result);
            exit();
        }else{
            $result = array('status' => 'ok', 'num' => '');
            
            return json_encode($result);
            exit();
        }
    }
    
    public function ajaxGetNotifications(){
        $this->checkLogin();
        echo $this->_getNotifications();
    }
    function _getNotifications() {
        $this->load->library("NotificationFactory");
        $notifications = $this->notificationfactory->ajaxGetNotifications($this->userId);
        
        $userType = $this->userType;
        if($userType == 'ADMIN'){
            $base_url = base_url()."admin/document/view/";
        }
        else if($userType == 'RD' || $userType == 'ARD' || $userType == 'SEC'){
            $base_url = base_url()."admin/document/details/";
        }
        else if($userType == 'EMP'){
            $base_url = base_url()."document/details/";
        }
           
        $contents = '';
        if($notifications){
            $i = 1;
            foreach($notifications as $notification){
                if($notification->getType() == '1'){
                    $contents .= "<li><a class='notify' href='$base_url".$notification->getObject()."?notification=".$notification->getId()."'><span class='glyphicon glyphicon-file'></span> You have a new document (ref.# ".$notification->getDocumentRefNo().").</a></li>";
                }else{
                    $contents .= "<li><a class='notify' href='$base_url".$notification->getObject()."?notification=".$notification->getId()."'><span class='glyphicon glyphicon-envelope'></span> You have a new message from ".$notification->getCreator().".</a></li>";
                }
                $i++;
            }
            $result = array('status' => 'ok', 'content' => $contents);
            
            return json_encode($result);
            exit();
        }else{
            $result = array('status' => 'ok', 'content' => '');
            
            return json_encode($result);
            exit();
        }
    }

}
