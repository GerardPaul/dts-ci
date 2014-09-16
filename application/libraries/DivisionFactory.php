<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class DivisionFactory{

	private $_ci;

 	function __construct()
    {
    	//When the class is constructed get an instance of codeigniter so we can access it locally
    	$this->_ci =& get_instance();
    	//Include the user_model so we can use it
    	$this->_ci->load->model("division_model");
    }
	
    public function getDivision($id = 0) {
    	//Are we getting an individual user or are we getting them all
    	if ($id > 0) {
    		//Getting an individual user
			$sql = "SELECT * FROM division  WHERE id = ?";
    		$query = $this->_ci->db->query($sql, array($id));
    		//Check if any results were returned
    		if ($query->num_rows() > 0) {
    			//Pass the data to our local function to create an object for us and return this new object
    			return $this->createObjectFromData($query->row());
    		}
    		return false;
    	} else {
    		//Getting all the users
    		$sql = "SELECT * FROM division";
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

    public function createObjectFromData($row) {
    	//Create a new object
    	$division = new Division_Model();
		
    	$division->setId($row->id);
		$division->setDivisionName($row->name);
		$division->setDivisionDescription($row->description);
    	//Return the new object
    	return $division;
    }

}
?>