<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	var $title = 'Login';
	var $message = '';
	
	function __construct(){
		parent::__construct();
	}
	
	public function index()	{
		if($this->session->userdata('logged_in')){
			redirect('home','refresh');
		}else{
			$data['title'] = $this->title;
			$this->load->view('login_view', $data);
		}		
	}
	
	public function auth(){
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$this->load->library("UserFactory");
		$user = $this->userfactory->getUserLogin($username);
		if($user !== FALSE){
			$userId = $user->getId();
			$userUsername = $user->getUsername();
			$userPassword = $user->getPassword();
			$userSalt = $user->getSalt();
			$userUserType = $user->getUserType();
			
			$hashPassword = hash('sha256', $userSalt . $password . $userSalt);
			
			if($userUsername == $username && $userPassword == $hashPassword){
				$sess_array = array(
					 'id' => $userId,
					 'username' => $userUsername,
					 'userType' => $userUserType
				   );
				$this->session->set_userdata('logged_in', $sess_array);
				redirect('home','refresh');
			}else{
				$this->message = "Invalid username or password!";
				$data = array(
					"title" => $this->title,
					"message" => $this->message
				);
				$this->load->view('login_view', $data);
			}
		}else{
			$this->message = "Invalid username or password!";
			$data = array(
				"title" => $this->title,
				"message" => $this->message
			);
			$this->load->view('login_view', $data);
		}
	}
}