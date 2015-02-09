<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Document extends CI_Controller {

    var $title = 'Documents';
    var $login = FALSE;
    var $userType = '';
    var $userId = '';
    var $username = '';

    private function checkLogin() {
        if ($this->session->userdata('logged_in')) {
            $this->login = TRUE;
            $session_data = $this->session->userdata('logged_in');
            $this->userType = $session_data['userType'];
            $this->userId = $session_data['id'];
            $this->username = $session_data['username'];
        } else {
            $this->login = FALSE;
            $this->userType = 'ARD';
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

        return addslashes($escaped);
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
            "userType" => $this->userType,
            "username" => $this->username
        );
        if ($this->userType == 'EMP') {
            $this->load->template('error_view', $data);
        } else {
            $this->load->admin_template('error_view', $data);
        }
    }

    public function index() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'EMP') {
                $this->error(403);
            } else {
                $data = array(
                    "title" => $this->title,
                    "header" => 'All Documents',
                    "userType" => $this->userType,
                    "username" => $this->username,
                    "userId" => $this->userId,
                    "load" => 'documents'
                );
                $this->load->template('user_documents', $data);
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getAllDocuments() {
        $userId = $_POST['userId'];

        $this->load->library("TrackFactory");
        echo json_encode($this->trackfactory->ajaxGetDocument($_POST['change'], $userId));
    }

    public function details($track = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'EMP') {
                $this->error(403);
            } else {
                $track = (int) $track;
                $this->load->library("TrackFactory");
                $document = $this->trackfactory->getUserDocument($track);
                if ($track > 0 && $document) {
                    $this->receive($this->userId, $track); //automatically receive upon viewing document
                    $status = $this->status($document->getStatus());
                    
                    $data = array(
                        "document" => $document,
                        "title" => 'Document Details',
                        "header" => 'Document Details',
                        "userType" => $this->userType,
                        "username" => $this->username,
                        "status" => $status,
                        "users" => $this->trackfactory->getCountUserDocuments($document->getDocument()),
                        "load" => 'empdetails',
                        "track" => $track
                    );
                    $this->load->template('emp_view_document', $data);
                    
                    if(isset($_GET['notification'])){
                        $notification_id = $_GET['notification'];
                        $this->load->library('NotificationFactory');
                        $this->notificationfactory->seenNotification($notification_id);
                    }
                } else {
                    $this->error(404);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    private function receive($user, $track) {
        $this->load->library("TrackFactory");
        if ($this->trackfactory->updateReceived($user, $track)) {
            return true;
        }
        return false;
    }
    
    private function status($status) {
        if ($status == 'Cancelled') {
            return '<span class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        } else if ($status == 'On-Going') {
            return '<span class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        } else if ($status == 'Complied') {
            return '<span class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="' . $status . '" data-toggle="tooltip"></i>&nbsp;' . $status . '</span>';
        }
    }

    public function statusChange($document = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'EMP') {
                $this->error(403);
            } else {
                $document = (int) $document;
                $status = $this->cleanString($_POST['status']);
                $track = $this->cleanString($_POST['trackId']);
                $this->load->library("TrackFactory");
                if ($this->trackfactory->updateStatus($document, $status)) {
                    $this->load->library("LogsFactory");
                    $user = $this->username;
                    
                    $this->load->library("DocumentFactory");
                    $refNo = $this->documentfactory->getRefNo($document);
                    $action = "$user has changed status of document, ref no. <a href='".base_url()."admin/document/view/$document' target='_blank'>$refNo</a>.";
                    $this->logsfactory->logAction($action);
                    
                    redirect('document/details/' . $track);
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

}
