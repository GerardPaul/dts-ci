<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	
	var $title = 'Users';
	
	public function index()	{
		$this->load->library("UserFactory");
		$data = array(
			"users" => $this->userfactory->getUser(),
			"title" => $this->title
		);
		$this->load->template('show_users',$data);
	}
	
	public function show($userId = 0)
	{
		$userId = (int)$userId;
		//Load the user factory
		$this->load->library("UserFactory");
		//Create a data array so we can pass information to the view
		$data = array(
			"users" => $this->userfactory->getUser($userId),
			"title" => $this->title
		);
		$this->load->template('show_users',$data);
	}
}