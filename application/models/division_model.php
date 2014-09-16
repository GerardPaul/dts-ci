<?php 

class Division_Model extends CI_Model{
	private $_id;
	private $_divisionName;
	private $_divisionDescription;
	
	function __construct(){
		parent::__construct();
	}
	
	public function setId($value)
	{
		$this->_id = $value;
	}
	public function getId()
	{
		return $this->_id;
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
	
	public function commit()
	{
		$data = array(
			'name' => $this->_divisionName,
			'description' => $this->_divisionDescription
		);

		if ($this->_id > 0) {
			//We have an ID so we need to update this object because it is not new
			if ($this->db->update("division", $data, array("id" => $this->_id))) {
				return true;
			}
		} else {
			//We dont have an ID meaning it is new and not yet in the database so we need to do an insert
			if ($this->db->insert("division", $data)) {
				//Now we can get the ID and update the newly created object
				$this->_id = $this->db->insert_id();
				return true;
			}
		}
		return false;
	}
}

?>