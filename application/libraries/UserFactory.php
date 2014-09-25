<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class UserFactory {

	private $_ci;

 	function __construct(){
    	$this->_ci =& get_instance();
    	$this->_ci->load->model("user_model");
    }

	public function getUserLogin($username=''){
		$sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
					d.id AS 'divisionID', d.name, d.description
				FROM user u, division d WHERE u.division = d.id AND u.username = ?";
    	$query = $this->_ci->db->query($sql, array($username));
    	if ($query->num_rows() > 0) {
    		return $this->createObjectFromData($query->row());
    	}
    	return false;
	}
	
    public function getUser($id = 0) {
    	if ($id > 0) {
			$sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
						d.id AS 'divisionID', d.name, d.description
					FROM user u, division d WHERE u.division = d.id AND u.id = ?";
    		$query = $this->_ci->db->query($sql, array($id));
    		if ($query->num_rows() > 0) {
    			return $this->createObjectFromData($query->row());
    		}
    		return false;
    	} else {
    		$sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
						d.id AS 'divisionID', d.name, d.description
					FROM user u, division d WHERE u.division = d.id";
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

	public function addUser($firstname, $lastname, $email, $username, $password, $userType, $division){
		$user = new User_Model();
		$user->setFirstName($firstname);
		$user->setLastName($lastname);
		$user->setEmail($email);
		$user->setUserType($userType);
		$user->setDivision($division);
		$user->setUsername($username);
		
		$salt1 = md5(uniqid(rand(),true));
		$salt = substr($salt1,0,50);
		$hashPassword = hash('sha256', $salt . $password . $salt);
		
		$user->setPassword($hashPassword);
		$user->setSalt($salt);
		
		return $user->commit();
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

}
?>