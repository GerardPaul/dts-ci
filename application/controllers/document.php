<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document extends CI_Controller {
	
	var $title = 'Documents';
	
	public function index()	{
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$this->load->library("DocumentFactory");
			$data = array(
				"documents" => $this->documentfactory->getDocument(),
				"title" => $this->title,
				"userType" => $session_data['userType']
			);
			$this->load->template('show_documents',$data);
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function show($documentId = 0){
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
			$documentId = (int)$documentId;
			$this->load->library("DocumentFactory");
			$data = array(
				"documents" => $this->documentfactory->getDocument($documentId),
				"title" => $this->title,
				"userType" => $session_data['userType']
			);
			$this->load->template('show_document',$data);
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function attachFile(){
		try{
			if($this->input->post("submit")){
				$this->load->library("app/uploader");
				$this->uploader->do_upload();
			}
			return $this->view();
		}
		catch(Exception $err){
			log_message("error",$err->getMessage());
			return show_error($err->getMessage());
		}
	}
	
	public function add(){
		if($this->session->userdata('logged_in')){
			$this->load->library("DocumentFactory");
			if($this->documentfactory->addDocument($_POST['subject'],$_POST['from'],$_POST['dueDate'],$_POST['attachment'],$_POST['status'],$_POST['referenceNumber'])){
				redirect('document');
			}else{
				echo "Failed!";
			}
		}else{
			redirect('login', 'refresh');
		}
	}
}