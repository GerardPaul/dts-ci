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
				"header" => 'All Documents',
				"userType" => $session_data['userType']
			);
			if($session_data['userType']=='RD'){$this->load->admin_template('rd_documents',$data);}
			else if($session_data['userType']=='SEC'){$this->load->admin_template('show_documents',$data);}
			else if($session_data['userType']=='ARD'){$this->load->admin_template('ard_documents',$data);}
			else{$this->load->admin_template('show_documents',$data);}
		}else{
			redirect('login', 'refresh');
		}
	}
	
	public function view($documentId = 0){
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
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
				"userType" => $session_data['userType']
			);
			$this->load->admin_template('view_document',$data);
		}else{
			redirect('login', 'refresh');
		}
	}

	public function add(){
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
	//edits
	public function show($documentId = 0){
		if($this->session->userdata('logged_in')){
			$session_data = $this->session->userdata('logged_in');
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
				"userType" => $session_data['userType']
			);
			$this->load->admin_template('view_document',$data);
		}else{
			redirect('login', 'refresh');
		}
	}
}