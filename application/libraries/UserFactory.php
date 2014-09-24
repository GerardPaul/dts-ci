<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class UserFactory {

	private $_ci;

 	function __construct()
    {
    	//When the class is constructed get an instance of codeigniter so we can access it locally
    	$this->_ci =& get_instance();
    	//Include the user_model so we can use it
    	$this->_ci->load->model("user_model");
    }

	public function getUserLogin($username=''){
		$sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
					d.id AS 'divisionID', d.name, d.description
				FROM user u, division d WHERE u.division = d.id AND u.username = ?";
    	$query = $this->_ci->db->query($sql, array($username));
    	//Check if any results were returned
    	if ($query->num_rows() > 0) {
    		//Pass the data to our local function to create an object for us and return this new object
    		return $this->createObjectFromData($query->row());
    	}
    	return false;
	}
	
    public function getUser($id = 0) {
    	//Are we getting an individual user or are we getting them all
    	if ($id > 0) {
    		//Getting an individual user
			$sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
						d.id AS 'divisionID', d.name, d.description
					FROM user u, division d WHERE u.division = d.id AND u.id = ?";
    		$query = $this->_ci->db->query($sql, array($id));
    		//Check if any results were returned
    		if ($query->num_rows() > 0) {
    			//Pass the data to our local function to create an object for us and return this new object
    			return $this->createObjectFromData($query->row());
    		}
    		return false;
    	} else {
    		//Getting all the users
    		$sql = "SELECT u.id AS 'userID', u.firstname, u.lastname, u.email, u.username, u.password, u.salt, u.userType, u.status,
						d.id AS 'divisionID', d.name, d.description
					FROM user u, division d WHERE u.division = d.id";
    		$query = $this->_ci->db->query($sql);
    		//Check if any results were returned
    		if ($query->num_rows() > 0) {
    			//Create an array to store users
    			$users = array();
    			//Loop through each row returned from the query
    			foreach ($query->result() as $row) {
    				//Pass the row data to our local function which creates a new user object with the data provided and add it to the users array
    				$users[] = $this->createObjectFromData($row);
    			}
    			//Return the users array
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
    	//Create a new user_model object
    	$user = new User_Model();
    	//Set the ID on the user model
    	$user->setId($row->userID);
    	//Set the username on the user model
    	$user->setUsername($row->username);
    	//Set the password on the user model
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
    	//Return the new user object
    	return $user;
    }

}
?>