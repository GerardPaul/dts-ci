<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Division extends CI_Controller {

    var $title = 'Divisions';
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
            $this->userType = 'EMP';
        }
    }

    function cleanString($string) {
        $detagged = strip_tags($string);
        if (get_magic_quotes_gpc()) {
            $stripped = stripslashes($detagged);
            $escaped = mysql_real_escape_string($stripped);
        } else {
            $escaped = mysql_real_escape_string($detagged);
        }
        return $escaped;
    }

    private function error($er = 0) {
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
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $this->load->library("DivisionFactory");
                    $data = array(
                        "divisions" => $this->divisionfactory->getDivision(),
                        "title" => $this->title,
                        "header" => 'All Divisions',
                        "userType" => $this->userType,
                        "username" => $this->username
                    );
                    $this->load->admin_template('show_divisions', $data);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function add() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $this->load->library("DivisionFactory");
                    $divisionName = $this->cleanString($_POST['divisionName']);
                    $desc = $this->cleanString($_POST['description']);

                    if ($this->divisionfactory->addDivision($divisionName, $desc)) {
                        $this->load->library("LogsFactory");
                        $user = $this->username;
                        $action = "$user has added '$divisionName' in DIVISIONS.";
                        $this->logsfactory->logAction($action);

                        redirect('admin/division');
                    } else {
                        echo "Failed!";
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

}
