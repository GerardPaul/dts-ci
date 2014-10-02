<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Division extends CI_Controller {
	
	var $title = 'Divisions';
	var $login = FALSE;
	var $userType = '';
	
	private function checkLogin(){
		if($this->session->userdata('logged_in')){
			$this->login = TRUE;
			$session_data = $this->session->userdata('logged_in');
			$this->userType = $session_data['userType'];
		}else{
			$this->login = FALSE;
			$this->userType = 'EMP';
		}
	}
	
	private function error($er = 0){
		$header = '';
		$content = '';
		if($er == 403){
			$header = 'Forbidden';
			$content = 'You have no permission to view this page.';
		}else if($er == 404){
			$header = 'Not Found';
			$content = 'We cannot find the page you are looking for.';
		}
		$data = array(
			"title" => 'Error '.$er,
			"header" => 'Error '.$er.' ' . $header,
			"content" => $content,
			"userType" => $this->userType
		);
		$this->load->admin_template('error_view',$data);
	}
	
	public function index()	{
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error(403);
			}else{
				if($this->userType=='RD' || $this->userType=='ARD'){
					$this->error(403);
				}else{
					$this->load->library("DivisionFactory");
					$data = array(
						"divisions" => $this->divisionfactory->getDivision(),
						"title" => $this->title,
						"header" => 'All Divisions',
						"userType" => $this->userType
					);
					$this->load->admin_template('show_divisions',$data);
				}
			}
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function add(){
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error(403);
			}else{
				if($this->userType=='RD' || $this->userType=='ARD'){
					$this->error(403);
				}else{
					$this->load->library("DivisionFactory");
					if($this->divisionfactory->addDivision($_POST['divisionName'],$_POST['description'])){
						redirect('admin/division');
					}else{
						echo "Failed!";
					}
				}
			}
		}else{
			redirect('login', 'refresh');
		}
	}
}