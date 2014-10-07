<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI

class Home extends CI_Controller {
	
	var $title = 'Home';
	
	function __construct(){
		parent::__construct();
	}

	public function index(){
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$data = array(
				"username" => $session_data['username'],
				"title" => $this->title,
				"header" => 'Home',
				"userType" => $session_data['userType']
			);
			$this->load->template('home_view', $data);
		}else{
			redirect('login', 'refresh');
		}
	}

	public function logout(){
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('login', 'refresh');
	}

}

?>