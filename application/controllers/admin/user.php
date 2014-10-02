<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	
	var $title = 'Users';
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
					$this->load->library("UserFactory");
					$this->load->library("DivisionFactory");
					$data = array(
						"users" => $this->userfactory->getUser(),
						"divisions" => $this->divisionfactory->getDivision(),
						"title" => $this->title,
						"header" => 'All Users',
						"userType" => $this->userType
					);
					$this->load->admin_template('show_users',$data);
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
					$this->load->library("UserFactory");
					if($this->userfactory->addUser($_POST['firstname'],$_POST['lastname'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['userType'],$_POST['division'])){
						redirect('admin/user');
					}else{
						echo "Failed!";
					}
				}
			}
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function edit($id=0){
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error(403);
			}else{
				if($this->userType=='RD' || $this->userType=='ARD'){
					$this->error(403);
				}else{
					$id = (int)$id;
					$this->load->library("UserFactory");
					$user = $this->userfactory->getUser($id);
					if($user && $id>0){
						$this->load->library("DivisionFactory");
						$data = array(
							"divisions" => $this->divisionfactory->getDivision(),
							"user" => $user,
							"title" => $this->title,
							"header" => 'Edit User',
							"userType" => $this->userType
						);
						$this->load->admin_template('edit_user',$data);
					}else{
						$this->error(404);
					}
				}
			}
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function update($id=0){
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error(403);
			}else{
				if($this->userType=='RD' || $this->userType=='ARD'){
					$this->error(403);
				}else{
					$this->load->library("UserFactory");
					if($this->userfactory->updateUser($_POST['status'],$_POST['userId'],$_POST['firstname'],$_POST['lastname'],$_POST['email'],$_POST['username'],$_POST['password'],$_POST['userType'],$_POST['division'])){
						redirect('admin/user');
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