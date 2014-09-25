<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document extends CI_Controller {
	
	var $title = 'Documents';
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
	
	private function error(){
		$data = array(
			"title" => 'Error 403',
			"header" => 'Error 403 Forbidden',
			"userType" => $this->userType
		);
		$this->load->admin_template('error_view',$data);
	}
	
	public function index()	{
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error();
			}else{
				$data = array(
					"title" => $this->title,
					"header" => 'All Documents',
					"userType" => $this->userType
				);
				if($this->userType=='RD'){ $this->rdIndex($data);}
				else if($this->userType=='SEC'){$this->adminIndex($data);}
				else if($this->userType=='ARD'){$this->ardIndex($data);}
				else{$this->adminIndex($data);}
			}
		}else{
			redirect('login', 'refresh');
		}
	}
	
	private function rdIndex($data){
		$this->load->library("RDDocumentFactory");
		$data['documents'] = $this->rddocumentfactory->getRDDocument();
		
		$this->load->admin_template('rd_documents',$data);
	}
	
	private function adminIndex($data){
		$this->load->library("DocumentFactory");
		$data['documents'] = $this->documentfactory->getDocument();
		
		$this->load->admin_template('show_documents',$data);
	}
	
	private function ardIndex($data){
		$this->load->library("ARDDocumentFactory");
		$data['documents'] = $this->arddocumentfactory->getARDDocument();
		
		$this->load->admin_template('ard_documents',$data);
	}
	
	public function view($documentId = 0){
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error();
			}else{
				if($this->userType=='RD' || $this->userType=='ARD'){
					$this->error();
				}else{
					$documentId = (int)$documentId;
					$this->load->library("DocumentFactory");
					$documents = $this->documentfactory->getDocument($documentId);
					$status = '';
					$stat = $documents->getStatus();
					if($stat=='Cancelled') $status = '<small class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="'.$stat.'" data-toggle="tooltip"></i></small>&nbsp;';
					else if($stat=='On-Going') $status = '<small class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="'.$stat.'" data-toggle="tooltip"></i></small>&nbsp;';
					else if($stat=='Compiled') $status = '<small class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="'.$stat.'" data-toggle="tooltip"></i></small>&nbsp;';
						
					$data = array(
						"documents" => $documents,
						"title" => 'Document Details',
						"header" => $status . $documents->getSubject() ,
						"userType" => $this->userType
					);
					$this->load->admin_template('view_document',$data);
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
				$this->error();
			}else{
				if($this->userType=='RD' || $this->userType=='ARD'){
					$this->error();
				}else{
					$attachment_path = 'No File.';
					$filename = date('ymds') . '-' . $_POST['referenceNumber'] . '-' . $_FILES['attachment']['name'];
					$config = array(
						'upload_path' => './upload/',
						'file_name' => $filename,
						'allowed_types' => 'gif|jpg|jpeg|png|pdf|txt',
						'max_size' => 2048,
					);
					$this->load->library('upload', $config);
					
					if(!$this->upload->do_upload('attachment')){
						$error = $this->upload->display_errors();
						echo $error;
					}else{
						$upload_data = $this->upload->data();
						if(isset($upload_data['full_path'])) {
							$attachment_path = $upload_data['full_path'];
						}else{
							$attachment_path = "No File.";
						}
					}
					if($this->session->userdata('logged_in')){
						$this->load->library("DocumentFactory");
						if($this->documentfactory->addDocument($_POST['subject'],$_POST['from'],$_POST['dueDate'],$attachment_path,$_POST['status'],$_POST['referenceNumber'],$_POST['dateReceived'])){
							redirect('admin/document');
						}else{
							echo "Failed!";
						}
					}else{
						redirect('login', 'refresh');
					}
				}
			}
		}else{
			redirect('login', 'refresh');
		}
	}
	//edits
	public function details($documentId = 0){
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error();
			}else{
				if($this->userType=='ADMIN' || $this->userType=='SEC' || $this->userType=='ARD'){
					$this->error();
				}else{
					$documentId = (int)$documentId;
					$this->load->library("RDDocumentFactory");
					$documents = $this->rddocumentfactory->getRDDocument($documentId);
					$status = '';
					$stat = $documents->getStatus();
					if($stat=='Cancelled') $status = '<small class="text-danger status"><i class="glyphicon glyphicon-remove-sign" title="'.$stat.'" data-toggle="tooltip"></i></small>&nbsp;';
					else if($stat=='On-Going') $status = '<small class="text-warning status"><i class="glyphicon glyphicon-info-sign" title="'.$stat.'" data-toggle="tooltip"></i></small>&nbsp;';
					else if($stat=='Compiled') $status = '<small class="text-success status"><i class="glyphicon glyphicon-ok-sign" title="'.$stat.'" data-toggle="tooltip"></i></small>&nbsp;';
						
					$data = array(
						"documents" => $documents,
						"title" => 'Document Details',
						"header" => $status . $documents->getSubject() ,
						"userType" => $this->userType
					);
					$this->load->admin_template('rd_view_document',$data);
				}
			}
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function receive($documentId = 0){
		$this->checkLogin();
		if($this->login){
			if($this->userType=='EMP'){
				$this->error();
			}else{
				if($this->userType=='ADMIN' || $this->userType=='SEC' || $this->userType=='ARD'){
					$this->error();
				}else{
					$documentId = (int)$documentId;
					$this->load->library("RDDocumentFactory");
					if($this->rddocumentfactory->updateReceived($documentId)){
						redirect('admin/document/details/'.$documentId);
					}else{
						echo "Failed!";
					}
				}
			}
		}
	}
}