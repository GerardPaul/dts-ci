<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    var $title = 'Users';
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
                    $this->load->library("UserFactory");
                    $this->load->library("DivisionFactory");
                    $data = array(
                        "users" => $this->userfactory->getUser(),
                        "divisions" => $this->divisionfactory->getDivision(),
                        "title" => $this->title,
                        "header" => 'All Users',
                        "userType" => $this->userType,
                        "username" => $this->username
                    );
                    $this->load->admin_template('show_users', $data);
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
                    $this->load->library("UserFactory");
                    $firstname = $this->cleanString($_POST['firstname']);
                    $lastname = $this->cleanString($_POST['lastname']);
                    $email = $this->cleanString($_POST['email']);
                    $username = $this->cleanString($_POST['username']);
                    $password = $this->cleanString($_POST['password']);
                    $type = $this->cleanString($_POST['userType']);
                    $division = $this->cleanString($_POST['division']);

                    if ($this->userfactory->addUser($firstname, $lastname, $email, $username, $password, $type, $division)) {
                        $this->load->library("LogsFactory");
                        $user = $this->username;
                        $action = "User '$user' has added '$firstname $lastname' to USERS.";
                        $this->logsfactory->logAction($action);

                        redirect('admin/user');
                    } else {
                        echo "Failed!";
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit($id = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $id = (int) $id;
                    $this->load->library("UserFactory");
                    $user = $this->userfactory->getUser($id);
                    if ($user && $id > 0) {
                        $this->load->library("DivisionFactory");
                        $data = array(
                            "divisions" => $this->divisionfactory->getDivision(),
                            "user" => $user,
                            "title" => $this->title,
                            "header" => 'Edit User',
                            "userType" => $this->userType,
                            "username" => $this->username
                        );
                        $this->load->admin_template('edit_user', $data);
                    } else {
                        $this->error(404);
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function update($id = 0) {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD') {
                    $this->error(403);
                } else {
                    $this->load->library("UserFactory");
                    $status = $this->cleanString($_POST['status']);
                    $userId = $this->cleanString($_POST['userId']);
                    $firstname = $this->cleanString($_POST['firstname']);
                    $lastname = $this->cleanString($_POST['lastname']);
                    $email = $this->cleanString($_POST['email']);
                    $username = $this->cleanString($_POST['username']);
                    $password = $this->cleanString($_POST['password']);
                    $type = $this->cleanString($_POST['userType']);
                    $division = $this->cleanString($_POST['division']);

                    if ($this->userfactory->updateUser($status, $userId, $firstname, $lastname, $email, $username, $password, $type, $division)) {
                        $this->load->library("LogsFactory");
                        $user = $this->username;
                        $action = "User '$user' has updated '$firstname $lastname' in USERS.";
                        $this->logsfactory->logAction($action);

                        redirect('admin/user');
                    } else {
                        echo "Failed!";
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function checkUsername() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD' || $this->userType == 'SEC') {
                    $this->error(403);
                } else {
                    $this->load->library("UserFactory");
                    $username = $this->cleanString($_GET['username']);
                    echo json_encode(array('valid' => $this->userfactory->checkUsername($username)));
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function checkEmail() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD' || $this->userType == 'SEC') {
                    $this->error(403);
                } else {
                    $this->load->library("UserFactory");
                    $email = $this->cleanString($_GET['email']);
                    echo json_encode(array('valid' => $this->userfactory->checkEmail($email)));
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function checkUserType() {
        $this->checkLogin();
        if ($this->login) {
            if ($this->userType == 'EMP') {
                $this->error(403);
            } else {
                if ($this->userType == 'RD' || $this->userType == 'ARD' || $this->userType == 'SEC') {
                    $this->error(403);
                } else {
                    $this->load->library("UserFactory");
                    $userType = $this->cleanString($_GET['userType']);
                    echo json_encode(array('valid' => $this->userfactory->checkUserType($userType)));
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }
}
