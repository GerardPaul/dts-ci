<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Division extends CI_Controller {
	
	var $title = 'Division';
	
	public function index()	{
		$this->load->library("DivisionFactory");
		$data = array(
			"divisions" => $this->divisionfactory->getDivision(),
			"title" => $this->title
		);
		$this->load->template('show_divisions',$data);
	}
	
	public function show($divisionId = 0)
	{
		$divisionId = (int)$divisionId;
		//Load the user factory
		$this->load->library("DivisionFactory");
		//Create a data array so we can pass information to the view
		$data = array(
			"divisions" => $this->divisionfactory->getDivision($divisionId),
			"title" => $this->title
		);
		$this->load->template('show_divisions',$data);
	}
	
	public function add(){
		
	}
}