<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	
	var $title = 'Users';
	
	public function index()	{
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$this->load->library("UserFactory");
			$this->load->library("DivisionFactory");
			$data = array(
				"users" => $this->userfactory->getUser(),
				"divisions" => $this->divisionfactory->getDivision(),
				"title" => $this->title,
				"userType" => $session_data['userType']
			);
			$this->load->admin_template('show_users',$data);
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function show($userId = 0){
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$userId = (int)$userId;
			$this->load->library("UserFactory");
			$data = array(
				"users" => $this->userfactory->getUser($userId),
				"title" => $this->title,
				"userType" => $session_data['userType']
			);
			$this->load->admin_template('show_users',$data);
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function add(){
		if($this->session->userdata('logged_in')){
			$this->load->library("UserFactory");
			if($this->userfactory->addUser($_POST['firstname'],$_POST['lastname'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['userType'],$_POST['division'])){
				redirect('admin/user');
			}else{
				echo "Failed!";
			}
		}else{
			redirect('login', 'refresh');
		}
	}
}