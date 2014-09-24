<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Division extends CI_Controller {
	
	var $title = 'Divisions';
	
	public function index()	{
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$this->load->library("DivisionFactory");
			$data = array(
				"divisions" => $this->divisionfactory->getDivision(),
				"title" => $this->title,
				"header" => 'All Divisions',
				"userType" => $session_data['userType']
			);
			$this->load->admin_template('show_divisions',$data);
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function show($divisionId = 0){
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$divisionId = (int)$divisionId;
			$this->load->library("DivisionFactory");
			$data = array(
				"divisions" => $this->divisionfactory->getDivision($divisionId),
				"title" => $this->title,
				"header" => 'Division Details',
				"userType" => $session_data['userType']
			);
			$this->load->admin_template('show_divisions',$data);
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function add(){
		if($this->session->userdata('logged_in')){
			$this->load->library("DivisionFactory");
			if($this->divisionfactory->addDivision($_POST['divisionName'],$_POST['description'])){
				redirect('admin/division');
			}else{
				echo "Failed!";
			}
		}else{
			redirect('login', 'refresh');
		}
	}
}