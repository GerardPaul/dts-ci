<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UserFactory {

    private $_ci;

    function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->model("user_model");
    }

    public function getUserLogin($username = '') {
        $sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
                        d.id AS 'divisionID', d.name, d.description
                FROM dts_user u, dts_division d WHERE u.division = d.id AND u.username = ?";
        $query = $this->_ci->db->query($sql, array($username));
        if ($query->num_rows() > 0) {
            return $this->createObjectFromData($query->row());
        }
        return false;
    }

    public function getUserByDivision($division) {
        $sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
                        d.id AS 'divisionID', d.name, d.description
                FROM dts_user u, dts_division d WHERE u.division = d.id AND d.name = ? ORDER BY u.userType ASC";
        $query = $this->_ci->db->query($sql, array($division));
        if ($query->num_rows() > 0) {
            $users = array();
            foreach ($query->result() as $row) {
                $users[] = $this->createObjectFromData($row);
            }
            return $users;
        }
        return false;
    }

    public function getAllUsers() {
        $sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
                    d.id AS 'divisionID', d.name, d.description
                FROM dts_user u, dts_division d 
                WHERE u.division = d.id AND u.status = '1' ORDER BY d.id ASC";
        $query = $this->_ci->db->query($sql);
        if ($query->num_rows() > 0) {
            $users = array();
            foreach ($query->result() as $row) {
                $users[] = $this->createObjectFromData($row);
            }
            return $users;
        }
        return false;
    }

    public function getUser($id = 0) {
        if ($id > 0) {
            $sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
                            d.id AS 'divisionID', d.name, d.description
                    FROM dts_user u, dts_division d WHERE u.division = d.id AND u.id = ?";
            $query = $this->_ci->db->query($sql, array($id));
            if ($query->num_rows() > 0) {
                return $this->createObjectFromData($query->row());
            }
            return false;
        } else {
            $sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
                            d.id AS 'divisionID', d.name, d.description
                    FROM dts_user u, dts_division d WHERE u.division = d.id";
            $query = $this->_ci->db->query($sql);
            if ($query->num_rows() > 0) {
                $users = array();
                foreach ($query->result() as $row) {
                    $users[] = $this->createObjectFromData($row);
                }
                return $users;
            }
            return false;
        }
    }

    public function addUser($firstname, $lastname, $email, $username, $password, $userType, $division) {
        $user = new User_Model();
        $user->setFirstName($firstname);
        $user->setLastName($lastname);
        $user->setEmail($email);
        $user->setUserType($userType);
        $user->setDivision($division);
        $user->setUsername($username);

        $salt1 = md5(uniqid(rand(), true));
        $salt = substr($salt1, 0, 50);
        $hashPassword = hash('sha256', $salt . $password . $salt);

        $user->setPassword($hashPassword);
        $user->setSalt($salt);

        return $user->commit();
    }

    public function updateUser($status, $id, $firstname, $lastname, $email, $username, $password, $userType, $division) {
        $user = new User_Model();
        $user->setId($id);
        $user->setFirstName($firstname);
        $user->setLastName($lastname);
        $user->setEmail($email);
        $user->setUserType($userType);
        $user->setDivision($division);
        $user->setUsername($username);
        $user->setStatus($status);

        if ($password != 'password') {
            $salt1 = md5(uniqid(rand(), true));
            $salt = substr($salt1, 0, 50);
            $hashPassword = hash('sha256', $salt . $password . $salt);

            $user->setPassword($hashPassword);
            $user->setSalt($salt);
        }

        return $user->commit();
    }

    public function updateProfile($userId, $firstname, $lastname, $email, $username, $password) {
        $sql = "UPDATE dts_user SET firstname = '$firstname', lastname = '$lastname', email = '$email', username = '$username' WHERE id = ?";
        if ($password != 'password') {
            $salt1 = md5(uniqid(rand(), true));
            $salt = substr($salt1, 0, 50);
            $hashPassword = hash('sha256', $salt . $password . $salt);
            $sql = "UPDATE dts_user SET firstname = '$firstname', lastname = '$lastname', email = '$email', username = '$username', password = '$hashPassword', salt = '$salt' WHERE id = ?";
        }
        if ($this->_ci->db->query($sql, array($userId)))
            return true;
        return false;
    }

    public function createObjectFromData($row) {
        $user = new User_Model();
        $user->setId($row->userID);
        $user->setUsername($row->username);
        $user->setPassword($row->password);

        $user->setSalt($row->salt);
        $user->setEmail($row->email);
        $user->setFirstName($row->firstname);
        $user->setLastName($row->lastname);
        $user->setUserType($row->userType);
        $user->setDivision($row->divisionID);
        $user->setDivisionName($row->name);
        $user->setDivisionDescription($row->description);
        $user->setStatus($row->status);

        return $user;
    }

    public function getDivision($user) {
        $sql = "SELECT d.name FROM dts_user u, dts_division d WHERE u.division = d.id AND u.id = ?";
        $query = $this->_ci->db->query($sql, array($user));
        if ($query->num_rows() > 0) {
            return $query->row('name');
        }
        return false;
    }

    private function checkId($userId, $item, $value){
        $sql = "SELECT $item FROM dts_user WHERE id = ?";
        $query = $this->_ci->db->query($sql, array($userId));
        if ($query->num_rows() > 0) {
            $value1 = $query->row($item);
            if($value1 == $value){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function checkUsername($username,$userId){
        $sql = "SELECT * FROM dts_user WHERE username = ?";
        $query = $this->_ci->db->query($sql, array($username));
        if ($query->num_rows() > 0) {
            if($this->checkId($userId, 'username', $username)){
                return true;
            }else{
                return false;
            }
        }else {
            return true;
        }
    }
    
    public function checkEmail($email,$userId){
        $sql = "SELECT * FROM dts_user WHERE email = ?";
        $query = $this->_ci->db->query($sql, array($email));
        if ($query->num_rows() > 0) {
            if($this->checkId($userId, 'email', $email)){
                return true;
            }else{
                return false;
            }
        }else {
            return true;
        }
    }
    
    public function checkUserType($userType,$userId){
        $sql = "SELECT * FROM dts_user WHERE userType = ?";
        $query = $this->_ci->db->query($sql, array($userType));
        $num = $query->num_rows();
        if (($userType == 'ADMIN' || $userType == 'RD' || $userType == 'SEC') && $num == 1) {
            if($this->checkId($userId, 'userType', $userType)){
                return true;
            }else{
                return false;
            }
        }else {
            return true;
        }
    }
}
