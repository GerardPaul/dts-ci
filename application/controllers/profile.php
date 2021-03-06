<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends CI_Controller {

    var $title = 'Profile';
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
        $this->error(404);
    }

    public function edit() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'ADMIN' || $this->userType == 'SEC' || $this->userType == 'RD' || $this->userType == 'ARD') {
                $this->error(403);
            } else {
                $id = $this->userId;
                $this->load->library("UserFactory");
                $user = $this->userfactory->getUser($id);
                $data = array(
                    "user" => $user,
                    "title" => $this->title,
                    "header" => 'Edit Profile',
                    "userType" => $this->userType,
                    "username" => $this->username,
                    "userId" => $this->userId
                );
                $this->load->template('edit_profile', $data);
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function update() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType != 'EMP') {
                $this->error(403);
            } else {
                $this->load->library("UserFactory");
                $userId = $this->userId;
                $firstname = $this->cleanString($_POST['firstname']);
                $lastname = $this->cleanString($_POST['lastname']);
                $email = $this->cleanString($_POST['email']);
                $username = $this->cleanString($_POST['username']);
                $password = $this->cleanString($_POST['password']);

                if ($this->userfactory->updateProfile($userId, $firstname, $lastname, $email, $username, $password)) {
                    $this->load->library("LogsFactory");
                    $user = $this->username;
                    $action = "User '$user' has updated his profile.";
                    $this->logsfactory->logAction($action);

                    if ($password === 'password')
                        redirect('home/');
                    else
                        redirect('home/logout');
                } else {
                    echo "Failed!";
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function checkUsername() {
        $this->checkLogin();
        if ($this->login) {
            $this->load->library("UserFactory");
            $username = $this->cleanString($_GET['username']);
            $userId = $this->cleanString($_GET['type']);
            echo json_encode(array('valid' => $this->userfactory->checkUsername($username, $userId)));
        } else {
            redirect('login', 'refresh');
        }
    }

    public function checkEmail() {
        $this->checkLogin();
        if ($this->login) {
            $this->load->library("UserFactory");
            $email = $this->cleanString($_GET['email']);
            $userId = $this->cleanString($_GET['type']);
            echo json_encode(array('valid' => $this->userfactory->checkEmail($email, $userId)));
        } else {
            redirect('login', 'refresh');
        }
    }

}
