<?php
class User_Model extends CI_Model{
	private $_id = 0;
	private $_username;
	private $_password;
	private $_salt;
	private $_email;
	private $_firstname;
	private $_lastname;
	private $_userType;
	private $_division;
	private $_divisionName;
	private $_divisionDescription;
	private $_status = 1;

	function __construct()
	{
		parent::__construct();
	}

	/**
	* @return int [$this->_id] Return this objects ID
	*/
	public function setId($value)
	{
		$this->_id = $value;
	}
	public function getId()
	{
		return $this->_id;
	}

	/**
	* @return string [$this->_username] Return this objects username
	*/
	public function getUsername()
	{
		return $this->_username;
	}

	public function setUsername($value)
	{
		$this->_username = $value;
	}

	public function getPassword()
	{
		return $this->_password;
	}

	public function setPassword($value)
	{
		$this->_password = $value;
	}
	
	
	public function getSalt()
	{
		return $this->_salt;
	}

	
	public function setSalt($value)
	{
		$this->_salt = $value;
	}
	
	public function getFirstName()
	{
		return $this->_firstname;
	}

	public function setFirstName($value)
	{
		$this->_firstname = $value;
	}
	public function getEmail()
	{
		return $this->_email;
	}

	public function setEmail($value)
	{
		$this->_email = $value;
	}
	
	public function getLastName()
	{
		return $this->_lastname;
	}

	public function setLastName($value)
	{
		$this->_lastname = $value;
	}

	public function getUserType()
	{
		return $this->_userType;
	}

	public function setUserType($value)
	{
		$this->_userType = $value;
	}
	
	public function getDivision()
	{
		return $this->_division;
	}

	public function setDivision($value)
	{
		$this->_division = $value;
	}
	public function getDivisionName()
	{
		return $this->_divisionName;
	}

	public function setDivisionName($value)
	{
		$this->_divisionName = $value;
	}
	public function getDivisionDescription()
	{
		return $this->_divisionDescription;
	}

	public function setDivisionDescription($value)
	{
		$this->_divisionDescription = $value;
	}
	public function getStatus()
	{
		return $this->_status;
	}

	public function setStatus($value)
	{
		$this->_status = $value;
	}
	/*
	* Class Methods
	*/

	/**
	*	Commit method, this will commit the entire object to the database
	*/
	public function commit()
	{
		$data = array(
			'username' => $this->_username,
			'password' => $this->_password,
			'salt' => $this->_salt,
			'userType' => $this->_userType,
			'email' => $this->_email,
			'firstname' => $this->_firstname,
			'lastname' => $this->_lastname,
			'division' => $this->_division,
			'status' => $this->_status
		);

		if ($this->_id > 0) {
			//We have an ID so we need to update this object because it is not new
			if ($this->db->update("user", $data, array("id" => $this->_id))) {
				return true;
			}
		} else {
			//We dont have an ID meaning it is new and not yet in the database so we need to do an insert
			if ($this->db->insert("user", $data)) {
				//Now we can get the ID and update the newly created object
				$this->_id = $this->db->insert_id();
				return true;
			}
		}
		return false;
	}
}
?>